<?php include 'partials/html.php' ?>

<?php
#======================================#
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
#======================================#
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once "services/database.php";
include_once 'logs/registrar_logs.php';
include_once "services/funcao.php";
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once 'services/checa_login_adm.php';
include_once "validar_2fa.php";
include_once "services/CSRF_Protect.php";
$csrf = new CSRF_Protect();

checa_login_adm();

if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

function get_afiliados_config()
{
    global $mysqli;
    $qry = "SELECT * FROM afiliados_config WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

function get_config()
{
    global $mysqli;
    $qry = "SELECT * FROM config WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

# Função para buscar configurações de manipulação de indicações
function get_manipulacao_indicacoes()
{
    global $mysqli;
    $qry = "SELECT * FROM manipulacao_indicacoes WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    $config = mysqli_fetch_assoc($result);
    
    // Se não existir, criar com valores padrão
    if (!$config) {
        $create_qry = "INSERT INTO manipulacao_indicacoes (id, dar_indicacoes, roubar_indicacoes, ativo) VALUES (1, 3, 1, 0)";
        mysqli_query($mysqli, $create_qry);
        $result = mysqli_query($mysqli, $qry);
        $config = mysqli_fetch_assoc($result);
    }
    
    return $config;
}

# Função para atualizar os dados da tabela afiliados_config
function update_afiliados_config($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE afiliados_config SET 
        cpaLvl1 = ?, 
        cpaLvl2 = ?, 
        cpaLvl3 = ?,
        chanceCpa = ?,
        minDepForCpa = ?,
        minResgate = ?,
        pagar_baus = ?
        WHERE id = 1");

    $qry->bind_param(
        "ddddddi",
        $data['cpaLvl1'],
        $data['cpaLvl2'],
        $data['cpaLvl3'],
        $data['chanceCpa'],
        $data['minDepForCpa'],
        $data['minResgate'],
        $data['pagar_baus']
    );
    return $qry->execute();
}

# Função para atualizar os dados da tabela config (baús)
function update_config($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET 
        qntsbaus = ?, 
        niveisbau = ?, 
        pessoasbau = ?
        WHERE id = 1");

    $qry->bind_param(
        "dsd",
        $data['qntsbaus'],
        $data['niveisbau'],
        $data['pessoasbau']
    );
    return $qry->execute();
}

# Função para atualizar configurações de manipulação
function update_manipulacao_indicacoes($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE manipulacao_indicacoes SET 
        dar_indicacoes = ?, 
        roubar_indicacoes = ?,
        ativo = ?
        WHERE id = 1");

    $qry->bind_param(
        "iii",
        $data['dar_indicacoes'],
        $data['roubar_indicacoes'],
        $data['ativo']
    );
    return $qry->execute();
}

# Se o formulário for enviado, atualizar os dados
$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Atualizar configurações de afiliados
    $dataAfiliados = [
        'cpaLvl1' => floatval($_POST['cpaLvl1']),
        'cpaLvl2' => floatval($_POST['cpaLvl2']),
        'cpaLvl3' => floatval($_POST['cpaLvl3']),
        'chanceCpa' => floatval($_POST['chanceCpa']),
        'minDepForCpa' => floatval($_POST['minDepForCpa']),
        'minResgate' => floatval($_POST['minResgate']),
        'pagar_baus' => isset($_POST['pagar_baus']) ? 1 : 0,
    ];

    // Atualizar configurações de baús
    $dataConfig = [
        'qntsbaus' => floatval($_POST['qntsbaus']),
        'niveisbau' => $_POST['niveisbau'],
        'pessoasbau' => floatval($_POST['pessoasbau']),
    ];

    // Atualizar configurações de manipulação de indicações
    $dataManipulacao = [
        'dar_indicacoes' => intval($_POST['dar_indicacoes']),
        'roubar_indicacoes' => intval($_POST['roubar_indicacoes']),
        'ativo' => isset($_POST['manipulacao_ativa']) ? 1 : 0,
    ];

    $sucessoAfiliados = update_afiliados_config($dataAfiliados);
    $sucessoConfig = update_config($dataConfig);
    $sucessoManipulacao = update_manipulacao_indicacoes($dataManipulacao);

    if ($sucessoAfiliados && $sucessoConfig && $sucessoManipulacao) {
        $toastType = 'success';
        $toastMessage = 'Configurações atualizadas com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar as configurações. Tente novamente.';
    }
}

# Buscar os dados atuais
$afiliadosConfig = get_afiliados_config();
$config = get_config();
$manipulacaoConfig = get_manipulacao_indicacoes();
?>

<head>
    <?php $title = "Configurações de Afiliados e Baús";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
</head>

<body>

    <!-- Top Bar Start -->
    <?php include 'partials/topbar.php' ?>
    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    <?php include 'partials/startbar.php' ?>
    <!-- end leftbar-tab-menu-->

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Gerenciamento de Afiliados e Baús da Plataforma</h4>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="">
                                    
                                    <!-- Seção de Configurações de Afiliados -->
                                    <h5 class="mb-3 text-primary"><i class="iconoir-percentage"></i> Configurações de Afiliados</h5>
                                    
                                    <!-- Ativar/Desativar Pagamentos de Baú -->
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <div class="card border-primary">
                                                <div class="card-body">
                                                    <div class="form-check form-switch form-switch-lg">
                                                        <input class="form-check-input" type="checkbox" id="pagar_baus" 
                                                               name="pagar_baus" <?= isset($afiliadosConfig['pagar_baus']) && $afiliadosConfig['pagar_baus'] == 1 ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="pagar_baus">
                                                            <h5 class="mb-0">
                                                                <i class="iconoir-wallet"></i> Ativar Pagamentos de Baú
                                                            </h5>
                                                            <small class="text-muted">Quando ativado, os afiliados poderão receber os pagamentos dos baús conquistados. Quando desativado, os baús não serão pagos aos afiliados.</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- CPA Level 1 -->
                                        <div class="col-md-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-medal"></i> CPA Nível 1
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Valor de comissão CPA para afiliados nível 1.
                                                    </p>
                                                    <div class="input-group">
                                                        <span class="input-group-text">%</span>
                                                        <input type="number" step="0.01" name="cpaLvl1" class="form-control"
                                                            value="<?= $afiliadosConfig['cpaLvl1'] ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CPA Level 2 -->
                                        <div class="col-md-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-medal"></i> CPA Nível 2
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Valor de comissão CPA para afiliados nível 2.
                                                    </p>
                                                    <div class="input-group">
                                                        <span class="input-group-text">%</span>
                                                        <input type="number" step="0.01" name="cpaLvl2" class="form-control"
                                                            value="<?= $afiliadosConfig['cpaLvl2'] ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CPA Level 3 -->
                                        <div class="col-md-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-medal"></i> CPA Nível 3
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Valor de comissão CPA para afiliados nível 3.
                                                    </p>
                                                    <div class="input-group">
                                                        <span class="input-group-text">%</span>
                                                        <input type="number" step="0.01" name="cpaLvl3" class="form-control"
                                                            value="<?= $afiliadosConfig['cpaLvl3'] ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Chance CPA -->
                                        <div class="col-md-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-dice"></i> Chance CPA
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Porcentagem de chance de ganhar CPA. Ex: 50 = 50% de chance.
                                                    </p>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" name="chanceCpa" class="form-control"
                                                            value="<?= $afiliadosConfig['chanceCpa'] ?>" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Depósito Mínimo para CPA -->
                                        <div class="col-md-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-wallet"></i> Depósito Mín. para Baú
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Valor mínimo que o convidado deve depositar para que o afiliado possa ganhar o baú. Ex: R$ 10,00.
                                                    </p>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" step="0.01" name="minDepForCpa" class="form-control"
                                                            value="<?= $afiliadosConfig['minDepForCpa'] ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Valor Mínimo Apostado -->
                                        <div class="col-md-4">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-credit-card"></i> Valor Mín. Apostado
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Valor mínimo que o convidado deve apostar para que o afiliado possa ganhar o baú. Ex: R$ 500,00.
                                                    </p>
                                                    <div class="input-group">
                                                        <span class="input-group-text">R$</span>
                                                        <input type="number" step="0.01" name="minResgate" class="form-control"
                                                            value="<?= $afiliadosConfig['minResgate'] ?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <!-- Seção de Manipulação de Indicações -->
                                    <h5 class="mb-3 text-warning"><i class="iconoir-shuffle"></i> Manipulação de Indicações (Sistema 3/1)</h5>
                                    <div class="alert alert-warning" role="alert">
                                        <i class="iconoir-warning-triangle me-2"></i>
                                        <strong>Atenção:</strong> Este sistema controla quantas indicações são reais e quantas a casa "rouba". 
                                        Exemplo: 3/1 significa que a cada 3 indicações dadas ao afiliado, 1 é subtraída pela casa.
                                    </div>

                                    <div class="row">
                                        <!-- Ativar/Desativar Manipulação -->
                                        <div class="col-md-12 mb-3">
                                            <div class="card border-warning">
                                                <div class="card-body">
                                                    <div class="form-check form-switch form-switch-lg">
                                                        <input class="form-check-input" type="checkbox" id="manipulacao_ativa" 
                                                               name="manipulacao_ativa" <?= $manipulacaoConfig['ativo'] == 1 ? 'checked' : '' ?>>
                                                        <label class="form-check-label" for="manipulacao_ativa">
                                                            <h5 class="mb-0">
                                                                Ativar Manipulação de Indicações
                                                            </h5>
                                                            <small class="text-muted">Quando ativado, o sistema irá manipular as indicações conforme configurado abaixo. Quando desativado, todas as indicações serão contabilizadas normalmente.</small>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Dar Indicações -->
                                        <div class="col-md-6">
                                            <div class="card mb-4 border-success">
                                                <div class="card-body">
                                                    <h5 class="card-title text-success">
                                                        <i class="iconoir-plus-circle"></i> Dar Indicações
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-3">
                                                        Quantas indicações serão creditadas ao afiliado antes de roubar.
                                                    </p>
                                                    <div class="input-group input-group-lg">
                                                        <span class="input-group-text bg-success text-white">
                                                            <i class="iconoir-user-plus"></i>
                                                        </span>
                                                        <input type="number" min="1" max="100" name="dar_indicacoes" 
                                                               class="form-control form-control-lg" 
                                                               value="<?= $manipulacaoConfig['dar_indicacoes'] ?>" required>
                                                        <span class="input-group-text">indicações</span>
                                                    </div>
                                                    <small class="text-muted mt-2 d-block">
                                                        <i class="iconoir-info-circle"></i> Valor padrão recomendado: 3
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Roubar Indicações -->
                                        <div class="col-md-6">
                                            <div class="card mb-4 border-danger">
                                                <div class="card-body">
                                                    <h5 class="card-title text-danger">
                                                        <i class="iconoir-minus-circle"></i> Roubar Indicações
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-3">
                                                        Quantas indicações a casa irá subtrair após dar as indicações.
                                                    </p>
                                                    <div class="input-group input-group-lg">
                                                        <span class="input-group-text bg-danger text-white">
                                                            <i class="iconoir-user-minus"></i>
                                                        </span>
                                                        <input type="number" min="1" max="100" name="roubar_indicacoes" 
                                                               class="form-control form-control-lg" 
                                                               value="<?= $manipulacaoConfig['roubar_indicacoes'] ?>" required>
                                                        <span class="input-group-text">indicações</span>
                                                    </div>
                                                    <small class="text-muted mt-2 d-block">
                                                        <i class="iconoir-info-circle"></i> Valor padrão recomendado: 1 ou 2
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <hr class="my-4">

                                    <!-- Seção de Configurações de Baús -->
                                    <h5 class="mb-3 text-primary"><i class="iconoir-box"></i> Configurações de Baús</h5>
                                    <div class="row">
                                        <!-- Quantidade de Baús -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-box"></i> Qtd. Baús
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Quantidade de baús que será exibido na tela de afiliados. Ex: 50, será 50 baús disponíveis.
                                                    </p>
                                                    <input type="text" name="qntsbaus" class="form-control"
                                                        value="<?= $config['qntsbaus'] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Valores dos Baús -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-group"></i> Valores dos Baús
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Valores de cada baú baseado na quantidade de baús exibidos. Ex: 10,20 para 20 baús exibidos = 10 baús R$ 10 e + 10 baús R$ 20.
                                                    </p>
                                                    <input type="text" name="niveisbau" class="form-control"
                                                        value="<?= $config['niveisbau'] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quantidade de Pessoas -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-community"></i> Qtd. Pessoas
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Quantidade de pessoas que será exibido na tela de afiliados dentro baú. Ex: Primeiro baú = 1 pessoa.
                                                    </p>
                                                    <input type="text" name="pessoasbau" class="form-control"
                                                        value="<?= $config['pessoasbau'] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="iconoir-check-circle"></i> Salvar Todas as Configurações
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->
            </div><!-- container -->
            
            <?php include 'partials/endbar.php' ?>
            <?php include 'partials/footer.php' ?>
            
        </div><!-- page content -->
    </div><!-- page-wrapper -->

    <!-- Toast container -->
    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Javascript -->
    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>

    <!-- Função de Toast -->
    <script>
        function showToast(type, message) {
            var toastPlacement = document.getElementById('toastPlacement');
            var toast = document.createElement('div');
            toast.className = `toast align-items-center bg-light border-0 fade show`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="toast-header">
                    <img src="/uploads/logo.png.webp" alt="" height="20" class="me-1">
                    <h5 class="me-auto my-0">Atualização</h5>
                    <small>Agora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
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
    </script>

    <!-- Exibir o Toast baseado nas ações do formulário -->
    <?php if ($toastType && $toastMessage): ?>
        <script>
            showToast('<?= $toastType ?>', '<?= $toastMessage ?>');
        </script>
    <?php endif; ?>

</body>
</html>