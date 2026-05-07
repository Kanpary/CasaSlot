<?php
ini_set('display_errors', 0);
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

$src = isset($_GET['src']) ? trim($_GET['src']) : '';

function isValidUrl($url) {
    if (empty($url)) return false;
    $u = parse_url($url);
    if (!$u) return false;
    if (!in_array(strtolower($u['scheme'] ?? ''), ['http','https'])) return false;
    return true;
}

// Suporta base64 do launch_url se vier como src_b64
if (!$src && isset($_GET['src_b64'])) {
    $decoded = base64_decode($_GET['src_b64'], true);
    if ($decoded !== false) {
        $src = trim($decoded);
    }
}

if (!isValidUrl($src)) {
    http_response_code(400);
    echo '<!doctype html><html><head><meta charset="utf-8"><title>URL inválida</title></head><body style="font-family:system-ui;margin:2rem;">URL do jogo inválida.</body></html>';
    exit;
}
?>
<!doctype html>
<html lang="pt">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Jogo</title>
    <style>
      html, body { height: 100%; width: 100%; margin: 0; background: #000; }
      .wrap { position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; background: #000; }
      iframe { border: 0; width: 100%; height: 100%; }
      .spinner { position: absolute; width: 48px; height: 48px; border: 4px solid #ffffff33; border-top-color: #fff; border-radius: 50%; animation: spin 1s linear infinite; }
      @keyframes spin { to { transform: rotate(360deg); } }
    </style>
  </head>
  <body>
    <div class="wrap">
      <div class="spinner" id="spinner"></div>
      <iframe id="gameFrame"
        src="<?php echo htmlspecialchars($src, ENT_QUOTES, 'UTF-8'); ?>"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; fullscreen; gyroscope; picture-in-picture"
        allowfullscreen>
      </iframe>
    </div>
    <script>
      const frame = document.getElementById('gameFrame');
      const spinner = document.getElementById('spinner');
      let hideSpinner = () => { spinner.style.display = 'none'; };
      frame.addEventListener('load', hideSpinner);
      // Fallback: oculta após 5s mesmo se não emitir load
      setTimeout(hideSpinner, 5000);
    </script>
  </body>
</html>

