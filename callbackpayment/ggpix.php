<?php
/**
 * Webhook GGPix - Recebe notificações de pagamento PIX
 * URL: /callbackpayment/ggpix.php
 */

ini_set('display_errors', 0);
error_reporting(0);

define('DASH', '02071995admin');
require_once __DIR__ . '/../' . DASH . '/services/database.php';
require_once __DIR__ . '/../' . DASH . '/services/funcao.php';
require_once __DIR__ . '/../' . DASH . '/services/crud.php';

header('Content-Type: application/json');

$raw = file_get_contents('php://input');
file_put_contents(__DIR__ . '/../logs.json', date('Y-m-d H:i:s') . ' GGPIX_WEBHOOK: ' . $raw . PHP_EOL, FILE_APPEND);

$payload = json_decode($raw, true);

if (!$payload) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Payload inválido']);
    exit;
}

$status   = $payload['status']        ?? $payload['event']          ?? '';
$txid     = $payload['transactionId'] ?? $payload['transaction_id'] ?? $payload['txid'] ?? '';
$valor    = floatval($payload['amount'] ?? $payload['valor'] ?? 0);
$endToEnd = $payload['endToEndId']    ?? $payload['end_to_end_id']  ?? '';

$statuses_pagos = ['PAID', 'COMPLETED', 'paid', 'completed', 'CONFIRMED', 'confirmed', 'pix_received'];

if (!in_array($status, $statuses_pagos)) {
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'message' => 'Evento ignorado: ' . $status]);
    exit;
}

if (empty($txid) && !empty($endToEnd)) {
    $txid = $endToEnd;
}

if (empty($txid)) {
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'message' => 'Sem transacao_id']);
    exit;
}

$txid_safe = $mysqli->real_escape_string($txid);
$res = $mysqli->query("SELECT * FROM transacoes WHERE transacao_id = '$txid_safe' LIMIT 1");

if (!$res || $mysqli->num_rows === 0) {
    $res2 = $mysqli->query("SELECT * FROM transacoes WHERE transacao_id LIKE '%$txid_safe%' LIMIT 1");
    if ($res2 && $mysqli->num_rows > 0) {
        $res = $res2;
    } else {
        http_response_code(200);
        echo json_encode(['status' => 'ok', 'message' => 'Transação não encontrada: ' . $txid]);
        exit;
    }
}

$transacao = $res->fetch_assoc();

if ($transacao['status'] === 'pago') {
    http_response_code(200);
    echo json_encode(['status' => 'ok', 'message' => 'Transação já processada']);
    exit;
}

$id_usuario = intval($transacao['usuario']);
$valor_creditar = floatval($transacao['valor']);

if ($valor_creditar <= 0 && $valor > 0) {
    $valor_creditar = $valor;
}

$stmt = $mysqli->prepare("UPDATE transacoes SET status = 'pago' WHERE transacao_id = ?");
$stmt->bind_param("s", $txid);
$stmt->execute();

$sql_fin = $mysqli->query("SELECT id FROM financeiro WHERE usuario = '$id_usuario' LIMIT 1");
if ($sql_fin && $sql_fin->num_rows > 0) {
    $mysqli->query("UPDATE financeiro SET saldo = saldo + $valor_creditar WHERE usuario = '$id_usuario'");
} else {
    $mysqli->query("INSERT INTO financeiro (usuario, saldo, bonus) VALUES ('$id_usuario', $valor_creditar, 0)");
}

$data_hora = date('Y-m-d H:i:s');
$tipo = 'deposito';
$codigo = 'ggpix';
$stmt2 = $mysqli->prepare("INSERT INTO adicao_saldo (id_user, valor, tipo, data_registro) VALUES (?, ?, 'deposito_pix', ?)");
$stmt2->bind_param("ids", $id_usuario, $valor_creditar, $data_hora);
$stmt2->execute();

$res_user = $mysqli->query("SELECT real_name, mobile FROM usuarios WHERE id = '$id_usuario' LIMIT 1");
if ($res_user && $res_user->num_rows > 0) {
    $user = $res_user->fetch_assoc();
    $nome = $user['real_name'] ?? $user['mobile'] ?? 'Usuário';
    $site_url = defined('SITE_URL') ? SITE_URL : '';
    if (function_exists('WebhookPixPagos')) {
        WebhookPixPagos($nome, $site_url, $valor_creditar);
    }
}

http_response_code(200);
echo json_encode([
    'status' => 'ok',
    'message' => 'Depósito processado com sucesso',
    'usuario' => $id_usuario,
    'valor' => $valor_creditar
]);
