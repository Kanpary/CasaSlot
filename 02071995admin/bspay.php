<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

include_once "../config.php";
include_once('../'.DASH.'/services/database.php');
include_once('../'.DASH.'/services/funcao.php');
include_once('../'.DASH.'/services/crud.php');
include_once('../'.DASH.'/services/afiliacao.php');
include_once('../'.DASH.'/services/webhook.php');

global $mysqli;

/**
 * ======================================================
 * LOG RAW (RECOMENDADO)
 * ======================================================
 */
file_put_contents(
    __DIR__ . '/bspay_raw.log',
    date('Y-m-d H:i:s') . ' ' . file_get_contents('php://input') . PHP_EOL,
    FILE_APPEND
);

/**
 * ======================================================
 * RECEBE JSON
 * ======================================================
 */
$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(200);
    exit('INVALID JSON');
}

/**
 * ======================================================
 * CAMPOS REAIS DO WEBHOOK (BSPAY / VELUPAY)
 * ======================================================
 */
if (!isset($data['transactionId'], $data['status'])) {
    http_response_code(200);
    exit('INVALID BODY');
}

$idTransaction     = PHP_SEGURO($data['transactionId']);
$statusTransaction = strtolower(PHP_SEGURO($data['status']));

/**
 * ======================================================
 * PROCESSA SOMENTE PAGAMENTO CONFIRMADO
 * ======================================================
 */
if (!in_array($statusTransaction, ['paid', 'complete'])) {
    http_response_code(200);
    exit('IGNORED');
}

/**
 * ======================================================
 * ATUALIZA TRANSAÇÃO + CREDITA SALDO
 * ======================================================
 */
$att_transacao = att_paymentpix($idTransaction);

http_response_code(200);
echo 'OK';
exit;

/**
 * ======================================================
 * FUNÇÕES
 * ======================================================
 */

function verificarBonus($userId, $valorPago) {
    global $mysqli;

    $stmt = $mysqli->prepare(
        "SELECT id FROM cupom_usados WHERE id_user = ? AND valor = ?"
    );
    $stmt->bind_param("id", $userId, $valorPago);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) return 0;

    $stmt = $mysqli->prepare(
        "SELECT qtd_insert FROM cupom WHERE status = 1 AND valor = ?"
    );
    $stmt->bind_param("d", $valorPago);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        return (float)$row['qtd_insert'];
    }

    return 0;
}

function registrarBonusUsado($userId, $valorPago, $bonus) {
    global $mysqli;

    if ($bonus <= 0) return false;

    $stmt = $mysqli->prepare(
        "INSERT INTO cupom_usados (id_user, valor, bonus, data_registro)
         VALUES (?, ?, ?, NOW())"
    );
    $stmt->bind_param("idi", $userId, $valorPago, $bonus);
    return $stmt->execute();
}

function busca_valor_ipn($transacao_id) {
    global $mysqli;

    $stmt = $mysqli->prepare(
        "SELECT usuario, valor FROM transacoes WHERE transacao_id = ? LIMIT 1"
    );
    $stmt->bind_param("s", $transacao_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {

        $userId     = (int)$row['usuario'];
        $valorPago = (float)$row['valor'];

        // BÔNUS
        $bonus = verificarBonus($userId, $valorPago);
        $total = $valorPago + $bonus;

        // CREDITA SALDO
        if (!adicionarSaldoUsuario($userId, $total)) {
            return false;
        }

        if ($bonus > 0) {
            registrarBonusUsado($userId, $valorPago, $bonus);
        }

        // AFILIAÇÃO
        processarTodasComissoes($userId, $valorPago);

        // WEBHOOK INTERNO
        $stmtUser = $mysqli->prepare("SELECT nome FROM usuarios WHERE id = ?");
        $stmtUser->bind_param("i", $userId);
        $stmtUser->execute();
        $user = $stmtUser->get_result()->fetch_assoc();

        WebhookPixPagos(
            $user['nome'] ?? 'Usuário',
            $_SERVER['HTTP_HOST'],
            $valorPago
        );

        return true;
    }

    return false;
}

function att_paymentpix($transacao_id) {
    global $mysqli;

    // VERIFICA DUPLICIDADE
    $stmt = $mysqli->prepare(
        "SELECT status FROM transacoes WHERE transacao_id = ? LIMIT 1"
    );
    $stmt->bind_param("s", $transacao_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if ($row['status'] === 'pago') {
            return 2;
        }
    }

    // MARCA COMO PAGO
    $stmt = $mysqli->prepare(
        "UPDATE transacoes SET status = 'pago' WHERE transacao_id = ?"
    );
    $stmt->bind_param("s", $transacao_id);

    if ($stmt->execute()) {
        return busca_valor_ipn($transacao_id) ? 1 : 0;
    }

    return 0;
}