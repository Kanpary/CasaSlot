<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if ($input === null) {
    echo json_encode(["error" => "Dados inválidos."]);
    exit;
}

function formatJson($data)
{
    if (is_string($data)) {
        return json_decode('"' . $data . '"', true);
    }

    if (is_array($data) || is_object($data)) {
        return $data;
    }

    return $data;
}

$input = formatJson($input);

$logFile = 'logs.json';

if (file_exists($logFile)) {
    $existingLogs = json_decode(file_get_contents($logFile), true);

    if ($existingLogs === null) {
        $existingLogs = [];
    }
} else {
    $existingLogs = [];
}

$existingLogs[] = $input;

if (file_put_contents($logFile, json_encode($existingLogs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(["message" => "Logs salvos com sucesso"]);
} else {
    echo json_encode(["error" => "Falha ao salvar os logs"]);
}
?>