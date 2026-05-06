<?php
include_once 'partials/html.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');
//session_start();
include_once "services/database.php";
//include_once 'logs/registrar_logs.php';
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

/**
 * Busca o status atual do menu navbar
 * @return bool - true se ativo, false se inativo
 */
function buscar_status_menu_navbar() {
    global $mysqli;
    
    $query = "SELECT menu_navbar_ativo FROM config LIMIT 1";
    $result = $mysqli->query($query);
    
    if ($result && $row = $result->fetch_assoc()) {
        return (bool) $row['menu_navbar_ativo'];
    }
    
    return true; // Padrão ativo se não existir a coluna ainda
}

/**
 * Busca o status atual do tema natal
 * @return bool - true se ativo, false se inativo
 */
function buscar_status_tema_natal() {
    global $mysqli;
    
    $query = "SELECT natal_theme_active FROM config LIMIT 1";
    $result = $mysqli->query($query);
    
    if ($result && $row = $result->fetch_assoc()) {
        return (bool) $row['natal_theme_active'];
    }
    
    return false; // Padrão inativo se não existir a coluna ainda
}

/**
 * Atualiza o status do menu navbar
 * @param bool $ativo - true para ativar, false para desativar
 * @return bool - true se sucesso, false se erro
 */
function atualizar_status_menu_navbar($ativo) {
    global $mysqli;
    
    $status = $ativo ? 1 : 0;
    $query = "UPDATE config SET menu_navbar_ativo = ?";
    $stmt = $mysqli->prepare($query);
    
    if (!$stmt) {
        return false;
    }
    
    $stmt->bind_param("i", $status);
    return $stmt->execute();
}

// Função para salvar o template de cores e imagem
function salvar_template_cores($nome_template, $temas, $url_site_images)
{
    global $mysqli;

    $imagem = '';
    if (isset($_FILES['template-image']) && $_FILES['template-image']['error'] == 0) {
        $diretorio_imagens1 = '../skin/lobby_asset/2-1-22/';
        $diretorio_imagens2 = '../skin/lobby_asset/2-0-22/';

        // Cria os diretórios se não existirem
        if (!file_exists($diretorio_imagens1)) {
            mkdir($diretorio_imagens1, 0777, true);
        }
        if (!file_exists($diretorio_imagens2)) {
            mkdir($diretorio_imagens2, 0777, true);
        }

        $imagem_nome = basename($_FILES['template-image']['name']);
        $imagem_destino1 = $diretorio_imagens1 . $imagem_nome;
        $imagem_destino2 = $diretorio_imagens2 . $imagem_nome;

        if (move_uploaded_file($_FILES['template-image']['tmp_name'], $imagem_destino1)) {
            // Copia para o segundo diretório
            copy($imagem_destino1, $imagem_destino2);
            $imagem = $imagem_destino1;
        }
    }

    $status_padrao = 1;

    // Desativa todos os templates
    $mysqli->query("UPDATE templates_cores SET ativo = 0");

    $query = "INSERT INTO templates_cores (nome_template, temas, imagem, ativo, url_site_images) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $temas_json = json_encode($temas);
    $stmt->bind_param("sssis", $nome_template, $temas_json, $imagem, $status_padrao, $url_site_images);
    return $stmt->execute();
}

function baixar_imagens($url_imagens)
{
    // Lista fixa de imagens a baixar
    $imagens_fixas = [
        "/common/_sprite/icon_btm_cz.avif",
        "/common/_sprite/icon_btm_sy.avif",
        "/common/_sprite/icon_btm_sy1.avif",
        "/common/_sprite/icon_btm_tg.avif",
        "/common/_sprite/icon_btm_wd.avif",
        "/common/_sprite/icon_btm_wd1.avif",
        "/common/_sprite/icon_btm_yh.avif",
        "/common/_sprite/icon_btm_yh1.avif",
        "/common/_sprite/icon_btm_zc.avif",
        "/common/_sprite/icon_dt_1gg.avif",
        "/common/_sprite/icon_dt_1xx_wd.avif",
        "/common/_sprite/icon_dt_1xx.avif",
        "/common/common/bg_pattern_tile.avif",
        "/web/common/btn_zc1_1.avif",
        "/web/common/btn_zc1_1.webp",
        "/web/common/btn_zc1_2.avif",
        "/web/common/btn_zc1_2.png",
        "/web/common/btn_zc2_1.avif",
        "/web/common/btn_zc2_2.avif",
        "/web/home/icon_dt_pmd.avif"
    ];

    $log_file = 'imagens_baixadas.log';
    file_put_contents($log_file, "Início do log de download: " . date('Y-m-d H:i:s') . "\n\n", FILE_APPEND);

    foreach ($imagens_fixas as $rota) {
        $url_imagem = rtrim($url_imagens, '/') . '/' . ltrim($rota, '/');
        $caminho_local1 = '../siteadmin/skin/lobby_asset/2-1-22' . $rota;
        $caminho_local2 = '../siteadmin/skin/lobby_asset/2-0-22' . $rota;

        $diretorio1 = dirname($caminho_local1);
        $diretorio2 = dirname($caminho_local2);

        if (!is_dir($diretorio1)) {
            mkdir($diretorio1, 0755, true);
        }
        if (!is_dir($diretorio2)) {
            mkdir($diretorio2, 0755, true);
        }

        // Baixar com fallback via cURL
        $dados = @file_get_contents($url_imagem);

        if ($dados === false) {
            // Tenta com cURL
            $ch = curl_init($url_imagem);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            $dados = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code !== 200 || $dados === false) {
                file_put_contents($log_file, "[ERRO] Falha ao baixar: $url_imagem (HTTP $http_code)\n", FILE_APPEND);
                continue;
            }
        }

        file_put_contents($caminho_local1, $dados);
        // Copia para o segundo diretório
        copy($caminho_local1, $caminho_local2);

        file_put_contents($log_file, "[OK] Baixado: $url_imagem\n", FILE_APPEND);
    }
}

// Carregar um template de cores
function carregar_template_cores($id_template)
{
    global $mysqli;

    // Desativa todos os templates
    $mysqli->query("UPDATE templates_cores SET ativo = 0");

    // Ativa apenas o template selecionado
    $update = $mysqli->prepare("UPDATE templates_cores SET ativo = 1 WHERE id = ?");
    $update->bind_param("i", $id_template);
    $update->execute();

    // Agora busca o template
    $query = "SELECT temas, url_site_images FROM templates_cores WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $id_template);
    $stmt->execute();
    $result = $stmt->get_result();
    $template = $result->fetch_assoc();

    baixar_imagens($template['url_site_images']);

    return json_decode($template['temas'], true);
}


// Função para capturar o style da tag <html> e salvar como JSON
function capturar_style_html_e_salvar_em_template($url, $url_imagens, $nome_template)
{
    // Captura o HTML da página
    $html = file_get_contents($url);
    if (!$html) return false;

    // Captura o conteúdo do atributo style da tag <html>
    if (!preg_match('/<html[^>]*?style=([\'"]?)([^\'">\s]+.*?)(\1)[\s>]/i', $html, $matches)) {
        return false;
    }

    $style_str = trim($matches[2]);

    // Extrair variáveis CSS no formato --nome: valor;
    preg_match_all('/(--[\w-]+)\s*:\s*([^;]+);/', $style_str, $variaveis);

    if (empty($variaveis[1])) return false;

    $temas = [];
    foreach ($variaveis[1] as $i => $nome_var) {
        $temas[$nome_var] = trim($variaveis[2][$i]);
    }

    baixar_imagens($url_imagens);

    // Salvar o JSON do tema no banco
    salvar_template_cores($nome_template, $temas, $url_imagens);

    return $temas;
}


// Função para listar os templates
function listar_templates()
{
    global $mysqli;
    $query = "SELECT id, nome_template, imagem FROM templates_cores";
    $result = mysqli_query($mysqli, $query);

    $templates = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $templates[] = $row;
    }

    return $templates;
}

function template_ativo()
{
    global $mysqli;
    $query = "SELECT nome_template FROM templates_cores WHERE ativo = 1 LIMIT 1";
    $result = mysqli_query($mysqli, $query);

    if ($result) {
        $template = mysqli_fetch_assoc($result);
        return $template;
    }
    return ['nome_template' => 'Nenhum'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['css-url']) && !empty($_POST['css-url'])) {
        $url = $_POST['css-url'];
        $url_imagens = $_POST['imagens-url'];
        $nome_template = $_POST['template-name'] ?? 'Template ' . date('d/m/Y H:i');

        $temas = capturar_style_html_e_salvar_em_template($url, $url_imagens, $nome_template);
        if ($temas) {
            $toastType = 'success';
            $toastMessage = 'Template salvo e ativado com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao capturar e salvar o template.';
        }
    }

    if (isset($_POST['template-id']) && !empty($_POST['template-id'])) {
        $id_template = $_POST['template-id'];
        $temas = carregar_template_cores($id_template);

        if ($temas) {
            $toastType = 'success';
            $toastMessage = 'Template carregado com sucesso!';
        } else {
            $toastType = 'error';
            $toastMessage = 'Erro ao carregar o template.';
        }
    }
}

$templates = listar_templates();
$template_ativo = template_ativo();

// Buscar status do Menu Navbar com tratamento de erro
try {
    $status_menu_navbar = buscar_status_menu_navbar();
} catch (Exception $e) {
    $status_menu_navbar = true;
    error_log("Erro ao buscar status menu navbar: " . $e->getMessage());
}

// Buscar status do Tema Natal com tratamento de erro
try {
    $status_tema_natal = buscar_status_tema_natal();
} catch (Exception $e) {
    $status_tema_natal = false;
    error_log("Erro ao buscar status tema natal: " . $e->getMessage());
}
?>
<head>
    <?php $title = "Configurações de Tema"; ?>
    <?php include 'partials/title-meta.php'; ?>
    <?php include 'partials/head-css.php'; ?>

    <style>
        .color-input {
            display: inline-block;
            width: 100%;
            height: 50px;
            position: relative;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            margin-top: 10px;
        }

        .color-input input[type="color"] {
            width: 100%;
            height: 100%;
            border: none;
            cursor: pointer;
            -webkit-appearance: none;
            appearance: none;
            border-radius: 50px;
            transition: transform 0.3s ease;
        }

        .color-input input[type="color"]:focus {
            transform: scale(1.1);
            box-shadow: 0 0 10px rgba(41, 171, 226, 0.5);
        }

        .color-input input[type="text"] {
            width: 100%;
            height: 50px;
            border-radius: 50px;
            border: 1px solid #ccc;
            padding: 5px;
            font-size: 16px;
        }

        .color-input label {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
            color: #555;
        }

        .btn-primary {
            border-radius: 50px;
            padding: 12px 20px;
            font-size: 16px;
        }
    </style>

</head>

<body>
    <?php include 'partials/topbar.php'; ?>
    <?php include 'partials/startbar.php'; ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Configurações de Cores do Tema</h4>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    
                                    <!-- Toggle Menu Navbar -->
                                    <div class="col-md-12 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-1">Menu Navbar Superior</h5>
                                                        <p class="text-muted mb-0 small">Ativa ou desativa a barra de marketing no topo do site</p>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-<?= $status_menu_navbar ? 'success' : 'danger' ?>" id="statusBadge">
                                                            <?= $status_menu_navbar ? 'ATIVO' : 'INATIVO' ?>
                                                        </span>
                                                        <div class="form-check form-switch">
                                                            <input 
                                                                class="form-check-input" 
                                                                type="checkbox" 
                                                                role="switch" 
                                                                id="toggleMenuNavbar" 
                                                                <?= $status_menu_navbar ? 'checked' : '' ?>
                                                                style="width: 50px; height: 25px; cursor: pointer;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Toggle Tema Natal -->
                                    <div class="col-md-12 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h5 class="mb-1">Tema de Natal</h5>
                                                        <p class="text-muted mb-0 small">Ativa ou desativa as decorações e imagens natalinas</p>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-<?= $status_tema_natal ? 'success' : 'danger' ?>" id="statusTemaNatalBadge">
                                                            <?= $status_tema_natal ? 'ATIVO' : 'INATIVO' ?>
                                                        </span>
                                                        <div class="form-check form-switch">
                                                            <input 
                                                                class="form-check-input" 
                                                                type="checkbox" 
                                                                role="switch" 
                                                                id="toggleTemaNatal" 
                                                                <?= $status_tema_natal ? 'checked' : '' ?>
                                                                style="width: 50px; height: 25px; cursor: pointer;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="css-url" class="form-label">URL do Site</label>
                                        <input type="url" id="css-url" name="css-url" class="form-control"
                                            placeholder="Digite a URL do Site">
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="imagens-url" class="form-label">URL das Imagens</label>
                                        <input type="url" id="imagens-url" name="imagens-url" class="form-control"
                                            placeholder="Ex: https://gadsgads.saxpgapp.com/siteadmin/skin/lobby_asset/2-1-12">
                                    </div>


                                    <div class="col-md-12 mb-3">
                                        <label for="template-load" class="form-label">Carregar Template <b style="color: green;">(<?= $template_ativo['nome_template']; ?> Ativo)</b></label>
                                        <div class="dropdown">
                                            <button class="btn btn-light dropdown-toggle w-100" type="button"
                                                id="template-load" data-bs-toggle="dropdown" aria-expanded="false">
                                                Escolha um template...
                                            </button>
                                            <ul class="dropdown-menu w-100" aria-labelledby="template-load">
                                                <?php foreach ($templates as $template): ?>
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center" href="#"
                                                            data-id="<?= $template['id'] ?>"
                                                            data-imagem="<?= $template['imagem'] ?>"
                                                            data-nome="<?= $template['nome_template'] ?>">
                                                            <?php if ($template['imagem']): ?>
                                                                <img src="<?= $template['imagem'] ?>" alt="Imagem do Tema"
                                                                    style="width: 100px; height: 100%; margin-right: 10px;">
                                                            <?php endif; ?>
                                                            <?= $template['nome_template'] ?>
                                                        </a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>

                                        <!-- Campo oculto para armazenar o ID do template -->
                                        <input type="hidden" id="template-id" name="template-id" value="">
                                    </div>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const dropdownItems = document.querySelectorAll('.dropdown-item');

                                            dropdownItems.forEach(item => {
                                                item.addEventListener('click', function(event) {
                                                    const selectedTemplateId = item.getAttribute('data-id');
                                                    const selectedTemplateName = item.getAttribute('data-nome');
                                                    const selectedTemplateImage = item.getAttribute('data-imagem');

                                                    // Atualiza o valor do botão dropdown com o nome do template
                                                    const dropdownButton = document.getElementById('template-load');
                                                    dropdownButton.textContent = selectedTemplateName;

                                                    // Atualiza o valor do campo oculto com o ID do template
                                                    document.getElementById('template-id').value = selectedTemplateId;

                                                    console.log("Template Selecionado: ", selectedTemplateName, selectedTemplateId, selectedTemplateImage);
                                                });
                                            });
                                        });
                                    </script>

                                    <!-- Novo campo de upload de imagem -->
                                    <div class="col-md-12 mb-3">
                                        <label for="template-image" class="form-label">Imagem do Template
                                            (Opcional)</label>
                                        <input type="file" id="template-image" name="template-image"
                                            class="form-control" accept="image/*">
                                    </div>


                                    <!-- Campo para salvar template -->
                                    <div class="col-md-12 mb-3">
                                        <label for="template-name" class="form-label">Nome do Template</label>
                                        <input type="text" id="template-name" name="template-name" class="form-control"
                                            placeholder="Nome do template">
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'partials/endbar.php'; ?>
            <?php include 'partials/footer.php'; ?>
        </div>
    </div>

    <div id="toastPlacement" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <?php include 'partials/vendorjs.php'; ?>
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

            setTimeout(function() {
                bootstrapToast.hide();
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Lógica do Menu Navbar
            const toggleMenuNavbar = document.getElementById('toggleMenuNavbar');
            const statusBadge = document.getElementById('statusBadge');
            
            if (toggleMenuNavbar && statusBadge) {
                toggleMenuNavbar.addEventListener('change', function() {
                    const isChecked = this.checked;
                    toggleMenuNavbar.disabled = true;
                    
                    statusBadge.textContent = 'ATUALIZANDO...';
                    statusBadge.className = 'badge bg-warning';
                    
                    fetch('ajax/toggle_navbar.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            setTimeout(() => {
                                if (data.ativo) {
                                    statusBadge.className = 'badge bg-success';
                                    statusBadge.textContent = 'ATIVO';
                                } else {
                                    statusBadge.className = 'badge bg-danger';
                                    statusBadge.textContent = 'INATIVO';
                                }
                            }, 300);
                            
                            showToast('success', data.message);
                        } else {
                            toggleMenuNavbar.checked = !isChecked;
                            statusBadge.textContent = isChecked ? 'INATIVO' : 'ATIVO';
                            statusBadge.className = isChecked ? 'badge bg-danger' : 'badge bg-success';
                            showToast('error', data.message || 'Erro ao atualizar o Menu Navbar');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        toggleMenuNavbar.checked = !isChecked;
                        statusBadge.textContent = isChecked ? 'INATIVO' : 'ATIVO';
                        statusBadge.className = isChecked ? 'badge bg-danger' : 'badge bg-success';
                        showToast('error', 'Erro ao comunicar com o servidor');
                    })
                    .finally(() => {
                        toggleMenuNavbar.disabled = false;
                    });
                });
            }

            // Lógica do Tema Natal
            const toggleTemaNatal = document.getElementById('toggleTemaNatal');
            const statusTemaNatalBadge = document.getElementById('statusTemaNatalBadge');
            
            if (toggleTemaNatal && statusTemaNatalBadge) {
                toggleTemaNatal.addEventListener('change', function() {
                    const isChecked = this.checked;
                    toggleTemaNatal.disabled = true;
                    
                    statusTemaNatalBadge.textContent = 'ATUALIZANDO...';
                    statusTemaNatalBadge.className = 'badge bg-warning';
                    
                    fetch('ajax/toggle_theme_natal.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            setTimeout(() => {
                                if (data.ativo) {
                                    statusTemaNatalBadge.className = 'badge bg-success';
                                    statusTemaNatalBadge.textContent = 'ATIVO';
                                } else {
                                    statusTemaNatalBadge.className = 'badge bg-danger';
                                    statusTemaNatalBadge.textContent = 'INATIVO';
                                }
                            }, 300);
                            
                            showToast('success', data.message);
                        } else {
                            toggleTemaNatal.checked = !isChecked;
                            statusTemaNatalBadge.textContent = isChecked ? 'INATIVO' : 'ATIVO';
                            statusTemaNatalBadge.className = isChecked ? 'badge bg-danger' : 'badge bg-success';
                            showToast('error', data.message || 'Erro ao atualizar o Tema Natal');
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        toggleTemaNatal.checked = !isChecked;
                        statusTemaNatalBadge.textContent = isChecked ? 'INATIVO' : 'ATIVO';
                        statusTemaNatalBadge.className = isChecked ? 'badge bg-danger' : 'badge bg-success';
                        showToast('error', 'Erro ao comunicar com o servidor');
                    })
                    .finally(() => {
                        toggleTemaNatal.disabled = false;
                    });
                });
            }
        });
    </script>

    <?php if (isset($toastType) && isset($toastMessage)): ?>
        <script>
            showToast('<?= $toastType ?>', '<?= $toastMessage ?>');
        </script>
    <?php endif; ?>

</body>

</html>