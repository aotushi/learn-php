<?php

declare(strict_types=1);

$name = filter_input(INPUT_GET, 'name', FILTER_UNSAFE_RAW);
$name = is_string($name) && trim($name) !== '' ? trim($name) : 'PHP 学习者';
$safeName = htmlspecialchars($name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

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
    <p>这个页面由 Docker 中的 PHP 生成。尝试输入 HTML，观察输出转义的效果。</p>
    <form method="get">
        <label for="name">姓名</label>
        <input id="name" name="name" required maxlength="80">
        <button type="submit">提交</button>
    </form>
</body>
</html>
