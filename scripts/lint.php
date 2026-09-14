<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$iterator = new RecursiveIteratorIterator(
    new RecursiveCallbackFilterIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS),
        static fn (SplFileInfo $file): bool => !$file->isDir()
            || !in_array($file->getFilename(), ['vendor', 'node_modules', '.git', 'runtime', 'dist'], true),
    ),
);

$failed = false;
$count = 0;

foreach ($iterator as $file) {
    $path = str_replace('\\', '/', $file->getPathname());

    if (
        $file->getExtension() !== 'php'
        || str_contains($path, '/vendor/')
        || str_contains($path, '/runtime/')
        || str_contains($path, '/10-magento-foundation/app/')
    ) {
        continue;
    }

    ++$count;
    passthru(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file->getPathname()), $exitCode);
    $failed = $failed || $exitCode !== 0;
}

printf("Checked %d PHP files.\n", $count);
exit($failed ? 1 : 0);
