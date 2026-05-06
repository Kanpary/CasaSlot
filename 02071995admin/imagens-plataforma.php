<?php include 'partials/html.php' ?>

<?php
#======================================#
ini_set('display_errors', 0);
error_reporting(E_ALL);
#======================================#
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
#======================================#
#expulsa user
checa_login_adm();
#======================================#
//inicio do script expulsa usuario bloqueado
if ($_SESSION['data_adm']['status'] != '1') {
    echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
    exit();
}


// Função para fazer o upload e renomear o arquivo com a extensão original
function upload_and_rename_as_original($file)
{
    $upload_dir = "../uploads/";
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Tipos permitidos
    $allowed_extensions = ['png','jpg','jpeg','webp','gif','ico','avif'];
    if (!in_array($file_extension, $allowed_extensions, true)) {
        return false;
    }

    // Detecta MIME de forma confiável
    $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : null;
    $mime = $finfo ? finfo_file($finfo, $file['tmp_name']) : ($file['type'] ?? '');
    if ($finfo) { finfo_close($finfo); }

    // Validação por tipo: AVIF pode não ser reconhecido por getimagesize em PHP 8.0
    $is_avif = ($file_extension === 'avif') || (stripos($mime, 'image/avif') !== false);

    if (!$is_avif) {
        $check = @getimagesize($file['tmp_name']);
        if ($check === false) {
            return false;
        }
    } else {
        // Para AVIF, apenas valida o MIME básico
        if ($mime && stripos($mime, 'image/') !== 0) {
            return false;
        }
    }

    // Gera nome e move
    $random_name = uniqid('img_', true);
    $target_file = $upload_dir . $random_name . '.' . $file_extension;
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return $random_name . '.' . $file_extension;
    }

    return false;
}



# Função para atualizar logo e/ou favicon na tabela config
function update_config_images($logo = null, $favicon = null, $download = null, $icone_download = null, $carregamento_img = null, $snow_flakes = null, $avatar = null)
{
    global $mysqli;

    $qry_string = "UPDATE config SET ";
    $params = [];
    $types = '';

    // Adicionar logo à consulta se estiver presente
    if ($logo !== null) {
        $qry_string .= "logo = ?, ";
        $params[] = $logo;
        $types .= 's';
    }

    // Adicionar favicon à consulta se estiver presente
    if ($favicon !== null) {
        $qry_string .= "favicon = ?, ";
        $params[] = $favicon;
        $types .= 's';
    }

    // Adicionar download à consulta se estiver presente
    if ($download !== null) {
        $qry_string .= "download = ?, ";
        $params[] = $download;
        $types .= 's';
    }

    // Adicionar download à consulta se estiver presente
    if ($icone_download !== null) {
        $qry_string .= "icone_download = ?, ";
        $params[] = $icone_download;
        $types .= 's';
    }

    // Adicionar download à consulta se estiver presente
    if ($carregamento_img !== null) {
        $qry_string .= "carregamento_img = ?, ";
        $params[] = $carregamento_img;
        $types .= 's';
    }

    // Adicionar download à consulta se estiver presente
    if ($snow_flakes !== null) {
        $qry_string .= "snow_flakes = ?, ";
        $params[] = $snow_flakes;
        $types .= 's';
    }

    // Adicionar download à consulta se estiver presente
    if ($avatar !== null) {
        $qry_string .= "avatar = ?, ";
        $params[] = $avatar;
        $types .= 's';
    }


    // Remover a última vírgula e espaço
    $qry_string = rtrim($qry_string, ', ') . " WHERE id = 1";

    $qry = $mysqli->prepare($qry_string);
    $qry->bind_param($types, ...$params);

    return $qry->execute();
}

# Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $toastType = null;
    $toastMessage = '';

    $logo = null;
    $favicon = null;
    $download = null;
    $icone_download = null;
    $carregamento_img = null;
    $snow_flakes = null;
    $avatar = null;

    if (!empty($_FILES['topimagemfundo']['name'])) {
        $file_extension = strtolower(pathinfo($_FILES['topimagemfundo']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['png','jpg','jpeg','webp','gif','avif'];
        if (in_array($file_extension, $allowed_extensions, true)) {
            $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : null;
            $mime = $finfo ? finfo_file($finfo, $_FILES['topimagemfundo']['tmp_name']) : ($_FILES['topimagemfundo']['type'] ?? '');
            if ($finfo) { finfo_close($finfo); }
            $is_avif = ($file_extension === 'avif') || (stripos($mime, 'image/avif') !== false);
            $valid = true;
            if (!$is_avif) {
                $check = @getimagesize($_FILES['topimagemfundo']['tmp_name']);
                if ($check === false) { $valid = false; }
            } else {
                if ($mime && stripos($mime, 'image/') !== 0) { $valid = false; }
            }
            if ($valid) {
                $destino1 = $_SERVER['DOCUMENT_ROOT'] . '/siteadmin/skin/lobby_asset/2-1-22/common/common/bg_pattern_tile2.' . $file_extension;
                $destino2 = $_SERVER['DOCUMENT_ROOT'] . '/siteadmin/skin/lobby_asset/2-0-22/common/common/bg_pattern_tile2.' . $file_extension;
                if (move_uploaded_file($_FILES['topimagemfundo']['tmp_name'], $destino1)) {
                    copy($destino1, $destino2);
                    $toastType = 'success';
                    $toastMessage = 'Imagem do Fundo Header atualizada com sucesso!';
                } else {
                    $toastType = 'error';
                    $toastMessage = 'Erro ao salvar a imagem do Fundo Header.';
                }
            } else {
                $toastType = 'error';
                $toastMessage = 'O arquivo enviado para o Fundo Header não é uma imagem válida.';
            }
        } else {
            $toastType = 'error';
            $toastMessage = 'Extensão não permitida para o Fundo Header.';
        }
    }

    // Verificar se o logo foi enviado
    if (!empty($_FILES['logo']['name'])) {
        $logo = upload_and_rename_as_original($_FILES['logo'], 'logo'); // Agora preserva a extensão original
        if (!$logo) {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar o logo. Verifique a extensão do arquivo.';
        }
    }

    // Verificar se o favicon foi enviado
    if (!empty($_FILES['favicon']['name'])) {
        $favicon = upload_and_rename_as_original($_FILES['favicon'], 'favicon'); // Agora preserva a extensão original
        if (!$favicon) {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar o favicon. Verifique a extensão do arquivo.';
        }
    }

    // Verificar se o download foi enviado
    if (!empty($_FILES['download']['name'])) {
        $download = upload_and_rename_as_original($_FILES['download'], 'download'); // Agora preserva a extensão original
        if (!$download) {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar o download. Verifique a extensão do arquivo.';
        }
    }


    // Verificar se o download foi enviado
    if (!empty($_FILES['icone_download']['name'])) {
        $icone_download = upload_and_rename_as_original($_FILES['icone_download'], 'icone_download'); // Agora preserva a extensão original
        if (!$icone_download) {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar o icone_download. Verifique a extensão do arquivo.';
        }
    }

    // Verificar se o download foi enviado
    if (!empty($_FILES['carregamento_img']['name'])) {
        $carregamento_img = upload_and_rename_as_original($_FILES['carregamento_img'], 'carregamento_img'); // Agora preserva a extensão original
        if (!$carregamento_img) {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar o carregamento_img. Verifique a extensão do arquivo.';
        }
    }


    if (!empty($_FILES['imagem_fundo']['name'])) {
        $file_extension = strtolower(pathinfo($_FILES['imagem_fundo']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = ['png','jpg','jpeg','webp','gif','avif'];
        if (in_array($file_extension, $allowed_extensions, true)) {
            $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : null;
            $mime = $finfo ? finfo_file($finfo, $_FILES['imagem_fundo']['tmp_name']) : ($_FILES['imagem_fundo']['type'] ?? '');
            if ($finfo) { finfo_close($finfo); }
            $is_avif = ($file_extension === 'avif') || (stripos($mime, 'image/avif') !== false);
            $valid = true;
            if (!$is_avif) {
                $check = @getimagesize($_FILES['imagem_fundo']['tmp_name']);
                if ($check === false) { $valid = false; }
            } else {
                if ($mime && stripos($mime, 'image/') !== 0) { $valid = false; }
            }
            if ($valid) {
                $destino1 = $_SERVER['DOCUMENT_ROOT'] . '/siteadmin/skin/lobby_asset/2-1-22/common/common/bg_pattern_tile.' . $file_extension;
                $destino2 = $_SERVER['DOCUMENT_ROOT'] . '/siteadmin/skin/lobby_asset/2-0-22/common/common/bg_pattern_tile.' . $file_extension;
                if (move_uploaded_file($_FILES['imagem_fundo']['tmp_name'], $destino1)) {
                    copy($destino1, $destino2);
                    $toastType = 'success';
                    $toastMessage = 'Imagem do Fundo atualizada com sucesso!';
                } else {
                    $toastType = 'error';
                    $toastMessage = 'Erro ao salvar a imagem do Fundo.';
                }
            } else {
                $toastType = 'error';
                $toastMessage = 'O arquivo enviado para o Fundo não é uma imagem válida.';
            }
        } else {
            $toastType = 'error';
            $toastMessage = 'Extensão não permitida para a Imagem de Fundo.';
        }
    }


    // Verificar se o download foi enviado
    if (!empty($_FILES['snow_flakes']['name'])) {
        $snow_flakes = upload_and_rename_as_original($_FILES['snow_flakes'], 'snow_flakes'); // Agora preserva a extensão original
        if (!$snow_flakes) {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar o snow_flakes. Verifique a extensão do arquivo.';
        }
    }

    // Verificar se o download foi enviado
    if (!empty($_FILES['avatar']['name'])) {
        $avatar = upload_and_rename_as_original($_FILES['avatar'], 'avatar'); // Agora preserva a extensão original
        if (!$avatar) {
            $toastType = 'error';
            $toastMessage = 'Erro ao enviar o avatar. Verifique a extensão do arquivo.';
        }
    }



    // Atualizar as imagens no banco de dados
    if ($logo || $favicon || $download || $icone_download || $carregamento_img || $snow_flakes || $avatar) {
        if (update_config_images($logo, $favicon, $download, $icone_download, $carregamento_img, $snow_flakes, $avatar)) {
            $toastType = 'success';
            $toastMessage = 'Imagens atualizadas com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao atualizar as imagens.';
        }
    }
}

# Buscar o caminho atual das imagens logo e favicon
$query = "SELECT logo, favicon, download, icone_download, carregamento_img, snow_flakes, avatar FROM config WHERE id = 1";
$result = mysqli_query($mysqli, $query);
$config = mysqli_fetch_assoc($result);

?>

<head>
    <?php $title = "Gerenciamento de Identidade Visual"; ?>
    <?php include 'partials/title-meta.php' ?>
    <?php include 'partials/head-css.php' ?>
</head>

<style>
    .img-container {
        width: 100%;
        height: 150px;
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
                                <h4 class="card-title">Gerenciamento De Identidade Visual</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="row mt-12">
                                        <div class="col-md-4"> <!-- Ajustado para 4 colunas para cada imagem -->
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <label for="logo" class="form-label">Logo</label>
                                                    <?php if (!empty($config['logo'])): ?>
                                                        <div class="mb-3">
                                                            <img src="/uploads/<?= $dataconfig['logo']; ?>" class="img-fluid" alt="Logo" style="max-height: 150px;">
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted">Nenhuma imagem de logo enviada ainda.</p>
                                                    <?php endif; ?>
                                                    <input type="file" name="logo" id="logo" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4"> <!-- Ajustado para 4 colunas para cada imagem -->
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <label for="favicon" class="form-label">Favicon</label>
                                                    <?php if (!empty($config['favicon'])): ?>
                                                        <div class="mb-3">
                                                            <img src="/uploads/<?= $dataconfig['favicon']; ?>" class="img-fluid" alt="Favicon" style="max-height: 150px;">
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted">Nenhuma imagem de favicon enviada ainda.</p>
                                                    <?php endif; ?>
                                                    <input type="file" name="favicon" id="favicon" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4"> <!-- Ajustado para 4 colunas para cada imagem -->
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <label for="download" class="form-label">Baixar Imagem</label>
                                                    <?php if (!empty($config['download'])): ?>
                                                        <div class="mb-3">
                                                            <img src="/uploads/<?= $dataconfig['download']; ?>" class="img-fluid" alt="download" style="max-height: 150px;">
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted">Nenhuma imagem de download enviada ainda.</p>
                                                    <?php endif; ?>
                                                    <input type="file" name="download" id="download" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4"> <!-- Ajustado para 4 colunas para cada imagem -->
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <label for="icone_download" class="form-label">Baixar Imagem</label>
                                                    <?php if (!empty($config['icone_download'])): ?>
                                                        <div class="mb-3">
                                                            <img src="/uploads/<?= $dataconfig['icone_download']; ?>" class="img-fluid" alt="download" style="max-height: 150px;">
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted">Nenhuma imagem de download enviada ainda.</p>
                                                    <?php endif; ?>
                                                    <input type="file" name="icone_download" id="icone_download" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4"> <!-- Ajustado para 4 colunas para cada imagem -->
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <label for="carregamento_img" class="form-label">Imagem de Carregamento</label>
                                                    <?php if (!empty($config['carregamento_img'])): ?>
                                                        <div class="mb-3">
                                                            <img src="/uploads/<?= $dataconfig['carregamento_img']; ?>" class="img-fluid" alt="carregamento_img" style="max-height: 150px;">
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted">Nenhuma imagem de download enviada ainda.</p>
                                                    <?php endif; ?>
                                                    <input type="file" name="carregamento_img" id="carregamento_img" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4"> <!-- Ajustado para 4 colunas para cada imagem -->
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <label for="imagem_fundo" class="form-label">Imagem de Fundo</label>
                                                    <div class="mb-3">
                                                        <?php
                                                        $bgExts = ['avif','png','webp','jpg','jpeg','gif'];
                                                        $bgSrc = null;
                                                        foreach ($bgExts as $ext) {
                                                            $p = '/siteadmin/skin/lobby_asset/2-0-22/common/common/bg_pattern_tile.' . $ext;
                                                            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $p)) { $bgSrc = $p; break; }
                                                        }
                                                        ?>
                                                        <img src="<?= $bgSrc ?: '/siteadmin/skin/lobby_asset/2-0-22/common/common/bg_pattern_tile.png' ?>" class="img-fluid" alt="imagemfundo" style="max-height: 150px;">
                                                    </div>
                                                   
                                                    <input type="file" name="imagem_fundo" id="imagem_fundo" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4"> <!-- Ajustado para 4 colunas para cada imagem -->
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <label for="snow_flakes" class="form-label">Flocos de neve</label>
                                                    <?php if (!empty($config['snow_flakes'])): ?>
                                                        <div class="mb-3">
                                                            <img src="/uploads/<?= $dataconfig['snow_flakes']; ?>" class="img-fluid" alt="snow_flakes" style="max-height: 150px;">
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted">Nenhuma imagem de flocos de neve enviada ainda.</p>
                                                    <?php endif; ?>
                                                    <input type="file" name="snow_flakes" id="snow_flakes" class="form-control">
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-4"> <!-- Ajustado para 4 colunas para cada imagem -->
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <label for="avatar" class="form-label">Avatar</label>
                                                    <?php if (!empty($config['avatar'])): ?>
                                                        <div class="mb-3">
                                                            <img src="/uploads/<?= $dataconfig['avatar']; ?>" class="img-fluid" alt="avatar" style="max-height: 150px;">
                                                        </div>
                                                    <?php else: ?>
                                                        <p class="text-muted">Nenhuma imagem de flocos de neve enviada ainda.</p>
                                                    <?php endif; ?>
                                                    <input type="file" name="avatar" id="avatar" class="form-control">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4"> <!-- Ajustado para 4 colunas para cada imagem -->
                                            <div class="card">
                                                <div class="card-body text-center">
                                                    <label for="topimagemfundo" class="form-label">Fundo Header</label>
                                                    <div class="mb-3">
                                                        <?php
                                                        $bg2Exts = ['avif','png','webp','jpg','jpeg','gif'];
                                                        $bg2Src = null;
                                                        foreach ($bg2Exts as $ext) {
                                                            $p2 = '/siteadmin/skin/lobby_asset/2-1-22/common/common/bg_pattern_tile2.' . $ext;
                                                            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $p2)) { $bg2Src = $p2; break; }
                                                        }
                                                        ?>
                                                        <img src="<?= $bg2Src ?: '/siteadmin/skin/lobby_asset/2-1-22/common/common/bg_pattern_tile2.png' ?>" class="img-fluid" alt="topimagemfundo" style="max-height: 150px;">
                                                    </div>
                                                    <input type="file" name="topimagemfundo" id="topimagemfundo" class="form-control">
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success mb-4">Salvar Alterações</button>
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
                    
                    <h5 class="me-auto my-0">Atualização</h5>
                    <small>Agora</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">${message}</div>
            `;
            toastPlacement.appendChild(toast);

            var bootstrapToast = new bootstrap.Toast(toast);
            bootstrapToast.show();

            setTimeout(function() {
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
