<?php
// updateDemoMaxAPIGames.php (antigo updateDemoPGClone.php)
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['data_adm'])) {
    echo json_encode(['success' => false, 'message' => 'Acesso não autorizado.']);
    exit;
}

include_once __DIR__ . '/../services/database.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!isset($data['mobile']) || !isset($data['modo_demo'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos: mobile ou modo_demo não fornecidos.']);
    exit;
}

$mobile = $data['mobile'];
$modo_demo = intval($data['modo_demo']);

if ($modo_demo !== 0 && $modo_demo !== 1) {
    echo json_encode(['success' => false, 'message' => 'Valor de Modo Demo inválido.']);
    exit;
}

try {
    if ($modo_demo === 1) {
        $result = registrarInfluencerMaxAPIGames($mobile);
        echo json_encode([
            'success' => true,
            'message' => 'Influencer ativado com sucesso na MaxAPIGames',
            'pgclone_status' => 'ativo',
            'pgclone_data' => $result
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'Influencer desativado na MaxAPIGames',
            'pgclone_status' => 'inativo',
            'pgclone_data' => null
        ]);
    }
} catch (Exception $e) {
    $action = $modo_demo === 1 ? 'ativar' : 'desativar';
    echo json_encode([
        'success' => false,
        'message' => "Erro ao {$action} influencer na MaxAPIGames",
        'pgclone_status' => 'erro',
        'pgclone_error' => $e->getMessage()
    ]);
}

exit;

/**
 * Função para registrar influencer/demo na MaxAPIGames usando set_demo
 */
function registrarInfluencerMaxAPIGames($username)
{
    global $mysqli;

    $config_stmt = $mysqli->prepare("SELECT * FROM maxapigames WHERE id = 1");
    $config_stmt->execute();
    $config = $config_stmt->get_result()->fetch_assoc();
    $config_stmt->close();

    if (!$config) {
        throw new Exception("Configuração da MaxAPIGames não encontrada");
    }

    if ($config['ativo'] != 1) {
        throw new Exception("MaxAPIGames não está ativa");
    }

    $data = [
        'method' => 'set_demo',
        'agent_code' => $config['agent_code'],
        'agent_token' => $config['agent_token'],
        'user_code' => $username
    ];

    $json_data = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $config['url']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error) {
        throw new Exception("cURL Error: $curl_error");
    }

    $data_response = json_decode($response, true);

    if ($data_response && isset($data_response['status']) && $data_response['status'] == 1) {
        return [
            'success' => true,
            'message' => 'Influencer configurado com sucesso na MaxAPIGames',
            'data' => $data_response
        ];
    } else {
        $error_msg = $data_response['msg'] ?? 'Erro desconhecido';
        throw new Exception("API Error: " . $error_msg);
    }
}
?>
