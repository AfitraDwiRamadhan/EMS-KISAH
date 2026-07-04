<?php

// Pastikan direktori write-temp ada di /tmp untuk Vercel Serverless
if (isset($_SERVER['VERCEL_URL']) || str_starts_with(dirname(__DIR__), '/var/task')) {
    if (!is_dir('/tmp/views')) {
        @mkdir('/tmp/views', 0755, true);
    }
    if (!is_dir('/tmp/sessions')) {
        @mkdir('/tmp/sessions', 0755, true);
    }
    if (!is_dir('/tmp/bootstrap/cache')) {
        @mkdir('/tmp/bootstrap/cache', 0755, true);
    }
}

try {
    // Forward Vercel request to Laravel public/index.php
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    error_log('FATAL EXCEPTION TRACE: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
    echo '<h1>Fatal Exception</h1>';
    echo '<pre>' . htmlspecialchars($e->getMessage()) . "\n" . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    exit(1);
}
