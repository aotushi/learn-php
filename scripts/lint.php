<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root . '/stages', FilesystemIterator::SKIP_DOTS)
);

$failed = false;
$count = 0;

foreach ($iterator as $file) {
    $path = str_replace('\\', '/', $file->getPathname());

    if ($file->getExtension() !== 'php' || str_contains($path, '/07-magento-foundation/app/')) {
        continue;
    }

    ++$count;
    passthru(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file->getPathname()), $exitCode);
    $failed = $failed || $exitCode !== 0;
}

printf("Checked %d PHP files.\n", $count);
exit($failed ? 1 : 0);
