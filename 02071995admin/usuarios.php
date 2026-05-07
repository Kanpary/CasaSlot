<?php include 'partials/html.php' ?>
<?php
include_once "validar_2fa.php";
include_once "services/database.php";
?>
<head>
    <?php $title = "dash"; ?>
    <?php include 'partials/title-meta.php' ?>
    <?php include 'partials/head-css.php' ?>
     <style>
        :root {
            --secondary-bg: #303231;
            --accent-color: #8b5cf6;
            --secondary-accent: #10b981;
            --info-accent: #3b82f6;
            --text-primary: #ffffff;
            --text-secondary: #9ca3af;
            --border-color: #4b5563;
            --hover-bg: rgba(75, 85, 99, 0.3);
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
        }

        body {
            color: var(--text-primary) !important;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .page-wrapper {
            background-color: var(--primary-bg) !important;
        }

        .page-content {
            background-color: var(--primary-bg) !important;
            min-height: 100vh;
            padding: 20px;
        }

        /* Header Section */
        .users-header {
            background: linear-gradient(135deg, var(--accent-color), #7c3aed);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .users-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect x="0" y="0" width="20" height="20" fill="rgba(255,255,255,0.05)"/></svg>') repeat;
            animation: slidePattern 20s linear infinite;
        }

        @keyframes slidePattern {
            0% { transform: translateX(0) translateY(0); }
            100% { transform: translateX(-20px) translateY(-20px); }
        }

        .users-header h4 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 2;
        }

        .users-header p {
            margin: 12px 0 0 0;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        .users-header .btn {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.2) !important;
            border: 2px solid rgba(255, 255, 255, 0.3) !important;
            color: white !important;
        }

        .users-header .btn:hover {
            background: rgba(255, 255, 255, 0.3) !important;
            border-color: rgba(255, 255, 255, 0.5) !important;
            transform: translateY(-2px) !important;
        }

        /* Stats Overview */
        .users-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--secondary-bg) !important;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.3);
            border-color: var(--accent-color);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }

        .stat-card.saldo::before {
            background: linear-gradient(90deg, var(--accent-color), #7c3aed);
        }

        .stat-card.depositado::before {
            background: linear-gradient(90deg, var(--success-color), #059669);
        }

        .stat-card.sacado::before {
            background: linear-gradient(90deg, var(--danger-color), #dc2626);
        }

        .stat-card.media::before {
            background: linear-gradient(90deg, var(--info-color), #2563eb);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: white;
            font-size: 24px;
        }

        .stat-icon.saldo {
            background: linear-gradient(135deg, var(--accent-color), #7c3aed);
        }

        .stat-icon.depositado {
            background: linear-gradient(135deg, var(--success-color), #059669);
        }

        .stat-icon.sacado {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
        }

        .stat-icon.media {
            background: linear-gradient(135deg, var(--info-color), #2563eb);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary) !important;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-secondary) !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Search and Filter Section */
        .search-filter-section {
            background: var(--secondary-bg);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            border: 2px solid var(--border-color);
        }

        .search-filter-title {
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Card Override */
        .card {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 16px !important;
            color: var(--text-primary) !important;
        }

        .card-header {
            background: var(--secondary-bg) !important;
            border-bottom: 2px solid var(--border-color) !important;
            border-radius: 16px 16px 0 0 !important;
            padding: 24px !important;
        }

        .card-title {
            font-size: 20px !important;
            font-weight: 700 !important;
            color: var(--text-primary) !important;
            margin: 0 !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-body {
            background: var(--secondary-bg) !important;
            color: var(--text-primary) !important;
            padding: 24px !important;
            border-radius: 20px !important;
        }

        /* Form Controls */
        .form-control, .form-select {
            background: var(--primary-bg) !important;
            border: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            border-radius: 8px !important;
            padding: 12px 16px !important;
        }

        .form-control:focus, .form-select:focus {
            background: var(--primary-bg) !important;
            border-color: var(--accent-color) !important;
            color: var(--text-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(139, 92, 246, 0.25);
        }

        .form-control::placeholder {
            color: var(--text-secondary) !important;
        }

        /* Table Styling */
        .table-responsive {
            padding: 0;
            border-radius: 20px !important;
        }

        .table {
            margin: 0 !important;
            color: var(--text-primary) !important;
            background: transparent !important;
        }

        .table thead th {
            background: var(--primary-bg) !important;
            border: none !important;
            color: var(--text-primary) !important;
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 20px 16px !important;
            border-bottom: 2px solid var(--border-color) !important;
        }

        .table tbody td {
            background: var(--secondary-bg) !important;
            border: none !important;
            color: var(--text-primary) !important;
            padding: 16px !important;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color) !important;
        }

        .table tbody tr:hover {
            background: rgba(139, 92, 246, 0.05) !important;
        }

        .table tbody tr:last-child td {
            border-bottom: none !important;
        }

        .table-light {
            background: var(--primary-bg) !important;
        }

        /* Badges */
        .badge {
            padding: 6px 12px !important;
            border-radius: 16px !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .bg-success {
            background: rgba(16, 185, 129, 0.2) !important;
            color: var(--success-color) !important;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .bg-danger {
            background: rgba(239, 68, 68, 0.2) !important;
            color: var(--danger-color) !important;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .bg-warning {
            background: rgba(245, 158, 11, 0.2) !important;
            color: var(--warning-color) !important;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        /* Buttons */
        .btn {
            border-radius: 8px !important;
            font-weight: 600 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            transition: all 0.3s ease !important;
        }

        .btn-primary {
            background: var(--accent-color) !important;
            border: none !important;
            color: white !important;
        }

        .btn-primary:hover {
            background: #7c3aed !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3) !important;
        }

        .btn-success {
            background: var(--success-color) !important;
            border: none !important;
        }

        .btn-success:hover {
            background: #059669 !important;
            transform: translateY(-2px) !important;
        }

        /* Dropdown */
        .dropdown-menu {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 12px !important;
        }

        .dropdown-item:hover {
            background: var(--hover-bg) !important;
            color: var(--text-primary) !important;
        }

        /* Pagination */
        .pagination .page-link {
            background: var(--secondary-bg) !important;
            border: 2px solid var(--border-color) !important;
            color: var(--text-primary) !important;
            margin: 0 2px !important;
            border-radius: 8px !important;
        }

        .pagination .page-link:hover {
            background: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: white !important;
        }

        .pagination .page-item.active .page-link {
            background: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: white !important;
        }

        /* Tag customizada */
        .tag {
            width: 4px;
            height: 24px;
            background: linear-gradient(135deg, var(--accent-color), #7c3aed);
            border-radius: 2px;
            margin-right: 12px;
        }

        /* Loading Animation */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .loading {
            animation: spin 1s linear infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .page-content {
                padding: 16px;
            }
            
            .users-header {
                padding: 24px;
            }
            
            .users-stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-responsive {
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .users-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <?php include 'partials/topbar.php' ?>
    <?php include 'partials/startbar.php' ?>

    <?php
    global $mysqli;

    // MODIFICAÇÃO: Filtro base para exibir apenas usuários (statusaff = 0)
    $statusaff_filter = " AND statusaff = 0";

    $search_query = '';
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search_query = mysqli_real_escape_string($mysqli, $_GET['search']);
    }

    if (isset($_GET['status']) && $_GET['status'] !== '') {
        $status_filter = (int) $_GET['status'];
        if ($status_filter == 2) {
            // Banidos (mas ainda com statusaff = 0)
            $statusaff_filter = " AND banido = 1 AND statusaff = 0";
        } elseif ($status_filter == 0) {
            // Usuários ativos (não banidos)
            $statusaff_filter = " AND statusaff = 0 AND banido = 0";
        }
        // Se o filtro for 1 (afiliado), ainda mantém statusaff = 0 para não exibir afiliados
    }

    $limit = 50;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $query_total_usuarios = "SELECT COUNT(*) AS total_usuarios FROM usuarios WHERE 1=1 $statusaff_filter";
    if (!empty($search_query)) {
        $query_total_usuarios .= " AND (id LIKE '%$search_query%' OR mobile LIKE '%$search_query%')";
    }
    
    $result_total_usuarios = mysqli_query($mysqli, $query_total_usuarios);
    $total_usuarios = mysqli_fetch_assoc($result_total_usuarios)['total_usuarios'];

    $total_pages = ceil($total_usuarios / $limit);

    $query_usuarios = "SELECT * FROM usuarios WHERE 1=1 $statusaff_filter";
    if (!empty($search_query)) {
        $query_usuarios .= " AND (id LIKE '%$search_query%' OR mobile LIKE '%$search_query%')";
    }
    
    $query_usuarios .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
    $result_usuarios = mysqli_query($mysqli, $query_usuarios);
    ?>

    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-xxl">
                <div class="row justify-content-center">
                    <div class="col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Todos Usuários (<?= $total_usuarios; ?> no total)</h4>
                                    </div>
                                    <div class="col text-end">
                                        <a href="export/exportar_usuarios.php" class="btn btn-primary">Exportar Dados</a>
                                    </div>
                                </div>
                            </div>
                            
                              <!-- Stats Overview -->
                <div class="users-stats">
                    <div class="stat-card saldo">
                        <div class="stat-icon saldo">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format(total_saldos_usuarios(), 2, ',', '.'); ?></div>
                        <div class="stat-label">Total Saldos</div>
                    </div>
                    <div class="stat-card depositado">
                        <div class="stat-icon depositado">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format(total_dep_pagos_usuarios(), 2, ',', '.'); ?></div>
                        <div class="stat-label">Total Depositado</div>
                    </div>
                    <div class="stat-card sacado">
                        <div class="stat-icon sacado">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format(total_saques_usuarios(), 2, ',', '.'); ?></div>
                        <div class="stat-label">Total Sacado</div>
                    </div>
                    <div class="stat-card media">
                        <div class="stat-icon media">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="stat-value">R$ <?= number_format(media_saldo_usuarios(), 2, ',', '.'); ?></div>
                        <div class="stat-label">Saldo Médio</div>
                    </div>
                </div>
                            
                            <div class="card-body pt-0">
                                <form method="GET" action="">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Buscar por ID ou Nome do Usuário"
                                                value="<?= htmlspecialchars($search_query) ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <select name="status" class="form-select">
                                                <option value="">Todos os Status</option>
                                                <option value="2" <?= (isset($_GET['status']) && $_GET['status'] == '2') ? 'selected' : ''; ?>>Banido</option>
                                                <option value="0" <?= (isset($_GET['status']) && $_GET['status'] == '0') ? 'selected' : ''; ?>>Ativo</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <button type="submit" class="btn btn-success mt-2 mb-2">Filtrar</button>
                                            <a href="?" class="btn btn-secondary mt-2 mb-2">Limpar</a>
                                        </div>
                                    </div>
                                </form>

                                <div class="table-responsive">
                                    <table class="table mb-0 table-centered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Id</th>
                                                <th>Usuário</th>
                                                <th>Saldo</th>
                                                <th>Depositado</th>
                                                <th>Sacado</th>
                                                <th>Cargo</th>
                                                <th>Indicados</th>
                                                <th>Status</th>
                                                <th>
                                                    Modo Demo
                                                    <i class="fa fa-info-circle text-info info-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Para ativar o modo demo, é necessário o afiliado ter feito no mínimo 1 aposta via Pragmatic e PGSoft"></i>
                                                </th>
                                                <th>RTP Individual</th>
                                                <th class="text-end">Detalhes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result_usuarios && mysqli_num_rows($result_usuarios) > 0) {
                                                while ($usuario = mysqli_fetch_assoc($result_usuarios)) {
                                                    // Definir o cargo com base nos dados da tabela
                                                    if ($usuario['banido'] == 1) {
                                                        $cargo_badge = "<span class='badge bg-dark'>Banido</span>";
                                                    } else {
                                                        $cargo_badge = "<span class='badge bg-secondary'>Usuário</span>";
                                                    }

                                                    $query_sacado = "SELECT SUM(valor) AS total_sacado FROM solicitacao_saques WHERE id_user = {$usuario['id']} AND status = 1";
                                                    $result_sacado = mysqli_query($mysqli, $query_sacado);
                                                    $sacado = ($result_sacado && mysqli_num_rows($result_sacado) > 0) ? mysqli_fetch_assoc($result_sacado)['total_sacado'] : 0;

                                                    $query_depositado = "SELECT SUM(valor) AS total_depositado FROM transacoes WHERE usuario = {$usuario['id']} AND status = 'pago'";
                                                    $result_depositado = mysqli_query($mysqli, $query_depositado);
                                                    $depositado = ($result_depositado && mysqli_num_rows($result_depositado) > 0) ? mysqli_fetch_assoc($result_depositado)['total_depositado'] : 0;

                                                    // Contar indicados (quem foi convidado por este usuário)
                                                    $query_indicados = "SELECT COUNT(*) AS total_indicados FROM usuarios WHERE invitation_code = '{$usuario['invite_code']}'";
                                                    $result_indicados = mysqli_query($mysqli, $query_indicados);
                                                    $total_indicados = ($result_indicados && mysqli_num_rows($result_indicados) > 0) ? mysqli_fetch_assoc($result_indicados)['total_indicados'] : 0;

                                                    // Status
                                                    if ($usuario['banido'] == 1) {
                                                        $status_badge = "<span class='badge bg-danger'>Banido</span>";
                                                    } else {
                                                        $status_badge = "<span class='badge bg-success'>Ativo</span>";
                                                    }

                                                    // Obter valores de modo_demo e rtp_individual
                                                    $modo_demo = $usuario['modo_demo'] ?? 0;
                                                    $rtp_individual = $usuario['rtp_individual'] ?? 95;
                                                    ?>
                                                    <tr>
                                                        <td><?= $usuario['id']; ?></td>
                                                        <td><?= htmlspecialchars($usuario['mobile']); ?></td>
                                                        <td>R$ <?= number_format($usuario['saldo'], 2, ',', '.'); ?></td>
                                                        <td>R$ <?= number_format($depositado, 2, ',', '.'); ?></td>
                                                        <td>R$ <?= number_format($sacado, 2, ',', '.'); ?></td>
                                                        <td><?= $cargo_badge; ?></td>
                                                        <td><?= $total_indicados; ?></td>
                                                        <td><?= $status_badge; ?></td>
                                                        <td>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input modo-demo-switch" 
                                                                       type="checkbox" 
                                                                       id="modoDemo_<?= $usuario['id']; ?>" 
                                                                       data-mobile="<?= htmlspecialchars($usuario['mobile']); ?>"
                                                                       <?= $modo_demo == 1 ? 'checked' : ''; ?>>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="rtp-individual">
                                                                <label for="rtpSlider_<?= $usuario['id']; ?>">
                                                                    <span id="rtpValueDisplay_<?= $usuario['id']; ?>"><?= $rtp_individual; ?>%</span>
                                                                </label>
                                                                <input type="range" 
                                                                       class="form-range rtp-slider" 
                                                                       min="0" 
                                                                       max="100" 
                                                                       step="1" 
                                                                       value="<?= $rtp_individual; ?>" 
                                                                       id="rtpSlider_<?= $usuario['id']; ?>"
                                                                       data-mobile="<?= htmlspecialchars($usuario['mobile']); ?>">
                                                            </div>
                                                        </td>
                                                        <td class="text-end">
                                                            <div class="dropdown d-inline-block">
                                                                <a class="dropdown-toggle arrow-none" id="dLabel11"
                                                                    data-bs-toggle="dropdown" href="#" role="button"
                                                                    aria-haspopup="false" aria-expanded="false">
                                                                    <i class="las la-ellipsis-v fs-20 text-muted"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item text-success"
                                                                        href="<?= $painel_adm_ver_usuarios . encodeAll($usuario['id']); ?>">
                                                                        <i class="las la-info-circle"></i> Detalhes
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='11' class='text-center'>Sem dados disponíveis!</td></tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table><!--end /table-->
                                </div><!--end /tableresponsive-->

                                <!-- Paginação -->
                                <?php if ($total_pages > 1): ?>
                                    <nav>
                                        <ul class="pagination justify-content-center mt-3">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page - 1 ?><?= !empty($search_query) ? '&search=' . urlencode($search_query) : '' ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>" aria-label="Anterior">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>

                                            <?php 
                                            $start_page = max(1, $page - 2);
                                            $end_page = min($total_pages, $page + 2);
                                            
                                            for ($i = $start_page; $i <= $end_page; $i++): 
                                            ?>
                                                <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                                    <a class="page-link" href="?page=<?= $i ?><?= !empty($search_query) ? '&search=' . urlencode($search_query) : '' ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($page < $total_pages): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page + 1 ?><?= !empty($search_query) ? '&search=' . urlencode($search_query) : '' ?><?= isset($_GET['status']) ? '&status=' . $_GET['status'] : '' ?>" aria-label="Próximo">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Depositado</h5>
                                        <p class="text-muted mb-0">R$
                                            <?= number_format(total_dep_pagos_usuarios(), 2, ',', '.'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Sacado</h5>
                                        <p class="text-muted mb-0">R$
                                            <?= number_format(total_saques_usuarios(), 2, ',', '.'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Saldo Médio</h5>
                                        <p class="text-muted mb-0">R$
                                            <?= number_format(media_saldo_usuarios(), 2, ',', '.'); ?>
                                        </p>
                                    </div>
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

    <?php include 'partials/vendorjs.php' ?>
    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
        
        function updateRtpIndividual(mobile, rtpValue) {
            fetch('partials/updateRtpIndividual.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ mobile: mobile, rtp: rtpValue })
            })
            .then(response => response.json())
            .then(json => {
                if (json.success) {
                    console.log('RTP atualizado com sucesso');
                } else {
                    console.error('Erro ao atualizar RTP');
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
            });
        }

        function updateModoDemo(mobile, modoDemoValue) {
            // 1. Atualizar modo demo na iGameWin
            fetch('partials/updateModoDemo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ mobile: mobile, modo_demo: modoDemoValue })
            })
            .then(response => response.json())
            .then(json => {
                if (json.success) {
                    console.log('Modo Demo atualizado com sucesso na iGameWin');
                    
                    const action = modoDemoValue === 1 ? 'ativado' : 'desativado';
                    console.log(`Modo Demo ${action} com sucesso`);
                } else {
                    console.error('Erro ao atualizar Modo Demo na iGameWin:', json.message);
                    throw new Error(json.message);
                }
            })
            .catch(error => {
                console.error('Erro na requisição:', error);
            });
        }

        document.querySelectorAll('.rtp-slider').forEach(function(slider) {
            slider.addEventListener('input', function() {
                var userId = this.id.replace('rtpSlider_', '');
                var rtpValue = parseInt(this.value);
                document.getElementById('rtpValueDisplay_' + userId).textContent = rtpValue + '%';
            });
            slider.addEventListener('change', function() {
                var userId = this.id.replace('rtpSlider_', '');
                var rtpValue = parseInt(this.value);
                var mobile = this.getAttribute('data-mobile');
                updateRtpIndividual(mobile, rtpValue);
            });
        });

        document.querySelectorAll('.modo-demo-switch').forEach(function(switchElem) {
            switchElem.addEventListener('change', function() {
                var mobile = this.getAttribute('data-mobile');
                var modoDemoValue = this.checked ? 1 : 0;
                updateModoDemo(mobile, modoDemoValue);
            });
        });
    </script>
</body>

</html>

<?php
function total_dep_pagos_usuarios()
{
    global $mysqli;
    $qry = "SELECT SUM(valor) as total_soma FROM transacoes WHERE status = 'pago' AND tipo = 'deposito'";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['total_soma'] ?? 0;
}

function total_saques_usuarios()
{
    global $mysqli;
    $qry = "SELECT SUM(valor) as total_soma FROM solicitacao_saques WHERE status = 1";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['total_soma'] ?? 0;
}

function media_saldo_usuarios()
{
    global $mysqli;
    $qry = "SELECT AVG(saldo) as media_saldo FROM usuarios";
    $result = mysqli_query($mysqli, $qry);
    return mysqli_fetch_assoc($result)['media_saldo'] ?? 0;
}
?>