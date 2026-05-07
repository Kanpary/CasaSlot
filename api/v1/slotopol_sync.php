<?php
/**
 * Slotopol wallet sync-back
 * Receives final casino_coins from game UI → credits to MariaDB saldo
 */
session_start();
define('DASH', '02071995admin');
require_once __DIR__ . '/../../' . DASH . '/services/database.php';

header('Content-Type: application/json');

$casino_uid = intval($_SESSION['sp_casino_uid'] ?? 0);
$mode       = $_SESSION['sp_mode'] ?? 'real';

// Parse POST body
$body = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];

// For non-real sessions or missing uid, noop
if (!$casino_uid || $mode !== 'real') {
    echo json_encode(['ok' => true, 'msg' => 'noop']);
    exit;
}

// casino_coins: final balance in coins (1 coin = R$0.01)
$casino_coins = isset($body['casino_coins']) ? intval($body['casino_coins']) : null;

if ($casino_coins === null) {
    echo json_encode(['ok' => false, 'msg' => 'missing casino_coins']);
    exit;
}

$casino_coins = max(0, $casino_coins);
$brl = round($casino_coins / 100, 2);

// Credit balance back to MariaDB (set absolute value, not delta)
$brl_safe = floatval($brl);
$mysqli->query("UPDATE usuarios SET saldo = $brl_safe WHERE id = $casino_uid");

// Clear session keys
foreach ([
    'sp_jwt', 'sp_gid', 'sp_uid', 'sp_cid', 'sp_alias', 'sp_mode',
    'sp_game', 'sp_casino_uid', 'sp_casino_balance', 'sp_baseline_wallet',
] as $k) {
    unset($_SESSION[$k]);
}

echo json_encode(['ok' => true, 'credited_brl' => $brl, 'casino_uid' => $casino_uid]);
