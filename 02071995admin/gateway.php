<?php include 'partials/html.php'; ?>

<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once "services/database.php";
include_once 'logs/registrar_logs.php';
include_once "services/funcao.php";
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once 'services/checa_login_adm.php';
include_once "services/CSRF_Protect.php";

$csrf = new CSRF_Protect();
checa_login_adm();

if ($_SESSION['data_adm']['status'] != '1') {
    header("Location: bloqueado.php");
    exit;
}

/**
 * =====================================================
 * BUSCAR CONFIG BSPAY
 * =====================================================
 */
$bspay = $mysqli->query("SELECT * FROM bspay WHERE id = 1")->fetch_assoc();

/**
 * =====================================================
 * SALVAR CONFIGURAÇÃO
 * =====================================================
 */
$toastType = null;
$toastMessage = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $client_id     = trim($_POST['client_id']);
    $url           = trim($_POST['url']);

    $stmt = $mysqli->prepare("
        UPDATE bspay
        SET client_id = ?, url = ?, ativo = 1
        WHERE id = 1
    ");
    $stmt->bind_param("ss", $client_id, $url);

    if ($stmt->execute()) {

        // DESATIVA QUALQUER OUTRO GATEWAY (SEGURANÇA)
        $mysqli->query("UPDATE nextpay SET ativo = 0 WHERE id = 1");
        $mysqli->query("UPDATE expfypay SET ativo = 0 WHERE id = 1");
        $mysqli->query("UPDATE aurenpay SET ativo = 0 WHERE id = 1");
        $mysqli->query("UPDATE versell SET ativo = 0 WHERE id = 1");

        $toastType = 'success';
        $toastMessage = 'GGPix ativado e credenciais salvas com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao salvar configurações.';
    }
}
?>

<head>
    <?php 
    $title = "Configuração GGPix";
    include 'partials/title-meta.php';
    include 'partials/head-css.php';
    ?>
</head>

<body>

<?php include 'partials/topbar.php'; ?>
<?php include 'partials/startbar.php'; ?>

<div class="page-wrapper">
    <div class="page-content">
        <div class="container-xxl">

            <div class="row justify-content-center mb-3">
                <div class="col-lg-12">
                    <div class="card border shadow-none" style="background-color: rgba(30, 190, 165, 0.05); border: 1px dashed #1ebea5 !important;">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="text-uppercase font-12 fw-bold mb-2" style="color: #1ebea5;">Gateway GGPix</h6>
                                    <p class="mb-2">Gateway de pagamento PIX integrado com a <strong>GGPix API</strong>.</p>
                                    <small class="text-muted">Configure sua API Key para ativar os depósitos via PIX.</small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="https://ggpixapi.com" target="_blank" class="btn btn-success">
                                        <i class="fas fa-external-link-alt me-1"></i> ACESSAR GGPIX
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-qrcode me-2"></i>Gateway Ativo: GGPix
                    </h4>
                </div>

                <div class="card-body">

                    <div class="alert alert-success">
                        <strong>Status:</strong> GGPix está ativo no sistema
                    </div>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">API Key</label>
                            <input type="text" name="client_id" class="form-control" required
                                   placeholder="gk_..."
                                   value="<?= htmlspecialchars($bspay['client_id'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Endpoint</label>
                            <select name="url" class="form-select" required>
                                <option value="https://ggpixapi.com/api/v1"
                                    <?= (isset($bspay['url']) && $bspay['url'] === 'https://ggpixapi.com/api/v1') ? 'selected' : '' ?>>
                                    GGPix (ggpixapi.com/api/v1)
                                </option>
                            </select>
                        </div>

                        <button class="btn btn-primary w-100">
                            <i class="fas fa-save me-1"></i>Salvar Configuração GGPix
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'partials/endbar.php'; ?>
<?php include 'partials/footer.php'; ?>
<?php include 'partials/vendorjs.php'; ?>
<script src="assets/js/app.js"></script>

<?php if (isset($toastType) && $toastType): ?>
<script>
    alert("<?= $toastMessage ?>");
</script>
<?php endif; ?>

</body>
</html>