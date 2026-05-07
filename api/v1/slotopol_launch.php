<?php
/**
 * Slotopol Game Launcher
 * Creates/authenticates casino user in slotopol server, syncs wallet, redirects to game
 */
session_start();
define('DASH', '02071995admin');
require_once __DIR__ . '/../../' . DASH . '/services/database.php';

$SLOTOPOL = 'http://127.0.0.1:5001';
$game     = trim($_GET['game'] ?? '');
$mode     = ($_GET['mode'] ?? 'real') === 'demo' ? 'demo' : 'real';

$uid = intval($_SESSION['id_user'] ?? 0);

if (!$uid && $mode === 'real') {
    header('Location: /');
    exit;
}

// Build slotopol user credentials tied to casino user
if ($uid) {
    $res = $mysqli->query("SELECT saldo, mobile FROM usuarios WHERE id=$uid LIMIT 1");
    $u   = $res ? $res->fetch_assoc() : [];
    $casino_balance = (float)($u['saldo'] ?? 0);
    $sl_email = 'uid' . $uid . '@casaslot.local';
} else {
    $casino_balance = 0;
    $sl_email = 'demo_' . substr(md5(session_id()), 0, 12) . '@casaslot.local';
}
$sl_pass = md5('sp_' . $sl_email . '_CasaSlot2024');

function slotopol_post($url, $body, $token = null) {
    $ch = curl_init($url);
    $headers = ['Content-Type: application/json'];
    if ($token) $headers[] = 'Authorization: Bearer ' . $token;
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($body),
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_CONNECTTIMEOUT => 3,
    ]);
    $r = curl_exec($ch);
    $c = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$c, json_decode($r, true)];
}

function slotopol_put($url, $body, $token) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST  => 'PUT',
        CURLOPT_POSTFIELDS     => json_encode($body),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Authorization: Bearer ' . $token],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
    ]);
    $r = curl_exec($ch);
    curl_close($ch);
    return json_decode($r, true);
}

// Try signin
[$code, $data] = slotopol_post("$SLOTOPOL/api/auth/signin", ['email' => $sl_email, 'pass' => $sl_pass]);
$access_token = $data['access'] ?? null;

// If signin failed, try signup then signin
if (!$access_token) {
    slotopol_post("$SLOTOPOL/api/auth/signup", [
        'email' => $sl_email, 'pass' => $sl_pass, 'name' => 'User' . $uid
    ]);
    [$code2, $data2] = slotopol_post("$SLOTOPOL/api/auth/signin", ['email' => $sl_email, 'pass' => $sl_pass]);
    $access_token = $data2['access'] ?? null;
}

if (!$access_token) {
    // Slotopol unavailable — fall back to canvas slot
    header('Location: /slot_canvas/');
    exit;
}

// Sync wallet: set slotopol balance
if ($mode === 'demo') {
    $slot_coins = 100000; // demo credits
} else {
    $slot_coins = intval($casino_balance * 100); // convert to cents
    if ($slot_coins < 100) $slot_coins = 0;
    // Deduct from casino wallet
    if ($uid && $casino_balance > 0) {
        $mysqli->query("UPDATE usuarios SET saldo = 0 WHERE id = $uid");
        $_SESSION['slotopol_out']   = $casino_balance;
        $_SESSION['slotopol_uid']   = $uid;
        $_SESSION['slotopol_token'] = $access_token;
        $_SESSION['slotopol_email'] = $sl_email;
        $_SESSION['slotopol_pass']  = $sl_pass;
    }
}

slotopol_put("$SLOTOPOL/api/user/bank", ['fival' => $slot_coins], $access_token);

// Redirect to proxied slotopol UI
$game_param = $game ? '?game=' . urlencode($game) : '';
header('Location: /slotopol/' . $game_param);
exit;
