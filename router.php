<?php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;
if (is_file($file)) return false;

// Vercel internal routes (e.g. Web Analytics) only exist in production
if (str_starts_with($path, '/_vercel/')) { http_response_code(404); exit; }

// Same security headers as Vercel, read from vercel.json, to test the CSP locally
$vercel = json_decode(file_get_contents(__DIR__ . '/vercel.json'), true);
foreach ($vercel['headers'][0]['headers'] ?? [] as $h) header($h['key'] . ': ' . $h['value']);

if (preg_match('#^/(pt/)?design-system/?$#', $path, $ds)) {
    $ds_lang = empty($ds[1]) ? 'en' : 'pt';
    require __DIR__ . '/templates/design-system.php';
    exit;
}

if (preg_match('#^/resume/?$#', $path)) {
    require __DIR__ . '/templates/resume.php';
    exit;
}

// clean URL: /portugues → portugues.php
if ($path === '/pt' || $path === '/pt/') {
    require __DIR__ . '/pt/index.php';
    exit;
}

if ($path !== '/' && $path !== '/index.php') {
    http_response_code(404);
    require __DIR__ . '/templates/404.php';
    exit;
}

require __DIR__ . '/index.php';
