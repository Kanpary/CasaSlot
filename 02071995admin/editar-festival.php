<?php include 'partials/html.php' ?>

<?php
#======================================#
ini_set('display_errors', 1);
error_reporting(E_ALL);
#======================================#
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include_once "services/database.php";
include_once "services/funcao.php";
include_once "services/crud.php";
include_once "services/crud-adm.php";
include_once 'services/checa_login_adm.php';
include_once "services/CSRF_Protect.php";
include_once "validar_2fa.php";
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

# Função para buscar os banners
function get_banners() {
    global $mysqli;
    $qry = "SELECT * FROM festival";
    $result = mysqli_query($mysqli, $qry);
    $banners = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $banners[] = $row;
        }
    }
    return $banners;
}

# Função para atualizar o banner
function update_banner($id, $titulo, $status, $img = null) {
    global $mysqli;

    if ($img) {
        $qry = $mysqli->prepare("UPDATE festival SET titulo = ?, status = ?, img = ? WHERE id = ?");
        $qry->bind_param("sisi", $titulo, $status, $img, $id);
    } else {
        $qry = $mysqli->prepare("UPDATE festival SET titulo = ?, status = ? WHERE id = ?");
        $qry->bind_param("sii", $titulo, $status, $id);
    }

    return $qry->execute();
}

# Se o formulário for enviado, atualizar os dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $titulo = $_POST['titulo'];
    $status = intval($_POST['status']);

    // Buscar a imagem atual no banco de dados
    $query = "SELECT img FROM festival WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $promocao = $result->fetch_assoc();
    $img = $promocao['img'];

    // Verificar se uma nova imagem foi enviada
    if (!empty($_FILES['img']['name'])) {
        // Definir o diretório de upload (no mesmo local da imagem atual)
        $upload_dir = "../holiday/14/";
        
        // Usar o nome da imagem atual ou o nome original se o usuário enviou uma nova imagem
        $img_path = $upload_dir . basename($img); // Usar o mesmo nome da imagem atual
        
        // Apagar a imagem antiga (se necessário)
        if (file_exists($img_path)) {
            unlink($img_path); // Deletar a imagem antiga
        }

        // Mover a nova imagem para o mesmo diretório e manter o nome
        if (move_uploaded_file($_FILES["img"]["tmp_name"], $img_path)) {
            // Caso a imagem seja enviada corretamente, manter o nome atual
            $img = basename($img); // Usar o nome da imagem original
        } else {
            // Se houver erro ao mover a imagem, definir mensagem de erro
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar a imagem. Tente novamente.';
            error_log("Erro ao mover o arquivo da imagem para $img_path");
        }
    } else {
        // Caso não tenha enviado uma nova imagem, manter a imagem atual
        $img = $img;
    }

    // Atualizar a promoção no banco de dados
    if (update_banner($id, $titulo, $status, '/holiday/14/' .$img)) {
        $toastType = 'success';
        $toastMessage = 'Promoção atualizada com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao atualizar a promoção. Tente novamente.';
        error_log("Erro ao atualizar a promoção com ID $id");
    }
}

# Buscar os banners atuais
$banners = get_banners();
?>

<head>
    <?php $title = "Gerenciamento de festival";
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
                                <h4 class="card-title">Gerenciamento de festival</h4>
                            </div>

                            <div class="card-body">
                                <table class="table table-centered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Título</th>
                                            <th>Imagem</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($banners as $banner): ?>
                                            <tr>
                                                <td><?= $banner['id']; ?></td>
                                                <td><?= $banner['titulo']; ?></td>
                                                <td><img src="<?= $banner['img']; ?>?v=<?= time(); ?>" alt="Banner" width="100"></td>
                                                <td><?= $banner['status'] == 1 ? 'Ativo' : 'Inativo'; ?></td>
                                                <td>
                                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#editBannerModal<?= $banner['id']; ?>">Editar</button>
                                                </td>
                                            </tr>

                                            <!-- Modal de Edição -->
                                            <div class="modal fade" id="editBannerModal<?= $banner['id']; ?>" tabindex="-1" aria-labelledby="editBannerLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editBannerLabel">Editar Banner</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form method="POST" enctype="multipart/form-data">
                                                                <input type="hidden" name="id" value="<?= $banner['id']; ?>">
                                                                <div class="mb-3">
                                                                    <label for="titulo" class="form-label">Título</label>
                                                                    <input type="text" class="form-control" name="titulo" value="<?= $banner['titulo']; ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="img" class="form-label">Imagem</label>
                                                                    <input type="file" class="form-control" name="img">
                                                                    <small class="text-muted">Deixe em branco se não quiser alterar a imagem.</small>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="status" class="form-label">Status</label>
                                                                    <select class="form-select" name="status">
                                                                        <option value="1" <?= $banner['status'] == 1 ? 'selected' : ''; ?>>Ativo</option>
                                                                        <option value="0" <?= $banner['status'] == 0 ? 'selected' : ''; ?>>Inativo</option>
                                                                    </select>
                                                                </div>
                                                                <div class="text-center">
                                                                    <button type="submit" class="btn btn-success">Salvar Alterações</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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

        // Mostrar Toast com base no resultado do PHP
        <?php if (isset($toastType) && isset($toastMessage)): ?>
            showToast("<?= $toastType; ?>", "<?= $toastMessage; ?>");
        <?php endif; ?>
    </script>
</body>
</html>
