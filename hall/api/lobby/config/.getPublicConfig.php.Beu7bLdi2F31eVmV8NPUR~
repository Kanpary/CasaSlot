<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache');

define('DASH', '02071995admin');
require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../' . DASH . '/services/database.php';

$row = [];
$res = $mysqli->query("SELECT * FROM config LIMIT 1");
if ($res) $row = $res->fetch_assoc() ?: [];

$siteUrl = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');

function cfg($row, $key, $default = '') {
    return isset($row[$key]) && $row[$key] !== '' ? $row[$key] : $default;
}

$logo    = cfg($row, 'logo');
$favicon = cfg($row, 'favicon');
$imgBase = 'https://b2nsyv-7920-ppp.s3.sa-east-1.amazonaws.com/siteadmin/upload/img/';

echo json_encode([
    "code"    => 0,
    "failed"  => false,
    "msg"     => "success",
    "success" => true,
    "timestamp" => round(microtime(true) * 1000),
    "data"    => [
        "siteName"         => cfg($row, 'nome_site', 'Casino'),
        "logo"             => $logo ? $imgBase . $logo : '',
        "favicon"          => $favicon ? $imgBase . $favicon : '',
        "primaryColor"     => cfg($row, 'cor_padrao', '#0096DD'),
        "backgroundColor"  => cfg($row, 'background_padrao', '#010e24'),
        "minBet"           => (float)cfg($row, 'minplay', 1),
        "minWithdraw"      => (float)cfg($row, 'minsaque', 10),
        "maxWithdraw"      => (float)cfg($row, 'maxsaque', 2000),
        "minDeposit"       => (float)cfg($row, 'mindep', 10),
        "androidVersion"   => cfg($row, 'versao_app_android', '1.0.0'),
        "androidDownload"  => cfg($row, 'link_app_android', ''),
        "iosDownload"      => cfg($row, 'link_app_ios', ''),
        "telegram"         => cfg($row, 'telegram', ''),
        "whatsapp"         => cfg($row, 'whatsapp', ''),
        "downloadEnabled"  => (bool)cfg($row, 'baixar_ativado', 0),
        "currency"         => "BRL",
        "language"         => "pt",
        "siteUrl"          => $siteUrl,
        "customerService"  => cfg($row, 'suporte', ''),
        "marquee"          => cfg($row, 'marquee', ''),
        "jackpotEnabled"   => (bool)cfg($row, 'jackpot_ativado', 0),
    ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
