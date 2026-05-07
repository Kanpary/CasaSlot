<?php
/**
 * Slotopol Game Launcher
 * Uses admin uid=1 as universal slotopol player.
 * Casino balance is tracked in MariaDB; slotopol wallet is a shared large buffer.
 * JWT auth via HS256 with known access-key.
 */
session_start();
define('DASH', '02071995admin');
require_once __DIR__ . '/../../' . DASH . '/services/database.php';

$SLOTOPOL_URL = 'http://127.0.0.1:5001';
$SLOTOPOL_KEY = 'CasaSlotAccessKey2024xJgM4NsbP3fs4k7vh0gfdkgGl8dJ';
$SLOTOPOL_CID = 1;
$SLOTOPOL_UID = 1; // admin user — always registered at startup

$game = trim($_GET['game'] ?? '');
$mode = ($_GET['mode'] ?? 'real') === 'demo' ? 'demo' : 'real';
$uid  = intval($_SESSION['id_user'] ?? 0);

if (!$uid && $mode === 'real') {
    header('Location: /');
    exit;
}

// ── JWT (HS256, iss=slotopol) ─────────────────────────────────────────────
function slotopol_jwt($uid_slot, $key, $ttl = 86400 * 30) {
    $h = rtrim(strtr(base64_encode('{"alg":"HS256","typ":"JWT"}'), '+/', '-_'), '=');
    $p = rtrim(strtr(base64_encode(json_encode([
        'uid' => (int)$uid_slot, 'iss' => 'slotopol', 'exp' => time() + $ttl
    ])), '+/', '-_'), '=');
    $sig = rtrim(strtr(base64_encode(hash_hmac('sha256', "$h.$p", $key, true)), '+/', '-_'), '=');
    return "$h.$p.$sig";
}

// ── Game alias map ────────────────────────────────────────────────────────
function name_to_alias($name) {
    $map = [
        'AI'                     => 'agt/ai',
        'Tesla'                  => 'agt/tesla',
        'Book of Set'            => 'agt/book-of-set',
        'Pharaoh II'             => 'agt/pharaoh-ii',
        'Aladdin'                => 'agt/aladdin',
        'Wild West'              => 'agt/wild-west',
        'Crown'                  => 'agt/crown',
        'Arabian Nights 2'       => 'agt/arabian-nights-2',
        'Casino'                 => 'agt/casino',
        'Cherry Hot'             => 'agt/cherry-hot',
        'Double Ice'             => 'agt/double-ice',
        'Double Hot'             => 'agt/double-hot',
        'Egypt'                  => 'agt/egypt',
        'Extra Spin'             => 'agt/extra-spin',
        'Extra Spin II'          => 'agt/extra-spin-ii',
        'Fruit Queen'            => 'agt/fruit-queen',
        'Gems'                   => 'agt/gems',
        '50 Gems'                => 'agt/50-gems',
        'Halloween'              => 'agt/halloween',
        'Hot Clover'             => 'agt/hot-clover',
        'Ice Fruits'             => 'agt/ice-fruits',
        'Mega Shine'             => 'agt/mega-shine',
        'Ice Ice Ice'            => 'agt/ice-ice-ice',
        '5 Hot Hot Hot'          => 'agt/5-hot-hot-hot',
        'Ice Queen'              => 'agt/ice-queen',
        'Stalker'                => 'agt/stalker',
        'Big Five'               => 'agt/big-five',
        'Arabian Nights'         => 'agt/arabian-nights',
        'Grand Theft'            => 'agt/grand-theft',
        'Bitcoin'                => 'agt/bitcoin',
        'Pirates Gold'           => 'agt/pirates-gold',
        'The Leprechaun'         => 'agt/the-leprechaun',
        'Infinity Gems'          => 'agt/infinity-gems',
        'Jokers'                 => 'agt/jokers',
        'Happy Santa'            => 'agt/happy-santa',
        'Bigfoot'                => 'agt/bigfoot',
        '100 Jokers'             => 'agt/100-jokers',
        '50 Happy Santa'         => 'agt/50-happy-santa',
        '40 Bigfoot'             => 'agt/40-bigfoot',
        'Lucky Slot'             => 'agt/lucky-slot',
        'Merry Christmas'        => 'agt/merry-christmas',
        'Panda'                  => 'agt/panda',
        'Santa'                  => 'agt/santa',
        'Seven Hot'              => 'agt/seven-hot',
        '100 Burning Brilliants' => 'ctinteractive/100-burning-brilliants',
        '20 Clovers Hot'         => 'ctinteractive/20-clovers-hot',
        '20 Dice Party'          => 'ctinteractive/20-dice-party',
        '20 Fruitata Wins'       => 'ctinteractive/20-fruitata-wins',
        '20 Mega Fresh'          => 'ctinteractive/20-mega-fresh',
        '20 Mega Slot'           => 'ctinteractive/20-mega-slot',
        '20 Mega Star'           => 'ctinteractive/20-mega-star',
        '20 Roosters'            => 'ctinteractive/20-roosters',
        '20 Shining Coins'       => 'ctinteractive/20-shining-coins',
        '20 Star Party'          => 'ctinteractive/20-star-party',
        '30 Fruitata Wins'       => 'ctinteractive/30-fruitata-wins',
        '30 Treasures'           => 'ctinteractive/30-treasures',
        '40 Brilliants'          => 'ctinteractive/40-brilliants',
        '40 Diamond Treasures'   => 'ctinteractive/40-diamond-treasures',
        '40 Dice Treasures'      => 'ctinteractive/40-dice-treasures',
        '40 Fruitata Wins'       => 'ctinteractive/40-fruitata-wins',
        '40 Mega Slot'           => 'ctinteractive/40-mega-slot',
        '40 Roosters'            => 'ctinteractive/40-roosters',
        '40 Shining Coins'       => 'ctinteractive/40-shining-coins',
        '40 Shining Jewels'      => 'ctinteractive/40-shining-jewels',
        "40 Hell's Cherries"     => 'ctinteractive/40-hells-cherries',
        '100 Shining Stars'      => 'agt/100-shining-stars',
        '2 Million B.C.'         => 'betsoft/2-million-bc',
        'Fire Keno'              => 'slotopol/fire-keno',
        'Keno Centurion'         => 'slotopol/keno-centurion',
        'Keno Fast'              => 'agt/keno-fast',
    ];
    if (isset($map[$name])) return $map[$name];
    // Auto-derive: "Book of Ra" → "bookofra-like" → search known providers
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', trim($name, " \t-")));
    foreach (['agt', 'ctinteractive', 'betsoft', 'slotopol', 'novomatic'] as $p) {
        return "$p/$slug";
    }
    return "agt/$slug";
}

// ── Resolve casino balance ────────────────────────────────────────────────
$casino_balance = 0;
if ($uid) {
    $res = $mysqli->query("SELECT saldo FROM usuarios WHERE id=$uid LIMIT 1");
    $row = $res ? $res->fetch_assoc() : [];
    $casino_balance = (float)($row['saldo'] ?? 0);

    if ($mode === 'real' && $casino_balance < 0.01) {
        header('Location: /?sem_saldo=1');
        exit;
    }
}

// ── Generate JWT for admin uid=1 ─────────────────────────────────────────
$jwt = slotopol_jwt($SLOTOPOL_UID, $SLOTOPOL_KEY);

// ── Resolve game alias ────────────────────────────────────────────────────
$alias = $game ? name_to_alias($game) : 'agt/ai';

// ── Create game session via slotopol API ──────────────────────────────────
function sp_post($url, $body, $token) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($body),
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'Authorization: Bearer ' . $token],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 8,
        CURLOPT_CONNECTTIMEOUT => 3,
    ]);
    $r = curl_exec($ch);
    curl_close($ch);
    return json_decode($r ?: '{}', true);
}

$game_data = sp_post("$SLOTOPOL_URL/game/new", [
    'cid'   => $SLOTOPOL_CID,
    'uid'   => $SLOTOPOL_UID,
    'alias' => $alias,
], $jwt);

$gid = intval($game_data['gid'] ?? 0);

// Fallback to agt/ai if alias not found
if (!$gid) {
    $alias = 'agt/ai';
    $game_data = sp_post("$SLOTOPOL_URL/game/new", [
        'cid'   => $SLOTOPOL_CID,
        'uid'   => $SLOTOPOL_UID,
        'alias' => $alias,
    ], $jwt);
    $gid = intval($game_data['gid'] ?? 0);
}

if (!$gid) {
    // Slotopol unavailable — fall back to canvas slot
    header('Location: /slot_canvas/');
    exit;
}

// ── Get current slotopol wallet as session baseline ───────────────────────
$wallet_data = sp_post("$SLOTOPOL_URL/prop/wallet/get", [
    'cid' => $SLOTOPOL_CID,
    'uid' => $SLOTOPOL_UID,
], $jwt);
$baseline_wallet = intval($wallet_data['wallet'] ?? 0);

// ── Save session data ─────────────────────────────────────────────────────
$_SESSION['sp_jwt']             = $jwt;
$_SESSION['sp_gid']             = $gid;
$_SESSION['sp_uid']             = $SLOTOPOL_UID;
$_SESSION['sp_cid']             = $SLOTOPOL_CID;
$_SESSION['sp_alias']           = $alias;
$_SESSION['sp_mode']            = $mode;
$_SESSION['sp_game']            = $game;
$_SESSION['sp_casino_uid']      = $uid;
$_SESSION['sp_casino_balance']  = $casino_balance;
$_SESSION['sp_baseline_wallet'] = $baseline_wallet;

// Deduct casino balance for real-money sessions (will credit back on exit)
if ($mode === 'real' && $uid && $casino_balance > 0) {
    $mysqli->query("UPDATE usuarios SET saldo = 0 WHERE id = $uid");
}

$params = http_build_query([
    'gid'     => $gid,
    'alias'   => $alias,
    'mode'    => $mode,
    'game'    => $game,
    'balance' => intval($casino_balance * 100), // send initial balance in coins
]);
header('Location: /slotopol_game?' . $params);
exit;
