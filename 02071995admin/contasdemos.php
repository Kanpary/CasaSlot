<?php include 'partials/html.php' ?>

<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once "services/database.php";
include_once "services/funcao.php";
include_once 'logs/registrar_logs.php';
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once "validar_2fa.php";
include_once "services/CSRF_Protect.php";
include_once 'services/checa_login_adm.php';
$csrf = new CSRF_Protect();

checa_login_adm();

function registrarInfluencerMaxAPIGames($username)
{
    global $mysqli;

    $config_stmt = $mysqli->prepare("SELECT * FROM maxapigames WHERE id = 1");
    $config_stmt->execute();
    $config = $config_stmt->get_result()->fetch_assoc();
    $config_stmt->close();

    if (!$config || $config['ativo'] != 1) {
        throw new Exception("MaxAPIGames não está configurada ou ativa");
    }

    $data = [
        'method' => 'set_demo',
        'agent_code' => $config['agent_code'],
        'agent_token' => $config['agent_token'],
        'user_code' => $username
    ];

    $json_data = json_encode($data);

    error_log("[INFLUENCER] Username: $username | Endpoint: " . $config['url']);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $config['url']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error) {
        error_log("[INFLUENCER] cURL Error: $curl_error");
    }

    $data_response = json_decode($response, true);
    if ($data_response && isset($data_response['status']) && $data_response['status'] == 1) {
        return [
            'success' => true,
            'message' => 'Influencer ativado com sucesso na MaxAPIGames',
            'data' => $data_response
        ];
    } else {
        $error_msg = $data_response['msg'] ?? 'Erro desconhecido';
        throw new Exception("API Error: " . $error_msg);
    }
}

function criarContasDemo($quantidade, $saldo, $abrir_jogo = false)
{
    global $mysqli;

    $contas_criadas = [];
    $erros = [];

    for ($i = 0; $i < $quantidade; $i++) {
        $random_id = rand(10000, 99999);
        $username = "demo" . $random_id;
        $password = "demo" . rand(1000, 9999);
        $token = md5(uniqid($username, true));
        $invite_code = (string)$random_id;

        $stmt = $mysqli->prepare("INSERT INTO usuarios
            (id, mobile, celular, password, saldo, spassword, url, token, data_registro, invite_code, statusaff, lobby, vip)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, 1, 1, 0)");

        $url = "https://" . $_SERVER['HTTP_HOST'];

        $stmt->bind_param(
            "isssdssss",
            $random_id,
            $username,
            $username,
            $password,
            $saldo,
            $password,
            $url,
            $token,
            $invite_code
        );

        if ($stmt->execute()) {
            $conta = [
                'id' => $random_id,
                'username' => $username,
                'password' => $password,
                'saldo' => $saldo,
                'token' => $token,
                'game_url' => null,
                'influencer_status' => 'aguardando',
            ];

            sleep(4);

            // Abrir jogo Fortune Tiger
            if ($abrir_jogo) {
                try {
                    $game_url = abrirJogoDemo($username, $saldo, $token);
                    $conta['game_url'] = $game_url;
                } catch (Exception $e) {
                    $conta['game_url_error'] = $e->getMessage();
                }
            }

            sleep(4);

            // Ativar influencer MaxAPIGames
            try {
                $result = registrarInfluencerMaxAPIGames($username);
                $conta['influencer_status'] = 'ativo';
                $conta['influencer_data'] = $result;
            } catch (Exception $e) {
                $conta['influencer_status'] = 'erro';
                $conta['influencer_error'] = $e->getMessage();
            }

            $contas_criadas[] = $conta;

        } else {
            $erros[] = "Erro ao criar conta $username: " . $stmt->error;
        }

        $stmt->close();
    }

    return [
        'sucesso' => count($contas_criadas),
        'erros' => count($erros),
        'contas' => $contas_criadas,
        'mensagens_erro' => $erros,
    ];
}

function abrirJogoDemo($username, $saldo, $token)
{
    global $mysqli;

    $stmt = $mysqli->prepare("SELECT game_code, provider FROM games WHERE game_name LIKE '%fortune%tiger%' AND api = 'MaxAPIGames' LIMIT 1");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $game_code = $row['game_code'];

        $config_stmt = $mysqli->prepare("SELECT * FROM maxapigames WHERE id = 1");
        $config_stmt->execute();
        $config = $config_stmt->get_result()->fetch_assoc();

        if ($config && $config['ativo'] == 1) {
            $data = [
                'method' => 'game_launch',
                'agent_code' => $config['agent_code'],
                'agent_token' => $config['agent_token'],
                'user_code' => $username,
                'game_code' => $game_code
            ];

            $json_data = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $config['url']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

            $response = curl_exec($ch);
            curl_close($ch);

            $data_response = json_decode($response, true);
            if (isset($data_response['launch_url'])) {
                return $data_response['launch_url'];
            }

            throw new Exception("Erro ao abrir jogo: " . ($data_response['msg'] ?? 'Resposta inválida da API'));
        }

        throw new Exception("MaxAPIGames não está configurada ou ativa");
    }

    throw new Exception("Jogo Fortune Tiger não encontrado");
}

$toastType = null;
$toastMessage = '';
$resultado = null;

if (isset($_SESSION['toast_type'])) {
    $toastType = $_SESSION['toast_type'];
    unset($_SESSION['toast_type']);
}

if (isset($_SESSION['toast_message'])) {
    $toastMessage = $_SESSION['toast_message'];
    unset($_SESSION['toast_message']);
}

if (isset($_SESSION['resultado'])) {
    $resultado = $_SESSION['resultado'];
    unset($_SESSION['resultado']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['criar_contas'])) {
    $quantidade = intval($_POST['quantidade']);
    $saldo = floatval($_POST['saldo']);
    $abrir_jogo = true;

    if ($quantidade > 0 && $quantidade <= 100 && $saldo >= 0) {
        $resultado_criacao = criarContasDemo($quantidade, $saldo, $abrir_jogo);

        $_SESSION['resultado'] = $resultado_criacao;

        if ($resultado_criacao['sucesso'] > 0) {
            $_SESSION['toast_type'] = 'success';
            $_SESSION['toast_message'] = "{$resultado_criacao['sucesso']} contas demo criadas e ativadas na MaxAPIGames!";
        } else {
            $_SESSION['toast_type'] = 'error';
            $_SESSION['toast_message'] = "Erro ao criar contas demo";
        }
    } else {
        $_SESSION['toast_type'] = 'error';
        $_SESSION['toast_message'] = "Dados inválidos. Quantidade deve ser entre 1 e 100.";
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

$contas_demo_qry = "SELECT * FROM usuarios WHERE mobile LIKE 'demo%' ORDER BY id DESC LIMIT 50";
$contas_demo_result = mysqli_query($mysqli, $contas_demo_qry);
$contas_demo = [];
while ($row = mysqli_fetch_assoc($contas_demo_result)) {
    $contas_demo[] = $row;
}
?>

<head>
    <?php $title = "Criar Contas Demo em Massa"; ?>
    <?php include 'partials/title-meta.php' ?>
    <?php include 'partials/head-css.php' ?>
    <style>
        .conta-card {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            background: #191a1a;
        }
        .conta-card:hover {
            background: #191a1a;
        }
        .copy-btn {
            cursor: pointer;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">

                <!-- Formulário de Criação -->
                <div class="row justify-content-center mb-4">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Criar Contas Demo em Massa</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST">
                                    <input type="hidden" name="criar_contas" value="1">

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Quantidade de Contas</label>
                                            <input type="number" name="quantidade" class="form-control"
                                                min="1" max="100" value="10" required>
                                            <small class="text-muted">Máximo: 100 contas por vez</small>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Saldo Inicial (R$)</label>
                                            <input type="number" name="saldo" class="form-control"
                                                step="0.01" min="0" value="1000.00" required>
                                            <small class="text-muted">Saldo para cada conta</small>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning">
                                        <strong>Tempo de Processamento:</strong>
                                        <ul class="mb-0">
                                            <li>Cada conta leva aproximadamente <strong>8 segundos</strong></li>
                                            <li>4 segundos para abrir o jogo Fortune Tiger</li>
                                            <li>4 segundos para ativar influencer MaxAPIGames</li>
                                            <li>Para 10 contas: ~1 minuto e 20 segundos</li>
                                        </ul>
                                    </div>

                                    <div class="alert alert-info">
                                        <strong>Informações:</strong>
                                        <ul class="mb-0">
                                            <li>Todas as contas terão <strong>statusaff = 1</strong> (Afiliado ativo)</li>
                                            <li>Username: <code>demo[número aleatório]</code></li>
                                            <li>Senha: <code>demo[4 dígitos]</code></li>
                                            <li>Lobby habilitado automaticamente</li>
                                            <li>Jogo Fortune Tiger aberto automaticamente</li>
                                            <li>MaxAPIGames ativado (influencer)</li>
                                        </ul>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-users"></i> Criar Contas Demo
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resultado da Criação -->
                <?php if ($resultado && $resultado['sucesso'] > 0): ?>
                <div class="row justify-content-center mb-4">
                    <div class="col-lg-10">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h4 class="card-title text-white mb-0">
                                    Contas Criadas: <?= $resultado['sucesso'] ?>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach ($resultado['contas'] as $conta): ?>
                                    <div class="col-md-6">
                                        <div class="conta-card">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="mb-1">
                                                        <i class="fas fa-user"></i> <?= $conta['username'] ?>
                                                    </h6>
                                                    <p class="mb-1">
                                                        <strong>Senha:</strong>
                                                        <code><?= $conta['password'] ?></code>
                                                        <i class="fas fa-copy copy-btn ms-2"
                                                            onclick="copiarTexto('<?= $conta['password'] ?>')"
                                                            title="Copiar senha"></i>
                                                    </p>
                                                    <p class="mb-1">
                                                        <strong>Saldo:</strong> R$ <?= number_format($conta['saldo'], 2, ',', '.') ?>
                                                    </p>
                                                    <p class="mb-0">
                                                        <strong>ID:</strong> <?= $conta['id'] ?>
                                                    </p>
                                                </div>
                                                <span class="badge bg-success">DEMO</span>
                                            </div>

                                            <!-- Status MaxAPIGames -->
                                            <div class="mb-2">
                                                <small>
                                                    <strong>MaxAPIGames:</strong>
                                                    <?php if ($conta['influencer_status'] === 'ativo'): ?>
                                                        <span class="badge bg-success">Ativado</span>
                                                    <?php elseif ($conta['influencer_status'] === 'erro'): ?>
                                                        <span class="badge bg-danger" title="<?= htmlspecialchars($conta['influencer_error'] ?? '') ?>">Erro</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Processando...</span>
                                                    <?php endif; ?>
                                                </small>
                                            </div>

                                            <?php if (!empty($conta['game_url'])): ?>
                                            <div class="mt-2">
                                                <a href="<?= $conta['game_url'] ?>" target="_blank" class="btn btn-sm btn-primary w-100">
                                                    <i class="fas fa-gamepad"></i> Abrir Fortune Tiger
                                                </a>
                                            </div>
                                            <?php elseif (isset($conta['game_url_error'])): ?>
                                            <div class="mt-2">
                                                <small class="text-danger"><?= htmlspecialchars($conta['game_url_error']) ?></small>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="text-center mt-3">
                                    <button class="btn btn-success" onclick="exportarContas()">
                                        <i class="fas fa-download"></i> Exportar Lista
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Contas Demo Existentes -->
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Últimas 50 Contas Demo</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>Senha</th>
                                                <th>Saldo</th>
                                                <th>Status</th>
                                                <th>Data Criação</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($contas_demo)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Nenhuma conta demo encontrada</td>
                                            </tr>
                                            <?php else: ?>
                                            <?php foreach ($contas_demo as $conta): ?>
                                            <tr>
                                                <td><?= $conta['id'] ?></td>
                                                <td><code><?= $conta['mobile'] ?></code></td>
                                                <td>
                                                    <code><?= $conta['password'] ?></code>
                                                    <i class="fas fa-copy copy-btn ms-1"
                                                        onclick="copiarTexto('<?= $conta['password'] ?>')"
                                                        title="Copiar"></i>
                                                </td>
                                                <td>R$ <?= number_format($conta['saldo'], 2, ',', '.') ?></td>
                                                <td>
                                                    <?php if ($conta['statusaff'] == 1): ?>
                                                        <span class="badge bg-success">Ativo</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inativo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= date('d/m/Y H:i', strtotime($conta['data_registro'])) ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="deletarConta(<?= $conta['id'] ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <?php include 'partials/endbar.php' ?>
            <?php include 'partials/footer.php' ?>
        </div>
    </div>

    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <script>
        function showToast(type, message) {
            var toastPlacement = document.getElementById('toastPlacement');
            var toast = document.createElement('div');
            toast.className = `toast align-items-center bg-light border-0 fade show`;
            toast.innerHTML = `
                <div class="toast-header">

                    <h5 class="me-auto my-0">Notificação</h5>
                    <small>Agora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">${message}</div>
            `;
            toastPlacement.appendChild(toast);
            var bootstrapToast = new bootstrap.Toast(toast);
            bootstrapToast.show();
            setTimeout(function () {
                bootstrapToast.hide();
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        function copiarTexto(texto) {
            navigator.clipboard.writeText(texto).then(() => {
                showToast('success', 'Copiado: ' + texto);
            }).catch(() => {
                showToast('error', 'Erro ao copiar');
            });
        }

        function exportarContas() {
            const contas = <?= json_encode($resultado['contas'] ?? []) ?>;
            let texto = "CONTAS DEMO CRIADAS\n\n";

            contas.forEach((conta, index) => {
                texto += `Conta ${index + 1}:\n`;
                texto += `Username: ${conta.username}\n`;
                texto += `Senha: ${conta.password}\n`;
                texto += `Saldo: R$ ${conta.saldo}\n`;
                texto += `MaxAPIGames: ${conta.influencer_status}\n`;
                if (conta.game_url) {
                    texto += `Link Fortune Tiger: ${conta.game_url}\n`;
                }
                texto += '\n';
            });

            const blob = new Blob([texto], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'contas_demo_' + new Date().getTime() + '.txt';
            a.click();
            window.URL.revokeObjectURL(url);

            showToast('success', 'Arquivo baixado!');
        }

        function deletarConta(id) {
            if (confirm('Deseja realmente deletar esta conta demo?')) {
                fetch('ajax/deletar_conta_demo.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'id=' + id
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('success', 'Conta deletada!');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast('error', 'Erro ao deletar conta');
                    }
                });
            }
        }
    </script>

    <?php if ($toastType && $toastMessage): ?>
        <script>
            showToast('<?= $toastType ?>', '<?= $toastMessage ?>');
        </script>
    <?php endif; ?>

</body>
</html>
