<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);
require_once "config.php";
require_once DASH . "/services/database.php";
require_once DASH . "/services/funcao.php";
require_once DASH . "/services/crud.php";
require_once DASH . "/services/CSRF_Protect.php";
require_once DASH . "/services/pega-ip.php";
require_once DASH . "/services/ip-crawler.php";
$csrf = new CSRF_Protect();
$ads_tipo = !empty($_GET['utm_ads']) ? PHP_SEGURO($_GET['utm_ads']) : null;
$url_atual = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
$referencia = $_SERVER['HTTP_REFERER'] ?? $url_atual;
$data_hoje = date("Y-m-d");
$hora_hoje = date("H:i:s");
// Resolver gargalo: só buscar geolocalização se for necessário inserir
$id_user = 1;
$stmt = $mysqli->prepare("SELECT 1 FROM visita_site WHERE data_cad = ? AND ip_visita = ?");
$stmt->bind_param("ss", $data_hoje, $ip);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows === 0) {
    $data_us = null;
    if (!empty($_SESSION['ip_geo']) &&
        isset($_SESSION['ip_geo']['ip'], $_SESSION['ip_geo']['data']) &&
        $_SESSION['ip_geo']['ip'] === $ip &&
        $_SESSION['ip_geo']['data'] === $data_hoje &&
        is_array($_SESSION['ip_geo']['info'])) {
        $data_us = $_SESSION['ip_geo']['info'];
    } else {
        $data_us = ip_F($ip);
        $_SESSION['ip_geo'] = [
            'ip' => $ip,
            'data' => $data_hoje,
            'info' => $data_us,
        ];
    }

    if (
        $browser !== "Unknown Browser" &&
        $os !== "Unknown OS Platform" &&
        isset($data_us['pais']) && $data_us['pais'] === "Brazil"
    ) {
        $stmt = $mysqli->prepare(
            "INSERT INTO visita_site (
                nav_os, mac_os, ip_visita, refer_visita, data_cad, hora_cad, id_user,
                pais, cidade, estado, ads_tipo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            "sssssssssss",
            $browser, $os, $ip, $referencia, $data_hoje, $hora_hoje,
            $id_user, $data_us['pais'], $data_us['cidade'], $data_us['regiao'], $ads_tipo
        );
        $stmt->execute();
    }
}
$style_inline = '';
$res = $mysqli->query("SELECT temas FROM templates_cores WHERE ativo = 1 LIMIT 1");
if ($res && $row = $res->fetch_assoc()) {
    $temas = json_decode($row['temas'], true);
    if (is_array($temas)) {
        $style_inline = implode(';', array_map(
            fn($k, $v) => "$k:$v",
            array_keys($temas),
            $temas
        )) . ';';
    }
}

// ============================================
// BUSCAR STATUS DO MENU NAVBAR
// ============================================
$menu_navbar_ativo = true; // Padrão ativo

try {
    $result = $mysqli->query("SELECT menu_navbar_ativo FROM config LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
        $menu_navbar_ativo = (bool) $row['menu_navbar_ativo'];
    }
} catch (Exception $e) {
    error_log("Erro ao buscar menu_navbar_ativo: " . $e->getMessage());
}

// ============================================
// BUSCAR STATUS DO TEMA NATAL
// ============================================
$natal_theme_active = false; // Padrão inativo

try {
    $result = $mysqli->query("SELECT natal_theme_active FROM config LIMIT 1");
    if ($result && $row = $result->fetch_assoc()) {
        $natal_theme_active = (bool) $row['natal_theme_active'];
    }
} catch (Exception $e) {
    error_log("Erro ao buscar natal_theme_active: " . $e->getMessage());
}

$online_count = get_online_count();
?>
<!DOCTYPE html>
<html lang=en data-version="Tue Apr 29 2025 03:28:09 GMT+0000 (Coordinated Universal Time)"
    data-request-id=220a64f1c958dc28d9f219ed8c55f044
    data-render="Tue Apr 29 2025 14:28:23 GMT+0000 (Coordinated Universal Time)" data-render-init=v6.0.14
    style="<?= htmlspecialchars($style_inline, ENT_QUOTES, 'UTF-8') ?>">

<head>
    <script>
    (function(){
        function isRegisterUrl(u){
            try{
                var url = typeof u === 'string' ? u : (u && u.url) || '';
                return /\/(hall\/)?api\/member\/register/.test(url) || /\/member\/register/.test(url);
            }catch(_e){ return false; }
        }
        (function() {
            var originalOpen = XMLHttpRequest.prototype.open;
            var originalSend = XMLHttpRequest.prototype.send;
            XMLHttpRequest.prototype.open = function(method, url) {
                this._url = url;
                this._method = method;
                return originalOpen.apply(this, arguments);
            };
            XMLHttpRequest.prototype.send = function() {
                var xhr = this;
                if (xhr._url && isRegisterUrl(xhr._url)) {
                    xhr.addEventListener('readystatechange', function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            try {
                                var data = JSON.parse(xhr.responseText);
                                if (data && data.code === 1 && data.data && data.data.showWelcomeModal === true) {
                                    document.cookie = 'welcome_after_register=1; path=/; max-age=300; samesite=Lax';
                                    if(!window.__welcomeReloadScheduled){
                                        window.__welcomeReloadScheduled = true;
                                        setTimeout(function(){ location.reload(); }, 550);
                                    }
                                }
                            } catch(e) {}
                        }
                    });
                }
                return originalSend.apply(this, arguments);
            };
        })();
        (function() {
            var originalFetch = window.fetch;
            window.fetch = function(...args) {
                var url = typeof args[0] === 'string' ? args[0] : (args[0] && args[0].url);
                return originalFetch.apply(this, args).then(function(response){
                    if (url && isRegisterUrl(url)) {
                        var clonedResponse = response.clone();
                        clonedResponse.json().then(function(data){
                            if (data && data.code === 1 && data.data && data.data.showWelcomeModal === true) {
                                document.cookie = 'welcome_after_register=1; path=/; max-age=300; samesite=Lax';
                                if(!window.__welcomeReloadScheduled){
                                    window.__welcomeReloadScheduled = true;
                                    setTimeout(function(){ location.reload(); }, 30);
                                }
                            }
                        }).catch(function(_err){});
                    }
                    return response;
                });
            };
        })();
    })();
    </script>
    <script type=module crossorigin="" src=/assets/theme-2/polyfills.BHSyO4m2.js></script>
    <meta charset=UTF-8>
    <link rel=preconnect href=https://gsfag.carvalhopg.com/ crossorigin="">
    <link rel=dns-prefetch href=https://gsfag.carvalhopg.com />
    <link rel=preconnect href=https://pubusppp.c1oudfront.com/ crossorigin="">
    <link rel=dns-prefetch href=https://pubusppp.c1oudfront.com />
    <link rel="shortcut icon" href="/uploads/<?= $dataconfig['favicon']; ?>">
    <link sizes=32x32 rel=icon  href="/uploads/<?= $dataconfig['favicon']; ?>"
        data-global-meta="">
    <link rel=apple-touch-icon  href="/uploads/<?= $dataconfig['favicon']; ?>"
        data-global-meta="">
    <link sizes=192x192 rel=apple-touch-icon
         href="/uploads/<?= $dataconfig['favicon']; ?>" data-global-meta="">
    <link rel=apple-touch-icon-precomposed
         href="/uploads/<?= $dataconfig['favicon']; ?>" data-global-meta="">
    <meta name=viewport content="width=device-width,initial-scale=1,user-scalable=no">
    <meta property=twitter:card content=summary_large_image>
    <meta name=mobile-web-app-capable content=yes>
    <meta name=apple-mobile-web-app-capable content=yes>
    <meta name=apple-mobile-web-app-status-bar-style content=black>
    <meta name=google content=notranslate>
    <link rel=icon href=data:image/ico;base64,aWNv>
    <link rel=manifest id=pwa-manifest>
    <style>
        :root {
            --lobby__max-width: 100%
        }

        *,
        ::after,
        ::before {
            box-sizing: inherit;
            padding: 0;
            margin: 0
        }



       /* ._game-platform-name_edfmq_43 {  //nome dos jogos//
            display: none !important
        }*/
        
        
        
        ._title_g68xy_50 {
         display: none !important;
        }
        
        ._lang_lz3od_105 {
            display: none !important;
        }
        
        ._menu-lang_14qgi_84 {
            display: none !important;
        }
        
        ._promot-index_1bn32_31 {
            top: 10px !important;
        }
        
        ._level-image_1a6qo_30 ._icon-img_1a6qo_36 {
            background-image: url('https://ozap888.888paz.cc/siteadmin/active/style1/iconColor/style_1_vip_color1.avif') !important;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 400
        }

        ul {
            list-style: none
        }

        img,
        video {
            height: auto;
            max-width: 100%
        }

        iframe {
            border: 0
        }

        table {
            border-collapse: collapse;
            border-spacing: 0
        }

        html {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent
        }

        html[data-device=mobile] {
            font-size: calc(100vw / (750 / 100))
        }

        html[data-ui-contain='1'] {
            background: var(--skin__border);
            font-size: calc(var(--lobby__max-width) / (750 / 100))
        }

        html[data-ui-contain='0'] {
            --lobby__max-width: 100%
        }
        
        /* ==========================================
           BANNER CAROUSEL - CSS ESPECÍFICO
           Afeta APENAS o banner carousel
           ========================================== */
        
        /* ==========================================
           CONTAINERS DO BANNER - SELETORES ESPECÍFICOS
           ========================================== */
        
        /* Container principal do banner */
        ._banner-container_1xtky_30 {
          width: 100%;
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }
        
        /* Box do banner */
        ._banner-container_1xtky_30 ._banner-box-3_1xtky_61 {
          width: 100% !important;
          margin: 0 !important;
          padding: 0 !important;
          box-sizing: border-box;
        }
        
        /* Swiper dentro do banner container */
        ._banner-container_1xtky_30 .swiper {
          width: 100% !important;
          margin: 0 !important;
          padding: 0 !important;
          box-sizing: border-box;
        }
        
        ._banner-container_1xtky_30 .swiper-wrapper {
          width: 100%;
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }
        
        ._banner-container_1xtky_30 .swiper-slide {
          width: 100% !important;
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }
        
        /* ==========================================
           BANNER ITEM - APENAS DENTRO DO CONTAINER
           ========================================== */
        
        /* Banner item específico */
        ._banner-container_1xtky_30 ._banner-item_1gweo_31 {
          width: 100%;
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }
        
        /* Background image do banner */
        ._banner-container_1xtky_30 ._banner-item_1gweo_31 ._bg-img_1gweo_37 {
          width: 100% !important;
          height: 100%;
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          background-size: cover !important;
          background-position: center !important;
          background-repeat: no-repeat !important;
        }
        
        /* Modo 3 específico */
        ._banner-container_1xtky_30 ._banner-item_1gweo_31._mode-3_1gweo_121 ._bg-img_1gweo_37._bg-img-3_1gweo_51 {
          width: 100% !important;
          border-radius: 8px;
        }
        
        /* ==========================================
           IMAGENS - APENAS DENTRO DO BANNER
           ========================================== */
        
        /* Imagens lobby apenas dentro do banner container */
        ._banner-container_1xtky_30 .lobby-image--use-bg {
          width: 100% !important;
          height: 100%;
          background-size: cover !important;
          background-position: center !important;
          background-repeat: no-repeat !important;
        }
        
        ._banner-container_1xtky_30 ._bg-cusomize-correction_1gweo_54 {
          background-size: cover !important;
          background-position: center !important;
        }
        
        /* ==========================================
           PAGINAÇÃO - APENAS DO BANNER
           ========================================== */
        
        ._banner-container_1xtky_30 .swiper-pagination {
          position: absolute !important;
          bottom: 12px !important;
          left: 50% !important;
          right: auto !important;
          transform: translateX(-50%) !important;
          z-index: 10;
          width: auto !important;
          display: flex !important;
          justify-content: center !important;
          align-items: center !important;
        }
        
        ._banner-container_1xtky_30 .swiper-pagination-bullets {
          display: flex !important;
          justify-content: center !important;
          align-items: center !important;
          gap: 6px !important;
        }
        
        ._banner-container_1xtky_30 .swiper-pagination-bullet {
          width: 8px !important;
          height: 8px !important;
          background: rgba(255, 255, 255, 0.6) !important;
          border-radius: 50% !important;
          transition: all 0.3s ease !important;
          opacity: 1 !important;
          margin: 0 !important;
          flex-shrink: 0 !important;
        }
        
        ._banner-container_1xtky_30 .swiper-pagination-bullet:hover {
          background: rgba(255, 255, 255, 0.85) !important;
          transform: scale(1.2) !important;
        }
        
        ._banner-container_1xtky_30 .swiper-pagination-bullet-active {
          background: #fff !important;
          width: 20px !important;
          height: 8px !important;
          border-radius: 4px !important;
        }
        
        /* ==========================================
           BANNER DE DOWNLOAD NO TOPO
           ========================================== */
        
        ._banner-container_1xtky_30 ._topAd_1xtky_80 {
          width: 100%;
          background: linear-gradient(135deg, #ff6b9d 0%, #c239b3 100%);
          padding: 16px;
          margin: 0;
          box-sizing: border-box;
        }
        
        ._banner-container_1xtky_30 ._top-download_sg07q_30 {
          display: flex;
          align-items: center;
          gap: 16px;
          padding: 12px 16px;
          background: rgba(255, 255, 255, 0.15);
          backdrop-filter: blur(10px);
          border-radius: 8px;
          position: relative;
        }
        
        ._banner-container_1xtky_30 ._top-download-close_sg07q_39 {
          position: absolute;
          top: 8px;
          right: 8px;
          cursor: pointer;
          width: 24px;
          height: 24px;
          display: flex;
          align-items: center;
          justify-content: center;
          background: rgba(0, 0, 0, 0.2);
          border-radius: 50%;
          transition: all 0.2s ease;
          z-index: 10;
        }
        
        ._banner-container_1xtky_30 ._top-download-close_sg07q_39:hover {
          background: rgba(0, 0, 0, 0.4);
          transform: scale(1.1);
        }
        
        ._banner-container_1xtky_30 ._top-download-close_sg07q_39 svg {
          width: 14px;
          height: 14px;
          color: #fff;
        }
        
        ._banner-container_1xtky_30 ._top-download-content_sg07q_47 {
          display: flex;
          align-items: center;
          gap: 12px;
          flex: 1;
          padding-right: 32px;
        }
        
        ._banner-container_1xtky_30 ._top-download-content_sg07q_47 img {
          width: 48px;
          height: 48px;
          object-fit: contain;
          flex-shrink: 0;
        }
        
        ._banner-container_1xtky_30 ._top-download-inner-html_sg07q_55 {
          flex: 1;
          color: #fff;
        }
        
        ._banner-container_1xtky_30 ._top-download-inner-html_sg07q_55 p {
          margin: 0;
          font-size: 14px;
          font-weight: 500;
          line-height: 1.4;
        }
        
        ._banner-container_1xtky_30 ._top-download-inner-html_sg07q_55 strong {
          font-weight: 700;
        }
        
        ._banner-container_1xtky_30 ._ghost_sg07q_115 {
          background: rgba(255, 255, 255, 0.25) !important;
          border: 2px solid #fff !important;
          color: #fff !important;
          padding: 10px 20px;
          border-radius: 6px;
          font-weight: 600;
          cursor: pointer;
          transition: all 0.3s ease;
          white-space: nowrap;
        }
        
        ._banner-container_1xtky_30 ._ghost_sg07q_115:hover {
          background: rgba(255, 255, 255, 0.35) !important;
          transform: translateY(-2px);
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        /* ==========================================
           ANIMAÇÕES - APENAS DO BANNER
           ========================================== */
        
        ._banner-container_1xtky_30 ._banner-item_1gweo_31 {
          transition: transform 0.3s ease;
        }
        
        ._banner-container_1xtky_30 ._banner-item_1gweo_31:hover {
          transform: translateY(-2px);
        }
        
        ._banner-container_1xtky_30 ._banner-item_1gweo_31 ._bg-img_1gweo_37 {
          transition: transform 0.5s ease;
        }
        
        ._banner-container_1xtky_30 ._banner-item_1gweo_31:hover ._bg-img_1gweo_37 {
          transform: scale(1.02);
        }
        
        /* ==========================================
           LOADING SKELETON - APENAS DO BANNER
           ========================================== */
        
        ._banner-container_1xtky_30 .lobby-image--skeleton::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0.05) 0%,
            rgba(255, 255, 255, 0.15) 50%,
            rgba(255, 255, 255, 0.05) 100%
          );
          animation: banner-shimmer 1.5s infinite;
          z-index: 1;
        }
        
        ._banner-container_1xtky_30 .lobby-image[data-status="success"]::before {
          display: none;
        }
        
        @keyframes banner-shimmer {
          0% {
            transform: translateX(-100%);
          }
          100% {
            transform: translateX(100%);
          }
        }
        
        /* ==========================================
           RESPONSIVIDADE - APENAS DO BANNER
           ========================================== */
        
        @media (max-width: 768px) {
          ._banner-container_1xtky_30 ._top-download_sg07q_30 {
            flex-direction: column;
            text-align: center;
            gap: 12px;
          }
          
          ._banner-container_1xtky_30 ._top-download-content_sg07q_47 {
            flex-direction: column;
            text-align: center;
            padding-right: 0;
          }
          
          ._banner-container_1xtky_30 ._ghost_sg07q_115 {
            width: 100%;
          }
        }
        
        @media (max-width: 480px) {
          ._banner-container_1xtky_30 ._topAd_1xtky_80 {
            padding: 12px;
          }
          
          ._banner-container_1xtky_30 ._top-download-content_sg07q_47 img {
            width: 40px;
            height: 40px;
          }
          
          ._banner-container_1xtky_30 ._top-download-inner-html_sg07q_55 p {
            font-size: 12px;
          }
          
          ._banner-container_1xtky_30 .swiper-pagination {
            bottom: 8px !important;
          }
          
          ._banner-container_1xtky_30 .swiper-pagination-bullet {
            width: 6px !important;
            height: 6px !important;
          }
          
          ._banner-container_1xtky_30 .swiper-pagination-bullet-active {
            width: 16px !important;
            height: 6px !important;
          }
        }
        
        /* ==========================================
           PERFORMANCE - APENAS DO BANNER
           ========================================== */
        
        ._banner-container_1xtky_30 .swiper-slide,
        ._banner-container_1xtky_30 ._bg-img_1gweo_37 {
          will-change: transform;
          -webkit-backface-visibility: hidden;
          backface-visibility: hidden;
        }
        
        /* ==========================================
           ACESSIBILIDADE - APENAS DO BANNER
           ========================================== */
        
        ._banner-container_1xtky_30 ._top-download-close_sg07q_39:focus,
        ._banner-container_1xtky_30 ._ghost_sg07q_115:focus,
        ._banner-container_1xtky_30 .swiper-pagination-bullet:focus {
          outline: 2px solid #fff;
          outline-offset: 2px;
        }
        
        ._banner-container_1xtky_30 * {
          -webkit-tap-highlight-color: transparent;
        }
    </style>
    <script
        defer=defer>!function () { for (var g = JSON.stringify({ accessRestrictedContentMerge: '<p style="text-align: center;"><span style="color: #060600;"><strong><span style="font-size: 32px;">访问受限</span></strong></span></p>\r\n <p style="text-align: center;"><span style="color: #060600;"><strong><span style="font-size: 32px;">Access Restricted</span></strong></span></p>\r\n <p style="text-align: center;">&nbsp;</p>\r\n <p style="text-align: center;">&nbsp;</p>\r\n <p style="line-height: 1.6;">&nbsp; &nbsp; &nbsp; &nbsp; <span style="font-size: 18px; color: #060600;">IP所在地区不在我们的服务范围，请更换其他国家IP，若给您带来的不便，敬请谅解。</span></p>\r\n <p style="line-height: 1.6;"><span style="font-size: 18px; color: #060600;">&nbsp; &nbsp; &nbsp; Your IP address is out of our service range, please change to an IP of a different country. We sincerely apologize for the inconvenience caused</span></p>\r\n <p>&nbsp;</p>', antiSwitch: "0", appIconBigPath: "https://YjJuc3l2LTc5MjAtcHBw@4579cfb0-c72e-4aaf-87e2-b643f42e87d2.czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@4579cfb0-c72e-4aaf-87e2-b643f42e87d2/cocos/lg/appIconBig.png", appIconSkeletonPath: "", appIconSmallPath: "https://YjJuc3l2LTc5MjAtcHBw@4579cfb0-c72e-4aaf-87e2-b643f42e87d2.czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@4579cfb0-c72e-4aaf-87e2-b643f42e87d2/cocos/lg/appIconSmall.png", commonOssBucket: null, commonOssDomain: null, currencyCode: "BRL", "dec2NyaXB0@4579cfb0-c72e-4aaf-87e2-b643f42e87d2ion": "", footerStatisticalCode: null, humanVerify: -1, keyword: "", languageCode: "pt", loadingImg: "https://YjJuc3l2LTc5MjAtcHBw@4579cfb0-c72e-4aaf-87e2-b643f42e87d2.czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@4579cfb0-c72e-4aaf-87e2-b643f42e87d2/cocos/lg/h5-load.png", ossName: "YjJuc3l2LTc5MjAtcHBw@4579cfb0-c72e-4aaf-87e2-b643f42e87d2", ossType: "czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@4579cfb0-c72e-4aaf-87e2-b643f42e87d2", shortcutSwitch: 1, siteAppIcon: "https://YjJuc3l2LTc5MjAtcHBw@4579cfb0-c72e-4aaf-87e2-b643f42e87d2.czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@4579cfb0-c72e-4aaf-87e2-b643f42e87d2/cocos/lg/h5icon.ico", siteCode: "7920", siteName: "站158", skinType: 22, statisticalCode: "", title: "CASSINO", type: 2, vestBagSaveLocal: 0, CREATED_TIME: 1745936902507, SPLASH_NODE_CLASSNAME: "skeleton-screen-main", OSS_MODE: !0, INJECT_DATA: { apiGetOptimizationSiteConfigV2: { data: { data: { layoutDesign: { advanceSetting: {}, backgroundShading: { appBackgroundShadingValue: "", type: 3 }, banner: 3, baseConfig: { appMyPage: "1", desktopShortcut: "1", test: "1" }, btmItems: ["home", "promote", "discount", "register", "mine"], commonConfig: { ageLimit: "18", americaSportLiveFlag: "0", commissionBubble: "0", displayLiveFlag: "0", footerStyle: "1", hallReturnType: "1", loadingType: "1", noWalletTipCny: "0", noWalletTipInr: "0", olympicSportLiveFlag: "0", registerLogin: "1", registerLoginDisplay: "0", sportFlag: "0", sportLiveFlag: "0", test: "1", turnPageType: "1", windowCss: "1" }, gameIcon: 1, hgAgeConfig: { cancelBtnText: "desistir", confirmBtnText: "aceitar", content: "", isOpen: 0, style: 1 }, iconColor: 0, imgPath: "/siteadmin/layoutDesign/", layouts: '["banner","top","icon"]', loginBtmItems: ["home", "promote", "discount", "recharge", "mine"], loginMidItems: ["yueBao", "vip", "promote"], loginMidItemsMore: ["customer", "withdraw", "recharge", "activity", "task", "rebate", "feedback", "security", "download", "setting", "accountDetails", "bettingRecord", "personalStatement", "award", "withdrawalManagement", "language", "audit", "facebook", "google", "line", "providentFund", "blindBox", "home", "mine"], loginSlogan: "", midItems: ["yueBao", "vip", "promote"], midItemsMore: ["customer", "withdraw", "recharge", "activity", "task", "rebate", "feedback", "security", "download", "setting", "accountDetails", "bettingRecord", "personalStatement", "award", "withdrawalManagement", "language", "audit", "facebook", "google", "line", "providentFund", "blindBox", "home", "mine"], platformIcon: 1, skeletonStyle: { img: "", textStyle: "1", type: 3 }, topLayout: 2 }, brandLogoUse: { iosBootInstallation: { shortcutsLogo: "" }, pcBootInstallation: { shortcutsLogo: "https://YjJuc3l2LTc5MjAtcHBw@4579cfb0-c72e-4aaf-87e2-b643f42e87d2.czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@4579cfb0-c72e-4aaf-87e2-b643f42e87d2/siteadmin/upload/img/1912634304413855745.png" }, androidBootInstallation: { shortcutsLogo: "https://YjJuc3l2LTc5MjAtcHBw@4579cfb0-c72e-4aaf-87e2-b643f42e87d2.czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@4579cfb0-c72e-4aaf-87e2-b643f42e87d2/siteadmin/upload/img/1912634232538427393.png" }, appIconBigPath: "", brandName: "", brandNameText: "", type: 1, officialWebsite: "" } }, code: 1 } }, apiGetSystemStatus: { data: { data: { homeGetSysInfo: { accountRegister: { cpf: 0, currency: 0, email: 0, inviteCode: 0, inviteCodeUnchangeable: 0, passwordConfirm: 1, phone: 2, quickEntry: { loginQuickEntryCustomer: 1, loginQuickEntryFree: 1, loginQuickEntryInstant: 1, registerQuickEntryCustomer: 1, registerQuickEntryFree: 1, registerQuickEntryInstant: 1 }, realName: 0 }, agentDisplayAgentSetting: 1, agentDisplayAgentTransfer: 2, agentDisplayCreateAccount: 1, agentDisplayEntrance: 1, agentDisplayParent: 2, agentDisplayTutorial: 1, autoVisitorLogin: 1, autoVisitorLoginH5: 3, auto_visitor_login: 1, auto_visitor_login_h5: 1, auto_visitor_login_web: 1, betTaskDisplayToggle: 2, clubFriendState: 1, clubSecurityVerify: { clubSecurityEmail: 0, clubSecurityGoogle: 1, clubSecurityPhone: 0 }, cpfRegexValidateDisabled: 1, directFinancial: 1, directNewAccount: 1, directOrder: 1, directReport: 1, directTake: 1, emailRequired: 0, emailSwitch: 0, enableModifyPhone: 1, enable_club: 1, enable_onekey_register: 2, enable_using_gmail: 2, enable_using_sms: 2, facebook: { app_id: "", status: 0 }, fingerprintJS: { publicKey: "cIMrDd2qJKZFByajXD7O" }, forgetPwdVerify: { securityEmail: 0, securityGoogle: 1, securityPassQuestion: 2, securityPassQuestionAccount: 0, securityPhone: 0, securityWithdrawPass: 2 }, forget_account_passwd_phone_verify_switch: 1, forget_account_passwd_question_verify_switch: 1, forget_bank_passwd_phone_verify_switch: 1, forget_bank_passwd_question_verify_switch: 1, free_training_switch: 1, geetestDeviceAndroidAppId: "ew2as8ojhn1vyjy8xtxd0myhkxscgqay", geetestDeviceAppId: "9ia4hndgblg9xihxcwgdjt9ztg8sjwaf", geetestDeviceIOSAppId: "ewkvbrw52ypxzpni86epjpwg9l19ieer", geetest_captcha_id: { feedback: "62c528ead784206de7e6db17765b9ac0", force_geetest: "62c528ead784206de7e6db17765b9ac0", login: "62c528ead784206de7e6db17765b9ac0", recharge: "62c528ead784206de7e6db17765b9ac0", register: "62c528ead784206de7e6db17765b9ac0" }, google: { app_id: "", status: 0 }, inviter_code_required: 0, inviter_code_switch: 0, inviter_code_update: 0, kfOnlineStatus: 1, kyc: { legalUserCountry: "0", legalUserEkycDoc: "0", legalUserEkycFace: "0", legalUserEkycTips: "De acordo com os regulamentos de <a href='https://www.pagcor.ph/index.php'>\"PAGCOR\"</a>, você precisa concluir a certificação KYC antes de poder usá-lo!", legalUserEmployerName: "0", legalUserFreezeCloseWindow: "0", legalUserFreezeHours: "72", legalUserFreezeSwitch: "0", legalUserFreezeTips: "De acordo com os regulamentos do <a href='https://www.pagcor.ph/index.php'>\"PAGCOR\"</a>, você precisa preencher todas as informações a seguir dentro de 72 horas após o registro, caso contrário a conta ficará inutilizável e as restrições não serão suspensas até que todas sejam concluídas.", legalUserFullname: "0", legalUserIncomeSource: "0", legalUserPlaceOfBirth: "0", legalUserPlaceOfCurrent: "0", legalUserPlaceOfPermanent: "0", legalUserWithdrawTips: "De acordo com os regulamentos de <a href='https://www.pagcor.ph/index.php'>\"PAGCOR\"</a>, você precisa preencher as seguintes informações antes de poder sacar dinheiro!", legalUserWorkType: "0", pcKycSwitch: 0, popWinAgreement: 0, provider: null, regFillRealInfoSwitch: 0, regKycSwitch: 0, withdrawKycSwitch: 0 }, legal_config_active_status: "0", legal_config_location_detection: "0", legal_config_login_status: "0", line: { app_id: "", status: 0 }, loginGameRestrictions: { loginGameDownloadApp: 0, loginGameDownloadAppTips: "Para uma melhor experiência de jogo, baixe e instale o APP mais recente e, em seguida, use a conta: {{CustomerAccount}} para fazer login e jogar no APP! Se você tiver outras dúvidas, entre em contato com {{ContactCustomerService}}", loginGameDownloadAppTipsSwitch: 0, loginGameDownloadAppType: "1,2,3", loginGameFirstCharge: 0, loginGameOnlyAndroidDownloadApp: 1, loginGameOnlyRechargedDownloadApp: 1, loginGameRechargedCountDownloadNativeApp: 99999, loginGameRechargedCountDownloadNativeAppTips: "Parabéns por se tornar um super VIP! Para sua melhor experiência, baixe o APP nativo para que possamos fornecer serviços mais avançados. Se você tiver outras dúvidas, entre em contato com {{ContactCustomerService}}.", loginGameRechargedCountDownloadNativeAppTipsSwitch: 0, returnLobbyDoubleConfirm: 0, returnLobbyDoubleConfirmShowRecharge: 1 }, loginPasswdStrengthDetectSwitch: 0, loginQuickEntryCustomer: 1, loginQuickEntryFree: 1, loginQuickEntryInstant: 1, loginRegister: { cpf: 0, currency: 0, inviteCode: 0, inviteCodeUnchangeable: 0, passwordDefaultPlainText: 0, quickEntry: { customer: 1, trial: 1 }, realName: 0, tabSort: "1,2" }, login_validate_mode: 1, messageSwitch: { ann: { delete: 1, read: 0 }, notify: { delete: 0, read: 1 } }, min_vip_level: 0, modifyPromoteCurrency: 0, musicAutoPlay: 0, musicShow: 1, phoneRegister: { account: 0, confirmDialog: 0, cpf: 0, currency: 0, email: 0, inviteCode: 0, inviteCodeUnchangeable: 0, loginVerify: 0, loginVerifyDefaultHint: 0, password: 0, quickEntry: { quickEntryCustomer: 1, quickEntryForgetPassword: 1, quickEntryFree: 1 }, realName: 0, registerRequiredCaptcha: 0, split: 1 }, questionListDisplay: 0, realNameConsecutiveCharLimitTimes: 0, realNameMustUppercase: 0, realNameRequired: 0, realNameSwitch: 0, recommendAreaCode: "", recommendCurrency: "", recommendLanguage: "en", registerCpfRequired: 0, registerCpfSwitch: 0, registerQuickEntryCustomer: 1, registerQuickEntryFree: 1, registerQuickEntryInstant: 1, registerTabSort: "1,2", registerTimeSwitch: 0, register_phone_switch: 0, register_validate_mode: 1, registermode: 3, resetPwdOneVerify: 0, resversion: "v1.0.0.1", securityVerify: { firstWithdrawPasswdSet: 0, securityEmail: 0, securityGoogle: 1, securityLoginPass: 1, securityPassQuestion: 1, securityPhone: 0, securityWithdrawPass: 0 }, serverversion: "v1.0.0.1", site_status: 0, strongPasswdLength: 8, strongPasswdRequireLowercase: 1, strongPasswdRequireNum: 1, strongPasswdRequireSpecial: 0, strongPasswdRequireUppercase: 1, strongPasswdWeakLoginRemind: 0, token: "b2e3d672-9d88-47a7-81b4-9d7ffc62054f", tryCurrency: "CNY", userEmailSwitch: 1, userGestureSwitch: 1, userLineSwitch: 1, userPhoneSwitch: 1, userRegisterTimeSwitch: 0, userTwitterSwitch: 1, userZaloSwitch: 0, user_facebook_switch: 1, user_telegram_switch: 1, user_wechat_switch: 0, user_whatsapp_switch: 1 }, messageBannerIndex: [{ content: "", endTime: 2095901999, hidden: 1, id: 9, img_category: 1, img_color: "", img_format: 3, img_icon: "", img_style: 3, img_type: 0, img_url: "", location_jump_window: 2, location_name: "活动", location_template: "15", location_type: 3, location_value: "4", publicityType: 5, publicity_name: "1", startTime: 1744772400, stay_time: 15, strokeColor: "", textStroke: 0 }, { content: "", endTime: 2095901999, hidden: 1, id: 7, img_category: 1, img_color: "", img_format: 3, img_icon: "", img_style: 3, img_type: 0, img_url: "", location_jump_window: 1, location_name: "外部链接", location_template: "", location_type: 2, location_value: "https://www.instagram.com/grupow1.oficial", publicityType: 5, publicity_name: "1", startTime: 1744772400, stay_time: 10, strokeColor: "", textStroke: 0 }, { content: "", endTime: 2095901999, hidden: 1, id: 5, img_category: 1, img_color: "", img_format: 3, img_icon: "", img_style: 3, img_type: 0, img_url: "", location_jump_window: 2, location_name: "充值", location_template: "", location_type: 5, location_value: "", publicityType: 5, publicity_name: "1", startTime: 1744772400, stay_time: 10, strokeColor: "", textStroke: 0 }, { content: "", endTime: 2095901999, hidden: 1, id: 3, img_category: 1, img_color: "", img_format: 3, img_icon: "", img_style: 3, img_type: 0, img_url: "", location_jump_window: 1, location_name: "外部链接", location_template: "", location_type: 2, location_value: "https://t.me/W1PG_Grupo212", publicityType: 5, publicity_name: "1", startTime: 1744772400, stay_time: 10, strokeColor: "", textStroke: 0 }, { content: "", endTime: 2095901999, hidden: 1, id: 1, img_category: 1, img_color: "", img_format: 3, img_icon: "", img_style: 3, img_type: 0, img_url: "", location_jump_window: 2, location_name: "活动", location_template: "3", location_type: 3, location_value: "2", publicityType: 5, publicity_name: "1", startTime: 1744772400, stay_time: 10, strokeColor: "", textStroke: 0 }] }, code: 1 } }, apiGetSiteInfo: { data: { data: { agentId: 0, agentName: "无限级差", backgroundColor: 0, clubType: 2, currencyCode: "BRL", currencyId: 11, currencyIds: "11", currencyInfos: [{ ci: "https://YjJuc3l2LTc5MjAtcHBw@4579cfb0-c72e-4aaf-87e2-b643f42e87d2.czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@4579cfb0-c72e-4aaf-87e2-b643f42e87d2/siteadmin/upload/img/BRL.png", currencyAisle: "", currencyCode: "BRL", currencyDisplay: "BRL", currencyName: "巴西雷亚尔", currencySign: "R$", currencyType: 1, fullName: "Brazilian Real", gameRate: 1, hs: 1, id: 11, marketCurrencyCode: "BRL", nation: "巴西", thousandthPlace: "." }], currencySign: "R$", deployEnv: "northAmerica", "dec2NyaXB0@4579cfb0-c72e-4aaf-87e2-b643f42e87d2ion": "", gameRate: 1, goBizMaintainStatus: 1, goClubMaintainStatus: 1, goGameMaintainStatus: 1, keyword: "", languageId: 8, languageIds: "1,8", languageInfos: [{ defaultLanguageTag: !1, id: 1, languageCode: "en", languageFlagIcon: "icon_flag_en.png", languageName: "英文", languageTranslateName: "English" }, { defaultLanguageTag: !0, id: 8, languageCode: "pt", languageFlagIcon: "icon_flag_pt.png", languageName: "葡萄牙语", languageTranslateName: "Português" }], languageMatchMode: 0, limitStatus: 0, maintainStatus: 0, maintainTimeBegin: 174573e4, maintainTimeEnd: 1745762400, parentSiteCode: "7920", siteCode: "7920", siteName: "站158", skinConfigInfo: { ID: "2-22", accent_1: "#04BE02", accent_2: "#EA4E3D", accent_3: "#FFAA09", alt_border: "#D9859A", alt_lead: "#FFFFFF", alt_neutral_1: "#D9859A", alt_neutral_2: "#B95B71", alt_primary: "#E9C86F", alt_text_primary: "#4C0113", bg_1: "#651226", bg_2: "#4C0113", border: "#842239", bs_topnav_bg: "#330215", bs_zc_an1: "#58071B", bs_zc_bg: "#4C0113", btmnav_active: "#E9C86F", btmnav_def: "#B95B71", ddt_bg: "#5A071B", ddt_icon: "#701E31", filter_active: "#E9C86F", filter_bg: "#651226", home_bg: "#4C0113", icon_1: "#E9C86F", icon_tg_q: "#D9859A", icon_tg_z: "#D9859A", jackpot_text: "#FFFFFF", jdd_vip_bjc: "#FFAA09", kb_bg: "#842239", label_accent3: "#FFAA09", labeltext_accent3: "#FFFFFF", lead: "#FFFFFF", leftnav_active: "#4C0113", leftnav_def: "#D9859A", neutral_1: "#D9859A", neutral_2: "#B95B71", neutral_3: "#B95B71", primary: "#E9C86F", profile_icon_1: "#E9C86F", profile_icon_2: "#E9C86F", profile_icon_3: "#E9C86F", profile_icon_4: "#E9C86F", profile_toptext: "#FFFFFF", search_icon: "#D9859A", table_bg: "#4C0113", text_accent3: "#FFFFFF", text_primary: "#4C0113", web_bs_yj_bg: "#330215", web_bs_zc_an2: "#711028", web_btmnav_db: "#400114", web_filter_gou: "#4C0113", web_left_bg_q: "#FFFFFF0A", web_left_bg_shadow: "#0000001F", web_left_bg_shadow_active: "#0000001F", web_left_bg_z: "#FFFFFF0D", web_load_zz: "#84223966", web_plat_line: "#842239", web_topbg_1: "#E9C86F", web_topbg_3: "#BB993E", "皮肤名称": "Bordeaux红", "皮肤版式": "欧美风" }, skinId: "1697160834305101800", skinTypeValue: 22, skinVersion: "v2", status: 0, timeZone: "UTC -03:00", title: "", type: 2, vestBagJumpConfig: [] }, code: 1 } }, apiGetAllCurrency: { data: { data: [{ ca: "", cc: "CNY", cd: "CNY", cn: "人民币", cs: "￥", ct: 1, fn: "Chinese Yuan", gr: 1, id: 0, mcc: "CNY", nation: "中国", siteRate: 7.25, tp: ",", usdtRate: "7.273366576588896" }, { ca: "", cc: "JPY", cd: "JPY", cn: "日元", cs: "￥", ct: 1, fn: "Japanese Yen", gr: 1, id: 1, mcc: "JPY", nation: "日本", siteRate: 149.93, tp: ",", usdtRate: "155.9846264008598" }, { ca: "", cc: "THB", cd: "THB", cn: "泰铢", cs: "฿", ct: 1, fn: "Thai Baht", gr: 1, id: 2, mcc: "THB", nation: "泰国", siteRate: 34, tp: ",", usdtRate: "36.222455426523695" }, { ca: "", cc: "USD", cd: "USD", cn: "美元", cs: "$", ct: 1, fn: "United States Dollar", gr: 1, id: 3, mcc: "USD", nation: "美国", siteRate: 1, tp: ",", usdtRate: "0.9998854274819239" }, { ca: "USD", cc: "USDT", cd: "USDT", cn: "USDT", cs: "U", ct: 2, fn: "Tether", gr: 1, id: 4, mcc: "USDT", nation: "", siteRate: 1, tp: ",", usdtRate: 1 }, { ca: "", cc: "VND", cd: "VND1000:1", cn: "越南盾", cs: "₫", ct: 1, fn: "Vietnamese Dong", gr: 1e3, id: 5, mcc: "VND", nation: "越南", siteRate: 25568.8, tp: ",", usdtRate: "25352.06459132667" }, { ca: "", cc: "IDR", cd: "IDR1000:1", cn: "印尼盾", cs: "Rp", ct: 1, fn: "Indonesian Rupiah", gr: 1e3, id: 6, mcc: "IDR", nation: "印尼", siteRate: 16658, tp: ",", usdtRate: "16221.357312530296" }, { ca: "", cc: "INR", cd: "INR", cn: "印度卢比", cs: "₹", ct: 1, fn: "Indian Rupee", gr: 1, id: 7, mcc: "INR", nation: "印度", siteRate: 85.46, tp: ",", usdtRate: "83.69750246754107" }, { ca: "", cc: "GBP", cd: "GBP", cn: "英镑", cs: "£", ct: 1, fn: "Pound Sterling", gr: 1, id: 8, mcc: "GBP", nation: "英国", siteRate: .77, tp: ",", usdtRate: "0.7738613265996372" }, { ca: "", cc: "KRW", cd: "KRW", cn: "韩元", cs: "₩", ct: 1, fn: "South Korean Won", gr: 1, id: 9, mcc: "KRW", nation: "韩国", siteRate: 1472.95, tp: ",", usdtRate: "1386.2142327525946" }, { ca: "", cc: "AUD", cd: "AUD", cn: "澳元", cs: "A$", ct: 1, fn: "Australian Dollar", gr: 1, id: 10, mcc: "AUD", nation: "澳大利亚", siteRate: 1.6, tp: ",", usdtRate: "1.5093840462533348" }, { ca: "", cc: "BRL", cd: "BRL", cn: "巴西雷亚尔", cs: "R$", ct: 1, fn: "Brazilian Real", gr: 1, id: 11, mcc: "BRL", nation: "巴西", siteRate: 5.69, tp: ".", usdtRate: "5.570361716501795" }, { ca: "", cc: "MXN", cd: "MXN", cn: "墨西哥比索", cs: "$", ct: 1, fn: "Mexican Peso", gr: 1, id: 12, mcc: "MXN", nation: "墨西哥", siteRate: 20.47, tp: ",", usdtRate: "17.94534372119028" }, { ca: "", cc: "EUR", cd: "EUR", cn: "欧元", cs: "€", ct: 1, fn: "Euro", gr: 1, id: 13, mcc: "EUR", nation: "", siteRate: .92, tp: ",", usdtRate: "0.9195656309777305" }, { ca: "", cc: "RUB", cd: "RUB", cn: "俄罗斯卢布", cs: "₽", ct: 1, fn: "Russian Ruble", gr: 1, id: 14, mcc: "RUB", nation: "俄罗斯", siteRate: 84.14, tp: ",", usdtRate: "87.41334968483304" }, { ca: "", cc: "MMK", cd: "MMK", cn: "缅甸元", cs: "K", ct: 1, fn: "Myanma Kyat", gr: 1, id: 15, mcc: "MMK", nation: "缅甸", siteRate: 4370, tp: ",", usdtRate: "2097.7596268581674" }, { ca: "", cc: "IDRK", cd: "IDRK", cn: "印尼盾", cs: "Rp", ct: 1, fn: "Indonesian Rupiah", gr: 1, id: 16, mcc: "IDR", nation: "印尼", siteRate: 0, tp: ",", usdtRate: "16221.357312530296" }, { ca: "", cc: "AED", cd: "AED", cn: "迪拉姆", cs: "د.إ", ct: 1, fn: "United Arab Emirates Dirham", gr: 1, id: 17, mcc: "AED", nation: "阿联酋", siteRate: 3.67, tp: ",", usdtRate: "3.6725641768597157" }, { ca: "", cc: "HKD", cd: "HKD", cn: "港币", cs: "$", ct: 1, fn: "Hong Kong Dollar", gr: 1, id: 18, mcc: "HKD", nation: "香港", siteRate: 7.77, tp: ",", usdtRate: "7.806855446421884" }, { ca: "", cc: "PHP", cd: "PHP", cn: "菲律宾比索", cs: "₱", ct: 1, fn: "Philippine Peso", gr: 1, id: 19, mcc: "PHP", nation: "菲律宾", siteRate: 57.24, tp: ",", usdtRate: "58.46180511627659" }, { ca: "CNY", cc: "BTC1", cd: "BTC1:100000", cn: "比特币", cs: "₿", ct: 2, fn: "Bitcoin", gr: 1e-5, id: 20, mcc: "BTC", nation: "", siteRate: 7.25, tp: ",", usdtRate: "0.00001494692097507063" }, { ca: "CNY", cc: "ETH1", cd: "ETH1:10000", cn: "以太坊", cs: "E", ct: 2, fn: "Ethereum", gr: 1e-4, id: 21, mcc: "ETH", nation: "", siteRate: 7.25, tp: ",", usdtRate: "0.0002836174081801079" }, { ca: "CNY", cc: "USDT1", cd: "USDT1:7", cn: "USDT", cs: "U", ct: 2, fn: "Tether", gr: "0.1428571428571429", id: 22, mcc: "USDT", nation: "", siteRate: 7.25, tp: ",", usdtRate: 1 }, { ca: "CNY", cc: "USDC1", cd: "USDC1:7", cn: "USDC", cs: "U", ct: 2, fn: "USD Coin", gr: "0.1428571428571429", id: 23, mcc: "USDC", nation: "", siteRate: 7.25, tp: ",", usdtRate: "0.9999875561465108" }, { ca: "USD", cc: "USDC", cd: "USDC", cn: "USDC", cs: "U", ct: 2, fn: "USD Coin", gr: 1, id: 24, mcc: "USDC", nation: "", siteRate: 1, tp: ",", usdtRate: "0.9999875561465108" }, { ca: "USD", cc: "BTC", cd: "BTC1:10000", cn: "比特币", cs: "₿", ct: 2, fn: "Bitcoin", gr: 1e-4, id: 25, mcc: "BTC", nation: "", siteRate: 1, tp: ",", usdtRate: "0.00001494692097507063" }, { ca: "USD", cc: "ETH", cd: "ETH1:1000", cn: "以太坊", cs: "E", ct: 2, fn: "Ethereum", gr: .001, id: 26, mcc: "ETH", nation: "", siteRate: 1, tp: ",", usdtRate: "0.0002836174081801079" }, { ca: "", cc: "KHR", cd: "KHR100:1", cn: "柬埔寨瑞尔", cs: "៛", ct: 1, fn: "Cambodian Riel", gr: 100, id: 27, mcc: "KHR", nation: "柬埔寨", siteRate: 3970.21, tp: ",", usdtRate: "4107.789546302077" }, { ca: "", cc: "BDT", cd: "BDT", cn: "塔卡", cs: "৳", ct: 1, fn: "Bangladeshi Taka", gr: 1, id: 28, mcc: "BDT", nation: "孟加拉", siteRate: 120.89, tp: ",", usdtRate: "117.47966551658881" }, { ca: "USD", cc: "TRX", cd: "TRX", cn: "波场", cs: "TRX", ct: 2, fn: "TRON", gr: 1, id: 29, mcc: "TRX", nation: "", siteRate: 4.19, tp: ",", usdtRate: "7.564092724424874" }, { ca: "", cc: "KES", cd: "KES", cn: "肯尼亚先令", cs: "KSh", ct: 1, fn: "Kenya shilling", gr: 1, id: 30, mcc: "KES", nation: "肯尼亚", siteRate: 129.35, tp: ",", usdtRate: "131.98487642757172" }, { ca: "", cc: "UGX", cd: "UGX", cn: "乌干达先令", cs: "USh", ct: 1, fn: "Ugandan Shilling", gr: 1, id: 31, mcc: "UGX", nation: "乌干达", siteRate: 3654.37, tp: ",", usdtRate: "3694.71090116209" }, { ca: "", cc: "TZS", cd: "TZS", cn: "坦桑尼亚先令", cs: "TZS", ct: 1, fn: "Tanzanian Shilling", gr: 1, id: 32, mcc: "TZS", nation: "坦桑尼亚", siteRate: 2648.27, tp: ",", usdtRate: 2719.4 }, { ca: "", cc: "NGN", cd: "NGN", cn: "尼日利亚奈拉", cs: "₦", ct: 1, fn: "Nigerian Naira", gr: 1, id: 33, mcc: "NGN", nation: "尼日利亚", siteRate: 1534.46, tp: ",", usdtRate: "1576.8193191500318" }, { ca: "", cc: "TRY", cd: "TRY", cn: "土耳其里拉", cs: "₺", ct: 1, fn: "Turkish Lira", gr: 1, id: 34, mcc: "TRY", nation: "土耳其", siteRate: 37.95, tp: ",", usdtRate: "32.937426843653085" }, { ca: "", cc: "PKR", cd: "PKR", cn: "巴基斯坦卢比", cs: "₨", ct: 1, fn: "Pakistani Rupee", gr: 1, id: 36, mcc: "PKR", nation: "巴基斯坦", siteRate: 279.18, tp: ",", usdtRate: 290.35 }, { ca: "", cc: "PYG", cd: "PYG1000:1", cn: "巴拉圭瓜拉尼", cs: "₲", ct: 1, fn: "Paraguayan Guarani", gr: 1e3, id: 37, mcc: "PYG", nation: "巴拉圭", siteRate: 7936.6, tp: ".", usdtRate: 7708 }, { ca: "", cc: "PEN", cd: "PEN", cn: "秘鲁新索尔", cs: "S/.", ct: 1, fn: "Peruvian Sol", gr: 1, id: 38, mcc: "PEN", nation: "秘鲁", siteRate: 3.67, tp: ",", usdtRate: 3.71 }, { ca: "", cc: "ARS", cd: "ARS", cn: "阿根廷比索", cs: "$", ct: 1, fn: "Argentine Peso", gr: 1, id: 39, mcc: "ARS", nation: "阿根廷", siteRate: 1073.13, tp: ".", usdtRate: 1370.45 }, { ca: "", cc: "EGP", cd: "EGP", cn: "埃及镑", cs: "E£", ct: 1, fn: "Egyptian Pound", gr: 1, id: 40, mcc: "EGP", nation: "埃及", siteRate: 50.57, tp: ",", usdtRate: 51.94 }, { ca: "", cc: "ZMW", cd: "ZMW", cn: "赞比亚克瓦查", cs: "ZK", ct: 1, fn: "", gr: 1, id: 41, mcc: "ZMW", nation: "赞比亚", siteRate: 28.16, tp: ",", usdtRate: 27.32 }, { ca: "", cc: "ZAR", cd: "ZAR", cn: "南非兰特", cs: "R", ct: 1, fn: "", gr: 1, id: 42, mcc: "ZAR", nation: "南非", siteRate: 18.31, tp: ",", usdtRate: 19.86 }, { ca: "", cc: "COP", cd: "COP", cn: "哥伦比亚比索", cs: "$", ct: 1, fn: "", gr: 1, id: 43, mcc: "COP", nation: "哥伦比亚", siteRate: 4179.27, tp: ",", usdtRate: 4262.66 }], code: 1 } }, apiGetGameCategorieList: { data: { code: 1, msg: "success", time: 1745936902, data: [{ l: [], p0: 0, p1: "Popular", p2: 1, p3: 2, p4: 3, p5: 2, p6: 5, p7: 10, p8: 10 }, { l: [{ t1: null, t10: "PG", t11: "", t12: 0, t13: 0, t14: 0, t15: "PG Slots", t16: 1, t17: "200/custom", t18: 0, t19: 0, t2: 0, t20: null, t21: null, t3: 1, t4: 3, t5: "0/200_N_PG_LOGO.png", t6: 0, t7: 1, t8: "200/custom", t9: 200 }, { t1: null, t10: "WG", t11: "", t12: 0, t13: 0, t14: 0, t15: "WG Slots", t16: 1, t17: null, t18: 0, t19: 1, t2: 1, t20: 0, t21: "", t3: 0, t4: 3, t5: "0/13_N_WG_LOGO.png?t=1681975574", t6: 0, t7: 1, t8: "", t9: 13 }, { t1: null, t10: "JDB", t11: "", t12: 1, t13: 1, t14: 1, t15: "JDB Slots", t16: 1, t17: null, t18: 0, t19: 1, t2: 1, t20: 0, t21: "", t3: 0, t4: 3, t5: "0/310_N_JDB_LOGO.png", t6: 0, t7: 1, t8: "", t9: 310 }, { t1: null, t10: "PP", t11: "", t12: 0, t13: 0, t14: 0, t15: "PP Slots", t16: 1, t17: null, t18: null, t19: 1, t2: 1, t20: null, t21: null, t3: null, t4: 3, t5: "0/37_N_PP_LOGO.png", t6: 0, t7: 1, t8: null, t9: 301 }, { t1: null, t10: "TADA", t11: "", t12: 1, t13: 1, t14: 1, t15: "TADA Slots", t16: 1, t17: null, t18: 0, t19: 1, t2: 0, t20: 0, t21: "", t3: 0, t4: 3, t5: "0/302_N_TADA_LOGO.png", t6: 0, t7: 1, t8: "", t9: 302 }, { t1: null, t10: "CQ9", t11: "", t12: 0, t13: 0, t14: 0, t15: "CQ9 Slots", t16: 0, t17: null, t18: null, t19: 1, t2: 0, t20: null, t21: null, t3: null, t4: 3, t5: "0/3_N_CQ9_LOGO.png", t6: 0, t7: 1, t8: null, t9: 316 }, { t1: null, t10: "Joker", t11: "", t12: 1, t13: 1, t14: 1, t15: "Joker Slots", t16: 0, t17: null, t18: 0, t19: 1, t2: 0, t20: 0, t21: "", t3: 0, t4: 3, t5: "0/97_N_Joker_LOGO.png", t6: 0, t7: 1, t8: "", t9: 97 }, { t1: null, t10: "FC", t11: "", t12: 1, t13: 1, t14: 1, t15: "FC Slots", t16: 1, t17: null, t18: 0, t19: 1, t2: 0, t20: 0, t21: "", t3: 0, t4: 3, t5: "0/24_N_FC_LOGO.png", t6: 0, t7: 1, t8: "", t9: 203 }, { t1: null, t10: "Dragoon Soft", t11: "", t12: 1, t13: 1, t14: 1, t15: "Dragoon Soft Slots", t16: 0, t17: null, t18: 0, t19: 1, t2: 0, t20: 0, t21: "", t3: 0, t4: 3, t5: "0/118_N_DS_LOGO.png", t6: 0, t7: 1, t8: "", t9: 118 }, { t1: null, t10: "Redtiger", t11: "", t12: 1, t13: 1, t14: 1, t15: "Redtiger Slots", t16: 0, t17: null, t18: 0, t19: 1, t2: 0, t20: 0, t21: "", t3: 0, t4: 3, t5: "0/32_N_RT_LOGO.png", t6: 0, t7: 1, t8: "", t9: 32 }], p0: 3, p1: "Slots", p2: 1, p3: 1, p4: 5, p5: 1, p6: 5, p7: 10, p8: 10 }, { l: [{ t1: null, t10: "WG", t11: "", t12: 0, t13: 0, t14: 0, t15: "WG Blockchain", t16: 1, t17: null, t18: 0, t19: 1, t2: 1, t20: 0, t21: "", t3: 0, t4: 11, t5: "0/13_N_WG_LOGO.png?t=1681975574", t6: 0, t7: 1, t8: "", t9: 13 }, { t1: null, t10: "JDB", t11: "", t12: 1, t13: 1, t14: 1, t15: "JDB Blockchain", t16: 0, t17: null, t18: 0, t19: 1, t2: 0, t20: 0, t21: "", t3: 0, t4: 11, t5: "0/310_N_JDB_LOGO.png", t6: 0, t7: 1, t8: "", t9: 310 }], p0: 11, p1: "Blockchain", p2: 1, p3: 1, p4: 5, p5: 1, p6: 5, p7: 10, p8: 10 }, { l: [], p0: 1, p1: "Cartas", p2: 0, p3: 1, p4: 5, p5: 1, p6: 2, p7: 10, p8: 10 }, { l: [], p0: 2, p1: "Pescaria", p2: 0, p3: 1, p4: 5, p5: 1, p6: 2, p7: 10, p8: 10 }, { l: [{ t1: 520093, t10: "WL", t11: "", t12: 0, t13: 0, t14: 0, t15: "WL Jogo Ao Vivo", t16: 0, t17: null, t18: 0, t19: 1, t2: 0, t20: 0, t21: "", t3: 0, t4: 4, t5: "0/52_N_WL_LOGO.png", t6: 0, t7: 0, t8: "", t9: 52 }, { t1: 318e4, t10: "W", t11: "", t12: 1, t13: 1, t14: 1, t15: "W Jogo Ao Vivo", t16: 0, t17: null, t18: null, t19: 1, t2: 0, t20: null, t21: null, t3: null, t4: 4, t5: "0/318_N_W_LOGO.png", t6: 0, t7: 0, t8: null, t9: 318 }], p0: 4, p1: "Ao Vivo", p2: 0, p3: 1, p4: 5, p5: 1, p6: 2, p7: 10, p8: 10 }, { l: [], p0: 5, p1: "Esporte", p2: 0, p3: 1, p4: 5, p5: 1, p6: 2, p7: 10, p8: 10 }, { l: [], p0: 6, p1: "Brigas Galos", p2: 0, p3: 1, p4: 5, p5: 1, p6: 2, p7: 10, p8: 10 }, { l: [], p0: 7, p1: "Esports", p2: 0, p3: 1, p4: 5, p5: 1, p6: 2, p7: 10, p8: 10 }, { l: [], p0: 8, p1: "Loteria", p2: 0, p3: 1, p4: 5, p5: 1, p6: 2, p7: 10, p8: 10 }, { l: [{ t1: null, t10: "WG", t11: "", t12: 0, t13: 0, t14: 0, t15: "WG Slots", t16: 1, t17: null, t18: 0, t19: 1, t2: 1, t20: 0, t21: "", t3: 0, t4: 3, t5: "0/13_N_WG_LOGO.png?t=1681975574", t6: 0, t7: 1, t8: "", t9: 13 }, { t1: null, t10: "JDB", t11: "", t12: 1, t13: 1, t14: 1, t15: "JDB Slots", t16: 1, t17: null, t18: 0, t19: 1, t2: 1, t20: 0, t21: "", t3: 0, t4: 3, t5: "0/310_N_JDB_LOGO.png", t6: 0, t7: 1, t8: "", t9: 310 }, { t1: null, t10: "PP", t11: "", t12: 0, t13: 0, t14: 0, t15: "PP Slots", t16: 1, t17: null, t18: null, t19: 1, t2: 1, t20: null, t21: null, t3: null, t4: 3, t5: "0/37_N_PP_LOGO.png", t6: 0, t7: 1, t8: null, t9: 301 }, { t1: null, t10: "WG", t11: "", t12: 0, t13: 0, t14: 0, t15: "WG Blockchain", t16: 1, t17: null, t18: 0, t19: 1, t2: 1, t20: 0, t21: "", t3: 0, t4: 11, t5: "0/13_N_WG_LOGO.png?t=1681975574", t6: 0, t7: 1, t8: "", t9: 13 }], p0: 20, p1: "Jogar Grátis", p2: 0, p3: 1, p4: 5, p5: 1, p6: 2, p7: 10, p8: 10 }, { l: [], p0: 12, p1: "Mesa de quarto", p2: 0, p3: 1, p4: 5, p5: 1, p6: 2, p7: 10, p8: 10 }] } }, ossGetGameCategorieExtLink: { data: { WITH_OSS: !0, AFTER_SERVER: !1, headers: { "x-amz-id-2": "HXpVBWvAZeBLA7tHvZIFOE3MjhW9ekpjcGXt/DpmvZ+m4BuZ2n/9O9L4lN7uVufHWaDxtA9KfwWSK8oXVBABVzK2mnucDXuS", "x-amz-request-id": "843KQPQMGZJ4ERQK", date: "Tue, 29 Apr 2025 14:28:23 GMT", "last-modified": "Sat, 19 Apr 2025 02:54:19 GMT", etag: '"9758d61fd487da1fdeb216231a21462a"', "x-amz-server-side-encryption": "AES256", "cache-control": "s-maxage=600,public,max-age=0", "accept-ranges": "bytes", "content-type": "application/json", "content-length": "92", server: "AmazonS3", connection: "close" }, code: 1, data: [] } }, apiGetHotGameList: { data: { code: 1, msg: "success", time: 1745936902, data: [{ g0: null, g1: "PG Slots", g10: 200, g11: 1, g12: 0, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "200/custom", g3: 1, g4: 0, g5: null, g6: 0, g7: null, g8: 1, g9: 3 }, { g0: 2001007, g1: "Fortune Rabbit", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 1, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000098, g1: "Fortune Ox", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 1, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000126, g1: "Fortune Tiger", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 1, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2001046, g1: "Fortune Snake", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "200/200_2001046_EA.png", g3: 0, g4: 0, g5: 1, g6: 0, g7: 1, g8: 0, g9: 3 }, { g0: 2000068, g1: "Fortune Mouse", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 1, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2001027, g1: "Fortune Dragon", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 1, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000057, g1: "Dragon Hatch", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 1, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 3010506, g1: "", g10: 301, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 1, g6: 1, g7: 1, g8: 1, g9: 3 }, { g0: 2001029, g1: "Cash Mania", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 1, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2001030, g1: "Wild Ape", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 1, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2001049, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "200/200_2001049_EA.png", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 0, g9: 3 }, { g0: 2001048, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 0, g9: 3 }, { g0: 2001047, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "200/200_2001047_EA.png", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 0, g9: 3 }, { g0: 3020109, g1: "", g10: 302, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 2, g8: 1, g9: 3 }, { g0: 2000042, g1: "Ganesha Gold", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2001026, g1: "Dragon Hatch 2", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 3020051, g1: "", g10: 302, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 2, g8: 1, g9: 3 }, { g0: 3020035, g1: "", g10: 302, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 2, g8: 1, g9: 3 }, { g0: 3004, g1: "", g10: 13, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 1, g7: 1, g8: 1, g9: 3 }, { g0: 2000083, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000082, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000080, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000079, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000075, g1: "Ganesha Fortune", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000074, g1: "Mahjong Ways 2", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000073, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000071, g1: "Caishen Wins", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000070, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000069, g1: "Bikini Paradise", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000067, g1: "Shaolin Soccer", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000065, g1: "Mahjong Ways", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000064, g1: "Muay Thai Champion", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000063, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000062, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000061, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000060, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000059, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000058, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000054, g1: "Captain's Bounty", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000053, g1: "The Great Icescape", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000050, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000048, g1: "Double Fortune", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000044, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000041, g1: "Symbols of Egypt", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000040, g1: "Jungle Delight", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000039, g1: "Piggy Gold", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000038, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000037, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000036, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000035, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000034, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000033, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000029, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000028, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000026, g1: "Tree of Fortune", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000025, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000024, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000020, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000018, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000007, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000006, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000003, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000002, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000001, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000128, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000127, g1: "Speed Winner", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000125, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000124, g1: "Battleground Royale", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000123, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000122, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000121, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000120, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000119, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000118, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000117, g1: "Cocktail Nights", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000115, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000114, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000113, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000112, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000110, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000108, g1: "Buffalo Win", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000107, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000106, g1: "Ways of the Qilin", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000105, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000104, g1: "Wild Bandito", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000103, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000102, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000101, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000100, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000097, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000095, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000094, g1: "Bali Vacation", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000093, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000092, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000091, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000090, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000089, g1: "Lucky Neko", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000088, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }, { g0: 2000087, g1: "", g10: 200, g11: 2, g12: null, g13: null, g14: null, g15: null, g16: null, g17: null, g2: "", g3: 0, g4: 0, g5: 0, g6: 0, g7: 1, g8: 1, g9: 3 }] } }, apiGetMessageAll: { data: { code: 1, msg: "返回成功", time: 1745936902, data: { marqueeList: [{ content: "<p>✈️✈️ Clique no canal: Telegram<br> 📱📱Baixe o site oficial do APP: w1-carvalhopg.com<br> ❤️Bônus de primeiro depósito para novos usuários R$3777❤️<br> 🎁B6nus de convite: R$ 10 por pessoa🎁<br> 🎁Convide amigos, compartilhe e ganhe dinheiro! Comissão até 2%🎁Tempo de chuva do envelope: AS00H 15H 20H E22H🎁<br> 👉👉Passo a passo: Visite → Eventos/Promoções → Indique um amigo → Receba/Recolha tudo.</p>", create_time: 1745848800, id: 153445, interval: 2 }], noticeCount: 2, noticeFrameMsgList: [{ content: '<p style="box-sizing: inherit; color: #666666; font-family: \'Helvetica Neue\', Helvetica, \'PingFang SC\', \'Hiragino Sans GB\', \'Microsoft YaHei\', Arial, sans-serif; margin: 5px 0px; text-align: justify;"> <span style="font-size: 16px;"><span style="box-sizing: inherit; font-weight: bolder;"><span style="box-sizing: inherit; color: #000000;"><span style="box-sizing: inherit;">✈️❤️</span> <span style="box-sizing: inherit;">PG Slots - Parceiro estratégico oficial</span> <span style="box-sizing: inherit;">❤️🎈</span></span></span> <span style="box-sizing: inherit;"><span style="box-sizing: inherit; font-weight: bolder;"><span style="box-sizing: inherit; color: #ff0000;"><br>🚨Depósito mínimo 10BRL✨</span></span></span></span><br> <span style="box-sizing: inherit; font-size: 16px;"><span style="box-sizing: inherit; font-weight: bolder;"><span style="box-sizing: inherit; color: #ff0000;">🚨Saque mínimo 10BRL✨</span></span></span><br> <span style="font-size: 16px;"><span style="box-sizing: inherit; font-weight: bolder;"><span style="box-sizing: inherit; color: #000000;"><span style="box-sizing: inherit;">🎈🎄</span> <span style="box-sizing: inherit;">Obrigado por escolher o</span></span></span> <span style="box-sizing: inherit;">&nbsp;</span> <span style="box-sizing: inherit; color: #0000ff;"><span style="box-sizing: inherit; font-weight: bolder;"><span style="box-sizing: inherit;"><a style="box-sizing: inherit; background-color: transparent; color: #0000ff; cursor: pointer; outline: none; text-decoration-line: none;" href="https://t.me/W1PG_Grupo2" target="_blank">GRUPO W1PG</a></span></span></span> <span style="box-sizing: inherit;">&nbsp;</span> <span style="box-sizing: inherit; font-weight: bolder;"><span style="box-sizing: inherit; color: #000000;"><span style="box-sizing: inherit;"><span style="box-sizing: inherit;">🍬🍬🎉</span></span></span></span></span><br> <span style="font-size: 16px;"><span style="box-sizing: inherit; font-weight: bolder;"><span style="box-sizing: inherit; color: #000000;"><span style="box-sizing: inherit;">👍</span> <span style="box-sizing: inherit;">Uma plataforma sem limitações</span></span></span> <span style="box-sizing: inherit;"><span style="box-sizing: inherit; font-weight: bolder;"><span style="box-sizing: inherit; color: #000000;">📷<br></span></span></span> <span style="box-sizing: inherit; font-weight: bolder;"><span style="box-sizing: inherit; color: #ff6600;"><span style="box-sizing: inherit; color: #000000;">✈️✈️ Agências de recrutamento em todo o Brasil entre em contato com seu</span> <span style="box-sizing: inherit;"><span style="box-sizing: inherit; color: #000000;">gerente : <span style="box-sizing: inherit; color: #0000ff;"><strong><a style="box-sizing: inherit; background-color: transparent; color: #0000ff; cursor: pointer; outline: none; text-decoration-line: none;" href="https://t.me/W1PG_Grupo2" target="_blank">Telegram</a></strong><br></span></span></span></span></span> <span style="box-sizing: inherit; font-weight: bolder;"><span style="box-sizing: inherit; color: #ff6600;"><span style="box-sizing: inherit; color: #ff0000;">🎁 <span style="box-sizing: inherit; color: #000000;">B6nus de convite: R$ 10 por pessoa<br></span></span></span></span> <span style="box-sizing: inherit; font-weight: bolder;">📱📱📱 <span style="box-sizing: inherit; color: #000000;">Nosso Instagram oficial também lança regularmente uma série de eventos sociais, sorteios, festas, etc.</span><br> 📱 <span style="box-sizing: inherit; color: #000000;">Obrigado a todos pelo apoio e amor</span><br> <span style="box-sizing: inherit; color: #000000;">📷 <span style="box-sizing: inherit;"><a style="box-sizing: inherit; background-color: transparent; color: #000000; cursor: pointer; outline: none; text-decoration-line: none;" href>Instagram:</a> <span style="box-sizing: inherit; color: #99cc00;"><a style="box-sizing: inherit; background-color: transparent; color: #99cc00; cursor: pointer; outline: none; text-decoration-line: none;" href="https://www.instagram.com/w1grupo/" target="_blank">Instagram</a></span></span></span></span></span></p>', create_time: 1745848800, cycleTime: 0, endTime: 0, feedbackId: 0, frameEndTime: 2095901999, frameStartTime: 1744772400, frameSwitch: 1, frameType: 1, id: 153443, interval: 0, isPublish: 1, isPublishFrame: 1, list_title: "", prize: 0, prize_status: 0, publishTime: 1745848800, read_status: 0, sendTime: 1745848800, startTime: 1745848800, time_stamp: 0, title: "<p>👍Uma plataforma sem limita&ccedil;&otilde;es📷&nbsp;</p>", title_origin: "👍Uma plataforma sem limitações📷 ", triggerId: 0, triggerType: 0 }], publicityMsgList: [{ active_info: [{ ActiveId: 4, ActiveName: "Recomende amigos para abrir o Baú dos Prêmios" }], content: "", frameEndTime: 2095901999, frameStartTime: 1744772400, frame_pop: 1, frame_switch: 1, frame_type: 1, hidden: 1, id: 30003, img_category: 1, img_color: "", img_icon: "", img_style: 3, img_url: "https://YjJuc3l2LTc5MjAtcHBw@4579cfb0-c72e-4aaf-87e2-b643f42e87d2.czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@4579cfb0-c72e-4aaf-87e2-b643f42e87d2/siteadmin/upload/img/1912633337107484674.jpg", is_maintenance: !1, location_jump_window: 2, location_name: "活动", location_template: "15", location_type: 3, location_value: "4", maintenance_end: 0, maintenance_start: 0, publicity_name: "Recomende amigos para abrir baús de tesouro", read_status: 0, receiver_type: 1, strokeColor: "", suspend_url: "2", suspended: 2, textStroke: 0 }], unreadNoticeCount: 2 } } }, ossGetVirtualBonusPoolData: { data: { WITH_OSS: !0, AFTER_SERVER: !1, headers: { "x-amz-id-2": "xKqpOZZFt7K3XvIK/uCDwsPdMWjOhgyZTjIZnuoc9R/0KF4YVW20hDaHlJN7kRlJGSygNwlukJVidMmKsirFpVVVxqroak6r9OGLMZ/FC8c=", "x-amz-request-id": "843MN0EWSW78JCPT", date: "Tue, 29 Apr 2025 14:28:23 GMT", "last-modified": "Tue, 29 Apr 2025 14:24:56 GMT", etag: '"3aeeb0dc190f41ce1fe4dcb4bb80fed4"', "x-amz-server-side-encryption": "AES256", "cache-control": "s-maxage=300,public,max-age=0", "accept-ranges": "bytes", "content-type": "application/json", "content-length": "2012", server: "AmazonS3", connection: "close" }, code: 1, data: [{ a: "1912621523379802114", b: 1, c: '{"name":"跑马灯上方","type":10}', d: { name: "PG电子", type: 10, value: "3", value2: "200" }, e: 2, f: 2, g: 0, h: "", j: "", k: "vjp/1912621477158440962.png", m: 1745936695089, n: ["17178249.36", "17178411.33", "17178564.90", "17178772.32", "17178898.43", "17179059.44", "17179273.15", "17179474.69", "17179595.11", "17179745.45", "17179957.25", "17180158.80", "17180278.04", "17180394.64", "17180533.13", "17180677.83", "17180868.04", "17181000.63", "17181146.29", "17181284.31", "17181441.64", "17181583.31", "17181783.02", "17181968.92", "17182095.36", "17182265.71", "17182403.38", "17182589.35", "17182755.45", "17182897.89", "17183049.40", "17183244.31", "17183366.71", "17183483.37", "17183638.32", "17183816.21", "17183940.74", "17184098.30", "17184217.52", "17184431.37", "17184551.62", "17184685.33", "17184871.96", "17185052.41", "17185264.77", "17185406.50", "17185611.07", "17185740.34", "17185879.58", "17186072.23", "17186261.38", "17186409.30", "17186561.68", "17186680.94", "17186851.37", "17186979.45", "17187190.45", "17187311.70", "17187428.45", "17187584.21", "17187785.64", "17187959.52", "17188096.56", "17188302.07", "17188423.75", "17188582.87", "17188716.67", "17188897.22", "17189054.38", "17189203.69", "17189417.09", "17189545.92", "17189676.15", "17189804.85", "17190000.65", "17190152.45", "17190268.79", "17190389.55", "17190515.79", "17190643.31", "17190803.09", "17190968.09", "17191135.61", "17191335.66", "17191486.12", "17191604.20", "17191720.79", "17191894.11", "17192030.10", "17192159.18", "17192339.53", "17192527.26", "17192707.49", "17192851.37", "17192991.48", "17193167.39", "17193314.33", "17193441.91", "17193570.26", "17193783.43", "17193938.29", "17194120.88", "17194304.97", "17194508.33", "17194632.14", "17194787.48", "17195001.32", "17195184.27", "17195360.17", "17195562.72", "17195691.29", "17195899.19", "17196094.97", "17196214.12", "17196363.04", "17196548.33", "17196722.49", "17196871.64", "17197084.50", "17197265.26"], y: "" }] } }, ossGetSkinHomeSvgSprite:{data:{code:1,data:'<svg version="1.1" xmlns="http://www.w3.org/2000/svg" style="display:none;"><symbol id="btn_zcl_arrow" viewBox="0 0 21 14"><g clip-path="url(#clip0_55_1390)"><path d="M1.2 13.213a1.2 1.2 0 1 1 0-2.4h8a1.199 1.199 0 0 1 .848 2.048c-.225.225-.53.351-.848.351h-8zm12.322-.522a1.2 1.2 0 0 1 0-1.7l2.793-2.794H1.2a1.2 1.2 0 1 1 0-2.4h15.115l-2.793-2.794a1.202 1.202 0 1 1 1.7-1.7l4.841 4.84a1.199 1.199 0 0 1 0 1.7l-4.84 4.841a1.2 1.2 0 0 1-1.701 0v.007zM1.2 3.19a1.2 1.2 0 1 1 0-2.4h8a1.199 1.199 0 1 1 0 2.4h-8z"/></g><defs><clipPath id="clip0_55_1390"><path transform="translate(0 .788)" d="M0 0h20.413v12.425H0z"/></clipPath></defs></symbol><symbol id="comm_icon_copy" viewBox="0 0 24 26"><g clip-path="url(#clip0_55_1392)"><path d="M2.206 24.644A1.294 1.294 0 0 1 .913 23.35V6.525a1.292 1.292 0 0 1 1.293-1.293h15.53a1.293 1.293 0 0 1 1.293 1.293V23.35a1.293 1.293 0 0 1-1.293 1.3l-15.53-.006zm.647-1.94h14.234V7.174H2.853v15.528zm18.116-2.264V3.292H8.354a.972.972 0 0 1 0-1.942h13.588a.978.978 0 0 1 .971.978V20.44a.972.972 0 1 1-1.942 0l-.002.001z"/></g><defs><clipPath id="clip0_55_1392"><path transform="translate(.413 .85)" d="M0 0h23.001v24.3H0z"/></clipPath></defs></symbol><symbol id="comm_icon_fh" viewBox="0 0 23 36"><g clip-path="url(#clip0_157_4500)"><path d="M19.012 35.415L1.27 19.642a2.009 2.009 0 0 1 .003-3.288L19.012.584A1.992 1.992 0 0 1 21.83 3.4L5.407 18 21.83 32.6a1.992 1.992 0 0 1-2.817 2.817v-.002z"/></g><defs><clipPath id="clip0_157_4500"><path transform="translate(.414 .001)" d="M0 0h21.999v35.998H0z"/></clipPath></defs></symbol><symbol id="comm_icon_fy_jt" viewBox="0 0 12 12"><g clip-path="url(#clip0_203_2)"><path d="M4.945 9.663L7.61 6.998H.41v-2h7.2l-2.664-2.66L6.36.922l5.08 5.075-5.08 5.08-1.414-1.415z"/></g><defs><clipPath id="clip0_203_2"><path transform="translate(.413 .923)" d="M0 0h11.024v10.154H0z"/></clipPath></defs></symbol><symbol id="comm_icon_jzgd 1" viewBox="0 0 13 12"><g clip-path="url(#clip0_341_1067)"><path d="M6.437 12a.535.535 0 0 1-.389-.14L.576 6.208a.5.5 0 0 1 .15-.8.456.456 0 0 1 .52.109l5.19 5.34 5.183-5.34a.447.447 0 0 1 .515-.11c.058.026.11.063.153.11a.488.488 0 0 1 .145.346.488.488 0 0 1-.145.345l-5.467 5.65a.553.553 0 0 1-.356.141h-.027zm0-5.373a.476.476 0 0 1-.389-.15L.576.836A.5.5 0 0 1 .43.486.5.5 0 0 1 .728.03a.46.46 0 0 1 .518.108l5.19 5.34 5.183-5.34a.463.463 0 0 1 .334-.145.459.459 0 0 1 .334.145.49.49 0 0 1 .107.539.49.49 0 0 1-.107.16L6.82 6.476a.493.493 0 0 1-.344.153l-.039-.002z"/></g><defs><clipPath id="clip0_341_1067"><path transform="translate(.437 -.004)" d="M0 0h11.996v12.008H0z"/></clipPath></defs></symbol><symbol id="comm_icon_jzgd" viewBox="0 0 13 12"><g clip-path="url(#clip0_341_1070)"><path d="M6.702 12a.535.535 0 0 1-.389-.14L.841 6.208a.5.5 0 0 1 .15-.8.456.456 0 0 1 .52.109l5.189 5.34 5.184-5.34a.448.448 0 0 1 .668 0 .486.486 0 0 1 .144.346.486.486 0 0 1-.144.345l-5.467 5.65a.553.553 0 0 1-.356.141h-.027zm0-5.373a.476.476 0 0 1-.389-.15L.841.836a.5.5 0 0 1-.145-.35A.5.5 0 0 1 .993.03a.46.46 0 0 1 .518.108L6.7 5.477l5.184-5.34a.464.464 0 0 1 .334-.145.459.459 0 0 1 .334.145.49.49 0 0 1 .107.539.49.49 0 0 1-.107.16l-5.467 5.64a.493.493 0 0 1-.344.153l-.039-.002z"/></g><defs><clipPath id="clip0_341_1070"><path transform="translate(.702 -.004)" d="M0 0h11.996v12.008H0z"/></clipPath></defs></symbol><symbol id="comm_icon_sort" viewBox="0 0 13 8"><g clip-path="url(#clip0_150_4355)"><path d="M12.148.764A1 1 0 0 0 11.414.5L1.441.566A1 1 0 0 0 .71.83a.953.953 0 0 0 0 1.387L5.696 7.17c.067.066.2.132.266.2l.067.066a1.045 1.045 0 0 0 1.063-.2l4.987-5.019a1.036 1.036 0 0 0 .069-1.453z"/></g><defs><clipPath id="clip0_150_4355"><path transform="translate(.414 .5)" d="M0 0h12v7H0z"/></clipPath></defs></symbol><symbol id="comm_icon_sx" viewBox="0 0 25 26"><g clip-path="url(#clip0_55_1394)"><path d="M8.985 24.426l5.058-9.146 2.1 3.9a10.844 10.844 0 0 0 4.845-9.038c0-3.028-2-6.03-4-8 3.919 1.64 7.43 5.773 7.43 10.285a10.856 10.856 0 0 1-6.549 9.964l1.977 3.18-10.861-1.145zM.413 13.568A10.86 10.86 0 0 1 6.96 3.603L4.985.425l10.857 1.142-5.06 9.147-2.095-3.9a10.842 10.842 0 0 0-4.847 9.04c0 3.027 2 6.03 4 8C3.926 22.216.414 18.083.414 13.57l-.001-.003z"/></g><defs><clipPath id="clip0_55_1394"><path transform="translate(.414 .428)" d="M0 0h24v25.144H0z"/></clipPath></defs></symbol><symbol id="icon_btm_cz" viewBox="0 0 71 70"><g clip-path="url(#clip0_55_1411)"><path d="M7.433 11.5a1 1 0 0 0-1 1v45a1 1 0 0 0 1 1h55a1 1 0 0 0 1-1v-45a1 1 0 0 0-1-1h-55zm0-3h55a4 4 0 0 1 4 4v45a4 4 0 0 1-4 4h-55a4 4 0 0 1-4-4v-45a4 4 0 0 1 4-4z"/><path d="M16.433 50.5h-3v-6h3v6zm0-9h-3v-6h3v6zm0-9h-3v-6h3v6zm0-9h-3v-6h3v6z"/></g><defs><clipPath id="clip0_55_1411"><path transform="translate(.433)" d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_cz0" viewBox="0 0 71 70"><g clip-path="url(#clip0_55_1418)"><path d="M40.433 22.5a7 7 0 0 0 0 14h23v-14h-23zm0-3h26v20h-26a10 10 0 1 1 0-20z"/><path d="M41.433 32.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/></g><defs><clipPath id="clip0_55_1418"><path transform="translate(.433)" d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_sy" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1481)"><path d="M56 61.07a1 1 0 0 0 1-1v-26h7.686L35.2 7.459l-.217-.374-.217.374-.348.314L5.282 34.07H13v26a1 1 0 0 0 1 1h42zm0 3H14a4 4 0 0 1-4-4v-23H4.965a2.953 2.953 0 0 1-2.575-4.431L32.409 5.546a2.984 2.984 0 0 1 5.15 0l30.019 27.093A2.955 2.955 0 0 1 65 37.069h-5v23a4 4 0 0 1-4 4z"/></g><defs><clipPath id="clip0_55_1481"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_sy0" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1486)"><path d="M32 42.07a3 3 0 0 0-3 3v16h12v-16a3 3 0 0 0-3-3h-6zm0-3h6a6 6 0 0 1 6 6v19H26v-19a6 6 0 0 1 6-6z"/></g><defs><clipPath id="clip0_55_1486"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_sy1" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1491)"><path d="M25.984 61.034v-16a6.007 6.007 0 0 1 6-6h6a6.007 6.007 0 0 1 6 6v16h12a1 1 0 0 0 1-1v-26h8.148L34.984 7.06 4.837 34.034h8.147v26a1 1 0 0 0 1 1h12zm3 3h-15a4 4 0 0 1-4-4v-23H4.837a3 3 0 0 1-2-5.236L32.984 4.824a3 3 0 0 1 4 0l30.149 26.974a3 3 0 0 1-2 5.236h-5.149v23a4.005 4.005 0 0 1-4 4h-15v-19a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3v19z"/></g><defs><clipPath id="clip0_55_1491"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_tg" viewBox="0 0 70 70"><path fill-rule="evenodd" clip-rule="evenodd" d="M34.644 10.442c7.15 0 12.948 5.859 12.948 13.086l-.004.338a13.097 13.097 0 0 1-7.2 11.39c5.637 1.213 9.842 4.277 12.809 7.878 3.693 4.482 5.493 9.797 6.02 13.521.05.247.074.518.053.813-.11 1.582-1.32 2.302-1.47 2.392a3.166 3.166 0 0 1-1.015.397c-.21.043-.396.058-.442.062-.151.012-.326.02-.48.025-.332.012-.803.022-1.375.03-1.155.017-2.813.03-4.798.038-3.975.018-9.313.024-14.68.03h-1.113c-5.367-.006-10.705-.012-14.68-.03a529.497 529.497 0 0 1-4.798-.038 69.357 69.357 0 0 1-1.374-.03c-.155-.005-.33-.013-.481-.025-.045-.004-.231-.02-.442-.062a3.167 3.167 0 0 1-1.016-.397c-.147-.089-1.359-.808-1.47-2.392-.02-.295.004-.566.053-.813.528-3.724 2.328-9.04 6.021-13.521 2.967-3.6 7.172-6.665 12.809-7.877a13.096 13.096 0 0 1-7.2-11.391l-.004-.338c0-7.227 5.798-13.086 12.948-13.086l.19.002.19-.002zm-.19 27.319l-.55-.007c-14.716 0-20.313 12.976-21.168 19.503-.149.108 10.43.12 21.165.13h1.105c10.727-.01 21.3-.022 21.164-.13-.854-6.527-6.45-19.503-21.168-19.503l-.549.007zm0-24.261c-.063-.001-.127-.004-.19-.004-5.358 0-9.844 4.413-9.844 10.032 0 5.618 4.486 10.031 9.843 10.032.064 0 .128-.004.19-.006l.19.005c5.357 0 9.844-4.413 9.844-10.031 0-5.619-4.487-10.032-9.843-10.032l-.19.004z"/><path d="M47.889 14.413c5.686.442 10.163 5.242 10.163 11.102l-.003.288a11.149 11.149 0 0 1-6.127 9.696c4.798 1.032 8.376 3.64 10.902 6.706 3.144 3.815 4.676 8.339 5.125 11.509.042.21.062.44.045.692-.095 1.345-1.123 1.959-1.25 2.036a2.72 2.72 0 0 1-.866.338c-.18.036-.338.05-.376.052-.128.011-.277.017-.409.022-.282.01-.683.018-1.17.025-.438.006-.96.011-1.554.016-.014-.23-.04-.45-.076-.66a26.103 26.103 0 0 0-.355-1.935c2.148-.015 3.459-.039 3.418-.074-.605-4.619-4-13.03-12.323-15.736a25.54 25.54 0 0 0-6.339-4.303c.039-.044.075-.092.113-.136l.063-.001.162.005c4.56 0 8.378-3.758 8.378-8.54 0-3.896-2.533-7.11-5.937-8.169a16.184 16.184 0 0 0-1.584-2.933zM21.012 14.42a16.19 16.19 0 0 0-1.593 2.962c-3.349 1.093-5.828 4.28-5.828 8.133 0 4.782 3.819 8.54 8.378 8.54.043 0 .087-.004.13-.005.038.045.075.092.114.137a25.546 25.546 0 0 0-6.394 4.351C7.604 41.297 4.246 49.635 3.645 54.226c-.04.034 1.231.057 3.324.073a26.115 26.115 0 0 0-.352 1.928 6.333 6.333 0 0 0-.08.667 258.15 258.15 0 0 1-1.46-.015 60.25 60.25 0 0 1-1.17-.025 8.971 8.971 0 0 1-.41-.022c-.038-.003-.196-.016-.375-.053a2.716 2.716 0 0 1-.864-.338c-.126-.075-1.157-.688-1.252-2.035a2.606 2.606 0 0 1 .045-.692c.45-3.17 1.982-7.695 5.125-11.51 2.526-3.065 6.104-5.673 10.902-6.705a11.15 11.15 0 0 1-6.127-9.696l-.003-.288c0-5.826 4.426-10.604 10.064-11.095z"/></symbol><symbol id="icon_btm_tg0" viewBox="0 0 70 70"><path d="M33.556 40.318l-3.358 8.798a.95.95 0 0 0 .157.946l3.374 4.058a.95.95 0 0 0 1.461 0l3.422-4.125a.8.8 0 0 0 .131-.798l-3.412-8.882c-.313-.813-1.464-.811-1.775.003z"/></symbol><symbol id="icon_btm_tg1" viewBox="0 0 70 70"><path fill-rule="evenodd" clip-rule="evenodd" d="M34.644 10.442c7.15 0 12.948 5.859 12.948 13.086l-.004.338a13.097 13.097 0 0 1-7.2 11.39c5.637 1.213 9.842 4.277 12.809 7.878 3.693 4.482 5.493 9.797 6.02 13.521.05.247.074.518.053.813-.11 1.582-1.32 2.302-1.47 2.392a3.166 3.166 0 0 1-1.015.397c-.21.043-.396.058-.442.062-.151.012-.326.02-.48.025-.332.012-.803.022-1.375.03-1.155.017-2.813.03-4.798.038-3.975.018-9.313.024-14.68.03h-1.113c-5.367-.006-10.705-.012-14.68-.03a529.497 529.497 0 0 1-4.798-.038 69.357 69.357 0 0 1-1.374-.03c-.155-.005-.33-.013-.481-.025-.045-.004-.231-.02-.442-.062a3.167 3.167 0 0 1-1.016-.397c-.147-.089-1.359-.808-1.47-2.392-.02-.295.004-.566.053-.813.528-3.724 2.328-9.04 6.021-13.521 2.967-3.6 7.172-6.665 12.809-7.877a13.096 13.096 0 0 1-7.2-11.391l-.004-.338c0-7.227 5.798-13.086 12.948-13.086l.19.002.19-.002zm-.19 27.319l-.55-.007c-14.716 0-20.313 12.976-21.168 19.503-.149.108 10.43.12 21.165.13h1.105c10.727-.01 21.3-.022 21.164-.13-.854-6.527-6.45-19.503-21.168-19.503l-.549.007zm0-24.261c-.063-.001-.127-.004-.19-.004-5.358 0-9.844 4.413-9.844 10.032 0 5.618 4.486 10.031 9.843 10.032.064 0 .128-.004.19-.006l.19.005c5.357 0 9.844-4.413 9.844-10.031 0-5.619-4.487-10.032-9.843-10.032l-.19.004z"/><path d="M47.889 14.413c5.686.442 10.163 5.242 10.163 11.102l-.003.288a11.149 11.149 0 0 1-6.127 9.696c4.798 1.032 8.376 3.64 10.902 6.706 3.144 3.815 4.676 8.339 5.125 11.509.042.21.062.44.045.692-.095 1.345-1.123 1.959-1.25 2.036a2.72 2.72 0 0 1-.866.338c-.18.036-.338.05-.376.052-.128.011-.277.017-.409.022-.282.01-.683.018-1.17.025-.438.006-.96.011-1.554.016-.014-.23-.04-.45-.076-.66a26.103 26.103 0 0 0-.355-1.935c2.148-.015 3.459-.039 3.418-.074-.605-4.619-4-13.03-12.323-15.736a25.54 25.54 0 0 0-6.339-4.303c.039-.044.075-.092.113-.136l.063-.001.162.005c4.56 0 8.378-3.758 8.378-8.54 0-3.896-2.533-7.11-5.937-8.169a16.184 16.184 0 0 0-1.584-2.933zM21.012 14.42a16.19 16.19 0 0 0-1.593 2.962c-3.349 1.093-5.828 4.28-5.828 8.133 0 4.782 3.819 8.54 8.378 8.54.043 0 .087-.004.13-.005.038.045.075.092.114.137a25.546 25.546 0 0 0-6.394 4.351C7.604 41.297 4.246 49.635 3.645 54.226c-.04.034 1.231.057 3.324.073a26.115 26.115 0 0 0-.352 1.928 6.333 6.333 0 0 0-.08.667 258.15 258.15 0 0 1-1.46-.015 60.25 60.25 0 0 1-1.17-.025 8.971 8.971 0 0 1-.41-.022c-.038-.003-.196-.016-.375-.053a2.716 2.716 0 0 1-.864-.338c-.126-.075-1.157-.688-1.252-2.035a2.606 2.606 0 0 1 .045-.692c.45-3.17 1.982-7.695 5.125-11.51 2.526-3.065 6.104-5.673 10.902-6.705a11.15 11.15 0 0 1-6.127-9.696l-.003-.288c0-5.826 4.426-10.604 10.064-11.095zM33.556 40.318l-3.358 8.798a.95.95 0 0 0 .157.946l3.374 4.058a.95.95 0 0 0 1.461 0l3.422-4.125a.8.8 0 0 0 .131-.798l-3.412-8.882c-.313-.813-1.464-.811-1.775.003z"/></symbol><symbol id="icon_btm_wd" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1531)"><path d="M35.078 7.999a13 13 0 0 0-9.192 22.192A13.001 13.001 0 1 0 44.27 11.807a12.916 12.916 0 0 0-9.192-3.808zm0-3a16 16 0 1 1 0 32 16 16 0 0 1 0-32z"/><path d="M9.578 65l-.105-3h51.034a.556.556 0 0 0 .489-.418c.02-.212.02-.426 0-.638a25.997 25.997 0 0 0-14.438-21.259l-.808-.4a25.701 25.701 0 0 0-10.4-2.284h-.439a25.848 25.848 0 0 0-10.2 2.11l-1.2.601a26.169 26.169 0 0 0-9.975 8.728 25.786 25.786 0 0 0-4.368 12.48c-.027.9 0 1.03.452 1.084l-.04 3m0 0a3.098 3.098 0 0 1-3.433-2.86 10.13 10.13 0 0 1 0-1.867 27.39 27.39 0 0 1 4.906-13.525 29.18 29.18 0 0 1 11.124-9.732v-.044A28.82 28.82 0 0 1 35.013 34h.022a.018.018 0 0 0 .018 0h.025a28.68 28.68 0 0 1 12.809 2.977v.018a28.998 28.998 0 0 1 16.1 23.714c.018.333.018.667 0 1a3.346 3.346 0 0 1-3.409 3.3c-.159 0-.471-.013-.472 0H10.05s-.259-.003-.472-.009l.002.005z"/></g><defs><clipPath id="clip0_55_1531"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_wd0" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1537)"><path d="M35.078 41.999a2 2 0 0 1 2 2v12a2 2 0 1 1-4 0v-12a2 2 0 0 1 2-2z"/></g><defs><clipPath id="clip0_55_1537"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_wd1" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1542)"><path d="M9.578 64.06a3.102 3.102 0 0 1-3.1-1.667 3.099 3.099 0 0 1-.333-1.194 10.137 10.137 0 0 1 0-1.865 27.4 27.4 0 0 1 4.906-13.525 29.17 29.17 0 0 1 11.125-9.73v-.046a28.982 28.982 0 0 1 5.172-1.962 16 16 0 0 1-3.584-25.325 16 16 0 1 1 19.014 25.34 28.804 28.804 0 0 1 5.1 1.947v.018a29.125 29.125 0 0 1 11.695 10.5 28.947 28.947 0 0 1 4.406 13.21c.016.39.016.628 0 1a3.35 3.35 0 0 1-2.12 3.068c-.41.162-.85.24-1.29.232-.159 0-.47-.012-.472 0H10.05 9.578zm3.469-3.007h47.461a.553.553 0 0 0 .488-.417c.02-.212.02-.426 0-.638a25.959 25.959 0 0 0-3.947-11.837 26.133 26.133 0 0 0-10.49-9.422l-.809-.4a25.687 25.687 0 0 0-10.4-2.284h-.535a25.817 25.817 0 0 0-10.109 2.11l-1.2.6a26.166 26.166 0 0 0-9.976 8.726 25.81 25.81 0 0 0-4.368 12.48c-.026.89 0 1.03.447 1.084h.668l2.77-.002zm12.838-50.185a12.914 12.914 0 0 0-3.807 9.191 12.916 12.916 0 0 0 3.807 9.193 12.909 12.909 0 0 0 8.951 3.8H35.301a12.904 12.904 0 0 0 8.971-3.8 12.915 12.915 0 0 0 3.807-9.193 12.913 12.913 0 0 0-3.807-9.19 12.914 12.914 0 0 0-9.193-3.81 12.915 12.915 0 0 0-9.194 3.81zm7.193 44.185v-12a2 2 0 1 1 4 0v12a2 2 0 1 1-4 0z"/></g><defs><clipPath id="clip0_55_1542"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_yh" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1572)"><path d="M37.001 65.004h-26a2 2 0 0 1-2-2v-29h-2a2 2 0 0 1-2-2v-12a2 2 0 0 1 2-2h57a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-2v29a2 2 0 0 1-2 2h-23zm22-3v-28h-22v28h22zm-47 0h22v-28h-22v28zm51-31v-10h-26v10h26zm-55 0h26v-10h-26v10z"/></g><defs><clipPath id="clip0_55_1572"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_yh0" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1578)"><path d="M42 12.002s9.885-6.927 11.662-7c2.621-.11 2.681 7 1.944 7H42zm-26.606 0c-.737 0-.677-7.11 1.944-7 1.777.074 11.662 7 11.662 7H15.394z"/></g><defs><clipPath id="clip0_55_1578"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_yh1" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1583)"><path d="M33 64.057v-47h3v47h-3zm8-53s9.885-6.926 11.662-7c2.621-.11 2.681 7 1.944 7H41zm-26.605 0c-.738 0-.678-7.11 1.944-7 1.777.074 11.662 7 11.662 7H14.395z"/><path d="M10 64.06a2 2 0 0 1-2-2v-29H6a2 2 0 0 1-2-2v-12a2 2 0 0 1 2-2h57a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-2v29a2 2 0 0 1-2 2H10zm1-3h47v-28H11v28zm-4-31h55v-10H7v10z"/></g><defs><clipPath id="clip0_55_1583"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_zc" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1590)"><path d="M2.77 64.493c-.458-1.157-.238-1.595-.156-3.956a30.518 30.518 0 0 1 22.623-26.97 16 16 0 1 1 15.529-.007 30.304 30.304 0 0 1 5.706 2.137 1.5 1.5 0 1 1-1.328 2.69 27.22 27.22 0 0 0-12.143-2.818 27.612 27.612 0 0 0-27.4 25.312c-.053.58-.053.663-.063 1A1.886 1.886 0 0 0 7.62 63.55l1.037.017h38.257a1.5 1.5 0 1 1 0 3H6.676s-.075.005-.205.005a3.975 3.975 0 0 1-3.701-2.08zm21.037-54.117a13.004 13.004 0 0 0 3.01 20.632A13.258 13.258 0 0 0 33 32.568a13 13 0 1 0-9.193-22.192z"/></g><defs><clipPath id="clip0_55_1590"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="icon_btm_zc0" viewBox="0 0 70 70"><g clip-path="url(#clip0_55_1595)"><path d="M55 58.569v-6h-6a2 2 0 0 1 0-4h6v-6a2 2 0 0 1 4 0v6h6a2 2 0 0 1 0 4h-6v6a2 2 0 1 1-4 0z"/></g><defs><clipPath id="clip0_55_1595"><path d="M0 0h70v70H0z"/></clipPath></defs></symbol><symbol id="img_mryx_card" viewBox="0 0 130 130"><g clip-path="url(#clip0_55_1600)"><path d="M74.124 105.868H52.76a1.033 1.033 0 0 1-1.065-1c0-11.3 5.791-20.2 11.236-22.84 3.27-1.585 6.315-5.886 7.927-8.475a32.39 32.39 0 0 1-5.3.377 25.323 25.323 0 0 1-8.1-1.4 9.88 9.88 0 0 0-2.556-.6c-2.21 0-3.2.922-3.2 2.994v3.991a1.034 1.034 0 0 1-1.066 1h-4.272a1.034 1.034 0 0 1-1.066-1V58.957a1.034 1.034 0 0 1 1.066-1h4.265a1.034 1.034 0 0 1 1.066 1v2.768c2.091-1.566 5.718-3.766 9.594-3.766a14.1 14.1 0 0 1 7.1 2.166 11.386 11.386 0 0 0 5.7 1.827 10.952 10.952 0 0 0 7.686-3.6 1.1 1.1 0 0 1 .776-.383c.301-.015.596.089.821.289l2.132 2a.95.95 0 0 1 .242 1.064.947.947 0 0 1-.215.321c-3.04 3.075-12.508 28.249-12.508 33.254a27.582 27.582 0 0 0 1.962 9.38c.137.17.213.382.213.6a1.036 1.036 0 0 1-1.032 1l-.042-.009zm36.053 0h-16.33a.815.815 0 0 1-.813-.813c0-9.228 4.426-16.5 8.583-18.648 2.5-1.292 4.827-4.8 6.059-6.917-1.34.221-2.696.324-4.054.308a18.27 18.27 0 0 1-6.194-1.141 7.158 7.158 0 0 0-1.954-.49c-1.69 0-2.444.753-2.444 2.445v3.259a.815.815 0 0 1-.813.813h-3.259a.816.816 0 0 1-.813-.813v-16.3a.815.815 0 0 1 .813-.813h3.263a.815.815 0 0 1 .813.813v2.263c1.6-1.28 4.371-3.075 7.333-3.075 1.938.067 3.818.68 5.423 1.769a8.32 8.32 0 0 0 4.354 1.49 8.225 8.225 0 0 0 5.875-2.943.817.817 0 0 1 1.22-.076l1.63 1.63a.813.813 0 0 1 .021 1.129c-2.324 2.511-9.561 23.063-9.561 27.15a23.797 23.797 0 0 0 1.5 7.659.818.818 0 0 1 .079.854.818.818 0 0 1-.728.449l-.003-.002zm-76.145 0H17.7a.815.815 0 0 1-.813-.813c0-9.228 4.426-16.5 8.584-18.648 2.5-1.292 4.826-4.8 6.059-6.917-1.34.221-2.697.324-4.055.308a18.265 18.265 0 0 1-6.194-1.141 7.17 7.17 0 0 0-1.954-.49c-1.69 0-2.444.753-2.444 2.445v3.259a.816.816 0 0 1-.813.813h-3.257a.816.816 0 0 1-.813-.816v-16.3a.815.815 0 0 1 .813-.813h3.263a.815.815 0 0 1 .813.813v2.263c1.6-1.28 4.37-3.075 7.333-3.075 1.938.067 3.818.68 5.423 1.769A8.321 8.321 0 0 0 34 70.017a8.221 8.221 0 0 0 5.874-2.943.816.816 0 0 1 1.22-.076l1.63 1.63a.813.813 0 0 1 .021 1.129c-2.324 2.511-9.56 23.063-9.56 27.15a23.83 23.83 0 0 0 1.5 7.659.812.812 0 0 1-.649 1.303l-.004-.001zm49.216-55.3a1.008 1.008 0 0 1-.389-1.04l1.22-5.373-4.136-3.627a1.01 1.01 0 0 1 .573-1.761l5.472-.5 2.162-5.063a1.008 1.008 0 0 1 1.48-.445c.164.108.293.262.37.443l2.162 5.064 5.472.5a1.008 1.008 0 0 1 .572 1.762l-4.136 3.627 1.219 5.373a1.006 1.006 0 0 1-1.499 1.089l-4.717-2.821-4.718 2.821a1.007 1.007 0 0 1-1.107-.051v.002zm-47.045 0a1.008 1.008 0 0 1-.389-1.04l1.22-5.373-4.134-3.63a1.01 1.01 0 0 1 .573-1.76l5.472-.5 2.155-5.063a1.007 1.007 0 0 1 1.854 0l2.162 5.064 5.472.5a1.006 1.006 0 0 1 .572 1.762l-4.137 3.627 1.22 5.373a1.008 1.008 0 0 1-1.5 1.089l-4.717-2.821-4.718 2.821a1.007 1.007 0 0 1-1.108-.051l.003.002zm21.938-2.7a1.281 1.281 0 0 1-.5-1.323l1.551-6.833-5.262-4.614a1.283 1.283 0 0 1 .725-2.242l6.962-.632 2.751-6.441a1.283 1.283 0 0 1 1.186-.781 1.282 1.282 0 0 1 1.179.778l2.751 6.441 6.961.632a1.28 1.28 0 0 1 1.117 1.617c-.066.242-.202.46-.391.625l-5.262 4.614 1.553 6.834a1.282 1.282 0 0 1-1.196 1.566c-.25.011-.5-.051-.715-.18l-6-3.589-6 3.589a1.28 1.28 0 0 1-1.411-.065l.001.004z"/></g><defs><clipPath id="clip0_55_1600"><path d="M0 0h130v130H0z"/></clipPath></defs></symbol><symbol id="img_tsyx_lxys_h5" viewBox="0 0 60 34"><g clip-path="url(#clip0_55_1604)"><path d="M53.908 13.224l5.282.807a.963.963 0 0 1 .518 1.64l-3.848 3.738a.965.965 0 0 0-.279.848l.871 5.316a.953.953 0 0 1-1.387 1l-4.713-2.534a.944.944 0 0 0-.886-.005l-4.739 2.475a.952.952 0 0 1-1.375-1.02l.935-5.3a.964.964 0 0 0-.269-.851l-3.807-3.785a.961.961 0 0 1 .537-1.634l5.291-.743a.952.952 0 0 0 .72-.521l2.394-4.816a.948.948 0 0 1 1.707.01l2.335 4.846a.95.95 0 0 0 .713.529z"/><path opacity=".8" d="M26.789 15.417l4.189.634a.754.754 0 0 1 .41 1.289l-3.052 2.937a.755.755 0 0 0-.221.666l.69 4.177a.754.754 0 0 1-1.1.788l-3.737-1.993a.755.755 0 0 0-.7 0L19.5 25.858a.755.755 0 0 1-1.09-.8l.74-4.168a.754.754 0 0 0-.212-.67l-3.013-2.973a.753.753 0 0 1 .426-1.284l4.2-.584a.753.753 0 0 0 .568-.41l1.898-3.783a.755.755 0 0 1 1.353.008L26.222 15a.756.756 0 0 0 .567.416z"/></g><defs><clipPath id="clip0_55_1604"><path d="M0 0h60v34H0z"/></clipPath></defs></symbol><symbol id="img_tsyx_ys2_h5" viewBox="0 0 170 40"><path d="M153.2 20.012l8.389-8.41 8.41 8.41-8.41 8.388-8.389-8.388zm-15.4 0l5.593-5.607 5.606 5.607-5.606 5.592-5.593-5.592zm1.483.008l4.1 4.1 4.131-4.1-4.131-4.132-4.1 4.132z"/></symbol><symbol id="img_tsyx_ys3_h5" viewBox="0 0 170 40"><g clip-path="url(#clip0_55_1614)"><path opacity=".2" d="M102.137 10.312L89.208 29.7h9l13.081-19.4-9.152.012z"/><path opacity=".4" d="M116.814 10.312L103.886 29.7h9l13.081-19.4-9.153.012z"/><path opacity=".6" d="M131.495 10.312L118.564 29.7h9l13.081-19.4-9.15.012z"/><path opacity=".8" d="M146.171 10.312L133.242 29.7h9l13.081-19.4-9.152.012z"/><path d="M160.849 10.312L147.92 29.7h9l13.082-19.4-9.153.012z"/></g><defs><clipPath id="clip0_55_1614"><path transform="translate(.002)" d="M0 0h170v40H0z"/></clipPath></defs></symbol><symbol id="img_tsyx_ys4_h5" viewBox="0 0 170 40"><g clip-path="url(#clip0_55_1623)"><path opacity=".7" d="M170.002 29.271l-5.139-4.932v-8.682l5.139-4.927v18.541z"/><path d="M166.136 4.999v7.974l-7.318 7.026 7.319 7.024v7.976l-15.636-15 15.635-15z"/></g><defs><clipPath id="clip0_55_1623"><path transform="translate(.002)" d="M0 0h170v40H0z"/></clipPath></defs></symbol></svg>',requestUrl:"https://aG9nZWpxLTg2MDYtcHBw@b90080a7-5907-4dc5-a127-33af0acb56f4.czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@b90080a7-5907-4dc5-a127-33af0acb56f4//siteadmin/skin/lobby_asset/2-1-common/web/home/svg_sprites_home.svg?t=1751498634002"}}, ossGetSiteUrlConfig: { data: { code: 1, data: { "web_bucket_url": [], "siteCode": "7920", "siteBucketSwitchStatus": 0, "issuedTime": "2025-04-11 16:00:53", "api_domain": [], "non_ma_domain": [], "combo_domain": [""], "lobby_domain": [], "oss_domain": [], "commonOssBucket": "", "web_domain": [""], "ma_domain": [], "pay_domain_switch_status": 1, "h5_domain": [""], "pay_domain": [""], "commonOssDomain": [], "hotfix_domain": [""], "download_domain_url": [""] } } }, ossGetSkinAssetsHash: null }, LOG: { uuid: "220a64f1c958dc28d9f219ed8c55f044" } }), l = ["c2NyaXB0@4579cfb0-c72e-4aaf-87e2-b643f42e87d2", "YjJuc3l2LTc5MjAtcHBw@4579cfb0-c72e-4aaf-87e2-b643f42e87d2", "czMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20=@4579cfb0-c72e-4aaf-87e2-b643f42e87d2"], t = 0; t < l.length; t++) { var n = l[t]; g = g.replace(new RegExp(n, "g"), (function () { return decodeURIComponent(window.atob(n.split("@")[0])) })) } var e = g; window.LOBBY_SITE_CONFIG = JSON.parse(e) }()</script>
    <script
        defer=defer>window.LOBBY_UTILS = { loadStats() { if (location.href.indexOf("mock.stats=true") > -1) { var e = document.createElement("script"); e.type = "text/javascript", e.src = "/libs/stats@0.0.3/index.js", document.head.appendChild(e) } }, preventZoom() { var e = 0; document.addEventListener("touchstart", (function (e) { e.touches.length > 1 && e.preventDefault() })), document.addEventListener("touchend", (function (t) { var n = (new Date).getTime(); n - e <= 300 && t.preventDefault(), e = n }), !1), document.addEventListener("gesturestart", (function (e) { e.preventDefault() })) }, detectDeviceType() { var e = navigator.userAgent, t = () => e.match(/(iPad)/) || !e.match(/(iPhone\sOS)\s([\d_]+)/) && window.screen.height > window.screen.width && /macintosh|mac os x/i.test(e), n = () => /iPhone|Android.+Mobile/.test(e) || navigator.maxTouchPoints && navigator.maxTouchPoints > 1 && /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(e); return { tablet: t, mobile: n, desktop: () => !t() && !n() } }, mediaScreen() { var e = window.LOBBY_UTILS.detectDeviceType(), t = e.tablet(), n = e.desktop(), i = window.innerWidth, o = window.innerHeight, a = 750 / 1334, r = "mobile", d = "0", s = i / o > a ? a * o : i; i > 450 && !t && (d = "1"); var c = document.querySelector("html"); return c && (c.style.setProperty("--lobby__max-width", "1" === d ? s + "px" : "100%"), c.style.setProperty("--lobby__vh", .01 * o + "px"), c.setAttribute("data-device", r), c.setAttribute("data-ui-contain", d), c.setAttribute("data-isdesktop", n ? "1" : "0")), { device: r, size: "small", uiContain: d } }, initSplash() { window.initSplash = { nodeClassName: "skeleton-screen-main", destroy(e) { try { var t = document.getElementsByClassName(this.nodeClassName)[0]; t.parentNode && t.parentNode.removeChild(t) } catch (t) { !e && this.destroy(!0) } } } }, redirect() { if (-1 !== window.navigator.userAgent.toLowerCase().indexOf("micromessenger")) { var e = `/pages/wechat/index.html?payload=${window.btoa(JSON.stringify({ origialUrl: encodeURIComponent(window.location.href) }))}&t=${+new Date}`; window.location.href = e } }, deleteOgImage() { for (var e = document.getElementsByTagName("meta"), t = e.length - 1; t >= 0; t--) { var n = e[t]; "og:image" === n.getAttribute("property") && n.parentNode.removeChild(n) } } }, LOBBY_UTILS.redirect(), LOBBY_UTILS.initSplash(), LOBBY_UTILS.mediaScreen(), LOBBY_UTILS.deleteOgImage(), window.addEventListener("resize", (function () { -1 === window.location.href.indexOf("fixed.isSaveShort") && (window.scrollTo(0, 1), LOBBY_UTILS.mediaScreen()) })), LOBBY_UTILS.preventZoom(), LOBBY_UTILS.loadStats()</script>
   <script defer=defer src="/libs/monitor/index.js?ver=1.0.2"></script>
    <script
        defer=defer>window.LOBBY_UTILS = { loadStats() { if (location.href.indexOf("mock.stats=true") > -1) { var e = document.createElement("script"); e.type = "text/javascript", e.src = "/libs/stats@0.0.3/index.js", document.head.appendChild(e) } }, preventZoom() { var e = 0; document.addEventListener("touchstart", (function (e) { e.touches.length > 1 && e.preventDefault() })), document.addEventListener("touchend", (function (t) { var o = (new Date).getTime(); o - e <= 300 && t.preventDefault(), e = o }), !1), document.addEventListener("gesturestart", (function (e) { e.preventDefault() })) }, detectMediaMatch() { var e = document.createElement("script"), t = "0.0.6"; e.src = "/libs/browser-media-match@0.0.6/index.js", document.head.appendChild(e), e.onload = function () { var e = window.localStorage.getItem("lobby@support@media") || ""; e && e.split(":")[0] === t || window.CGBrowserMediaMatch.checkImageFormatSupport((function (e) { window.localStorage.setItem("lobby@support@media", t + ":" + e) })) } }, detectDeviceType() { var e = navigator.userAgent, t = () => e.match(/(iPad)/) || !e.match(/(iPhone\sOS)\s([\d_]+)/) && window.screen.height > window.screen.width && /macintosh|mac os x/i.test(e), o = () => /iPhone|Android.+Mobile/.test(e) || navigator.maxTouchPoints && navigator.maxTouchPoints > 1 && /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(e); return { tablet: t, mobile: o, desktop: () => !t() && !o() } }, mediaScreen() { var e = window.LOBBY_UTILS.detectDeviceType(), t = e.tablet(), o = e.desktop(), n = window.innerWidth, i = window.innerHeight, a = 750 / 1334, r = "mobile", d = "0", s = n / i > a ? a * i : n; n > 450 && !t && (d = "1"); var c = document.querySelector("html"); return c && (c.style.setProperty("--lobby__max-width", "1" === d ? s + "px" : "100%"), c.style.setProperty("--lobby__vh", .01 * i + "px"), c.setAttribute("data-device", r), c.setAttribute("data-ui-contain", d), c.setAttribute("data-isdesktop", o ? "1" : "0")), { device: r, size: "small", uiContain: d } }, initSplash() { window.initSplash = { nodeClassName: "skeleton-screen-main", destroy(e) { try { var t = document.getElementsByClassName(this.nodeClassName)[0]; t.parentNode && t.parentNode.removeChild(t) } catch (t) { !e && this.destroy(!0) } } } }, redirect() { if (-1 !== window.navigator.userAgent.toLowerCase().indexOf("micromessenger")) { var e = `/pages/wechat/index.html?payload=${window.btoa(JSON.stringify({ origialUrl: encodeURIComponent(window.location.href) }))}&t=${+new Date}`; window.location.href = e } }, deleteOgImage() { for (var e = document.getElementsByTagName("meta"), t = e.length - 1; t >= 0; t--) { var o = e[t]; "og:image" === o.getAttribute("property") && o.parentNode.removeChild(o) } }, updateThemeColor(e) { var t = document.querySelector('meta[name="theme-color"]'); t ? t.setAttribute("content", e) : ((t = document.createElement("meta")).name = "theme-color", t.content = e, document.head.appendChild(t)) }, initFrameProxyInit() { try { window.frameProxyIsReady = window.CGFrameStorageProxy.frameProxyInit({ disabled: function () { var e = window.self !== window.top; return !((location.href.includes("fixed.iswebclip=2") || location.href.includes("webClipData=")) && e) } }).isReady } catch (e) { window.frameProxyIsReady = !0 } } }, LOBBY_UTILS.redirect(), LOBBY_UTILS.initSplash(), LOBBY_UTILS.mediaScreen(), LOBBY_UTILS.deleteOgImage(), LOBBY_UTILS.initFrameProxyInit(), window.updateThemeColor = LOBBY_UTILS.updateThemeColor, window.addEventListener("resize", (function () { -1 === window.location.href.indexOf("fixed.isSaveShort") && (window.scrollTo(0, 1), LOBBY_UTILS.mediaScreen()) })), LOBBY_UTILS.preventZoom(), LOBBY_UTILS.detectMediaMatch(), LOBBY_UTILS.loadStats()</script>
    <script type=module crossorigin="" src=/assets/theme-2/index.D2vRNKDI.js onerror=location.reload()></script>
    <link rel=preload crossorigin="" href=/assets/vendors/vendor-.9Fs140A-.css as=style
        onload='this.onload=null,this.rel="stylesheet"'>
    <link rel=preload crossorigin="" href=/assets/vendors/vendor-swiper.CoXUCMPL.css as=style
        onload='this.onload=null,this.rel="stylesheet"'>
    <link rel=preload crossorigin="" href=/assets/theme-2/commonChunk.BL0GeSe6.css as=style
        onload='this.onload=null,this.rel="stylesheet"'>
    <script
        type=module>import.meta.url, import("_").catch((() => 1)), async function* () { }().next(), "file:" != location.protocol && (window.__vite_is_modern_browser = !0)</script>
    <script
        type=module>!function () { if (!window.__vite_is_modern_browser) { console.warn("vite: loading legacy chunks, syntax error above and the same error below should be ignored"); var e = document.getElementById("vite-legacy-polyfill"), t = document.createElement("script"); t.src = e.src, t.onload = function () { System.import(document.getElementById("vite-legacy-entry").getAttribute("data-src")) }, document.body.appendChild(t) } }()</script>
</head>
<!-- Start of LiveChat (www.livechat.com) code 
<script>
    window.__lc = window.__lc || {};
    window.__lc.license = 19172355;
    window.__lc.integration_name = "manual_onboarding";
    window.__lc.product_name = "livechat";
    ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)}var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can't use getters before load.");return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){var n=t.createElement("script");n.async=!0,n.type="text/javascript",n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};!n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
</script>
<noscript><a href="https://www.livechat.com/chat-with/19172355/" rel="nofollow">Chat with us</a>, powered by <a href="https://www.livechat.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a></noscript>
 End of LiveChat code -->

<body class="<?= $natal_theme_active ? 'theme-natal' : '' ?>">
    <div id=app></div>
    <div class=skeleton-screen-main style=background-color:transparent data-inject-mode="undefined">
        <div>
            <style>
                html[data-ui-contain="1"] .skeleton-screen-main {
                    max-width: var(--lobby__max-width);
                    margin: 0 auto
                }

                .skeleton-screen-main {
                    position: fixed;
                    top: 0;
                    right: 0;
                    bottom: 0;
                    left: 0;
                    z-index: 1000
                }

                .skeleton-custom {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 100%;
                    height: 100vh;
                    background-color: var(--skin__bg_2);
                    opacity: 1 !important
                }

                .skeleton-custom img {
                    width: 5.55rem;
                    height: 5.55rem
                }
            </style>
            <div class=skeleton-custom style=opacity:0; data-device=mobile>
                <picture>
                    <source id=skeleton-custom-avif-mobile
                        srcset="<?="/uploads/" . $dataconfig['carregamento_img'];?>"
                        type=image/avif>
                    <source id=skeleton-custom-webp-mobile
                        srcset="<?="/uploads/" . $dataconfig['carregamento_img'];?>"
                        type=image/webp> <img id=skeleton-custom-original-mobile
                        src="<?="/uploads/" . $dataconfig['carregamento_img'];?>"
                        onerror='(function(){var t=document.getElementById("skeleton-custom-avif-mobile"),e=document.getElementById("skeleton-custom-webp-mobile"),l=document.getElementById("skeleton-custom-original-mobile"),c=decodeURIComponent(window.atob("aHR0cHMlM0ElMkYlMkZiMm5zeXYtNzkyMC1wcHAuczMuc2EtZWFzdC0xLmFtYXpvbmF3cy5jb20lMkZzaXRlYWRtaW4lMkZsYXlvdXREZXNpZ24lMkYxOTEyNjM0NzM2NDExOTUxMTA1LnBuZw==@9f88750f-e1ee-4d70-adc2-e4c62c677827".split("@")[0]));this.getAttribute("data-retry")||(this.setAttribute("data-retry",1),t&&(t.srcset=c),e&&(e.srcset=c),l&&(l.src=c))}).call(this)'>
                </picture>
            </div>
        </div>
    </div>
    <style>
        .antiban {
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background-color: #fff;
            z-index: 99999
        }

        .antiban .antiban-box {
            width: 90%;
            margin-top: 1.4rem;
            margin-right: auto;
            margin-left: auto;
            padding: .15rem;
            color: #3c763d;
            background-color: #dff0d8;
            font-size: .14rem;
            text-align: center;
            border: .01rem solid transparent;
            border-radius: .04rem
        }

        .antiban .antiban-forward {
            display: inline-block;
            margin-top: .2rem;
            margin-bottom: 0;
            padding: .06rem .12rem;
            color: #fff;
            font-weight: 400;
            white-space: nowrap;
            text-align: center;
            vertical-align: middle;
            background-color: #5cb85c;
            background-image: none;
            border: .01rem solid transparent;
            border-radius: .04rem;
            cursor: pointer
        }
    </style>

    <?php if ($menu_navbar_ativo): ?>
    <style>
        :root {
            --marketing-box-enabled: 1;
        }
        
        ._marketing-box_10kxy_33 {
            display: var(--marketing-box-enabled, 1) == 1 ? block : none;
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            padding: 0;
            background: transparent;
            position: relative;
        }
        
        ._main_1sqbq_44 {
            display: flex;
            justify-content: space-evenly;
            align-items: flex-end;
            gap: 12px;
            padding: 15px 20px;
            overflow: visible;
        }
        
        ._marketing-item_tnolq_68 {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            transition: transform 0.2s ease;
            position: relative;
            flex-shrink: 0;
        }
        
        ._marketing-item_tnolq_68:hover {
            transform: translateY(-2px);
        }
        
        ._marketing-bg_tnolq_77 {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: -28px;
            transition: all 0.2s ease;
            border: none;
        }
        
        ._marketing-title_tnolq_118 {
            margin: 0;
            padding: 5px 12px;
            border-radius: 14px;
            background: var(--bg);
            color: white;
            font-size: 11px;
            font-weight: 700;
            text-align: center;
            line-height: 1.2;
            min-width: 75px;
            max-width: 80px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            text-transform: capitalize;
            letter-spacing: 0px;
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            hyphens: none;
        }
        
        ._marketing-title_tnolq_118 p {
            margin: 0;
            padding: 0;
            width: 100%;
        }
        
        ._marketing-item_tnolq_68[data-marketing="promocao"] ._marketing-title_tnolq_118 {
            background: linear-gradient(135deg, #E53E3E 0%, #C53030 100%);
        }
        
        ._marketing-item_tnolq_68[data-marketing="recompensas"] ._marketing-title_tnolq_118 {
            background: linear-gradient(135deg, #E53E3E 0%, #C53030 100%);
        }
        
        ._marketing-item_tnolq_68[data-marketing="juros"] ._marketing-title_tnolq_118 {
            background: linear-gradient(135deg, #F6AD55 0%, #ED8936 100%);
        }
        
        ._marketing-item_tnolq_68[data-marketing="rebate"] ._marketing-title_tnolq_118 {
            background: linear-gradient(135deg, #48BB78 0%, #38A169 100%);
        }
        
        ._marketing-item_tnolq_68[data-marketing="vip"] ._marketing-title_tnolq_118 {
            background: linear-gradient(135deg, #E53E3E 0%, #C53030 100%);
        }
        
        @media (max-width: 768px) {
            ._marketing-box_10kxy_33 {
                margin: 15px auto;
                max-width: 100%;
                padding: 0;
            }
            
            ._main_1sqbq_44 {
                gap: 6px;
                justify-content: flex-start;
                padding: 15px 10px;
                overflow-x: auto;
                overflow-y: visible;
                flex-wrap: nowrap;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }
            
            ._main_1sqbq_44::-webkit-scrollbar {
                display: none;
            }
            
            ._marketing-item_tnolq_68 {
                flex-shrink: 0;
                -webkit-user-drag: none;
                user-select: none;
                -webkit-touch-callout: none;
            }
            
            ._marketing-bg_tnolq_77 {
                width: 75px;
                height: 75px;
                margin-bottom: -26px;
                pointer-events: none;
            }
            
            ._marketing-title_tnolq_118 {
                font-size: 10px;
                padding: 4px 10px;
                min-width: 70px;
                max-width: 75px;
                pointer-events: none;
            }
        }
        
        @media (max-width: 480px) {
            ._main_1sqbq_44 {
                gap: 14px !important;
                padding: 8px 15px !important;
                justify-content: flex-start !important;
            }
            
            ._main_1sqbq_44 {
                gap: 5px;
                padding: 12px 8px;
                justify-content: flex-start;
            }
            
            ._marketing-item_tnolq_68 {
                flex: 0 0 auto;
            }
            
            ._marketing-bg_tnolq_77 {
                width: 70px;
                height: 70px;
                border-radius: 50%;
                margin-bottom: -24px;
            }
            
            ._marketing-title_tnolq_118 {
                font-size: 9px;
                padding: 4px 9px;
                min-width: 65px;
                max-width: 70px;
                border-radius: 12px;
            }
        }
        
        @media (max-width: 360px) {
            ._main_1sqbq_44 {
                gap: 4px;
                padding: 10px 5px;
            }
            
            ._marketing-bg_tnolq_77 {
                width: 65px;
                height: 65px;
                margin-bottom: -22px;
            }
            
            ._marketing-title_tnolq_118 {
                font-size: 9px;
                padding: 4px 8px;
                min-width: 62px;
                max-width: 66px;
            }
        }
    </style>
    
    <script>
        const MARKETING_BOX_CONFIG = {
            enabled: true,
            items: [
                {
                    id: 'promocao',
                    title: 'Promoção',
                    icon: 'https://dfhfdsh.eggspgpay.com/siteadmin/upload/icon_tgzq1.avif',
                    route: '/home/event?eventCurrent=1'
                },
                {
                    id: 'recompensas',
                    title: 'Recompensas',
                    icon: 'https://dfhfdsh.eggspgpay.com/siteadmin/upload/icon_dlq1.avif',
                    route: '/home/canReceive'
                },
                {
                    id: 'juros',
                    title: 'Juros',
                    icon: 'https://dfhfdsh.eggspgpay.com/siteadmin/upload/icon_lxb1.avif',
                    route: '/home/yuebao'
                },
                {
                    id: 'rebate',
                    title: 'Rebate',
                    icon: 'https://dfhfdsh.eggspgpay.com/siteadmin/upload/icon_ssfs1.avif',
                    route: '/home/cashback'
                },
                {
                    id: 'vip',
                    title: 'VIP',
                    icon: 'https://dfhfdsh.eggspgpay.com/siteadmin/upload/icon_vip1.avif',
                    route: '/home/vip'
                }
                ,
              
            ]
        };
    
        function createMarketingBox() {
            if (!MARKETING_BOX_CONFIG.enabled) return null;
    
            const marketingBox = document.createElement('div');
            marketingBox.className = '_marketing-box_10kxy_33';
            marketingBox.id = 'marketing-box';
    
            const mainContainer = document.createElement('div');
            mainContainer.className = '_main_1sqbq_44';
    
            MARKETING_BOX_CONFIG.items.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = '_marketing-item_tnolq_68';
                itemElement.setAttribute('data-marketing', item.id);
                itemElement.style.cursor = 'pointer';
    
                const iconElement = document.createElement('img');
                iconElement.className = '_marketing-bg_tnolq_77';
                iconElement.src = item.icon;
                iconElement.alt = item.title;
                iconElement.loading = 'lazy';
    
                const titleElement = document.createElement('div');
                titleElement.className = '_marketing-title_tnolq_118';
                const titleText = document.createElement('p');
                titleText.textContent = item.title;
                titleElement.appendChild(titleText);
    
                itemElement.appendChild(iconElement);
                itemElement.appendChild(titleElement);
    
                itemElement.addEventListener('click', () => {
                    handleMarketingClick(item);
                });
    
                mainContainer.appendChild(itemElement);
            });
    
            marketingBox.appendChild(mainContainer);
            return marketingBox;
        }
    
        function handleMarketingClick(item) {
            console.log(`Marketing item clicked: ${item.title}`);
            sessionStorage.setItem('activeMarketingItem', item.id);
            
            if (item.route) {
                window.location.href = item.route;
            }
        }
    
        function injectMarketingBox() {
            if (!MARKETING_BOX_CONFIG.enabled) return;
    
            const targetSelector = '._banner-container_1xtky_30';
            const target = document.querySelector(targetSelector);
            
            if (target && !document.getElementById('marketing-box')) {
                const marketingBox = createMarketingBox();
                if (marketingBox) {
                    target.parentNode.insertBefore(marketingBox, target);
                    console.log('Marketing box injetado com sucesso');
                    refreshOnlineCount();
                    setInterval(refreshOnlineCount, 60000);
                    setInterval(registerOnlinePing, 60000);
                }
            }
        }
    
        function toggleMarketingBox(enabled) {
            MARKETING_BOX_CONFIG.enabled = enabled;
            const existingBox = document.getElementById('marketing-box');
            
            if (enabled && !existingBox) {
                injectMarketingBox();
            } else if (!enabled && existingBox) {
                existingBox.remove();
            }
            
            document.documentElement.style.setProperty('--marketing-box-enabled', enabled ? '1' : '0');
        }
    
        function isMarketingBoxEnabled() {
            return MARKETING_BOX_CONFIG.enabled;
        }
    
        function attemptInjection() {
            let attempts = 0;
            const maxAttempts = 50;
            
            const tryInject = () => {
                attempts++;
                if (attempts > maxAttempts) {
                    console.log('Marketing box: máximo de tentativas atingido');
                    return;
                }
                
                const target = document.querySelector('._banner-container_1xtky_30');
                if (target) {
                    injectMarketingBox();
                } else {
                    setTimeout(tryInject, 100);
                }
            };
            
            tryInject();
        }
    
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', attemptInjection);
        } else {
            attemptInjection();
        }
    
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    const target = document.querySelector('._banner-container_1xtky_30');
                    if (target && !document.getElementById('marketing-box')) {
                        injectMarketingBox();
                    }
                }
            });
        });
    
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });

        function refreshOnlineCount() {
            fetch('/api/v1/online_ping?count=1')
                .then(r => r.json())
                .then(d => {
                    if (d && d.success) {
                        const item = document.querySelector("._marketing-item_tnolq_68[data-marketing='onlineusers'] ._marketing-title_tnolq_118 p");
                        if (item) item.textContent = 'Online: ' + d.count;
                    }
                })
                .catch(() => {});
        }

        function registerOnlinePing() {
            fetch('/api/v1/online_ping', { method: 'POST' }).catch(() => {});
        }

        window.addEventListener('load', () => {
            registerOnlinePing();
        });

        function sendOfflineBeacon() {
            const data = new Blob(['offline=1'], { type: 'application/x-www-form-urlencoded' });
            if (navigator.sendBeacon) {
                navigator.sendBeacon('/api/v1/online_ping', data);
            } else {
                fetch('/api/v1/online_ping', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'offline=1' }).catch(() => {});
            }
        }

        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'hidden') {
                sendOfflineBeacon();
            }
        });

        window.addEventListener('pagehide', () => {
            sendOfflineBeacon();
        });
</script>
    <?php endif; ?>
    
        <script>
    (function(){
        function findOrderId(){
            try {
                const list = document.querySelectorAll('._orderInfo_eji8x_30 li');
                for (const li of list){
                    const labelEl = li.querySelector('span');
                    const labelText = labelEl ? (labelEl.textContent || '').trim() : '';
                    if (!labelText) continue;
                    if (labelText.includes('Número do pedido do comerciante') || labelText.includes('Número do Pedido')){
                        const infoEl = li.querySelector('div');
                        const txt = infoEl ? (infoEl.textContent || '').trim() : (li.textContent || '').replace(labelText,'').trim();
                        if (txt) return txt;
                    }
                }
            } catch(e){}
            return null;
        }

        function showPaidView(){
            try {
                const mainInfo = document.querySelector('._mainInfo_1e4js_30');
                const qrContainer = document.querySelector("[id^='recharge-qrcode-']");
                if (!mainInfo || !qrContainer) return;

                const amountEl = document.querySelector('._money_1e4js_33 span');
                const amountText = amountEl ? (amountEl.textContent || '').trim() : '';

                const statusLabel = Array.from(document.querySelectorAll('._orderInfo_eji8x_30 li span')).find(el => (el.textContent || '').trim().includes('Status do pedido'));
                if (statusLabel){
                    const li = statusLabel.closest('li');
                    const info = li ? li.querySelector('div') : null;
                    if (info){
                        info.innerHTML = '<span style="color:#10b981;font-weight:700">Pagamento bem-feito</span>';
                    }
                }

                if (!document.getElementById('paid-overlay')){
                    const overlay = document.createElement('div');
                    overlay.id = 'paid-overlay';
                    overlay.style.position = 'absolute';
                    overlay.style.inset = '0';
                    overlay.style.backdropFilter = 'blur(3px)';
                    overlay.style.background = 'rgba(0,0,0,0.25)';
                    overlay.style.pointerEvents = 'none';
                    overlay.style.zIndex = '5';

                    const checkWrap = document.createElement('div');
                    checkWrap.style.position = 'absolute';
                    checkWrap.style.inset = '0';
                    checkWrap.style.display = 'flex';
                    checkWrap.style.alignItems = 'center';
                    checkWrap.style.justifyContent = 'center';
                    checkWrap.style.zIndex = '6';

                    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    svg.setAttribute('viewBox','0 0 120 120');
                    svg.style.width = '96px';
                    svg.style.height = '96px';

                    const circle = document.createElementNS('http://www.w3.org/2000/svg','circle');
                    circle.setAttribute('cx','60');
                    circle.setAttribute('cy','60');
                    circle.setAttribute('r','46');
                    circle.setAttribute('fill','#22c55e');

                    const path = document.createElementNS('http://www.w3.org/2000/svg','path');
                    path.setAttribute('d','M44 60l10 10 22-22');
                    path.setAttribute('stroke','#fff');
                    path.setAttribute('stroke-width','10');
                    path.setAttribute('fill','none');
                    path.setAttribute('stroke-linecap','round');
                    path.setAttribute('stroke-linejoin','round');

                    svg.appendChild(circle);
                    svg.appendChild(path);
                    checkWrap.appendChild(svg);

                    const qrBox = qrContainer;
                    qrBox.style.position = 'relative';
                    qrBox.appendChild(overlay);
                    qrBox.appendChild(checkWrap);
                }

                if (!document.getElementById('paid-success-block')){
                    const successBlock = document.createElement('div');
                    successBlock.id = 'paid-success-block';
                    successBlock.style.display = 'flex';
                    successBlock.style.flexDirection = 'column';
                    successBlock.style.alignItems = 'center';
                    successBlock.style.gap = '8px';
                    successBlock.style.padding = '.12rem 0';

                    const amount = document.createElement('div');
                    amount.style.fontSize = '20px';
                    amount.style.fontWeight = '700';
                    amount.style.color = '#fff';
                    amount.textContent = amountText;

                    const success = document.createElement('div');
                    success.style.color = '#22c55e';
                    success.style.fontSize = '18px';
                    success.style.fontWeight = '800';
                    success.textContent = 'Recebido com sucesso';

                    const desc = document.createElement('div');
                    desc.style.color = '#8ce99a';
                    desc.style.textAlign = 'center';
                    desc.style.fontSize = '14px';
                    desc.innerHTML = 'O sistema atribuiu automaticamente uma pontuação a você, A página atual será fechada após <span id="paid-countdown">10</span> segundos';

                    const actions = document.createElement('div');
                    actions.style.display = 'flex';
                    actions.style.gap = '8px';
                    actions.style.fontSize = '13px';
                    actions.style.color = '#fff';
                    actions.style.alignItems = 'center';

                    const closeNow = document.createElement('a');
                    closeNow.href = 'javascript:void(0)';
                    closeNow.style.color = '#fff';
                    closeNow.style.textDecoration = 'underline';
                    closeNow.textContent = 'Fechar agora';
                    closeNow.onclick = function(){ window.location.reload(); };

                    const sep = document.createElement('span');
                    sep.style.opacity = '0.7';
                    sep.textContent = 'Ou verificar pedido';
                    sep.onclick = function(){ window.location.reload(); };

                    actions.appendChild(closeNow);
                    actions.appendChild(sep);

                    successBlock.appendChild(amount);
                    successBlock.appendChild(success);
                    successBlock.appendChild(desc);
                    successBlock.appendChild(actions);

                    mainInfo.appendChild(successBlock);

                    let t = 10;
                    const el = document.getElementById('paid-countdown');
                    const c = setInterval(function(){
                        t--; if (el) el.textContent = String(t);
                        if (t <= 0){ clearInterval(c); window.location.reload(); }
                    },1000);
                }
            } catch(e){}
        }

        function poll(){
            const orderId = findOrderId();
            if (!orderId) return;
            let done = false;
            const intv = setInterval(function(){
                if (done){ clearInterval(intv); return; }
                fetch('/hall/pay/checkstatus?orderId=' + encodeURIComponent(orderId), { credentials: 'include' })
                    .then(function(r){ return r.json(); })
                    .then(function(j){
                        if (j && j.data && j.data.status === 'pago'){
                            done = true;
                            clearInterval(intv);
                            showPaidView();
                        }
                    })
                    .catch(function(){ /* silencioso */ });
            }, 3000);
        }

        function initWhenReady(){
            let attempts = 0;
            const max = 60;
            const tryStart = function(){
                attempts++;
                const orderSection = document.querySelector('._orderInfo_eji8x_30');
                const qr = document.querySelector("[id^='recharge-qrcode-']");
                if (orderSection && qr){
                    poll();
                } else if (attempts < max){
                    setTimeout(tryStart, 500);
                }
            };
            tryStart();
        }

        if (document.readyState === 'loading'){
            document.addEventListener('DOMContentLoaded', initWhenReady);
        } else {
            initWhenReady();
        }

        const mo = new MutationObserver(function(){
            const orderSection = document.querySelector('._orderInfo_eji8x_30');
            const qr = document.querySelector("[id^='recharge-qrcode-']");
            if (orderSection && qr){
                initWhenReady();
                mo.disconnect();
            }
        });
    mo.observe(document.body,{childList:true,subtree:true});
    })();
    </script>

    <div id="welcome-modal" style="position:fixed;inset:0;display:none;z-index:9999;background:rgba(0,0,0,0.5)">
        <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:min(450px,90%);border-radius:10px;background:var(--skin__bg_2, #1a1c23);color:var(--skin__neutral_2,#fff);padding:16px;border:1px solid var(--skin__border,#2a2d36)">
            <div class="wm-title" style="text-align:center;font-weight:700;font-size:18px;margin-bottom:12px;color:#21d07a">Parabéns por se cadastrar!</div>
            <div style="font-size:14px;line-height:1.35;margin:8px 0;text-align:center;color:#fff">🎉 Parabéns por se cadastrar! ✅</div>
            <div style="font-size:13px;line-height:1.35;margin:8px 0;text-align:center;color:#fff">$ Ganhe até 777 de bônus no seu primeiro depósito.</div>
            <div style="font-size:13px;line-height:1.35;margin:8px 0;text-align:center;color:#fff">💥 Jogue agora! 💥</div>
            <div style="display:flex;gap:12px;align-items:center;justify-content:center;margin-top:18px">
                <div style="position:relative;flex:1">
                    <span style="position:absolute;top:-16px;right:0;transform:none;background:#21d07a;color:#fff;border-radius:10px;padding:1px 6px;font-size:11px;white-space:nowrap">Ganhe R$777</span>
                    <button class="wm-app" style="width:100%;padding:12px 14px;border-radius:12px;border:1px solid var(--skin__border,#2a2d36);background:transparent;color:var(--skin__neutral_2,#fff);font-weight:600;font-size:14px;cursor:pointer">Baixe o APP</button>
                </div>
                <div style="position:relative;flex:1">
                    <span style="position:absolute;top:-16px;right:0;transform:none;background:#21d07a;color:#fff;border-radius:10px;padding:1px 6px;font-size:11px;white-space:nowrap">Bônus de depósito</span>
                    <button class="wm-deposit" style="width:100%;padding:12px 14px;border-radius:12px;border:1px solid var(--skin__primary);background:var(--skin__primary);font-weight:600;font-size:14px;color:#fff;cursor:pointer">Deposite agora</button>
                </div>
            </div>
            <button class="wm-close" aria-label="close" style="position:absolute;left:50%;bottom:-40px;transform:translateX(-50%);border:none;background:transparent;color:inherit;display:flex;align-items:center;justify-content:center;padding:0;cursor:pointer">
                <svg width="30" height="30" fill="#fff"><use xlink:href="#ui-close-059120"></use></svg>
            </button>
        </div>
    </div>

    <!-- SCRIPT DO MODAL - No final do body -->
    <script>
    (function(){
        var modal=document.getElementById('welcome-modal');
        if(!modal) return;
        
        function openWelcome(){
            document.cookie='welcome_after_register=; path=/; max-age=0; samesite=Lax';
            var title=modal.querySelector('.wm-title');
            title.style.color='#21d07a';
            var btnDeposit=modal.querySelector('.wm-deposit');
            var btnApp=modal.querySelector('.wm-app');
            var btnClose=modal.querySelector('.wm-close');
            
            try{
                var hasSymbol=document.querySelector('#ui-close-059120')!=null;
                if(!hasSymbol){ btnClose.textContent='×'; }
            }catch(_e){}
            
            function findDepositTriggerDeep(){
                try{
                    var byId=document.getElementById('depositClick');
                    if(byId) return byId;
                    
                    var byClass = document.querySelector('._reCharge_1wuau_54');
                    if(byClass) return byClass;
                    
                    var byClassContains = document.querySelector('[class*="_reCharge_"]');
                    if(byClassContains) return byClassContains;
                    
                    var buttons = document.querySelectorAll('button, a, div[role="button"], span[role="button"]');
                    
                    for(var i=0; i<buttons.length; i++){
                        var btn = buttons[i];
                        var txt = (btn.textContent || '').trim();
                        var ariaLabel = btn.getAttribute('aria-label') || '';
                        
                        if(txt === 'Depósito' || /Dep(ó|o)sit/i.test(txt) || /Dep(ó|o)sit/i.test(ariaLabel)) {
                            return btn;
                        }
                    }
                    
                    var iconButtons = document.querySelectorAll('[class*="deposit"], [class*="recharge"], [class*="Deposit"], [class*="Recharge"]');
                    if(iconButtons.length > 0) return iconButtons[0];
                    
                    return null;
                }catch(e){ 
                    return null;
                }
            }
            
            function tryOpenDeposit(){
                setTimeout(function(){
                    var depositBtn = findDepositTriggerDeep();
                    
                    if(depositBtn){
                        try{ depositBtn.click(); }catch(e){}
                        
                        try{ 
                            depositBtn.dispatchEvent(new MouseEvent('click', {
                                bubbles:true, 
                                cancelable:true, 
                                view:window
                            })); 
                        }catch(e){}
                        
                        try{ 
                            depositBtn.dispatchEvent(new PointerEvent('click', {
                                bubbles:true, 
                                cancelable:true
                            })); 
                        }catch(e){}
                        
                        try{ 
                            depositBtn.dispatchEvent(new TouchEvent('touchstart', {
                                bubbles:true, 
                                cancelable:true
                            })); 
                        }catch(e){}
                        
                        var checkCount = 0;
                        var checkTimer = setInterval(function(){
                            checkCount++;
                            
                            var depositModalSelectors = [
                                '.ui-overlay[data-hidden="0"]', 
                                '.ui-popup:not(#welcome-modal)', 
                                '._main-recharge_1m3jl_30',
                                '[class*="recharge"]:not(#welcome-modal)',
                                '[class*="deposit"]:not(#welcome-modal)',
                                '.modal:not(#welcome-modal)',
                                '[role="dialog"]:not(#welcome-modal)'
                            ];
                            
                            var modalOpened = false;
                            for(var i=0; i<depositModalSelectors.length; i++){
                                var el = document.querySelector(depositModalSelectors[i]);
                                if(el && el.offsetParent !== null && el.id !== 'welcome-modal'){ 
                                    modalOpened = true;
                                    break; 
                                }
                            }
                            
                            if(modalOpened || checkCount >= 20){
                                clearInterval(checkTimer);
                                if(modalOpened) close();
                            }
                        }, 200);
                    } else {
                        alert('Não foi possível encontrar o botão de depósito. Por favor, clique no botão "Depósito" na barra superior.');
                    }
                }, 500);
            }
            
            function close(){
                modal.style.display='none';
                document.cookie='welcome_after_register=; path=/; max-age=0; samesite=Lax';
                window.__welcomeAfterRegister=false;
                try{ localStorage.removeItem('welcome_after_register'); }catch(_e){}
            }
            
            btnClose.addEventListener('click', function(){
                close();
            });
            
            btnApp.addEventListener('click', function(){ 
                window.location.href='/uploads/baixar.avif'; 
            });
            
            btnDeposit.addEventListener('click', function(){
                tryOpenDeposit();
            });
            
            modal.style.display='block';
        }
        
        function hasFlag(){ 
            return document.cookie.indexOf('welcome_after_register=1') !== -1;
        }
        
        window.addEventListener('welcomeModalReady', function(){
            setTimeout(openWelcome, 500);
        });
        
        if(hasFlag()){
            setTimeout(openWelcome, 1000);
        } else {
            var tries=0, limit=180;
            var timer=setInterval(function(){
                if(hasFlag()){
                    clearInterval(timer);
                    setTimeout(openWelcome, 500);
                } else if(++tries>=limit){
                    clearInterval(timer);
                }
            },1000);
        }
    })();
    </script>

    <script nomodule="">!function () { var e = document, t = e.createElement("script"); if (!("noModule" in t) && "onbeforeload" in t) { var n = !1; e.addEventListener("beforeload", (function (e) { if (e.target === t) n = !0; else if (!e.target.hasAttribute("nomodule") || !n) return; e.preventDefault() }), !0), t.type = "module", t.src = ".", e.head.appendChild(t), t.remove() } }()</script>
    <script nomodule="" crossorigin="" id=vite-legacy-polyfill
        src=/assets/theme-2/polyfills-legacy.BNCWm5KL.js></script>
    <script nomodule="" crossorigin="" id=vite-legacy-entry
        data-src=assets/theme-2/index-legacy.BkAsfoMz.js>System.import(document.getElementById("vite-legacy-entry").getAttribute("data-src"))</script>
        


</body>
</html>
