

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- Estrutura para tabela `adicao_saldo`
--

CREATE TABLE `adicao_saldo` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `valor` int(11) NOT NULL DEFAULT 0,
  `tipo` varchar(255) DEFAULT NULL,
  `data_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `email` text NOT NULL,
  `contato` text DEFAULT NULL,
  `senha` text NOT NULL,
  `nivel` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `token_recover` text DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `2fa` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `admin_users`
--

INSERT INTO `admin_users` (`id`, `nome`, `email`, `contato`, `senha`, `nivel`, `status`, `token_recover`, `avatar`, `2fa`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2a$12$bMYnrKRuejID3CUVjARXP.Dyb7xT4dlX9un07SLQmyWUoyoOHbQv2', 0, 1, NULL, NULL, '$2a$12$VaqNmVTt0Kb5snhM.7D0l.EGRfRB/0vv4XmD8z9NnrjQSj0KMJn3.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `afiliados_config`
--

CREATE TABLE `afiliados_config` (
  `id` int(11) NOT NULL,
  `cpaLvl1` decimal(10,2) DEFAULT NULL,
  `cpaLvl2` decimal(10,2) DEFAULT NULL,
  `cpaLvl3` decimal(10,2) DEFAULT NULL,
  `chanceCpa` decimal(5,2) DEFAULT NULL,
  `revShareFalso` decimal(5,2) DEFAULT NULL,
  `revShareLvl1` decimal(5,2) DEFAULT NULL,
  `revShareLvl2` decimal(5,2) DEFAULT NULL,
  `revShareLvl3` decimal(5,2) DEFAULT NULL,
  `minDepForCpa` decimal(10,2) DEFAULT NULL,
  `minResgate` decimal(10,2) DEFAULT NULL,
  `pagar_baus` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `afiliados_config`
--

INSERT INTO `afiliados_config` (`id`, `cpaLvl1`, `cpaLvl2`, `cpaLvl3`, `chanceCpa`, `revShareFalso`, `revShareLvl1`, `revShareLvl2`, `revShareLvl3`, `minDepForCpa`, `minResgate`, `pagar_baus`) VALUES
(1, 50.00, 10.00, 10.00, 100.00, 0.00, 0.00, 0.00, 0.00, 20.00, 200.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `aurenpay`
--

CREATE TABLE `aurenpay` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `client_id` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `atualizado` varchar(45) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `aurenpay`
--

INSERT INTO `aurenpay` (`id`, `url`, `client_id`, `client_secret`, `atualizado`, `ativo`) VALUES
(1, 'https://api.aurenpay.com', 'Client ID', 'Client Secret', NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `banner`
--

CREATE TABLE `banner` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `img` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `banner`
--

INSERT INTO `banner` (`id`, `titulo`, `criado_em`, `img`, `status`) VALUES
(1, 'Banner 1', '2024-06-28 21:10:47', '1765901030_imgi_52_1764938046_imgi_50_1992362735277334530.avif', 1),
(2, 'Banner 2', '2024-06-28 21:08:02', '1765901037_imgi_52_1764938046_imgi_50_1992362735277334530.avif', 1),
(3, 'Banner 3', '2024-06-28 21:08:02', '1765901049_imgi_52_1764938046_imgi_50_1992362735277334530.avif', 1),
(4, 'Banner 4', '2024-06-28 21:08:02', '1765829375_imgi_54_1793901527754293250.avif', 0),
(5, 'Banner 5', '2024-06-28 21:08:02', '1748550977_banner2.png', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `bau`
--

CREATE TABLE `bau` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `num` text DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `is_get` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `bau`
--

INSERT INTO `bau` (`id`, `id_user`, `num`, `status`, `token`, `is_get`) VALUES
(328, NULL, '', 'user novo', '994745c16ecadd92b4716e98b88934e3', 0),
(329, NULL, '', 'user novo', '190674a1ff9ac3384d904274da1962eb', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `bspay`
--

CREATE TABLE `bspay` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `client_id` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `atualizado` varchar(45) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL,
  `invite_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `bspay`
--

INSERT INTO `bspay` (`id`, `url`, `client_id`, `client_secret`, `atualizado`, `ativo`, `invite_code`) VALUES
(1, 'https://ggpixapi.com/api/v1', '', '', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `nome_site` text DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `grupoplataforma` varchar(255) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `download` varchar(255) DEFAULT NULL,
  `icone_download` varchar(255) DEFAULT NULL,
  `telegram` text DEFAULT NULL,
  `instagram` text DEFAULT NULL,
  `whatsapp` text DEFAULT NULL,
  `suporte` text DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `sublogo` text DEFAULT NULL,
  `facebookads` text DEFAULT NULL,
  `rodapelogo` text DEFAULT NULL,
  `favicon` text DEFAULT NULL,
  `googleAnalytics` text DEFAULT NULL,
  `minplay` int(11) DEFAULT NULL,
  `minsaque` double DEFAULT NULL,
  `maxsaque` int(11) DEFAULT 1000,
  `saque_automatico` int(11) NOT NULL,
  `rollover` int(11) DEFAULT NULL,
  `mindep` text DEFAULT NULL,
  `jackpot` int(11) DEFAULT NULL,
  `navbar` int(11) DEFAULT NULL,
  `numero_jackpot` int(11) DEFAULT NULL,
  `jackpot_custom` text DEFAULT NULL,
  `cor_padrao` varchar(45) NOT NULL,
  `background_padrao` varchar(50) DEFAULT NULL,
  `custom_css` longtext NOT NULL,
  `texto` varchar(45) NOT NULL,
  `img_seo` text DEFAULT NULL,
  `keyword` text DEFAULT NULL,
  `marquee` text DEFAULT NULL,
  `status_topheader` int(11) NOT NULL DEFAULT 0,
  `cor_topheader` varchar(48) DEFAULT '#ed1c24',
  `niveisbau` text DEFAULT NULL,
  `qntsbaus` int(11) DEFAULT NULL,
  `nvlbau` int(11) DEFAULT NULL,
  `pessoasbau` int(11) DEFAULT NULL,
  `tema` int(11) DEFAULT NULL,
  `versao_app_android` text DEFAULT NULL,
  `versao_app_ios` text DEFAULT NULL,
  `mensagem_app` text DEFAULT NULL,
  `link_app_android` text DEFAULT NULL,
  `link_app_ios` text DEFAULT NULL,
  `broadcast` text DEFAULT NULL,
  `limite_saque` int(11) DEFAULT 0,
  `sort_jackpot` int(11) DEFAULT 1,
  `carregamento_img` varchar(255) DEFAULT NULL,
  `imagem_fundo` text DEFAULT NULL,
  `snow_flakes` text DEFAULT NULL,
  `painel_rolante` text DEFAULT NULL,
  `atendimento` text DEFAULT NULL,
  `jackpot_ativado` int(11) NOT NULL DEFAULT 1,
  `limite_de_chaves` int(11) NOT NULL DEFAULT 1,
  `facebook` varchar(255) DEFAULT NULL,
  `baixar_ativado` int(11) DEFAULT NULL,
  `topIconColor` varchar(255) DEFAULT NULL,
  `topBgColor` varchar(255) DEFAULT NULL,
  `tema_popup_inicio` int(11) NOT NULL DEFAULT 1,
  `slogan` text DEFAULT NULL,
  `comissao_gerente` varchar(255) DEFAULT NULL,
  `google_client_id` varchar(255) DEFAULT NULL,
  `google_client_secret` varchar(255) DEFAULT NULL,
  `google_login_status` int(1) DEFAULT 0,
  `menu_navbar_ativo` tinyint(1) NOT NULL DEFAULT 1,
  `natal_theme_active` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `config`
--

INSERT INTO `config` (`id`, `nome`, `nome_site`, `descricao`, `grupoplataforma`, `logo`, `avatar`, `download`, `icone_download`, `telegram`, `instagram`, `whatsapp`, `suporte`, `email`, `sublogo`, `facebookads`, `rodapelogo`, `favicon`, `googleAnalytics`, `minplay`, `minsaque`, `maxsaque`, `saque_automatico`, `rollover`, `mindep`, `jackpot`, `navbar`, `numero_jackpot`, `jackpot_custom`, `cor_padrao`, `background_padrao`, `custom_css`, `texto`, `img_seo`, `keyword`, `marquee`, `status_topheader`, `cor_topheader`, `niveisbau`, `qntsbaus`, `nvlbau`, `pessoasbau`, `tema`, `versao_app_android`, `versao_app_ios`, `mensagem_app`, `link_app_android`, `link_app_ios`, `broadcast`, `limite_saque`, `sort_jackpot`, `carregamento_img`, `imagem_fundo`, `snow_flakes`, `painel_rolante`, `atendimento`, `jackpot_ativado`, `limite_de_chaves`, `facebook`, `baixar_ativado`, `topIconColor`, `topBgColor`, `tema_popup_inicio`, `slogan`, `comissao_gerente`, `google_client_id`, `google_client_secret`, `google_login_status`, `menu_navbar_ativo`, `natal_theme_active`) VALUES
(1, 'W1-BMXPG', '', 'Plataforma de entretenimento digital com sistema de sorteios e prêmios regulamentados.', 'Grupo G10', 'img_6933114b81ab48.05623573.avif', 'img_691a00e0a64526.45519973.png', 'img_69331174c51e88.69663002.avif', 'img_691a00e0a63db3.64271709.png', 'https://telegram.me/', '', 'https://telegram.me/', NULL, '', '', 'ID AQUI', NULL, 'img_6933123ed6e028.62533881.png', 'ID AQUI', 1, 10, 2000, 10, 10, '10', 0, 1, 2, 'jackpot_692de48d801b4.avif', '#0096DD', '#010e24', '', '', '154504365733.png', 'tigrinho pagante, jogo estilo tigrinho, jogo tipo tigrinho que paga, chinesa pagante, slot pagante, slots online pagantes, plataforma pagante, plataforma que paga no pix, jogo pagante 2025, caça-níquel pagante, jogo de prêmio online, jogo que paga via pix, jogo que paga de verdade, site que paga no pix, jogos online que pagam, plataforma de prêmios, slots brasileiros pagantes, slots confiáveis, jogo parecido com tigrinho, tigrinho alternativo, jogo de prêmios instantâneos, jogo de celular que paga, app que paga via pix, jogo rápido que paga, ganhar dinheiro jogando, jogo de prêmios reais, plataforma de slots pagantes, slot da chinesa, chinesinha pagante, site de prêmios via pix, plataforma pix pagante, saque rápido pix, depósito via pix instantâneo, jogos virais que pagam, plataforma pixsorte, pixsorte pagante, pixsorte confiável, pixsorte funciona', '✈️✈️ Clique no canal:  Telegram📱📱Baixe o site oficial do APP:  teste.com ❤️Bônus de primeiro depósito para novos usuários R$3777❤️🎁B6nus de convite: R$ 10 por pessoa🎁🎁Convide amigos, compartilhe e ganhe dinheiro! Comissao até 2%🎁Tempo de chuva do envelope: AS00H 15H 20H E22H🎁👉👉Passo a passo: Visite → Eventos/Promog6es → Indique um amigo → Receba/Recolha tudo.', 0, '#0096dd', '10,15,20,25,30,35,40,45,50', 50, 5, 1, 21, '1.0.0.1', '1.0.0.2', 'MENSAGEM POPUP', 'https://google.com/', 'https://google.com/', 'PHILLYPS LINDO', 10, 4, 'img_693312273cfea7.12146031.avif', 'img_67e3328b16f8f2.80383522.png', 'img_68f6ca9661a404.35196359.png', '', 'https://telegram.me/', 1, 2, '', 0, '', '#252E3B', 1, '<p><span style=\\\"color: #e03e2d;\\\">Cadastre-se e ganhe R$8.888</span><br><span style=\\\"color: #e03e2d;\\\">Convide outras pessoas e ganhe R$ 1 milh&atilde;o!</span></p>', '2', NULL, NULL, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cupom`
--

CREATE TABLE `cupom` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `valor` int(11) NOT NULL,
  `qtd_insert` int(11) NOT NULL DEFAULT 0 COMMENT 'Quantidade inicial de cupons criados',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: inativo / 1: ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cupom`
--

INSERT INTO `cupom` (`id`, `nome`, `valor`, `qtd_insert`, `status`) VALUES
(1, 'DEPÓSITO DE 10000', 10000, 1000, 1),
(2, 'DEPÓSITO DE 5000', 5000, 500, 1),
(3, 'DEPÓSITO DE 3000', 3000, 300, 1),
(4, 'DEPÓSITO DE 1000', 1000, 100, 1),
(5, 'DEPÓSITO DE 500', 500, 50, 1),
(6, 'DEPÓSITO DE 100', 100, 10, 1),
(7, 'DEPÓSITO DE 50', 50, 5, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `cupom_usados`
--

CREATE TABLE `cupom_usados` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `valor` int(11) NOT NULL COMMENT 'Valor do depósito que gerou o bônus',
  `bonus` int(11) NOT NULL DEFAULT 0 COMMENT 'Valor do bônus recebido',
  `data_registro` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `customer_feedback`
--

CREATE TABLE `customer_feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` tinyint(4) NOT NULL,
  `content` text NOT NULL,
  `file_link` varchar(255) DEFAULT NULL,
  `source` varchar(50) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `reply` text DEFAULT NULL,
  `reply_time` datetime DEFAULT NULL,
  `reply_read` tinyint(1) DEFAULT 0,
  `reply_by` int(11) DEFAULT NULL,
  `bonus_amount` decimal(10,2) DEFAULT 0.00,
  `bonus_received` tinyint(1) DEFAULT 0,
  `bonus_received_at` datetime DEFAULT NULL,
  `client_time` bigint(20) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `drakon`
--

CREATE TABLE `drakon` (
  `id` int(11) NOT NULL,
  `agent_code` varchar(64) NOT NULL,
  `agent_token` varchar(128) NOT NULL,
  `agent_secret_key` varchar(128) NOT NULL,
  `api_base` varchar(255) NOT NULL DEFAULT 'https://gator.drakon.casino/api/v1',
  `ativo` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `drakon`
--

INSERT INTO `drakon` (`id`, `agent_code`, `agent_token`, `agent_secret_key`, `api_base`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 'Agent Code', 'Agent Token', 'Agent Secret', 'https://gator.drakon.casino/api/v1', 1, '2025-11-13 16:19:19', '2025-11-15 19:44:09');

-- --------------------------------------------------------

--
-- Estrutura para tabela `expfypay`
--

CREATE TABLE `expfypay` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `client_id` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `atualizado` varchar(45) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `expfypay`
--

INSERT INTO `expfypay` (`id`, `url`, `client_id`, `client_secret`, `atualizado`, `ativo`) VALUES
(1, 'https://pro.expfypay.com', 'Client ID', 'Client Secret', '0', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `festival`
--

CREATE TABLE `festival` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `img` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `festival`
--

INSERT INTO `festival` (`id`, `titulo`, `criado_em`, `img`, `status`) VALUES
(1, 'Festival 1', '2025-03-25 20:58:37', '/holiday/14/apng_top_jr.png', 1),
(2, 'Festival 2', '2025-03-25 20:58:37', '/holiday/14/btn_zc1_jr.avif', 0),
(3, 'Festival 3', '2025-03-25 20:58:54', '/holiday/14/btn_zc1_jr2.avif', 1),
(4, 'Festival 4', '2025-03-25 20:59:06', '/holiday/14/h5_zs_jr.avif', 1),
(5, 'Festival 5', '2025-03-25 20:59:16', '/holiday/14/h5_zs_jr2.avif', 1),
(6, 'Festival 6', '2025-03-25 20:59:25', '/holiday/14/h5_zs_jr3.avif', 1),
(7, 'Festival 7', '2025-03-25 20:59:34', '/holiday/14/icon_btm_jr.avif', 1),
(8, 'Festival 8', '2025-03-25 20:59:47', '/holiday/14/icon_btm_jr2.avif', 1),
(9, 'Festival 9', '2025-03-25 20:59:59', '/holiday/14/icon_btm_jr3.avif', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `financeiro`
--

CREATE TABLE `financeiro` (
  `id` int(11) NOT NULL,
  `usuario` int(11) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `bonus` decimal(10,2) DEFAULT NULL,
  `saldo_afiliados` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `floats`
--

CREATE TABLE `floats` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `redirect` text DEFAULT NULL,
  `tipo` int(11) NOT NULL DEFAULT 0,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `img` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `floats`
--

INSERT INTO `floats` (`id`, `titulo`, `redirect`, `tipo`, `criado_em`, `img`, `status`) VALUES
(1, 'telegram', 'https://t.me/', 0, '2024-06-28 21:10:47', '1751754375_1748553603_31699a44-b7df-45c3-af88-67a8470823a6.gif', 1),
(2, 'Recomend....', 'https://salmaopg.com/home/promote?active=promoteShare', 1, '2024-06-28 21:08:02', '1747774137_ActiveImg8075480511658811.gif', 1),
(3, 'Float 3', 'https://t.me/', 0, '2024-06-28 21:08:02', '1747774181_ActiveImg8087241140451735.png', 0),
(4, 'Recomend ami....', 'https://t.me/', 0, '2024-06-28 21:08:02', '1751754330_1747774137_ActiveImg8075480511658811 (1).gif', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `game_code` text NOT NULL,
  `game_name` text NOT NULL,
  `banner` text DEFAULT NULL,
  `status` int(11) NOT NULL,
  `provider` text DEFAULT NULL,
  `popular` int(11) NOT NULL DEFAULT 0,
  `type` text DEFAULT NULL,
  `game_type` varchar(255) DEFAULT NULL,
  `api` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `games`
--

INSERT INTO `games` (`id`, `game_code`, `game_name`, `banner`, `status`, `provider`, `popular`, `type`, `game_type`, `api`) VALUES
(9999999, 'canvas-slot', 'Fortune Tiger Canvas', '/slot_canvas/banner.png', 1, 'CanvasSlot', 1, 'slot', '1', 'CanvasSlot'),
(9000001, 'slotopol-aztec', 'Aztec Coins', '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
(9000002, 'slotopol-book', 'Book of Ra', '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
(9000003, 'slotopol-monkey', 'Crazy Monkey', '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
(9000004, 'slotopol-fruit', 'Fruit Cocktail', '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
(9000005, 'slotopol-garage', 'Garage', '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
(9000006, 'slotopol-haunter', 'Lucky Haunter', '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
(9000007, 'slotopol-resident', 'Resident', '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
(9000008, 'slotopol-shaman', 'Shaman', '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol'),
(9000009, 'slotopol-sweet', 'Sweet Life', '/slot_canvas/banner.png', 1, 'Slotopol', 1, 'slot', '1', 'Slotopol');


-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_play`
--

CREATE TABLE `historico_play` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nome_game` text NOT NULL,
  `bet_money` decimal(10,2) NOT NULL DEFAULT 0.00,
  `win_money` decimal(10,2) NOT NULL DEFAULT 0.00,
  `txn_id` text NOT NULL,
  `created_at` datetime NOT NULL,
  `status_play` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_vip`
--

CREATE TABLE `historico_vip` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `bonus` float NOT NULL,
  `data` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `igamewin`
--

CREATE TABLE `igamewin` (
  `id` int(11) NOT NULL,
  `agent_code` varchar(255) NOT NULL,
  `agent_token` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT 'https://api.igamewin.com',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `rtp` int(11) DEFAULT 92
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `igamewin`
--

INSERT INTO `igamewin` (`id`, `agent_code`, `agent_token`, `url`, `ativo`, `rtp`) VALUES
(1, 'Agent Code', 'Agent Token', 'https://igamewin.com/api/v1', 1, 50);

-- --------------------------------------------------------

--
-- Estrutura para tabela `lobby_pgsoft`
--

CREATE TABLE `lobby_pgsoft` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `saldo` decimal(11,2) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `data_registro` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `lobby_pgsoft`
--

INSERT INTO `lobby_pgsoft` (`id`, `id_user`, `saldo`, `tipo`, `data_registro`) VALUES
(4128, 666511294, 0.00, 'saida', '2025-12-09 17:33:41'),
(4129, 666511294, 0.00, 'saida', '2025-12-09 17:33:42'),
(4130, 666511294, 0.00, 'saida', '2025-12-09 17:43:03'),
(4131, 666511294, 0.00, 'saida', '2025-12-09 17:50:28'),
(4132, 666511294, 0.00, 'saida', '2025-12-15 17:12:15'),
(4133, 666511294, 0.00, 'saida', '2025-12-15 17:13:08'),
(4134, 666511294, 0.00, 'saida', '2025-12-15 17:14:01'),
(4135, 666511294, 0.00, 'saida', '2025-12-15 17:36:58'),
(4136, 666511294, 0.00, 'saida', '2025-12-18 18:33:27'),
(4137, 581585215, 0.00, 'saida', '2025-12-18 18:40:02'),
(4138, 581585215, 0.00, 'saida', '2025-12-18 18:40:03'),
(4139, 581585215, 0.00, 'saida', '2025-12-18 18:44:14'),
(4140, 951922589, 0.00, 'saida', '2025-12-18 18:45:21'),
(4141, 951922589, 0.00, 'saida', '2025-12-18 18:45:22'),
(4142, 951922589, 0.00, 'saida', '2025-12-18 18:45:48'),
(4143, 428033206, 0.00, 'saida', '2025-12-18 18:49:27'),
(4144, 428033206, 0.00, 'saida', '2025-12-18 18:49:28'),
(4145, 286396873, 0.00, 'saida', '2025-12-18 18:50:21'),
(4146, 286396873, 0.00, 'saida', '2025-12-18 18:50:22'),
(4147, 376514101, 0.00, 'saida', '2025-12-18 18:51:59'),
(4148, 376514101, 0.00, 'saida', '2025-12-18 18:52:00'),
(4149, 721374918, 0.00, 'saida', '2025-12-18 18:53:18'),
(4150, 721374918, 0.00, 'saida', '2025-12-18 18:53:19'),
(4151, 879161423, 0.00, 'saida', '2025-12-18 18:54:00'),
(4152, 879161423, 0.00, 'saida', '2025-12-18 18:54:02'),
(4153, 879161423, 0.00, 'saida', '2025-12-18 18:55:40'),
(4154, 997813060, 0.00, 'saida', '2025-12-18 18:55:56'),
(4155, 997813060, 0.00, 'saida', '2025-12-18 18:55:58'),
(4156, 308654911, 0.00, 'saida', '2025-12-18 19:00:17'),
(4157, 308654911, 0.00, 'saida', '2025-12-18 19:00:19'),
(4158, 585550085, 0.00, 'saida', '2025-12-18 19:01:23'),
(4159, 585550085, 0.00, 'saida', '2025-12-18 19:01:24'),
(4160, 585550085, 0.00, 'saida', '2025-12-18 19:01:27'),
(4161, 585550085, 0.00, 'saida', '2025-12-18 19:02:25'),
(4162, 585550085, 0.00, 'saida', '2025-12-18 19:07:39'),
(4163, 585550085, 0.00, 'saida', '2025-12-18 19:09:30'),
(4164, 585550085, 0.00, 'saida', '2025-12-18 19:10:54'),
(4165, 585550085, 0.00, 'saida', '2025-12-18 19:14:56'),
(4166, 585550085, 0.00, 'saida', '2025-12-18 19:17:05'),
(4167, 585550085, 0.00, 'saida', '2025-12-18 19:19:04'),
(4168, 585550085, 0.00, 'saida', '2025-12-18 19:22:27'),
(4169, 585550085, 0.00, 'saida', '2025-12-18 19:24:24'),
(4170, 585550085, 0.00, 'saida', '2025-12-18 19:26:14'),
(4171, 585550085, 0.00, 'saida', '2025-12-18 19:27:25'),
(4172, 585550085, 0.00, 'saida', '2025-12-18 19:28:04'),
(4173, 585550085, 0.00, 'saida', '2025-12-18 19:29:50'),
(4174, 585550085, 0.00, 'saida', '2025-12-18 19:32:11'),
(4175, 585550085, 0.00, 'saida', '2025-12-18 19:33:35'),
(4176, 585550085, 0.00, 'saida', '2025-12-18 19:33:53'),
(4177, 585550085, 0.00, 'saida', '2025-12-18 19:34:48'),
(4178, 585550085, 0.00, 'saida', '2025-12-18 19:37:22'),
(4179, 585550085, 0.00, 'saida', '2025-12-18 19:37:29'),
(4180, 585550085, 0.00, 'saida', '2025-12-18 19:39:13'),
(4181, 585550085, 0.00, 'saida', '2025-12-18 19:46:05'),
(4182, 585550085, 0.00, 'saida', '2025-12-18 19:48:00'),
(4183, 467246297, 0.00, 'saida', '2025-12-18 19:48:21'),
(4184, 467246297, 0.00, 'saida', '2025-12-18 19:48:22'),
(4185, 467246297, 0.00, 'saida', '2025-12-18 19:49:07'),
(4186, 467246297, 0.00, 'saida', '2025-12-18 19:49:11'),
(4187, 467246297, 0.00, 'saida', '2025-12-18 19:51:05'),
(4188, 467246297, 0.00, 'saida', '2025-12-18 19:51:39'),
(4189, 467246297, 0.00, 'saida', '2025-12-18 19:52:43'),
(4190, 467246297, 0.00, 'saida', '2025-12-18 20:14:18'),
(4191, 666511294, 0.00, 'saida', '2025-12-19 14:29:07'),
(4192, 666511294, 100.00, 'saida', '2025-12-19 14:29:29'),
(4193, 666511294, -100.00, 'entrada', '2025-12-19 14:29:31'),
(4194, 666511294, 100.00, 'saida', '2025-12-19 14:29:50'),
(4195, 666511294, -100.00, 'entrada', '2025-12-19 14:29:52'),
(4196, 666511294, 100.00, 'saida', '2025-12-19 14:30:07'),
(4197, 666511294, -100.00, 'entrada', '2025-12-19 14:30:09'),
(4198, 666511294, 100.00, 'saida', '2025-12-19 14:31:59'),
(4199, 666511294, -100.00, 'entrada', '2025-12-19 14:32:03'),
(4200, 666511294, 100.00, 'saida', '2025-12-19 14:32:13'),
(4201, 666511294, 100.00, 'saida', '2025-12-19 14:32:24'),
(4202, 666511294, -100.00, 'entrada', '2025-12-19 14:32:54'),
(4203, 666511294, 100.00, 'saida', '2025-12-19 14:33:08'),
(4204, 666511294, -100.00, 'entrada', '2025-12-19 14:33:10'),
(4205, 666511294, 100.00, 'saida', '2025-12-19 14:33:25'),
(4206, 666511294, -98.00, 'entrada', '2025-12-19 14:33:27'),
(4207, 666511294, 98.00, 'saida', '2025-12-19 14:33:34'),
(4208, 666511294, -98.00, 'entrada', '2025-12-19 14:33:36'),
(4209, 666511294, 98.00, 'saida', '2025-12-19 14:33:54'),
(4210, 666511294, 98.00, 'saida', '2025-12-19 14:33:57'),
(4211, 666511294, -98.00, 'entrada', '2025-12-19 14:34:01'),
(4212, 666511294, 86.00, 'saida', '2025-12-19 14:35:35'),
(4213, 666511294, -86.00, 'entrada', '2025-12-19 14:36:07'),
(4214, 666511294, 86.00, 'saida', '2025-12-19 14:36:14'),
(4215, 666511294, -86.00, 'entrada', '2025-12-19 14:36:16'),
(4216, 666511294, 150.16, 'saida', '2025-12-19 14:39:41'),
(4217, 666511294, 150.16, 'saida', '2025-12-24 11:37:42'),
(4218, 666511294, 150.16, 'saida', '2025-12-24 11:41:47'),
(4219, 666511294, 150.16, 'saida', '2025-12-24 11:42:24'),
(4220, 666511294, 150.16, 'saida', '2025-12-24 11:42:51'),
(4221, 666511294, 150.16, 'saida', '2025-12-24 12:08:49'),
(4222, 666511294, 150.16, 'saida', '2025-12-24 15:12:23'),
(4223, 666511294, 150.16, 'saida', '2025-12-24 15:15:16'),
(4224, 666511294, -150.16, 'entrada', '2025-12-24 15:15:18'),
(4225, 666511294, 150.16, 'saida', '2025-12-24 15:15:40'),
(4226, 666511294, 150.16, 'saida', '2025-12-24 15:15:42'),
(4227, 666511294, 150.16, 'saida', '2025-12-24 15:15:42'),
(4228, 666511294, 150.16, 'saida', '2025-12-24 15:15:43'),
(4229, 666511294, -150.16, 'entrada', '2025-12-24 15:15:44'),
(4230, 666511294, 150.16, 'saida', '2025-12-24 15:15:58'),
(4231, 666511294, -150.16, 'entrada', '2025-12-24 15:19:20'),
(4232, 666511294, 150.16, 'saida', '2025-12-24 15:19:39'),
(4233, 666511294, 150.16, 'saida', '2025-12-24 15:27:17'),
(4234, 666511294, 150.16, 'saida', '2025-12-24 15:27:19'),
(4235, 666511294, -150.16, 'entrada', '2025-12-24 15:27:20'),
(4236, 666511294, 148.56, 'saida', '2025-12-24 15:27:47'),
(4237, 666511294, -148.56, 'entrada', '2025-12-24 15:31:19'),
(4238, 666511294, 148.56, 'saida', '2025-12-24 15:31:32'),
(4239, 666511294, -148.56, 'entrada', '2025-12-24 15:31:34'),
(4240, 666511294, 144.56, 'saida', '2025-12-24 15:31:45'),
(4241, 666511294, -144.56, 'entrada', '2025-12-24 16:13:36'),
(4242, 666511294, 1623.56, 'saida', '2025-12-24 16:14:00'),
(4243, 666511294, 1623.56, 'saida', '2025-12-24 16:40:22'),
(4244, 666511294, 1623.56, 'saida', '2025-12-24 16:41:33'),
(4245, 666511294, -1623.56, 'entrada', '2025-12-24 16:41:35'),
(4246, 666511294, 1637.96, 'saida', '2025-12-24 17:38:33'),
(4247, 666511294, -1637.96, 'entrada', '2025-12-24 17:42:21'),
(4248, 666511294, 3088.44, 'saida', '2025-12-24 17:42:47'),
(4249, 666511294, -3088.44, 'entrada', '2025-12-24 17:43:55'),
(4250, 666511294, 3088.44, 'saida', '2025-12-24 17:44:00'),
(4251, 666511294, 3088.44, 'saida', '2025-12-24 17:44:02'),
(4252, 666511294, 3088.44, 'saida', '2025-12-24 17:44:12'),
(4253, 666511294, 3088.44, 'saida', '2025-12-24 17:58:10'),
(4254, 666511294, -3088.44, 'entrada', '2025-12-24 17:58:11'),
(4255, 666511294, 3139.08, 'saida', '2025-12-24 18:02:09'),
(4256, 666511294, -3139.08, 'entrada', '2025-12-24 18:02:11'),
(4257, 666511294, 3105.48, 'saida', '2025-12-24 18:02:54'),
(4258, 666511294, -3105.48, 'entrada', '2025-12-24 18:02:56'),
(4259, 666511294, 2598.48, 'saida', '2025-12-24 18:08:59'),
(4260, 666511294, -2598.48, 'entrada', '2025-12-24 18:10:51'),
(4261, 666511294, 2346.48, 'saida', '2025-12-24 18:11:17'),
(4262, 666511294, -2346.48, 'entrada', '2025-12-24 18:11:18'),
(4263, 666511294, 2346.48, 'saida', '2025-12-24 18:13:21'),
(4264, 666511294, -2346.48, 'entrada', '2025-12-24 18:13:52'),
(4265, 666511294, 2286.48, 'saida', '2025-12-24 18:18:50'),
(4266, 666511294, -2286.48, 'entrada', '2025-12-24 18:18:52'),
(4267, 666511294, 2286.48, 'saida', '2025-12-24 18:18:57'),
(4268, 666511294, -2286.48, 'entrada', '2025-12-24 18:18:59'),
(4269, 666511294, 2286.48, 'saida', '2025-12-24 18:19:14'),
(4270, 666511294, -2286.48, 'entrada', '2025-12-24 18:19:23'),
(4271, 666511294, 2135.08, 'saida', '2025-12-24 18:20:04'),
(4272, 666511294, -2135.08, 'entrada', '2025-12-24 18:20:06'),
(4273, 666511294, 2111.08, 'saida', '2025-12-24 18:24:15'),
(4274, 666511294, -2111.08, 'entrada', '2025-12-24 18:24:17'),
(4275, 666511294, 2111.08, 'saida', '2025-12-24 18:24:22'),
(4276, 666511294, -2111.08, 'entrada', '2025-12-24 18:24:27'),
(4277, 666511294, 2109.08, 'saida', '2025-12-24 18:28:19'),
(4278, 666511294, -2109.08, 'entrada', '2025-12-24 18:28:21'),
(4279, 666511294, 2109.08, 'saida', '2025-12-24 18:28:23'),
(4280, 666511294, -2109.08, 'entrada', '2025-12-24 18:28:52'),
(4281, 666511294, 2107.48, 'saida', '2025-12-24 18:29:01'),
(4282, 666511294, -2107.48, 'entrada', '2025-12-24 18:33:04'),
(4283, 666511294, 2095.48, 'saida', '2025-12-24 18:33:15'),
(4284, 666511294, -2095.48, 'entrada', '2025-12-24 18:36:01'),
(4285, 666511294, 2089.48, 'saida', '2025-12-24 18:38:33'),
(4286, 666511294, -2089.48, 'entrada', '2025-12-24 18:38:34'),
(4287, 666511294, 2084.28, 'saida', '2025-12-24 18:39:12'),
(4288, 666511294, -2084.28, 'entrada', '2025-12-24 18:39:36'),
(4289, 666511294, 2074.28, 'saida', '2025-12-24 18:40:11'),
(4290, 666511294, -2074.28, 'entrada', '2025-12-24 18:40:13'),
(4291, 666511294, 2062.28, 'saida', '2025-12-24 18:41:13'),
(4292, 666511294, -2062.28, 'entrada', '2025-12-24 18:41:17'),
(4293, 666511294, 2062.28, 'saida', '2025-12-24 18:41:22'),
(4294, 666511294, -2062.28, 'entrada', '2025-12-24 18:41:24'),
(4295, 666511294, 2043.08, 'saida', '2025-12-24 18:42:02'),
(4296, 666511294, -2043.08, 'entrada', '2025-12-24 18:42:04'),
(4297, 666511294, 2030.76, 'saida', '2025-12-24 18:42:40'),
(4298, 666511294, -2030.76, 'entrada', '2025-12-24 18:42:42'),
(4299, 666511294, 2233.80, 'saida', '2025-12-24 18:44:22'),
(4300, 666511294, -2233.80, 'entrada', '2025-12-24 18:44:24'),
(4301, 666511294, 1885.40, 'saida', '2025-12-24 18:46:06'),
(4302, 666511294, -1885.40, 'entrada', '2025-12-24 18:46:45'),
(4303, 666511294, 1827.40, 'saida', '2025-12-24 18:47:19'),
(4304, 666511294, -1827.40, 'entrada', '2025-12-24 19:02:53'),
(4305, 666511294, 1528.60, 'saida', '2025-12-24 19:08:25'),
(4306, 666511294, -1528.60, 'entrada', '2025-12-24 19:09:23'),
(4307, 666511294, 1813.40, 'saida', '2025-12-24 19:16:02'),
(4308, 666511294, -1813.40, 'entrada', '2025-12-24 19:17:43'),
(4309, 666511294, 1835.90, 'saida', '2025-12-24 19:18:30'),
(4310, 666511294, -1835.90, 'entrada', '2025-12-24 19:36:01'),
(4311, 666511294, 1835.90, 'saida', '2025-12-24 19:36:03'),
(4312, 666511294, -1835.90, 'entrada', '2025-12-24 19:38:16'),
(4313, 666511294, 1834.62, 'saida', '2025-12-24 19:38:32'),
(4314, 340909423, 0.00, 'saida', '2025-12-24 19:40:09'),
(4315, 340909423, 0.00, 'saida', '2025-12-24 19:40:10'),
(4316, 340909423, 100.00, 'saida', '2025-12-24 19:40:28'),
(4317, 340909423, -100.00, 'entrada', '2025-12-24 19:40:29'),
(4318, 340909423, 60.00, 'saida', '2025-12-24 19:48:59'),
(4319, 351335686, 0.00, 'saida', '2025-12-24 19:49:18'),
(4320, 351335686, 0.00, 'saida', '2025-12-24 19:49:19'),
(4321, 351335686, 50.00, 'saida', '2025-12-24 19:49:46'),
(4322, 351335686, -50.00, 'entrada', '2025-12-24 19:49:47'),
(4323, 351335686, 16.00, 'saida', '2025-12-24 19:50:22'),
(4324, 351335686, -16.00, 'entrada', '2025-12-24 19:50:24'),
(4325, 351335686, 8.96, 'saida', '2025-12-24 19:57:50'),
(4326, 351335686, -8.96, 'entrada', '2025-12-24 19:57:53'),
(4327, 667502611, 0.00, 'saida', '2025-12-24 20:02:08'),
(4328, 667502611, 0.00, 'saida', '2025-12-24 20:02:11'),
(4329, 667502611, 100.00, 'saida', '2025-12-24 20:02:27'),
(4330, 667502611, -100.00, 'entrada', '2025-12-24 20:02:34'),
(4331, 667502611, 92.00, 'saida', '2025-12-24 20:03:22'),
(4332, 667502611, -92.00, 'entrada', '2025-12-24 20:03:52'),
(4333, 667502611, 92.00, 'saida', '2025-12-24 20:04:04'),
(4334, 667502611, -92.00, 'entrada', '2025-12-24 20:04:06'),
(4335, 667502611, 80.80, 'saida', '2025-12-24 20:04:37'),
(4336, 667502611, -80.80, 'entrada', '2025-12-24 20:05:56'),
(4337, 220880806, 0.00, 'saida', '2025-12-24 20:07:22'),
(4338, 220880806, 0.00, 'saida', '2025-12-24 20:07:24'),
(4339, 220880806, 100.00, 'saida', '2025-12-24 20:07:49'),
(4340, 220880806, -100.00, 'entrada', '2025-12-24 20:07:51'),
(4341, 220880806, 60.00, 'saida', '2025-12-24 20:08:28'),
(4342, 220880806, -60.00, 'entrada', '2025-12-24 20:08:29'),
(4343, 220880806, 31.60, 'saida', '2025-12-24 20:09:28'),
(4344, 220880806, -31.60, 'entrada', '2025-12-24 20:09:30'),
(4345, 220880806, 28.40, 'saida', '2025-12-24 20:10:23'),
(4346, 502298627, 0.00, 'saida', '2025-12-24 20:13:36'),
(4347, 502298627, 0.00, 'saida', '2025-12-24 20:13:38'),
(4348, 502298627, 0.00, 'saida', '2025-12-24 20:13:40'),
(4349, 502298627, 100.00, 'saida', '2025-12-24 20:14:26'),
(4350, 502298627, -100.00, 'entrada', '2025-12-24 20:14:27'),
(4351, 502298627, 100.00, 'saida', '2025-12-24 20:14:32'),
(4352, 502298627, -100.00, 'entrada', '2025-12-24 20:14:34'),
(4353, 502298627, 108.48, 'saida', '2025-12-24 20:15:46'),
(4354, 502298627, -108.48, 'entrada', '2025-12-24 20:15:47'),
(4355, 502298627, 100.08, 'saida', '2025-12-24 20:16:17'),
(4356, 502298627, 100.08, 'saida', '2025-12-24 20:16:23'),
(4357, 502298627, -100.08, 'entrada', '2025-12-24 20:24:43'),
(4358, 502298627, 83.08, 'saida', '2025-12-24 20:26:20'),
(4359, 502298627, -83.08, 'entrada', '2025-12-24 20:26:22'),
(4360, 666511294, 1834.62, 'saida', '2025-12-25 10:48:16'),
(4361, 666511294, -1834.62, 'entrada', '2025-12-25 10:48:24'),
(4362, 666511294, 1823.82, 'saida', '2025-12-25 10:49:29'),
(4363, 666511294, 1823.82, 'saida', '2025-12-26 15:49:48'),
(4364, 666511294, 1823.82, 'saida', '2025-12-26 15:51:01'),
(4365, 666511294, -1823.82, 'entrada', '2025-12-26 15:53:51'),
(4366, 666511294, 1823.82, 'saida', '2025-12-26 15:54:00'),
(4367, 666511294, -1823.82, 'entrada', '2025-12-26 15:54:02'),
(4368, 666511294, 1823.82, 'saida', '2025-12-26 15:54:09'),
(4369, 666511294, -1823.82, 'entrada', '2025-12-26 15:54:11'),
(4370, 666511294, 1823.82, 'saida', '2025-12-26 15:54:18'),
(4371, 666511294, 1823.82, 'saida', '2025-12-26 15:55:13'),
(4372, 666511294, -1823.82, 'entrada', '2025-12-26 15:55:15'),
(4373, 666511294, 1823.82, 'saida', '2025-12-26 15:56:20'),
(4374, 666511294, -1823.82, 'entrada', '2025-12-26 15:56:22'),
(4375, 666511294, 1823.82, 'saida', '2025-12-26 15:56:28'),
(4376, 666511294, -1823.82, 'entrada', '2025-12-26 15:57:41'),
(4377, 666511294, 1823.82, 'saida', '2025-12-26 15:57:46'),
(4378, 666511294, 1823.82, 'saida', '2025-12-26 16:01:59'),
(4379, 666511294, 1823.82, 'saida', '2025-12-26 16:03:24'),
(4380, 666511294, -1823.82, 'entrada', '2025-12-26 16:03:26'),
(4381, 666511294, 1823.82, 'saida', '2025-12-26 16:03:33'),
(4382, 666511294, 1823.82, 'saida', '2025-12-26 16:05:18'),
(4383, 666511294, -1823.82, 'entrada', '2025-12-26 16:05:19'),
(4384, 666511294, 1823.82, 'saida', '2025-12-26 16:06:16'),
(4385, 666511294, 1823.82, 'saida', '2025-12-26 16:06:18'),
(4386, 666511294, -1823.82, 'entrada', '2025-12-26 16:06:19'),
(4387, 666511294, 1823.82, 'saida', '2025-12-26 16:08:02'),
(4388, 666511294, -1823.82, 'entrada', '2025-12-26 16:08:04'),
(4389, 666511294, 1823.82, 'saida', '2025-12-26 16:10:13'),
(4390, 666511294, 1823.82, 'saida', '2025-12-26 16:10:15'),
(4391, 666511294, -1823.82, 'entrada', '2025-12-26 16:10:15'),
(4392, 666511294, 1823.82, 'saida', '2025-12-26 16:10:21'),
(4393, 666511294, 1823.82, 'saida', '2025-12-26 16:10:56'),
(4394, 666511294, -1823.82, 'entrada', '2025-12-26 16:10:57'),
(4395, 666511294, 1823.82, 'saida', '2025-12-26 16:11:26'),
(4396, 666511294, -1823.82, 'entrada', '2025-12-26 16:11:30'),
(4397, 666511294, 1823.82, 'saida', '2025-12-26 16:13:32'),
(4398, 666511294, -1823.82, 'entrada', '2025-12-26 16:13:34'),
(4399, 666511294, 1823.82, 'saida', '2025-12-26 16:13:41'),
(4400, 666511294, 1823.82, 'saida', '2025-12-26 16:14:53'),
(4401, 666511294, -1823.82, 'entrada', '2025-12-26 16:14:55'),
(4402, 666511294, 1823.82, 'saida', '2025-12-26 16:16:41'),
(4403, 666511294, 1823.82, 'saida', '2025-12-26 16:22:00'),
(4404, 666511294, -1823.82, 'entrada', '2025-12-26 16:22:02'),
(4405, 666511294, 1823.82, 'saida', '2025-12-26 16:22:09'),
(4406, 666511294, -1823.82, 'entrada', '2025-12-26 16:22:36'),
(4407, 666511294, 1823.82, 'saida', '2025-12-26 16:22:42'),
(4408, 666511294, 1823.82, 'saida', '2025-12-26 16:22:44'),
(4409, 666511294, 1823.82, 'saida', '2025-12-26 16:22:58'),
(4410, 666511294, -1823.82, 'entrada', '2025-12-26 16:22:59'),
(4411, 666511294, 1823.82, 'saida', '2025-12-26 16:23:14'),
(4412, 666511294, -1823.82, 'entrada', '2025-12-26 16:27:14'),
(4413, 666511294, 1821.34, 'saida', '2025-12-26 16:27:39'),
(4414, 666511294, 1821.34, 'saida', '2025-12-26 16:29:22'),
(4415, 666511294, -1821.34, 'entrada', '2025-12-26 16:29:23'),
(4416, 666511294, 1821.34, 'saida', '2025-12-26 16:29:40'),
(4417, 666511294, -1821.34, 'entrada', '2025-12-26 16:29:42'),
(4418, 666511294, 1832.14, 'saida', '2025-12-26 16:30:08'),
(4419, 666511294, -1832.14, 'entrada', '2025-12-26 16:34:05'),
(4420, 666511294, 1832.14, 'saida', '2025-12-26 16:34:13'),
(4421, 666511294, -1832.14, 'entrada', '2025-12-26 16:34:14'),
(4422, 666511294, 1553.74, 'saida', '2025-12-26 16:34:53'),
(4423, 666511294, -1553.74, 'entrada', '2025-12-26 16:35:02'),
(4424, 666511294, 1496.14, 'saida', '2025-12-26 16:35:33'),
(4425, 666511294, -1496.14, 'entrada', '2025-12-26 16:40:30'),
(4426, 666511294, 1535.74, 'saida', '2025-12-26 16:41:03'),
(4427, 666511294, -1535.74, 'entrada', '2025-12-26 16:41:05'),
(4428, 666511294, 1677.34, 'saida', '2025-12-26 16:41:33'),
(4429, 666511294, 1677.34, 'saida', '2025-12-26 16:55:20'),
(4430, 666511294, 1677.34, 'saida', '2025-12-26 16:55:40'),
(4431, 666511294, -1677.34, 'entrada', '2025-12-26 16:55:41'),
(4432, 666511294, 1678.54, 'saida', '2025-12-26 16:55:48'),
(4433, 666511294, -1678.54, 'entrada', '2025-12-26 16:55:49'),
(4434, 666511294, 1690.14, 'saida', '2025-12-26 16:56:07'),
(4435, 666511294, -1690.14, 'entrada', '2025-12-26 16:56:28'),
(4436, 666511294, 2325.90, 'saida', '2025-12-26 16:57:20'),
(4437, 666511294, -2325.90, 'entrada', '2025-12-26 16:57:48'),
(4438, 666511294, 2418.14, 'saida', '2025-12-26 16:58:35'),
(4439, 666511294, 2418.14, 'saida', '2025-12-26 16:58:37'),
(4440, 666511294, 2418.14, 'saida', '2025-12-26 16:58:39'),
(4441, 666511294, -2418.14, 'entrada', '2025-12-26 16:58:39'),
(4442, 666511294, 2418.14, 'saida', '2025-12-26 16:59:11'),
(4443, 666511294, -2418.14, 'entrada', '2025-12-26 16:59:12'),
(4444, 666511294, 2418.14, 'saida', '2025-12-26 17:00:14'),
(4445, 666511294, -2418.14, 'entrada', '2025-12-26 17:00:28'),
(4446, 666511294, 2430.22, 'saida', '2025-12-26 17:01:01'),
(4447, 569050504, 0.00, 'saida', '2025-12-26 17:04:02'),
(4448, 569050504, 0.00, 'saida', '2025-12-26 17:04:04'),
(4449, 569050504, 200.00, 'saida', '2025-12-26 17:05:36'),
(4450, 569050504, -200.00, 'entrada', '2025-12-26 17:05:38'),
(4451, 569050504, 200.00, 'saida', '2025-12-26 17:05:45'),
(4452, 569050504, -200.00, 'entrada', '2025-12-26 17:05:46'),
(4453, 569050504, 73.20, 'saida', '2025-12-26 17:07:14'),
(4454, 569050504, -73.20, 'entrada', '2025-12-26 17:09:11'),
(4455, 569050504, 65.60, 'saida', '2025-12-26 17:09:38'),
(4456, 483195429, 0.00, 'saida', '2025-12-26 17:09:57'),
(4457, 483195429, 0.00, 'saida', '2025-12-26 17:09:58'),
(4458, 483195429, 100.00, 'saida', '2025-12-26 17:10:17'),
(4459, 483195429, -100.00, 'entrada', '2025-12-26 17:10:18'),
(4460, 483195429, 225.20, 'saida', '2025-12-26 17:12:11'),
(4461, 483195429, 225.20, 'saida', '2025-12-26 17:15:10'),
(4462, 483195429, 225.20, 'saida', '2025-12-26 17:15:54'),
(4463, 483195429, -225.20, 'entrada', '2025-12-26 17:16:15'),
(4464, 483195429, 189.20, 'saida', '2025-12-26 17:16:35'),
(4465, 483195429, -189.20, 'entrada', '2025-12-26 17:16:41'),
(4466, 483195429, 189.20, 'saida', '2025-12-26 17:16:50'),
(4467, 483195429, -189.20, 'entrada', '2025-12-26 17:16:53'),
(4468, 483195429, 189.20, 'saida', '2025-12-26 17:17:01'),
(4469, 483195429, 189.20, 'saida', '2025-12-26 17:17:17'),
(4470, 483195429, -189.20, 'entrada', '2025-12-26 17:17:20'),
(4471, 483195429, 180.20, 'saida', '2025-12-26 17:17:53'),
(4472, 483195429, -180.20, 'entrada', '2025-12-26 17:18:35'),
(4473, 483195429, 68.60, 'saida', '2025-12-26 17:19:04'),
(4474, 146064420, 0.00, 'saida', '2025-12-26 17:19:25'),
(4475, 146064420, 0.00, 'saida', '2025-12-26 17:19:28'),
(4476, 146064420, 100.00, 'saida', '2025-12-26 17:19:41'),
(4477, 146064420, -100.00, 'entrada', '2025-12-26 17:19:45'),
(4478, 146064420, 27.20, 'saida', '2025-12-26 17:24:32'),
(4479, 146064420, 27.20, 'saida', '2025-12-26 17:33:41'),
(4480, 146064420, -27.20, 'entrada', '2025-12-26 17:33:52'),
(4481, 146064420, 27.20, 'saida', '2025-12-26 17:33:58'),
(4482, 146064420, -27.20, 'entrada', '2025-12-26 17:34:01'),
(4483, 146064420, 27.20, 'saida', '2025-12-26 17:34:07'),
(4484, 146064420, -27.20, 'entrada', '2025-12-26 17:34:11'),
(4485, 146064420, 27.20, 'saida', '2025-12-26 17:34:15'),
(4486, 146064420, 27.20, 'saida', '2025-12-26 17:34:29'),
(4487, 146064420, -27.20, 'entrada', '2025-12-26 17:34:34'),
(4488, 146064420, 26.00, 'saida', '2025-12-26 17:34:54'),
(4489, 146064420, -26.00, 'entrada', '2025-12-26 17:36:44'),
(4490, 146064420, 23.20, 'saida', '2025-12-26 17:37:03'),
(4491, 146064420, -23.20, 'entrada', '2025-12-26 17:37:06'),
(4492, 146064420, 20.20, 'saida', '2025-12-26 17:37:24'),
(4493, 146064420, -20.20, 'entrada', '2025-12-26 17:39:54'),
(4494, 146064420, 18.60, 'saida', '2025-12-26 17:48:10'),
(4495, 146064420, -18.60, 'entrada', '2025-12-26 17:50:03'),
(4496, 146064420, 17.00, 'saida', '2025-12-26 17:50:16'),
(4497, 146064420, -17.00, 'entrada', '2025-12-26 18:06:32'),
(4498, 146064420, 11.00, 'saida', '2025-12-26 18:06:41'),
(4499, 146064420, -11.00, 'entrada', '2025-12-26 18:19:43'),
(4500, 146064420, 11.00, 'saida', '2025-12-26 18:27:55'),
(4501, 146064420, -11.00, 'entrada', '2025-12-26 18:27:58'),
(4502, 146064420, 11.00, 'saida', '2025-12-26 18:28:02'),
(4503, 146064420, 11.00, 'saida', '2025-12-26 18:28:06'),
(4504, 146064420, 11.00, 'saida', '2025-12-26 18:30:47'),
(4505, 146064420, -11.00, 'entrada', '2025-12-26 18:30:51'),
(4506, 146064420, 11.00, 'saida', '2025-12-26 18:30:58'),
(4507, 146064420, 11.00, 'saida', '2025-12-26 18:31:48'),
(4508, 146064420, -11.00, 'entrada', '2025-12-26 18:31:54'),
(4509, 146064420, 11.00, 'saida', '2025-12-26 18:31:58'),
(4510, 146064420, -11.00, 'entrada', '2025-12-26 18:32:04'),
(4511, 146064420, 11.00, 'saida', '2025-12-26 18:34:31'),
(4512, 146064420, -11.00, 'entrada', '2025-12-26 18:34:36'),
(4513, 146064420, 11.00, 'saida', '2025-12-26 18:34:41'),
(4514, 146064420, 11.00, 'saida', '2025-12-26 18:34:44'),
(4515, 146064420, -11.00, 'entrada', '2025-12-26 18:34:47'),
(4516, 146064420, 11.00, 'saida', '2025-12-26 18:35:12'),
(4517, 146064420, 11.00, 'saida', '2025-12-26 18:35:48'),
(4518, 146064420, -11.00, 'entrada', '2025-12-26 18:35:52'),
(4519, 146064420, 11.00, 'saida', '2025-12-26 18:35:56'),
(4520, 146064420, 11.00, 'saida', '2025-12-26 18:39:51'),
(4521, 146064420, -11.00, 'entrada', '2025-12-26 18:39:56'),
(4522, 146064420, 11.00, 'saida', '2025-12-26 18:40:02'),
(4523, 146064420, -11.00, 'entrada', '2025-12-26 18:41:01'),
(4524, 146064420, 11.00, 'saida', '2025-12-26 18:41:08'),
(4525, 146064420, -11.00, 'entrada', '2025-12-26 18:41:12'),
(4526, 146064420, 7.01, 'saida', '2025-12-26 18:41:55'),
(4527, 146064420, 7.01, 'saida', '2025-12-26 18:43:14'),
(4528, 146064420, -7.01, 'entrada', '2025-12-26 18:43:18'),
(4529, 146064420, 7.01, 'saida', '2025-12-26 18:43:24'),
(4530, 146064420, -7.01, 'entrada', '2025-12-26 18:43:28'),
(4531, 146064420, 7.01, 'saida', '2025-12-26 18:43:32'),
(4532, 146064420, -7.01, 'entrada', '2025-12-26 18:44:03'),
(4533, 146064420, 7.01, 'saida', '2025-12-26 18:44:05'),
(4534, 146064420, -7.01, 'entrada', '2025-12-26 18:44:51'),
(4535, 146064420, 7.01, 'saida', '2025-12-26 18:44:54'),
(4536, 146064420, -7.01, 'entrada', '2025-12-26 18:44:56'),
(4537, 146064420, 7.01, 'saida', '2025-12-26 18:45:01'),
(4538, 146064420, 7.01, 'saida', '2025-12-26 18:57:22'),
(4539, 146064420, -7.01, 'entrada', '2025-12-26 18:57:27'),
(4540, 146064420, 7.01, 'saida', '2025-12-26 18:57:30'),
(4541, 146064420, -7.01, 'entrada', '2025-12-26 18:57:35'),
(4542, 146064420, 7.01, 'saida', '2025-12-26 18:57:44'),
(4543, 146064420, -7.01, 'entrada', '2025-12-26 18:57:55'),
(4544, 146064420, 7.01, 'saida', '2025-12-26 18:58:54'),
(4545, 666511294, 2430.22, 'saida', '2025-12-26 19:01:34'),
(4546, 666511294, -2430.22, 'entrada', '2025-12-26 19:01:36'),
(4547, 666511294, 2430.22, 'saida', '2025-12-26 19:01:40'),
(4548, 666511294, -2430.22, 'entrada', '2025-12-26 19:02:24'),
(4549, 666511294, 2430.22, 'saida', '2025-12-26 19:02:27'),
(4550, 666511294, -2430.22, 'entrada', '2025-12-26 19:02:33'),
(4551, 666511294, 2430.22, 'saida', '2025-12-26 19:02:39'),
(4552, 666511294, -2430.22, 'entrada', '2025-12-26 19:02:43'),
(4553, 666511294, 2430.22, 'saida', '2025-12-26 19:02:51'),
(4554, 666511294, 2430.22, 'saida', '2025-12-26 23:18:44'),
(4555, 666511294, -2430.22, 'entrada', '2025-12-26 23:18:46'),
(4556, 666511294, 2430.22, 'saida', '2025-12-26 23:18:53'),
(4557, 666511294, -2430.22, 'entrada', '2025-12-26 23:19:07'),
(4558, 666511294, 2430.22, 'saida', '2025-12-26 23:19:15'),
(4559, 666511294, 2430.22, 'saida', '2025-12-27 08:03:11'),
(4560, 666511294, -2430.22, 'entrada', '2025-12-27 08:03:13'),
(4561, 666511294, 2430.22, 'saida', '2025-12-27 08:03:15'),
(4562, 666511294, -2430.22, 'entrada', '2025-12-27 08:04:05'),
(4563, 666511294, 1605.22, 'saida', '2025-12-27 08:04:27'),
(4564, 666511294, -1605.22, 'entrada', '2025-12-27 08:04:51'),
(4565, 666511294, 1571.62, 'saida', '2025-12-27 08:05:09'),
(4566, 666511294, -1571.62, 'entrada', '2025-12-27 08:05:47'),
(4567, 666511294, 1535.62, 'saida', '2025-12-27 08:06:07'),
(4568, 666511294, -1535.62, 'entrada', '2025-12-27 08:06:28'),
(4569, 666511294, 1535.62, 'saida', '2025-12-27 08:06:39'),
(4570, 666511294, -1535.62, 'entrada', '2025-12-27 08:06:40'),
(4571, 666511294, 1535.62, 'saida', '2025-12-27 08:06:50'),
(4572, 666511294, 1535.62, 'saida', '2025-12-27 08:06:52'),
(4573, 666511294, -1535.62, 'entrada', '2025-12-27 08:06:54'),
(4574, 666511294, 1535.62, 'saida', '2025-12-27 08:07:29'),
(4575, 666511294, 1535.62, 'saida', '2025-12-27 08:07:41'),
(4576, 666511294, -1535.62, 'entrada', '2025-12-27 08:07:41'),
(4577, 666511294, 1533.62, 'saida', '2025-12-27 08:08:01'),
(4578, 666511294, -1533.62, 'entrada', '2025-12-27 08:08:03'),
(4579, 666511294, 1525.62, 'saida', '2025-12-27 08:08:15'),
(4580, 666511294, 1525.62, 'saida', '2025-12-27 08:08:38'),
(4581, 666511294, -1525.62, 'entrada', '2025-12-27 08:08:40'),
(4582, 666511294, 1520.22, 'saida', '2025-12-27 08:09:02'),
(4583, 666511294, 1520.22, 'saida', '2025-12-27 08:09:24'),
(4584, 666511294, -1520.22, 'entrada', '2025-12-27 08:09:24'),
(4585, 666511294, 1505.52, 'saida', '2025-12-27 08:09:49'),
(4586, 666511294, -1505.52, 'entrada', '2025-12-27 08:10:08'),
(4587, 666511294, 1500.52, 'saida', '2025-12-27 08:10:32'),
(4588, 666511294, -1500.52, 'entrada', '2025-12-27 08:10:51'),
(4589, 666511294, 1500.52, 'saida', '2025-12-27 08:10:56'),
(4590, 666511294, -1500.52, 'entrada', '2025-12-27 08:10:58'),
(4591, 666511294, 1500.52, 'saida', '2025-12-27 08:11:02'),
(4592, 666511294, -1500.52, 'entrada', '2025-12-27 08:11:03'),
(4593, 666511294, 1500.52, 'saida', '2025-12-27 08:11:32'),
(4594, 666511294, 1500.52, 'saida', '2025-12-27 08:11:45'),
(4595, 666511294, -1500.52, 'entrada', '2025-12-27 08:11:46'),
(4596, 666511294, 1500.52, 'saida', '2025-12-27 08:12:18'),
(4597, 666511294, 1500.52, 'saida', '2025-12-27 08:12:20'),
(4598, 666511294, -1500.52, 'entrada', '2025-12-27 08:12:21'),
(4599, 666511294, 1500.52, 'saida', '2025-12-27 08:12:36'),
(4600, 666511294, -1500.52, 'entrada', '2025-12-27 08:12:47'),
(4601, 666511294, 1500.52, 'saida', '2025-12-27 08:13:05'),
(4602, 666511294, -1500.52, 'entrada', '2025-12-27 08:13:07'),
(4603, 666511294, 1500.52, 'saida', '2025-12-27 08:13:16'),
(4604, 666511294, -1500.52, 'entrada', '2025-12-27 08:13:18'),
(4605, 666511294, 1500.52, 'saida', '2025-12-27 08:13:28'),
(4606, 666511294, -1500.52, 'entrada', '2025-12-27 08:13:30'),
(4607, 666511294, 1500.52, 'saida', '2025-12-27 08:13:42'),
(4608, 666511294, 1500.52, 'saida', '2025-12-27 08:14:13'),
(4609, 666511294, -1500.52, 'entrada', '2025-12-27 08:14:14'),
(4610, 666511294, 1500.52, 'saida', '2025-12-27 08:14:25'),
(4611, 666511294, -1500.52, 'entrada', '2025-12-27 08:15:13'),
(4612, 666511294, 1492.52, 'saida', '2025-12-27 08:15:37'),
(4613, 666511294, -1492.52, 'entrada', '2025-12-27 08:15:58'),
(4614, 666511294, 1485.02, 'saida', '2025-12-27 08:16:18'),
(4615, 666511294, 1485.02, 'saida', '2025-12-27 08:16:37'),
(4616, 666511294, -1485.02, 'entrada', '2025-12-27 08:16:39'),
(4617, 666511294, 1485.02, 'saida', '2025-12-27 08:16:46'),
(4618, 666511294, -1485.02, 'entrada', '2025-12-27 08:16:48'),
(4619, 666511294, 1461.02, 'saida', '2025-12-27 08:17:06'),
(4620, 666511294, -1461.02, 'entrada', '2025-12-27 08:17:27'),
(4621, 666511294, 1461.02, 'saida', '2025-12-27 08:17:33'),
(4622, 666511294, -1461.02, 'entrada', '2025-12-27 08:17:34'),
(4623, 666511294, 1461.02, 'saida', '2025-12-27 08:17:55'),
(4624, 666511294, -1461.02, 'entrada', '2025-12-27 08:18:01'),
(4625, 666511294, 1452.62, 'saida', '2025-12-27 08:18:26'),
(4626, 666511294, 1452.62, 'saida', '2025-12-27 08:18:50'),
(4627, 666511294, -1452.62, 'entrada', '2025-12-27 08:18:51'),
(4628, 666511294, 1440.62, 'saida', '2025-12-27 08:19:07'),
(4629, 666511294, -1440.62, 'entrada', '2025-12-27 08:19:09'),
(4630, 666511294, 1404.62, 'saida', '2025-12-27 08:19:19'),
(4631, 666511294, -1404.62, 'entrada', '2025-12-27 08:19:38'),
(4632, 666511294, 1404.62, 'saida', '2025-12-27 08:19:44'),
(4633, 666511294, -1404.62, 'entrada', '2025-12-27 08:19:47'),
(4634, 666511294, 1404.62, 'saida', '2025-12-27 08:19:54'),
(4635, 666511294, -1404.62, 'entrada', '2025-12-27 08:19:55'),
(4636, 666511294, 1404.62, 'saida', '2025-12-27 08:20:01'),
(4637, 666511294, 1404.62, 'saida', '2025-12-27 08:20:03'),
(4638, 666511294, -1404.62, 'entrada', '2025-12-27 08:20:04'),
(4639, 666511294, 1404.62, 'saida', '2025-12-27 08:20:16'),
(4640, 666511294, -1404.62, 'entrada', '2025-12-27 08:21:27'),
(4641, 666511294, 1356.62, 'saida', '2025-12-27 08:21:40'),
(4642, 666511294, -1356.62, 'entrada', '2025-12-27 08:22:48'),
(4643, 666511294, 1320.62, 'saida', '2025-12-27 08:23:06'),
(4644, 666511294, -1320.62, 'entrada', '2025-12-27 08:23:25'),
(4645, 666511294, 1320.62, 'saida', '2025-12-27 08:23:30'),
(4646, 666511294, -1320.62, 'entrada', '2025-12-27 08:23:33'),
(4647, 666511294, 1320.62, 'saida', '2025-12-27 08:23:52'),
(4648, 666511294, -1320.62, 'entrada', '2025-12-27 08:24:03'),
(4649, 666511294, 1320.62, 'saida', '2025-12-27 08:24:07'),
(4650, 666511294, -1320.62, 'entrada', '2025-12-27 08:24:08'),
(4651, 666511294, 1320.62, 'saida', '2025-12-27 08:24:13'),
(4652, 666511294, -1320.62, 'entrada', '2025-12-27 08:24:36'),
(4653, 666511294, 1320.62, 'saida', '2025-12-27 08:24:45'),
(4654, 666511294, -1320.62, 'entrada', '2025-12-27 08:24:47'),
(4655, 666511294, 1320.62, 'saida', '2025-12-27 08:24:52'),
(4656, 666511294, 1320.62, 'saida', '2025-12-27 08:24:54'),
(4657, 666511294, -1320.62, 'entrada', '2025-12-27 08:24:55'),
(4658, 666511294, 1320.62, 'saida', '2025-12-27 08:25:34'),
(4659, 666511294, -1320.62, 'entrada', '2025-12-27 08:25:36'),
(4660, 666511294, 1320.62, 'saida', '2025-12-27 08:25:41'),
(4661, 666511294, -1320.62, 'entrada', '2025-12-27 08:25:50'),
(4662, 666511294, 1320.62, 'saida', '2025-12-27 08:25:54'),
(4663, 666511294, -1320.62, 'entrada', '2025-12-27 08:25:55'),
(4664, 666511294, 1320.62, 'saida', '2025-12-27 08:26:29'),
(4665, 666511294, -1320.62, 'entrada', '2025-12-27 08:26:30'),
(4666, 666511294, 1320.62, 'saida', '2025-12-27 08:26:34'),
(4667, 666511294, -1320.62, 'entrada', '2025-12-27 08:26:35'),
(4668, 666511294, 1320.62, 'saida', '2025-12-27 08:26:39'),
(4669, 666511294, -1320.62, 'entrada', '2025-12-27 08:27:14'),
(4670, 666511294, 1320.62, 'saida', '2025-12-27 08:27:17'),
(4671, 666511294, -1320.62, 'entrada', '2025-12-27 08:27:18'),
(4672, 666511294, 1320.62, 'saida', '2025-12-27 08:27:22'),
(4673, 666511294, -1320.62, 'entrada', '2025-12-27 08:47:53'),
(4674, 666511294, 1320.62, 'saida', '2025-12-27 08:47:56'),
(4675, 666511294, -1320.62, 'entrada', '2025-12-27 08:48:39'),
(4676, 666511294, 1320.62, 'saida', '2025-12-27 08:48:43'),
(4677, 666511294, -1320.62, 'entrada', '2025-12-27 08:49:12'),
(4678, 666511294, 1320.62, 'saida', '2025-12-27 08:49:17'),
(4679, 666511294, -1320.62, 'entrada', '2025-12-27 08:49:48'),
(4680, 666511294, 1320.62, 'saida', '2025-12-27 08:49:52'),
(4681, 666511294, -1320.62, 'entrada', '2025-12-27 08:50:06'),
(4682, 666511294, 1320.62, 'saida', '2025-12-27 08:50:11'),
(4683, 666511294, 1320.62, 'saida', '2025-12-27 08:50:14'),
(4684, 666511294, -1320.62, 'entrada', '2025-12-27 08:52:54'),
(4685, 666511294, 1320.62, 'saida', '2025-12-27 08:52:56'),
(4686, 666511294, -1320.62, 'entrada', '2025-12-27 09:01:42'),
(4687, 666511294, 1320.62, 'saida', '2025-12-27 09:01:46'),
(4688, 666511294, 1320.62, 'saida', '2025-12-27 09:01:50'),
(4689, 666511294, -1320.62, 'entrada', '2025-12-27 09:01:50'),
(4690, 666511294, 1320.62, 'saida', '2025-12-27 09:01:57'),
(4691, 666511294, 1320.62, 'saida', '2025-12-27 09:02:06'),
(4692, 666511294, 1320.62, 'saida', '2025-12-27 09:02:32'),
(4693, 666511294, -1320.62, 'entrada', '2025-12-27 09:02:33'),
(4694, 666511294, 1320.62, 'saida', '2025-12-27 09:02:37'),
(4695, 666511294, -1320.62, 'entrada', '2025-12-27 09:02:49'),
(4696, 666511294, 1320.62, 'saida', '2025-12-27 09:02:59'),
(4697, 666511294, -1320.62, 'entrada', '2025-12-27 09:03:40'),
(4698, 666511294, 1320.62, 'saida', '2025-12-27 09:03:53'),
(4699, 666511294, 1320.62, 'saida', '2025-12-27 09:03:55'),
(4700, 666511294, -1320.62, 'entrada', '2025-12-27 09:03:56'),
(4701, 666511294, 1318.22, 'saida', '2025-12-27 09:04:23'),
(4702, 666511294, 1318.22, 'saida', '2025-12-27 09:28:24'),
(4703, 666511294, -1318.22, 'entrada', '2025-12-27 09:28:30'),
(4704, 666511294, 1318.22, 'saida', '2025-12-27 09:28:43'),
(4705, 666511294, -1318.22, 'entrada', '2025-12-27 09:29:32'),
(4706, 666511294, 1318.22, 'saida', '2025-12-27 09:29:42'),
(4707, 666511294, 1318.22, 'saida', '2025-12-27 11:58:59'),
(4708, 666511294, 1318.22, 'saida', '2025-12-27 18:16:00'),
(4709, 666511294, 1318.22, 'saida', '2025-12-27 18:20:28'),
(4710, 491700733, 0.00, 'saida', '2025-12-27 18:29:33'),
(4711, 491700733, 0.00, 'saida', '2025-12-27 18:29:37'),
(4712, 491700733, -1300.00, 'entrada', '2025-12-27 22:41:55'),
(4713, 491700733, 1300.00, 'saida', '2025-12-27 22:42:38'),
(4714, 491700733, -1300.00, 'entrada', '2025-12-27 22:42:53'),
(4715, 491700733, 1299.60, 'saida', '2025-12-27 22:43:45'),
(4716, 491700733, 1299.60, 'saida', '2025-12-27 22:45:19'),
(4717, 491700733, -1299.60, 'entrada', '2025-12-27 22:45:31'),
(4718, 491700733, 1297.60, 'saida', '2025-12-27 22:46:07'),
(4719, 491700733, 1297.60, 'saida', '2025-12-27 22:46:11'),
(4720, 491700733, 1297.60, 'saida', '2025-12-27 22:49:00'),
(4721, 491700733, 1297.60, 'saida', '2025-12-27 22:49:00'),
(4722, 491700733, -1297.60, 'entrada', '2025-12-28 00:33:42'),
(4723, 491700733, 1297.60, 'saida', '2025-12-28 00:33:45'),
(4724, 491700733, -1297.60, 'entrada', '2025-12-28 00:33:49'),
(4725, 491700733, 1293.20, 'saida', '2025-12-28 00:47:57'),
(4726, 491700733, 1293.20, 'saida', '2025-12-28 00:59:34'),
(4727, 491700733, -1293.20, 'entrada', '2025-12-28 00:59:38'),
(4728, 491700733, 1293.20, 'saida', '2025-12-28 01:00:01'),
(4729, 491700733, -1293.20, 'entrada', '2025-12-28 01:00:07'),
(4730, 491700733, 1284.20, 'saida', '2025-12-28 01:00:48'),
(4731, 491700733, -1284.20, 'entrada', '2025-12-28 01:00:55'),
(4732, 491700733, 1284.20, 'saida', '2025-12-28 01:01:03'),
(4733, 491700733, 1284.20, 'saida', '2025-12-28 01:01:17'),
(4734, 491700733, 1284.20, 'saida', '2025-12-28 01:01:23'),
(4735, 491700733, -1284.20, 'entrada', '2025-12-28 01:01:27'),
(4736, 491700733, 1236.20, 'saida', '2025-12-28 01:02:02'),
(4737, 491700733, -1236.20, 'entrada', '2025-12-28 01:02:30'),
(4738, 491700733, 1236.20, 'saida', '2025-12-28 01:02:53'),
(4739, 491700733, -1236.20, 'entrada', '2025-12-28 01:03:04'),
(4740, 491700733, 1236.20, 'saida', '2025-12-28 01:03:13'),
(4741, 491700733, 1236.20, 'saida', '2025-12-28 01:03:19'),
(4742, 491700733, -1236.20, 'entrada', '2025-12-28 01:03:30'),
(4743, 491700733, 1236.20, 'saida', '2025-12-28 01:03:52'),
(4744, 491700733, -1236.20, 'entrada', '2025-12-28 01:03:57'),
(4745, 491700733, 1127.40, 'saida', '2025-12-28 01:05:51'),
(4746, 491700733, -1127.40, 'entrada', '2025-12-28 01:05:56'),
(4747, 491700733, 1127.40, 'saida', '2025-12-28 01:06:01'),
(4748, 491700733, -1127.40, 'entrada', '2025-12-28 01:06:20'),
(4749, 491700733, 1127.40, 'saida', '2025-12-28 01:06:29'),
(4750, 491700733, -1127.40, 'entrada', '2025-12-28 01:06:35'),
(4751, 491700733, 1127.40, 'saida', '2025-12-28 01:06:42'),
(4752, 491700733, 1127.40, 'saida', '2025-12-28 01:06:57'),
(4753, 491700733, 1127.40, 'saida', '2025-12-28 01:07:04'),
(4754, 491700733, -1127.40, 'entrada', '2025-12-28 01:07:17'),
(4755, 491700733, 1127.40, 'saida', '2025-12-28 01:07:38'),
(4756, 491700733, 1127.40, 'saida', '2025-12-28 01:43:30'),
(4757, 491700733, 1127.40, 'saida', '2025-12-28 09:28:25'),
(4758, 491700733, 1127.40, 'saida', '2025-12-28 09:28:41'),
(4759, 491700733, 1127.40, 'saida', '2025-12-28 11:49:53'),
(4760, 666511294, 1318.22, 'saida', '2025-12-28 12:39:20'),
(4761, 666511294, 1318.22, 'saida', '2025-12-28 13:18:13'),
(4762, 666511294, 1318.22, 'saida', '2025-12-28 13:21:14'),
(4763, 759829687, 0.00, 'saida', '2026-01-01 09:16:37'),
(4764, 759829687, 0.00, 'saida', '2026-01-01 09:16:38'),
(4765, 759829687, 0.00, 'saida', '2026-01-01 09:19:08'),
(4766, 759829687, 0.00, 'saida', '2026-01-01 09:20:31'),
(4767, 759829687, 0.00, 'saida', '2026-01-01 09:21:36'),
(4768, 129577476, 0.00, 'saida', '2026-01-02 09:44:38'),
(4769, 129577476, 0.00, 'saida', '2026-01-02 09:44:39'),
(4770, 129577476, 0.00, 'saida', '2026-01-02 09:45:18'),
(4771, 129577476, 0.00, 'saida', '2026-01-02 09:49:58'),
(4772, 129577476, 0.00, 'saida', '2026-01-02 09:50:46'),
(4773, 129577476, 0.00, 'saida', '2026-01-02 09:51:29'),
(4774, 129577476, 100.00, 'saida', '2026-01-02 09:55:46'),
(4775, 129577476, 100.00, 'saida', '2026-01-02 09:55:53'),
(4776, 129577476, 100.00, 'saida', '2026-01-02 09:55:57'),
(4777, 129577476, 100.00, 'saida', '2026-01-02 09:56:01'),
(4778, 129577476, 100.00, 'saida', '2026-01-02 09:56:26'),
(4779, 129577476, -100.00, 'entrada', '2026-01-02 09:56:28'),
(4780, 129577476, 100.00, 'saida', '2026-01-02 09:56:39'),
(4781, 129577476, -100.00, 'entrada', '2026-01-02 09:56:42'),
(4782, 129577476, 100.00, 'saida', '2026-01-02 09:57:13'),
(4783, 129577476, -100.00, 'entrada', '2026-01-02 09:57:55'),
(4784, 129577476, 100.00, 'saida', '2026-01-02 09:58:18'),
(4785, 129577476, -100.00, 'entrada', '2026-01-02 09:58:20'),
(4786, 129577476, 100.00, 'saida', '2026-01-02 09:59:01'),
(4787, 129577476, -100.00, 'entrada', '2026-01-02 09:59:03'),
(4788, 129577476, 100.00, 'saida', '2026-01-02 10:03:23'),
(4789, 129577476, -100.00, 'entrada', '2026-01-02 10:03:25'),
(4790, 129577476, 100.00, 'saida', '2026-01-02 10:03:31'),
(4791, 129577476, -100.00, 'entrada', '2026-01-02 10:03:33'),
(4792, 129577476, 100.00, 'saida', '2026-01-02 10:05:59'),
(4793, 129577476, 100.00, 'saida', '2026-01-02 10:06:03'),
(4794, 129577476, -100.00, 'entrada', '2026-01-02 10:06:04'),
(4795, 129577476, 100.00, 'saida', '2026-01-02 10:06:17'),
(4796, 129577476, -100.00, 'entrada', '2026-01-02 10:06:20'),
(4797, 129577476, 100.00, 'saida', '2026-01-02 11:15:19'),
(4798, 129577476, -100.00, 'entrada', '2026-01-02 11:15:29'),
(4799, 129577476, 100.00, 'saida', '2026-01-02 11:15:35'),
(4800, 129577476, -100.00, 'entrada', '2026-01-02 11:15:38'),
(4801, 129577476, 121.50, 'saida', '2026-01-02 11:17:33'),
(4802, 129577476, -121.50, 'entrada', '2026-01-02 11:17:36'),
(4803, 129577476, 121.50, 'saida', '2026-01-02 11:17:40'),
(4804, 129577476, -121.50, 'entrada', '2026-01-02 11:17:42'),
(4805, 129577476, 121.50, 'saida', '2026-01-02 11:18:18'),
(4806, 129577476, 121.50, 'saida', '2026-01-02 11:37:56'),
(4807, 129577476, 121.50, 'saida', '2026-01-02 11:39:22'),
(4808, 129577476, 121.50, 'saida', '2026-01-02 11:40:09'),
(4809, 129577476, 121.50, 'saida', '2026-01-02 11:51:32'),
(4810, 129577476, -121.50, 'entrada', '2026-01-02 11:51:35'),
(4811, 129577476, 121.50, 'saida', '2026-01-02 11:51:42'),
(4812, 129577476, -121.50, 'entrada', '2026-01-02 11:51:46'),
(4813, 129577476, 121.50, 'saida', '2026-01-02 11:52:12'),
(4814, 129577476, -121.50, 'entrada', '2026-01-02 11:52:15'),
(4815, 129577476, 121.50, 'saida', '2026-01-02 11:52:41'),
(4816, 129577476, -121.50, 'entrada', '2026-01-02 11:52:42'),
(4817, 129577476, 117.50, 'saida', '2026-01-02 11:54:28'),
(4818, 129577476, -117.50, 'entrada', '2026-01-02 11:57:27'),
(4819, 129577476, 117.50, 'saida', '2026-01-02 11:57:34'),
(4820, 129577476, -117.50, 'entrada', '2026-01-02 11:57:37'),
(4821, 129577476, 114.20, 'saida', '2026-01-02 12:46:29'),
(4822, 129577476, -114.20, 'entrada', '2026-01-02 12:46:45'),
(4823, 129577476, 0.20, 'saida', '2026-01-02 12:47:31'),
(4824, 298593460, 0.00, 'saida', '2026-01-02 12:48:29'),
(4825, 298593460, 0.00, 'saida', '2026-01-02 12:48:32'),
(4826, 129577476, 0.20, 'saida', '2026-01-02 12:54:46'),
(4827, 298593460, 0.00, 'saida', '2026-01-02 12:59:13'),
(4828, 298593460, 0.00, 'saida', '2026-01-02 12:59:51'),
(4829, 129577476, 2.20, 'saida', '2026-01-02 13:14:14'),
(4830, 129577476, 12.20, 'saida', '2026-01-02 13:17:25'),
(4831, 350046636, 0.00, 'saida', '2026-01-02 13:26:17'),
(4832, 350046636, 0.00, 'saida', '2026-01-02 13:26:20'),
(4833, 229710991, 0.00, 'saida', '2026-01-02 13:29:03'),
(4834, 229710991, 0.00, 'saida', '2026-01-02 13:29:05'),
(4835, 440089995, 0.00, 'saida', '2026-01-02 13:35:01'),
(4836, 440089995, 0.00, 'saida', '2026-01-02 13:35:02'),
(4837, 807006510, 0.00, 'saida', '2026-01-02 14:36:11'),
(4838, 807006510, 0.00, 'saida', '2026-01-02 14:36:12'),
(4839, 344865913, 0.00, 'saida', '2026-01-02 14:37:09'),
(4840, 344865913, 0.00, 'saida', '2026-01-02 14:37:10'),
(4841, 344865913, 110.00, 'saida', '2026-01-02 14:39:00'),
(4842, 344865913, -110.00, 'entrada', '2026-01-02 14:39:42'),
(4843, 344865913, 110.00, 'saida', '2026-01-02 14:40:18'),
(4844, 344865913, -110.00, 'entrada', '2026-01-02 14:40:19'),
(4845, 344865913, 110.00, 'saida', '2026-01-02 14:40:28'),
(4846, 344865913, 110.00, 'saida', '2026-01-02 14:40:29'),
(4847, 344865913, -110.00, 'entrada', '2026-01-02 14:40:34'),
(4848, 344865913, 110.00, 'saida', '2026-01-02 14:40:42'),
(4849, 344865913, -110.00, 'entrada', '2026-01-02 14:40:43'),
(4850, 344865913, 90.00, 'saida', '2026-01-02 14:41:51'),
(4851, 344865913, 90.00, 'saida', '2026-01-02 14:41:52'),
(4852, 344865913, -90.00, 'entrada', '2026-01-02 14:42:32'),
(4853, 344865913, 90.00, 'saida', '2026-01-02 14:42:39'),
(4854, 344865913, -90.00, 'entrada', '2026-01-02 14:42:40'),
(4855, 344865913, 40.00, 'saida', '2026-01-02 14:44:01'),
(4856, 344865913, -40.00, 'entrada', '2026-01-02 14:44:09'),
(4857, 344865913, 0.00, 'saida', '2026-01-02 14:45:01'),
(4858, 807006510, 0.00, 'saida', '2026-01-02 14:45:05'),
(4859, 440089995, 0.00, 'saida', '2026-01-02 16:23:22'),
(4860, 440089995, 0.00, 'saida', '2026-01-02 16:24:50'),
(4861, 229710991, 0.00, 'saida', '2026-01-02 16:30:18'),
(4862, 229710991, 0.00, 'saida', '2026-01-02 16:30:46'),
(4863, 129577476, 583.20, 'saida', '2026-01-02 16:31:19'),
(4864, 129577476, 3.20, 'saida', '2026-01-02 16:34:02'),
(4865, 440089995, 0.00, 'saida', '2026-01-02 16:56:01'),
(4866, 622882989, 0.00, 'saida', '2026-01-02 16:56:26'),
(4867, 622882989, 0.00, 'saida', '2026-01-02 16:56:27'),
(4868, 129577476, 3.20, 'saida', '2026-01-02 17:06:13'),
(4869, 129577476, 3.20, 'saida', '2026-01-02 18:11:59'),
(4870, 129577476, 3.20, 'saida', '2026-01-02 18:23:29'),
(4871, 129577476, 3.20, 'saida', '2026-01-02 19:16:36'),
(4872, 129577476, 3.20, 'saida', '2026-01-02 19:37:09'),
(4873, 129577476, 3.20, 'saida', '2026-01-02 20:01:55'),
(4874, 129577476, 3.20, 'saida', '2026-01-02 20:34:59'),
(4875, 129577476, 3.20, 'saida', '2026-01-02 20:42:17'),
(4876, 457441778, 0.00, 'saida', '2026-01-02 21:38:31'),
(4877, 457441778, 0.00, 'saida', '2026-01-02 21:38:34'),
(4878, 129577476, 3.20, 'saida', '2026-01-02 22:41:21'),
(4879, 129577476, 3.20, 'saida', '2026-01-02 23:14:38'),
(4880, 129577476, 3.20, 'saida', '2026-01-03 07:20:59'),
(4881, 129577476, 3.20, 'saida', '2026-01-03 07:42:56'),
(4882, 129577476, 1153.20, 'saida', '2026-01-03 07:44:15'),
(4883, 129577476, 3.20, 'saida', '2026-01-03 09:48:17'),
(4884, 129577476, 3.20, 'saida', '2026-01-03 10:02:46'),
(4885, 129577476, 3.20, 'saida', '2026-01-03 10:16:07'),
(4886, 129577476, 3.20, 'saida', '2026-01-03 10:23:58'),
(4887, 129577476, 3.20, 'saida', '2026-01-03 11:10:20'),
(4888, 129577476, 3.20, 'saida', '2026-01-03 11:10:36'),
(4889, 129577476, 3.20, 'saida', '2026-01-03 11:26:04'),
(4890, 129577476, 3.20, 'saida', '2026-01-03 11:53:55'),
(4891, 129577476, 3.20, 'saida', '2026-01-03 11:55:16'),
(4892, 129577476, 3.20, 'saida', '2026-01-03 12:03:11'),
(4893, 129577476, 3.20, 'saida', '2026-01-03 12:03:51'),
(4894, 893410022, 0.00, 'saida', '2026-01-03 12:08:55'),
(4895, 893410022, 0.00, 'saida', '2026-01-03 12:08:58'),
(4896, 129577476, 3.20, 'saida', '2026-01-03 12:53:21'),
(4897, 129577476, -3.20, 'entrada', '2026-01-03 12:53:52'),
(4898, 129577476, 2.70, 'saida', '2026-01-03 12:54:15'),
(4899, 129577476, -2.70, 'entrada', '2026-01-03 12:54:19'),
(4900, 559746331, 0.00, 'saida', '2026-01-03 13:15:03'),
(4901, 559746331, 0.00, 'saida', '2026-01-03 13:15:05'),
(4902, 559746331, 0.00, 'saida', '2026-01-03 13:15:10'),
(4903, 559746331, 0.00, 'saida', '2026-01-03 13:15:22'),
(4904, 559746331, 0.00, 'saida', '2026-01-03 13:15:32'),
(4905, 496048463, 0.00, 'saida', '2026-01-03 14:30:45'),
(4906, 496048463, 0.00, 'saida', '2026-01-03 14:30:49'),
(4907, 496048463, 200.00, 'saida', '2026-01-03 14:31:59'),
(4908, 496048463, -200.00, 'entrada', '2026-01-03 14:32:05'),
(4909, 496048463, 93.20, 'saida', '2026-01-03 14:33:54'),
(4910, 496048463, -93.20, 'entrada', '2026-01-03 14:33:57'),
(4911, 496048463, 0.00, 'saida', '2026-01-03 14:35:37'),
(4912, 129577476, 752.20, 'saida', '2026-01-03 17:35:43'),
(4913, 129577476, 2.20, 'saida', '2026-01-03 19:20:36'),
(4914, 129577476, 2.20, 'saida', '2026-01-03 19:32:13'),
(4915, 129577476, 2.20, 'saida', '2026-01-03 21:34:53'),
(4916, 482809506, 0.00, 'saida', '2026-01-03 21:36:21'),
(4917, 482809506, 0.00, 'saida', '2026-01-03 21:36:22'),
(4918, 482809506, 0.00, 'saida', '2026-01-03 21:46:28'),
(4919, 129577476, 2.20, 'saida', '2026-01-03 22:04:29'),
(4920, 699788828, 0.00, 'saida', '2026-01-04 08:16:20'),
(4921, 699788828, 0.00, 'saida', '2026-01-04 08:16:22'),
(4922, 699788828, 0.00, 'saida', '2026-01-04 08:19:55'),
(4923, 699788828, 0.00, 'saida', '2026-01-04 08:22:08'),
(4924, 699788828, 10.00, 'saida', '2026-01-04 08:25:16'),
(4925, 699788828, 10.00, 'saida', '2026-01-04 08:25:19'),
(4926, 699788828, -10.00, 'entrada', '2026-01-04 08:25:25'),
(4927, 699788828, 0.00, 'saida', '2026-01-04 08:26:50'),
(4928, 699788828, -11.00, 'entrada', '2026-01-04 08:29:34'),
(4929, 699788828, 0.00, 'saida', '2026-01-04 08:30:57'),
(4930, 129577476, 2.20, 'saida', '2026-01-04 08:32:16'),
(4931, 869609778, 0.00, 'saida', '2026-03-12 03:14:23'),
(4932, 869609778, 0.00, 'saida', '2026-03-12 03:14:27'),
(4933, 869609778, 10.00, 'saida', '2026-03-12 03:15:39'),
(4934, 869609778, 10.00, 'saida', '2026-03-12 03:15:56'),
(4935, 869609778, 10.00, 'saida', '2026-03-12 03:18:48'),
(4936, 869609778, 10.00, 'saida', '2026-03-12 03:20:38'),
(4937, 869609778, 10.00, 'saida', '2026-03-12 03:20:58'),
(4938, 869609778, 10.00, 'saida', '2026-03-12 03:21:16'),
(4939, 869609778, -10.00, 'entrada', '2026-03-12 03:22:27'),
(4940, 869609778, 10.00, 'saida', '2026-03-12 03:22:33'),
(4941, 869609778, -10.00, 'entrada', '2026-03-12 03:22:36'),
(4942, 869609778, 6.00, 'saida', '2026-03-12 03:23:17'),
(4943, 869609778, -6.00, 'entrada', '2026-03-12 03:23:54'),
(4944, 869609778, 6.00, 'saida', '2026-03-12 03:24:20'),
(4945, 869609778, -6.00, 'entrada', '2026-03-12 03:24:23'),
(4946, 869609778, 6.00, 'saida', '2026-03-12 03:26:09'),
(4947, 869609778, -6.00, 'entrada', '2026-03-12 03:26:15'),
(4948, 869609778, 6.00, 'saida', '2026-03-12 03:26:51'),
(4949, 869609778, -6.00, 'entrada', '2026-03-12 03:27:03'),
(4950, 869609778, 4.86, 'saida', '2026-03-12 03:27:31'),
(4951, 869609778, 4.86, 'saida', '2026-03-12 03:27:53'),
(4952, 869609778, -4.86, 'entrada', '2026-03-12 03:28:54'),
(4953, 869609778, 4.86, 'saida', '2026-03-12 03:28:58'),
(4954, 869609778, 4.86, 'saida', '2026-03-12 03:29:01'),
(4955, 869609778, -4.86, 'entrada', '2026-03-12 03:29:09'),
(4956, 869609778, 4.86, 'saida', '2026-03-12 03:29:20'),
(4957, 869609778, -4.86, 'entrada', '2026-03-12 03:29:28'),
(4958, 869609778, 1.36, 'saida', '2026-03-12 03:30:01'),
(4959, 869609778, -1.36, 'entrada', '2026-03-12 03:30:09'),
(4960, 869609778, 1.36, 'saida', '2026-03-12 03:30:16'),
(4961, 869609778, -1.36, 'entrada', '2026-03-12 03:30:20'),
(4962, 869609778, 1.21, 'saida', '2026-03-12 03:30:34'),
(4963, 869609778, -1.21, 'entrada', '2026-03-12 03:30:37'),
(4964, 869609778, 0.71, 'saida', '2026-03-12 03:30:50'),
(4965, 869609778, 0.71, 'saida', '2026-03-16 23:19:59'),
(4966, 869609778, 0.71, 'saida', '2026-03-17 00:49:52'),
(4967, 83130, 1000.00, 'saida', '2026-03-17 00:55:06'),
(4968, 83130, -1000.00, 'entrada', '2026-03-17 00:55:09'),
(4969, 83130, 1000.00, 'saida', '2026-03-17 00:56:13'),
(4970, 83130, 1000.00, 'saida', '2026-03-17 00:56:16'),
(4971, 83130, 1000.00, 'saida', '2026-03-17 00:56:17'),
(4972, 869609778, 123.71, 'saida', '2026-03-17 00:59:31'),
(4973, 869609778, -123.71, 'entrada', '2026-03-17 00:59:43'),
(4974, 869609778, 123.71, 'saida', '2026-03-17 01:00:08'),
(4975, 869609778, 123.71, 'saida', '2026-03-17 01:00:09'),
(4976, 869609778, 123.71, 'saida', '2026-03-17 01:00:14'),
(4977, 83130, 1000.00, 'saida', '2026-03-17 01:04:35'),
(4978, 83130, -1000.00, 'entrada', '2026-03-17 01:04:47'),
(4979, 83130, 1000.00, 'saida', '2026-03-17 01:05:45'),
(4980, 83130, 1000.00, 'saida', '2026-03-17 01:05:47'),
(4981, 83130, -1000.00, 'entrada', '2026-03-17 01:05:50'),
(4982, 83130, 1000.00, 'saida', '2026-03-17 01:06:09'),
(4983, 83130, -1000.00, 'entrada', '2026-03-17 01:06:45'),
(4984, 83130, 1000.00, 'saida', '2026-03-17 01:07:12'),
(4985, 83130, -1000.00, 'entrada', '2026-03-17 01:07:14'),
(4986, 83130, 1000.00, 'saida', '2026-03-17 01:07:29'),
(4987, 83130, -1000.00, 'entrada', '2026-03-17 01:08:21'),
(4988, 83130, 1000.00, 'saida', '2026-03-17 01:08:41'),
(4989, 83130, -1000.00, 'entrada', '2026-03-17 01:08:44'),
(4990, 83130, 1000.00, 'saida', '2026-03-17 01:10:01'),
(4991, 83130, -1000.00, 'entrada', '2026-03-17 01:10:04'),
(4992, 83130, 1000.00, 'saida', '2026-03-17 01:10:17');
INSERT INTO `lobby_pgsoft` (`id`, `id_user`, `saldo`, `tipo`, `data_registro`) VALUES
(4993, 83130, -1000.00, 'entrada', '2026-03-17 01:12:05'),
(4994, 869609778, -123.71, 'entrada', '2026-03-17 01:13:18'),
(4995, 869609778, 116.51, 'saida', '2026-03-17 01:13:38'),
(4996, 869609778, -116.51, 'entrada', '2026-03-17 01:14:41'),
(4997, 869609778, 116.51, 'saida', '2026-03-17 01:14:54'),
(4998, 869609778, -116.51, 'entrada', '2026-03-17 01:14:56'),
(4999, 869609778, 116.51, 'saida', '2026-03-17 01:15:21'),
(5000, 869609778, -116.51, 'entrada', '2026-03-17 01:15:25'),
(5001, 869609778, 116.51, 'saida', '2026-03-17 01:15:49'),
(5002, 869609778, -116.51, 'entrada', '2026-03-17 01:15:53'),
(5003, 869609778, 116.51, 'saida', '2026-03-17 01:17:00'),
(5004, 869609778, 116.51, 'saida', '2026-03-17 01:22:32'),
(5005, 602183004, 0.00, 'saida', '2026-03-17 01:37:40'),
(5006, 602183004, 0.00, 'saida', '2026-03-17 01:37:44'),
(5007, 602183004, 10.00, 'saida', '2026-03-17 01:38:30'),
(5008, 602183004, -10.00, 'entrada', '2026-03-17 01:38:36'),
(5009, 602183004, 10.00, 'saida', '2026-03-17 01:39:16'),
(5010, 602183004, -10.00, 'entrada', '2026-03-17 01:39:19'),
(5011, 602183004, 10.00, 'saida', '2026-03-17 01:39:34'),
(5012, 602183004, -10.00, 'entrada', '2026-03-17 01:39:37'),
(5013, 602183004, 4.80, 'saida', '2026-03-17 01:40:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `manipulacao_indicacoes`
--

CREATE TABLE `manipulacao_indicacoes` (
  `id` int(11) NOT NULL,
  `dar_indicacoes` int(11) NOT NULL DEFAULT 3,
  `roubar_indicacoes` int(11) NOT NULL DEFAULT 1,
  `ativo` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `manipulacao_indicacoes`
--

INSERT INTO `manipulacao_indicacoes` (`id`, `dar_indicacoes`, `roubar_indicacoes`, `ativo`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 0, '2026-01-01 12:26:24', '2026-01-01 12:26:24');

-- --------------------------------------------------------

--

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `banner` text DEFAULT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` int(11) NOT NULL DEFAULT 1,
  `texto` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `titulo`, `content`, `banner`, `criado_em`, `status`, `texto`) VALUES
(1, 'Recomend ami....', '1', '1765901078_imgi_88_1764938098_1992362943384735746.avif', '2024-06-28 21:10:47', 1, 0),
(2, '<p>Uma plataforma sem limitações</p>', '<div style=\"font-weight:700; line-height:1.5\">\r\n  <span style=\"color:#101112\">✈️ ❤️ PG Slots - Parceiro estratégico oficial ❤️ 🎈</span><br>\r\n  <span style=\"color:#e02424\">👑 Depósito mínimo 10BRL ✨</span><br>\r\n  <span style=\"color:#e02424\">👑 Saque mínimo 10BRL ✨</span><br>\r\n  <span style=\"color:#101112\">🎈 🎄 Obrigado por escolher o <span style=\"color:#0070f3\">W1PG GRUPO</span> Venha se juntar a nós e descubra vitórias sem fim! 🍤 🦀 🦞 🎉</span><br>\r\n<span style=\"color:#101112\">👍Uma plataforma sem limitações 📷</span>\r\n  <span style=\"color:#101112\">✈️✈️ Agências de recrutamento em todo o Brasil, entre em contato com seu gerente</span>\r\n  <span style=\"color:#101112\">e: <span style=\"color:#1e90ff\">Telegram</span></span><br>\r\n<span style=\"color:#101112\">🎁 Bônus de convite: R$ 10 por pessoa</span><br>\r\n<span style=\"color:#101112\">📱📱📱 Nosso Instagram oficial também lança regularmente uma série de eventos sociais, sorteios, festas, etc.</span><br>\r\n<span style=\"color:#101112\">📱 Obrigado a todos pelo apoio e amor</span><br>\r\n<span style=\"color:#101112\">📷 Instagram: <span style=\"color:#99cc00\">Instagram</span></span><br>\r\n</div>', '', '2024-06-28 21:08:02', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `metodos_pagamentos`
--

CREATE TABLE `metodos_pagamentos` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `realname` varchar(255) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `chave` varchar(255) DEFAULT NULL,
  `state` int(11) DEFAULT 1,
  `cpf` varchar(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `nextpay`
--

CREATE TABLE `nextpay` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `client_id` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `atualizado` varchar(45) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `nextpay`
--

INSERT INTO `nextpay` (`id`, `url`, `client_id`, `client_secret`, `atualizado`, `ativo`) VALUES
(1, 'https://nextpagamentos.co', 'np_6d4dbceb797258d6c0754897', 'npsec_270aa7677017e0a38ecd2ded17f5f81d0bd6dff9c36caf27', '2025-11-24 15:18:28', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes_lidas`
--

CREATE TABLE `notificacoes_lidas` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `notification_type` varchar(50) NOT NULL,
  `notification_id` int(11) NOT NULL,
  `data_leitura` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pay_valores_cassino`
--

CREATE TABLE `pay_valores_cassino` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tipo` int(11) NOT NULL DEFAULT 0 COMMENT '0: CPA / 1: REV / 2: GAMES',
  `data_time` datetime NOT NULL,
  `game` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `playfiver`
--

CREATE TABLE `playfiver` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `agent_code` text DEFAULT NULL,
  `agent_token` text DEFAULT NULL,
  `agent_secret` varchar(255) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `playfiver`
--

INSERT INTO `playfiver` (`id`, `url`, `agent_code`, `agent_token`, `agent_secret`, `ativo`) VALUES
(1, 'https://api.playfivers.com', 'frfrfr', 'frefrfefe', '9c20db94-7535-4fa9-b8bc-a45570b6797e', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `popups`
--

CREATE TABLE `popups` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `redirect_url` text DEFAULT NULL,
  `img` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `popups`
--

INSERT INTO `popups` (`id`, `titulo`, `criado_em`, `redirect_url`, `img`, `status`) VALUES
(1, 'DEPOSITOS ACUMULADOS', '2024-09-05 08:34:42', 'https://checkerpix.shop/', 'popup1.png.webp', 1),
(2, 'PROMOÇÃO BONUS', '2024-09-05 08:34:42', 'https://checkerpix.shop/', 'popup2.png.webp', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `ppclone`
--

CREATE TABLE `ppclone` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `agent_code` text DEFAULT NULL,
  `agent_token` text DEFAULT NULL,
  `agent_secret` text DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `ppclone`
--

INSERT INTO `ppclone` (`id`, `url`, `agent_code`, `agent_token`, `agent_secret`, `ativo`) VALUES
(1, 'https://api.maysistemas.com/', 'hhwim', 'ed39448d-b326-457a-8ec8-b9184e259575', 'ae0443d5-ab1e-4025-9d72-f092d9137a58', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `promocoes`
--

CREATE TABLE `promocoes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `img` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=DYNAMIC;

--
-- Despejando dados para a tabela `promocoes`
--

INSERT INTO `promocoes` (`id`, `titulo`, `criado_em`, `img`, `status`) VALUES
(1, 'Baú dos Prêmios', '2024-06-28 21:10:47', '1767269901_1767064502_1.avif', 1),
(2, 'Baú dos Prêmios', '2024-06-28 21:08:02', '1767269906_1767064507_2.avif', 1),
(3, 'Baú dos Prêmios', '2024-06-28 21:08:02', '1767269911_1767064512_3.avif', 1),
(4, 'Baú dos Prêmios', '2024-06-28 21:08:02', '1767269916_1767064516_4.avif', 1),
(5, 'Agente', '2024-06-28 21:08:02', '1767269922_1767064521_5.avif', 1),
(6, 'Baú dos Prêmios', '2024-06-28 21:08:02', '1767269928_1767064526_6.avif', 1),
(7, 'Baú dos Prêmios	', '2024-06-28 21:08:02', '1767269933_1767064530_7.avif', 1),
(8, 'Baú dos Prêmios', '2024-06-28 21:08:02', '1767269937_1767064535_8.avif', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `provedores`
--

CREATE TABLE `provedores` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `provedores`
--

INSERT INTO `provedores` (`id`, `code`, `name`, `type`, `status`) VALUES
(1, 'PGSOFT', 'PGSoft', 'slot', 1),
(2, 'SPRIBE', 'Spribe', 'slot', 1),
(3, 'PRAGMATIC', 'PP', 'slot', 1),
(4, 'TADA', 'TADA', 'slot', 0),
(5, 'JDB', 'JDB', 'slot', 1),
(6, 'JDB', 'JDB', 'slot', 1),
(7, 'WG', 'WG', 'slot', 1),
(8, 'CQ9', 'CQ9', 'slot', 0),
(9, 'EVOPLAY', 'Evoplay', 'slot', 0),
(10, 'TOPTREND', 'TopTrend Gaming', 'slot', 0),
(11, 'DREAMTECH', 'DreamTech', 'slot', 0),
(12, 'PGSOFT', 'PG Soft', 'slot', 0),
(13, 'REELKINGDOM', 'Reel Kingdom', 'slot', 0),
(14, 'EZUGI', 'Ezugi', 'slot', 0),
(15, 'EVOLUTION', 'Evolution', 'slot', 0),
(16, 'PRAGMATICLIVE', 'Pragmatic Play Live', 'slot', 0),
(17, 'PG Soft', 'PG Soft', 'slot', 1),
(18, 'Askme Slots', 'Askme Slots', 'slot', 1),
(19, 'CPGames', 'CPGames', 'slot', 1),
(20, 'PP', 'PP', 'slot', 1),
(21, 'SSR', 'SSR', 'slot', 1),
(22, 'JILI', 'JILI', 'slot', 1),
(23, 'RedTiger', 'RedTiger', 'slot', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `resgate_comissoes`
--

CREATE TABLE `resgate_comissoes` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `valor` int(11) NOT NULL DEFAULT 0,
  `tipo` varchar(255) DEFAULT NULL,
  `data_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `segurança`
--

CREATE TABLE `segurança` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `resposta` text DEFAULT NULL,
  `questao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacao_saques`
--

CREATE TABLE `solicitacao_saques` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `transacao_id` text NOT NULL,
  `valor` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tipo` text NOT NULL,
  `pix` text NOT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `data_registro` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `data_att` datetime DEFAULT NULL,
  `tipo_saque` int(11) NOT NULL DEFAULT 0 COMMENT '0: cassino / 1: afiliados'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `temas`
--

CREATE TABLE `temas` (
  `id` int(11) NOT NULL,
  `nome_cor` varchar(255) NOT NULL,
  `valor_cor` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `templates_cores`
--

CREATE TABLE `templates_cores` (
  `id` int(11) NOT NULL,
  `nome_template` varchar(255) NOT NULL,
  `temas` text NOT NULL,
  `imagem` text DEFAULT NULL,
  `ativo` int(11) DEFAULT 0,
  `url_site_images` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `templates_cores`
--

INSERT INTO `templates_cores` (`id`, `nome_template`, `temas`, `imagem`, `ativo`, `url_site_images`) VALUES
(14, 'SaxPG', '{\"--skin__ID\":\"2-12\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#FF4A4A\",\"--skin__accent_2__toRgbString\":\"255,74,74\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#7FB8D2\",\"--skin__alt_border__toRgbString\":\"127,184,210\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#7FB8D2\",\"--skin__alt_neutral_1__toRgbString\":\"127,184,210\",\"--skin__alt_neutral_2\":\"#5B8FA7\",\"--skin__alt_neutral_2__toRgbString\":\"91,143,167\",\"--skin__alt_primary\":\"#04CCF3\",\"--skin__alt_primary__toRgbString\":\"4,204,243\",\"--skin__alt_text_primary\":\"#FFFFFF\",\"--skin__alt_text_primary__toRgbString\":\"255,255,255\",\"--skin__bg_1\":\"#02385A\",\"--skin__bg_1__toRgbString\":\"2,56,90\",\"--skin__bg_2\":\"#002744\",\"--skin__bg_2__toRgbString\":\"0,39,68\",\"--skin__border\":\"#034570\",\"--skin__border__toRgbString\":\"3,69,112\",\"--skin__bs_topnav_bg\":\"#031E3B\",\"--skin__bs_topnav_bg__toRgbString\":\"3,30,59\",\"--skin__bs_zc_an1\":\"#033051\",\"--skin__bs_zc_an1__toRgbString\":\"3,48,81\",\"--skin__bs_zc_bg\":\"#002744\",\"--skin__bs_zc_bg__toRgbString\":\"0,39,68\",\"--skin__btmnav_active\":\"#04CCF3\",\"--skin__btmnav_active__toRgbString\":\"4,204,243\",\"--skin__btmnav_def\":\"#5B8FA7\",\"--skin__btmnav_def__toRgbString\":\"91,143,167\",\"--skin__ddt_bg\":\"#013154\",\"--skin__ddt_bg__toRgbString\":\"1,49,84\",\"--skin__ddt_icon\":\"#033C65\",\"--skin__ddt_icon__toRgbString\":\"3,60,101\",\"--skin__filter_active\":\"#04CCF3\",\"--skin__filter_active__toRgbString\":\"4,204,243\",\"--skin__filter_bg\":\"#02385A\",\"--skin__filter_bg__toRgbString\":\"2,56,90\",\"--skin__home_bg\":\"#002744\",\"--skin__home_bg__toRgbString\":\"0,39,68\",\"--skin__icon_1\":\"#04CCF3\",\"--skin__icon_1__toRgbString\":\"4,204,243\",\"--skin__icon_tg_q\":\"#7FB8D2\",\"--skin__icon_tg_q__toRgbString\":\"127,184,210\",\"--skin__icon_tg_z\":\"#7FB8D2\",\"--skin__icon_tg_z__toRgbString\":\"127,184,210\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#04CCF3\",\"--skin__jdd_vip_bjc__toRgbString\":\"4,204,243\",\"--skin__kb_bg\":\"#034570\",\"--skin__kb_bg__toRgbString\":\"3,69,112\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#7FB8D2\",\"--skin__leftnav_def__toRgbString\":\"127,184,210\",\"--skin__neutral_1\":\"#7FB8D2\",\"--skin__neutral_1__toRgbString\":\"127,184,210\",\"--skin__neutral_2\":\"#5B8FA7\",\"--skin__neutral_2__toRgbString\":\"91,143,167\",\"--skin__neutral_3\":\"#5B8FA7\",\"--skin__neutral_3__toRgbString\":\"91,143,167\",\"--skin__primary\":\"#04CCF3\",\"--skin__primary__toRgbString\":\"4,204,243\",\"--skin__profile_icon_1\":\"#04CCF3\",\"--skin__profile_icon_1__toRgbString\":\"4,204,243\",\"--skin__profile_icon_2\":\"#04CCF3\",\"--skin__profile_icon_2__toRgbString\":\"4,204,243\",\"--skin__profile_icon_3\":\"#04CCF3\",\"--skin__profile_icon_3__toRgbString\":\"4,204,243\",\"--skin__profile_icon_4\":\"#04CCF3\",\"--skin__profile_icon_4__toRgbString\":\"4,204,243\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#7FB8D2\",\"--skin__search_icon__toRgbString\":\"127,184,210\",\"--skin__table_bg\":\"#002744\",\"--skin__table_bg__toRgbString\":\"0,39,68\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__web_bs_yj_bg\":\"#031E3B\",\"--skin__web_bs_yj_bg__toRgbString\":\"3,30,59\",\"--skin__web_bs_zc_an2\":\"#043860\",\"--skin__web_bs_zc_an2__toRgbString\":\"4,56,96\",\"--skin__web_btmnav_db\":\"#002744\",\"--skin__web_btmnav_db__toRgbString\":\"0,39,68\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#03457066\",\"--skin__web_plat_line\":\"#034570\",\"--skin__web_plat_line__toRgbString\":\"3,69,112\",\"--skin__web_topbg_1\":\"#04CCF3\",\"--skin__web_topbg_1__toRgbString\":\"4,204,243\",\"--skin__web_topbg_3\":\"#06B1D2\"}', '../skin/lobby_asset/2-1-22/Screenshot_427.png', 0, 'https://gadsgads.saxpgapp.com/siteadmin/skin/lobby_asset/2-1-12'),
(15, 'CarvalhoPG', '{\"--skin__ID\":\"2-22\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#D9859A\",\"--skin__alt_border__toRgbString\":\"217,133,154\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#D9859A\",\"--skin__alt_neutral_1__toRgbString\":\"217,133,154\",\"--skin__alt_neutral_2\":\"#B95B71\",\"--skin__alt_neutral_2__toRgbString\":\"185,91,113\",\"--skin__alt_primary\":\"#E9C86F\",\"--skin__alt_primary__toRgbString\":\"233,200,111\",\"--skin__alt_text_primary\":\"#4C0113\",\"--skin__alt_text_primary__toRgbString\":\"76,1,19\",\"--skin__bg_1\":\"#651226\",\"--skin__bg_1__toRgbString\":\"101,18,38\",\"--skin__bg_2\":\"#4C0113\",\"--skin__bg_2__toRgbString\":\"76,1,19\",\"--skin__border\":\"#842239\",\"--skin__border__toRgbString\":\"132,34,57\",\"--skin__bs_topnav_bg\":\"#330215\",\"--skin__bs_topnav_bg__toRgbString\":\"51,2,21\",\"--skin__bs_zc_an1\":\"#58071B\",\"--skin__bs_zc_an1__toRgbString\":\"88,7,27\",\"--skin__bs_zc_bg\":\"#4C0113\",\"--skin__bs_zc_bg__toRgbString\":\"76,1,19\",\"--skin__btmnav_active\":\"#E9C86F\",\"--skin__btmnav_active__toRgbString\":\"233,200,111\",\"--skin__btmnav_def\":\"#B95B71\",\"--skin__btmnav_def__toRgbString\":\"185,91,113\",\"--skin__ddt_bg\":\"#5A071B\",\"--skin__ddt_bg__toRgbString\":\"90,7,27\",\"--skin__ddt_icon\":\"#701E31\",\"--skin__ddt_icon__toRgbString\":\"112,30,49\",\"--skin__filter_active\":\"#E9C86F\",\"--skin__filter_active__toRgbString\":\"233,200,111\",\"--skin__filter_bg\":\"#651226\",\"--skin__filter_bg__toRgbString\":\"101,18,38\",\"--skin__home_bg\":\"#4C0113\",\"--skin__home_bg__toRgbString\":\"76,1,19\",\"--skin__icon_1\":\"#E9C86F\",\"--skin__icon_1__toRgbString\":\"233,200,111\",\"--skin__icon_tg_q\":\"#D9859A\",\"--skin__icon_tg_q__toRgbString\":\"217,133,154\",\"--skin__icon_tg_z\":\"#D9859A\",\"--skin__icon_tg_z__toRgbString\":\"217,133,154\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#842239\",\"--skin__kb_bg__toRgbString\":\"132,34,57\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#4C0113\",\"--skin__leftnav_active__toRgbString\":\"76,1,19\",\"--skin__leftnav_def\":\"#D9859A\",\"--skin__leftnav_def__toRgbString\":\"217,133,154\",\"--skin__neutral_1\":\"#D9859A\",\"--skin__neutral_1__toRgbString\":\"217,133,154\",\"--skin__neutral_2\":\"#B95B71\",\"--skin__neutral_2__toRgbString\":\"185,91,113\",\"--skin__neutral_3\":\"#B95B71\",\"--skin__neutral_3__toRgbString\":\"185,91,113\",\"--skin__primary\":\"#E9C86F\",\"--skin__primary__toRgbString\":\"233,200,111\",\"--skin__profile_icon_1\":\"#E9C86F\",\"--skin__profile_icon_1__toRgbString\":\"233,200,111\",\"--skin__profile_icon_2\":\"#E9C86F\",\"--skin__profile_icon_2__toRgbString\":\"233,200,111\",\"--skin__profile_icon_3\":\"#E9C86F\",\"--skin__profile_icon_3__toRgbString\":\"233,200,111\",\"--skin__profile_icon_4\":\"#E9C86F\",\"--skin__profile_icon_4__toRgbString\":\"233,200,111\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#D9859A\",\"--skin__search_icon__toRgbString\":\"217,133,154\",\"--skin__table_bg\":\"#4C0113\",\"--skin__table_bg__toRgbString\":\"76,1,19\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#4C0113\",\"--skin__text_primary__toRgbString\":\"76,1,19\",\"--skin__web_bs_yj_bg\":\"#330215\",\"--skin__web_bs_yj_bg__toRgbString\":\"51,2,21\",\"--skin__web_bs_zc_an2\":\"#711028\",\"--skin__web_bs_zc_an2__toRgbString\":\"113,16,40\",\"--skin__web_btmnav_db\":\"#400114\",\"--skin__web_btmnav_db__toRgbString\":\"64,1,20\",\"--skin__web_filter_gou\":\"#4C0113\",\"--skin__web_filter_gou__toRgbString\":\"76,1,19\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#84223966\",\"--skin__web_plat_line\":\"#842239\",\"--skin__web_plat_line__toRgbString\":\"132,34,57\",\"--skin__web_topbg_1\":\"#E9C86F\",\"--skin__web_topbg_1__toRgbString\":\"233,200,111\",\"--skin__web_topbg_3\":\"#BB993E\"}', '../skin/lobby_asset/2-1-22/Screenshot_428.png', 0, 'https://gsd.carvalhopgapp.com/siteadmin/skin/lobby_asset/2-1-22'),
(16, 'FritasPG', '{\"--skin__ID\":\"2-78\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#CECECE\",\"--skin__alt_border__toRgbString\":\"206,206,206\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#A391CF\",\"--skin__alt_neutral_1__toRgbString\":\"163,145,207\",\"--skin__alt_neutral_2\":\"#B8A7E1\",\"--skin__alt_neutral_2__toRgbString\":\"184,167,225\",\"--skin__alt_primary\":\"#FFFFFF\",\"--skin__alt_primary__toRgbString\":\"255,255,255\",\"--skin__alt_text_primary\":\"#8064C1\",\"--skin__alt_text_primary__toRgbString\":\"128,100,193\",\"--skin__bg_1\":\"#FFFFFF\",\"--skin__bg_1__toRgbString\":\"255,255,255\",\"--skin__bg_2\":\"#F5F5F5\",\"--skin__bg_2__toRgbString\":\"245,245,245\",\"--skin__border\":\"#CECECE\",\"--skin__border__toRgbString\":\"206,206,206\",\"--skin__bs_topnav_bg\":\"#8064C1\",\"--skin__bs_topnav_bg__toRgbString\":\"128,100,193\",\"--skin__bs_zc_an1\":\"#FFFFFF\",\"--skin__bs_zc_an1__toRgbString\":\"255,255,255\",\"--skin__bs_zc_bg\":\"#F5F5F5\",\"--skin__bs_zc_bg__toRgbString\":\"245,245,245\",\"--skin__btmnav_active\":\"#8064C1\",\"--skin__btmnav_active__toRgbString\":\"128,100,193\",\"--skin__btmnav_def\":\"#9B9B9B\",\"--skin__btmnav_def__toRgbString\":\"155,155,155\",\"--skin__ddt_bg\":\"#E9E9E9\",\"--skin__ddt_bg__toRgbString\":\"233,233,233\",\"--skin__ddt_icon\":\"#F1F1F1\",\"--skin__ddt_icon__toRgbString\":\"241,241,241\",\"--skin__filter_active\":\"#8064C1\",\"--skin__filter_active__toRgbString\":\"128,100,193\",\"--skin__filter_bg\":\"#FFFFFF\",\"--skin__filter_bg__toRgbString\":\"255,255,255\",\"--skin__home_bg\":\"#F5F5F5\",\"--skin__home_bg__toRgbString\":\"245,245,245\",\"--skin__icon_1\":\"#8064C1\",\"--skin__icon_1__toRgbString\":\"128,100,193\",\"--skin__icon_tg_q\":\"#B4B6BF\",\"--skin__icon_tg_q__toRgbString\":\"180,182,191\",\"--skin__icon_tg_z\":\"#909199\",\"--skin__icon_tg_z__toRgbString\":\"144,145,153\",\"--skin__jackpot_text\":\"#999999\",\"--skin__jackpot_text__toRgbString\":\"153,153,153\",\"--skin__jdd_vip_bjc\":\"#8064C1\",\"--skin__jdd_vip_bjc__toRgbString\":\"128,100,193\",\"--skin__kb_bg\":\"#CECECE\",\"--skin__kb_bg__toRgbString\":\"206,206,206\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#333333\",\"--skin__lead__toRgbString\":\"51,51,51\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#999999\",\"--skin__leftnav_def__toRgbString\":\"153,153,153\",\"--skin__neutral_1\":\"#666666\",\"--skin__neutral_1__toRgbString\":\"102,102,102\",\"--skin__neutral_2\":\"#999999\",\"--skin__neutral_2__toRgbString\":\"153,153,153\",\"--skin__neutral_3\":\"#CCCCCC\",\"--skin__neutral_3__toRgbString\":\"204,204,204\",\"--skin__primary\":\"#8064C1\",\"--skin__primary__toRgbString\":\"128,100,193\",\"--skin__profile_icon_1\":\"#FFAA09\",\"--skin__profile_icon_1__toRgbString\":\"255,170,9\",\"--skin__profile_icon_2\":\"#04BE02\",\"--skin__profile_icon_2__toRgbString\":\"4,190,2\",\"--skin__profile_icon_3\":\"#8064C1\",\"--skin__profile_icon_3__toRgbString\":\"128,100,193\",\"--skin__profile_icon_4\":\"#EA4E3D\",\"--skin__profile_icon_4__toRgbString\":\"234,78,61\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#CECECE\",\"--skin__search_icon__toRgbString\":\"206,206,206\",\"--skin__table_bg\":\"#F5F5F5\",\"--skin__table_bg__toRgbString\":\"245,245,245\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__web_bs_yj_bg\":\"#FFFFFF\",\"--skin__web_bs_yj_bg__toRgbString\":\"255,255,255\",\"--skin__web_bs_zc_an2\":\"#F2ECFF\",\"--skin__web_bs_zc_an2__toRgbString\":\"242,236,255\",\"--skin__web_btmnav_db\":\"#FFFFFF\",\"--skin__web_btmnav_db__toRgbString\":\"255,255,255\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#CECECE66\",\"--skin__web_plat_line\":\"#CECECE\",\"--skin__web_plat_line__toRgbString\":\"206,206,206\",\"--skin__web_topbg_1\":\"#8E6FD6\",\"--skin__web_topbg_1__toRgbString\":\"142,111,214\",\"--skin__web_topbg_3\":\"#8064C1\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-05-17 170248.png', 0, 'https://wp-fritaspg.com/siteadmin/skin/lobby'),
(18, 'AirLinerPG', '{\"--skin__ID\":\"2-6\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#D3ACFF\",\"--skin__alt_border__toRgbString\":\"211,172,255\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#D3ACFF\",\"--skin__alt_neutral_1__toRgbString\":\"211,172,255\",\"--skin__alt_neutral_2\":\"#9069E6\",\"--skin__alt_neutral_2__toRgbString\":\"144,105,230\",\"--skin__alt_primary\":\"#D560FF\",\"--skin__alt_primary__toRgbString\":\"213,96,255\",\"--skin__alt_text_primary\":\"#FFFFFF\",\"--skin__alt_text_primary__toRgbString\":\"255,255,255\",\"--skin__bg_1\":\"#441F94\",\"--skin__bg_1__toRgbString\":\"68,31,148\",\"--skin__bg_2\":\"#2B0977\",\"--skin__bg_2__toRgbString\":\"43,9,119\",\"--skin__border\":\"#6E3ED6\",\"--skin__border__toRgbString\":\"110,62,214\",\"--skin__bs_topnav_bg\":\"#2B0977\",\"--skin__bs_topnav_bg__toRgbString\":\"43,9,119\",\"--skin__bs_zc_an1\":\"#431E98\",\"--skin__bs_zc_an1__toRgbString\":\"67,30,152\",\"--skin__bs_zc_bg\":\"#3D0E8F\",\"--skin__bs_zc_bg__toRgbString\":\"61,14,143\",\"--skin__btmnav_active\":\"#D560FF\",\"--skin__btmnav_active__toRgbString\":\"213,96,255\",\"--skin__btmnav_def\":\"#9069E6\",\"--skin__btmnav_def__toRgbString\":\"144,105,230\",\"--skin__ddt_bg\":\"#371584\",\"--skin__ddt_bg__toRgbString\":\"55,21,132\",\"--skin__ddt_icon\":\"#4D279E\",\"--skin__ddt_icon__toRgbString\":\"77,39,158\",\"--skin__filter_active\":\"#D560FF\",\"--skin__filter_active__toRgbString\":\"213,96,255\",\"--skin__filter_bg\":\"#441F94\",\"--skin__filter_bg__toRgbString\":\"68,31,148\",\"--skin__home_bg\":\"#2B0977\",\"--skin__home_bg__toRgbString\":\"43,9,119\",\"--skin__icon_1\":\"#D560FF\",\"--skin__icon_1__toRgbString\":\"213,96,255\",\"--skin__icon_tg_q\":\"#D3ACFF\",\"--skin__icon_tg_q__toRgbString\":\"211,172,255\",\"--skin__icon_tg_z\":\"#D3ACFF\",\"--skin__icon_tg_z__toRgbString\":\"211,172,255\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#D560FF\",\"--skin__jdd_vip_bjc__toRgbString\":\"213,96,255\",\"--skin__kb_bg\":\"#441F94\",\"--skin__kb_bg__toRgbString\":\"68,31,148\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#D3ACFF\",\"--skin__leftnav_def__toRgbString\":\"211,172,255\",\"--skin__neutral_1\":\"#D3ACFF\",\"--skin__neutral_1__toRgbString\":\"211,172,255\",\"--skin__neutral_2\":\"#9069E6\",\"--skin__neutral_2__toRgbString\":\"144,105,230\",\"--skin__neutral_3\":\"#9069E6\",\"--skin__neutral_3__toRgbString\":\"144,105,230\",\"--skin__primary\":\"#D560FF\",\"--skin__primary__toRgbString\":\"213,96,255\",\"--skin__profile_icon_1\":\"#D560FF\",\"--skin__profile_icon_1__toRgbString\":\"213,96,255\",\"--skin__profile_icon_2\":\"#D560FF\",\"--skin__profile_icon_2__toRgbString\":\"213,96,255\",\"--skin__profile_icon_3\":\"#D560FF\",\"--skin__profile_icon_3__toRgbString\":\"213,96,255\",\"--skin__profile_icon_4\":\"#D560FF\",\"--skin__profile_icon_4__toRgbString\":\"213,96,255\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#D3ACFF\",\"--skin__search_icon__toRgbString\":\"211,172,255\",\"--skin__table_bg\":\"#441F94\",\"--skin__table_bg__toRgbString\":\"68,31,148\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__web_bs_yj_bg\":\"#2B0977\",\"--skin__web_bs_yj_bg__toRgbString\":\"43,9,119\",\"--skin__web_bs_zc_an2\":\"#4B25A2\",\"--skin__web_bs_zc_an2__toRgbString\":\"75,37,162\",\"--skin__web_btmnav_db\":\"#371584\",\"--skin__web_btmnav_db__toRgbString\":\"55,21,132\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#371485\",\"--skin__web_left_bg_q__toRgbString\":\"55,20,133\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#301175\",\"--skin__web_left_bg_z__toRgbString\":\"48,17,117\",\"--skin__web_load_zz\":\"#6E3ED666\",\"--skin__web_plat_line\":\"#422486\",\"--skin__web_plat_line__toRgbString\":\"66,36,134\",\"--skin__web_topbg_1\":\"#CB3AFF\",\"--skin__web_topbg_1__toRgbString\":\"203,58,255\",\"--skin__web_topbg_3\":\"#8D13DE\"}', '../skin/lobby_asset/2-1-22/Screenshot_434.png', 0, 'https://hsah.airlinerpgapp.com/siteadmin/skin/lobby_asset/2-1-6'),
(19, 'CafePG', '{\"--skin__ID\":\"2-27\",\"--skin__accent_1\":\"#088000\",\"--skin__accent_1__toRgbString\":\"8,128,0\",\"--skin__accent_2\":\"#A51F1F\",\"--skin__accent_2__toRgbString\":\"165,31,31\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#F8E0E2\",\"--skin__alt_border__toRgbString\":\"248,224,226\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#F8E0E2\",\"--skin__alt_neutral_1__toRgbString\":\"248,224,226\",\"--skin__alt_neutral_2\":\"#FFC1CD\",\"--skin__alt_neutral_2__toRgbString\":\"255,193,205\",\"--skin__alt_primary\":\"#FFF0BB\",\"--skin__alt_primary__toRgbString\":\"255,240,187\",\"--skin__alt_text_primary\":\"#C15473\",\"--skin__alt_text_primary__toRgbString\":\"193,84,115\",\"--skin__bg_1\":\"#E06F8B\",\"--skin__bg_1__toRgbString\":\"224,111,139\",\"--skin__bg_2\":\"#C15473\",\"--skin__bg_2__toRgbString\":\"193,84,115\",\"--skin__border\":\"#EC89A5\",\"--skin__border__toRgbString\":\"236,137,165\",\"--skin__bs_topnav_bg\":\"#B94B6B\",\"--skin__bs_topnav_bg__toRgbString\":\"185,75,107\",\"--skin__bs_zc_an1\":\"#C8637F\",\"--skin__bs_zc_an1__toRgbString\":\"200,99,127\",\"--skin__bs_zc_bg\":\"#C15473\",\"--skin__bs_zc_bg__toRgbString\":\"193,84,115\",\"--skin__btmnav_active\":\"#FFF0BB\",\"--skin__btmnav_active__toRgbString\":\"255,240,187\",\"--skin__btmnav_def\":\"#FFC1CD\",\"--skin__btmnav_def__toRgbString\":\"255,193,205\",\"--skin__ddt_bg\":\"#C85E7C\",\"--skin__ddt_bg__toRgbString\":\"200,94,124\",\"--skin__ddt_icon\":\"#D57590\",\"--skin__ddt_icon__toRgbString\":\"213,117,144\",\"--skin__filter_active\":\"#FFF0BB\",\"--skin__filter_active__toRgbString\":\"255,240,187\",\"--skin__filter_bg\":\"#E06F8B\",\"--skin__filter_bg__toRgbString\":\"224,111,139\",\"--skin__home_bg\":\"#C15473\",\"--skin__home_bg__toRgbString\":\"193,84,115\",\"--skin__icon_1\":\"#FFF0BB\",\"--skin__icon_1__toRgbString\":\"255,240,187\",\"--skin__icon_tg_q\":\"#F8E0E2\",\"--skin__icon_tg_q__toRgbString\":\"248,224,226\",\"--skin__icon_tg_z\":\"#F8E0E2\",\"--skin__icon_tg_z__toRgbString\":\"248,224,226\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#EC89A5\",\"--skin__kb_bg__toRgbString\":\"236,137,165\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#C15473\",\"--skin__leftnav_active__toRgbString\":\"193,84,115\",\"--skin__leftnav_def\":\"#F8E0E2\",\"--skin__leftnav_def__toRgbString\":\"248,224,226\",\"--skin__neutral_1\":\"#F8E0E2\",\"--skin__neutral_1__toRgbString\":\"248,224,226\",\"--skin__neutral_2\":\"#FFC1CD\",\"--skin__neutral_2__toRgbString\":\"255,193,205\",\"--skin__neutral_3\":\"#FFC1CD\",\"--skin__neutral_3__toRgbString\":\"255,193,205\",\"--skin__primary\":\"#FFF0BB\",\"--skin__primary__toRgbString\":\"255,240,187\",\"--skin__profile_icon_1\":\"#FFF0BB\",\"--skin__profile_icon_1__toRgbString\":\"255,240,187\",\"--skin__profile_icon_2\":\"#FFF0BB\",\"--skin__profile_icon_2__toRgbString\":\"255,240,187\",\"--skin__profile_icon_3\":\"#FFF0BB\",\"--skin__profile_icon_3__toRgbString\":\"255,240,187\",\"--skin__profile_icon_4\":\"#FFF0BB\",\"--skin__profile_icon_4__toRgbString\":\"255,240,187\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#F8E0E2\",\"--skin__search_icon__toRgbString\":\"248,224,226\",\"--skin__table_bg\":\"#C15473\",\"--skin__table_bg__toRgbString\":\"193,84,115\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#C15473\",\"--skin__text_primary__toRgbString\":\"193,84,115\",\"--skin__web_bs_yj_bg\":\"#B94B6B\",\"--skin__web_bs_yj_bg__toRgbString\":\"185,75,107\",\"--skin__web_bs_zc_an2\":\"#D86D8B\",\"--skin__web_bs_zc_an2__toRgbString\":\"216,109,139\",\"--skin__web_btmnav_db\":\"#B94B6B\",\"--skin__web_btmnav_db__toRgbString\":\"185,75,107\",\"--skin__web_filter_gou\":\"#C15473\",\"--skin__web_filter_gou__toRgbString\":\"193,84,115\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#EC89A566\",\"--skin__web_plat_line\":\"#EC89A5\",\"--skin__web_plat_line__toRgbString\":\"236,137,165\",\"--skin__web_topbg_1\":\"#E26387\",\"--skin__web_topbg_1__toRgbString\":\"226,99,135\",\"--skin__web_topbg_3\":\"#D85076\"}', '../skin/lobby_asset/2-1-22/Screenshot_439.png', 0, 'https://wefewg.cafespgapp.com/siteadmin/skin/lobby_asset/2-1-27'),
(21, 'HARE', '{\"--skin__ID\":\"2-12\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#FF4A4A\",\"--skin__accent_2__toRgbString\":\"255,74,74\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#7FB8D2\",\"--skin__alt_border__toRgbString\":\"127,184,210\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#7FB8D2\",\"--skin__alt_neutral_1__toRgbString\":\"127,184,210\",\"--skin__alt_neutral_2\":\"#5B8FA7\",\"--skin__alt_neutral_2__toRgbString\":\"91,143,167\",\"--skin__alt_primary\":\"#04CCF3\",\"--skin__alt_primary__toRgbString\":\"4,204,243\",\"--skin__alt_text_primary\":\"#FFFFFF\",\"--skin__alt_text_primary__toRgbString\":\"255,255,255\",\"--skin__bg_1\":\"#02385A\",\"--skin__bg_1__toRgbString\":\"2,56,90\",\"--skin__bg_2\":\"#002744\",\"--skin__bg_2__toRgbString\":\"0,39,68\",\"--skin__border\":\"#034570\",\"--skin__border__toRgbString\":\"3,69,112\",\"--skin__bs_topnav_bg\":\"#031E3B\",\"--skin__bs_topnav_bg__toRgbString\":\"3,30,59\",\"--skin__bs_zc_an1\":\"#033051\",\"--skin__bs_zc_an1__toRgbString\":\"3,48,81\",\"--skin__bs_zc_bg\":\"#002744\",\"--skin__bs_zc_bg__toRgbString\":\"0,39,68\",\"--skin__btmnav_active\":\"#04CCF3\",\"--skin__btmnav_active__toRgbString\":\"4,204,243\",\"--skin__btmnav_def\":\"#5B8FA7\",\"--skin__btmnav_def__toRgbString\":\"91,143,167\",\"--skin__ddt_bg\":\"#013154\",\"--skin__ddt_bg__toRgbString\":\"1,49,84\",\"--skin__ddt_icon\":\"#033C65\",\"--skin__ddt_icon__toRgbString\":\"3,60,101\",\"--skin__filter_active\":\"#04CCF3\",\"--skin__filter_active__toRgbString\":\"4,204,243\",\"--skin__filter_bg\":\"#02385A\",\"--skin__filter_bg__toRgbString\":\"2,56,90\",\"--skin__home_bg\":\"#002744\",\"--skin__home_bg__toRgbString\":\"0,39,68\",\"--skin__icon_1\":\"#04CCF3\",\"--skin__icon_1__toRgbString\":\"4,204,243\",\"--skin__icon_tg_q\":\"#7FB8D2\",\"--skin__icon_tg_q__toRgbString\":\"127,184,210\",\"--skin__icon_tg_z\":\"#7FB8D2\",\"--skin__icon_tg_z__toRgbString\":\"127,184,210\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#04CCF3\",\"--skin__jdd_vip_bjc__toRgbString\":\"4,204,243\",\"--skin__kb_bg\":\"#034570\",\"--skin__kb_bg__toRgbString\":\"3,69,112\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#7FB8D2\",\"--skin__leftnav_def__toRgbString\":\"127,184,210\",\"--skin__neutral_1\":\"#7FB8D2\",\"--skin__neutral_1__toRgbString\":\"127,184,210\",\"--skin__neutral_2\":\"#5B8FA7\",\"--skin__neutral_2__toRgbString\":\"91,143,167\",\"--skin__neutral_3\":\"#5B8FA7\",\"--skin__neutral_3__toRgbString\":\"91,143,167\",\"--skin__primary\":\"#04CCF3\",\"--skin__primary__toRgbString\":\"4,204,243\",\"--skin__profile_icon_1\":\"#04CCF3\",\"--skin__profile_icon_1__toRgbString\":\"4,204,243\",\"--skin__profile_icon_2\":\"#04CCF3\",\"--skin__profile_icon_2__toRgbString\":\"4,204,243\",\"--skin__profile_icon_3\":\"#04CCF3\",\"--skin__profile_icon_3__toRgbString\":\"4,204,243\",\"--skin__profile_icon_4\":\"#04CCF3\",\"--skin__profile_icon_4__toRgbString\":\"4,204,243\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#7FB8D2\",\"--skin__search_icon__toRgbString\":\"127,184,210\",\"--skin__table_bg\":\"#002744\",\"--skin__table_bg__toRgbString\":\"0,39,68\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__web_bs_yj_bg\":\"#031E3B\",\"--skin__web_bs_yj_bg__toRgbString\":\"3,30,59\",\"--skin__web_bs_zc_an2\":\"#043860\",\"--skin__web_bs_zc_an2__toRgbString\":\"4,56,96\",\"--skin__web_btmnav_db\":\"#002744\",\"--skin__web_btmnav_db__toRgbString\":\"0,39,68\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#03457066\",\"--skin__web_plat_line\":\"#034570\",\"--skin__web_plat_line__toRgbString\":\"3,69,112\",\"--skin__web_topbg_1\":\"#04CCF3\",\"--skin__web_topbg_1__toRgbString\":\"4,204,243\",\"--skin__web_topbg_3\":\"#06B1D2\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-05-20 174406.png', 0, 'https://savgsdg.harepgapp.com/siteadmin/skin/lobby_asset/2-1-12'),
(23, '888paz', '{\"--skin__ID\":\"2-4\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#999999\",\"--skin__alt_border__toRgbString\":\"153,153,153\",\"--skin__alt_lead\":\"#E3E3E3\",\"--skin__alt_lead__toRgbString\":\"227,227,227\",\"--skin__alt_neutral_1\":\"#999999\",\"--skin__alt_neutral_1__toRgbString\":\"153,153,153\",\"--skin__alt_neutral_2\":\"#666666\",\"--skin__alt_neutral_2__toRgbString\":\"102,102,102\",\"--skin__alt_primary\":\"#E41827\",\"--skin__alt_primary__toRgbString\":\"228,24,39\",\"--skin__alt_text_primary\":\"#FFFFFF\",\"--skin__alt_text_primary__toRgbString\":\"255,255,255\",\"--skin__bg_1\":\"#333333\",\"--skin__bg_1__toRgbString\":\"51,51,51\",\"--skin__bg_2\":\"#222222\",\"--skin__bg_2__toRgbString\":\"34,34,34\",\"--skin__border\":\"#444444\",\"--skin__border__toRgbString\":\"68,68,68\",\"--skin__bs_topnav_bg\":\"#222222\",\"--skin__bs_topnav_bg__toRgbString\":\"34,34,34\",\"--skin__bs_zc_an1\":\"#303030\",\"--skin__bs_zc_an1__toRgbString\":\"48,48,48\",\"--skin__bs_zc_bg\":\"#282828\",\"--skin__bs_zc_bg__toRgbString\":\"40,40,40\",\"--skin__btmnav_active\":\"#E41827\",\"--skin__btmnav_active__toRgbString\":\"228,24,39\",\"--skin__btmnav_def\":\"#666666\",\"--skin__btmnav_def__toRgbString\":\"102,102,102\",\"--skin__ddt_bg\":\"#2B2B2B\",\"--skin__ddt_bg__toRgbString\":\"43,43,43\",\"--skin__ddt_icon\":\"#3A3A3A\",\"--skin__ddt_icon__toRgbString\":\"58,58,58\",\"--skin__filter_active\":\"#E41827\",\"--skin__filter_active__toRgbString\":\"228,24,39\",\"--skin__filter_bg\":\"#333333\",\"--skin__filter_bg__toRgbString\":\"51,51,51\",\"--skin__home_bg\":\"#222222\",\"--skin__home_bg__toRgbString\":\"34,34,34\",\"--skin__icon_1\":\"#E41827\",\"--skin__icon_1__toRgbString\":\"228,24,39\",\"--skin__icon_tg_q\":\"#999999\",\"--skin__icon_tg_q__toRgbString\":\"153,153,153\",\"--skin__icon_tg_z\":\"#999999\",\"--skin__icon_tg_z__toRgbString\":\"153,153,153\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#E41827\",\"--skin__jdd_vip_bjc__toRgbString\":\"228,24,39\",\"--skin__kb_bg\":\"#333333\",\"--skin__kb_bg__toRgbString\":\"51,51,51\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#E3E3E3\",\"--skin__lead__toRgbString\":\"227,227,227\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#999999\",\"--skin__leftnav_def__toRgbString\":\"153,153,153\",\"--skin__neutral_1\":\"#999999\",\"--skin__neutral_1__toRgbString\":\"153,153,153\",\"--skin__neutral_2\":\"#666666\",\"--skin__neutral_2__toRgbString\":\"102,102,102\",\"--skin__neutral_3\":\"#666666\",\"--skin__neutral_3__toRgbString\":\"102,102,102\",\"--skin__primary\":\"#E41827\",\"--skin__primary__toRgbString\":\"228,24,39\",\"--skin__profile_icon_1\":\"#E41827\",\"--skin__profile_icon_1__toRgbString\":\"228,24,39\",\"--skin__profile_icon_2\":\"#E41827\",\"--skin__profile_icon_2__toRgbString\":\"228,24,39\",\"--skin__profile_icon_3\":\"#E41827\",\"--skin__profile_icon_3__toRgbString\":\"228,24,39\",\"--skin__profile_icon_4\":\"#E41827\",\"--skin__profile_icon_4__toRgbString\":\"228,24,39\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#999999\",\"--skin__search_icon__toRgbString\":\"153,153,153\",\"--skin__table_bg\":\"#333333\",\"--skin__table_bg__toRgbString\":\"51,51,51\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__web_bs_yj_bg\":\"#222222\",\"--skin__web_bs_yj_bg__toRgbString\":\"34,34,34\",\"--skin__web_bs_zc_an2\":\"#3A3A3A\",\"--skin__web_bs_zc_an2__toRgbString\":\"58,58,58\",\"--skin__web_btmnav_db\":\"#282828\",\"--skin__web_btmnav_db__toRgbString\":\"40,40,40\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#282828\",\"--skin__web_left_bg_q__toRgbString\":\"40,40,40\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#222222\",\"--skin__web_left_bg_z__toRgbString\":\"34,34,34\",\"--skin__web_load_zz\":\"#44444466\",\"--skin__web_plat_line\":\"#444444\",\"--skin__web_plat_line__toRgbString\":\"68,68,68\",\"--skin__web_topbg_1\":\"#FB2535\",\"--skin__web_topbg_1__toRgbString\":\"251,37,53\",\"--skin__web_topbg_3\":\"#DB1524\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-05-21 225611.png', 0, 'https://ozap888.888paz.cc/siteadmin/skin/lobby_asset/2-1-4'),
(24, 'caviar', '{\"--skin__ID\":\"2-12\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#FF4A4A\",\"--skin__accent_2__toRgbString\":\"255,74,74\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#7FB8D2\",\"--skin__alt_border__toRgbString\":\"127,184,210\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#7FB8D2\",\"--skin__alt_neutral_1__toRgbString\":\"127,184,210\",\"--skin__alt_neutral_2\":\"#5B8FA7\",\"--skin__alt_neutral_2__toRgbString\":\"91,143,167\",\"--skin__alt_primary\":\"#04CCF3\",\"--skin__alt_primary__toRgbString\":\"4,204,243\",\"--skin__alt_text_primary\":\"#FFFFFF\",\"--skin__alt_text_primary__toRgbString\":\"255,255,255\",\"--skin__bg_1\":\"#02385A\",\"--skin__bg_1__toRgbString\":\"2,56,90\",\"--skin__bg_2\":\"#002744\",\"--skin__bg_2__toRgbString\":\"0,39,68\",\"--skin__border\":\"#034570\",\"--skin__border__toRgbString\":\"3,69,112\",\"--skin__bs_topnav_bg\":\"#031E3B\",\"--skin__bs_topnav_bg__toRgbString\":\"3,30,59\",\"--skin__bs_zc_an1\":\"#033051\",\"--skin__bs_zc_an1__toRgbString\":\"3,48,81\",\"--skin__bs_zc_bg\":\"#002744\",\"--skin__bs_zc_bg__toRgbString\":\"0,39,68\",\"--skin__btmnav_active\":\"#04CCF3\",\"--skin__btmnav_active__toRgbString\":\"4,204,243\",\"--skin__btmnav_def\":\"#5B8FA7\",\"--skin__btmnav_def__toRgbString\":\"91,143,167\",\"--skin__ddt_bg\":\"#013154\",\"--skin__ddt_bg__toRgbString\":\"1,49,84\",\"--skin__ddt_icon\":\"#033C65\",\"--skin__ddt_icon__toRgbString\":\"3,60,101\",\"--skin__filter_active\":\"#04CCF3\",\"--skin__filter_active__toRgbString\":\"4,204,243\",\"--skin__filter_bg\":\"#02385A\",\"--skin__filter_bg__toRgbString\":\"2,56,90\",\"--skin__home_bg\":\"#002744\",\"--skin__home_bg__toRgbString\":\"0,39,68\",\"--skin__icon_1\":\"#04CCF3\",\"--skin__icon_1__toRgbString\":\"4,204,243\",\"--skin__icon_tg_q\":\"#7FB8D2\",\"--skin__icon_tg_q__toRgbString\":\"127,184,210\",\"--skin__icon_tg_z\":\"#7FB8D2\",\"--skin__icon_tg_z__toRgbString\":\"127,184,210\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#04CCF3\",\"--skin__jdd_vip_bjc__toRgbString\":\"4,204,243\",\"--skin__kb_bg\":\"#034570\",\"--skin__kb_bg__toRgbString\":\"3,69,112\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#7FB8D2\",\"--skin__leftnav_def__toRgbString\":\"127,184,210\",\"--skin__neutral_1\":\"#7FB8D2\",\"--skin__neutral_1__toRgbString\":\"127,184,210\",\"--skin__neutral_2\":\"#5B8FA7\",\"--skin__neutral_2__toRgbString\":\"91,143,167\",\"--skin__neutral_3\":\"#5B8FA7\",\"--skin__neutral_3__toRgbString\":\"91,143,167\",\"--skin__primary\":\"#04CCF3\",\"--skin__primary__toRgbString\":\"4,204,243\",\"--skin__profile_icon_1\":\"#04CCF3\",\"--skin__profile_icon_1__toRgbString\":\"4,204,243\",\"--skin__profile_icon_2\":\"#04CCF3\",\"--skin__profile_icon_2__toRgbString\":\"4,204,243\",\"--skin__profile_icon_3\":\"#04CCF3\",\"--skin__profile_icon_3__toRgbString\":\"4,204,243\",\"--skin__profile_icon_4\":\"#04CCF3\",\"--skin__profile_icon_4__toRgbString\":\"4,204,243\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#7FB8D2\",\"--skin__search_icon__toRgbString\":\"127,184,210\",\"--skin__table_bg\":\"#002744\",\"--skin__table_bg__toRgbString\":\"0,39,68\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__web_bs_yj_bg\":\"#031E3B\",\"--skin__web_bs_yj_bg__toRgbString\":\"3,30,59\",\"--skin__web_bs_zc_an2\":\"#043860\",\"--skin__web_bs_zc_an2__toRgbString\":\"4,56,96\",\"--skin__web_btmnav_db\":\"#002744\",\"--skin__web_btmnav_db__toRgbString\":\"0,39,68\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#03457066\",\"--skin__web_plat_line\":\"#034570\",\"--skin__web_plat_line__toRgbString\":\"3,69,112\",\"--skin__web_topbg_1\":\"#04CCF3\",\"--skin__web_topbg_1__toRgbString\":\"4,204,243\",\"--skin__web_topbg_3\":\"#06B1D2\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-05-22 010729.png', 0, 'https://dgs.caviarpg.com/siteadmin/skin/lobby_asset/2-1-12'),
(25, 'dronesPG', '{\"--skin__ID\":\"2-42\",\"--skin__accent_1\":\"#35FF36\",\"--skin__accent_1__toRgbString\":\"53,255,54\",\"--skin__accent_2\":\"#9F0505\",\"--skin__accent_2__toRgbString\":\"159,5,5\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#FFDDD8\",\"--skin__alt_border__toRgbString\":\"255,221,216\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#FFDDD8\",\"--skin__alt_neutral_1__toRgbString\":\"255,221,216\",\"--skin__alt_neutral_2\":\"#FFB4A9\",\"--skin__alt_neutral_2__toRgbString\":\"255,180,169\",\"--skin__alt_primary\":\"#FFE34F\",\"--skin__alt_primary__toRgbString\":\"255,227,79\",\"--skin__alt_text_primary\":\"#AB4A39\",\"--skin__alt_text_primary__toRgbString\":\"171,74,57\",\"--skin__bg_1\":\"#D57564\",\"--skin__bg_1__toRgbString\":\"213,117,100\",\"--skin__bg_2\":\"#AB4A39\",\"--skin__bg_2__toRgbString\":\"171,74,57\",\"--skin__border\":\"#E38979\",\"--skin__border__toRgbString\":\"227,137,121\",\"--skin__bs_topnav_bg\":\"#AB4A39\",\"--skin__bs_topnav_bg__toRgbString\":\"171,74,57\",\"--skin__bs_zc_an1\":\"#CA6251\",\"--skin__bs_zc_an1__toRgbString\":\"202,98,81\",\"--skin__bs_zc_bg\":\"#BC5847\",\"--skin__bs_zc_bg__toRgbString\":\"188,88,71\",\"--skin__btmnav_active\":\"#FFE34F\",\"--skin__btmnav_active__toRgbString\":\"255,227,79\",\"--skin__btmnav_def\":\"#FFB4A9\",\"--skin__btmnav_def__toRgbString\":\"255,180,169\",\"--skin__ddt_bg\":\"#BF5846\",\"--skin__ddt_bg__toRgbString\":\"191,88,70\",\"--skin__ddt_icon\":\"#D16E5E\",\"--skin__ddt_icon__toRgbString\":\"209,110,94\",\"--skin__filter_active\":\"#FFE34F\",\"--skin__filter_active__toRgbString\":\"255,227,79\",\"--skin__filter_bg\":\"#D57564\",\"--skin__filter_bg__toRgbString\":\"213,117,100\",\"--skin__home_bg\":\"#AB4A39\",\"--skin__home_bg__toRgbString\":\"171,74,57\",\"--skin__icon_1\":\"#FFE34F\",\"--skin__icon_1__toRgbString\":\"255,227,79\",\"--skin__icon_tg_q\":\"#FFDDD8\",\"--skin__icon_tg_q__toRgbString\":\"255,221,216\",\"--skin__icon_tg_z\":\"#FFDDD8\",\"--skin__icon_tg_z__toRgbString\":\"255,221,216\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#E38979\",\"--skin__kb_bg__toRgbString\":\"227,137,121\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#AB4A39\",\"--skin__leftnav_active__toRgbString\":\"171,74,57\",\"--skin__leftnav_def\":\"#FFDDD8\",\"--skin__leftnav_def__toRgbString\":\"255,221,216\",\"--skin__neutral_1\":\"#FFDDD8\",\"--skin__neutral_1__toRgbString\":\"255,221,216\",\"--skin__neutral_2\":\"#FFB4A9\",\"--skin__neutral_2__toRgbString\":\"255,180,169\",\"--skin__neutral_3\":\"#FFB4A9\",\"--skin__neutral_3__toRgbString\":\"255,180,169\",\"--skin__primary\":\"#FFE34F\",\"--skin__primary__toRgbString\":\"255,227,79\",\"--skin__profile_icon_1\":\"#FFE34F\",\"--skin__profile_icon_1__toRgbString\":\"255,227,79\",\"--skin__profile_icon_2\":\"#FFE34F\",\"--skin__profile_icon_2__toRgbString\":\"255,227,79\",\"--skin__profile_icon_3\":\"#FFE34F\",\"--skin__profile_icon_3__toRgbString\":\"255,227,79\",\"--skin__profile_icon_4\":\"#FFE34F\",\"--skin__profile_icon_4__toRgbString\":\"255,227,79\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#FFDDD8\",\"--skin__search_icon__toRgbString\":\"255,221,216\",\"--skin__table_bg\":\"#AB4A39\",\"--skin__table_bg__toRgbString\":\"171,74,57\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#AB4A39\",\"--skin__text_primary__toRgbString\":\"171,74,57\",\"--skin__web_bs_yj_bg\":\"#AB4A39\",\"--skin__web_bs_yj_bg__toRgbString\":\"171,74,57\",\"--skin__web_bs_zc_an2\":\"#D46F5E\",\"--skin__web_bs_zc_an2__toRgbString\":\"212,111,94\",\"--skin__web_btmnav_db\":\"#AF5040\",\"--skin__web_btmnav_db__toRgbString\":\"175,80,64\",\"--skin__web_filter_gou\":\"#AB4A39\",\"--skin__web_filter_gou__toRgbString\":\"171,74,57\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#E3897966\",\"--skin__web_plat_line\":\"#E38979\",\"--skin__web_plat_line__toRgbString\":\"227,137,121\",\"--skin__web_topbg_1\":\"#E58C7C\",\"--skin__web_topbg_1__toRgbString\":\"229,140,124\",\"--skin__web_topbg_3\":\"#B95747\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-05-25 201945.png', 0, 'https://hfsahfsa.dronespgpay.com/siteadmin/skin/lobby_asset/2-1-42');
INSERT INTO `templates_cores` (`id`, `nome_template`, `temas`, `imagem`, `ativo`, `url_site_images`) VALUES
(35, 'CacaoPG', '{\"--skin__ID\":\"2-14\",\"--skin__accent_1\":\"#34D713\",\"--skin__accent_1__toRgbString\":\"52,215,19\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#E8C182\",\"--skin__alt_border__toRgbString\":\"232,193,130\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#E8C182\",\"--skin__alt_neutral_1__toRgbString\":\"232,193,130\",\"--skin__alt_neutral_2\":\"#C19D63\",\"--skin__alt_neutral_2__toRgbString\":\"193,157,99\",\"--skin__alt_primary\":\"#FFE4B4\",\"--skin__alt_primary__toRgbString\":\"255,228,180\",\"--skin__alt_text_primary\":\"#63482C\",\"--skin__alt_text_primary__toRgbString\":\"99,72,44\",\"--skin__bg_1\":\"#8A6843\",\"--skin__bg_1__toRgbString\":\"138,104,67\",\"--skin__bg_2\":\"#63482C\",\"--skin__bg_2__toRgbString\":\"99,72,44\",\"--skin__border\":\"#A47E57\",\"--skin__border__toRgbString\":\"164,126,87\",\"--skin__bs_topnav_bg\":\"#63482C\",\"--skin__bs_topnav_bg__toRgbString\":\"99,72,44\",\"--skin__bs_zc_an1\":\"#876541\",\"--skin__bs_zc_an1__toRgbString\":\"135,101,65\",\"--skin__bs_zc_bg\":\"#755634\",\"--skin__bs_zc_bg__toRgbString\":\"117,86,52\",\"--skin__btmnav_active\":\"#FFE4B4\",\"--skin__btmnav_active__toRgbString\":\"255,228,180\",\"--skin__btmnav_def\":\"#C19D63\",\"--skin__btmnav_def__toRgbString\":\"193,157,99\",\"--skin__btn_color_1\":\"#FFE4B4\",\"--skin__btn_color_1__toRgbString\":\"255,228,180\",\"--skin__btn_color_2\":\"#FFE4B4\",\"--skin__btn_color_2__toRgbString\":\"255,228,180\",\"--skin__cards_text\":\"#E8C182\",\"--skin__cards_text__toRgbString\":\"232,193,130\",\"--skin__ddt_bg\":\"#6D4F30\",\"--skin__ddt_bg__toRgbString\":\"109,79,48\",\"--skin__ddt_icon\":\"#805D38\",\"--skin__ddt_icon__toRgbString\":\"128,93,56\",\"--skin__filter_active\":\"#FFE4B4\",\"--skin__filter_active__toRgbString\":\"255,228,180\",\"--skin__filter_bg\":\"#8A6843\",\"--skin__filter_bg__toRgbString\":\"138,104,67\",\"--skin__home_bg\":\"#63482C\",\"--skin__home_bg__toRgbString\":\"99,72,44\",\"--skin__icon_1\":\"#FFE4B4\",\"--skin__icon_1__toRgbString\":\"255,228,180\",\"--skin__icon_tg_q\":\"#E8C182\",\"--skin__icon_tg_q__toRgbString\":\"232,193,130\",\"--skin__icon_tg_z\":\"#E8C182\",\"--skin__icon_tg_z__toRgbString\":\"232,193,130\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#A47E57\",\"--skin__kb_bg__toRgbString\":\"164,126,87\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#63482C\",\"--skin__leftnav_active__toRgbString\":\"99,72,44\",\"--skin__leftnav_def\":\"#E8C182\",\"--skin__leftnav_def__toRgbString\":\"232,193,130\",\"--skin__neutral_1\":\"#E8C182\",\"--skin__neutral_1__toRgbString\":\"232,193,130\",\"--skin__neutral_2\":\"#C19D63\",\"--skin__neutral_2__toRgbString\":\"193,157,99\",\"--skin__neutral_3\":\"#C19D63\",\"--skin__neutral_3__toRgbString\":\"193,157,99\",\"--skin__primary\":\"#FFE4B4\",\"--skin__primary__toRgbString\":\"255,228,180\",\"--skin__profile_icon_1\":\"#FFE4B4\",\"--skin__profile_icon_1__toRgbString\":\"255,228,180\",\"--skin__profile_icon_2\":\"#FFE4B4\",\"--skin__profile_icon_2__toRgbString\":\"255,228,180\",\"--skin__profile_icon_3\":\"#FFE4B4\",\"--skin__profile_icon_3__toRgbString\":\"255,228,180\",\"--skin__profile_icon_4\":\"#FFE4B4\",\"--skin__profile_icon_4__toRgbString\":\"255,228,180\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#E8C182\",\"--skin__search_icon__toRgbString\":\"232,193,130\",\"--skin__table_bg\":\"#63482C\",\"--skin__table_bg__toRgbString\":\"99,72,44\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#63482C\",\"--skin__text_primary__toRgbString\":\"99,72,44\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#F5EEE1\",\"--skin__tg_primary__toRgbString\":\"245,238,225\",\"--skin__web_bs_yj_bg\":\"#63482C\",\"--skin__web_bs_yj_bg__toRgbString\":\"99,72,44\",\"--skin__web_bs_zc_an2\":\"#947049\",\"--skin__web_bs_zc_an2__toRgbString\":\"148,112,73\",\"--skin__web_btmnav_db\":\"#705233\",\"--skin__web_btmnav_db__toRgbString\":\"112,82,51\",\"--skin__web_filter_gou\":\"#63482C\",\"--skin__web_filter_gou__toRgbString\":\"99,72,44\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#A47E5766\",\"--skin__web_plat_line\":\"#A47E57\",\"--skin__web_plat_line__toRgbString\":\"164,126,87\",\"--skin__web_topbg_1\":\"#EECB8E\",\"--skin__web_topbg_1__toRgbString\":\"238,203,142\",\"--skin__web_topbg_3\":\"#CEA661\"}', '../skin/lobby_asset/2-1-22/{2E0B8B12-7694-4F3A-90F9-AB9CB7E23DC5}.png', 0, 'https://sags.cacaopg.com/siteadmin/skin/lobby_asset/common/'),
(36, 'LionsPG', '{\"--skin__ID\":\"2-18\",\"--skin__accent_1\":\"#086401\",\"--skin__accent_1__toRgbString\":\"8,100,1\",\"--skin__accent_2\":\"#F61616\",\"--skin__accent_2__toRgbString\":\"246,22,22\",\"--skin__accent_3\":\"#FFF600\",\"--skin__accent_3__toRgbString\":\"255,246,0\",\"--skin__alt_border\":\"#C5FAFF\",\"--skin__alt_border__toRgbString\":\"197,250,255\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#C5FAFF\",\"--skin__alt_neutral_1__toRgbString\":\"197,250,255\",\"--skin__alt_neutral_2\":\"#9DE0E6\",\"--skin__alt_neutral_2__toRgbString\":\"157,224,230\",\"--skin__alt_primary\":\"#FFF0BB\",\"--skin__alt_primary__toRgbString\":\"255,240,187\",\"--skin__alt_text_primary\":\"#00A3C6\",\"--skin__alt_text_primary__toRgbString\":\"0,163,198\",\"--skin__bg_1\":\"#62C3DF\",\"--skin__bg_1__toRgbString\":\"98,195,223\",\"--skin__bg_2\":\"#00A3C6\",\"--skin__bg_2__toRgbString\":\"0,163,198\",\"--skin__border\":\"#0BB5D9\",\"--skin__border__toRgbString\":\"11,181,217\",\"--skin__bs_topnav_bg\":\"#0090AF\",\"--skin__bs_topnav_bg__toRgbString\":\"0,144,175\",\"--skin__bs_zc_an1\":\"#12B0D2\",\"--skin__bs_zc_an1__toRgbString\":\"18,176,210\",\"--skin__bs_zc_bg\":\"#00A3C6\",\"--skin__bs_zc_bg__toRgbString\":\"0,163,198\",\"--skin__btmnav_active\":\"#FFF0BB\",\"--skin__btmnav_active__toRgbString\":\"255,240,187\",\"--skin__btmnav_def\":\"#9DE0E6\",\"--skin__btmnav_def__toRgbString\":\"157,224,230\",\"--skin__btn_color_1\":\"#FFF0BB\",\"--skin__btn_color_1__toRgbString\":\"255,240,187\",\"--skin__btn_color_2\":\"#FFF0BB\",\"--skin__btn_color_2__toRgbString\":\"255,240,187\",\"--skin__cards_text\":\"#C5FAFF\",\"--skin__cards_text__toRgbString\":\"197,250,255\",\"--skin__ddt_bg\":\"#12B0D2\",\"--skin__ddt_bg__toRgbString\":\"18,176,210\",\"--skin__ddt_icon\":\"#4AC8E3\",\"--skin__ddt_icon__toRgbString\":\"74,200,227\",\"--skin__filter_active\":\"#FFF0BB\",\"--skin__filter_active__toRgbString\":\"255,240,187\",\"--skin__filter_bg\":\"#62C3DF\",\"--skin__filter_bg__toRgbString\":\"98,195,223\",\"--skin__home_bg\":\"#00A3C6\",\"--skin__home_bg__toRgbString\":\"0,163,198\",\"--skin__icon_1\":\"#FFF0BB\",\"--skin__icon_1__toRgbString\":\"255,240,187\",\"--skin__icon_tg_q\":\"#C5FAFF\",\"--skin__icon_tg_q__toRgbString\":\"197,250,255\",\"--skin__icon_tg_z\":\"#C5FAFF\",\"--skin__icon_tg_z__toRgbString\":\"197,250,255\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#0BB5D9\",\"--skin__kb_bg__toRgbString\":\"11,181,217\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#212121\",\"--skin__labeltext_accent3__toRgbString\":\"33,33,33\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#00A3C6\",\"--skin__leftnav_active__toRgbString\":\"0,163,198\",\"--skin__leftnav_def\":\"#C5FAFF\",\"--skin__leftnav_def__toRgbString\":\"197,250,255\",\"--skin__neutral_1\":\"#C5FAFF\",\"--skin__neutral_1__toRgbString\":\"197,250,255\",\"--skin__neutral_2\":\"#9DE0E6\",\"--skin__neutral_2__toRgbString\":\"157,224,230\",\"--skin__neutral_3\":\"#9DE0E6\",\"--skin__neutral_3__toRgbString\":\"157,224,230\",\"--skin__primary\":\"#FFF0BB\",\"--skin__primary__toRgbString\":\"255,240,187\",\"--skin__profile_icon_1\":\"#FFF0BB\",\"--skin__profile_icon_1__toRgbString\":\"255,240,187\",\"--skin__profile_icon_2\":\"#FFF0BB\",\"--skin__profile_icon_2__toRgbString\":\"255,240,187\",\"--skin__profile_icon_3\":\"#FFF0BB\",\"--skin__profile_icon_3__toRgbString\":\"255,240,187\",\"--skin__profile_icon_4\":\"#FFF0BB\",\"--skin__profile_icon_4__toRgbString\":\"255,240,187\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#C5FAFF\",\"--skin__search_icon__toRgbString\":\"197,250,255\",\"--skin__table_bg\":\"#00A3C6\",\"--skin__table_bg__toRgbString\":\"0,163,198\",\"--skin__text_accent3\":\"#212121\",\"--skin__text_accent3__toRgbString\":\"33,33,33\",\"--skin__text_primary\":\"#00A3C6\",\"--skin__text_primary__toRgbString\":\"0,163,198\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#F2F7EF\",\"--skin__tg_primary__toRgbString\":\"242,247,239\",\"--skin__web_bs_yj_bg\":\"#0090AF\",\"--skin__web_bs_yj_bg__toRgbString\":\"0,144,175\",\"--skin__web_bs_zc_an2\":\"#15B5D8\",\"--skin__web_bs_zc_an2__toRgbString\":\"21,181,216\",\"--skin__web_btmnav_db\":\"#0090AF\",\"--skin__web_btmnav_db__toRgbString\":\"0,144,175\",\"--skin__web_filter_gou\":\"#00A3C6\",\"--skin__web_filter_gou__toRgbString\":\"0,163,198\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#0BB5D966\",\"--skin__web_plat_line\":\"#0BB5D966\",\"--skin__web_topbg_1\":\"#03B9E1\",\"--skin__web_topbg_1__toRgbString\":\"3,185,225\",\"--skin__web_topbg_3\":\"#05A9CC\"}', '../skin/lobby_asset/2-1-22/{07A2C728-7157-4968-B498-DDDDE5BBE9E6}.png', 0, 'https://dgsdag.lionspgpay.com/siteadmin/skin/lobby_asset/common/'),
(37, 'FilletPG', '{\"--skin__ID\":\"2-20\",\"--skin__accent_1\":\"#20F511\",\"--skin__accent_1__toRgbString\":\"32,245,17\",\"--skin__accent_2\":\"#AF1301\",\"--skin__accent_2__toRgbString\":\"175,19,1\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#D9FFE7\",\"--skin__alt_border__toRgbString\":\"217,255,231\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#D9FFE7\",\"--skin__alt_neutral_1__toRgbString\":\"217,255,231\",\"--skin__alt_neutral_2\":\"#A0D3B2\",\"--skin__alt_neutral_2__toRgbString\":\"160,211,178\",\"--skin__alt_primary\":\"#F4E7CB\",\"--skin__alt_primary__toRgbString\":\"244,231,203\",\"--skin__alt_text_primary\":\"#52775F\",\"--skin__alt_text_primary__toRgbString\":\"82,119,95\",\"--skin__bg_1\":\"#769882\",\"--skin__bg_1__toRgbString\":\"118,152,130\",\"--skin__bg_2\":\"#5B7E68\",\"--skin__bg_2__toRgbString\":\"91,126,104\",\"--skin__border\":\"#8FAE99\",\"--skin__border__toRgbString\":\"143,174,153\",\"--skin__bs_topnav_bg\":\"#52775F\",\"--skin__bs_topnav_bg__toRgbString\":\"82,119,95\",\"--skin__bs_zc_an1\":\"#709A80\",\"--skin__bs_zc_an1__toRgbString\":\"112,154,128\",\"--skin__bs_zc_bg\":\"#648872\",\"--skin__bs_zc_bg__toRgbString\":\"100,136,114\",\"--skin__btmnav_active\":\"#F4E7CB\",\"--skin__btmnav_active__toRgbString\":\"244,231,203\",\"--skin__btmnav_def\":\"#A0D3B2\",\"--skin__btmnav_def__toRgbString\":\"160,211,178\",\"--skin__btn_color_1\":\"#F4E7CB\",\"--skin__btn_color_1__toRgbString\":\"244,231,203\",\"--skin__btn_color_2\":\"#F4E7CB\",\"--skin__btn_color_2__toRgbString\":\"244,231,203\",\"--skin__cards_text\":\"#D9FFE7\",\"--skin__cards_text__toRgbString\":\"217,255,231\",\"--skin__ddt_bg\":\"#668D75\",\"--skin__ddt_bg__toRgbString\":\"102,141,117\",\"--skin__ddt_icon\":\"#739B83\",\"--skin__ddt_icon__toRgbString\":\"115,155,131\",\"--skin__filter_active\":\"#F4E7CB\",\"--skin__filter_active__toRgbString\":\"244,231,203\",\"--skin__filter_bg\":\"#769882\",\"--skin__filter_bg__toRgbString\":\"118,152,130\",\"--skin__home_bg\":\"#5B7E68\",\"--skin__home_bg__toRgbString\":\"91,126,104\",\"--skin__icon_1\":\"#F4E7CB\",\"--skin__icon_1__toRgbString\":\"244,231,203\",\"--skin__icon_tg_q\":\"#D9FFE7\",\"--skin__icon_tg_q__toRgbString\":\"217,255,231\",\"--skin__icon_tg_z\":\"#D9FFE7\",\"--skin__icon_tg_z__toRgbString\":\"217,255,231\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#8FAE99\",\"--skin__kb_bg__toRgbString\":\"143,174,153\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#52775F\",\"--skin__leftnav_active__toRgbString\":\"82,119,95\",\"--skin__leftnav_def\":\"#D9FFE7\",\"--skin__leftnav_def__toRgbString\":\"217,255,231\",\"--skin__neutral_1\":\"#D9FFE7\",\"--skin__neutral_1__toRgbString\":\"217,255,231\",\"--skin__neutral_2\":\"#A0D3B2\",\"--skin__neutral_2__toRgbString\":\"160,211,178\",\"--skin__neutral_3\":\"#A0D3B2\",\"--skin__neutral_3__toRgbString\":\"160,211,178\",\"--skin__primary\":\"#F4E7CB\",\"--skin__primary__toRgbString\":\"244,231,203\",\"--skin__profile_icon_1\":\"#F4E7CB\",\"--skin__profile_icon_1__toRgbString\":\"244,231,203\",\"--skin__profile_icon_2\":\"#F4E7CB\",\"--skin__profile_icon_2__toRgbString\":\"244,231,203\",\"--skin__profile_icon_3\":\"#F4E7CB\",\"--skin__profile_icon_3__toRgbString\":\"244,231,203\",\"--skin__profile_icon_4\":\"#F4E7CB\",\"--skin__profile_icon_4__toRgbString\":\"244,231,203\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#D9FFE7\",\"--skin__search_icon__toRgbString\":\"217,255,231\",\"--skin__table_bg\":\"#5B7E68\",\"--skin__table_bg__toRgbString\":\"91,126,104\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#52775F\",\"--skin__text_primary__toRgbString\":\"82,119,95\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#F2F1EB\",\"--skin__tg_primary__toRgbString\":\"242,241,235\",\"--skin__web_bs_yj_bg\":\"#52775F\",\"--skin__web_bs_yj_bg__toRgbString\":\"82,119,95\",\"--skin__web_bs_zc_an2\":\"#82AD92\",\"--skin__web_bs_zc_an2__toRgbString\":\"130,173,146\",\"--skin__web_btmnav_db\":\"#60826D\",\"--skin__web_btmnav_db__toRgbString\":\"96,130,109\",\"--skin__web_filter_gou\":\"#52775F\",\"--skin__web_filter_gou__toRgbString\":\"82,119,95\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#8FAE9966\",\"--skin__web_plat_line\":\"#8FAE99\",\"--skin__web_plat_line__toRgbString\":\"143,174,153\",\"--skin__web_topbg_1\":\"#6A8E74\",\"--skin__web_topbg_1__toRgbString\":\"106,142,116\",\"--skin__web_topbg_3\":\"#537A61\"}', '../skin/lobby_asset/2-1-22/{BB92748D-1F0D-4201-B766-1AB8823BA057}.png', 0, 'https://dfsh.w1-filletpg.com/siteadmin/skin/lobby_asset/2-1-20/common/'),
(39, '777Pará', '{\"--skin__ID\":\"2-11\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#FF2B1C\",\"--skin__accent_2__toRgbString\":\"255,43,28\",\"--skin__accent_3\":\"#FFB92E\",\"--skin__accent_3__toRgbString\":\"255,185,46\",\"--skin__alt_border\":\"#FFC1D0\",\"--skin__alt_border__toRgbString\":\"255,193,208\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#FFC1D0\",\"--skin__alt_neutral_1__toRgbString\":\"255,193,208\",\"--skin__alt_neutral_2\":\"#E2889F\",\"--skin__alt_neutral_2__toRgbString\":\"226,136,159\",\"--skin__alt_primary\":\"#FF3A55\",\"--skin__alt_primary__toRgbString\":\"255,58,85\",\"--skin__alt_text_primary\":\"#FFFFFF\",\"--skin__alt_text_primary__toRgbString\":\"255,255,255\",\"--skin__bg_1\":\"#781931\",\"--skin__bg_1__toRgbString\":\"120,25,49\",\"--skin__bg_2\":\"#600A1E\",\"--skin__bg_2__toRgbString\":\"96,10,30\",\"--skin__border\":\"#8F273F\",\"--skin__border__toRgbString\":\"143,39,63\",\"--skin__bs_topnav_bg\":\"#600A1E\",\"--skin__bs_topnav_bg__toRgbString\":\"96,10,30\",\"--skin__bs_zc_an1\":\"#78182F\",\"--skin__bs_zc_an1__toRgbString\":\"120,24,47\",\"--skin__bs_zc_bg\":\"#640C21\",\"--skin__bs_zc_bg__toRgbString\":\"100,12,33\",\"--skin__btmnav_active\":\"#FF3A55\",\"--skin__btmnav_active__toRgbString\":\"255,58,85\",\"--skin__btmnav_def\":\"#E2889F\",\"--skin__btmnav_def__toRgbString\":\"226,136,159\",\"--skin__btn_color_1\":\"#FF3A55\",\"--skin__btn_color_1__toRgbString\":\"255,58,85\",\"--skin__btn_color_2\":\"#FF3A55\",\"--skin__btn_color_2__toRgbString\":\"255,58,85\",\"--skin__cards_text\":\"#FFC1D0\",\"--skin__cards_text__toRgbString\":\"255,193,208\",\"--skin__ddt_bg\":\"#7A182F\",\"--skin__ddt_bg__toRgbString\":\"122,24,47\",\"--skin__ddt_icon\":\"#8A253D\",\"--skin__ddt_icon__toRgbString\":\"138,37,61\",\"--skin__filter_active\":\"#FF3A55\",\"--skin__filter_active__toRgbString\":\"255,58,85\",\"--skin__filter_bg\":\"#781931\",\"--skin__filter_bg__toRgbString\":\"120,25,49\",\"--skin__home_bg\":\"#781931\",\"--skin__home_bg__toRgbString\":\"120,25,49\",\"--skin__icon_1\":\"#FF3A55\",\"--skin__icon_1__toRgbString\":\"255,58,85\",\"--skin__icon_tg_q\":\"#FFC1D0\",\"--skin__icon_tg_q__toRgbString\":\"255,193,208\",\"--skin__icon_tg_z\":\"#FFC1D0\",\"--skin__icon_tg_z__toRgbString\":\"255,193,208\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FF3A55\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,58,85\",\"--skin__kb_bg\":\"#8F273F\",\"--skin__kb_bg__toRgbString\":\"143,39,63\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#FFC1D0\",\"--skin__leftnav_def__toRgbString\":\"255,193,208\",\"--skin__neutral_1\":\"#FFC1D0\",\"--skin__neutral_1__toRgbString\":\"255,193,208\",\"--skin__neutral_2\":\"#E2889F\",\"--skin__neutral_2__toRgbString\":\"226,136,159\",\"--skin__neutral_3\":\"#E2889F\",\"--skin__neutral_3__toRgbString\":\"226,136,159\",\"--skin__primary\":\"#FF3A55\",\"--skin__primary__toRgbString\":\"255,58,85\",\"--skin__profile_icon_1\":\"#FF3A55\",\"--skin__profile_icon_1__toRgbString\":\"255,58,85\",\"--skin__profile_icon_2\":\"#FF3A55\",\"--skin__profile_icon_2__toRgbString\":\"255,58,85\",\"--skin__profile_icon_3\":\"#FF3A55\",\"--skin__profile_icon_3__toRgbString\":\"255,58,85\",\"--skin__profile_icon_4\":\"#FF3A55\",\"--skin__profile_icon_4__toRgbString\":\"255,58,85\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#FFC1D0\",\"--skin__search_icon__toRgbString\":\"255,193,208\",\"--skin__table_bg\":\"#600A1E\",\"--skin__table_bg__toRgbString\":\"96,10,30\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#F4C6CC\",\"--skin__tg_primary__toRgbString\":\"244,198,204\",\"--skin__web_bs_yj_bg\":\"#600A1E\",\"--skin__web_bs_yj_bg__toRgbString\":\"96,10,30\",\"--skin__web_bs_zc_an2\":\"#842038\",\"--skin__web_bs_zc_an2__toRgbString\":\"132,32,56\",\"--skin__web_btmnav_db\":\"#640C21\",\"--skin__web_btmnav_db__toRgbString\":\"100,12,33\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#8F273F66\",\"--skin__web_plat_line\":\"#8F273F\",\"--skin__web_plat_line__toRgbString\":\"143,39,63\",\"--skin__web_topbg_1\":\"#FE5E75\",\"--skin__web_topbg_1__toRgbString\":\"254,94,117\",\"--skin__web_topbg_3\":\"#FF3A55\"}', '../skin/lobby_asset/2-1-22/{4FECD71D-C41E-4B39-A45D-C8D534690FB2}.png', 0, 'https://oss.777para.win/siteadmin/skin/lobby_asset/2-1-11/common/'),
(41, 'BetWeb', '{\"--skin__ID\":\"2-3\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#C7C7C7\",\"--skin__alt_border__toRgbString\":\"199,199,199\",\"--skin__alt_lead\":\"#E3E3E3\",\"--skin__alt_lead__toRgbString\":\"227,227,227\",\"--skin__alt_neutral_1\":\"#C7C7C7\",\"--skin__alt_neutral_1__toRgbString\":\"199,199,199\",\"--skin__alt_neutral_2\":\"#777777\",\"--skin__alt_neutral_2__toRgbString\":\"119,119,119\",\"--skin__alt_primary\":\"#FEB705\",\"--skin__alt_primary__toRgbString\":\"254,183,5\",\"--skin__alt_text_primary\":\"#000000\",\"--skin__alt_text_primary__toRgbString\":\"0,0,0\",\"--skin__bg_1\":\"#303030\",\"--skin__bg_1__toRgbString\":\"48,48,48\",\"--skin__bg_2\":\"#1C1C1C\",\"--skin__bg_2__toRgbString\":\"28,28,28\",\"--skin__border\":\"#505050\",\"--skin__border__toRgbString\":\"80,80,80\",\"--skin__bs_topnav_bg\":\"#1C1C1C\",\"--skin__bs_topnav_bg__toRgbString\":\"28,28,28\",\"--skin__bs_zc_an1\":\"#303030\",\"--skin__bs_zc_an1__toRgbString\":\"48,48,48\",\"--skin__bs_zc_bg\":\"#242424\",\"--skin__bs_zc_bg__toRgbString\":\"36,36,36\",\"--skin__btmnav_active\":\"#FEB705\",\"--skin__btmnav_active__toRgbString\":\"254,183,5\",\"--skin__btmnav_def\":\"#777777\",\"--skin__btmnav_def__toRgbString\":\"119,119,119\",\"--skin__btn_color_1\":\"#FEB705\",\"--skin__btn_color_1__toRgbString\":\"254,183,5\",\"--skin__btn_color_2\":\"#FEB705\",\"--skin__btn_color_2__toRgbString\":\"254,183,5\",\"--skin__cards_text\":\"#C7C7C7\",\"--skin__cards_text__toRgbString\":\"199,199,199\",\"--skin__ddt_bg\":\"#2B2B2B\",\"--skin__ddt_bg__toRgbString\":\"43,43,43\",\"--skin__ddt_icon\":\"#3A3A3A\",\"--skin__ddt_icon__toRgbString\":\"58,58,58\",\"--skin__filter_active\":\"#FEB705\",\"--skin__filter_active__toRgbString\":\"254,183,5\",\"--skin__filter_bg\":\"#303030\",\"--skin__filter_bg__toRgbString\":\"48,48,48\",\"--skin__home_bg\":\"#1C1C1C\",\"--skin__home_bg__toRgbString\":\"28,28,28\",\"--skin__icon_1\":\"#FEB705\",\"--skin__icon_1__toRgbString\":\"254,183,5\",\"--skin__icon_tg_q\":\"#C7C7C7\",\"--skin__icon_tg_q__toRgbString\":\"199,199,199\",\"--skin__icon_tg_z\":\"#C7C7C7\",\"--skin__icon_tg_z__toRgbString\":\"199,199,199\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#303030\",\"--skin__kb_bg__toRgbString\":\"48,48,48\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#E3E3E3\",\"--skin__lead__toRgbString\":\"227,227,227\",\"--skin__leftnav_active\":\"#000000\",\"--skin__leftnav_active__toRgbString\":\"0,0,0\",\"--skin__leftnav_def\":\"#C7C7C7\",\"--skin__leftnav_def__toRgbString\":\"199,199,199\",\"--skin__neutral_1\":\"#C7C7C7\",\"--skin__neutral_1__toRgbString\":\"199,199,199\",\"--skin__neutral_2\":\"#777777\",\"--skin__neutral_2__toRgbString\":\"119,119,119\",\"--skin__neutral_3\":\"#777777\",\"--skin__neutral_3__toRgbString\":\"119,119,119\",\"--skin__primary\":\"#FEB705\",\"--skin__primary__toRgbString\":\"254,183,5\",\"--skin__profile_icon_1\":\"#FEB705\",\"--skin__profile_icon_1__toRgbString\":\"254,183,5\",\"--skin__profile_icon_2\":\"#FEB705\",\"--skin__profile_icon_2__toRgbString\":\"254,183,5\",\"--skin__profile_icon_3\":\"#FEB705\",\"--skin__profile_icon_3__toRgbString\":\"254,183,5\",\"--skin__profile_icon_4\":\"#FEB705\",\"--skin__profile_icon_4__toRgbString\":\"254,183,5\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#C7C7C7\",\"--skin__search_icon__toRgbString\":\"199,199,199\",\"--skin__table_bg\":\"#303030\",\"--skin__table_bg__toRgbString\":\"48,48,48\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#000000\",\"--skin__text_primary__toRgbString\":\"0,0,0\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#FDF0D1\",\"--skin__tg_primary__toRgbString\":\"253,240,209\",\"--skin__web_bs_yj_bg\":\"#1C1C1C\",\"--skin__web_bs_yj_bg__toRgbString\":\"28,28,28\",\"--skin__web_bs_zc_an2\":\"#3A3A3A\",\"--skin__web_bs_zc_an2__toRgbString\":\"58,58,58\",\"--skin__web_btmnav_db\":\"#242424\",\"--skin__web_btmnav_db__toRgbString\":\"36,36,36\",\"--skin__web_filter_gou\":\"#000000\",\"--skin__web_filter_gou__toRgbString\":\"0,0,0\",\"--skin__web_left_bg_q\":\"#363636\",\"--skin__web_left_bg_q__toRgbString\":\"54,54,54\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#1D1D1D\",\"--skin__web_left_bg_z__toRgbString\":\"29,29,29\",\"--skin__web_load_zz\":\"#50505066\",\"--skin__web_plat_line\":\"#303030\",\"--skin__web_plat_line__toRgbString\":\"48,48,48\",\"--skin__web_topbg_1\":\"#F6BE30\",\"--skin__web_topbg_1__toRgbString\":\"246,190,48\",\"--skin__web_topbg_3\":\"#EE9F03\"}', '../skin/lobby_asset/2-1-22/{6094781D-36E9-47EA-A615-0E84D2436E09}.png', 0, 'https://oteb8766.6678bet.win/siteadmin/skin/lobby_asset/2-1-3/common/'),
(42, 'BBZZVIP', '{\"--skin__ID\":\"2-2\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EF4C44\",\"--skin__accent_2__toRgbString\":\"239,76,68\",\"--skin__accent_3\":\"#FFC320\",\"--skin__accent_3__toRgbString\":\"255,195,32\",\"--skin__alt_border\":\"#A9D7DB\",\"--skin__alt_border__toRgbString\":\"169,215,219\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#A9D7DB\",\"--skin__alt_neutral_1__toRgbString\":\"169,215,219\",\"--skin__alt_neutral_2\":\"#5D989E\",\"--skin__alt_neutral_2__toRgbString\":\"93,152,158\",\"--skin__alt_primary\":\"#06D0DF\",\"--skin__alt_primary__toRgbString\":\"6,208,223\",\"--skin__alt_text_primary\":\"#054146\",\"--skin__alt_text_primary__toRgbString\":\"5,65,70\",\"--skin__bg_1\":\"#055F67\",\"--skin__bg_1__toRgbString\":\"5,95,103\",\"--skin__bg_2\":\"#054146\",\"--skin__bg_2__toRgbString\":\"5,65,70\",\"--skin__border\":\"#18747E\",\"--skin__border__toRgbString\":\"24,116,126\",\"--skin__bs_topnav_bg\":\"#054146\",\"--skin__bs_topnav_bg__toRgbString\":\"5,65,70\",\"--skin__bs_zc_an1\":\"#035C64\",\"--skin__bs_zc_an1__toRgbString\":\"3,92,100\",\"--skin__bs_zc_bg\":\"#024A50\",\"--skin__bs_zc_bg__toRgbString\":\"2,74,80\",\"--skin__btmnav_active\":\"#06D0DF\",\"--skin__btmnav_active__toRgbString\":\"6,208,223\",\"--skin__btmnav_def\":\"#5D989E\",\"--skin__btmnav_def__toRgbString\":\"93,152,158\",\"--skin__btn_color_1\":\"#06D0DF\",\"--skin__btn_color_1__toRgbString\":\"6,208,223\",\"--skin__btn_color_2\":\"#06D0DF\",\"--skin__btn_color_2__toRgbString\":\"6,208,223\",\"--skin__cards_text\":\"#A9D7DB\",\"--skin__cards_text__toRgbString\":\"169,215,219\",\"--skin__ddt_bg\":\"#065258\",\"--skin__ddt_bg__toRgbString\":\"6,82,88\",\"--skin__ddt_icon\":\"#18747E\",\"--skin__ddt_icon__toRgbString\":\"24,116,126\",\"--skin__filter_active\":\"#06D0DF\",\"--skin__filter_active__toRgbString\":\"6,208,223\",\"--skin__filter_bg\":\"#055F67\",\"--skin__filter_bg__toRgbString\":\"5,95,103\",\"--skin__home_bg\":\"#054146\",\"--skin__home_bg__toRgbString\":\"5,65,70\",\"--skin__icon_1\":\"#06D0DF\",\"--skin__icon_1__toRgbString\":\"6,208,223\",\"--skin__icon_tg_q\":\"#A9D7DB\",\"--skin__icon_tg_q__toRgbString\":\"169,215,219\",\"--skin__icon_tg_z\":\"#A9D7DB\",\"--skin__icon_tg_z__toRgbString\":\"169,215,219\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#06D0DF\",\"--skin__jdd_vip_bjc__toRgbString\":\"6,208,223\",\"--skin__kb_bg\":\"#055F67\",\"--skin__kb_bg__toRgbString\":\"5,95,103\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#054146\",\"--skin__leftnav_active__toRgbString\":\"5,65,70\",\"--skin__leftnav_def\":\"#A9D7DB\",\"--skin__leftnav_def__toRgbString\":\"169,215,219\",\"--skin__neutral_1\":\"#A9D7DB\",\"--skin__neutral_1__toRgbString\":\"169,215,219\",\"--skin__neutral_2\":\"#5D989E\",\"--skin__neutral_2__toRgbString\":\"93,152,158\",\"--skin__neutral_3\":\"#5D989E\",\"--skin__neutral_3__toRgbString\":\"93,152,158\",\"--skin__primary\":\"#06D0DF\",\"--skin__primary__toRgbString\":\"6,208,223\",\"--skin__profile_icon_1\":\"#06D0DF\",\"--skin__profile_icon_1__toRgbString\":\"6,208,223\",\"--skin__profile_icon_2\":\"#06D0DF\",\"--skin__profile_icon_2__toRgbString\":\"6,208,223\",\"--skin__profile_icon_3\":\"#06D0DF\",\"--skin__profile_icon_3__toRgbString\":\"6,208,223\",\"--skin__profile_icon_4\":\"#06D0DF\",\"--skin__profile_icon_4__toRgbString\":\"6,208,223\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#A9D7DB\",\"--skin__search_icon__toRgbString\":\"169,215,219\",\"--skin__table_bg\":\"#055F67\",\"--skin__table_bg__toRgbString\":\"5,95,103\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#054146\",\"--skin__text_primary__toRgbString\":\"5,65,70\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#C7E3F3\",\"--skin__tg_primary__toRgbString\":\"199,227,243\",\"--skin__web_bs_yj_bg\":\"#054146\",\"--skin__web_bs_yj_bg__toRgbString\":\"5,65,70\",\"--skin__web_bs_zc_an2\":\"#267982\",\"--skin__web_bs_zc_an2__toRgbString\":\"38,121,130\",\"--skin__web_btmnav_db\":\"#05484E\",\"--skin__web_btmnav_db__toRgbString\":\"5,72,78\",\"--skin__web_filter_gou\":\"#054146\",\"--skin__web_filter_gou__toRgbString\":\"5,65,70\",\"--skin__web_left_bg_q\":\"#07545B\",\"--skin__web_left_bg_q__toRgbString\":\"7,84,91\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#054146\",\"--skin__web_left_bg_z__toRgbString\":\"5,65,70\",\"--skin__web_load_zz\":\"#18747E66\",\"--skin__web_plat_line\":\"#1B5257\",\"--skin__web_plat_line__toRgbString\":\"27,82,87\",\"--skin__web_topbg_1\":\"#00C1D0\",\"--skin__web_topbg_1__toRgbString\":\"0,193,208\",\"--skin__web_topbg_3\":\"#00ADC0\"}', '../skin/lobby_asset/2-1-22/{8086A4F7-1273-44DA-B3A7-ECE0B5089BC6}.png', 0, 'https://opivzzbb.bbzzvip.com/siteadmin/skin/lobby_asset/2-1-2/common/'),
(43, 'AmaranthPG', '{\"--skin__ID\":\"2-17\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#FF4A4A\",\"--skin__accent_2__toRgbString\":\"255,74,74\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#A0C5FB\",\"--skin__alt_border__toRgbString\":\"160,197,251\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#A0C5FB\",\"--skin__alt_neutral_1__toRgbString\":\"160,197,251\",\"--skin__alt_neutral_2\":\"#6FA4EF\",\"--skin__alt_neutral_2__toRgbString\":\"111,164,239\",\"--skin__alt_primary\":\"#FFF0BB\",\"--skin__alt_primary__toRgbString\":\"255,240,187\",\"--skin__alt_text_primary\":\"#05309F\",\"--skin__alt_text_primary__toRgbString\":\"5,48,159\",\"--skin__bg_1\":\"#1A45B1\",\"--skin__bg_1__toRgbString\":\"26,69,177\",\"--skin__bg_2\":\"#05309F\",\"--skin__bg_2__toRgbString\":\"5,48,159\",\"--skin__border\":\"#3A61C2\",\"--skin__border__toRgbString\":\"58,97,194\",\"--skin__bs_topnav_bg\":\"#062064\",\"--skin__bs_topnav_bg__toRgbString\":\"6,32,100\",\"--skin__bs_zc_an1\":\"#3A61C2\",\"--skin__bs_zc_an1__toRgbString\":\"58,97,194\",\"--skin__bs_zc_bg\":\"#05309F\",\"--skin__bs_zc_bg__toRgbString\":\"5,48,159\",\"--skin__btmnav_active\":\"#FFF0BB\",\"--skin__btmnav_active__toRgbString\":\"255,240,187\",\"--skin__btmnav_def\":\"#6FA4EF\",\"--skin__btmnav_def__toRgbString\":\"111,164,239\",\"--skin__btn_color_1\":\"#FFF0BB\",\"--skin__btn_color_1__toRgbString\":\"255,240,187\",\"--skin__btn_color_2\":\"#FFF0BB\",\"--skin__btn_color_2__toRgbString\":\"255,240,187\",\"--skin__cards_text\":\"#A0C5FB\",\"--skin__cards_text__toRgbString\":\"160,197,251\",\"--skin__ddt_bg\":\"#123FB1\",\"--skin__ddt_bg__toRgbString\":\"18,63,177\",\"--skin__ddt_icon\":\"#1E4EC5\",\"--skin__ddt_icon__toRgbString\":\"30,78,197\",\"--skin__filter_active\":\"#FFF0BB\",\"--skin__filter_active__toRgbString\":\"255,240,187\",\"--skin__filter_bg\":\"#1A45B1\",\"--skin__filter_bg__toRgbString\":\"26,69,177\",\"--skin__home_bg\":\"#05309F\",\"--skin__home_bg__toRgbString\":\"5,48,159\",\"--skin__icon_1\":\"#FFF0BB\",\"--skin__icon_1__toRgbString\":\"255,240,187\",\"--skin__icon_tg_q\":\"#A0C5FB\",\"--skin__icon_tg_q__toRgbString\":\"160,197,251\",\"--skin__icon_tg_z\":\"#A0C5FB\",\"--skin__icon_tg_z__toRgbString\":\"160,197,251\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#3A61C2\",\"--skin__kb_bg__toRgbString\":\"58,97,194\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#05309F\",\"--skin__leftnav_active__toRgbString\":\"5,48,159\",\"--skin__leftnav_def\":\"#A0C5FB\",\"--skin__leftnav_def__toRgbString\":\"160,197,251\",\"--skin__neutral_1\":\"#A0C5FB\",\"--skin__neutral_1__toRgbString\":\"160,197,251\",\"--skin__neutral_2\":\"#6FA4EF\",\"--skin__neutral_2__toRgbString\":\"111,164,239\",\"--skin__neutral_3\":\"#6FA4EF\",\"--skin__neutral_3__toRgbString\":\"111,164,239\",\"--skin__primary\":\"#FFF0BB\",\"--skin__primary__toRgbString\":\"255,240,187\",\"--skin__profile_icon_1\":\"#FFF0BB\",\"--skin__profile_icon_1__toRgbString\":\"255,240,187\",\"--skin__profile_icon_2\":\"#FFF0BB\",\"--skin__profile_icon_2__toRgbString\":\"255,240,187\",\"--skin__profile_icon_3\":\"#FFF0BB\",\"--skin__profile_icon_3__toRgbString\":\"255,240,187\",\"--skin__profile_icon_4\":\"#FFF0BB\",\"--skin__profile_icon_4__toRgbString\":\"255,240,187\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#A0C5FB\",\"--skin__search_icon__toRgbString\":\"160,197,251\",\"--skin__table_bg\":\"#05309F\",\"--skin__table_bg__toRgbString\":\"5,48,159\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#05309F\",\"--skin__text_primary__toRgbString\":\"5,48,159\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#EDEDEB\",\"--skin__tg_primary__toRgbString\":\"237,237,235\",\"--skin__web_bs_yj_bg\":\"#062064\",\"--skin__web_bs_yj_bg__toRgbString\":\"6,32,100\",\"--skin__web_bs_zc_an2\":\"#1B4DC8\",\"--skin__web_bs_zc_an2__toRgbString\":\"27,77,200\",\"--skin__web_btmnav_db\":\"#032B92\",\"--skin__web_btmnav_db__toRgbString\":\"3,43,146\",\"--skin__web_filter_gou\":\"#05309F\",\"--skin__web_filter_gou__toRgbString\":\"5,48,159\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#3A61C266\",\"--skin__web_plat_line\":\"#3A61C2\",\"--skin__web_plat_line__toRgbString\":\"58,97,194\",\"--skin__web_topbg_1\":\"#0635B2\",\"--skin__web_topbg_1__toRgbString\":\"6,53,178\",\"--skin__web_topbg_3\":\"#032B92\"}', '../skin/lobby_asset/2-1-22/{010FDB8B-5CE5-4E2B-BE26-749F2B5AFE23}.png', 0, 'https://gdsg.amaranthpgpay.com/siteadmin/skin/lobby_asset/2-1-17/common/'),
(44, 'FeijaoPG', '{\"--skin__ID\":\"2-14\",\"--skin__accent_1\":\"#34D713\",\"--skin__accent_1__toRgbString\":\"52,215,19\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#E8C182\",\"--skin__alt_border__toRgbString\":\"232,193,130\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#E8C182\",\"--skin__alt_neutral_1__toRgbString\":\"232,193,130\",\"--skin__alt_neutral_2\":\"#C19D63\",\"--skin__alt_neutral_2__toRgbString\":\"193,157,99\",\"--skin__alt_primary\":\"#FFE4B4\",\"--skin__alt_primary__toRgbString\":\"255,228,180\",\"--skin__alt_text_primary\":\"#63482C\",\"--skin__alt_text_primary__toRgbString\":\"99,72,44\",\"--skin__bg_1\":\"#8A6843\",\"--skin__bg_1__toRgbString\":\"138,104,67\",\"--skin__bg_2\":\"#63482C\",\"--skin__bg_2__toRgbString\":\"99,72,44\",\"--skin__border\":\"#A47E57\",\"--skin__border__toRgbString\":\"164,126,87\",\"--skin__bs_topnav_bg\":\"#63482C\",\"--skin__bs_topnav_bg__toRgbString\":\"99,72,44\",\"--skin__bs_zc_an1\":\"#876541\",\"--skin__bs_zc_an1__toRgbString\":\"135,101,65\",\"--skin__bs_zc_bg\":\"#755634\",\"--skin__bs_zc_bg__toRgbString\":\"117,86,52\",\"--skin__btmnav_active\":\"#FFE4B4\",\"--skin__btmnav_active__toRgbString\":\"255,228,180\",\"--skin__btmnav_def\":\"#C19D63\",\"--skin__btmnav_def__toRgbString\":\"193,157,99\",\"--skin__btn_color_1\":\"#FFE4B4\",\"--skin__btn_color_1__toRgbString\":\"255,228,180\",\"--skin__btn_color_2\":\"#FFE4B4\",\"--skin__btn_color_2__toRgbString\":\"255,228,180\",\"--skin__cards_text\":\"#E8C182\",\"--skin__cards_text__toRgbString\":\"232,193,130\",\"--skin__ddt_bg\":\"#6D4F30\",\"--skin__ddt_bg__toRgbString\":\"109,79,48\",\"--skin__ddt_icon\":\"#805D38\",\"--skin__ddt_icon__toRgbString\":\"128,93,56\",\"--skin__filter_active\":\"#FFE4B4\",\"--skin__filter_active__toRgbString\":\"255,228,180\",\"--skin__filter_bg\":\"#8A6843\",\"--skin__filter_bg__toRgbString\":\"138,104,67\",\"--skin__home_bg\":\"#63482C\",\"--skin__home_bg__toRgbString\":\"99,72,44\",\"--skin__icon_1\":\"#FFE4B4\",\"--skin__icon_1__toRgbString\":\"255,228,180\",\"--skin__icon_tg_q\":\"#E8C182\",\"--skin__icon_tg_q__toRgbString\":\"232,193,130\",\"--skin__icon_tg_z\":\"#E8C182\",\"--skin__icon_tg_z__toRgbString\":\"232,193,130\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#A47E57\",\"--skin__kb_bg__toRgbString\":\"164,126,87\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#63482C\",\"--skin__leftnav_active__toRgbString\":\"99,72,44\",\"--skin__leftnav_def\":\"#E8C182\",\"--skin__leftnav_def__toRgbString\":\"232,193,130\",\"--skin__neutral_1\":\"#E8C182\",\"--skin__neutral_1__toRgbString\":\"232,193,130\",\"--skin__neutral_2\":\"#C19D63\",\"--skin__neutral_2__toRgbString\":\"193,157,99\",\"--skin__neutral_3\":\"#C19D63\",\"--skin__neutral_3__toRgbString\":\"193,157,99\",\"--skin__primary\":\"#FFE4B4\",\"--skin__primary__toRgbString\":\"255,228,180\",\"--skin__profile_icon_1\":\"#FFE4B4\",\"--skin__profile_icon_1__toRgbString\":\"255,228,180\",\"--skin__profile_icon_2\":\"#FFE4B4\",\"--skin__profile_icon_2__toRgbString\":\"255,228,180\",\"--skin__profile_icon_3\":\"#FFE4B4\",\"--skin__profile_icon_3__toRgbString\":\"255,228,180\",\"--skin__profile_icon_4\":\"#FFE4B4\",\"--skin__profile_icon_4__toRgbString\":\"255,228,180\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#E8C182\",\"--skin__search_icon__toRgbString\":\"232,193,130\",\"--skin__table_bg\":\"#63482C\",\"--skin__table_bg__toRgbString\":\"99,72,44\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#63482C\",\"--skin__text_primary__toRgbString\":\"99,72,44\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#F5EEE1\",\"--skin__tg_primary__toRgbString\":\"245,238,225\",\"--skin__web_bs_yj_bg\":\"#63482C\",\"--skin__web_bs_yj_bg__toRgbString\":\"99,72,44\",\"--skin__web_bs_zc_an2\":\"#947049\",\"--skin__web_bs_zc_an2__toRgbString\":\"148,112,73\",\"--skin__web_btmnav_db\":\"#705233\",\"--skin__web_btmnav_db__toRgbString\":\"112,82,51\",\"--skin__web_filter_gou\":\"#63482C\",\"--skin__web_filter_gou__toRgbString\":\"99,72,44\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#A47E5766\",\"--skin__web_plat_line\":\"#A47E57\",\"--skin__web_plat_line__toRgbString\":\"164,126,87\",\"--skin__web_topbg_1\":\"#EECB8E\",\"--skin__web_topbg_1__toRgbString\":\"238,203,142\",\"--skin__web_topbg_3\":\"#CEA661\"}', '../skin/lobby_asset/2-1-22/{6E7A03B4-42DC-441B-A74D-6E1F34385060}.png', 0, 'https://agsa.feijoapgpay1.com/siteadmin/skin/lobby_asset/2-1-14/common/'),
(45, 'w1-ackeepg.com', '{\"--skin__ID\":\"2-20\",\"--skin__accent_1\":\"#20F511\",\"--skin__accent_1__toRgbString\":\"32,245,17\",\"--skin__accent_2\":\"#AF1301\",\"--skin__accent_2__toRgbString\":\"175,19,1\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#D9FFE7\",\"--skin__alt_border__toRgbString\":\"217,255,231\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#D9FFE7\",\"--skin__alt_neutral_1__toRgbString\":\"217,255,231\",\"--skin__alt_neutral_2\":\"#A0D3B2\",\"--skin__alt_neutral_2__toRgbString\":\"160,211,178\",\"--skin__alt_primary\":\"#F4E7CB\",\"--skin__alt_primary__toRgbString\":\"244,231,203\",\"--skin__alt_text_primary\":\"#52775F\",\"--skin__alt_text_primary__toRgbString\":\"82,119,95\",\"--skin__bg_1\":\"#769882\",\"--skin__bg_1__toRgbString\":\"118,152,130\",\"--skin__bg_2\":\"#5B7E68\",\"--skin__bg_2__toRgbString\":\"91,126,104\",\"--skin__border\":\"#8FAE99\",\"--skin__border__toRgbString\":\"143,174,153\",\"--skin__bs_topnav_bg\":\"#52775F\",\"--skin__bs_topnav_bg__toRgbString\":\"82,119,95\",\"--skin__bs_zc_an1\":\"#709A80\",\"--skin__bs_zc_an1__toRgbString\":\"112,154,128\",\"--skin__bs_zc_bg\":\"#648872\",\"--skin__bs_zc_bg__toRgbString\":\"100,136,114\",\"--skin__btmnav_active\":\"#F4E7CB\",\"--skin__btmnav_active__toRgbString\":\"244,231,203\",\"--skin__btmnav_def\":\"#A0D3B2\",\"--skin__btmnav_def__toRgbString\":\"160,211,178\",\"--skin__btn_color_1\":\"#F4E7CB\",\"--skin__btn_color_1__toRgbString\":\"244,231,203\",\"--skin__btn_color_2\":\"#F4E7CB\",\"--skin__btn_color_2__toRgbString\":\"244,231,203\",\"--skin__cards_text\":\"#D9FFE7\",\"--skin__cards_text__toRgbString\":\"217,255,231\",\"--skin__ddt_bg\":\"#668D75\",\"--skin__ddt_bg__toRgbString\":\"102,141,117\",\"--skin__ddt_icon\":\"#739B83\",\"--skin__ddt_icon__toRgbString\":\"115,155,131\",\"--skin__filter_active\":\"#F4E7CB\",\"--skin__filter_active__toRgbString\":\"244,231,203\",\"--skin__filter_bg\":\"#769882\",\"--skin__filter_bg__toRgbString\":\"118,152,130\",\"--skin__home_bg\":\"#5B7E68\",\"--skin__home_bg__toRgbString\":\"91,126,104\",\"--skin__icon_1\":\"#F4E7CB\",\"--skin__icon_1__toRgbString\":\"244,231,203\",\"--skin__icon_tg_q\":\"#D9FFE7\",\"--skin__icon_tg_q__toRgbString\":\"217,255,231\",\"--skin__icon_tg_z\":\"#D9FFE7\",\"--skin__icon_tg_z__toRgbString\":\"217,255,231\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#8FAE99\",\"--skin__kb_bg__toRgbString\":\"143,174,153\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#52775F\",\"--skin__leftnav_active__toRgbString\":\"82,119,95\",\"--skin__leftnav_def\":\"#D9FFE7\",\"--skin__leftnav_def__toRgbString\":\"217,255,231\",\"--skin__neutral_1\":\"#D9FFE7\",\"--skin__neutral_1__toRgbString\":\"217,255,231\",\"--skin__neutral_2\":\"#A0D3B2\",\"--skin__neutral_2__toRgbString\":\"160,211,178\",\"--skin__neutral_3\":\"#A0D3B2\",\"--skin__neutral_3__toRgbString\":\"160,211,178\",\"--skin__primary\":\"#F4E7CB\",\"--skin__primary__toRgbString\":\"244,231,203\",\"--skin__profile_icon_1\":\"#F4E7CB\",\"--skin__profile_icon_1__toRgbString\":\"244,231,203\",\"--skin__profile_icon_2\":\"#F4E7CB\",\"--skin__profile_icon_2__toRgbString\":\"244,231,203\",\"--skin__profile_icon_3\":\"#F4E7CB\",\"--skin__profile_icon_3__toRgbString\":\"244,231,203\",\"--skin__profile_icon_4\":\"#F4E7CB\",\"--skin__profile_icon_4__toRgbString\":\"244,231,203\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#D9FFE7\",\"--skin__search_icon__toRgbString\":\"217,255,231\",\"--skin__table_bg\":\"#5B7E68\",\"--skin__table_bg__toRgbString\":\"91,126,104\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#52775F\",\"--skin__text_primary__toRgbString\":\"82,119,95\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#F2F1EB\",\"--skin__tg_primary__toRgbString\":\"242,241,235\",\"--skin__web_bs_yj_bg\":\"#52775F\",\"--skin__web_bs_yj_bg__toRgbString\":\"82,119,95\",\"--skin__web_bs_zc_an2\":\"#82AD92\",\"--skin__web_bs_zc_an2__toRgbString\":\"130,173,146\",\"--skin__web_btmnav_db\":\"#60826D\",\"--skin__web_btmnav_db__toRgbString\":\"96,130,109\",\"--skin__web_filter_gou\":\"#52775F\",\"--skin__web_filter_gou__toRgbString\":\"82,119,95\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#8FAE9966\",\"--skin__web_plat_line\":\"#8FAE99\",\"--skin__web_plat_line__toRgbString\":\"143,174,153\",\"--skin__web_topbg_1\":\"#6A8E74\",\"--skin__web_topbg_1__toRgbString\":\"106,142,116\",\"--skin__web_topbg_3\":\"#537A61\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-01 104857.png', 0, 'https://dsg.w1-ackeepg.com/siteadmin/skin/lobby_asset');
INSERT INTO `templates_cores` (`id`, `nome_template`, `temas`, `imagem`, `ativo`, `url_site_images`) VALUES
(46, 'makeuppg.com', '{\"--skin__ID\":\"2-8\",\"--skin__accent_1\":\"#13C911\",\"--skin__accent_1__toRgbString\":\"19,201,17\",\"--skin__accent_2\":\"#FF4A4A\",\"--skin__accent_2__toRgbString\":\"255,74,74\",\"--skin__accent_3\":\"#FFB509\",\"--skin__accent_3__toRgbString\":\"255,181,9\",\"--skin__alt_border\":\"#C5E2D2\",\"--skin__alt_border__toRgbString\":\"197,226,210\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#C5E2D2\",\"--skin__alt_neutral_1__toRgbString\":\"197,226,210\",\"--skin__alt_neutral_2\":\"#7DB39E\",\"--skin__alt_neutral_2__toRgbString\":\"125,179,158\",\"--skin__alt_primary\":\"#F9FD4E\",\"--skin__alt_primary__toRgbString\":\"249,253,78\",\"--skin__alt_text_primary\":\"#22674B\",\"--skin__alt_text_primary__toRgbString\":\"34,103,75\",\"--skin__bg_1\":\"#22674B\",\"--skin__bg_1__toRgbString\":\"34,103,75\",\"--skin__bg_2\":\"#194C38\",\"--skin__bg_2__toRgbString\":\"25,76,56\",\"--skin__border\":\"#2A815F\",\"--skin__border__toRgbString\":\"42,129,95\",\"--skin__bs_topnav_bg\":\"#164633\",\"--skin__bs_topnav_bg__toRgbString\":\"22,70,51\",\"--skin__bs_zc_an1\":\"#26634A\",\"--skin__bs_zc_an1__toRgbString\":\"38,99,74\",\"--skin__bs_zc_bg\":\"#1D533E\",\"--skin__bs_zc_bg__toRgbString\":\"29,83,62\",\"--skin__btmnav_active\":\"#F9FD4E\",\"--skin__btmnav_active__toRgbString\":\"249,253,78\",\"--skin__btmnav_def\":\"#7DB39E\",\"--skin__btmnav_def__toRgbString\":\"125,179,158\",\"--skin__btn_color_1\":\"#F9FD4E\",\"--skin__btn_color_1__toRgbString\":\"249,253,78\",\"--skin__btn_color_2\":\"#F9FD4E\",\"--skin__btn_color_2__toRgbString\":\"249,253,78\",\"--skin__cards_text\":\"#C5E2D2\",\"--skin__cards_text__toRgbString\":\"197,226,210\",\"--skin__ddt_bg\":\"#1C5B42\",\"--skin__ddt_bg__toRgbString\":\"28,91,66\",\"--skin__ddt_icon\":\"#237051\",\"--skin__ddt_icon__toRgbString\":\"35,112,81\",\"--skin__filter_active\":\"#F9FD4E\",\"--skin__filter_active__toRgbString\":\"249,253,78\",\"--skin__filter_bg\":\"#22674B\",\"--skin__filter_bg__toRgbString\":\"34,103,75\",\"--skin__home_bg\":\"#194C38\",\"--skin__home_bg__toRgbString\":\"25,76,56\",\"--skin__icon_1\":\"#F9FD4E\",\"--skin__icon_1__toRgbString\":\"249,253,78\",\"--skin__icon_tg_q\":\"#C5E2D2\",\"--skin__icon_tg_q__toRgbString\":\"197,226,210\",\"--skin__icon_tg_z\":\"#C5E2D2\",\"--skin__icon_tg_z__toRgbString\":\"197,226,210\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFB509\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,181,9\",\"--skin__kb_bg\":\"#2A815F\",\"--skin__kb_bg__toRgbString\":\"42,129,95\",\"--skin__label_accent3\":\"#FFB509\",\"--skin__label_accent3__toRgbString\":\"255,181,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#22674B\",\"--skin__leftnav_active__toRgbString\":\"34,103,75\",\"--skin__leftnav_def\":\"#C5E2D2\",\"--skin__leftnav_def__toRgbString\":\"197,226,210\",\"--skin__neutral_1\":\"#C5E2D2\",\"--skin__neutral_1__toRgbString\":\"197,226,210\",\"--skin__neutral_2\":\"#7DB39E\",\"--skin__neutral_2__toRgbString\":\"125,179,158\",\"--skin__neutral_3\":\"#7DB39E\",\"--skin__neutral_3__toRgbString\":\"125,179,158\",\"--skin__primary\":\"#F9FD4E\",\"--skin__primary__toRgbString\":\"249,253,78\",\"--skin__profile_icon_1\":\"#F9FD4E\",\"--skin__profile_icon_1__toRgbString\":\"249,253,78\",\"--skin__profile_icon_2\":\"#F9FD4E\",\"--skin__profile_icon_2__toRgbString\":\"249,253,78\",\"--skin__profile_icon_3\":\"#F9FD4E\",\"--skin__profile_icon_3__toRgbString\":\"249,253,78\",\"--skin__profile_icon_4\":\"#F9FD4E\",\"--skin__profile_icon_4__toRgbString\":\"249,253,78\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#C5E2D2\",\"--skin__search_icon__toRgbString\":\"197,226,210\",\"--skin__table_bg\":\"#194C38\",\"--skin__table_bg__toRgbString\":\"25,76,56\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#22674B\",\"--skin__text_primary__toRgbString\":\"34,103,75\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#ECF3CE\",\"--skin__tg_primary__toRgbString\":\"236,243,206\",\"--skin__web_bs_yj_bg\":\"#164633\",\"--skin__web_bs_yj_bg__toRgbString\":\"22,70,51\",\"--skin__web_bs_zc_an2\":\"#2F7257\",\"--skin__web_bs_zc_an2__toRgbString\":\"47,114,87\",\"--skin__web_btmnav_db\":\"#1D533E\",\"--skin__web_btmnav_db__toRgbString\":\"29,83,62\",\"--skin__web_filter_gou\":\"#22674B\",\"--skin__web_filter_gou__toRgbString\":\"34,103,75\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#2A815F66\",\"--skin__web_plat_line\":\"#2A815F\",\"--skin__web_plat_line__toRgbString\":\"42,129,95\",\"--skin__web_topbg_1\":\"#1D5B43\",\"--skin__web_topbg_1__toRgbString\":\"29,91,67\",\"--skin__web_topbg_3\":\"#194C38\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-03 110721.png', 0, 'https://d.91-makeuppg.com/siteadmin/skin/lobby_asset/2-1-8/common/'),
(47, 'w1-bmxpg.com', '{\"--skin__ID\":\"2-10\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#A7B6FF\",\"--skin__alt_border__toRgbString\":\"167,182,255\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#A7B6FF\",\"--skin__alt_neutral_1__toRgbString\":\"167,182,255\",\"--skin__alt_neutral_2\":\"#6873BF\",\"--skin__alt_neutral_2__toRgbString\":\"104,115,191\",\"--skin__alt_primary\":\"#F3D99E\",\"--skin__alt_primary__toRgbString\":\"243,217,158\",\"--skin__alt_text_primary\":\"#0A0C39\",\"--skin__alt_text_primary__toRgbString\":\"10,12,57\",\"--skin__bg_1\":\"#121A4D\",\"--skin__bg_1__toRgbString\":\"18,26,77\",\"--skin__bg_2\":\"#0A0C39\",\"--skin__bg_2__toRgbString\":\"10,12,57\",\"--skin__border\":\"#1D286A\",\"--skin__border__toRgbString\":\"29,40,106\",\"--skin__bs_topnav_bg\":\"#060730\",\"--skin__bs_topnav_bg__toRgbString\":\"6,7,48\",\"--skin__bs_zc_an1\":\"#121B53\",\"--skin__bs_zc_an1__toRgbString\":\"18,27,83\",\"--skin__bs_zc_bg\":\"#0A0C39\",\"--skin__bs_zc_bg__toRgbString\":\"10,12,57\",\"--skin__btmnav_active\":\"#F3D99E\",\"--skin__btmnav_active__toRgbString\":\"243,217,158\",\"--skin__btmnav_def\":\"#6873BF\",\"--skin__btmnav_def__toRgbString\":\"104,115,191\",\"--skin__btn_color_1\":\"#F3D99E\",\"--skin__btn_color_1__toRgbString\":\"243,217,158\",\"--skin__btn_color_2\":\"#F3D99E\",\"--skin__btn_color_2__toRgbString\":\"243,217,158\",\"--skin__cards_text\":\"#A7B6FF\",\"--skin__cards_text__toRgbString\":\"167,182,255\",\"--skin__ddt_bg\":\"#1B235A\",\"--skin__ddt_bg__toRgbString\":\"27,35,90\",\"--skin__ddt_icon\":\"#2A336E\",\"--skin__ddt_icon__toRgbString\":\"42,51,110\",\"--skin__filter_active\":\"#F3D99E\",\"--skin__filter_active__toRgbString\":\"243,217,158\",\"--skin__filter_bg\":\"#121A4D\",\"--skin__filter_bg__toRgbString\":\"18,26,77\",\"--skin__home_bg\":\"#0A0C39\",\"--skin__home_bg__toRgbString\":\"10,12,57\",\"--skin__icon_1\":\"#F3D99E\",\"--skin__icon_1__toRgbString\":\"243,217,158\",\"--skin__icon_tg_q\":\"#A7B6FF\",\"--skin__icon_tg_q__toRgbString\":\"167,182,255\",\"--skin__icon_tg_z\":\"#A7B6FF\",\"--skin__icon_tg_z__toRgbString\":\"167,182,255\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#1D286A\",\"--skin__kb_bg__toRgbString\":\"29,40,106\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#0A0C39\",\"--skin__leftnav_active__toRgbString\":\"10,12,57\",\"--skin__leftnav_def\":\"#A7B6FF\",\"--skin__leftnav_def__toRgbString\":\"167,182,255\",\"--skin__neutral_1\":\"#A7B6FF\",\"--skin__neutral_1__toRgbString\":\"167,182,255\",\"--skin__neutral_2\":\"#6873BF\",\"--skin__neutral_2__toRgbString\":\"104,115,191\",\"--skin__neutral_3\":\"#6873BF\",\"--skin__neutral_3__toRgbString\":\"104,115,191\",\"--skin__primary\":\"#F3D99E\",\"--skin__primary__toRgbString\":\"243,217,158\",\"--skin__profile_icon_1\":\"#F3D99E\",\"--skin__profile_icon_1__toRgbString\":\"243,217,158\",\"--skin__profile_icon_2\":\"#F3D99E\",\"--skin__profile_icon_2__toRgbString\":\"243,217,158\",\"--skin__profile_icon_3\":\"#F3D99E\",\"--skin__profile_icon_3__toRgbString\":\"243,217,158\",\"--skin__profile_icon_4\":\"#F3D99E\",\"--skin__profile_icon_4__toRgbString\":\"243,217,158\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#A7B6FF\",\"--skin__search_icon__toRgbString\":\"167,182,255\",\"--skin__table_bg\":\"#0A0C39\",\"--skin__table_bg__toRgbString\":\"10,12,57\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#0A0C39\",\"--skin__text_primary__toRgbString\":\"10,12,57\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#ECC6CF\",\"--skin__tg_primary__toRgbString\":\"236,198,207\",\"--skin__web_bs_yj_bg\":\"#060730\",\"--skin__web_bs_yj_bg__toRgbString\":\"6,7,48\",\"--skin__web_bs_zc_an2\":\"#162064\",\"--skin__web_bs_zc_an2__toRgbString\":\"22,32,100\",\"--skin__web_btmnav_db\":\"#0B0E3E\",\"--skin__web_btmnav_db__toRgbString\":\"11,14,62\",\"--skin__web_filter_gou\":\"#0A0C39\",\"--skin__web_filter_gou__toRgbString\":\"10,12,57\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#1D286A66\",\"--skin__web_plat_line\":\"#1D286A\",\"--skin__web_plat_line__toRgbString\":\"29,40,106\",\"--skin__web_topbg_1\":\"#242F74\",\"--skin__web_topbg_1__toRgbString\":\"36,47,116\",\"--skin__web_topbg_3\":\"#19225D\"}', '../skin/lobby_asset/2-1-22/Capturadetela2025-12-07114802.png', 0, 'https://agsdg.bmxpgpay.com/siteadmin/skin/lobby_asset/2-1-10'),
(48, 'w1-yampg.com', '{\"--skin__ID\":\"2-6\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#D3ACFF\",\"--skin__alt_border__toRgbString\":\"211,172,255\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#D3ACFF\",\"--skin__alt_neutral_1__toRgbString\":\"211,172,255\",\"--skin__alt_neutral_2\":\"#9069E6\",\"--skin__alt_neutral_2__toRgbString\":\"144,105,230\",\"--skin__alt_primary\":\"#D560FF\",\"--skin__alt_primary__toRgbString\":\"213,96,255\",\"--skin__alt_text_primary\":\"#FFFFFF\",\"--skin__alt_text_primary__toRgbString\":\"255,255,255\",\"--skin__bg_1\":\"#441F94\",\"--skin__bg_1__toRgbString\":\"68,31,148\",\"--skin__bg_2\":\"#2B0977\",\"--skin__bg_2__toRgbString\":\"43,9,119\",\"--skin__border\":\"#6E3ED6\",\"--skin__border__toRgbString\":\"110,62,214\",\"--skin__bs_topnav_bg\":\"#2B0977\",\"--skin__bs_topnav_bg__toRgbString\":\"43,9,119\",\"--skin__bs_zc_an1\":\"#431E98\",\"--skin__bs_zc_an1__toRgbString\":\"67,30,152\",\"--skin__bs_zc_bg\":\"#3D0E8F\",\"--skin__bs_zc_bg__toRgbString\":\"61,14,143\",\"--skin__btmnav_active\":\"#D560FF\",\"--skin__btmnav_active__toRgbString\":\"213,96,255\",\"--skin__btmnav_def\":\"#9069E6\",\"--skin__btmnav_def__toRgbString\":\"144,105,230\",\"--skin__btn_color_1\":\"#D560FF\",\"--skin__btn_color_1__toRgbString\":\"213,96,255\",\"--skin__btn_color_2\":\"#D560FF\",\"--skin__btn_color_2__toRgbString\":\"213,96,255\",\"--skin__cards_text\":\"#D3ACFF\",\"--skin__cards_text__toRgbString\":\"211,172,255\",\"--skin__ddt_bg\":\"#371584\",\"--skin__ddt_bg__toRgbString\":\"55,21,132\",\"--skin__ddt_icon\":\"#4D279E\",\"--skin__ddt_icon__toRgbString\":\"77,39,158\",\"--skin__filter_active\":\"#D560FF\",\"--skin__filter_active__toRgbString\":\"213,96,255\",\"--skin__filter_bg\":\"#441F94\",\"--skin__filter_bg__toRgbString\":\"68,31,148\",\"--skin__home_bg\":\"#2B0977\",\"--skin__home_bg__toRgbString\":\"43,9,119\",\"--skin__icon_1\":\"#D560FF\",\"--skin__icon_1__toRgbString\":\"213,96,255\",\"--skin__icon_tg_q\":\"#D3ACFF\",\"--skin__icon_tg_q__toRgbString\":\"211,172,255\",\"--skin__icon_tg_z\":\"#D3ACFF\",\"--skin__icon_tg_z__toRgbString\":\"211,172,255\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#D560FF\",\"--skin__jdd_vip_bjc__toRgbString\":\"213,96,255\",\"--skin__kb_bg\":\"#441F94\",\"--skin__kb_bg__toRgbString\":\"68,31,148\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#D3ACFF\",\"--skin__leftnav_def__toRgbString\":\"211,172,255\",\"--skin__neutral_1\":\"#D3ACFF\",\"--skin__neutral_1__toRgbString\":\"211,172,255\",\"--skin__neutral_2\":\"#9069E6\",\"--skin__neutral_2__toRgbString\":\"144,105,230\",\"--skin__neutral_3\":\"#9069E6\",\"--skin__neutral_3__toRgbString\":\"144,105,230\",\"--skin__primary\":\"#D560FF\",\"--skin__primary__toRgbString\":\"213,96,255\",\"--skin__profile_icon_1\":\"#D560FF\",\"--skin__profile_icon_1__toRgbString\":\"213,96,255\",\"--skin__profile_icon_2\":\"#D560FF\",\"--skin__profile_icon_2__toRgbString\":\"213,96,255\",\"--skin__profile_icon_3\":\"#D560FF\",\"--skin__profile_icon_3__toRgbString\":\"213,96,255\",\"--skin__profile_icon_4\":\"#D560FF\",\"--skin__profile_icon_4__toRgbString\":\"213,96,255\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#D3ACFF\",\"--skin__search_icon__toRgbString\":\"211,172,255\",\"--skin__table_bg\":\"#441F94\",\"--skin__table_bg__toRgbString\":\"68,31,148\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#E8CDF6\",\"--skin__tg_primary__toRgbString\":\"232,205,246\",\"--skin__web_bs_yj_bg\":\"#2B0977\",\"--skin__web_bs_yj_bg__toRgbString\":\"43,9,119\",\"--skin__web_bs_zc_an2\":\"#4B25A2\",\"--skin__web_bs_zc_an2__toRgbString\":\"75,37,162\",\"--skin__web_btmnav_db\":\"#371584\",\"--skin__web_btmnav_db__toRgbString\":\"55,21,132\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#371485\",\"--skin__web_left_bg_q__toRgbString\":\"55,20,133\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#301175\",\"--skin__web_left_bg_z__toRgbString\":\"48,17,117\",\"--skin__web_load_zz\":\"#6E3ED666\",\"--skin__web_plat_line\":\"#422486\",\"--skin__web_plat_line__toRgbString\":\"66,36,134\",\"--skin__web_topbg_1\":\"#CB3AFF\",\"--skin__web_topbg_1__toRgbString\":\"203,58,255\",\"--skin__web_topbg_3\":\"#8D13DE\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-07 115214.png', 0, 'https://gsd.yampgpay.com/siteadmin/skin/lobby_asset/2-1-6'),
(49, 'Azul Claro', '{\"--skin__ID\":\"2-5\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#9DC5E6\",\"--skin__alt_border__toRgbString\":\"157,197,230\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#9DC5E6\",\"--skin__alt_neutral_1__toRgbString\":\"157,197,230\",\"--skin__alt_neutral_2\":\"#2F8DDC\",\"--skin__alt_neutral_2__toRgbString\":\"47,141,220\",\"--skin__alt_primary\":\"#3CCFFF\",\"--skin__alt_primary__toRgbString\":\"60,207,255\",\"--skin__alt_text_primary\":\"#FFFFFF\",\"--skin__alt_text_primary__toRgbString\":\"255,255,255\",\"--skin__bg_1\":\"#024C8C\",\"--skin__bg_1__toRgbString\":\"2,76,140\",\"--skin__bg_2\":\"#013D74\",\"--skin__bg_2__toRgbString\":\"1,61,116\",\"--skin__border\":\"#1466AB\",\"--skin__border__toRgbString\":\"20,102,171\",\"--skin__bs_topnav_bg\":\"#013D74\",\"--skin__bs_topnav_bg__toRgbString\":\"1,61,116\",\"--skin__bs_zc_an1\":\"#00509A\",\"--skin__bs_zc_an1__toRgbString\":\"0,80,154\",\"--skin__bs_zc_bg\":\"#004584\",\"--skin__bs_zc_bg__toRgbString\":\"0,69,132\",\"--skin__btmnav_active\":\"#3CCFFF\",\"--skin__btmnav_active__toRgbString\":\"60,207,255\",\"--skin__btmnav_def\":\"#2F8DDC\",\"--skin__btmnav_def__toRgbString\":\"47,141,220\",\"--skin__btn_color_1\":\"#3CCFFF\",\"--skin__btn_color_1__toRgbString\":\"60,207,255\",\"--skin__btn_color_2\":\"#3CCFFF\",\"--skin__btn_color_2__toRgbString\":\"60,207,255\",\"--skin__cards_text\":\"#9DC5E6\",\"--skin__cards_text__toRgbString\":\"157,197,230\",\"--skin__ddt_bg\":\"#003361\",\"--skin__ddt_bg__toRgbString\":\"0,51,97\",\"--skin__ddt_icon\":\"#03427d\",\"--skin__ddt_icon__toRgbString\":\"3,66,125\",\"--skin__filter_active\":\"#3CCFFF\",\"--skin__filter_active__toRgbString\":\"60,207,255\",\"--skin__filter_bg\":\"#024C8C\",\"--skin__filter_bg__toRgbString\":\"2,76,140\",\"--skin__home_bg\":\"#013D74\",\"--skin__home_bg__toRgbString\":\"1,61,116\",\"--skin__icon_1\":\"#3CCFFF\",\"--skin__icon_1__toRgbString\":\"60,207,255\",\"--skin__icon_tg_q\":\"#9DC5E6\",\"--skin__icon_tg_q__toRgbString\":\"157,197,230\",\"--skin__icon_tg_z\":\"#9DC5E6\",\"--skin__icon_tg_z__toRgbString\":\"157,197,230\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#3CCFFF\",\"--skin__jdd_vip_bjc__toRgbString\":\"60,207,255\",\"--skin__kb_bg\":\"#024C8C\",\"--skin__kb_bg__toRgbString\":\"2,76,140\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#9DC5E6\",\"--skin__leftnav_def__toRgbString\":\"157,197,230\",\"--skin__neutral_1\":\"#9DC5E6\",\"--skin__neutral_1__toRgbString\":\"157,197,230\",\"--skin__neutral_2\":\"#2F8DDC\",\"--skin__neutral_2__toRgbString\":\"47,141,220\",\"--skin__neutral_3\":\"#2F8DDC\",\"--skin__neutral_3__toRgbString\":\"47,141,220\",\"--skin__primary\":\"#3CCFFF\",\"--skin__primary__toRgbString\":\"60,207,255\",\"--skin__profile_icon_1\":\"#3CCFFF\",\"--skin__profile_icon_1__toRgbString\":\"60,207,255\",\"--skin__profile_icon_2\":\"#3CCFFF\",\"--skin__profile_icon_2__toRgbString\":\"60,207,255\",\"--skin__profile_icon_3\":\"#3CCFFF\",\"--skin__profile_icon_3__toRgbString\":\"60,207,255\",\"--skin__profile_icon_4\":\"#3CCFFF\",\"--skin__profile_icon_4__toRgbString\":\"60,207,255\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#9DC5E6\",\"--skin__search_icon__toRgbString\":\"157,197,230\",\"--skin__table_bg\":\"#024C8C\",\"--skin__table_bg__toRgbString\":\"2,76,140\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#C3E7F5\",\"--skin__tg_primary__toRgbString\":\"195,231,245\",\"--skin__web_bs_yj_bg\":\"#013D74\",\"--skin__web_bs_yj_bg__toRgbString\":\"1,61,116\",\"--skin__web_bs_zc_an2\":\"#005DB4\",\"--skin__web_bs_zc_an2__toRgbString\":\"0,93,180\",\"--skin__web_btmnav_db\":\"#004584\",\"--skin__web_btmnav_db__toRgbString\":\"0,69,132\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#005DB4\",\"--skin__web_left_bg_q__toRgbString\":\"0,93,180\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#00509A\",\"--skin__web_left_bg_z__toRgbString\":\"0,80,154\",\"--skin__web_load_zz\":\"#1466AB66\",\"--skin__web_plat_line\":\"#1466AB\",\"--skin__web_plat_line__toRgbString\":\"20,102,171\",\"--skin__web_topbg_1\":\"#3CCFFF\",\"--skin__web_topbg_1__toRgbString\":\"60,207,255\",\"--skin__web_topbg_3\":\"#00ADE6\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-07 115700.png', 0, 'https://hgdg.dolphinspg.com/siteadmin/skin/lobby_asset/2-1-5/common/'),
(50, 'Roxo', '{\"--skin__ID\":\"2-16\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#FF79FF\",\"--skin__alt_border__toRgbString\":\"255,121,255\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#FF79FF\",\"--skin__alt_neutral_1__toRgbString\":\"255,121,255\",\"--skin__alt_neutral_2\":\"#E14CE1\",\"--skin__alt_neutral_2__toRgbString\":\"225,76,225\",\"--skin__alt_primary\":\"#FFDC83\",\"--skin__alt_primary__toRgbString\":\"255,220,131\",\"--skin__alt_text_primary\":\"#5D075D\",\"--skin__alt_text_primary__toRgbString\":\"93,7,93\",\"--skin__bg_1\":\"#751375\",\"--skin__bg_1__toRgbString\":\"117,19,117\",\"--skin__bg_2\":\"#5D075D\",\"--skin__bg_2__toRgbString\":\"93,7,93\",\"--skin__border\":\"#AA2FAA\",\"--skin__border__toRgbString\":\"170,47,170\",\"--skin__bs_topnav_bg\":\"#4E024E\",\"--skin__bs_topnav_bg__toRgbString\":\"78,2,78\",\"--skin__bs_zc_an1\":\"#7E1B7E\",\"--skin__bs_zc_an1__toRgbString\":\"126,27,126\",\"--skin__bs_zc_bg\":\"#630963\",\"--skin__bs_zc_bg__toRgbString\":\"99,9,99\",\"--skin__btmnav_active\":\"#FFDC83\",\"--skin__btmnav_active__toRgbString\":\"255,220,131\",\"--skin__btmnav_def\":\"#E14CE1\",\"--skin__btmnav_def__toRgbString\":\"225,76,225\",\"--skin__btn_color_1\":\"#FFDC83\",\"--skin__btn_color_1__toRgbString\":\"255,220,131\",\"--skin__btn_color_2\":\"#FFDC83\",\"--skin__btn_color_2__toRgbString\":\"255,220,131\",\"--skin__cards_text\":\"#FF79FF\",\"--skin__cards_text__toRgbString\":\"255,121,255\",\"--skin__ddt_bg\":\"#721072\",\"--skin__ddt_bg__toRgbString\":\"114,16,114\",\"--skin__ddt_icon\":\"#8D1B8D\",\"--skin__ddt_icon__toRgbString\":\"141,27,141\",\"--skin__filter_active\":\"#FFDC83\",\"--skin__filter_active__toRgbString\":\"255,220,131\",\"--skin__filter_bg\":\"#751375\",\"--skin__filter_bg__toRgbString\":\"117,19,117\",\"--skin__home_bg\":\"#751375\",\"--skin__home_bg__toRgbString\":\"117,19,117\",\"--skin__icon_1\":\"#FFDC83\",\"--skin__icon_1__toRgbString\":\"255,220,131\",\"--skin__icon_tg_q\":\"#FF79FF\",\"--skin__icon_tg_q__toRgbString\":\"255,121,255\",\"--skin__icon_tg_z\":\"#FF79FF\",\"--skin__icon_tg_z__toRgbString\":\"255,121,255\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#AA2FAA\",\"--skin__kb_bg__toRgbString\":\"170,47,170\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#5D075D\",\"--skin__leftnav_active__toRgbString\":\"93,7,93\",\"--skin__leftnav_def\":\"#FF79FF\",\"--skin__leftnav_def__toRgbString\":\"255,121,255\",\"--skin__neutral_1\":\"#FF79FF\",\"--skin__neutral_1__toRgbString\":\"255,121,255\",\"--skin__neutral_2\":\"#E14CE1\",\"--skin__neutral_2__toRgbString\":\"225,76,225\",\"--skin__neutral_3\":\"#E14CE1\",\"--skin__neutral_3__toRgbString\":\"225,76,225\",\"--skin__primary\":\"#FFDC83\",\"--skin__primary__toRgbString\":\"255,220,131\",\"--skin__profile_icon_1\":\"#FFDC83\",\"--skin__profile_icon_1__toRgbString\":\"255,220,131\",\"--skin__profile_icon_2\":\"#FFDC83\",\"--skin__profile_icon_2__toRgbString\":\"255,220,131\",\"--skin__profile_icon_3\":\"#FFDC83\",\"--skin__profile_icon_3__toRgbString\":\"255,220,131\",\"--skin__profile_icon_4\":\"#FFDC83\",\"--skin__profile_icon_4__toRgbString\":\"255,220,131\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#FF79FF\",\"--skin__search_icon__toRgbString\":\"255,121,255\",\"--skin__table_bg\":\"#5D075D\",\"--skin__table_bg__toRgbString\":\"93,7,93\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#5D075D\",\"--skin__text_primary__toRgbString\":\"93,7,93\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#FFF1E8\",\"--skin__tg_primary__toRgbString\":\"255,241,232\",\"--skin__web_bs_yj_bg\":\"#4E024E\",\"--skin__web_bs_yj_bg__toRgbString\":\"78,2,78\",\"--skin__web_bs_zc_an2\":\"#962996\",\"--skin__web_bs_zc_an2__toRgbString\":\"150,41,150\",\"--skin__web_btmnav_db\":\"#630963\",\"--skin__web_btmnav_db__toRgbString\":\"99,9,99\",\"--skin__web_filter_gou\":\"#5D075D\",\"--skin__web_filter_gou__toRgbString\":\"93,7,93\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#AA2FAA66\",\"--skin__web_plat_line\":\"#AA2FAA\",\"--skin__web_plat_line__toRgbString\":\"170,47,170\",\"--skin__web_topbg_1\":\"#AF00AF\",\"--skin__web_topbg_1__toRgbString\":\"175,0,175\",\"--skin__web_topbg_3\":\"#880088\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-07 115952.png', 0, 'https://dgsdg.wasabipgpay.com/siteadmin/skin/lobby_asset/2-1-16/common/'),
(51, 'MACALLAN12PG', '{\"--skin__ID\":\"2-23\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#8FACD9\",\"--skin__alt_border__toRgbString\":\"143,172,217\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#8FACD9\",\"--skin__alt_neutral_1__toRgbString\":\"143,172,217\",\"--skin__alt_neutral_2\":\"#496592\",\"--skin__alt_neutral_2__toRgbString\":\"73,101,146\",\"--skin__alt_primary\":\"#ECC866\",\"--skin__alt_primary__toRgbString\":\"236,200,102\",\"--skin__alt_text_primary\":\"#1D222C\",\"--skin__alt_text_primary__toRgbString\":\"29,34,44\",\"--skin__bg_1\":\"#2C3445\",\"--skin__bg_1__toRgbString\":\"44,52,69\",\"--skin__bg_2\":\"#222832\",\"--skin__bg_2__toRgbString\":\"34,40,50\",\"--skin__border\":\"#344868\",\"--skin__border__toRgbString\":\"52,72,104\",\"--skin__bs_topnav_bg\":\"#1D222C\",\"--skin__bs_topnav_bg__toRgbString\":\"29,34,44\",\"--skin__bs_zc_an1\":\"#2A3240\",\"--skin__bs_zc_an1__toRgbString\":\"42,50,64\",\"--skin__bs_zc_bg\":\"#222832\",\"--skin__bs_zc_bg__toRgbString\":\"34,40,50\",\"--skin__btmnav_active\":\"#ECC866\",\"--skin__btmnav_active__toRgbString\":\"236,200,102\",\"--skin__btmnav_def\":\"#6E8AB7\",\"--skin__btmnav_def__toRgbString\":\"110,138,183\",\"--skin__btn_color_1\":\"#ECC866\",\"--skin__btn_color_1__toRgbString\":\"236,200,102\",\"--skin__btn_color_2\":\"#ECC866\",\"--skin__btn_color_2__toRgbString\":\"236,200,102\",\"--skin__cards_text\":\"#8FACD9\",\"--skin__cards_text__toRgbString\":\"143,172,217\",\"--skin__ddt_bg\":\"#272F3E\",\"--skin__ddt_bg__toRgbString\":\"39,47,62\",\"--skin__ddt_icon\":\"#2E384A\",\"--skin__ddt_icon__toRgbString\":\"46,56,74\",\"--skin__filter_active\":\"#ECC866\",\"--skin__filter_active__toRgbString\":\"236,200,102\",\"--skin__filter_bg\":\"#2C3445\",\"--skin__filter_bg__toRgbString\":\"44,52,69\",\"--skin__home_bg\":\"#222832\",\"--skin__home_bg__toRgbString\":\"34,40,50\",\"--skin__icon_1\":\"#ECC866\",\"--skin__icon_1__toRgbString\":\"236,200,102\",\"--skin__icon_tg_q\":\"#8FACD9\",\"--skin__icon_tg_q__toRgbString\":\"143,172,217\",\"--skin__icon_tg_z\":\"#8FACD9\",\"--skin__icon_tg_z__toRgbString\":\"143,172,217\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#344868\",\"--skin__kb_bg__toRgbString\":\"52,72,104\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#1D222C\",\"--skin__leftnav_active__toRgbString\":\"29,34,44\",\"--skin__leftnav_def\":\"#8FACD9\",\"--skin__leftnav_def__toRgbString\":\"143,172,217\",\"--skin__neutral_1\":\"#8FACD9\",\"--skin__neutral_1__toRgbString\":\"143,172,217\",\"--skin__neutral_2\":\"#496592\",\"--skin__neutral_2__toRgbString\":\"73,101,146\",\"--skin__neutral_3\":\"#496592\",\"--skin__neutral_3__toRgbString\":\"73,101,146\",\"--skin__primary\":\"#ECC866\",\"--skin__primary__toRgbString\":\"236,200,102\",\"--skin__profile_icon_1\":\"#ECC866\",\"--skin__profile_icon_1__toRgbString\":\"236,200,102\",\"--skin__profile_icon_2\":\"#ECC866\",\"--skin__profile_icon_2__toRgbString\":\"236,200,102\",\"--skin__profile_icon_3\":\"#ECC866\",\"--skin__profile_icon_3__toRgbString\":\"236,200,102\",\"--skin__profile_icon_4\":\"#ECC866\",\"--skin__profile_icon_4__toRgbString\":\"236,200,102\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#8FACD9\",\"--skin__search_icon__toRgbString\":\"143,172,217\",\"--skin__table_bg\":\"#222832\",\"--skin__table_bg__toRgbString\":\"34,40,50\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#1D222C\",\"--skin__text_primary__toRgbString\":\"29,34,44\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#FBEACE\",\"--skin__tg_primary__toRgbString\":\"251,234,206\",\"--skin__web_bs_yj_bg\":\"#1D222C\",\"--skin__web_bs_yj_bg__toRgbString\":\"29,34,44\",\"--skin__web_bs_zc_an2\":\"#2F3848\",\"--skin__web_bs_zc_an2__toRgbString\":\"47,56,72\",\"--skin__web_btmnav_db\":\"#2D3541\",\"--skin__web_btmnav_db__toRgbString\":\"45,53,65\",\"--skin__web_filter_gou\":\"#1D222C\",\"--skin__web_filter_gou__toRgbString\":\"29,34,44\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#34486866\",\"--skin__web_plat_line\":\"#344868\",\"--skin__web_plat_line__toRgbString\":\"52,72,104\",\"--skin__web_topbg_1\":\"#F6D26F\",\"--skin__web_topbg_1__toRgbString\":\"246,210,111\",\"--skin__web_topbg_3\":\"#EBBF48\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-07 120236.png', 1, 'https://ds.macallan12pgpay1.com/siteadmin/skin/lobby_asset/2-1-23'),
(52, 'bluewhalepg.com', '{\"--skin__ID\":\"2-9\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#FFC6C6\",\"--skin__alt_border__toRgbString\":\"255,198,198\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#FFC6C6\",\"--skin__alt_neutral_1__toRgbString\":\"255,198,198\",\"--skin__alt_neutral_2\":\"#E27E7E\",\"--skin__alt_neutral_2__toRgbString\":\"226,126,126\",\"--skin__alt_primary\":\"#FFC300\",\"--skin__alt_primary__toRgbString\":\"255,195,0\",\"--skin__alt_text_primary\":\"#8D2322\",\"--skin__alt_text_primary__toRgbString\":\"141,35,34\",\"--skin__bg_1\":\"#A12F2F\",\"--skin__bg_1__toRgbString\":\"161,47,47\",\"--skin__bg_2\":\"#852322\",\"--skin__bg_2__toRgbString\":\"133,35,34\",\"--skin__border\":\"#BE4949\",\"--skin__border__toRgbString\":\"190,73,73\",\"--skin__bs_topnav_bg\":\"#721C1B\",\"--skin__bs_topnav_bg__toRgbString\":\"114,28,27\",\"--skin__bs_zc_an1\":\"#A12F2F\",\"--skin__bs_zc_an1__toRgbString\":\"161,47,47\",\"--skin__bs_zc_bg\":\"#852322\",\"--skin__bs_zc_bg__toRgbString\":\"133,35,34\",\"--skin__btmnav_active\":\"#FFC300\",\"--skin__btmnav_active__toRgbString\":\"255,195,0\",\"--skin__btmnav_def\":\"#E27E7E\",\"--skin__btmnav_def__toRgbString\":\"226,126,126\",\"--skin__btn_color_1\":\"#FFC300\",\"--skin__btn_color_1__toRgbString\":\"255,195,0\",\"--skin__btn_color_2\":\"#FFC300\",\"--skin__btn_color_2__toRgbString\":\"255,195,0\",\"--skin__cards_text\":\"#FFC6C6\",\"--skin__cards_text__toRgbString\":\"255,198,198\",\"--skin__ddt_bg\":\"#972A29\",\"--skin__ddt_bg__toRgbString\":\"151,42,41\",\"--skin__ddt_icon\":\"#B23534\",\"--skin__ddt_icon__toRgbString\":\"178,53,52\",\"--skin__filter_active\":\"#FFC300\",\"--skin__filter_active__toRgbString\":\"255,195,0\",\"--skin__filter_bg\":\"#A12F2F\",\"--skin__filter_bg__toRgbString\":\"161,47,47\",\"--skin__home_bg\":\"#852322\",\"--skin__home_bg__toRgbString\":\"133,35,34\",\"--skin__icon_1\":\"#FFC300\",\"--skin__icon_1__toRgbString\":\"255,195,0\",\"--skin__icon_tg_q\":\"#FFC6C6\",\"--skin__icon_tg_q__toRgbString\":\"255,198,198\",\"--skin__icon_tg_z\":\"#FFC6C6\",\"--skin__icon_tg_z__toRgbString\":\"255,198,198\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFC300\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,195,0\",\"--skin__kb_bg\":\"#BE4949\",\"--skin__kb_bg__toRgbString\":\"190,73,73\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#8D2322\",\"--skin__leftnav_active__toRgbString\":\"141,35,34\",\"--skin__leftnav_def\":\"#FFC6C6\",\"--skin__leftnav_def__toRgbString\":\"255,198,198\",\"--skin__neutral_1\":\"#FFC6C6\",\"--skin__neutral_1__toRgbString\":\"255,198,198\",\"--skin__neutral_2\":\"#E27E7E\",\"--skin__neutral_2__toRgbString\":\"226,126,126\",\"--skin__neutral_3\":\"#E27E7E\",\"--skin__neutral_3__toRgbString\":\"226,126,126\",\"--skin__primary\":\"#FFC300\",\"--skin__primary__toRgbString\":\"255,195,0\",\"--skin__profile_icon_1\":\"#FFC300\",\"--skin__profile_icon_1__toRgbString\":\"255,195,0\",\"--skin__profile_icon_2\":\"#FFC300\",\"--skin__profile_icon_2__toRgbString\":\"255,195,0\",\"--skin__profile_icon_3\":\"#FFC300\",\"--skin__profile_icon_3__toRgbString\":\"255,195,0\",\"--skin__profile_icon_4\":\"#FFC300\",\"--skin__profile_icon_4__toRgbString\":\"255,195,0\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#FFC6C6\",\"--skin__search_icon__toRgbString\":\"255,198,198\",\"--skin__table_bg\":\"#852322\",\"--skin__table_bg__toRgbString\":\"133,35,34\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#8D2322\",\"--skin__text_primary__toRgbString\":\"141,35,34\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#F7E2BB\",\"--skin__tg_primary__toRgbString\":\"247,226,187\",\"--skin__web_bs_yj_bg\":\"#721C1B\",\"--skin__web_bs_yj_bg__toRgbString\":\"114,28,27\",\"--skin__web_bs_zc_an2\":\"#BE4949\",\"--skin__web_bs_zc_an2__toRgbString\":\"190,73,73\",\"--skin__web_btmnav_db\":\"#852322\",\"--skin__web_btmnav_db__toRgbString\":\"133,35,34\",\"--skin__web_filter_gou\":\"#8D2322\",\"--skin__web_filter_gou__toRgbString\":\"141,35,34\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#BE494966\",\"--skin__web_plat_line\":\"#BE4949\",\"--skin__web_plat_line__toRgbString\":\"190,73,73\",\"--skin__web_topbg_1\":\"#CE2E2C\",\"--skin__web_topbg_1__toRgbString\":\"206,46,44\",\"--skin__web_topbg_3\":\"#AB2928\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-07 120702.png', 0, 'https://fsdaf.bluewhalepgpay.com/siteadmin/skin/lobby_asset/2-1-9/'),
(53, 'yogapg.com', '{\"--skin__ID\":\"2-31\",\"--skin__accent_1\":\"#04BE02\",\"--skin__accent_1__toRgbString\":\"4,190,2\",\"--skin__accent_2\":\"#EA4E3D\",\"--skin__accent_2__toRgbString\":\"234,78,61\",\"--skin__accent_3\":\"#FFAA09\",\"--skin__accent_3__toRgbString\":\"255,170,9\",\"--skin__alt_border\":\"#C2B7AE\",\"--skin__alt_border__toRgbString\":\"194,183,174\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#C2B7AE\",\"--skin__alt_neutral_1__toRgbString\":\"194,183,174\",\"--skin__alt_neutral_2\":\"#988B7F\",\"--skin__alt_neutral_2__toRgbString\":\"152,139,127\",\"--skin__alt_primary\":\"#FEAF89\",\"--skin__alt_primary__toRgbString\":\"254,175,137\",\"--skin__alt_text_primary\":\"#57433A\",\"--skin__alt_text_primary__toRgbString\":\"87,67,58\",\"--skin__bg_1\":\"#6E5549\",\"--skin__bg_1__toRgbString\":\"110,85,73\",\"--skin__bg_2\":\"#57433A\",\"--skin__bg_2__toRgbString\":\"87,67,58\",\"--skin__border\":\"#816659\",\"--skin__border__toRgbString\":\"129,102,89\",\"--skin__bs_topnav_bg\":\"#3A2F2A\",\"--skin__bs_topnav_bg__toRgbString\":\"58,47,42\",\"--skin__bs_zc_an1\":\"#5E4940\",\"--skin__bs_zc_an1__toRgbString\":\"94,73,64\",\"--skin__bs_zc_bg\":\"#57433B\",\"--skin__bs_zc_bg__toRgbString\":\"87,67,59\",\"--skin__btmnav_active\":\"#FEAF89\",\"--skin__btmnav_active__toRgbString\":\"254,175,137\",\"--skin__btmnav_def\":\"#A79687\",\"--skin__btmnav_def__toRgbString\":\"167,150,135\",\"--skin__btn_color_1\":\"#FEAF89\",\"--skin__btn_color_1__toRgbString\":\"254,175,137\",\"--skin__btn_color_2\":\"#FEAF89\",\"--skin__btn_color_2__toRgbString\":\"254,175,137\",\"--skin__cards_text\":\"#C2B7AE\",\"--skin__cards_text__toRgbString\":\"194,183,174\",\"--skin__ddt_bg\":\"#5E4940\",\"--skin__ddt_bg__toRgbString\":\"94,73,64\",\"--skin__ddt_icon\":\"#6D5950\",\"--skin__ddt_icon__toRgbString\":\"109,89,80\",\"--skin__filter_active\":\"#FEAF89\",\"--skin__filter_active__toRgbString\":\"254,175,137\",\"--skin__filter_bg\":\"#3E332C\",\"--skin__filter_bg__toRgbString\":\"62,51,44\",\"--skin__home_bg\":\"#57433A\",\"--skin__home_bg__toRgbString\":\"87,67,58\",\"--skin__icon_1\":\"#FEAF89\",\"--skin__icon_1__toRgbString\":\"254,175,137\",\"--skin__icon_tg_q\":\"#C2B7AE\",\"--skin__icon_tg_q__toRgbString\":\"194,183,174\",\"--skin__icon_tg_z\":\"#C2B7AE\",\"--skin__icon_tg_z__toRgbString\":\"194,183,174\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FEAF89\",\"--skin__jdd_vip_bjc__toRgbString\":\"254,175,137\",\"--skin__kb_bg\":\"#816659\",\"--skin__kb_bg__toRgbString\":\"129,102,89\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#FFFFFF\",\"--skin__labeltext_accent3__toRgbString\":\"255,255,255\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#57433A\",\"--skin__leftnav_active__toRgbString\":\"87,67,58\",\"--skin__leftnav_def\":\"#C2B7AE\",\"--skin__leftnav_def__toRgbString\":\"194,183,174\",\"--skin__neutral_1\":\"#C2B7AE\",\"--skin__neutral_1__toRgbString\":\"194,183,174\",\"--skin__neutral_2\":\"#988B7F\",\"--skin__neutral_2__toRgbString\":\"152,139,127\",\"--skin__neutral_3\":\"#988B7F\",\"--skin__neutral_3__toRgbString\":\"152,139,127\",\"--skin__primary\":\"#FEAF89\",\"--skin__primary__toRgbString\":\"254,175,137\",\"--skin__profile_icon_1\":\"#FEAF89\",\"--skin__profile_icon_1__toRgbString\":\"254,175,137\",\"--skin__profile_icon_2\":\"#FEAF89\",\"--skin__profile_icon_2__toRgbString\":\"254,175,137\",\"--skin__profile_icon_3\":\"#FEAF89\",\"--skin__profile_icon_3__toRgbString\":\"254,175,137\",\"--skin__profile_icon_4\":\"#FEAF89\",\"--skin__profile_icon_4__toRgbString\":\"254,175,137\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#C2B7AE\",\"--skin__search_icon__toRgbString\":\"194,183,174\",\"--skin__table_bg\":\"#57433A\",\"--skin__table_bg__toRgbString\":\"87,67,58\",\"--skin__text_accent3\":\"#FFFFFF\",\"--skin__text_accent3__toRgbString\":\"255,255,255\",\"--skin__text_primary\":\"#57433A\",\"--skin__text_primary__toRgbString\":\"87,67,58\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#F3E1D8\",\"--skin__tg_primary__toRgbString\":\"243,225,216\",\"--skin__web_bs_yj_bg\":\"#3A2F2A\",\"--skin__web_bs_yj_bg__toRgbString\":\"58,47,42\",\"--skin__web_bs_zc_an2\":\"#654E44\",\"--skin__web_bs_zc_an2__toRgbString\":\"101,78,68\",\"--skin__web_btmnav_db\":\"#58463E\",\"--skin__web_btmnav_db__toRgbString\":\"88,70,62\",\"--skin__web_filter_gou\":\"#57433A\",\"--skin__web_filter_gou__toRgbString\":\"87,67,58\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#81665966\",\"--skin__web_plat_line\":\"#816659\",\"--skin__web_plat_line__toRgbString\":\"129,102,89\",\"--skin__web_topbg_1\":\"#876B5D\",\"--skin__web_topbg_1__toRgbString\":\"135,107,93\",\"--skin__web_topbg_3\":\"#61483E\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-07 120847.png', 0, 'https://modelo777.cushionspg.com/siteadmin/skin/lobby_asset/2-0-22/'),
(54, 'w1-iloveyoupg.com', '{\"--skin__ID\":\"2-28\",\"--skin__accent_1\":\"#10920E\",\"--skin__accent_1__toRgbString\":\"16,146,14\",\"--skin__accent_2\":\"#DF1600\",\"--skin__accent_2__toRgbString\":\"223,22,0\",\"--skin__accent_3\":\"#FFF600\",\"--skin__accent_3__toRgbString\":\"255,246,0\",\"--skin__alt_border\":\"#FFDFE0\",\"--skin__alt_border__toRgbString\":\"255,223,224\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#FFDFE0\",\"--skin__alt_neutral_1__toRgbString\":\"255,223,224\",\"--skin__alt_neutral_2\":\"#FFC5C7\",\"--skin__alt_neutral_2__toRgbString\":\"255,197,199\",\"--skin__alt_primary\":\"#FFE1C9\",\"--skin__alt_primary__toRgbString\":\"255,225,201\",\"--skin__alt_text_primary\":\"#DF6061\",\"--skin__alt_text_primary__toRgbString\":\"223,96,97\",\"--skin__bg_1\":\"#F59E8F\",\"--skin__bg_1__toRgbString\":\"245,158,143\",\"--skin__bg_2\":\"#EE7879\",\"--skin__bg_2__toRgbString\":\"238,120,121\",\"--skin__border\":\"#FCACAD\",\"--skin__border__toRgbString\":\"252,172,173\",\"--skin__bs_topnav_bg\":\"#DF6061\",\"--skin__bs_topnav_bg__toRgbString\":\"223,96,97\",\"--skin__bs_zc_an1\":\"#F88486\",\"--skin__bs_zc_an1__toRgbString\":\"248,132,134\",\"--skin__bs_zc_bg\":\"#EE7879\",\"--skin__bs_zc_bg__toRgbString\":\"238,120,121\",\"--skin__btmnav_active\":\"#FFE1C9\",\"--skin__btmnav_active__toRgbString\":\"255,225,201\",\"--skin__btmnav_def\":\"#FFC5C7\",\"--skin__btmnav_def__toRgbString\":\"255,197,199\",\"--skin__btn_color_1\":\"#FFE1C9\",\"--skin__btn_color_1__toRgbString\":\"255,225,201\",\"--skin__btn_color_2\":\"#FFE1C9\",\"--skin__btn_color_2__toRgbString\":\"255,225,201\",\"--skin__cards_text\":\"#FFDFE0\",\"--skin__cards_text__toRgbString\":\"255,223,224\",\"--skin__ddt_bg\":\"#F88486\",\"--skin__ddt_bg__toRgbString\":\"248,132,134\",\"--skin__ddt_icon\":\"#FFA1A2\",\"--skin__ddt_icon__toRgbString\":\"255,161,162\",\"--skin__filter_active\":\"#FFE1C9\",\"--skin__filter_active__toRgbString\":\"255,225,201\",\"--skin__filter_bg\":\"#F59E8F\",\"--skin__filter_bg__toRgbString\":\"245,158,143\",\"--skin__home_bg\":\"#EE7879\",\"--skin__home_bg__toRgbString\":\"238,120,121\",\"--skin__icon_1\":\"#FFE1C9\",\"--skin__icon_1__toRgbString\":\"255,225,201\",\"--skin__icon_tg_q\":\"#FFDFE0\",\"--skin__icon_tg_q__toRgbString\":\"255,223,224\",\"--skin__icon_tg_z\":\"#FFDFE0\",\"--skin__icon_tg_z__toRgbString\":\"255,223,224\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#FFAA09\",\"--skin__jdd_vip_bjc__toRgbString\":\"255,170,9\",\"--skin__kb_bg\":\"#FCACAD\",\"--skin__kb_bg__toRgbString\":\"252,172,173\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#212121\",\"--skin__labeltext_accent3__toRgbString\":\"33,33,33\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#DF6061\",\"--skin__leftnav_active__toRgbString\":\"223,96,97\",\"--skin__leftnav_def\":\"#FFDFE0\",\"--skin__leftnav_def__toRgbString\":\"255,223,224\",\"--skin__neutral_1\":\"#FFDFE0\",\"--skin__neutral_1__toRgbString\":\"255,223,224\",\"--skin__neutral_2\":\"#FFC5C7\",\"--skin__neutral_2__toRgbString\":\"255,197,199\",\"--skin__neutral_3\":\"#FFC5C7\",\"--skin__neutral_3__toRgbString\":\"255,197,199\",\"--skin__primary\":\"#FFE1C9\",\"--skin__primary__toRgbString\":\"255,225,201\",\"--skin__profile_icon_1\":\"#FFE1C9\",\"--skin__profile_icon_1__toRgbString\":\"255,225,201\",\"--skin__profile_icon_2\":\"#FFE1C9\",\"--skin__profile_icon_2__toRgbString\":\"255,225,201\",\"--skin__profile_icon_3\":\"#FFE1C9\",\"--skin__profile_icon_3__toRgbString\":\"255,225,201\",\"--skin__profile_icon_4\":\"#FFE1C9\",\"--skin__profile_icon_4__toRgbString\":\"255,225,201\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#FFDFE0\",\"--skin__search_icon__toRgbString\":\"255,223,224\",\"--skin__table_bg\":\"#EE7879\",\"--skin__table_bg__toRgbString\":\"238,120,121\",\"--skin__text_accent3\":\"#212121\",\"--skin__text_accent3__toRgbString\":\"33,33,33\",\"--skin__text_primary\":\"#DF6061\",\"--skin__text_primary__toRgbString\":\"223,96,97\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#FEF1EB\",\"--skin__tg_primary__toRgbString\":\"254,241,235\",\"--skin__web_bs_yj_bg\":\"#DF6061\",\"--skin__web_bs_yj_bg__toRgbString\":\"223,96,97\",\"--skin__web_bs_zc_an2\":\"#FF9B9D\",\"--skin__web_bs_zc_an2__toRgbString\":\"255,155,157\",\"--skin__web_btmnav_db\":\"#DF6061\",\"--skin__web_btmnav_db__toRgbString\":\"223,96,97\",\"--skin__web_filter_gou\":\"#DF6061\",\"--skin__web_filter_gou__toRgbString\":\"223,96,97\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#FCACAD66\",\"--skin__web_plat_line\":\"#FCACAD\",\"--skin__web_plat_line__toRgbString\":\"252,172,173\",\"--skin__web_topbg_1\":\"#F67C7D\",\"--skin__web_topbg_1__toRgbString\":\"246,124,125\",\"--skin__web_topbg_3\":\"#DF6061\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-07 121047.png', 0, 'https://gfg.iloveyoupgpay.com/siteadmin/skin/lobby_asset/2-1-28');
INSERT INTO `templates_cores` (`id`, `nome_template`, `temas`, `imagem`, `ativo`, `url_site_images`) VALUES
(56, 'Laranja', '{\"--skin__ID\":\"2-24\",\"--skin__accent_1\":\"#10920E\",\"--skin__accent_1__toRgbString\":\"16,146,14\",\"--skin__accent_2\":\"#DF230E\",\"--skin__accent_2__toRgbString\":\"223,35,14\",\"--skin__accent_3\":\"#FFF600\",\"--skin__accent_3__toRgbString\":\"255,246,0\",\"--skin__alt_border\":\"#FFE7C8\",\"--skin__alt_border__toRgbString\":\"255,231,200\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#FFE7C8\",\"--skin__alt_neutral_1__toRgbString\":\"255,231,200\",\"--skin__alt_neutral_2\":\"#FFBD8B\",\"--skin__alt_neutral_2__toRgbString\":\"255,189,139\",\"--skin__alt_primary\":\"#531F0E\",\"--skin__alt_primary__toRgbString\":\"83,31,14\",\"--skin__alt_text_primary\":\"#FFFFFF\",\"--skin__alt_text_primary__toRgbString\":\"255,255,255\",\"--skin__bg_1\":\"#FC8521\",\"--skin__bg_1__toRgbString\":\"252,133,33\",\"--skin__bg_2\":\"#F26D0A\",\"--skin__bg_2__toRgbString\":\"242,109,10\",\"--skin__border\":\"#FFA561\",\"--skin__border__toRgbString\":\"255,165,97\",\"--skin__bs_topnav_bg\":\"#F16305\",\"--skin__bs_topnav_bg__toRgbString\":\"241,99,5\",\"--skin__bs_zc_an1\":\"#FA831D\",\"--skin__bs_zc_an1__toRgbString\":\"250,131,29\",\"--skin__bs_zc_bg\":\"#F2760C\",\"--skin__bs_zc_bg__toRgbString\":\"242,118,12\",\"--skin__btmnav_active\":\"#531F0E\",\"--skin__btmnav_active__toRgbString\":\"83,31,14\",\"--skin__btmnav_def\":\"#FFC89F\",\"--skin__btmnav_def__toRgbString\":\"255,200,159\",\"--skin__btn_color_1\":\"#531F0E\",\"--skin__btn_color_1__toRgbString\":\"83,31,14\",\"--skin__btn_color_2\":\"#531F0E\",\"--skin__btn_color_2__toRgbString\":\"83,31,14\",\"--skin__cards_text\":\"#FFE7C8\",\"--skin__cards_text__toRgbString\":\"255,231,200\",\"--skin__ddt_bg\":\"#F3791E\",\"--skin__ddt_bg__toRgbString\":\"243,121,30\",\"--skin__ddt_icon\":\"#FA8732\",\"--skin__ddt_icon__toRgbString\":\"250,135,50\",\"--skin__filter_active\":\"#531F0E\",\"--skin__filter_active__toRgbString\":\"83,31,14\",\"--skin__filter_bg\":\"#FC8521\",\"--skin__filter_bg__toRgbString\":\"252,133,33\",\"--skin__home_bg\":\"#F26D0A\",\"--skin__home_bg__toRgbString\":\"242,109,10\",\"--skin__icon_1\":\"#531F0E\",\"--skin__icon_1__toRgbString\":\"83,31,14\",\"--skin__icon_tg_q\":\"#FFE7C8\",\"--skin__icon_tg_q__toRgbString\":\"255,231,200\",\"--skin__icon_tg_z\":\"#FFE7C8\",\"--skin__icon_tg_z__toRgbString\":\"255,231,200\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#531F0E\",\"--skin__jdd_vip_bjc__toRgbString\":\"83,31,14\",\"--skin__kb_bg\":\"#FFA561\",\"--skin__kb_bg__toRgbString\":\"255,165,97\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#212121\",\"--skin__labeltext_accent3__toRgbString\":\"33,33,33\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#FFE7C8\",\"--skin__leftnav_def__toRgbString\":\"255,231,200\",\"--skin__neutral_1\":\"#FFE7C8\",\"--skin__neutral_1__toRgbString\":\"255,231,200\",\"--skin__neutral_2\":\"#FFBD8B\",\"--skin__neutral_2__toRgbString\":\"255,189,139\",\"--skin__neutral_3\":\"#FFBD8B\",\"--skin__neutral_3__toRgbString\":\"255,189,139\",\"--skin__primary\":\"#531F0E\",\"--skin__primary__toRgbString\":\"83,31,14\",\"--skin__profile_icon_1\":\"#531F0E\",\"--skin__profile_icon_1__toRgbString\":\"83,31,14\",\"--skin__profile_icon_2\":\"#531F0E\",\"--skin__profile_icon_2__toRgbString\":\"83,31,14\",\"--skin__profile_icon_3\":\"#531F0E\",\"--skin__profile_icon_3__toRgbString\":\"83,31,14\",\"--skin__profile_icon_4\":\"#531F0E\",\"--skin__profile_icon_4__toRgbString\":\"83,31,14\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#FFE7C8\",\"--skin__search_icon__toRgbString\":\"255,231,200\",\"--skin__table_bg\":\"#F26D0A\",\"--skin__table_bg__toRgbString\":\"242,109,10\",\"--skin__text_accent3\":\"#212121\",\"--skin__text_accent3__toRgbString\":\"33,33,33\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#CBC1C0\",\"--skin__tg_primary__toRgbString\":\"203,193,192\",\"--skin__web_bs_yj_bg\":\"#F16305\",\"--skin__web_bs_yj_bg__toRgbString\":\"241,99,5\",\"--skin__web_bs_zc_an2\":\"#FF963C\",\"--skin__web_bs_zc_an2__toRgbString\":\"255,150,60\",\"--skin__web_btmnav_db\":\"#F2760C\",\"--skin__web_btmnav_db__toRgbString\":\"242,118,12\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#FFA56166\",\"--skin__web_plat_line\":\"#FFA561\",\"--skin__web_plat_line__toRgbString\":\"255,165,97\",\"--skin__web_topbg_1\":\"#82402C\",\"--skin__web_topbg_1__toRgbString\":\"130,64,44\",\"--skin__web_topbg_3\":\"#531F0E\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-09 170824.png', 0, 'https://dg.abalonepgapp.com/siteadmin/skin/lobby_asset/2-1-24/common/'),
(57, 'TEMA LARANJA', '{\"--skin__ID\":\"2-24\",\"--skin__accent_1\":\"#10920E\",\"--skin__accent_1__toRgbString\":\"16,146,14\",\"--skin__accent_2\":\"#DF230E\",\"--skin__accent_2__toRgbString\":\"223,35,14\",\"--skin__accent_3\":\"#FFF600\",\"--skin__accent_3__toRgbString\":\"255,246,0\",\"--skin__alt_border\":\"#FFE7C8\",\"--skin__alt_border__toRgbString\":\"255,231,200\",\"--skin__alt_lead\":\"#FFFFFF\",\"--skin__alt_lead__toRgbString\":\"255,255,255\",\"--skin__alt_neutral_1\":\"#FFE7C8\",\"--skin__alt_neutral_1__toRgbString\":\"255,231,200\",\"--skin__alt_neutral_2\":\"#FFBD8B\",\"--skin__alt_neutral_2__toRgbString\":\"255,189,139\",\"--skin__alt_primary\":\"#531F0E\",\"--skin__alt_primary__toRgbString\":\"83,31,14\",\"--skin__alt_text_primary\":\"#FFFFFF\",\"--skin__alt_text_primary__toRgbString\":\"255,255,255\",\"--skin__bg_1\":\"#FC8521\",\"--skin__bg_1__toRgbString\":\"252,133,33\",\"--skin__bg_2\":\"#F26D0A\",\"--skin__bg_2__toRgbString\":\"242,109,10\",\"--skin__border\":\"#FFA561\",\"--skin__border__toRgbString\":\"255,165,97\",\"--skin__bs_topnav_bg\":\"#F16305\",\"--skin__bs_topnav_bg__toRgbString\":\"241,99,5\",\"--skin__bs_zc_an1\":\"#FA831D\",\"--skin__bs_zc_an1__toRgbString\":\"250,131,29\",\"--skin__bs_zc_bg\":\"#F2760C\",\"--skin__bs_zc_bg__toRgbString\":\"242,118,12\",\"--skin__btmnav_active\":\"#531F0E\",\"--skin__btmnav_active__toRgbString\":\"83,31,14\",\"--skin__btmnav_def\":\"#FFC89F\",\"--skin__btmnav_def__toRgbString\":\"255,200,159\",\"--skin__btn_color_1\":\"#531F0E\",\"--skin__btn_color_1__toRgbString\":\"83,31,14\",\"--skin__btn_color_2\":\"#531F0E\",\"--skin__btn_color_2__toRgbString\":\"83,31,14\",\"--skin__cards_text\":\"#FFE7C8\",\"--skin__cards_text__toRgbString\":\"255,231,200\",\"--skin__ddt_bg\":\"#F3791E\",\"--skin__ddt_bg__toRgbString\":\"243,121,30\",\"--skin__ddt_icon\":\"#FA8732\",\"--skin__ddt_icon__toRgbString\":\"250,135,50\",\"--skin__filter_active\":\"#531F0E\",\"--skin__filter_active__toRgbString\":\"83,31,14\",\"--skin__filter_bg\":\"#FC8521\",\"--skin__filter_bg__toRgbString\":\"252,133,33\",\"--skin__home_bg\":\"#F26D0A\",\"--skin__home_bg__toRgbString\":\"242,109,10\",\"--skin__icon_1\":\"#531F0E\",\"--skin__icon_1__toRgbString\":\"83,31,14\",\"--skin__icon_tg_q\":\"#FFE7C8\",\"--skin__icon_tg_q__toRgbString\":\"255,231,200\",\"--skin__icon_tg_z\":\"#FFE7C8\",\"--skin__icon_tg_z__toRgbString\":\"255,231,200\",\"--skin__jackpot_text\":\"#FFFFFF\",\"--skin__jackpot_text__toRgbString\":\"255,255,255\",\"--skin__jdd_vip_bjc\":\"#531F0E\",\"--skin__jdd_vip_bjc__toRgbString\":\"83,31,14\",\"--skin__kb_bg\":\"#FFA561\",\"--skin__kb_bg__toRgbString\":\"255,165,97\",\"--skin__label_accent3\":\"#FFAA09\",\"--skin__label_accent3__toRgbString\":\"255,170,9\",\"--skin__labeltext_accent3\":\"#212121\",\"--skin__labeltext_accent3__toRgbString\":\"33,33,33\",\"--skin__lead\":\"#FFFFFF\",\"--skin__lead__toRgbString\":\"255,255,255\",\"--skin__leftnav_active\":\"#FFFFFF\",\"--skin__leftnav_active__toRgbString\":\"255,255,255\",\"--skin__leftnav_def\":\"#FFE7C8\",\"--skin__leftnav_def__toRgbString\":\"255,231,200\",\"--skin__neutral_1\":\"#FFE7C8\",\"--skin__neutral_1__toRgbString\":\"255,231,200\",\"--skin__neutral_2\":\"#FFBD8B\",\"--skin__neutral_2__toRgbString\":\"255,189,139\",\"--skin__neutral_3\":\"#FFBD8B\",\"--skin__neutral_3__toRgbString\":\"255,189,139\",\"--skin__primary\":\"#531F0E\",\"--skin__primary__toRgbString\":\"83,31,14\",\"--skin__profile_icon_1\":\"#531F0E\",\"--skin__profile_icon_1__toRgbString\":\"83,31,14\",\"--skin__profile_icon_2\":\"#531F0E\",\"--skin__profile_icon_2__toRgbString\":\"83,31,14\",\"--skin__profile_icon_3\":\"#531F0E\",\"--skin__profile_icon_3__toRgbString\":\"83,31,14\",\"--skin__profile_icon_4\":\"#531F0E\",\"--skin__profile_icon_4__toRgbString\":\"83,31,14\",\"--skin__profile_toptext\":\"#FFFFFF\",\"--skin__profile_toptext__toRgbString\":\"255,255,255\",\"--skin__search_icon\":\"#FFE7C8\",\"--skin__search_icon__toRgbString\":\"255,231,200\",\"--skin__table_bg\":\"#F26D0A\",\"--skin__table_bg__toRgbString\":\"242,109,10\",\"--skin__text_accent3\":\"#212121\",\"--skin__text_accent3__toRgbString\":\"33,33,33\",\"--skin__text_primary\":\"#FFFFFF\",\"--skin__text_primary__toRgbString\":\"255,255,255\",\"--skin__tg_accent_1\":\"#BBDFC1\",\"--skin__tg_accent_1__toRgbString\":\"187,223,193\",\"--skin__tg_accent_3\":\"#FFE7B8\",\"--skin__tg_accent_3__toRgbString\":\"255,231,184\",\"--skin__tg_primary\":\"#CBC1C0\",\"--skin__tg_primary__toRgbString\":\"203,193,192\",\"--skin__web_bs_yj_bg\":\"#F16305\",\"--skin__web_bs_yj_bg__toRgbString\":\"241,99,5\",\"--skin__web_bs_zc_an2\":\"#FF963C\",\"--skin__web_bs_zc_an2__toRgbString\":\"255,150,60\",\"--skin__web_btmnav_db\":\"#F2760C\",\"--skin__web_btmnav_db__toRgbString\":\"242,118,12\",\"--skin__web_filter_gou\":\"#FFFFFF\",\"--skin__web_filter_gou__toRgbString\":\"255,255,255\",\"--skin__web_left_bg_q\":\"#FFFFFF0A\",\"--skin__web_left_bg_shadow\":\"#0000001F\",\"--skin__web_left_bg_shadow_active\":\"#0000001F\",\"--skin__web_left_bg_z\":\"#FFFFFF0D\",\"--skin__web_load_zz\":\"#FFA56166\",\"--skin__web_plat_line\":\"#FFA561\",\"--skin__web_plat_line__toRgbString\":\"255,165,97\",\"--skin__web_topbg_1\":\"#82402C\",\"--skin__web_topbg_1__toRgbString\":\"130,64,44\",\"--skin__web_topbg_3\":\"#531F0E\"}', '../skin/lobby_asset/2-1-22/Captura de tela 2025-12-24 121258.png', 0, 'https://dg.skirtinipgpay1.com/siteadmin/skin/lobby_asset/2-1-24/common/');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tokens_recuperacoes`
--

CREATE TABLE `tokens_recuperacoes` (
  `id_usuario` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `transacoes`
--

CREATE TABLE `transacoes` (
  `id` int(11) NOT NULL,
  `transacao_id` varchar(255) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `tipo` enum('deposito','saque') DEFAULT NULL,
  `data_registro` datetime DEFAULT NULL,
  `qrcode` longtext DEFAULT NULL,
  `code` text DEFAULT NULL,
  `status` enum('pago','processamento','expirado') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `celular` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `saldo` decimal(10,2) DEFAULT 0.00,
  `saldo_afiliados` decimal(10,2) DEFAULT 0.00,
  `rev` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_rev` decimal(10,2) NOT NULL DEFAULT 0.00,
  `real_name` varchar(255) DEFAULT NULL,
  `spassword` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `data_registro` datetime DEFAULT current_timestamp(),
  `invite_code` varchar(255) NOT NULL,
  `invitation_code` varchar(255) DEFAULT NULL,
  `cpf` varchar(11) DEFAULT NULL,
  `tipo_pagamento` int(11) NOT NULL DEFAULT 0,
  `senha_saque` int(11) NOT NULL DEFAULT 0,
  `senhaparasacar` varchar(255) DEFAULT NULL,
  `pessoas_convidadas` int(11) NOT NULL DEFAULT 0,
  `indicacoes_roubadas` int(11) DEFAULT 0,
  `statusaff` int(11) NOT NULL DEFAULT 0,
  `banido` int(11) DEFAULT 0,
  `historico` text DEFAULT NULL,
  `favoritos` text DEFAULT NULL,
  `vip` int(11) NOT NULL DEFAULT 0,
  `recompensa_vip` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_recompensa_vip` decimal(10,2) NOT NULL DEFAULT 0.00,
  `data_nascimento` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `avatar` int(255) NOT NULL DEFAULT 1,
  `facebook` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `telegram` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `birth` varchar(255) DEFAULT NULL,
  `freeze` int(11) DEFAULT 0,
  `relogar` int(11) NOT NULL DEFAULT 0,
  `lobby` int(11) DEFAULT 1,
  `rtp` int(11) DEFAULT NULL,
  `modo_demo` tinyint(1) DEFAULT 0,
  `cpaLvl1` float DEFAULT NULL,
  `cpaLvl2` float DEFAULT NULL,
  `cpaLvl3` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `versell`
--

CREATE TABLE `versell` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `client_id` text DEFAULT NULL,
  `client_secret` text DEFAULT NULL,
  `atualizado` varchar(45) DEFAULT NULL,
  `ativo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Despejando dados para a tabela `versell`
--

INSERT INTO `versell` (`id`, `url`, `client_id`, `client_secret`, `atualizado`, `ativo`) VALUES
(1, 'https://api.versellpay.com', NULL, NULL, '0', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `vip_levels`
--

CREATE TABLE `vip_levels` (
  `id` int(11) NOT NULL,
  `id_vip` int(11) NOT NULL,
  `meta` float NOT NULL,
  `bonus` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Despejando dados para a tabela `vip_levels`
--

INSERT INTO `vip_levels` (`id`, `id_vip`, `meta`, `bonus`) VALUES
(1, 1, 5000, 5),
(2, 2, 18000, 18),
(3, 3, 88000, 28),
(4, 4, 200000, 58),
(5, 5, 1000000, 5000),
(6, 6, 1000000, 390),
(7, 7, 1000000, 500),
(8, 8, 1000000, 600),
(9, 9, 1000000, 700),
(10, 10, 1000000, 800),
(11, 11, 2000000, 900),
(12, 12, 3000000, 1000),
(13, 13, 4000000, 1100),
(14, 14, 5000000, 1200),
(15, 15, 6000000, 1300),
(16, 16, 7000000, 1400);

-- --------------------------------------------------------

--
-- Estrutura para tabela `visita_site`
--

CREATE TABLE `visita_site` (
  `id` int(11) NOT NULL,
  `nav_os` text DEFAULT NULL,
  `mac_os` text DEFAULT NULL,
  `ip_visita` text DEFAULT NULL,
  `refer_visita` text DEFAULT NULL,
  `data_cad` date DEFAULT NULL,
  `hora_cad` time DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `pais` text DEFAULT NULL,
  `cidade` text DEFAULT NULL,
  `estado` text DEFAULT NULL,
  `ads_tipo` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `visita_site`
--

INSERT INTO `visita_site` (`id`, `nav_os`, `mac_os`, `ip_visita`, `refer_visita`, `data_cad`, `hora_cad`, `id_user`, `pais`, `cidade`, `estado`, `ads_tipo`) VALUES
(950, 'Chrome', 'Windows 10', '2804:1b1:f941:16bb:50ac:4875:5eac:a21b', 'https://ganhepremios.fun/02071995admin/jogos', '2026-03-17', '01:52:15', 1, 'Brazil', 'Campinas', 'Sao Paulo', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `webhook`
--

CREATE TABLE `webhook` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `bot_id` varchar(255) NOT NULL,
  `chat_id` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `webhook`
--

INSERT INTO `webhook` (`id`, `nome`, `bot_id`, `chat_id`, `status`) VALUES
(1, 'Cadastros e Pixs', 'asdsadas', 'e2dwesa', 0),
(2, 'Saques', 'asdasd', 'asdasdasdas', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `adicao_saldo`
--
ALTER TABLE `adicao_saldo`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `afiliados_config`
--
ALTER TABLE `afiliados_config`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `aurenpay`
--
ALTER TABLE `aurenpay`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `bau`
--
ALTER TABLE `bau`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `bspay`
--
ALTER TABLE `bspay`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cupom`
--
ALTER TABLE `cupom`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Índices de tabela `cupom_usados`
--
ALTER TABLE `cupom_usados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `valor` (`valor`);

--
-- Índices de tabela `customer_feedback`
--
ALTER TABLE `customer_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Índices de tabela `drakon`
--
ALTER TABLE `drakon`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `expfypay`
--
ALTER TABLE `expfypay`
  ADD UNIQUE KEY `id` (`id`);

--
-- Índices de tabela `festival`
--
ALTER TABLE `festival`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `financeiro`
--
ALTER TABLE `financeiro`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `floats`
--
ALTER TABLE `floats`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `historico_play`
--
ALTER TABLE `historico_play`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `historico_vip`
--
ALTER TABLE `historico_vip`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `igamewin`
--
ALTER TABLE `igamewin`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `lobby_pgsoft`
--
ALTER TABLE `lobby_pgsoft`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `manipulacao_indicacoes`
--
ALTER TABLE `manipulacao_indicacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `maxapigames`
--
ALTER TABLE `maxapigames`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `metodos_pagamentos`
--
ALTER TABLE `metodos_pagamentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `nextpay`
--
ALTER TABLE `nextpay`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `notificacoes_lidas`
--
ALTER TABLE `notificacoes_lidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_read` (`admin_id`,`notification_type`,`notification_id`);

--
-- Índices de tabela `pay_valores_cassino`
--
ALTER TABLE `pay_valores_cassino`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `playfiver`
--
ALTER TABLE `playfiver`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `popups`
--
ALTER TABLE `popups`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `ppclone`
--
ALTER TABLE `ppclone`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `promocoes`
--
ALTER TABLE `promocoes`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Índices de tabela `provedores`
--
ALTER TABLE `provedores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `resgate_comissoes`
--
ALTER TABLE `resgate_comissoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `segurança`
--
ALTER TABLE `segurança`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `solicitacao_saques`
--
ALTER TABLE `solicitacao_saques`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `temas`
--
ALTER TABLE `temas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `templates_cores`
--
ALTER TABLE `templates_cores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `transacoes`
--
ALTER TABLE `transacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `versell`
--
ALTER TABLE `versell`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `vip_levels`
--
ALTER TABLE `vip_levels`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `visita_site`
--
ALTER TABLE `visita_site`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `webhook`
--
ALTER TABLE `webhook`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `adicao_saldo`
--
ALTER TABLE `adicao_saldo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT de tabela `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `afiliados_config`
--
ALTER TABLE `afiliados_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `banner`
--
ALTER TABLE `banner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `bau`
--
ALTER TABLE `bau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=330;

--
-- AUTO_INCREMENT de tabela `cupom`
--
ALTER TABLE `cupom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `cupom_usados`
--
ALTER TABLE `cupom_usados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `customer_feedback`
--
ALTER TABLE `customer_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `festival`
--
ALTER TABLE `festival`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `financeiro`
--
ALTER TABLE `financeiro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `floats`
--
ALTER TABLE `floats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3021332;

--
-- AUTO_INCREMENT de tabela `historico_play`
--
ALTER TABLE `historico_play`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8279;

--
-- AUTO_INCREMENT de tabela `historico_vip`
--
ALTER TABLE `historico_vip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `igamewin`
--
ALTER TABLE `igamewin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `lobby_pgsoft`
--
ALTER TABLE `lobby_pgsoft`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5014;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=329;

--
-- AUTO_INCREMENT de tabela `manipulacao_indicacoes`
--
ALTER TABLE `manipulacao_indicacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `metodos_pagamentos`
--
ALTER TABLE `metodos_pagamentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `notificacoes_lidas`
--
ALTER TABLE `notificacoes_lidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pay_valores_cassino`
--
ALTER TABLE `pay_valores_cassino`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `promocoes`
--
ALTER TABLE `promocoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `provedores`
--
ALTER TABLE `provedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `resgate_comissoes`
--
ALTER TABLE `resgate_comissoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `segurança`
--
ALTER TABLE `segurança`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `solicitacao_saques`
--
ALTER TABLE `solicitacao_saques`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `temas`
--
ALTER TABLE `temas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `templates_cores`
--
ALTER TABLE `templates_cores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de tabela `transacoes`
--
ALTER TABLE `transacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=997813061;

--
-- AUTO_INCREMENT de tabela `vip_levels`
--
ALTER TABLE `vip_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `visita_site`
--
ALTER TABLE `visita_site`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=951;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `customer_feedback`
--
ALTER TABLE `customer_feedback`
  ADD CONSTRAINT `customer_feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
