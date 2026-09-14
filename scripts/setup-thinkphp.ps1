# 默认只准备后端；-WithFrontend 同时准备小型 Vue 联调示例。
param([switch]$WithFrontend)
$ErrorActionPreference = 'Stop'
$ProjectRoot = Split-Path -Parent $PSScriptRoot

function Invoke-Docker {
    $DockerArguments = $args
    # Windows PowerShell 5.1 把原生命令的 stderr 当成 PowerShell 错误。
    # 允许显示输出，随后用退出码判断结果。
    $PreviousPreference = $ErrorActionPreference
    $ErrorActionPreference = 'Continue'
    try {
        & docker @DockerArguments
        if ($LASTEXITCODE -ne 0) { throw "Docker command failed (exit $LASTEXITCODE). Check the output above." }
    } finally { $ErrorActionPreference = $PreviousPreference }
}

Push-Location $ProjectRoot
try {
    if (-not (Get-Command docker -ErrorAction SilentlyContinue)) { throw '请先安装并打开 Docker Desktop。' }
    Invoke-Docker info --format '{{.ServerVersion}}'
    # image ls 找不到镜像时正常返回空值，不依赖 inspect 失败时的 stderr。
    $Image = Invoke-Docker image ls --quiet learn-php:8.4
    if (-not $Image) { Invoke-Docker compose build php }
    Invoke-Docker compose up -d php
    Invoke-Docker compose exec -T php composer install --no-interaction
    Invoke-Docker compose run --rm --workdir /workspace/stages/07-thinkphp-foundation/app php composer install --no-interaction
    Invoke-Docker compose --profile thinkphp up -d --wait mysql
    Invoke-Docker compose exec -T php php scripts/setup-data.php
    Invoke-Docker compose --profile thinkphp up -d --wait thinkphp
    $PortMapping = Invoke-Docker compose port thinkphp 8000
    $ThinkPhpPort = ($PortMapping | Select-Object -First 1).Split(':')[-1]
    $Health = Invoke-RestMethod "http://localhost:$ThinkPhpPort/api/health"
    if (-not $Health.success) { throw '健康检查失败。' }
    Write-Host "ThinkPHP ready: http://localhost:$ThinkPhpPort/api/health"
    if ($WithFrontend) {
        Invoke-Docker compose --profile frontend run --rm node npm ci
        Invoke-Docker compose --profile frontend up -d --wait frontend
        $FrontendMapping = Invoke-Docker compose port frontend 5173
        $FrontendPort = ($FrontendMapping | Select-Object -First 1).Split(':')[-1]
        Write-Host "Vue demo: http://localhost:$FrontendPort"
    }
    Write-Host 'Local learning accounts: admin / viewer; password: LearnPhp123!'
} finally { Pop-Location }
