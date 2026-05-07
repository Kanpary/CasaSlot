<?php
   ini_set('display_errors', 0);
   error_reporting(E_ALL);
   include_once "./services/database.php";
   include_once './logs/registrar_logs.php';
   include_once "./services/funcao.php";
   include_once "./services/crud.php";
   include_once "./services/crud-adm.php";
   include_once './services/checa_login_adm.php';
   include_once "./services/CSRF_Protect.php";
   $csrf = new CSRF_Protect();
   
   checa_login_adm();
   
   if ($_SESSION['data_adm']['status'] != '1') {
       echo "<script>setTimeout(function() { window.location.href = 'bloqueado.php'; }, 0);</script>";
       exit();
   }
   
   $admin_id = $_SESSION['data_adm']['id'];
   
   // Buscar notificações organizadas por categoria
   $notificacoes_por_tipo = [
       'saques' => [],
       'depositos' => [],
       'usuarios' => [],
       'feedbacks' => []
   ];
   
   // Saques (limite de 10 por categoria)
   $query = "SELECT s.id, s.valor, s.data_registro, u.mobile 
             FROM solicitacao_saques s 
             INNER JOIN usuarios u ON s.id_user = u.id 
             WHERE s.status = '0' 
             ORDER BY s.data_registro DESC LIMIT 10";
   $result = mysqli_query($mysqli, $query);
   while ($row = mysqli_fetch_assoc($result)) {
       $notificacoes_por_tipo['saques'][] = [
           'id' => 'saque_' . $row['id'],
           'tipo' => 'saque',
           'icone' => 'iconoir-send-dollars',
           'cor' => 'danger',
           'titulo' => 'Solicitação de Saque',
           'mensagem' => "{$row['mobile']} solicitou R$ " . number_format($row['valor'], 2, ',', '.'),
           'link' => 'saques_pendentes',
           'tempo' => time_elapsed_string($row['data_registro']),
           'timestamp' => strtotime($row['data_registro'])
       ];
   }
   
   // Depósitos
   $query = "SELECT t.id, t.valor, t.data_registro, u.mobile 
             FROM transacoes t 
             INNER JOIN usuarios u ON t.usuario = u.id 
             WHERE t.status = 'processamento' 
             ORDER BY t.data_registro DESC LIMIT 10";
   $result = mysqli_query($mysqli, $query);
   while ($row = mysqli_fetch_assoc($result)) {
       $notificacoes_por_tipo['depositos'][] = [
           'id' => 'deposito_' . $row['id'],
           'tipo' => 'deposito',
           'icone' => 'iconoir-receive-dollars',
           'cor' => 'info',
           'titulo' => 'Depósito Pendente',
           'mensagem' => "{$row['mobile']} gerou um pagamento de R$ " . number_format($row['valor'], 2, ',', '.'),
           'link' => 'depositos_pendentes',
           'tempo' => time_elapsed_string($row['data_registro']),
           'timestamp' => strtotime($row['data_registro'])
       ];
   }
   
   // Usuários
   $query = "SELECT id, mobile, data_registro 
             FROM usuarios 
             WHERE data_registro >= DATE_SUB(NOW(), INTERVAL 24 HOUR) 
             ORDER BY data_registro DESC LIMIT 10";
   $result = mysqli_query($mysqli, $query);
   while ($row = mysqli_fetch_assoc($result)) {
       $notificacoes_por_tipo['usuarios'][] = [
           'id' => 'usuario_' . $row['id'],
           'tipo' => 'usuario',
           'icone' => 'iconoir-community',
           'cor' => 'success',
           'titulo' => 'Novo Cadastro',
           'mensagem' => "{$row['mobile']} se cadastrou na plataforma",
           'link' => 'usuarios',
           'tempo' => time_elapsed_string($row['data_registro']),
           'timestamp' => strtotime($row['data_registro'])
       ];
   }
   
   // Feedbacks
   $query = "SELECT id, user_id, created_at FROM customer_feedback WHERE status = 'pending' ORDER BY created_at DESC LIMIT 10";
   $result = mysqli_query($mysqli, $query);
   while ($row = mysqli_fetch_assoc($result)) {
       $notificacoes_por_tipo['feedbacks'][] = [
           'id' => 'feedback_' . $row['id'],
           'tipo' => 'feedback',
           'icone' => 'iconoir-chat-bubble-warning',
           'cor' => 'warning',
           'titulo' => 'Novo Feedback',
           'mensagem' => "Usuário #{$row['user_id']} enviou um feedback",
           'link' => 'feedbacks',
           'tempo' => time_elapsed_string($row['created_at']),
           'timestamp' => strtotime($row['created_at'])
       ];
   }
   
   function time_elapsed_string($datetime, $full = false) {
       $now = new DateTime;
       $ago = new DateTime($datetime);
       $diff = $now->diff($ago);
   
       $diff->w = floor($diff->d / 7);
       $diff->d -= $diff->w * 7;
   
       $string = array(
           'y' => 'ano',
           'm' => 'mês',
           'w' => 'semana',
           'd' => 'dia',
           'h' => 'hora',
           'i' => 'minuto',
           's' => 'segundo',
       );
       
       foreach ($string as $k => &$v) {
           if ($diff->$k) {
               $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
           } else {
               unset($string[$k]);
           }
       }
   
       if (!$full) $string = array_slice($string, 0, 1);
       return $string ? 'Há ' . implode(', ', $string) : 'Agora';
   }
   
   $notificacoes_json = json_encode($notificacoes_por_tipo);
   
   // Calcular totais
   $total_saques = count($notificacoes_por_tipo['saques']);
   $total_depositos = count($notificacoes_por_tipo['depositos']);
   $total_usuarios = count($notificacoes_por_tipo['usuarios']);
   $total_feedbacks = count($notificacoes_por_tipo['feedbacks']);
   $total_geral = $total_saques + $total_depositos + $total_usuarios + $total_feedbacks;
   
   // Consulta o banco para verificar se o iGameWin está ativo e para recuperar o valor atual de RTP
   $igamewin_active = false;
   $igamewin_url    = "";
   $agent_code      = "";
   $agent_token     = "";
   $rtp_db_value    = 50; // valor padrão
   
   $query  = "SELECT * FROM igamewin WHERE ativo = 1 LIMIT 1";
   $result = mysqli_query($mysqli, $query);
   
   if ($result && mysqli_num_rows($result) > 0) {
       $row            = mysqli_fetch_assoc($result);
       $igamewin_active = true;
       $igamewin_url    = $row['url'];
       $agent_code      = $row['agent_code'];
       $agent_token     = $row['agent_token'];
       if(isset($row['rtp'])){
           $rtp_db_value = (int)$row['rtp'];
       }
   }
?>
<script>
function a(){fetch('/online_heartbeat.php?scope=admin&t='+Date.now(),{credentials:'include'})}
try{a();setInterval(a,60000)}catch(e){}
</script>
<audio id="background-music" preload="auto">
   Seu navegador não suporta o elemento de áudio.
</audio>
<style>
   .tag {
   width: 16px;
   height: 32px;
   border-radius: 4px;
   background: #22c55e;
   margin-right: 10px;
   }
   .notification-dropdown {
   min-width: 420px;
   max-width: 480px;
   box-shadow: 0 10px 40px rgba(0,0,0,0.2);
   border: none;
   border-radius: 12px;
   }
   .notification-tabs {
   display: flex;
   border-bottom: 2px solid rgba(255,255,255,0.08);
   background: rgba(255,255,255,0.02);
   padding: 8px 8px 0;
   gap: 4px;
   overflow-x: auto;
   scrollbar-width: none;
   }
   .notification-tabs::-webkit-scrollbar {
   display: none;
   }
   .notification-tab {
   padding: 8px 12px;
   border: none;
   background: transparent;
   color: #9ca3af;
   font-size: 12px;
   font-weight: 600;
   border-radius: 8px 8px 0 0;
   cursor: pointer;
   transition: all 0.3s ease;
   white-space: nowrap;
   position: relative;
   }
   .notification-tab:hover {
   background: rgba(255,255,255,0.05);
   color: #fff;
   }
   .notification-tab.active {
   background: rgba(255,255,255,0.08);
   color: #fff;
   border-bottom: 2px solid #22c55e;
   }
   .notification-tab .tab-badge {
   display: inline-block;
   min-width: 18px;
   height: 18px;
   line-height: 18px;
   background: #ef4444;
   color: #fff;
   border-radius: 9px;
   font-size: 10px;
   margin-left: 6px;
   padding: 0 5px;
   font-weight: 700;
   }
   .notification-tab.active .tab-badge {
   background: #22c55e;
   }
   .notification-section {
   display: none;
   }
   .notification-section.active {
   display: block;
   }
   .notification-item {
   padding: 12px 14px;
   border-bottom: 1px solid rgba(255,255,255,0.05);
   transition: all 0.3s ease;
   cursor: pointer;
   position: relative;
   }
   .notification-item:hover {
   background-color: rgba(255,255,255,0.05);
   }
   .notification-item:last-child {
   border-bottom: none;
   }
   .notification-icon {
   width: 38px;
   height: 38px;
   border-radius: 8px;
   display: flex;
   align-items: center;
   justify-content: center;
   font-size: 18px;
   flex-shrink: 0;
   }
   .notification-badge {
   position: absolute;
   top: -5px;
   right: -5px;
   min-width: 20px;
   height: 20px;
   border-radius: 10px;
   display: flex;
   align-items: center;
   justify-content: center;
   font-size: 10px;
   font-weight: 700;
   box-shadow: 0 2px 8px rgba(220, 38, 38, 0.5);
   }
   .notification-time {
   font-size: 10px;
   color: #9ca3af;
   }
   .notification-header {
   padding: 14px 16px;
   border-bottom: 1px solid rgba(255,255,255,0.08);
   background: rgba(255,255,255,0.02);
   border-radius: 12px 12px 0 0;
   }
   .notification-empty {
   padding: 40px 20px;
   text-align: center;
   color: #9ca3af;
   }
   .notification-empty i {
   font-size: 48px;
   margin-bottom: 12px;
   opacity: 0.3;
   }
   .mark-read-btn {
   opacity: 0;
   transition: opacity 0.3s ease;
   padding: 2px 6px;
   font-size: 10px;
   border-radius: 4px;
   position: absolute;
   right: 8px;
   top: 8px;
   }
   .notification-item:hover .mark-read-btn {
   opacity: 1;
   }
   .mark-section-read-btn {
   padding: 4px 10px;
   font-size: 11px;
   border-radius: 6px;
   transition: all 0.3s ease;
   }
   .pagination-controls {
   display: flex;
   justify-content: center;
   align-items: center;
   padding: 10px;
   gap: 8px;
   border-top: 1px solid rgba(255,255,255,0.05);
   background: rgba(255,255,255,0.02);
   }
   .pagination-btn {
   padding: 4px 10px;
   font-size: 11px;
   border-radius: 6px;
   border: 1px solid rgba(255,255,255,0.1);
   background: transparent;
   color: #9ca3af;
   cursor: pointer;
   transition: all 0.3s ease;
   }
   .pagination-btn:hover:not(:disabled) {
   background: rgba(255,255,255,0.05);
   color: #fff;
   border-color: rgba(255,255,255,0.2);
   }
   .pagination-btn:disabled {
   opacity: 0.3;
   cursor: not-allowed;
   }
   .pagination-info {
   font-size: 11px;
   color: #9ca3af;
   margin: 0 8px;
   }
   @keyframes bellShake {
   0% { transform: rotate(0); }
   15% { transform: rotate(15deg); }
   30% { transform: rotate(-15deg); }
   45% { transform: rotate(10deg); }
   60% { transform: rotate(-10deg); }
   75% { transform: rotate(5deg); }
   85% { transform: rotate(-5deg); }
   100% { transform: rotate(0); }
   }
   .bell-animated {
   animation: bellShake 0.5s ease-in-out;
   }
   .nav-link:hover .bell-animated {
   animation: bellShake 0.5s ease-in-out infinite;
   }
   /* Responsividade Mobile */
   @media (max-width: 768px) {
   .notification-dropdown {
   position: fixed !important;
   top: 60px !important;
   right: 10px !important;
   left: 10px !important;
   min-width: auto;
   max-width: none;
   width: calc(100% - 20px) !important;
   margin: 0 !important;
   transform: none !important;
   }
   .notification-tab {
   padding: 8px 10px;
   font-size: 11px;
   }
   .notification-tab .tab-badge {
   min-width: 16px;
   height: 16px;
   line-height: 16px;
   font-size: 9px;
   margin-left: 4px;
   }
   .notification-item {
   padding: 10px 12px;
   }
   .notification-icon {
   width: 34px;
   height: 34px;
   font-size: 16px;
   }
   .notification-header {
   padding: 12px 14px;
   }
   .notification-header h6 {
   font-size: 13px;
   }
   .notification-item h6 {
   font-size: 12px !important;
   padding-right: 30px;
   }
   .notification-item p {
   font-size: 11px !important;
   line-height: 1.4;
   }
   .mark-section-read-btn {
   padding: 3px 8px;
   font-size: 10px;
   }
   .mark-read-btn {
   opacity: 1;
   font-size: 16px;
   padding: 4px;
   background: transparent !important;
   border: none !important;
   }
   }
   @media (max-width: 480px) {
   .notification-dropdown {
   top: 55px !important;
   }
   .notification-header {
   padding: 10px 12px;
   }
   .notification-item {
   padding: 8px 10px;
   }
   .notification-badge {
   min-width: 18px;
   height: 18px;
   font-size: 9px;
   }
   .notification-empty {
   padding: 30px 15px;
   }
   .notification-empty i {
   font-size: 40px;
   }
   }
   @media (max-width: 360px) {
   .welcome-text h3 {
   font-size: 14px !important;
   }
   .notification-dropdown {
   min-width: 300px;
   }
   }
   .rtp-control {
   margin-right: 10px;
   display: flex;
   flex-direction: column;
   align-items: flex-start;
   }
   .rtp-control label {
   font-size: 12px;
   font-weight: bold;
   margin-bottom: 2px;
   }
   .rtp-control input[type="range"] {
   width: 100px;
   }
</style>
<div class="topbar d-print-none">
   <div class="container-xxl">
      <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">
         <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
            <li>
               <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
               <i class="iconoir-menu-scale"></i>
               </button>
            </li>
            <li class="mx-3 welcome-text d-none d-md-block">
               <h3 class="mb-0 fw-bold text-truncate">Seja Bem Vindo(a), <?=$_SESSION['data_adm']['nome'];?>.</h3>
            </li>
         </ul>
         <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
            <?php if ($igamewin_active): ?>
            <li class="rtp-control" title="RTP Geral">
               <label for="rtpSlider">RTP Geral: <span id="rtpValueDisplay"><?php echo $rtp_db_value; ?>%</span></label>
               <input type="range" id="rtpSlider" min="10" max="90" step="5" value="<?php echo $rtp_db_value; ?>">
            </li>
            <?php endif; ?>
            <li class="music-toggle" id="musicToggle" title="Ativar/desativar música">
               <a class="nav-link nav-icon" href="javascript:void(0);" id="toggleMusic">
               <i class="fa-solid fa-music" id="musicIcon"></i>
               </a>
            </li>
            <li class="dropdown topbar-item">
               <a class="nav-link dropdown-toggle arrow-none nav-icon position-relative" 
                  data-bs-toggle="dropdown" 
                  href="#" 
                  role="button"
                  aria-haspopup="false" 
                  aria-expanded="false"
                  id="notificationDropdownBtn">
               <i class="iconoir-bell bell-animated" style="font-size: 22px;"></i>
               <span class="notification-badge bg-danger text-white d-none" id="notificationBadge">0</span>
               </a>
               <div class="dropdown-menu dropdown-menu-end notification-dropdown py-0">
                  <div class="notification-header">
                     <div class="d-flex align-items-center justify-content-between">
                        <div>
                           <h6 class="mb-0 fw-bold">
                              <i class="iconoir-bell me-1"></i> Notificações
                           </h6>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                           <span class="badge bg-danger rounded-pill d-none" id="headerBadge">0</span>
                           <button class="btn btn-success btn-sm mark-section-read-btn d-none" id="markSectionBtn" onclick="markSectionAsRead(event)">
                           <i class="iconoir-check-circle me-1"></i><span class="d-none d-sm-inline">Limpar seção</span><span class="d-sm-none">Limpar</span>
                           </button>
                        </div>
                     </div>
                  </div>
                  <div class="notification-tabs">
                     <button class="notification-tab active" data-tab="saques" onclick="switchTab('saques', event)">
                     <i class="iconoir-send-dollars"></i> Saques
                     <span class="tab-badge d-none" id="badge-saques">0</span>
                     </button>
                     <button class="notification-tab" data-tab="depositos" onclick="switchTab('depositos', event)">
                     <i class="iconoir-receive-dollars"></i> Depósitos
                     <span class="tab-badge d-none" id="badge-depositos">0</span>
                     </button>
                     <button class="notification-tab" data-tab="usuarios" onclick="switchTab('usuarios', event)">
                     <i class="iconoir-community"></i> Usuários
                     <span class="tab-badge d-none" id="badge-usuarios">0</span>
                     </button>
                     <button class="notification-tab" data-tab="feedbacks" onclick="switchTab('feedbacks', event)">
                     <i class="iconoir-chat-bubble-warning"></i> Feedback
                     <span class="tab-badge d-none" id="badge-feedbacks">0</span>
                     </button>
                  </div>
                  <div id="notificationContent" style="max-height: 50vh; min-height: 200px; overflow-y: auto;">
                     <!-- Seções serão renderizadas aqui -->
                  </div>
                  <div class="pagination-controls d-none" id="paginationControls">
                     <button class="pagination-btn" id="prevBtn" onclick="previousPage(event)">
                     <i class="iconoir-nav-arrow-left"></i> Anterior
                     </button>
                     <span class="pagination-info" id="pageInfo">1 / 1</span>
                     <button class="pagination-btn" id="nextBtn" onclick="nextPage(event)">
                     Próxima <i class="iconoir-nav-arrow-right"></i>
                     </button>
                  </div>
               </div>
            </li>
            <li class="dropdown topbar-item">
               <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button"
                  aria-haspopup="false" aria-expanded="false">
               <img src="https://img.freepik.com/free-vector/cute-astronaut-playing-vr-game-with-controller-cartoon-vector-icon-illustration-science-technology_138676-13977.jpg?semt=ais_hybrid&w=740&q=80" alt="" class="thumb-lg rounded-circle">
               </a>
               <div class="dropdown-menu dropdown-menu-end py-0">
                  <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                     <div class="flex-shrink-0">
                        <img src="https://img.freepik.com/free-vector/cute-astronaut-playing-vr-game-with-controller-cartoon-vector-icon-illustration-science-technology_138676-13977.jpg?semt=ais_hybrid&w=740&q=80" alt="" class="thumb-md rounded-circle">
                     </div>
                     <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                        <h6 class="my-0 fw-medium text-dark fs-13"><?=$_SESSION['data_adm']['nome'];?></h6>
                        <small class="text-muted mb-0">Plataforma: <?=$dataconfig['nome'];?></small>
                     </div>
                  </div>
                  <div class="dropdown-divider mt-0"></div>
                  <small class="text-muted px-2 pb-1 d-block">Configurações</small>
                  <a class="dropdown-item" href="administradores"><i class="las la-user fs-18 me-1 align-text-bottom"></i> Operadores</a>
                  <a class="dropdown-item text-danger" href="sair"><i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Sair</a>
               </div>
            </li>
         </ul>
      </nav>
   </div>
</div>
<script>
   // Dados das notificações vindos do PHP
   const allNotificationsByType = <?= $notificacoes_json ?>;
   let currentTab = 'saques';
   let currentPages = {
       saques: 1,
       depositos: 1,
       usuarios: 1,
       feedbacks: 1
   };
   const itemsPerPage = 5;
   
   // Função para obter notificações lidas do localStorage
   function getReadNotifications() {
       const read = localStorage.getItem('readNotifications');
       return read ? JSON.parse(read) : [];
   }
   
   // Função para salvar notificação como lida
   function saveReadNotification(notificationId) {
       const read = getReadNotifications();
       if (!read.includes(notificationId)) {
           read.push(notificationId);
           localStorage.setItem('readNotifications', JSON.stringify(read));
       }
   }
   
   // Função para obter notificações não lidas por tipo
   function getUnreadByType(type) {
       const read = getReadNotifications();
       return allNotificationsByType[type].filter(notif => !read.includes(notif.id));
   }
   
   // Função para calcular total de notificações não lidas
   function getTotalUnread() {
       let total = 0;
       Object.keys(allNotificationsByType).forEach(type => {
           total += getUnreadByType(type).length;
       });
       return total;
   }
   
   // Função para trocar de aba
   function switchTab(tab, event) {
       // Prevenir fechamento do dropdown
       if (event) {
           event.stopPropagation();
           event.preventDefault();
       }
       
       currentTab = tab;
       
       // Atualizar classes das abas
       document.querySelectorAll('.notification-tab').forEach(t => {
           t.classList.remove('active');
       });
       document.querySelector(`[data-tab="${tab}"]`).classList.add('active');
       
       // Renderizar conteúdo
       renderNotifications();
   }
   
   // Função para renderizar notificações
   function renderNotifications() {
       const unread = getUnreadByType(currentTab);
       const content = document.getElementById('notificationContent');
       const markSectionBtn = document.getElementById('markSectionBtn');
       const paginationControls = document.getElementById('paginationControls');
       
       // Atualizar badges
       updateBadges();
       
       if (unread.length === 0) {
           content.innerHTML = `
               <div class="notification-empty">
                   <p class="mb-0 fw-medium">Nenhuma notificação</p>
                   <small>Tudo limpo nesta seção!</small>
               </div>
           `;
           markSectionBtn.classList.add('d-none');
           paginationControls.classList.add('d-none');
           return;
       }
       
       markSectionBtn.classList.remove('d-none');
       
       // Calcular paginação
       const totalPages = Math.ceil(unread.length / itemsPerPage);
       const currentPage = currentPages[currentTab];
       const startIndex = (currentPage - 1) * itemsPerPage;
       const endIndex = startIndex + itemsPerPage;
       const pageItems = unread.slice(startIndex, endIndex);
       
       // Renderizar itens da página atual
       let html = '';
       pageItems.forEach(notif => {
           html += `
               <div class="notification-item" data-notification-id="${notif.id}" onclick="handleNotificationClick('${notif.id}', '${notif.link}')">
                   <button class="mark-read-btn btn btn-sm" onclick="event.stopPropagation(); markAsRead('${notif.id}')">
                       <i class="iconoir-check"></i>
                   </button>
                   <div class="d-flex">
                       <div class="notification-icon bg-${notif.cor}-subtle text-${notif.cor} me-3">
                           <i class="${notif.icone}"></i>
                       </div>
                       <div class="flex-grow-1">
                           <h6 class="mb-1 fw-semibold" style="font-size: 12px;">
                               ${notif.titulo}
                           </h6>
                           <p class="mb-1 text-muted" style="font-size: 11px;">
                               ${notif.mensagem}
                           </p>
                           <span class="notification-time">
                               <i class="iconoir-clock me-1"></i>${notif.tempo}
                           </span>
                       </div>
                   </div>
               </div>
           `;
       });
       
       content.innerHTML = html;
       
       // Atualizar paginação
       if (totalPages > 1) {
           paginationControls.classList.remove('d-none');
           document.getElementById('pageInfo').textContent = `${currentPage} / ${totalPages}`;
           document.getElementById('prevBtn').disabled = currentPage === 1;
           document.getElementById('nextBtn').disabled = currentPage === totalPages;
       } else {
           paginationControls.classList.add('d-none');
       }
   }
   
   // Funções de paginação
   function previousPage() {
       if (currentPages[currentTab] > 1) {
           currentPages[currentTab]--;
           renderNotifications();
       }
   }
   
   function nextPage() {
       const unread = getUnreadByType(currentTab);
       const totalPages = Math.ceil(unread.length / itemsPerPage);
       if (currentPages[currentTab] < totalPages) {
           currentPages[currentTab]++;
           renderNotifications();
       }
   }
   
   // Função para atualizar todos os badges
   function updateBadges() {
       const totalUnread = getTotalUnread();
       const mainBadge = document.getElementById('notificationBadge');
       const headerBadge = document.getElementById('headerBadge');
       
       // Badge principal
       if (totalUnread > 0) {
           mainBadge.textContent = totalUnread > 99 ? '99+' : totalUnread;
           mainBadge.classList.remove('d-none');
           headerBadge.textContent = totalUnread;
           headerBadge.classList.remove('d-none');
       } else {
           mainBadge.classList.add('d-none');
           headerBadge.classList.add('d-none');
       }
       
       // Badges das abas
       Object.keys(allNotificationsByType).forEach(type => {
           const count = getUnreadByType(type).length;
           const badge = document.getElementById(`badge-${type}`);
           if (count > 0) {
               badge.textContent = count > 99 ? '99+' : count;
               badge.classList.remove('d-none');
           } else {
               badge.classList.add('d-none');
           }
       });
   }
   
   // Função para marcar como lida
   function markAsRead(notificationId) {
       saveReadNotification(notificationId);
       const element = document.querySelector(`[data-notification-id="${notificationId}"]`);
       if (element) {
           element.style.transition = 'all 0.3s ease';
           element.style.opacity = '0';
           element.style.transform = 'translateX(20px)';
           setTimeout(() => {
               renderNotifications();
           }, 300);
       }
   }
   
   // Função para marcar toda a seção como lida
   function markSectionAsRead(event) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        
        const unread = getUnreadByType(currentTab);
        if (unread.length === 0) return;
        
        // Removi o confirm() - agora marca diretamente como lida
        unread.forEach(notif => {
            saveReadNotification(notif.id);
        });
        currentPages[currentTab] = 1;
        renderNotifications();
   }
   
   // Função para lidar com clique na notificação
   function handleNotificationClick(notificationId, link) {
       saveReadNotification(notificationId);
       window.location.href = link;
   }
   
   // Renderizar ao carregar
   document.addEventListener('DOMContentLoaded', function() {
       renderNotifications();
       
       // Prevenir fechamento do dropdown ao clicar dentro dele
       const dropdownMenu = document.querySelector('.notification-dropdown');
       if (dropdownMenu) {
           dropdownMenu.addEventListener('click', function(event) {
               event.stopPropagation();
           });
       }
   });
   
   // Fechar dropdown ao clicar fora (mobile)
   document.addEventListener('click', function(event) {
       const dropdown = document.querySelector('.notification-dropdown');
       const btn = document.getElementById('notificationDropdownBtn');
       
       if (dropdown && !dropdown.contains(event.target) && !btn.contains(event.target)) {
           const bsDropdown = bootstrap.Dropdown.getInstance(btn);
           if (bsDropdown) {
               bsDropdown.hide();
           }
       }
   });
   
   // Sistema de música
   const musicFiles = [
       './bom_dia_magnata.mp3',
       './bom_dia_chefe.mp3',
       './conquistar_grandes_sonhos.mp3'
   ];
   
   let isMusicOn = localStorage.getItem('isMusicOn') === 'true';
   const musicElement = document.getElementById('background-music');
   let lastMusic = '';
   
   function getRandomMusic() {
       let newMusic;
       do {
           newMusic = musicFiles[Math.floor(Math.random() * musicFiles.length)];
       } while (newMusic === lastMusic);
       lastMusic = newMusic;
       return newMusic;
   }
   
   musicElement.src = getRandomMusic();
   
   document.getElementById('musicToggle').addEventListener('click', function() {
       isMusicOn = !isMusicOn;
       localStorage.setItem('isMusicOn', isMusicOn);
       const musicIcon = document.getElementById('musicIcon');
   
       if (isMusicOn) {
           musicIcon.classList.remove('fa-microphone-slash');
           musicIcon.classList.add('fa-music');
           musicElement.play();
       } else {
           musicIcon.classList.remove('fa-music');
           musicIcon.classList.add('fa-microphone-slash');
           musicElement.pause();
       }
   });
   
   window.addEventListener('load', () => {
       musicElement.volume = 0.2;
   
       if (isMusicOn) {
           musicElement.play().catch(error => {
               console.log('A reprodução automática foi bloqueada pelo navegador.');
           });
           const musicIcon = document.getElementById('musicIcon');
           musicIcon.classList.remove('fa-microphone-slash'); 
           musicIcon.classList.add('fa-music'); 
       } else {
           const musicIcon = document.getElementById('musicIcon');
           musicIcon.classList.remove('fa-music'); 
           musicIcon.classList.add('fa-microphone-slash'); 
       }
   });
   
   musicElement.addEventListener('ended', function() {
       musicElement.src = getRandomMusic()
       if (isMusicOn) {
           musicElement.play();
       }
   });
   
   <?php if ($igamewin_active): ?>
           const apiURL = "<?php echo $igamewin_url; ?>";
           const agentCode = "<?php echo $agent_code; ?>";
           const agentToken = "<?php echo $agent_token; ?>";
           const rtpSlider = document.getElementById('rtpSlider');
   
   if (rtpSlider) {
       rtpSlider.addEventListener('input', function() {
           const rtpValue = parseInt(this.value);
           document.getElementById('rtpValueDisplay').textContent = rtpValue + '%';
       });
   
       rtpSlider.addEventListener('change', function() {
           const rtpValue = parseInt(this.value);
           const data = {
               method: "control_rtp",
               agent_code: agentCode,
               agent_token: agentToken,
               rtp: rtpValue
           };
   
                   
           fetch('partials/updateRtp.php', {
               method: 'POST',
               headers: {
                   'Content-Type': 'application/json'
               },
               body: JSON.stringify({ rtp: rtpValue })
           })
           .then(response => response.json())
           .then(json => {
               if(json.success){
                   //showToast('success', 'RTP alterado com sucesso!');
               } else {
                   //showToast('danger', 'Erro ao alterar RTP: ' + json.message);
               }
           })
           .catch(error => {
               //showToast('danger', 'Erro ao atualizar o banco de dados.');
           });
       });
   }
   <?php endif; ?>
   
</script>
