#!/usr/bin/env bash
# Windows 绑定挂载可能带可执行权限；无论被 source 还是执行都载入官方函数。
source /usr/local/bin/docker-entrypoint.sh
# 独立执行时父进程的 SOCKET 不是环境变量，需要从服务器配置恢复。
SOCKET="${SOCKET:-$(mysql_get_config 'socket' mysqld)}"
# 限制标识符字符，避免把环境变量直接拼成任意 SQL。
if [[ ! "$MYSQL_DATABASE" =~ ^[A-Za-z0-9_]+$ || ! "$MYSQL_USER" =~ ^[A-Za-z0-9_]+$ ]]; then
    echo 'MYSQL_DATABASE and MYSQL_USER must contain only letters, digits and underscores.' >&2
    exit 1
fi
docker_process_sql --database="$MYSQL_DATABASE" < /opt/learning.sql
docker_process_sql <<SQL
CREATE DATABASE IF NOT EXISTS magento CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON magento.* TO '$MYSQL_USER'@'%';
SQL
