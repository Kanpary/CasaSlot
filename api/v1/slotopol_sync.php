<?php
/**
 * Sync slotopol wallet back to casino wallet when user returns from game
 */
session_start();
header('Content-Type: application/json');
define('DASH', '02071995admin');
require_once __DIR__ . '/../../' . DASH . '/services/database.php';

$uid          = intval($_SESSION['slotopol_uid'] ?? 0);
$balance_out  = (float)($_SESSION['slotopol_out'] ?? 0);
$sl_email     = $_SESSION['slotopol_email'] ?? '';
$sl_pass      = $_SESSION['slotopol_pass'] ?? '';

if (!$uid || !$sl_email) {
    echo json_encode(['status' => 'ok', 'synced' => false]);
    exit;
}

$SLOTOPOL = 'http://127.0.0.1:5001';

// Re-authenticate
$ch = curl_init("$SLOTOPOL/api/auth/signin");
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode(['email' => $sl_email, 'pass' => $sl_pass]),
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 5,
]);
$r    = curl_exec($ch);
curl_close($ch);
$d    = json_decode($r, true);
$tok  = $d['access'] ?? null;

if (!$tok) {
    // Cannot reach slotopol — restore original balance
    $stmt = $mysqli->prepare("UPDATE usuarios SET saldo = saldo + ? WHERE id = ?");
    $stmt->bind_param("di", $balance_out, $uid);
    $stmt->execute();
    unset($_SESSION['slotopol_out'], $_SESSION['slotopol_uid'], $_SESSION['slotopol_token'],
          $_SESSION['slotopol_email'], $_SESSION['slotopol_pass']);
    echo json_encode(['status' => 'ok', 'synced' => true, 'balance' => $balance_out, 'note' => 'restored']);
    exit;
}

// Get current slotopol balance
$ch = curl_init("$SLOTOPOL/api/user/bank");
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $tok],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 5,
]);
$r  = curl_exec($ch);
curl_close($ch);
$bd = json_decode($r, true);

$slot_coins = intval($bd['coins'] ?? $bd['fival'] ?? 0);
$new_balance = round($slot_coins / 100, 2); // convert cents back to BRL

// Update casino wallet
$stmt = $mysqli->prepare("UPDATE usuarios SET saldo = ? WHERE id = ?");
$stmt->bind_param("di", $new_balance, $uid);
$stmt->execute();

// Zero out slotopol wallet
$ch = curl_init("$SLOTOPOL/api/user/bank");
curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST  => 'PUT',
    CURLOPT_POSTFIELDS     => json_encode(['fival' => 0]),
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Authorization: Bearer ' . $tok],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 5,
]);
curl_exec($ch);
curl_close($ch);

unset($_SESSION['slotopol_out'], $_SESSION['slotopol_uid'], $_SESSION['slotopol_token'],
      $_SESSION['slotopol_email'], $_SESSION['slotopol_pass']);

echo json_encode(['status' => 'ok', 'synced' => true, 'balance' => $new_balance]);
