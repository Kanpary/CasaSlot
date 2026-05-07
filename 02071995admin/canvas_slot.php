<?php include 'partials/html.php' ?>
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
include_once "validar_2fa.php";
include_once "services/CSRF_Protect.php";
$csrf = new CSRF_Protect();
checa_login_adm();

if ($_SESSION['data_adm']['status'] != '1') {
    header("Location: bloqueado.php"); exit;
}

$toastType = null;
$toastMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_jogo      = trim($_POST['nome_jogo'] ?? 'Fortune Tiger Canvas');
    $descricao      = trim($_POST['descricao'] ?? '');
    $status         = intval($_POST['status'] ?? 1);
    $popular        = intval($_POST['popular'] ?? 1);
    $aposta_min     = floatval($_POST['aposta_minima'] ?? 1);
    $aposta_max     = floatval($_POST['aposta_maxima'] ?? 500);
    $rtp            = floatval($_POST['rtp'] ?? 96);
    $cor_fundo      = trim($_POST['cor_fundo'] ?? '#1a0a2e');
    $cor_primaria   = trim($_POST['cor_primaria'] ?? '#f0a500');
    $efeitos        = intval($_POST['efeitos_sonoros'] ?? 1);
    $modo_demo      = intval($_POST['modo_demo'] ?? 1);

    // Symbols — comma separated
    $simbolos_raw = trim($_POST['simbolos'] ?? '');
    $simbolos_arr = array_values(array_filter(array_map('trim', explode(',', $simbolos_raw))));
    if (empty($simbolos_arr)) {
        $simbolos_arr = ['🐯','💎','🍋','🍇','🔔','⭐','🎰','💰','🃏','❤️'];
    }
    $simbolos_json = json_encode($simbolos_arr, JSON_UNESCAPED_UNICODE);

    // Multiplicadores
    $mult = [
        '3x_same' => intval($_POST['mult_3x_same'] ?? 5),
        '4x_same' => intval($_POST['mult_4x_same'] ?? 15),
        '5x_same' => intval($_POST['mult_5x_same'] ?? 50),
        '3x_wild' => intval($_POST['mult_3x_wild'] ?? 10),
        '4x_wild' => intval($_POST['mult_4x_wild'] ?? 25),
        '5x_wild' => intval($_POST['mult_5x_wild'] ?? 100),
    ];
    $mult_json = json_encode($mult);

    $stmt = $mysqli->prepare("
        UPDATE canvas_slot_config SET
            nome_jogo=?, descricao=?, status=?, popular=?,
            aposta_minima=?, aposta_maxima=?, rtp=?,
            simbolos=?, multiplicadores=?,
            cor_fundo=?, cor_primaria=?,
            efeitos_sonoros=?, modo_demo=?
        WHERE id=1
    ");
    $stmt->bind_param(
        "ssiidddssssii",
        $nome_jogo, $descricao, $status, $popular,
        $aposta_min, $aposta_max, $rtp,
        $simbolos_json, $mult_json,
        $cor_fundo, $cor_primaria,
        $efeitos, $modo_demo
    );

    if ($stmt->execute()) {
        // Sync game name in games table
        $mysqli->prepare("UPDATE games SET game_name=?, status=?, popular=? WHERE game_code='canvas-slot'")
               ->execute(); // will use bind below
        $sg = $mysqli->prepare("UPDATE games SET game_name=?, status=?, popular=? WHERE game_code='canvas-slot'");
        $sg->bind_param("sii", $nome_jogo, $status, $popular);
        $sg->execute();

        $toastType = 'success';
        $toastMessage = 'Configurações do Canvas Slot salvas com sucesso!';
    } else {
        $toastType = 'error';
        $toastMessage = 'Erro ao salvar: ' . $mysqli->error;
    }
}

$config = $mysqli->query("SELECT * FROM canvas_slot_config WHERE id=1 LIMIT 1")->fetch_assoc();
$simbolos_display = implode(', ', json_decode($config['simbolos'] ?? '[]', true) ?: ['🐯','💎','🍋','🍇','🔔','⭐','🎰','💰','🃏','❤️']);
$mult = json_decode($config['multiplicadores'] ?? '{}', true) ?: ['3x_same'=>5,'4x_same'=>15,'5x_same'=>50,'3x_wild'=>10,'4x_wild'=>25,'5x_wild'=>100];
?>

<head>
    <?php $title = "Canvas Slot — Configuração"; include 'partials/title-meta.php'; include 'partials/head-css.php'; ?>
</head>
<body>
<?php include 'partials/topbar.php'; include 'partials/startbar.php'; ?>

<div class="page-wrapper">
<div class="page-content">
<div class="container-xxl">

    <!-- Header card -->
    <div class="row justify-content-center mb-3">
        <div class="col-lg-12">
            <div class="card border shadow-none" style="background:rgba(240,165,0,0.07);border:1px dashed #f0a500 !important;">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="text-uppercase font-12 fw-bold mb-2" style="color:#f0a500;">🎰 Slot Canvas — Jogo Exclusivo</h6>
                            <p class="mb-1">Jogo de slot construído com <strong>HTML5 Canvas</strong> — animações nativas, sem dependências externas.</p>
                            <small class="text-muted">Todas as alterações são salvas no banco de dados e refletem imediatamente no jogo.</small>
                        </div>
                        <div class="col-md-4 text-end d-flex gap-2 justify-content-end flex-wrap">
                            <a href="/slot_canvas/" target="_blank" class="btn btn-warning text-dark">
                                <i class="fas fa-gamepad me-1"></i> Abrir Jogo
                            </a>
                            <a href="/slot_canvas/?demo=1" target="_blank" class="btn btn-outline-warning">
                                <i class="fas fa-eye me-1"></i> Demo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST">
    <div class="row g-3">

        <!-- Coluna esquerda -->
        <div class="col-lg-7">

            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Informações Gerais</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nome do Jogo</label>
                        <input type="text" name="nome_jogo" class="form-control" required
                               value="<?= htmlspecialchars($config['nome_jogo'] ?? 'Fortune Tiger Canvas') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <textarea name="descricao" class="form-control" rows="2"><?= htmlspecialchars($config['descricao'] ?? '') ?></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="1" <?= ($config['status']??1)==1?'selected':'' ?>>✅ Ativo</option>
                                <option value="0" <?= ($config['status']??1)==0?'selected':'' ?>>🔴 Inativo</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Popular</label>
                            <select name="popular" class="form-select">
                                <option value="1" <?= ($config['popular']??1)==1?'selected':'' ?>>⭐ Sim</option>
                                <option value="0" <?= ($config['popular']??1)==0?'selected':'' ?>>Não</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-coins me-2"></i>Apostas & RTP</h5></div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-4">
                            <label class="form-label">Aposta Mínima (R$)</label>
                            <input type="number" name="aposta_minima" class="form-control" step="0.01" min="0.01"
                                   value="<?= number_format($config['aposta_minima']??1,2,'.','') ?>">
                        </div>
                        <div class="col-4">
                            <label class="form-label">Aposta Máxima (R$)</label>
                            <input type="number" name="aposta_maxima" class="form-control" step="1" min="1"
                                   value="<?= number_format($config['aposta_maxima']??500,2,'.','') ?>">
                        </div>
                        <div class="col-4">
                            <label class="form-label">RTP (%)</label>
                            <input type="number" name="rtp" class="form-control" step="0.01" min="50" max="99.99"
                                   value="<?= number_format($config['rtp']??96,2,'.','') ?>">
                            <small class="text-muted">Retorno ao jogador</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-dice me-2"></i>Multiplicadores de Prêmios</h5></div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Valor = aposta × multiplicador. Ex: aposta R$10 com mult 5x = R$50.</p>
                    <div class="row g-2">
                        <div class="col-4">
                            <label class="form-label">3 Iguais</label>
                            <div class="input-group">
                                <input type="number" name="mult_3x_same" class="form-control" min="1" value="<?= $mult['3x_same']??5 ?>">
                                <span class="input-group-text">×</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">4 Iguais</label>
                            <div class="input-group">
                                <input type="number" name="mult_4x_same" class="form-control" min="1" value="<?= $mult['4x_same']??15 ?>">
                                <span class="input-group-text">×</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">5 Iguais</label>
                            <div class="input-group">
                                <input type="number" name="mult_5x_same" class="form-control" min="1" value="<?= $mult['5x_same']??50 ?>">
                                <span class="input-group-text">×</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">3 Wild (1º símbolo)</label>
                            <div class="input-group">
                                <input type="number" name="mult_3x_wild" class="form-control" min="1" value="<?= $mult['3x_wild']??10 ?>">
                                <span class="input-group-text">×</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">4 Wild</label>
                            <div class="input-group">
                                <input type="number" name="mult_4x_wild" class="form-control" min="1" value="<?= $mult['4x_wild']??25 ?>">
                                <span class="input-group-text">×</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <label class="form-label">5 Wild (Jackpot)</label>
                            <div class="input-group">
                                <input type="number" name="mult_5x_wild" class="form-control" min="1" value="<?= $mult['5x_wild']??100 ?>">
                                <span class="input-group-text">×</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Coluna direita -->
        <div class="col-lg-5">

            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-paint-brush me-2"></i>Visual & Aparência</h5></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Cor de Fundo</label>
                        <div class="input-group">
                            <input type="color" name="cor_fundo" class="form-control form-control-color"
                                   value="<?= htmlspecialchars($config['cor_fundo'] ?? '#1a0a2e') ?>">
                            <input type="text" class="form-control"
                                   value="<?= htmlspecialchars($config['cor_fundo'] ?? '#1a0a2e') ?>"
                                   id="corFundoText" oninput="syncColor(this,'cor_fundo')">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cor Primária (destaque)</label>
                        <div class="input-group">
                            <input type="color" name="cor_primaria" class="form-control form-control-color"
                                   value="<?= htmlspecialchars($config['cor_primaria'] ?? '#f0a500') ?>">
                            <input type="text" class="form-control"
                                   value="<?= htmlspecialchars($config['cor_primaria'] ?? '#f0a500') ?>"
                                   id="corPriText" oninput="syncColor(this,'cor_primaria')">
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Efeitos Sonoros</label>
                            <select name="efeitos_sonoros" class="form-select">
                                <option value="1" <?= ($config['efeitos_sonoros']??1)==1?'selected':'' ?>>🔊 Sim</option>
                                <option value="0" <?= ($config['efeitos_sonoros']??1)==0?'selected':'' ?>>🔇 Não</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Modo Demo</label>
                            <select name="modo_demo" class="form-select">
                                <option value="1" <?= ($config['modo_demo']??1)==1?'selected':'' ?>>✅ Habilitado</option>
                                <option value="0" <?= ($config['modo_demo']??1)==0?'selected':'' ?>>🔴 Desabilitado</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-th me-2"></i>Símbolos dos Rolos</h5></div>
                <div class="card-body">
                    <div class="mb-2">
                        <label class="form-label">Símbolos (separados por vírgula)</label>
                        <input type="text" name="simbolos" class="form-control"
                               value="<?= htmlspecialchars($simbolos_display) ?>">
                        <small class="text-muted">O <strong>primeiro símbolo</strong> é o Wild. Use emojis ou texto curto.</small>
                    </div>
                    <div class="alert alert-info p-2 mb-0">
                        <small><strong>Wild</strong>: substitui qualquer símbolo. Combinações de Wild pagam mais.</small>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h5 class="card-title mb-0"><i class="fas fa-link me-2"></i>Link do Jogo</h5></div>
                <div class="card-body">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" id="gameUrl" readonly
                               value="<?= (isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!='off'?'https':'http').'://'.$_SERVER['HTTP_HOST'].'/slot_canvas/' ?>">
                        <button type="button" class="btn btn-outline-secondary" onclick="navigator.clipboard.writeText(document.getElementById('gameUrl').value);this.textContent='✓'">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    <small class="text-muted">URL permanente — compartilhe ou use em banners.</small>
                </div>
            </div>

            <button type="submit" class="btn btn-warning text-dark w-100 fw-bold py-3">
                <i class="fas fa-save me-2"></i>SALVAR CONFIGURAÇÕES
            </button>
        </div>

    </div>
    </form>

</div>
</div>
</div>

<?php include 'partials/endbar.php'; include 'partials/footer.php'; include 'partials/vendorjs.php'; ?>
<script src="assets/js/app.js"></script>
<script>
function syncColor(input, name) {
    document.querySelector('[name="'+name+'"]').value = input.value;
}
<?php if ($toastType): ?>
Swal.fire({icon:'<?= $toastType ?>',title:'<?= $toastType==='success'?'Salvo!':'Erro' ?>',text:'<?= addslashes($toastMessage) ?>',timer:3000,showConfirmButton:false});
<?php endif; ?>
</script>
</body>
</html>
