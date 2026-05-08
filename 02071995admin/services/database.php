<?php
date_default_timezone_set("America/Sao_Paulo");

if (!defined('SITE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    define('SITE_URL', $protocol . $host);
}

if (!defined('DATABASE_LOADED')) {
    // Mark as loaded immediately to prevent any secondary include from re-running this block
    define('DATABASE_LOADED', true);

    $bd = array(
        'local' => 'localhost',
        'usuario' => 'root',
        'senha' => '',
        'banco' => 'casino',
        'socket' => '/tmp/mysql_run/mysql.sock'
    );

    // Retry connection up to 15 times with 1-second intervals (~15s window)
    $mysqli = null;
    $db_error = null;
    for ($attempt = 1; $attempt <= 15; $attempt++) {
        try {
            $conn = new mysqli(null, $bd['usuario'], $bd['senha'], $bd['banco'], 3307, $bd['socket']);
            if (!$conn->connect_errno) {
                $mysqli = $conn;
                break;
            }
            $db_error = $conn->connect_error;
        } catch (Exception $e) {
            $db_error = $e->getMessage();
        }
        if ($attempt < 15) sleep(1);
    }

    if ($mysqli === null) {
        error_log("Database connection failed after 15 attempts: " . $db_error);
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $is_api = (
            strpos($uri, '/hall/') === 0 ||
            strpos($uri, '/ajax/') !== false ||
            (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
            (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
        );
        if ($is_api) {
            // Return HTTP 200 with JSON error so the SPA handles it gracefully
            // (non-200 codes cause "HTTP Error 500/503" dialogs in the SPA)
            header('Content-Type: application/json');
            echo json_encode(['code' => 0, 'data' => [], 'success' => false, 'failed' => true, 'msg' => 'Servidor iniciando, aguarde...', 'timestamp' => round(microtime(true) * 1000)]);
        } else {
            header('Content-Type: text/html; charset=UTF-8');
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Aguarde...</title>'
                . '<meta http-equiv="refresh" content="5">'
                . '<style>body{background:#1a1c23;color:#fff;font-family:sans-serif;display:flex;align-items:center;justify-content:center;height:100vh;margin:0;}'
                . '.box{text-align:center;padding:40px;}</style></head>'
                . '<body><div class="box"><h2>Iniciando servidor...</h2>'
                . '<p>Aguarde, o banco de dados está carregando. A página será recarregada automaticamente.</p>'
                . '<p style="opacity:.5;font-size:12px">Recarregando em 5 segundos...</p>'
                . '</div></body></html>';
        }
        exit;
    }

    if (!$mysqli->set_charset("utf8mb4")) {
        $mysqli->set_charset("utf8");
    }

    // Check for table collation only if connection is successful
    try {
        $res = $mysqli->query("SELECT T.table_collation FROM information_schema.TABLES T WHERE T.table_schema = DATABASE() AND T.table_name = 'config' LIMIT 1");
        if ($res) {
            $row = $res->fetch_assoc();
            if ($row && isset($row['table_collation']) && strpos($row['table_collation'], 'utf8mb4') === false) {
                $mysqli->query("ALTER TABLE `config` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            }
        }
    } catch (Exception $e) {
        // Ignore collation check errors
    }
}
?>
