<?php
/**
 * Canvas Slot API — handles bet/result server-side
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

session_start();

if (!defined('DASH')) define('DASH', '02071995admin');
require_once __DIR__ . '/../' . DASH . '/services/database.php';
require_once __DIR__ . '/../' . DASH . '/services/funcao.php';

if (empty($_SESSION['id_user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Não autenticado']);
    exit;
}

$uid = intval($_SESSION['id_user']);
$raw = file_get_contents('php://input');
$data = json_decode($raw, true);

$action = $data['action'] ?? '';
$bet    = floatval($data['bet'] ?? 0);
$win    = floatval($data['win'] ?? 0);

if ($action === 'result') {
    if ($bet <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Aposta inválida']);
        exit;
    }

    $config = $mysqli->query("SELECT * FROM canvas_slot_config WHERE id=1 LIMIT 1")->fetch_assoc();
    if (!$config || !$config['status']) {
        echo json_encode(['status' => 'error', 'message' => 'Jogo desativado']);
        exit;
    }

    if ($bet < $config['aposta_minima'] || $bet > $config['aposta_maxima']) {
        echo json_encode(['status' => 'error', 'message' => 'Aposta fora dos limites']);
        exit;
    }

    $res = $mysqli->query("SELECT saldo FROM usuarios WHERE id=$uid LIMIT 1");
    $user = $res->fetch_assoc();
    $saldo_atual = (float)$user['saldo'];

    if ($saldo_atual < $bet) {
        echo json_encode(['status' => 'error', 'message' => 'Saldo insuficiente']);
        exit;
    }

    $novo_saldo = $saldo_atual - $bet + $win;
    $novo_saldo = max(0, $novo_saldo);

    $stmt = $mysqli->prepare("UPDATE usuarios SET saldo=? WHERE id=?");
    $stmt->bind_param("di", $novo_saldo, $uid);
    $stmt->execute();

    // Registrar jogada
    $game_name = $config['nome_jogo'];
    $resultado = $win > 0 ? 'win' : 'loss';
    $data_hora = date('Y-m-d H:i:s');

    $stmt2 = $mysqli->prepare(
        "INSERT INTO historico_play (id_user, game_name, valor_aposta, valor_ganho, resultado, data_hora)
         VALUES (?,?,?,?,?,?)"
    );
    if ($stmt2) {
        $stmt2->bind_param("isddss", $uid, $game_name, $bet, $win, $resultado, $data_hora);
        $stmt2->execute();
    }

    echo json_encode([
        'status' => 'success',
        'saldo'  => round($novo_saldo, 2),
        'bet'    => $bet,
        'win'    => $win,
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ação desconhecida']);
}
