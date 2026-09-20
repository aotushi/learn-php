<?php

declare(strict_types=1);

// filter_input 从请求读取字段；INPUT_GET 指 URL 查询参数，FILTER_UNSAFE_RAW 不会自动做安全转义。
$name = filter_input(INPUT_GET, 'name', FILTER_UNSAFE_RAW);
// is_string 检查字符串类型；&& 要求两边都成立且会短路；trim 去掉两端空白，!== 严格不相等。
// 合法且非空才采用输入，否则用默认名；不是直接相信浏览器提交的内容。
$name = is_string($name) && trim($name) !== '' ? trim($name) : 'PHP 学习者';
// htmlspecialchars 将 HTML 特殊字符转成文本实体；ENT_QUOTES 包括两种引号，ENT_SUBSTITUTE 替换无效编码。
// | 是位或，用于合并选项，不是逻辑 ||；UTF-8 是文本编码名称。
$safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

// header 设置响应头，必须在输出正文前调用；下方结束标记离开 PHP，HTML 中的短输出标签是 echo 简写。
header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP Web 基础</title>
    <style>
        body { max-width: 42rem; margin: 4rem auto; padding: 0 1rem; font: 18px/1.6 system-ui, sans-serif; }
        form { display: flex; gap: .5rem; }
        input, button { padding: .65rem; font: inherit; }
        input { flex: 1; }
    </style>
</head>
<body>
    <h1>你好，<?= $safeName ?>！</h1>
    <!-- <?php echo $safeName; ?> -->
    <p>这个页面由 Docker 中的 PHP 生成。尝试输入 HTML，观察输出转义的效果。</p>
    <form method="get">
        <label for="name">姓名</label>
        <input id="name" name="name" required maxlength="80">
        <button type="submit">提交</button>
    </form>
</body>
</html>
