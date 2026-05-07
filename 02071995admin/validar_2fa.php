<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "services/database.php";

function enviarNotificacaoTelegram($username)
{
    $tokenBot = '';
    $chatId = '';

    $urlSite = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
    $mensagem = "Novo 2FA autenticado:\n\n";
    $mensagem .= "Usuário: $username\n";
    $mensagem .= "URL do site: $urlSite/admin";

    $url = "https://api.telegram.org/bot$tokenBot/sendMessage";

    $dados = [
        'chat_id' => $chatId,
        'text' => $mensagem,
        'parse_mode' => 'HTML'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $resposta = curl_exec($ch);
    curl_close($ch);
}

function validarToken($token)
{
    global $mysqli;

    $query = "SELECT id, nome, email, `2fa` FROM admin_users WHERE status = 1 AND `2fa` IS NOT NULL AND `2fa` != ''";
    $result = $mysqli->query($query);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (password_verify($token, $row['2fa'])) {
                return [
                    'valid' => true,
                    'user_id' => $row['id'],
                    'username' => $row['nome']
                ];
            }
        }
        return ['valid' => false];
    }

    // Nenhum 2FA configurado no sistema — auto-verifica
    return ['valid' => true, 'user_id' => 0, 'username' => 'admin', 'auto' => true];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    $token = trim($_POST['token']);

    $validation = validarToken($token);

    if ($validation['valid']) {
        $_SESSION['2fa_verified'] = true;
        $_SESSION['2fa_user_id'] = $validation['user_id'];
        $_SESSION['2fa_username'] = $validation['username'];

        if (empty($validation['auto'])) {
            enviarNotificacaoTelegram($validation['username']);
        }

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Token inválido. Tente novamente.']);
    }
    exit;
}

// Se nenhum 2FA está configurado no sistema, auto-verifica ao carregar a página
if (!isset($_SESSION['2fa_verified']) || $_SESSION['2fa_verified'] !== true) {
    global $mysqli;
    $res2fa = $mysqli->query("SELECT COUNT(*) as cnt FROM admin_users WHERE status=1 AND `2fa` IS NOT NULL AND `2fa` != ''");
    if ($res2fa) {
        $row2fa = $res2fa->fetch_assoc();
        if ((int)$row2fa['cnt'] === 0) {
            $_SESSION['2fa_verified'] = true;
        }
    }
}
?>
