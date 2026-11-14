<?php

// Development router: php -S 127.0.0.1:8000 -t public tools/saas-router.php
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$public = realpath(__DIR__ . '/../public');
$file = realpath($public . '/' . ltrim($path, '/'));
if (!preg_match('#^/(storage|files|uploads)(/|$)#', $path) && $file
    && strpos($file, $public . DIRECTORY_SEPARATOR) === 0 && is_file($file)
    && strtolower(pathinfo($file, PATHINFO_EXTENSION)) !== 'php') {
    return false;
}
require $public . '/index.php';
