<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$base = __DIR__;
$file = $base . $uri;

// GGPix PIX generation endpoint
if (preg_match('/^\/ggpix\/gerar/', $uri)) {
    require $base . '/api/v1/ggpix_pix.php';
    return;
}
if (preg_match('/^\/ggpix\/status/', $uri)) {
    require $base . '/api/v1/ggpix_status.php';
    return;
}
if (preg_match('/^\/ggpix\/saque/', $uri)) {
    require $base . '/api/v1/ggpix_saque.php';
    return;
}
// GGPix webhook callback
if (preg_match('/^\/callbackpayment\/ggpix/', $uri)) {
    require $base . '/callbackpayment/ggpix.php';
    return;
}

// API rewrites matching .htaccess
if (preg_match('/^\/hall\//', $uri)) {
    require $base . '/api/v1/api.php';
    return;
}
if (preg_match('/^\/gold_api/', $uri) || preg_match('/^\/infinitysoft_api/', $uri)) {
    require $base . '/callback/game_callback.php';
    return;
}
if (preg_match('/^\/igamewin/', $uri)) {
    $cb = $base . '/callback/igamewin.php';
    if (file_exists($cb)) { require $cb; return; }
}
if (preg_match('/^\/ppclone/', $uri)) {
    $cb = $base . '/callback/ppclone.php';
    if (file_exists($cb)) { require $cb; return; }
}
if (preg_match('/^\/drakon_api/', $uri)) {
    $cb = $base . '/callback/drakon.php';
    if (file_exists($cb)) { require $cb; return; }
}
if (preg_match('/^\/playfiver\/webhook/', $uri)) {
    $cb = $base . '/callback/playfiver.php';
    if (file_exists($cb)) { require $cb; return; }
}

// Try the exact file first
if (file_exists($file) && !is_dir($file)) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if ($ext !== 'php') {
        return false; // PHP built-in server serves static files automatically
    }
    chdir(dirname($file));
    require $file;
    return;
}

// Try appending .php if not present
if (!str_ends_with($uri, '.php') && file_exists($file . '.php') && !is_dir($file . '.php')) {
    chdir(dirname($file . '.php'));
    require $file . '.php';
    return;
}

// Directory — look for index.php inside
if (is_dir($file)) {
    $index = rtrim($file, '/') . '/index.php';
    if (file_exists($index)) {
        chdir(dirname($index));
        require $index;
        return;
    }
}

// Admin paths: any unknown URL under /02071995admin/ → admin index.php
if (strpos($uri, '/02071995admin') === 0) {
    require $base . '/02071995admin/index.php';
    return;
}

// Everything else → main casino Vue SPA
require $base . '/index.php';
