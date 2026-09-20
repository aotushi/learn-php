$ErrorActionPreference = 'Stop'

$ProjectRoot = Split-Path -Parent $PSScriptRoot
$EnvFile = Join-Path $ProjectRoot '.env'
$MagentoDirectory = Join-Path $ProjectRoot 'stages\10-magento-foundation\app'

function Get-DotEnvValue {
    param(
        [Parameter(Mandatory = $true)][string]$Name,
        [string]$Default = ''
    )

    $Match = Get-Content -LiteralPath $EnvFile | Where-Object {
        $_ -match ('^' + [regex]::Escape($Name) + '=')
    } | Select-Object -Last 1

    if (-not $Match) {
        return $Default
    }

    return ($Match -split '=', 2)[1].Trim().Trim('"').Trim("'")
}

if (-not (Test-Path -LiteralPath $EnvFile)) {
    Copy-Item -LiteralPath (Join-Path $ProjectRoot '.env.example') -Destination $EnvFile
    throw '已创建 .env。请填写 Magento Marketplace 公钥和私钥后重新运行。'
}

$MagentoVersion = Get-DotEnvValue -Name 'MAGENTO_VERSION' -Default '2.4.8-p5'
$PublicKey = Get-DotEnvValue -Name 'MAGENTO_PUBLIC_KEY'
$PrivateKey = Get-DotEnvValue -Name 'MAGENTO_PRIVATE_KEY'

if ([string]::IsNullOrWhiteSpace($PublicKey) -or [string]::IsNullOrWhiteSpace($PrivateKey)) {
    throw '请先在 .env 中填写 MAGENTO_PUBLIC_KEY 和 MAGENTO_PRIVATE_KEY。'
}

$AdminUser = Get-DotEnvValue -Name 'MAGENTO_ADMIN_USER' -Default 'admin'
$AdminPassword = Get-DotEnvValue -Name 'MAGENTO_ADMIN_PASSWORD' -Default 'Admin123456!'
$AdminEmail = Get-DotEnvValue -Name 'MAGENTO_ADMIN_EMAIL' -Default 'admin@example.com'
$DatabaseName = Get-DotEnvValue -Name 'MAGENTO_MYSQL_DATABASE' -Default 'magento'
$DatabaseUser = Get-DotEnvValue -Name 'MAGENTO_MYSQL_USER' -Default 'magento'
$DatabasePassword = Get-DotEnvValue -Name 'MAGENTO_MYSQL_PASSWORD' -Default 'magento-local-only'
$RabbitUser = Get-DotEnvValue -Name 'RABBITMQ_DEFAULT_USER' -Default 'magento'
$RabbitPassword = Get-DotEnvValue -Name 'RABBITMQ_DEFAULT_PASS' -Default 'magento-local-only'
$MagentoHostPort = Get-DotEnvValue -Name 'MAGENTO_HOST_PORT' -Default '8088'
$MagentoBaseUrl = "http://localhost:$MagentoHostPort/"

Push-Location $ProjectRoot
try {
    docker compose -f compose.magento.yaml build magento-php
    if ($LASTEXITCODE -ne 0) { throw 'PHP 镜像构建失败。' }

    $MagentoComposerFile = Join-Path $MagentoDirectory 'composer.json'
    if (-not (Test-Path -LiteralPath $MagentoComposerFile)) {
        if (Test-Path -LiteralPath $MagentoDirectory) {
            $ExistingItems = Get-ChildItem -Force -LiteralPath $MagentoDirectory
            if ($ExistingItems.Count -gt 0) {
                throw 'Magento app 目录不是空目录，且没有 composer.json。请先检查其中内容。'
            }
        }

        $env:COMPOSER_AUTH = (@{
            'http-basic' = @{
                'repo.magento.com' = @{
                    username = $PublicKey
                    password = $PrivateKey
                }
            }
        } | ConvertTo-Json -Depth 4 -Compress)

        docker compose -f compose.magento.yaml run --rm --no-deps -e COMPOSER_AUTH magento-php composer create-project `
            --repository-url=https://repo.magento.com/ `
            --no-interaction `
            "magento/project-community-edition=$MagentoVersion" `
            .
        if ($LASTEXITCODE -ne 0) { throw 'Magento 代码下载失败。' }
        Remove-Item Env:COMPOSER_AUTH -ErrorAction SilentlyContinue
    }

    docker compose -f compose.magento.yaml up -d --wait mysql valkey opensearch rabbitmq
    if ($LASTEXITCODE -ne 0) { throw 'Magento 依赖服务启动失败。' }

    $MagentoEnvFile = Join-Path $MagentoDirectory 'app\etc\env.php'
    if (-not (Test-Path -LiteralPath $MagentoEnvFile)) {
        docker compose -f compose.magento.yaml run --rm magento-php php bin/magento setup:install `
            "--base-url=$MagentoBaseUrl" `
            --db-host=mysql `
            "--db-name=$DatabaseName" `
            "--db-user=$DatabaseUser" `
            "--db-password=$DatabasePassword" `
            --backend-frontname=admin `
            --admin-firstname=Local `
            --admin-lastname=Admin `
            "--admin-email=$AdminEmail" `
            "--admin-user=$AdminUser" `
            "--admin-password=$AdminPassword" `
            --language=en_US `
            --currency=USD `
            --timezone=Asia/Shanghai `
            --use-rewrites=1 `
            --search-engine=opensearch `
            --opensearch-host=opensearch `
            --opensearch-port=9200 `
            --cache-backend=redis `
            --cache-backend-redis-server=valkey `
            --cache-backend-redis-port=6379 `
            --session-save=redis `
            --session-save-redis-host=valkey `
            --session-save-redis-port=6379 `
            --amqp-host=rabbitmq `
            --amqp-port=5672 `
            "--amqp-user=$RabbitUser" `
            "--amqp-password=$RabbitPassword" `
            --amqp-virtualhost=/
        if ($LASTEXITCODE -ne 0) { throw 'Magento 数据库安装失败。' }
    }

    docker compose -f compose.magento.yaml up -d --wait
    if ($LASTEXITCODE -ne 0) { throw 'Magento Web 服务启动失败。' }

    docker compose -f compose.magento.yaml exec magento-php php bin/magento cache:flush
    Write-Host ''
    Write-Host 'Magento 本地环境已就绪。'
    Write-Host "店铺：$MagentoBaseUrl"
    Write-Host "后台：${MagentoBaseUrl}admin"
    Write-Host "后台用户：$AdminUser"
}
finally {
    Remove-Item Env:COMPOSER_AUTH -ErrorAction SilentlyContinue
    Pop-Location
}
