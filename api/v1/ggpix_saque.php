<?php
/**
 * GGPix - Processar saque via PIX
 * Endpoint: /ggpix/saque (POST)
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

$raw  = file_get_contents('php://input');
$body = json_decode($raw, true);

$valor     = floatval($body['valor']    ?? $_POST['valor']    ?? 0);
$chave_pix = trim($body['chave_pix']   ?? $_POST['chave_pix'] ?? '');
$tipo_chave= trim($body['tipo_chave']  ?? $_POST['tipo_chave'] ?? 'CPF');
$id_user   = intval($_SESSION['id_user']);

if ($valor <= 0 || empty($chave_pix)) {
    echo json_encode(['status' => 'error', 'message' => 'Valor e chave PIX são obrigatórios']);
    exit;
}

$fin = $mysqli->query("SELECT saldo FROM financeiro WHERE usuario = '$id_user' LIMIT 1")->fetch_assoc();
$saldo_atual = floatval($fin['saldo'] ?? 0);

if ($saldo_atual < $valor) {
    echo json_encode(['status' => 'error', 'message' => 'Saldo insuficiente']);
    exit;
}

$api_key  = $data_bspay['client_id'] ?? '';
$base_url = rtrim($data_bspay['url'] ?? 'https://ggpixapi.com/api/v1', '/');

if (empty($api_key) || $data_bspay['ativo'] != 1) {
    echo json_encode(['status' => 'error', 'message' => 'Gateway não configurado']);
    exit;
}

$transacao_id = 'SAQUE_' . $id_user . '_' . time() . '_' . rand(1000, 9999);

$res_user = $mysqli->query("SELECT * FROM usuarios WHERE id = '$id_user' LIMIT 1");
$user = $res_user->fetch_assoc();

$payload = [
    'amount'   => $valor,
    'pixKey'   => $chave_pix,
    'pixKeyType' => $tipo_chave,
    'reference'  => $transacao_id,
    'description'=> 'Saque via PIX',
    'beneficiary'=> [
        'name'  => $user['real_name'] ?? 'Cliente',
        'document' => $user['documento'] ?? '',
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base_url . '/pix/transfer');
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
$resp     = curl_exec($ch);
$http_code= curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$dados = json_decode($resp, true);

file_put_contents(__DIR__ . '/../../logs.json',
    date('Y-m-d H:i:s') . ' GGPIX_SAQUE: HTTP=' . $http_code . ' RESP=' . $resp . PHP_EOL,
    FILE_APPEND);

if ($http_code < 200 || $http_code >= 300) {
    echo json_encode([
        'status'  => 'error',
        'message' => 'Erro ao processar saque',
        'debug'   => $resp
    ]);
    exit;
}

$mysqli->query("UPDATE financeiro SET saldo = saldo - $valor WHERE usuario = '$id_user'");

$data_hora = date('Y-m-d H:i:s');
$stmt = $mysqli->prepare(
    "INSERT INTO solicitacao_saques (id_user, transacao_id, valor, tipo, pix, telefone, data_registro, status, tipo_saque)
     VALUES (?, ?, ?, 'PIX', ?, ?, ?, 1, 0)"
);
$tx_resp = $dados['transactionId'] ?? $dados['id'] ?? $transacao_id;
$telefone = $user['celular'] ?? '';
$stmt->bind_param("isdsss", $id_user, $tx_resp, $valor, $chave_pix, $telefone, $data_hora);
$stmt->execute();

if (function_exists('WebhookSaquesGerados')) {
    $nome = $user['real_name'] ?? $user['mobile'] ?? 'Usuário';
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    WebhookSaquesGerados($nome, $protocol . $_SERVER['HTTP_HOST'], $valor);
}

echo json_encode([
    'status'  => 'success',
    'message' => 'Saque processado com sucesso',
    'txid'    => $tx_resp,
    'valor'   => $valor
]);
