# Docker 轻量伴学

这不是一门完整的 Docker 课程，只讲本项目每天真正会用到的部分。你可以一边学 PHP，一边自然恢复 Docker，不需要先学完 Docker。

本文负责建立直觉；需要查询某个字段的完整英文时，打开 [命令与缩写词典](command-glossary.md)。命令块中 `#` 开头的是注释，可以连同命令一起复制。

## 先回答：这个项目由 Docker 管理吗？

是。PHP、Composer、ThinkPHP、MySQL、Valkey、Node.js、OpenSearch、RabbitMQ 和 nginx 都在 Docker 中运行。本机主要负责保存代码和执行 `docker compose` 命令。

这样做的好处是：不用在 Windows 中分别安装和切换这些软件版本，项目环境也更容易重建。

## 只记住 5 个概念

| 名称 | 在本项目里可以理解为 |
| --- | --- |
| 镜像 Image | 已经装好 PHP 等软件的“环境模板” |
| 容器 Container | 根据模板启动的“正在运行的环境” |
| Compose | 按 `compose.yaml` 统一启动一组环境 |
| 挂载 Mount | Windows 项目文件与容器共享，所以修改代码后立即生效 |
| 数据卷 Volume | 专门保存数据库等数据，停止容器后数据仍然存在 |

现在不需要深入镜像分层、网络驱动或集群。遇到对应场景时再学。

## 每天只用这套流程

所有命令都在 `E:\code\github\learn-php` 下执行。

### 1. 开始学习

```powershell
# 创建并在后台启动 php 服务。-d = --detach（后台运行）
docker compose up -d php
```

意思：在后台启动名为 `php` 的学习环境。重复执行也没关系。

### 2. 运行 PHP 文件

```powershell
# exec = execute：在 php 服务中，用 PHP 程序执行示例文件
docker compose exec php php stages/00-environment/hello.php
```

意思：请正在运行的 `php` 容器执行这个文件。

以后只需要替换最后的文件路径。

#### Windows 按 Tab 补全的路径，为什么在容器里打不开？

补全发生在 Windows PowerShell 中，可能生成 `.\stages\...`；但最后打开文件的是 **Linux 容器里的 PHP**。在这条 `exec` 命令中，Docker 不会替你把这个参数里的 `\` 改成 Linux 的目录分隔符 `/`。

本项目把 Windows 的 `E:\code\github\learn-php` 共享为容器中的 `/workspace`，并将 `php` 服务的工作目录设为 `/workspace`。`.` 表示当前目录，所以 `./stages/...` 和 `stages/...` 都从这个目录开始找。问题是反斜杠，不是多了一个点。

| 传给容器内 PHP 的文件路径 | 本项目中的结果 |
| --- | --- |
| `.\stages\01-php-syntax\exercises\day02.php` | 失败：Linux 不把 `\` 当作目录分隔符 |
| `./stages/01-php-syntax/exercises/day02.php` | 成功：`./` 表示从当前目录查找 |
| `stages/01-php-syntax/exercises/day02.php` | 成功：同样是相对当前目录的路径 |

```powershell
# pwd = print working directory：查看 php 服务容器内的当前目录，不是 Windows 的目录
docker compose exec php pwd
# 保留 ./，使用正斜杠 /，验证“点不是问题”
docker compose exec php php ./stages/01-php-syntax/exercises/day02.php
```

预期：先显示 `/workspace`，再显示 `Notebook: 9900` 和 `bool(true)`。这些命令只读取信息，不更改文件或数据。按 Tab 后，把路径中所有 `\` 改为 `/` 即可；只删开头的 `.\`、或只给错误路径加引号都不能解决剩余反斜杠的问题。

`Could not open input file` 在这个例子中表示 PHP 已启动，但按收到的路径找不到脚本，不需要重装 Docker。若改完仍报错，先核对文件名、大小写和容器当前目录。不要把这个规则套到所有 PowerShell 命令：路径由 Windows 读取还是由容器内程序读取，决定应使用哪一套路径规则。

### 3. 查看状态

```powershell
# ps = process status：显示本项目容器状态
docker compose ps
```

看到 `Up` 或 `running` 表示正在运行；看到 `Exited` 表示已经停止。

### 4. 出错时先分清是哪一种输出

```powershell
# 查看容器主进程的最后 50 行；本项目 php 的主进程是 sleep，通常为空
docker compose logs --tail 50 php
```

CLI 脚本通过 exec 执行时，输出/异常就在执行命令的当前终端，不会自动进入上面的日志。thinkphp/web 的主进程是 Web 服务器，HTTP 访问记录看对应服务日志；ThinkPHP 详细异常还会写入阶段 07 的 app/runtime/log。

### 5. 学完后停止

```powershell
# 停止 php 服务，但不删除代码、容器或数据
docker compose stop php
```

停止不会删除代码、镜像或数据库数据。下次重新执行 `up -d php` 即可。

## 你现在可以做的 10 分钟练习

依次执行：

```powershell
# 查看当前正在运行的容器
docker compose ps

# 在 php 服务中显示 PHP 版本
docker compose exec php php --version

# 在 php 服务中执行第一个 PHP 示例
docker compose exec php php stages/00-environment/hello.php

# 查看 php 服务最后 10 行日志
docker compose logs --tail 10 php

# 停止 php 服务，不删除它
docker compose stop php

# 显示全部容器，包括已停止的容器；-a = --all
docker compose ps -a

# 再次在后台启动 php 服务；已有容器会被复用
docker compose up -d php
```

完成后只需理解三件事：

1. `up` 负责启动。
2. `exec` 负责在容器中做事。
3. `stop` 只停止，不删除。

## 随学习阶段逐步增加

不要一开始启动全部服务。

| 学到哪里 | 使用的命令 | 顺便学到的 Docker 内容 |
| --- | --- | --- |
| PHP 语法、OOP、测试 | `docker compose up -d php` | 容器、执行命令、挂载 |
| Web 基础 | `docker compose --profile web up -d` | 端口，浏览器如何访问容器 |
| MySQL | `docker compose --profile data up -d --wait php mysql` | 多容器通信、数据卷、健康状态 |
| Vue 前端 | `powershell -File scripts/setup-thinkphp.ps1 -WithFrontend` | 先安装依赖，再等待前后端健康 |
| ThinkPHP + Vue 后台 | `docker compose --profile thinkphp up -d` | 应用等待数据库健康、端口联调 |
| 第二阶段 Magento | `docker compose --profile magento up -d` | 多服务组合、日志和资源使用 |

这样 Docker 学习会跟着真实问题出现，不需要死记命令。

## 最小排错顺序

命令失败时按顺序检查：

```powershell
# 确认 Docker 客户端能否连接 Docker Engine
docker version

# 显示全部容器，包括已停止的容器
docker compose ps -a

# 将“服务名”替换为 php、mysql、thinkphp 等实际名称，查看最后 100 行日志
docker compose logs --tail 100 服务名

# 重新启动指定服务
docker compose restart 服务名
```

判断方法：

- `docker version` 失败：先确认 Docker Desktop 已启动。
- 服务不存在：确认当前目录是 `learn-php`。
- 服务已停止：看日志，再重新 `up -d`。
- 端口被占用：不要删除别的项目，修改 `.env` 中本项目的端口。

## 安全边界

下面是常用命令，但 exec 执行什么决定它是否安全，restart/down 会中断本项目服务：

```text
docker compose up
docker compose ps
docker compose exec
docker compose logs
docker compose restart
docker compose stop
docker compose down
```

暂时不要自行使用：

```text
docker compose down -v
docker system prune
docker volume rm
```

它们可能删除数据库数据、缓存或其他 Docker 资源。特别是 `down -v`，其中的 `-v` 表示连本项目数据卷一起删除。

## 什么时候算学会了

现阶段不要求能编写复杂 Dockerfile。满足下面四项就足够继续 PHP：

- 能启动和停止 PHP 环境。
- 能在容器中执行 PHP 与 Composer 命令。
- 能查看状态和日志。
- 知道数据保存在代码目录还是数据卷中。

更完整的版本、端口、ThinkPHP 和 Magento 命令需要时再查 [Docker 环境手册](docker-environment.md)。
