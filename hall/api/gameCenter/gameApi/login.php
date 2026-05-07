<?php
/**
 * Game Launch Interceptor
 * Handles Slotopol game launches; delegates all other games to the main api.php
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, callContext');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

// Parse POST body (JSON or form)
$body    = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];
$gameid  = intval($body['gameid'] ?? $_POST['gameid'] ?? 0);
$is_demo = intval($body['user_type'] ?? $body['is_demo'] ?? 0);
$demo    = ($is_demo === 0);  // user_type=0 = formal/real, user_type=1 = demo; or is_demo=1 = demo

// Slotopol game ID range: 10000000–10000099
if ($gameid >= 10000000 && $gameid < 10000100) {

    // Slotopol alias map (game_code suffix → slotopol alias)
    $alias_map = [
        'slotopol-aztec'    => 'agt/ai',          // closest match
        'slotopol-book'     => 'agt/book-of-set',
        'slotopol-monkey'   => 'agt/cherry-hot',
        'slotopol-fruit'    => 'agt/fruit-queen',
        'slotopol-garage'   => 'agt/gems',
        'slotopol-haunter'  => 'agt/halloween',
        'slotopol-resident' => 'agt/jokers',
        'slotopol-shaman'   => 'agt/panda',
        'slotopol-sweet'    => 'agt/crown',
        // ID-based lookup (fallback)
        10000000            => 'agt/ai',
        10000001            => 'agt/book-of-set',
        10000002            => 'agt/cherry-hot',
        10000003            => 'agt/fruit-queen',
        10000004            => 'agt/gems',
        10000005            => 'agt/halloween',
        10000006            => 'agt/jokers',
        10000007            => 'agt/panda',
        10000008            => 'agt/crown',
    ];

    // Lookup game in DB to get game_code
    define('DASH', '02071995admin');
    require_once __DIR__ . '/../../../../' . DASH . '/services/database.php';

    $alias    = $alias_map[$gameid] ?? 'agt/ai';
    $gamename = '';

    $res = $mysqli->query("SELECT game_code, game_name FROM games WHERE id = $gameid LIMIT 1");
    if ($res && $row = $res->fetch_assoc()) {
        $gamename  = $row['game_name'];
        $gc        = $row['game_code'];
        if (isset($alias_map[$gc])) $alias = $alias_map[$gc];
    }

    $mode = $demo ? 'demo' : 'real';

    // Build launch URL
    $launch_url = '/slotopol_launch?' . http_build_query([
        'game'  => $gamename ?: 'AI',
        'mode'  => $mode,
        'alias' => $alias,
    ]);

    echo json_encode([
        'code'    => 0,
        'succeed' => true,
        'msg'     => 'success',
        'data'    => [
            'game_url'  => $launch_url,
            'game_name' => $gamename,
            'alias'     => $alias,
            'mode'      => $mode,
        ],
    ]);
    exit;
}

// Fallback: delegate to main encrypted api.php
chdir(dirname(__DIR__ . '/../../../../api/v1/api.php'));
require __DIR__ . '/../../../../api/v1/api.php';
