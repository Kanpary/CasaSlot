<?php
session_start();
include_once('../services/database.php');
include_once('../services/funcao.php');
include_once('../services/checa_login_adm.php');

checa_login_adm();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

$result = mysqli_query($mysqli, "DELETE FROM games");

if ($result) {
    $deleted = mysqli_affected_rows($mysqli);
    echo json_encode(['success' => true, 'message' => "$deleted jogos removidos com sucesso!"]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao remover jogos: ' . mysqli_error($mysqli)]);
}
?>
