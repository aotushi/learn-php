# 阶段 06：Vue 与运行环境

按 [第 19–24 天](../../docs/lessons/04-integration.md) 学习。小示例只帮助观察 HTTP 和权限，不重新建设已有后台。已有项目的适配边界见 [联调说明](../../docs/vue-admin-integration.md)。

```powershell
# 准备后端和 Vue；-WithFrontend 是本脚本定义的开关
powershell -ExecutionPolicy Bypass -File scripts/setup-thinkphp.ps1 -WithFrontend
# 编译 Vue 单文件组件，检查构建
docker compose --profile frontend run --rm node npm run build
```

打开 http://localhost:15173，用 admin / LearnPhp123! 登录。viewer 同密码只读。浏览器请求同源 /api，由 Vite 代理给 ThinkPHP。源码在 src；.vue 文件编译模板，解决运行时字符串模板导致的空白页。安装依赖只在 Docker 内执行；命名卷隔离 Windows/Linux 的 node_modules。

## Linux 命令不必先进入交互终端

```powershell
# 在容器执行 pwd（print working directory），显示目录
docker compose exec php pwd
# 执行 ls（list）；-l 长格式，-a 包括隐藏文件
docker compose exec php ls -la
# 执行 ps（process status）；a/u/x 显示更多进程及用户详情
docker compose exec php ps aux
# 查看 PHP 配置位置；ini 是 initialization file
docker compose exec php php --ini
```

这样每条都能直接在 PowerShell 执行，不混用 Bash 语法。若主动执行 docker compose exec php bash，后续已进入 Linux 终端；用 exit 回到 PowerShell。

验收：真实完成新增、修改、删除；观察加载/错误/只读状态；区分 CLI 输出、Web 日志、应用日志。构建通过不算浏览器验收通过。
