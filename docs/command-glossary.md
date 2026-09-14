# 命令与缩写词典

这是一份随查随用的词典，不需要背诵。教程中的命令会在原地说明“这一行做什么”，本页负责解释命令字段的完整写法和英文含义。

## 怎样阅读一条命令

以这条命令为例：

```powershell
# 在 php 服务容器中执行 PHP 命令行程序，并显示版本
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
| `--profile` | profile，配置组 | 选择 Web、数据、前端、ThinkPHP 或 Magento 服务组 |
| `config` | configuration | 解析并显示 Compose 配置 |
| `--services` | services | 只列出服务名称 |
| `-e` | `--env` / environment | 临时给容器内命令设置环境变量 |
| `run` | run，非缩写 | 创建一次性容器执行命令，不要求服务已运行 |
| `--rm` | remove | 命令结束后删除这一个临时容器，不删除挂载源码或命名卷 |
| `-T` | `--no-TTY`；TTY 源自 teletypewriter | 禁用伪终端，适合脚本/自动检查 |
| `--wait` | wait | 等待服务运行或健康；是否真正可用取决于健康检查内容 |
| `build` | build | 构建镜像；不是编译每次 PHP 练习 |

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

## ThinkPHP 与 MySQL

| 字段 | 完整写法或英文 | 作用 |
| --- | --- | --- |
| `thinkphp` | Compose 服务名 | 本项目运行 ThinkPHP API 的容器服务 |
| `think` | ThinkPHP command entry | ThinkPHP 项目的命令入口文件 |
| `php think run` | run development server | 启动 ThinkPHP 本地开发服务器 |
| `route:list` | route list | 列出 URL、HTTP 方法和处理程序之间的路由 |
| MySQL | MySQL，产品名称 | 本项目使用的关系数据库，不是缩写 |
| LTS | Long-Term Support | 长期支持版本系列 |
| `mysql` | Compose 服务名和客户端程序名 | 根据所在位置表示数据库服务或 MySQL 命令行程序 |
| `-u` | `--user` | 指定数据库用户名 |
| `-p` | `--password` | 提供或提示输入数据库密码；命令行明文只用于本机学习 |

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
| `-ExecutionPolicy Bypass` | execution policy / bypass | 仅为新开的 PowerShell 进程跳过脚本策略；只运行信任的仓库脚本，不是提升管理员权限 |
| `-WithFrontend` | with frontend | 本项目脚本自定义开关，同时准备 Vue 示例；不是 Docker 内置选项 |
| `Test-Path` | test path | 检查路径是否存在，避免覆盖已有 .env |
| `Invoke-RestMethod` | invoke REST method | 发送 HTTP 请求并把 JSON 转成 PowerShell 对象 |
| `-Method/-Uri/-Headers/-ContentType/-Body` | method / Uniform Resource Identifier / headers / content type / body | 请求方法、地址、请求头、数据格式、请求体 |
| `npm` | npm，官方名称不是缩写 | 管理 Node.js 项目依赖和脚本 |
| `npm install` | install packages | 安装 `package.json` 中的依赖 |
| `npm ci` | clean install | 按锁文件干净安装；重建目标 node_modules，不更改锁文件 |
| `npm run dev` | development | 执行项目定义的开发服务器脚本 |
| `--filter` | filter | 按名字筛选 PHPUnit 测试；前面的 -- 把参数交给测试命令 |
| `npm run build` | run build script | 执行名为 `build` 的项目脚本 |
| `--` | option separator | 后面的选项传给项目脚本，而不是 npm 自己 |
| `--host 0.0.0.0` | host address | 监听容器所有网络接口，允许从容器外访问 |

## 常见技术缩写

| 缩写 | 完整英文 | 中文理解 |
| --- | --- | --- |
| HTTP | Hypertext Transfer Protocol | 超文本传输协议 |
| API | Application Programming Interface | 应用程序接口 |
| MVC | Model-View-Controller | 模型、视图、控制器的职责划分 |
| ORM | Object-Relational Mapping | 对象关系映射，用对象方式操作关系数据库 |
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
| RBAC | Role-Based Access Control | 基于角色的访问控制 |
| OOP | Object-Oriented Programming | 面向对象编程 |
| PSR-4 | PHP Standard Recommendation 4 | 命名空间到文件路径的自动加载规则 |
| CRUD | Create, Read, Update, Delete | 增、查、改、删 |
| CORS | Cross-Origin Resource Sharing | 跨源资源共享 |
| JWT | JSON Web Token | 一种令牌格式；本教程没有采用 |
| Bearer | bearer，持有者，非缩写 | 持有令牌的请求方可以以该身份访问 |
| SFC | Single-File Component | Vue 单文件组件，由构建工具编译模板 |
| TLS | Transport Layer Security | 传输层安全协议；HTTPS 使用它保护 HTTP 传输 |

没有把握时先看命令前的注释，再来本页查字段；不必一次记住所有英文。
