<?php include 'partials/html.php' ?>

<?php
#======================================#
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
#======================================#
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once "services/database.php";
include_once "services/funcao.php";
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once 'services/checa_login_adm.php';
include_once "services/CSRF_Protect.php";
$csrf = new CSRF_Protect();
#======================================#
#expulsa user
checa_login_adm();
#======================================#
//inicio do script expulsa usuario bloqueado
if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}

# Função para buscar o jackpot atual
function get_jackpot_atual()
{
    global $mysqli;
    $qry = "SELECT jackpot FROM config WHERE id = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['jackpot'];
}

function get_jackpot_ativado()
{
    global $mysqli;
    $qry = "SELECT jackpot_ativado FROM config WHERE id = 1";
    $result = mysqli_query($mysqli, $qry);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return intval($row['jackpot_ativado']);
    }
    return 0;
}


# Atualiza o nome da imagem na coluna jackpot_custom da tabela config
function update_jackpot_custom($filename)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET jackpot_custom = ? WHERE id = 1");
    $qry->bind_param("s", $filename);
    return $qry->execute();
}

function ativar_desativar_jackpot($opcao)
{
    global $mysqli;
    $qry = $mysqli->prepare("UPDATE config SET jackpot_ativado = ? WHERE id = 1");
    $qry->bind_param("i", $opcao);
    return $qry->execute();
}

# Lógica principal
$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jackpot_selecionado = intval($_POST['jackpot']);

    $jackpot_ativado = isset($_POST['jackpot_ativado']) ? intval($_POST['jackpot_ativado']) : 0;

    // Ativa ou desativa o jackpot no banco
    if (ativar_desativar_jackpot($jackpot_ativado)) {
        $toastType = 'success';
        $toastMessage = 'Status do Jackpot atualizado com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar o status do Jackpot.';
    }

    // Se enviou uma imagem, atualiza o jackpot customizado
    if (!empty($_FILES['custom_jackpot']['name'])) {
        $upload_dir = "../cocos/icon/vjp/";

        $ext = pathinfo($_FILES['custom_jackpot']['name'], PATHINFO_EXTENSION);
        $random_name = 'jackpot_' . uniqid() . '.' . $ext;
        $destino = $upload_dir . $random_name;

        if (move_uploaded_file($_FILES["custom_jackpot"]["tmp_name"], $destino)) {
            if (update_jackpot_custom($random_name)) {
                $toastType = 'success';
                $toastMessage = 'Imagem customizada atualizada com sucesso!';
            } else {
                $toastType = 'error';
                $toastMessage = 'Erro ao salvar imagem no banco.';
            }
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao mover o arquivo.';
        }
    } 
}




# Buscar o jackpot atual
$jackpot_atual = get_jackpot_atual();
$jackpot_ativado = get_jackpot_ativado();

?>

<head>
    <?php $title = "Configurações de Jackpot"; ?>
    <?php include 'partials/title-meta.php' ?>
    <?php include 'partials/head-css.php' ?>
</head>

<style>
    .img-container {
        width: 100%;
        height: 150px;
        /* Ajuste a altura conforme necessário */
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
    }

    .img-container img {
        width: auto;
        object-fit: cover;
    }
</style>


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
                                <h4 class="card-title">Selecione um jackpot para personalizar</h4>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <!-- Campo para custom jackpot -->
                                    <div class="row mt-12">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <!-- Switch de ativar/desativar jackpot -->
                                                    <div class="row mb-4 align-items-center">
                                                        <div class="col-auto">
                                                            <label for="jackpot_ativado"
                                                                class="col-form-label fw-semibold">Status do
                                                                Jackpot:</label>
                                                        </div>
                                                        <div class="col-auto">
                                                            <select class="form-select" id="jackpot_ativado"
                                                                name="jackpot_ativado">
                                                                <option value="1" <?= isset($jackpot_ativado) && $jackpot_ativado == 1 ? 'selected' : '' ?>>Ativado
                                                                </option>
                                                                <option value="0" <?= isset($jackpot_ativado) && $jackpot_ativado == 0 ? 'selected' : '' ?>>Desativado
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <label for="custom_jackpot" class="form-label">Custom
                                                        Jackpot</label>
                                                    <?php
                                                    // Verificar se a imagem "jackpot_custom.png" existe
                                                    $custom_jackpot_path = '/cocos/icon/vjp/' . $dataconfig['jackpot_custom'];
                                                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $custom_jackpot_path)) {
                                                        echo "<div class='mb-3'>
                        <img src='$custom_jackpot_path' class='img-fluid' alt='Custom Jackpot' style='max-height: 150px;'>
                    </div>";
                                                    } else {
                                                        echo "<p class='text-muted'>Nenhuma imagem customizada de jackpot enviada ainda.</p>";
                                                    }
                                                    ?>
                                                    <input type="file" name="custom_jackpot" id="custom_jackpot"
                                                        class="form-control">
                                                    <input type="radio" style="margin-top: 10px" name="jackpot"
                                                        value="5" id="custom_jackpot_radio" <?= $jackpot_atual == 5 ? 'checked' : '' ?>>
                                                    <label for="custom_jackpot_radio"
                                                        style="margin-top: -5px">Selecionar Custom Jackpot</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success">Salvar Jackpot</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div><!-- end row -->
            </div><!-- container -->
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
                    
                    <h5 class="me-auto my-0">Phillyps</h5>
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