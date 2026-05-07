<?php
/**
 * Slotopol Game Launcher
 * JWT-based auth, SQLite user management, wallet sync, game init
 */
session_start();
define('DASH', '02071995admin');
require_once __DIR__ . '/../../' . DASH . '/services/database.php';

$SLOTOPOL_URL    = 'http://127.0.0.1:5001';
$SLOTOPOL_SQLITE = __DIR__ . '/../../slotopol/sqlite/slot-club.sqlite';
$SLOTOPOL_KEY    = 'CasaSlotAccessKey2024xJgM4NsbP3fs4k7vh0gfdkgGl8dJ';
$SLOTOPOL_CID    = 1;

$game = trim($_GET['game'] ?? '');
$mode = ($_GET['mode'] ?? 'real') === 'demo' ? 'demo' : 'real';
$uid  = intval($_SESSION['id_user'] ?? 0);

if (!$uid && $mode === 'real') {
    header('Location: /');
    exit;
}

// ── JWT generation (HS256, iss=slotopol) ──────────────────────────────────
function slotopol_jwt($uid, $secret, $ttl = 86400 * 30) {
    $h = rtrim(strtr(base64_encode('{"alg":"HS256","typ":"JWT"}'), '+/', '-_'), '=');
    $p = rtrim(strtr(base64_encode(json_encode([
        'uid' => (int)$uid, 'iss' => 'slotopol', 'exp' => time() + $ttl
    ])), '+/', '-_'), '=');
    $sig = rtrim(strtr(base64_encode(hash_hmac('sha256', "$h.$p", $secret, true)), '+/', '-_'), '=');
    return "$h.$p.$sig";
}

// ── Game alias lookup ─────────────────────────────────────────────────────
function name_to_alias($name) {
    static $map = null;
    if ($map === null) {
        $map = [
            // AGT
            'AI' => 'agt/ai', 'Tesla' => 'agt/tesla', 'Book of Set' => 'agt/book-of-set',
            'Pharaoh II' => 'agt/pharaoh-ii', 'Aladdin' => 'agt/aladdin',
            'Wild West' => 'agt/wild-west', 'Crown' => 'agt/crown',
            'Arabian Nights 2' => 'agt/arabian-nights-2', 'Casino' => 'agt/casino',
            'Cherry Hot' => 'agt/cherry-hot', 'Double Ice' => 'agt/double-ice',
            'Double Hot' => 'agt/double-hot', 'Egypt' => 'agt/egypt',
            'Extra Spin' => 'agt/extra-spin', 'Extra Spin II' => 'agt/extra-spin-ii',
            'Fruit Queen' => 'agt/fruit-queen', 'Gems' => 'agt/gems',
            '50 Gems' => 'agt/50-gems', 'Halloween' => 'agt/halloween',
            'Hot Clover' => 'agt/hot-clover', 'Ice Fruits' => 'agt/ice-fruits',
            'Mega Shine' => 'agt/mega-shine', 'Ice Fruits 6 Reels' => 'agt/ice-fruits-6-reels',
            'Ice Ice Ice' => 'agt/ice-ice-ice', '5 Hot Hot Hot' => 'agt/5-hot-hot-hot',
            'Ice Queen' => 'agt/ice-queen', 'Stalker' => 'agt/stalker',
            'Big Five' => 'agt/big-five', 'Arabian Nights' => 'agt/arabian-nights',
            'Grand Theft' => 'agt/grand-theft', 'Bitcoin' => 'agt/bitcoin',
            'Pirates Gold' => 'agt/pirates-gold', 'The Leprechaun' => 'agt/the-leprechaun',
            'Infinity Gems' => 'agt/infinity-gems', 'Jokers' => 'agt/jokers',
            'Happy Santa' => 'agt/happy-santa', 'Bigfoot' => 'agt/bigfoot',
            '100 Jokers' => 'agt/100-jokers', '50 Happy Santa' => 'agt/50-happy-santa',
            '40 Bigfoot' => 'agt/40-bigfoot', 'Lucky Slot' => 'agt/lucky-slot',
            'Merry Christmas' => 'agt/merry-christmas', 'Panda' => 'agt/panda',
            'Santa' => 'agt/santa', 'Seven Hot' => 'agt/seven-hot',
            // CT Interactive
            '100 Burning Brilliants' => 'ctinteractive/100-burning-brilliants',
            '20 Clovers Hot' => 'ctinteractive/20-clovers-hot',
            '20 Dice Party' => 'ctinteractive/20-dice-party',
            '20 Fruitata Wins' => 'ctinteractive/20-fruitata-wins',
            '20 Mega Fresh' => 'ctinteractive/20-mega-fresh',
            '20 Mega Slot' => 'ctinteractive/20-mega-slot',
            '20 Mega Star' => 'ctinteractive/20-mega-star',
            '20 Roosters' => 'ctinteractive/20-roosters',
            '20 Shining Coins' => 'ctinteractive/20-shining-coins',
            '20 Star Party' => 'ctinteractive/20-star-party',
            '30 Fruitata Wins' => 'ctinteractive/30-fruitata-wins',
            '30 Treasures' => 'ctinteractive/30-treasures',
            '40 Brilliants' => 'ctinteractive/40-brilliants',
            '40 Diamond Treasures' => 'ctinteractive/40-diamond-treasures',
            '40 Dice Treasures' => 'ctinteractive/40-dice-treasures',
            '40 Fruitata Wins' => 'ctinteractive/40-fruitata-wins',
            '40 Mega Slot' => 'ctinteractive/40-mega-slot',
            '40 Roosters' => 'ctinteractive/40-roosters',
            '40 Shining Coins' => 'ctinteractive/40-shining-coins',
            '40 Shining Jewels' => 'ctinteractive/40-shining-jewels',
            '40 Hell\'s Cherries' => 'ctinteractive/40-hells-cherries',
            '100 Shining Stars' => 'agt/100-shining-stars',
            // BetSoft
            '2 Million B.C.' => 'betsoft/2-million-bc',
            // Novomatic / EGT
            'Book of Ra' => 'novomatic/bookofra',
            'Crazy Monkey' => 'megajack/crazymonkey',
            // Keno
            'Fire Keno' => 'slotopol/fire-keno',
            'Keno Centurion' => 'slotopol/keno-centurion',
            'Keno Fast' => 'agt/keno-fast',
            // Canvas Slot fallback
            'CanvasSlot' => '',
        ];
    }
    if (isset($map[$name])) return $map[$name];
    // Auto-generate: lowercase, replace spaces with dashes
    return strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
}

// ── Slotopol SQLite user/wallet sync ─────────────────────────────────────
function slotopol_ensure_user($sqlite_path, $cid, $casino_uid, $wallet_coins, $mode) {
    if (!file_exists($sqlite_path)) return false;
    try {
        $db = new SQLite3($sqlite_path, SQLITE3_OPEN_READWRITE);
        $sl_uid = 1000 + $casino_uid;
        $email  = 'uid' . $casino_uid . '@casaslot.local';
        $secret = 'cs_' . md5('slot_' . $casino_uid . '_2024');

        // Ensure user exists
        $db->exec("INSERT OR IGNORE INTO user
            (uid, ctime, utime, email, secret, name, code, status, gal)
            VALUES ($sl_uid, datetime('now'), datetime('now'),
            " . $db->escapeString($email) . ",
            " . $db->escapeString($secret) . ",
            'Player$casino_uid', 0, 1, 0)");

        // Ensure props row
        $db->exec("INSERT OR IGNORE INTO props
            (cid, uid, ctime, utime, wallet, access, mrtp)
            VALUES ($cid, $sl_uid, datetime('now'), datetime('now'), 0, 1, 0)");

        // Set wallet
        if ($mode === 'demo') {
            $wallet_coins = 100000;
        }
        $db->exec("UPDATE props SET wallet=$wallet_coins, utime=datetime('now')
            WHERE cid=$cid AND uid=$sl_uid");

        $db->close();
        return $sl_uid;
    } catch (Exception $e) {
        return false;
    }
}

// ── Demo user (no casino login needed) ───────────────────────────────────
function slotopol_ensure_demo($sqlite_path, $cid) {
    if (!file_exists($sqlite_path)) return false;
    try {
        $db  = new SQLite3($sqlite_path, SQLITE3_OPEN_READWRITE);
        $sid = 9999;
        $db->exec("INSERT OR IGNORE INTO user
            (uid, ctime, utime, email, secret, name, code, status, gal)
            VALUES ($sid, datetime('now'), datetime('now'),
            'demo@casaslot.local', 'demo_secret_2024', 'Demo Player', 0, 1, 0)");
        $db->exec("INSERT OR IGNORE INTO props
            (cid, uid, ctime, utime, wallet, access, mrtp)
            VALUES ($cid, $sid, datetime('now'), datetime('now'), 100000, 1, 0)");
        $db->exec("UPDATE props SET wallet=100000, utime=datetime('now')
            WHERE cid=$cid AND uid=$sid");
        $db->close();
        return $sid;
    } catch (Exception $e) {
        return false;
    }
}

// ── Resolve casino user data & compute slotopol uid + wallet ─────────────
$casino_balance = 0;
$sl_uid = 0;

if ($mode === 'demo' && !$uid) {
    $sl_uid = slotopol_ensure_demo($SLOTOPOL_SQLITE, $SLOTOPOL_CID);
    if (!$sl_uid) { header('Location: /slot_canvas/'); exit; }
} elseif ($uid) {
    $res = $mysqli->query("SELECT saldo FROM usuarios WHERE id=$uid LIMIT 1");
    $row = $res ? $res->fetch_assoc() : [];
    $casino_balance = (float)($row['saldo'] ?? 0);

    if ($mode === 'real' && $casino_balance < 1) {
        header('Location: /?sem_saldo=1');
        exit;
    }

    $wallet_coins = ($mode === 'demo') ? 100000 : intval($casino_balance * 100);
    $sl_uid = slotopol_ensure_user($SLOTOPOL_SQLITE, $SLOTOPOL_CID, $uid, $wallet_coins, $mode);

    if (!$sl_uid) { header('Location: /slot_canvas/'); exit; }

    // Deduct from casino wallet (real mode)
    if ($mode === 'real' && $casino_balance > 0) {
        $mysqli->query("UPDATE usuarios SET saldo = 0 WHERE id = $uid");
        $_SESSION['slotopol_out']     = $casino_balance;
        $_SESSION['slotopol_uid']     = $uid;
        $_SESSION['slotopol_sl_uid']  = $sl_uid;
    }
} else {
    header('Location: /');
    exit;
}

// ── Generate JWT ─────────────────────────────────────────────────────────
$jwt = slotopol_jwt($sl_uid, $SLOTOPOL_KEY);

// ── Resolve game alias ────────────────────────────────────────────────────
$alias = '';
if ($game) {
    $alias = name_to_alias($game);
}
if (!$alias) {
    $alias = 'agt/ai'; // default
}

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
    'uid'   => $sl_uid,
    'alias' => $alias,
], $jwt);

$gid = intval($game_data['gid'] ?? 0);

if (!$gid) {
    // Try fallback alias
    $game_data = sp_post("$SLOTOPOL_URL/game/new", [
        'cid'   => $SLOTOPOL_CID,
        'uid'   => $sl_uid,
        'alias' => 'agt/ai',
    ], $jwt);
    $gid = intval($game_data['gid'] ?? 0);
    $alias = 'agt/ai';
}

if (!$gid) {
    header('Location: /slot_canvas/');
    exit;
}

// ── Store session & redirect ──────────────────────────────────────────────
$_SESSION['sp_jwt']    = $jwt;
$_SESSION['sp_gid']    = $gid;
$_SESSION['sp_uid']    = $sl_uid;
$_SESSION['sp_cid']    = $SLOTOPOL_CID;
$_SESSION['sp_alias']  = $alias;
$_SESSION['sp_mode']   = $mode;
$_SESSION['sp_game']   = $game;

$params = http_build_query([
    'gid'   => $gid,
    'alias' => $alias,
    'mode'  => $mode,
    'game'  => $game,
]);
header('Location: /slotopol_game?' . $params);
exit;
