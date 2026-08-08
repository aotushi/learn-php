# 命令与缩写词典

这是一份随查随用的词典，不需要背诵。教程中的命令会在原地说明“这一行做什么”，本页负责解释命令字段的完整写法和英文含义。

## 怎样阅读一条命令

以这条命令为例：

```powershell
# 在名为 php 的服务中运行 PHP 程序，并显示版本
docker compose exec php php --version
```

从左到右阅读：

1. `docker`：调用 Docker 客户端。
2. `compose`：使用本目录的 `compose.yaml` 管理项目服务；不是缩写。
3. `exec`：`execute` 的缩写，表示“在已经运行的容器中执行”。
4. 第一个 `php`：Compose 中的服务名。
5. 第二个 `php`：容器中的 PHP 程序。
6. `--version`：要求程序显示版本；双短横线通常表示完整选项名。

因此，重复出现的 `php` 不是笔误：前一个回答“在哪个服务中”，后一个回答“运行什么程序”。

## Docker 与 Compose

| 字段 | 完整写法或英文 | 在本项目中的作用 |
| --- | --- | --- |
| `docker` | Docker | 与 Docker Engine 交互的命令 |
| `compose` | Compose，非缩写 | 按 `compose.yaml` 管理一组服务 |
| `up` | up，非缩写 | 创建并启动服务；已有服务会复用或更新 |
| `-d` | `--detach` | 后台运行，不持续占用当前终端 |
| `exec` | execute | 在已运行的服务容器中执行命令 |
| `ps` | process status | 列出本项目容器及其状态 |
| `-a` | `--all` | 显示全部，包括已经停止的容器 |
| `logs` | logs，非缩写 | 查看服务输出记录 |
| `--tail 20` | tail，末尾 | 只显示最后 20 行 |
| `stop` | stop，非缩写 | 停止容器，不删除容器和数据 |
| `restart` | restart，非缩写 | 先停止再启动指定服务 |
| `down` | down，非缩写 | 停止并移除本项目容器和网络，默认保留数据卷 |
| `-v` | `--volumes` | 连数据卷一起处理；与 `down` 合用会删除数据 |
| `--profile` | profile，配置组 | 选择 Web、数据库、前端或 Magento 服务组 |
| `config` | configuration | 解析并显示 Compose 配置 |
| `--services` | services | 只列出服务名称 |
| `-e` | `--env` / environment | 临时给容器内命令设置环境变量 |

## PHP 与 Composer

| 字段 | 完整写法或英文 | 作用 |
| --- | --- | --- |
| `PHP` | PHP: Hypertext Preprocessor | 本项目学习的编程语言；它是递归缩写 |
| `php 文件.php` | PHP CLI | 用 PHP 的命令行界面运行文件 |
| `CLI` | Command-Line Interface | 命令行界面 |
| `--version` | version | 显示程序版本 |
| `--ini` | initialization file | 显示 PHP 配置文件位置 |
| `composer install` | install dependencies | 按锁定文件安装 PHP 依赖 |
| `composer test` | Composer script: test | 运行项目在 `composer.json` 中定义的测试命令 |
| `PHPUnit` | PHP Unit Testing framework | PHP 自动测试工具 |

## Linux 排查命令

| 字段 | 完整写法或英文 | 作用 |
| --- | --- | --- |
| `bash` | Bourne Again Shell | 进入容器中的命令行环境 |
| `pwd` | print working directory | 显示当前目录 |
| `ls` | list | 列出目录内容 |
| `ls -l` | long format | 显示权限、所有者、大小等详细信息 |
| `ls -a` | all | 包括名称以 `.` 开头的隐藏项 |
| `ps` | process status | 查看进程 |
| `ps aux` | all、user-oriented、including no terminal | 以详细格式查看几乎所有进程 |
| `ip addr` | Internet Protocol address | 查看网络地址 |
| `ss` | socket statistics | 查看网络套接字 |
| `ss -lntp` | listening、numeric、TCP、process | 查看监听中的 TCP 端口及所属进程 |
| `df -h` | disk free、human-readable | 查看磁盘空间并使用易读单位 |
| `free -h` | free memory、human-readable | 查看内存并使用易读单位 |

## PowerShell 与前端命令

| 字段 | 完整写法或英文 | 作用 |
| --- | --- | --- |
| `cd` | change directory | 切换当前目录 |
| `Copy-Item` | copy item | 复制文件或目录 |
| `-Recurse` | recursive | 连同所有子目录一起复制 |
| `-File` | file | 指定要执行的 PowerShell 脚本文件 |
| `npm` | npm，官方名称不是缩写 | 管理 Node.js 项目依赖和脚本 |
| `npm install` | install packages | 安装 `package.json` 中的依赖 |
| `npm run build` | run build script | 执行名为 `build` 的项目脚本 |
| `--` | option separator | 后面的选项传给项目脚本，而不是 npm 自己 |
| `--host 0.0.0.0` | host address | 监听容器所有网络接口，允许从容器外访问 |

## 常见技术缩写

| 缩写 | 完整英文 | 中文理解 |
| --- | --- | --- |
| HTTP | Hypertext Transfer Protocol | 超文本传输协议 |
| API | Application Programming Interface | 应用程序接口 |
| REST | Representational State Transfer | 表述性状态转移 |
| JSON | JavaScript Object Notation | JavaScript 对象表示法 |
| SQL | Structured Query Language | 结构化查询语言 |
| PDO | PHP Data Objects | PHP 数据对象/数据库访问接口 |
| SKU | Stock Keeping Unit | 库存量单位/商品编码 |
| DNS | Domain Name System | 域名系统 |
| TCP | Transmission Control Protocol | 传输控制协议 |
| FPM | FastCGI Process Manager | FastCGI 进程管理器 |
| XSS | Cross-Site Scripting | 跨站脚本攻击 |
| CSRF | Cross-Site Request Forgery | 跨站请求伪造 |

没有把握时先看命令前的注释，再来本页查字段；不必一次记住所有英文。
