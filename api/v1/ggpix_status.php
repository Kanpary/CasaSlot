<?php
/**
 * GGPix - Consultar status da transação
 * Endpoint: /hall/ggpix/status?txid=GGPIX_xxx
 */

ini_set('display_errors', 0);
error_reporting(0);

define('DASH', '02071995admin');
require_once __DIR__ . '/../../' . DASH . '/services/database.php';
require_once __DIR__ . '/../../' . DASH . '/services/funcao.php';
require_once __DIR__ . '/../../' . DASH . '/services/crud.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

session_start();
if (empty($_SESSION['id_user'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Não autenticado']);
    exit;
}

$txid = $_GET['txid'] ?? '';
$id_user = intval($_SESSION['id_user']);

if (empty($txid)) {
    echo json_encode(['status' => 'error', 'message' => 'txid obrigatório']);
    exit;
}

$txid_safe = $mysqli->real_escape_string($txid);
$res = $mysqli->query("SELECT * FROM transacoes WHERE transacao_id = '$txid_safe' AND usuario = '$id_user' LIMIT 1");

if (!$res || $res->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Transação não encontrada']);
    exit;
}

$transacao = $res->fetch_assoc();

if ($transacao['status'] === 'pago') {
    echo json_encode(['status' => 'pago', 'message' => 'Pagamento confirmado!']);
    exit;
}

if ($transacao['status'] === 'expirado') {
    echo json_encode(['status' => 'expirado', 'message' => 'PIX expirado']);
    exit;
}

$api_key  = $data_bspay['client_id'] ?? '';
$base_url = rtrim($data_bspay['url'] ?? 'https://ggpixapi.com/api/v1', '/');

if (!empty($api_key)) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $base_url . '/pix/status/' . urlencode($txid));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_key,
        'Accept: application/json',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $resp = curl_exec($ch);
    curl_close($ch);

    $dados = json_decode($resp, true);
    $status_api = $dados['status'] ?? '';
    $statuses_pagos = ['PAID', 'COMPLETED', 'paid', 'completed', 'CONFIRMED', 'confirmed'];

    if (in_array($status_api, $statuses_pagos)) {
        $valor_creditar = floatval($transacao['valor']);
        $mysqli->query("UPDATE transacoes SET status = 'pago' WHERE transacao_id = '$txid_safe'");
        $mysqli->query("UPDATE financeiro SET saldo = saldo + $valor_creditar WHERE usuario = '$id_user'");
        echo json_encode(['status' => 'pago', 'message' => 'Pagamento confirmado!']);
        exit;
    }
}

echo json_encode(['status' => 'processamento', 'message' => 'Aguardando pagamento...']);
