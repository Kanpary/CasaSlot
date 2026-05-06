<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Incluir configuração do banco de dados usando a estrutura correta
include_once __DIR__ . "/../../../../config.php";
include_once __DIR__ . '/../../../../' . DASH . '/services/database.php';

// Get parameters from query string
$siteCode = $_GET['siteCode'] ?? '';
$token = $_GET['token'] ?? '';
$currency = $_GET['currency'] ?? 'BRL';
$language = $_GET['language'] ?? 'pt';
$categoryId = $_GET['categoryId'] ?? '';
$platformId = $_GET['platformId'] ?? '';

// Validate required parameters
if (empty($categoryId) || empty($platformId)) {
    http_response_code(400);
    echo json_encode([
        'code' => 400,
        'error' => 'Missing required parameters: categoryId and platformId',
        'failed' => true,
        'msg' => 'Bad Request',
        'success' => false,
        'timestamp' => time()
    ]);
    exit();
}

try {
    // O $mysqli já está disponível através do database.php
    
    // Buscar jogos da tabela games baseado nos parâmetros
    $sql = "SELECT id, game_code, game_name, banner, status, provider, popular, type, game_type, api 
            FROM games 
            WHERE status = 1 AND provider = ?";
    
    // Adicionar filtros baseados nos parâmetros
    if ($platformId == '200') {
        $sql .= " AND provider = 'PGSOFT'";
    } elseif ($platformId == '310') {
        $sql .= " AND provider = 'PragmaticPlay'";
    }
    
    $sql .= " ORDER BY id ASC";
    
    $result = $mysqli->query($sql);
    
    if (!$result) {
        throw new Exception("Query failed: " . $mysqli->error);
    }
    
    $games = [];
    while ($row = $result->fetch_assoc()) {
        // Construir URL correta do banner baseada na estrutura real
        // Usar o banner real do banco de dados, removendo TODOS os backticks e espaços extras
        $banner = $row['banner'] ?? "";
        $banner = str_replace('`', '', $banner); // Remove todos os backticks
        $banner = trim($banner); // Remove espaços do início e fim
        
        // Se o banner estiver vazio, tentar encontrar um arquivo válido
        if (empty($banner)) {
            $gameId = $row['id'];
            $extensions = ['png', 'jpg', 'jpeg', 'webp', 'avif', 'gif'];
            $bannerFound = false;
            
            foreach ($extensions as $ext) {
                $testPath = "/www/wwwroot/w1_original/game_pictures/g/EA/200/3/{$gameId}/default.{$ext}";
                if (file_exists($testPath)) {
                    $banner = "/game_pictures/g/EA/200/3/{$gameId}/default.{$ext}";
                    $bannerFound = true;
                    break;
                }
            }
            
            // Se ainda não encontrou, usar um banner padrão genérico
            if (!$bannerFound) {
                $banner = "/uploads/tema1.png"; // Banner padrão
            }
        }
        
        $games[] = [
            "g0" => (int)$row['id'],
            "g1" => $row['game_name'],
            "g10" => (int)$platformId,
            "g11" => 2,
            "g2" => $banner, // Banner real do banco de dados
            "g3" => 0,
            "g4" => 0,
            "g5" => (int)$row['popular'],
            "g6" => 0,
            "g7" => 1,
            "g8" => (int)$row['status'],
            "g9" => (int)$categoryId
        ];
    }
    
    $response = [
        "code" => 0,
        "data" => $games,
        "failed" => false,
        "msg" => "success",
        "success" => true,
        "timestamp" => round(microtime(true) * 1000)
    ];
    
    echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'code' => 500,
        'error' => 'Database error: ' . $e->getMessage(),
        'failed' => true,
        'msg' => 'Internal Server Error',
        'success' => false,
        'timestamp' => time()
    ]);
}
?>