<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$base = __DIR__;
$file = $base . $uri;

// Android-only: block non-Android browsers (except admin, API and assets)
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isAndroid = stripos($ua, 'Android') !== false;
$isAsset = preg_match('/\.(js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|mp3|mp4|json)$/i', $uri);
$isApi = preg_match('/^\/(hall|ggpix|slotopol|slot_canvas|gold_api|infinitysoft_api|igamewin|ppclone|drakon_api|playfiver|callbackpayment)/', $uri);
$isAdmin = strpos($uri, '/02071995admin') === 0;
if (!$isAndroid && !$isAsset && !$isApi && !$isAdmin) {
    http_response_code(200);
    echo '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Acesso exclusivo Android</title><style>body{margin:0;background:#0d0d1a;color:#fff;font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;text-align:center}.box{padding:2rem}.icon{font-size:4rem}.title{font-size:1.5rem;margin:.5rem 0;color:#f0a500}.sub{color:#aaa;font-size:.95rem}</style></head><body><div class="box"><div class="icon">🤖</div><h1 class="title">Disponível apenas para Android</h1><p class="sub">Acesse pelo seu celular Android para jogar.</p></div></body></html>';
    return;
}

// Canvas Slot routes
if (preg_match('/^\/slot_canvas\/api/', $uri)) {
    require $base . '/slot_canvas/api.php';
    return;
}
if (preg_match('/^\/slot_canvas/', $uri)) {
    require $base . '/slot_canvas/index.php';
    return;
}

// Slotopol game launch (bridge: creates slotopol session, syncs casino wallet)
if (preg_match('/^\/slotopol_launch/', $uri)) {
    require $base . '/api/v1/slotopol_launch.php';
    return;
}

// Slotopol game UI (HTML5 slot frontend)
if (preg_match('/^\/slotopol_game/', $uri)) {
    require $base . '/api/v1/slotopol_game.php';
    return;
}

// Slotopol wallet sync-back (called when user returns from game)
if (preg_match('/^\/slotopol_sync/', $uri)) {
    require $base . '/api/v1/slotopol_sync.php';
    return;
}

// Slotopol reverse proxy — exposes slotopol server at /slotopol/*
if (preg_match('/^\/slotopol(\/.*)?$/', $uri, $m)) {
    $slotopol_path = $m[1] ?? '/';
    if (empty($slotopol_path)) $slotopol_path = '/';

    $query = $_SERVER['QUERY_STRING'] ?? '';
    $target = 'http://127.0.0.1:5001' . $slotopol_path . ($query ? '?' . $query : '');

    $method = $_SERVER['REQUEST_METHOD'];
    $body   = file_get_contents('php://input');

    $ch = curl_init($target);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_CONNECTTIMEOUT => 5,
    ]);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    } elseif ($method !== 'GET') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    }

    // Forward request headers
    $req_headers = [];
    foreach (getallheaders() as $k => $v) {
        $kl = strtolower($k);
        if ($kl !== 'host' && $kl !== 'connection') {
            $req_headers[] = "$k: $v";
        }
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $req_headers);

    $result      = curl_exec($ch);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $http_code   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_err    = curl_error($ch);
    curl_close($ch);

    if ($result === false) {
        http_response_code(503);
        echo json_encode(['error' => 'Slotopol indisponível', 'detail' => $curl_err]);
        return;
    }

    $resp_headers = substr($result, 0, $header_size);
    $resp_body    = substr($result, $header_size);

    http_response_code($http_code);
    foreach (explode("\r\n", $resp_headers) as $hdr) {
        $hl = strtolower($hdr);
        if (preg_match('/^(content-type|content-length|location|set-cookie|cache-control|access-control):/i', $hdr)) {
            header($hdr, false);
        }
    }
    // Allow cross-origin for the slotopol sub-app
    header('Access-Control-Allow-Origin: *');
    echo $resp_body;
    return;
}

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

// /hall/* — serve from hall/ static cache folder, fallback to api.php
if (preg_match('/^\/hall\//', $uri)) {
    $hallPath = $base . '/hall' . substr($uri, 5);

    if (file_exists($hallPath . '.php')) {
        chdir(dirname($hallPath . '.php'));
        require $hallPath . '.php';
        return;
    }

    $currency = $_GET['currency'] ?? 'BRL';
    $language = $_GET['language'] ?? 'pt';

    $candidates = [
        $hallPath . '/currency/' . $currency . '/language/' . $language . '.json',
        $hallPath . '/currency/' . $currency . '.json',
        $hallPath . '/language/' . $language . '.json',
        $hallPath . '/default.json',
        $hallPath,
    ];

    $categoryId = $_GET['categoryId'] ?? null;
    $platformId  = $_GET['platformId']  ?? null;
    $page        = $_GET['page']        ?? null;
    $type        = $_GET['type']        ?? null;
    $osType      = $_GET['osType']      ?? null;

    if ($categoryId && $platformId) {
        array_unshift($candidates,
            $hallPath . '/categoryId/' . $categoryId . '/currency/' . $currency . '/language/' . $language . '/platformId/' . $platformId . '.json'
        );
    }
    if ($page && $type) {
        array_unshift($candidates,
            $hallPath . '/currency/' . $currency . '/language/' . $language . '/page/' . $page . '/type/' . $type . '.json'
        );
    }
    if ($osType) {
        array_unshift($candidates,
            $hallPath . '/currency/' . $currency . '/osType/' . $osType . '.json'
        );
    }

    $candidates[] = $hallPath . '.json';

    foreach ($candidates as $candidate) {
        if (file_exists($candidate) && !is_dir($candidate)) {
            header('Content-Type: application/json; charset=utf-8');
            header('Access-Control-Allow-Origin: *');
            header('Cache-Control: no-cache');
            readfile($candidate);
            return;
        }
    }

    chdir($base . '/api/v1');
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
        return false;
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

// Admin paths
if (strpos($uri, '/02071995admin') === 0) {
    require $base . '/02071995admin/index.php';
    return;
}

// Everything else → main casino Vue SPA
require $base . '/index.php';
