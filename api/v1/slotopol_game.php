<?php
/**
 * Slotopol Game Page — Android-optimized HTML5 slot UI
 */
session_start();

$jwt            = $_SESSION['sp_jwt']            ?? '';
$gid            = intval($_SESSION['sp_gid']            ?? ($_GET['gid']   ?? 0));
$sl_uid         = intval($_SESSION['sp_uid']            ?? 1);
$cid            = intval($_SESSION['sp_cid']            ?? 1);
$alias          = $_SESSION['sp_alias']          ?? ($_GET['alias'] ?? 'agt/ai');
$mode           = $_SESSION['sp_mode']           ?? ($_GET['mode']  ?? 'real');
$game           = $_SESSION['sp_game']           ?? ($_GET['game']  ?? $alias);
$casino_uid     = intval($_SESSION['sp_casino_uid']     ?? 0);
$casino_coins   = intval($_SESSION['sp_casino_balance'] ?? 0) * 100; // BRL→coins; session stores BRL
// URL param override (passed from launch redirect)
if (isset($_GET['balance'])) {
    $casino_coins = intval($_GET['balance']);
}

if (!$jwt || !$gid) {
    header('Location: /');
    exit;
}

// Derive display name
$display_name = $game ?: ucwords(str_replace(['-', '/'], [' ', ' / '], $alias));

// Determine if keno (gt=2)
$is_keno = (strpos($alias, 'keno') !== false || strpos($alias, '/keno') !== false);
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, viewport-fit=cover">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="theme-color" content="#0a0a1a">
<title><?= htmlspecialchars($display_name) ?> — CasaSlot</title>
<style>
*{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
:root{
  --gold:#f5c518;--red:#e63946;--green:#2dc653;
  --bg:#0a0a1a;--card:#12122a;--border:#1e1e3a;
  --text:#eee;--muted:#888;
}
html,body{height:100%;background:var(--bg);color:var(--text);font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;overflow:hidden;touch-action:pan-y}
.screen{display:flex;flex-direction:column;height:100dvh;max-width:480px;margin:0 auto;position:relative}

/* Header */
.hdr{display:flex;align-items:center;justify-content:space-between;padding:10px 14px 8px;background:rgba(0,0,0,.4);border-bottom:1px solid var(--border);flex-shrink:0}
.hdr .logo{font-size:18px;font-weight:800;color:var(--gold);letter-spacing:-0.5px}
.hdr .bal-box{text-align:right}
.hdr .bal-label{font-size:10px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px}
.hdr .bal-val{font-size:20px;font-weight:700;color:var(--green);line-height:1.1}
.hdr .bal-val.win-flash{color:var(--gold);animation:wflash .4s ease 3}
@keyframes wflash{0%,100%{color:var(--gold)}50%{color:#fff}}
.btn-back{background:rgba(255,255,255,.08);border:1px solid var(--border);color:var(--text);font-size:13px;padding:6px 12px;border-radius:8px;cursor:pointer;white-space:nowrap}
.btn-back:active{opacity:.7}

/* Game label */
.game-label{text-align:center;padding:6px 0 4px;font-size:13px;color:var(--muted);font-weight:600;letter-spacing:.5px;flex-shrink:0}

/* Slot grid */
.slot-wrap{flex:1;display:flex;align-items:center;justify-content:center;padding:8px 12px;overflow:hidden}
.slot-machine{width:100%;background:linear-gradient(180deg,#0f0f22 0%,#080816 100%);border-radius:16px;border:2px solid var(--border);box-shadow:0 0 30px rgba(245,197,24,.1);padding:10px;position:relative}
.slot-inner{display:grid;gap:6px}
.slot-inner.cols5{grid-template-columns:repeat(5,1fr)}
.slot-inner.cols3{grid-template-columns:repeat(3,1fr)}

.reel{display:flex;flex-direction:column;gap:5px;overflow:hidden}
.reel.spinning .sym{animation:spinblur .08s linear infinite}
@keyframes spinblur{0%{transform:translateY(-8px);opacity:.6}100%{transform:translateY(8px);opacity:.6}}

.sym{
  aspect-ratio:1;border-radius:10px;display:flex;align-items:center;justify-content:center;
  font-size:clamp(20px,6vw,32px);background:rgba(255,255,255,.05);
  border:1.5px solid rgba(255,255,255,.08);
  transition:background .15s,border-color .15s;
  user-select:none;position:relative;overflow:hidden
}
.sym.win-line{background:rgba(245,197,24,.18)!important;border-color:var(--gold)!important;box-shadow:0 0 8px var(--gold)}
.sym.wild{background:rgba(255,80,80,.18)!important;border-color:var(--red)!important}

/* Win display */
.win-display{text-align:center;padding:6px;min-height:28px;flex-shrink:0}
.win-msg{font-size:14px;font-weight:700;letter-spacing:.5px}
.win-msg.show{animation:winpop .4s ease}
@keyframes winpop{0%{transform:scale(.7);opacity:0}60%{transform:scale(1.15)}100%{transform:scale(1);opacity:1}}
.win-msg.big{color:var(--gold);font-size:18px}
.win-msg.small{color:var(--green)}
.win-msg.lose{color:var(--muted);font-size:12px}

/* Controls */
.controls{flex-shrink:0;padding:8px 14px 12px;background:rgba(0,0,0,.3);border-top:1px solid var(--border)}
.bet-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}
.bet-label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px}
.bet-ctrl{display:flex;align-items:center;gap:10px}
.bet-btn{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,.08);border:1px solid var(--border);color:var(--text);font-size:18px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .15s}
.bet-btn:active{background:rgba(255,255,255,.2)}
.bet-val{font-size:20px;font-weight:700;color:var(--gold);min-width:40px;text-align:center}
.lines-row{display:flex;align-items:center;gap:8px;margin-bottom:10px}
.lines-label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;flex:1}
.lines-val{font-size:13px;color:var(--text);font-weight:600}
.spin-btn{
  width:100%;padding:16px;border-radius:14px;border:none;
  background:linear-gradient(135deg,#f5c518,#e0a800);
  color:#000;font-size:18px;font-weight:800;letter-spacing:1px;
  cursor:pointer;transition:transform .1s,box-shadow .1s;
  box-shadow:0 4px 18px rgba(245,197,24,.4);
  text-transform:uppercase
}
.spin-btn:active{transform:scale(.97)}
.spin-btn:disabled{background:#444;color:#666;box-shadow:none;transform:none}
.spin-btn.spinning-state{background:linear-gradient(135deg,#888,#555);animation:pulse .5s ease infinite alternate}
@keyframes pulse{from{box-shadow:0 4px 18px rgba(128,128,128,.3)}to{box-shadow:0 4px 28px rgba(128,128,128,.5)}}

/* Mode badge */
.mode-badge{position:absolute;top:10px;right:10px;padding:3px 8px;border-radius:6px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px}
.mode-badge.demo{background:rgba(255,165,0,.2);color:orange;border:1px solid orange}
.mode-badge.real{background:rgba(44,198,83,.15);color:var(--green);border:1px solid var(--green)}

/* Error toast */
.toast{position:fixed;bottom:80px;left:50%;transform:translateX(-50%);background:#333;color:#fff;padding:10px 20px;border-radius:10px;font-size:13px;display:none;z-index:999}

/* Loading overlay */
.loading-overlay{position:absolute;inset:0;background:rgba(0,0,0,.7);display:flex;align-items:center;justify-content:center;border-radius:14px;z-index:10}
.loading-overlay .spinner{width:40px;height:40px;border:3px solid var(--border);border-top-color:var(--gold);border-radius:50%;animation:spin 0.7s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
</head>
<body>
<div class="screen">

  <!-- Header -->
  <div class="hdr">
    <div>
      <div class="logo">🎰 CasaSlot</div>
    </div>
    <div class="bal-box">
      <div class="bal-label">Saldo</div>
      <div class="bal-val" id="balanceDisplay">R$ 0,00</div>
    </div>
    <button class="btn-back" id="btnBack">← Sair</button>
  </div>

  <!-- Game label -->
  <div class="game-label">
    <?= htmlspecialchars($display_name) ?>
    <span class="mode-badge <?= $mode ?>">
      <?= $mode === 'demo' ? 'DEMO' : 'REAL' ?>
    </span>
  </div>

  <!-- Slot grid area -->
  <div class="slot-wrap">
    <div class="slot-machine" id="slotMachine">
      <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
      </div>
      <div class="slot-inner cols5" id="slotGrid"></div>
    </div>
  </div>

  <!-- Win display -->
  <div class="win-display">
    <div class="win-msg" id="winMsg"></div>
  </div>

  <!-- Controls -->
  <div class="controls">
    <div class="bet-row">
      <span class="bet-label">Aposta (R$)</span>
      <div class="bet-ctrl">
        <button class="bet-btn" id="betMinus">−</button>
        <span class="bet-val" id="betDisplay">0,01</span>
        <button class="bet-btn" id="betPlus">+</button>
      </div>
    </div>
    <div class="lines-row">
      <span class="lines-label">Linhas ativas</span>
      <span class="lines-val" id="linesDisplay">15</span>
    </div>
    <button class="spin-btn" id="spinBtn">GIRAR</button>
  </div>
</div>

<div class="toast" id="toast"></div>

<script>
const JWT          = <?= json_encode($jwt) ?>;
const GID          = <?= $gid ?>;
const UID          = <?= $sl_uid ?>;
const CID          = <?= $cid ?>;
const ALIAS        = <?= json_encode($alias) ?>;
const MODE         = <?= json_encode($mode) ?>;
const CASINO_UID   = <?= $casino_uid ?>;
const INIT_COINS   = <?= $casino_coins ?>; // casino balance in coins (1 coin = R$0.01)

const BET_STEPS = [1, 2, 5, 10, 25, 50, 100, 200, 500, 1000];

let state = {
  slotWallet: 0,     // slotopol server wallet (shared 1B buffer)
  casinoCoins: INIT_COINS, // actual casino balance tracked per-session
  bet: 1,            // in coins (1 coin = R$0.01)
  betIdx: 0,
  lines: 15,
  grid: [],
  spinning: false,
  cols: 5,
  rows: 3,
};

const $ = id => document.getElementById(id);
const fmt = coins => 'R$ ' + (coins / 100).toFixed(2).replace('.', ',');

// ── API helper ────────────────────────────────────────────────────────────
async function api(path, body) {
  const r = await fetch('/slotopol' + path, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer ' + JWT,
    },
    body: JSON.stringify({ cid: CID, uid: UID, gid: GID, ...body }),
  });
  return r.json();
}

// ── Symbol emoji map ──────────────────────────────────────────────────────
const SYMS = {
  1:  { e: '🍒', c: '#e63946' },
  2:  { e: '🍋', c: '#f5c518' },
  3:  { e: '🍊', c: '#f77f00' },
  4:  { e: '🍇', c: '#9b5de5' },
  5:  { e: '⭐', c: '#f5c518' },
  6:  { e: '🔔', c: '#ffd60a' },
  7:  { e: '💎', c: '#48cae4' },
  8:  { e: '🃏', c: '#2dc653' },
  9:  { e: '👑', c: '#e9c46a' },
  10: { e: '🎰', c: '#e63946' },
  11: { e: '🌟', c: '#fff' },
  12: { e: '🐉', c: '#ff6b6b' },
};
function symInfo(n) { return SYMS[n] || { e: '?', c: '#888' }; }

// ── Build grid DOM ────────────────────────────────────────────────────────
function buildGrid(cols, rows) {
  state.cols = cols;
  state.rows = rows;
  const grid = $('slotGrid');
  grid.className = `slot-inner cols${cols}`;
  grid.innerHTML = '';
  for (let c = 0; c < cols; c++) {
    const reel = document.createElement('div');
    reel.className = 'reel';
    reel.id = 'reel' + c;
    for (let r = 0; r < rows; r++) {
      const sym = document.createElement('div');
      sym.className = 'sym';
      sym.id = `sym_${c}_${r}`;
      reel.appendChild(sym);
    }
    grid.appendChild(reel);
  }
}

// ── Render grid from flat or nested array ─────────────────────────────────
function renderGrid(grid) {
  // grid can be [[col0row0, col0row1, ...], ...] or flat array
  const cols = state.cols, rows = state.rows;
  for (let c = 0; c < cols; c++) {
    for (let r = 0; r < rows; r++) {
      let val;
      if (Array.isArray(grid[c])) {
        val = grid[c][r];
      } else {
        val = grid[c * rows + r];
      }
      const el = $(`sym_${c}_${r}`);
      if (!el) continue;
      const info = symInfo(val);
      el.textContent = info.e;
      el.style.background = `${info.c}22`;
      el.style.borderColor = `${info.c}55`;
      el.classList.remove('win-line', 'wild');
    }
  }
}

// ── Spin animation ────────────────────────────────────────────────────────
function startSpinAnim() {
  for (let c = 0; c < state.cols; c++) {
    const reel = $('reel' + c);
    if (reel) reel.classList.add('spinning');
  }
}
function stopSpinAnim() {
  for (let c = 0; c < state.cols; c++) {
    const reel = $('reel' + c);
    if (reel) reel.classList.remove('spinning');
  }
}

// ── Update balance display ────────────────────────────────────────────────
function setBalance(coins) {
  state.prevWallet = state.wallet;
  state.wallet = coins;
  const el = $('balanceDisplay');
  el.textContent = fmt(coins);
  el.classList.remove('win-flash');
  if (coins > state.prevWallet) {
    void el.offsetWidth;
    el.classList.add('win-flash');
  }
}

// ── Show win message ──────────────────────────────────────────────────────
function showWin(coinsWon) {
  const el = $('winMsg');
  el.className = 'win-msg show';
  if (coinsWon > 0) {
    el.classList.add(coinsWon > 500 ? 'big' : 'small');
    el.textContent = `🎉 GANHOU ${fmt(coinsWon)}!`;
  } else {
    el.classList.add('lose');
    el.textContent = 'Sem ganhos desta vez';
  }
  setTimeout(() => { el.textContent = ''; el.className = 'win-msg'; }, 2500);
}

// ── Toast ──────────────────────────────────────────────────────────────────
function toast(msg) {
  const el = $('toast');
  el.textContent = msg;
  el.style.display = 'block';
  setTimeout(() => el.style.display = 'none', 2500);
}

// ── Init game from API ────────────────────────────────────────────────────
async function initGame() {
  try {
    const info = await api('/game/info', {});
    const g = info.game || {};
    const cols = Array.isArray(g.grid) && Array.isArray(g.grid[0]) ? g.grid.length : 5;
    const rows = Array.isArray(g.grid) && Array.isArray(g.grid[0]) ? g.grid[0].length : 3;
    buildGrid(cols, rows);
    if (g.grid) renderGrid(g.grid);
    if (g.sel !== undefined) {
      state.lines = g.sel;
      $('linesDisplay').textContent = g.sel;
    }
    if (g.bet !== undefined) {
      // bet from slotopol is in coins, step to nearest
      const betCoins = g.bet;
      state.bet = Math.max(1, betCoins);
      state.betIdx = Math.max(0, BET_STEPS.indexOf(state.bet));
      if (state.betIdx < 0) state.betIdx = 0;
    }
    const walletData = await api('/prop/wallet/get', {});
    setBalance(walletData.wallet || 0);
    $('betDisplay').textContent = (state.bet / 100).toFixed(2).replace('.', ',');
  } catch(e) {
    toast('Erro ao carregar jogo');
  } finally {
    $('loadingOverlay').style.display = 'none';
  }
}

// ── Spin ──────────────────────────────────────────────────────────────────
async function doSpin() {
  if (state.spinning) return;
  const cost = state.bet * state.lines;
  if (MODE === 'real' && state.wallet < cost) {
    toast('Saldo insuficiente');
    return;
  }

  state.spinning = true;
  const btn = $('spinBtn');
  btn.disabled = true;
  btn.classList.add('spinning-state');
  btn.textContent = '⏳ GIRANDO';

  startSpinAnim();

  try {
    const result = await api('/slot/spin', {});
    const g = result.game || {};

    // Brief spin visual delay
    await new Promise(r => setTimeout(r, 350));
    stopSpinAnim();

    if (g.grid) renderGrid(g.grid);

    const newWallet = result.wallet !== undefined ? result.wallet : state.wallet - cost;
    const won = newWallet - state.wallet + cost;  // won = (new - old) + cost
    setBalance(newWallet);
    showWin(Math.max(0, won));

  } catch(e) {
    stopSpinAnim();
    toast('Erro no giro. Tente novamente.');
  } finally {
    state.spinning = false;
    btn.disabled = false;
    btn.classList.remove('spinning-state');
    btn.textContent = 'GIRAR';
  }
}

// ── Bet controls ──────────────────────────────────────────────────────────
function updateBetDisplay() {
  $('betDisplay').textContent = (state.bet / 100).toFixed(2).replace('.', ',');
}
$('betPlus').addEventListener('click', async () => {
  if (state.betIdx < BET_STEPS.length - 1) {
    state.betIdx++;
    state.bet = BET_STEPS[state.betIdx];
    updateBetDisplay();
    try { await api('/slot/bet/set', { bet: state.bet }); } catch(e){}
  }
});
$('betMinus').addEventListener('click', async () => {
  if (state.betIdx > 0) {
    state.betIdx--;
    state.bet = BET_STEPS[state.betIdx];
    updateBetDisplay();
    try { await api('/slot/bet/set', { bet: state.bet }); } catch(e){}
  }
});

// ── Spin button ───────────────────────────────────────────────────────────
$('spinBtn').addEventListener('click', doSpin);
document.addEventListener('keydown', e => {
  if (e.code === 'Space') { e.preventDefault(); doSpin(); }
});

// ── Back / wallet sync ────────────────────────────────────────────────────
$('btnBack').addEventListener('click', async () => {
  if (MODE === 'real') {
    try {
      await fetch('/slotopol_sync', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ wallet: state.wallet }),
      });
    } catch(e) {}
  }
  window.location.href = '/';
});

// Handle page unload: sync wallet back
window.addEventListener('beforeunload', () => {
  if (MODE === 'real' && state.wallet > 0) {
    navigator.sendBeacon('/slotopol_sync', JSON.stringify({ wallet: state.wallet }));
  }
});

// ── Boot ──────────────────────────────────────────────────────────────────
buildGrid(5, 3);
initGame();
</script>
</body>
</html>
