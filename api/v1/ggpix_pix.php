<?php
/**
 * GGPix - Gerar cobrança PIX
 * Endpoint: /hall/ggpix/gerar
 */

ini_set('display_errors', 0);
error_reporting(0);

define('DASH', '02071995admin');
require_once __DIR__ . '/../../' . DASH . '/services/database.php';
require_once __DIR__ . '/../../' . DASH . '/services/funcao.php';
require_once __DIR__ . '/../../' . DASH . '/services/crud.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido']);
    exit;
}

session_start();
if (empty($_SESSION['id_user'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Não autenticado']);
    exit;
}

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);

$valor = floatval($body['valor'] ?? $_POST['valor'] ?? 0);
$id_user = intval($_SESSION['id_user']);

if ($valor <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Valor inválido']);
    exit;
}

$config_pay = $data_bspay;

if (empty($config_pay['client_id']) || $config_pay['ativo'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Gateway de pagamento não configurado']);
    exit;
}

$api_key = $config_pay['client_id'];
$base_url = rtrim($config_pay['url'] ?? 'https://ggpixapi.com/api/v1', '/');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$site_url = $protocol . $host;

$transacao_id = 'GGPIX_' . $id_user . '_' . time() . '_' . rand(1000, 9999);

$res_user = $mysqli->query("SELECT * FROM usuarios WHERE id = '$id_user' LIMIT 1");
$user = $res_user->fetch_assoc();

$payload = [
    'amount'      => $valor,
    'description' => 'Depósito via PIX',
    'reference'   => $transacao_id,
    'callbackUrl' => $site_url . '/callbackpayment/ggpix.php',
    'customer'    => [
        'name'  => $user['real_name'] ?? 'Cliente',
        'email' => $user['mobile']    ?? '',
        'phone' => $user['celular']   ?? '',
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base_url . '/pix/create');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key,
    'Accept: application/json',
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$dados = json_decode($resp, true);

file_put_contents(__DIR__ . '/../../logs.json',
    date('Y-m-d H:i:s') . ' GGPIX_GERAR: HTTP=' . $http_code . ' RESP=' . $resp . PHP_EOL,
    FILE_APPEND);

if ($http_code < 200 || $http_code >= 300 || empty($dados)) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Erro ao criar cobrança PIX',
        'debug'   => $resp
    ]);
    exit;
}

$qrcode_img  = $dados['qrCodeBase64'] ?? $dados['qr_code_base64'] ?? $dados['qrcode']    ?? '';
$qrcode_text = $dados['qrCode']       ?? $dados['qr_code']        ?? $dados['copyPaste']  ?? $dados['brcode'] ?? '';
$tx_id       = $dados['transactionId'] ?? $dados['transaction_id'] ?? $dados['id']        ?? $transacao_id;
$expires_at  = $dados['expiresAt']    ?? $dados['expires_at']     ?? null;

$data_hora = date('Y-m-d H:i:s');
$status = 'processamento';
$tipo = 'deposito';
$qrcode_store = $qrcode_text ?: $qrcode_img;

$stmt = $mysqli->prepare(
    "INSERT INTO transacoes (transacao_id, usuario, valor, data_registro, tipo, status, qrcode, code)
     VALUES (?, ?, ?, ?, 'deposito', 'processamento', ?, 'ggpix')"
);
$stmt->bind_param("ssdss", $tx_id, $id_user, $valor, $data_hora, $qrcode_store);
$stmt->execute();

if (function_exists('WebhookPixGerado')) {
    $nome = $user['real_name'] ?? $user['mobile'] ?? 'Usuário';
    WebhookPixGerado($nome, $site_url, $valor);
}

echo json_encode([
    'status'      => 'success',
    'transacaoId' => $tx_id,
    'qrcode'      => $qrcode_img,
    'copyPaste'   => $qrcode_text,
    'valor'       => $valor,
    'expiresAt'   => $expires_at,
    'message'     => 'PIX gerado com sucesso'
]);
