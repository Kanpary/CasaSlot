<div class="startbar d-print-none">
    <div class="brand">
        <a href="index.php" class="logo">
            <span>
                <img src="../uploads/<?= $dataconfig['logo'] ?>" alt="logo-small" class="logo-sm">
            </span>
            <span class=""></span>
        </a>
    </div>
    
    <div class="startbar-menu">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100">
                <ul class="navbar-nav mb-auto w-100">
                    
                    <li class="menu-label pt-0 mt-0">
                        <span style="color: white;">RELATÓRIOS</span>
                    </li>
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="dashboard">
                            <i class="iconoir-home-simple menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <div class="border-dashed-bottom pb-2"></div>
                    
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">PLATAFORMA</span>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarMaps" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarMaps">
                            <i class="iconoir-html5 menu-icon"></i>
                            <span>Configurações</span>
                        </a>
                        <div class="collapse " id="sidebarMaps">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="configuracoes">Valores</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="cupons">Bônus de Depósito</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="gerenciamento-nomes">Nomes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="modal">Modais <span class="badge rounded text-danger bg-danger-subtle ms-1">(NOVO)</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="atendimento">Canais de Suporte</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="baixarpop">App Download</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="alterapainel">Alterar Painel</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="webhooks">Webhooks</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#temas" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="temas">
                            <i class="iconoir-design-pencil menu-icon"></i>
                            <span>Personalização</span><span class="badge rounded text-danger bg-danger-subtle ms-1">(HOT)</span>
                        </a>
                        <div class="collapse " id="temas">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="identidade-visual">Imagens da Plataforma</a>
                                </li>
                            </ul>
                            
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="banners">Banners
                                    <span class="badge rounded text-success bg-success-subtle ms-1">6</span>
                                    </a>
                                </li>
                            </ul>
                            
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="promocoes">Promoções
                                    <span class="badge rounded text-success bg-success-subtle ms-1">8</span>
                                    </a>
                                </li>
                            </ul>
                            
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="temas">Temas
                                    <span class="badge rounded text-danger bg-danger-subtle ms-1">(NOVO)</span>
                                    </a>
                                </li>
                            </ul>
                            
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="mensagens">Gerenciar Anuncios
                                    <span class="badge rounded text-success bg-success-subtle ms-1">4</span>
                                    </a>
                                </li>
                            </ul>
                            
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="iconesfloat">Icones Float
                                    <span class="badge rounded text-success bg-success-subtle ms-1">5</span>
                                    </a>
                                </li>
                            </ul>
                            
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="jackpot">Jackpot
                                    <span class="badge rounded text-danger bg-danger-subtle ms-1">(NOVO)</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#gateway" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="gateway">
                            <i class="iconoir-fingerprint-lock-circle menu-icon"></i>
                            <span>Gateway</span>
                        </a>
                        <div class="collapse " id="gateway">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="gateway">Pagamentos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="chavespix">Chaves Pix <span class="badge rounded text-danger bg-danger-subtle ms-1">(NOVO)</span></a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#afiliados" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="afiliados">
                            <i class="iconoir-media-image-folder menu-icon"></i>
                            <span>Afiliados</span>
                        </a>
                        <div class="collapse " id="afiliados">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="baus">Configurações</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#vips" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="vips">
                            <i class="iconoir-trophy menu-icon"></i>
                            <span>Níveis VIP</span>
                        </a>
                        <div class="collapse " id="vips">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="niveis">Configurar Vips</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <div class="border-dashed-bottom pb-2"></div>
                    
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">FINANCEIRO</span>
                    </li>
                    
                    <?php
                    $query_depositos_processamento = "SELECT COUNT(*) as total_processamento FROM transacoes WHERE status = 'processamento'";
                    $result_depositos_processamento = mysqli_query($mysqli, $query_depositos_processamento);
                    $row_depositos_processamento = mysqli_fetch_assoc($result_depositos_processamento);
                    $total_depositos_processamento = $row_depositos_processamento['total_processamento'];
                    
                    $query_depositos_aprovados = "SELECT COUNT(*) as total_aprovados FROM transacoes WHERE status = 'pago'";
                    $result_depositos_aprovados = mysqli_query($mysqli, $query_depositos_aprovados);
                    $row_depositos_aprovados = mysqli_fetch_assoc($result_depositos_aprovados);
                    $total_depositos_aprovados = $row_depositos_aprovados['total_aprovados'];
                    
                    $query_depositos_recusados = "SELECT COUNT(*) as total_recusados FROM transacoes WHERE status = 'expirado'";
                    $result_depositos_recusados = mysqli_query($mysqli, $query_depositos_recusados);
                    $row_depositos_recusados = mysqli_fetch_assoc($result_depositos_recusados);
                    $total_depositos_recusados = $row_depositos_recusados['total_recusados'];
                    
                    $total_depositos = $total_depositos_processamento + $total_depositos_aprovados + $total_depositos_recusados;
                    ?>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarElements" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarElements">
                            <i class="iconoir-receive-dollars menu-icon"></i>
                            <span>Depósitos</span><span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_depositos; ?></span>
                        </a>
                        <div class="collapse " id="sidebarElements">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="depositos_pagos">Pagos <span class="badge rounded text-success bg-success-subtle ms-1"><?= $total_depositos_aprovados; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="depositos_pendentes">Pendentes <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_depositos_processamento; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="depositos_expirados">Expirados <span class="badge rounded text-danger bg-danger-subtle ms-1"><?= $total_depositos_recusados; ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <?php
                    $query_saques_pendentes = "SELECT COUNT(*) as total_pendentes FROM solicitacao_saques WHERE status = '0' AND tipo_saque = '0'";
                    $result_saques_pendentes = mysqli_query($mysqli, $query_saques_pendentes);
                    $row_saques_pendentes = mysqli_fetch_assoc($result_saques_pendentes);
                    $total_saques_pendentes = $row_saques_pendentes['total_pendentes'];
                    
                    $query_saques_aprovados = "SELECT COUNT(*) as total_aprovados FROM solicitacao_saques WHERE status = '1' AND tipo_saque = '0'";
                    $result_saques_aprovados = mysqli_query($mysqli, $query_saques_aprovados);
                    $row_saques_aprovados = mysqli_fetch_assoc($result_saques_aprovados);
                    $total_saques_aprovados = $row_saques_aprovados['total_aprovados'];
                    
                    $query_saques_recusados = "SELECT COUNT(*) as total_recusados FROM solicitacao_saques WHERE status = '2' AND tipo_saque = '0'";
                    $result_saques_recusados = mysqli_query($mysqli, $query_saques_recusados);
                    $row_saques_recusados = mysqli_fetch_assoc($result_saques_recusados);
                    $total_saques_recusados = $row_saques_recusados['total_recusados'];
                    
                    $total_saques = $total_saques_pendentes + $total_saques_aprovados + $total_saques_recusados;
                    ?>
                    
                    <?php
                    $query_saques_afiliados_pendentes = "SELECT COUNT(*) as total_pendentes FROM solicitacao_saques WHERE status = '0' AND tipo_saque = '1'";
                    $result_saques_afiliados_pendentes = mysqli_query($mysqli, $query_saques_afiliados_pendentes);
                    $row_saques_afiliados_pendentes = mysqli_fetch_assoc($result_saques_afiliados_pendentes);
                    $total_saques_afiliados_pendentes = $row_saques_afiliados_pendentes['total_pendentes'];
                    
                    $query_saques_afiliados_aprovados = "SELECT COUNT(*) as total_aprovados FROM solicitacao_saques WHERE status = '1' AND tipo_saque = '1'";
                    $result_saques_afiliados_aprovados = mysqli_query($mysqli, $query_saques_afiliados_aprovados);
                    $row_saques_afiliados_aprovados = mysqli_fetch_assoc($result_saques_afiliados_aprovados);
                    $total_saques_afiliados_aprovados = $row_saques_afiliados_aprovados['total_aprovados'];
                    
                    $query_saques_afiliados_recusados = "SELECT COUNT(*) as total_recusados FROM solicitacao_saques WHERE status = '2' AND tipo_saque = '1'";
                    $result_saques_afiliados_recusados = mysqli_query($mysqli, $query_saques_afiliados_recusados);
                    $row_saques_afiliados_recusados = mysqli_fetch_assoc($result_saques_afiliados_recusados);
                    $total_saques_afiliados_recusados = $row_saques_afiliados_recusados['total_recusados'];
                    
                    $total_saques_afiliados = $total_saques_afiliados_pendentes + $total_saques_afiliados_aprovados + $total_saques_afiliados_recusados;
                    ?>

                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarAdvancedUI" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarAdvancedUI">
                            <i class="iconoir-send-dollars menu-icon"></i>
                            <span>Saques</span>
                            <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_saques; ?></span>
                        </a>
                        <div class="collapse" id="sidebarAdvancedUI">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="saques_aprovados">Pagos
                                        <span class="badge rounded text-success bg-success-subtle ms-1"><?= $total_saques_aprovados; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="saques_pendentes">Pendentes
                                        <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_saques_pendentes; ?></span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="saques_recusados">Recusados
                                    <span class="badge rounded text-danger bg-danger-subtle ms-1"><?= $total_saques_recusados; ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <div class="border-dashed-bottom pb-2"></div>
                    
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">USUÁRIOS</span>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarForms" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarForms">
                            <i class="iconoir-community menu-icon"></i>
                            <span>Usuários</span>
                        </a>
                        <div class="collapse " id="sidebarForms">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="usuarios">Todos Usuários</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarForms2" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarForms2">
                            <i class="iconoir-community menu-icon"></i>
                            <span>Afiliados</span>
                        </a>
                        <div class="collapse " id="sidebarForms2">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="afiliados">Todos Afiliados</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#sidebarForms3" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarForms3">
                            <i class="iconoir-community menu-icon"></i>
                            <span>Criação de Demos</span>
                        </a>
                        <div class="collapse " id="sidebarForms3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="contas-demos">Demos em Massa</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <div class="border-dashed-bottom pb-2"></div>
                    
                    <?php
                    $query_feedbacks_pendentes = "SELECT COUNT(*) as total_pendentes FROM customer_feedback WHERE status = 'pending'";
                    $result_feedbacks_pendentes = mysqli_query($mysqli, $query_feedbacks_pendentes);
                    $row_feedbacks_pendentes = mysqli_fetch_assoc($result_feedbacks_pendentes);
                    $total_feedbacks_pendentes = $row_feedbacks_pendentes['total_pendentes'];
                    
                    $query_feedbacks_total = "SELECT COUNT(*) as total FROM customer_feedback";
                    $result_feedbacks_total = mysqli_query($mysqli, $query_feedbacks_total);
                    $row_feedbacks_total = mysqli_fetch_assoc($result_feedbacks_total);
                    $total_feedbacks = $row_feedbacks_total['total'];
                    ?>
                    
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">NOTIFICAÇÕES</span>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#feedbacks" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="feedbacks">
                            <i class="iconoir-bell menu-icon"></i>
                            <span>Feedbacks</span>
                            <?php if ($total_feedbacks_pendentes > 0): ?>
                                <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_feedbacks_pendentes; ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="collapse " id="feedbacks">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="feedbacks">
                                        Feedbacks
                                        <?php if ($total_feedbacks_pendentes > 0): ?>
                                            <span class="badge rounded text-warning bg-warning-subtle ms-1"><?= $total_feedbacks_pendentes; ?></span>
                                        <?php else: ?>
                                            <span class="badge rounded text-success bg-success-subtle ms-1"><?= $total_feedbacks; ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <div class="border-dashed-bottom pb-2"></div>
                    
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">HISTÓRICOS</span>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#historicosForms" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="historicosForms">
                            <i class="iconoir-gamepad menu-icon"></i>
                            <span>Apostas</span>
                        </a>
                        <div class="collapse " id="historicosForms">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="historicosplay">Todas Jogadas</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#historicosForms2" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="historicosForms2">
                            <i class="iconoir-gift menu-icon"></i>
                            <span>Bônus</span>
                        </a>
                        <div class="collapse " id="historicosForms2">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="logsbonus">Todos Bônus Usados</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#historicosForms3" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="historicosForms3">
                            <i class="iconoir-trophy menu-icon"></i>
                            <span>Níveis</span>
                        </a>
                        <div class="collapse " id="historicosForms3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="niveislogs">Todos Níveis Ganhos</a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <div class="border-dashed-bottom pb-2"></div>
                    
                    <li class="menu-label mt-2">
                        <small class="label-border">
                            <div class="border_left hidden-xs"></div>
                            <div class="border_right"></div>
                        </small>
                        <span style="color: white;">JOGOS</span>
                    </li>
                    
                    <li class="nav-item" style="background-color: rgba(255, 255, 255, 0.04); border-radius: 8px; margin:2px;">
                        <a class="nav-link" href="#chavesapi" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="chavesapi">
                            <i class="iconoir-key-plus menu-icon"></i>
                            <span>API / Jogos</span>
                        </a>
                        <div class="collapse " id="chavesapi">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="api">Credenciais</a>
                                </li>
                            </ul>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="jogos">Jogos</a>
                                </li>
                            </ul>
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" href="provedores">Provedores</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                </ul>

            </div>
        </div>
    </div>
</div>
<div class="startbar-overlay d-print-none"></div>
