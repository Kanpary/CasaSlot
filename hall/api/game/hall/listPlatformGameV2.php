<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once __DIR__ . "/../../../../config.php";
include_once __DIR__ . '/../../../../' . DASH . '/services/database.php';

$currency   = $_GET['currency']   ?? 'BRL';
$language   = $_GET['language']   ?? 'pt';
$categoryId = $_GET['categoryId'] ?? '';
$platformId = $_GET['platformId'] ?? '';

if (empty($categoryId) || empty($platformId)) {
    http_response_code(400);
    echo json_encode([
        'code'      => 400,
        'error'     => 'Missing required parameters: categoryId and platformId',
        'failed'    => true,
        'msg'       => 'Bad Request',
        'success'   => false,
        'timestamp' => time()
    ]);
    exit();
}

// Map platformId to provider name (matches actual DB values)
$providerMap = [
    '200' => 'PG Soft',
    '301' => 'PP',
    '302' => 'JDB',
    '310' => 'JDB',
    '13'  => 'WG',
    '400' => 'CPGames',
    '401' => 'JILI',
    '402' => 'Askme Slots',
    '403' => 'SSR',
    '32'  => 'RedTiger',
    '37'  => 'PP',
    '500' => 'Slotopol',
];

try {
    $pid = (string)$platformId;

    if (isset($providerMap[$pid])) {
        $provider = $providerMap[$pid];
        $escaped  = $mysqli->real_escape_string($provider);
        $sql      = "SELECT id, game_code, game_name, banner, status, provider, popular, type, game_type, api
                     FROM games
                     WHERE status = 1 AND provider = '$escaped'
                     ORDER BY id ASC";
    } else {
        $sql = "SELECT id, game_code, game_name, banner, status, provider, popular, type, game_type, api
                FROM games
                WHERE status = 1
                ORDER BY id ASC";
    }

    $result = $mysqli->query($sql);

    if (!$result) {
        throw new Exception("Query failed: " . $mysqli->error);
    }

    $games = [];
    while ($row = $result->fetch_assoc()) {
        $banner = trim(str_replace('`', '', $row['banner'] ?? ''));

        $games[] = [
            "g0"  => (int)$row['id'],
            "g1"  => $row['game_name'],
            "g10" => (int)$platformId,
            "g11" => 2,
            "g2"  => $banner,
            "g3"  => 0,
            "g4"  => 0,
            "g5"  => (int)$row['popular'],
            "g6"  => 0,
            "g7"  => 1,
            "g8"  => (int)$row['status'],
            "g9"  => (int)$categoryId
        ];
    }

    echo json_encode([
        "code"      => 0,
        "data"      => $games,
        "failed"    => false,
        "msg"       => "success",
        "success"   => true,
        "timestamp" => round(microtime(true) * 1000)
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'code'      => 500,
        'error'     => 'Database error: ' . $e->getMessage(),
        'failed'    => true,
        'msg'       => 'Internal Server Error',
        'success'   => false,
        'timestamp' => time()
    ]);
}
