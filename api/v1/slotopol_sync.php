<?php
/**
 * Slotopol wallet sync-back: reads slotopol SQLite balance → credits casino wallet
 */
session_start();
define('DASH', '02071995admin');
require_once __DIR__ . '/../../' . DASH . '/services/database.php';

$SLOTOPOL_SQLITE = __DIR__ . '/../../slotopol/sqlite/slot-club.sqlite';
$SLOTOPOL_CID    = 1;

header('Content-Type: application/json');

$uid    = intval($_SESSION['id_user']   ?? 0);
$sl_uid = intval($_SESSION['sp_uid']    ?? 0);
$mode   = $_SESSION['sp_mode'] ?? 'real';

if (!$uid || !$sl_uid || $mode !== 'real') {
    echo json_encode(['ok' => false, 'msg' => 'noop']);
    exit;
}

// Read wallet from slotopol SQLite
$coins = 0;
try {
    if (file_exists($SLOTOPOL_SQLITE)) {
        $db  = new SQLite3($SLOTOPOL_SQLITE, SQLITE3_OPEN_READONLY);
        $row = $db->querySingle(
            "SELECT wallet FROM props WHERE cid=$SLOTOPOL_CID AND uid=$sl_uid LIMIT 1",
            true
        );
        $db->close();
        $coins = intval($row['wallet'] ?? 0);
    }
} catch (Exception $e) {
    // Fallback: use JS-provided value via POST body
    $body  = json_decode(file_get_contents('php://input') ?: '{}', true);
    $coins = intval($body['wallet'] ?? 0);
}

// Convert coins → BRL (1 coin = R$0.01)
$brl = round($coins / 100, 2);

if ($brl > 0) {
    $brl_safe = floatval($brl);
    $mysqli->query("UPDATE usuarios SET saldo = saldo + $brl_safe WHERE id = $uid");

    // Zero out slotopol wallet to prevent double-credit
    try {
        if (file_exists($SLOTOPOL_SQLITE)) {
            $db = new SQLite3($SLOTOPOL_SQLITE, SQLITE3_OPEN_READWRITE);
            $db->exec("UPDATE props SET wallet=0, utime=datetime('now')
                       WHERE cid=$SLOTOPOL_CID AND uid=$sl_uid");
            $db->close();
        }
    } catch (Exception $e) {}
}

// Clear session keys
foreach (['sp_jwt','sp_gid','sp_uid','sp_cid','sp_alias','sp_mode','sp_game',
          'slotopol_out','slotopol_uid','slotopol_sl_uid',
          'slotopol_token','slotopol_email','slotopol_pass'] as $k) {
    unset($_SESSION[$k]);
}

echo json_encode(['ok' => true, 'credited_brl' => $brl]);
