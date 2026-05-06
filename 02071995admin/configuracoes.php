<?php include 'partials/html.php' ?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once "services/database.php";
include_once 'logs/registrar_logs.php';
include_once "services/funcao.php";
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once 'services/checa_login_adm.php';
include_once "services/CSRF_Protect.php";
include_once "validar_2fa.php";
$csrf = new CSRF_Protect();

checa_login_adm();

if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

function get_afiliados_config()
{
    global $mysqli;
    $qry = "SELECT * FROM config WHERE id=1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result);
}

function update_config($data)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET 
        minsaque = ?, 
        maxsaque = ?, 
        saque_automatico = ?, 
        rollover = ?, 
        mindep = ?, 
        jackpot = ?,
        limite_saque = ?
        WHERE id = 1");

    $qry->bind_param(
        "ddddsdd",
        $data['minsaque'],
        $data['maxsaque'],
        $data['saque_automatico'],
        $data['rollover'],
        $data['mindep'],
        $data['jackpot'],
        $data['limite_saque']
    );
    return $qry->execute();
}

$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'minsaque' => floatval($_POST['minsaque']),
        'maxsaque' => floatval($_POST['maxsaque']),
        'saque_automatico' => floatval($_POST['saque_automatico']),
        'mindep' => $_POST['mindep'],
        'jackpot' => floatval($_POST['jackpot']),
        'rollover' => floatval($_POST['rollover']),
        'limite_saque' => floatval($_POST['limite_saque']),
        'comissao' => floatval($_POST['comissao'])
    ];

    if (update_config($data)) {
        $toastType = 'success';
        $toastMessage = 'Configurações atualizadas com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar as configurações. Tente novamente.';
    }
}

$config = get_afiliados_config();
?>

<head>
    <?php $title = "Configurações de Afiliados";
    include 'partials/title-meta.php' ?>

    <link rel="stylesheet" href="assets/libs/jsvectormap/jsvectormap.min.css">
    <?php include 'partials/head-css.php' ?>
</head>

<body>

    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Gerenciamento de valores da plataforma</h4>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="">
                                    <div class="row">
                                        
                                        <!-- Saque Minimo -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-user"></i> Saque Minimo (R$)
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Digite o valor do saque mínimo dentro da plataforma.
                                                    </p>
                                                    <input type="text" name="minsaque" class="form-control"
                                                        value="<?= $config['minsaque'] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Saque Maximo -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-user"></i> Saque Máximo
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Digite o valor do saque máximo dentro da plataforma.
                                                    </p>
                                                    <input type="text" name="maxsaque" class="form-control"
                                                        value="<?= $config['maxsaque'] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Saque automatico maximo -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-group"></i> Saque Automático
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Digite o valor de saque automático, caso o saque seja no valor, ele é enviado diretamente ao jogador.
                                                    </p>
                                                    <input type="text" name="saque_automatico" class="form-control"
                                                        value="<?= $config['saque_automatico'] ?>">
                                                </div>
                                            </div>
                                        </div>

                                         <!-- Saque automatico maximo -->
                                         <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-group"></i> Limite Diário de Saques
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Digite a quantia máxima de saques que o usuário pode solicitar por dia.
                                                    </p>
                                                    <input type="text" name="limite_saque" class="form-control"
                                                        value="<?= $config['limite_saque'] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Depósito minimo -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-community"></i> Depósito Mínimo
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Digite o valor do depósito mínimo.
                                                    </p>
                                                    <input type="text" name="mindep" class="form-control"
                                                        value="<?= $config['mindep'] ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!--    
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title"><i class="iconoir-percentage-circle"></i>
                                                        Valor no jackpot</h5>
                                                    <input type="text" name="jackpot" class="form-control"
                                                        value="<?= $config['jackpot'] ?>" required>
                                                </div>
                                            </div>
                                        </div> -->

                                        <!-- Rollover -->
                                        <div class="col-md-6">
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <i class="iconoir-percentage-circle"></i> Rollover
                                                    </h5>
                                                    <p class="card-subtitle text-muted mb-2">
                                                        Multiplicador de Rollover no depósito.
                                                    </p>
                                                    <input type="text" name="rollover" class="form-control"
                                                        value="<?= $config['rollover'] ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success">Salvar Configurações</button>
                                    </div>
                                </form>
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
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            toast.innerHTML = `
                <div class="toast-header">
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

    <?php if ($toastType && $toastMessage): ?>
        <script>
            showToast('<?= $toastType ?>', '<?= $toastMessage ?>');
        </script>
    <?php endif; ?>

</body>
</html>