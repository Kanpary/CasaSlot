<?php
session_start();
if (!defined('DASH')) define('DASH', '02071995admin');
require_once __DIR__ . '/../' . DASH . '/services/database.php';
require_once __DIR__ . '/../' . DASH . '/services/funcao.php';
require_once __DIR__ . '/../' . DASH . '/services/crud.php';

$config = $mysqli->query("SELECT * FROM canvas_slot_config WHERE id=1 LIMIT 1")->fetch_assoc();
if (!$config) {
    die('Jogo não configurado.');
}

$logado = !empty($_SESSION['id_user']);
$saldo_raw = 0;
if ($logado) {
    $uid = intval($_SESSION['id_user']);
    $r = $mysqli->query("SELECT saldo FROM usuarios WHERE id=$uid LIMIT 1");
    if ($r) { $saldo_raw = (float)($r->fetch_assoc()['saldo'] ?? 0); }
}

$simbolos   = json_decode($config['simbolos'], true) ?: ['🐯','💎','🍋','🍇','🔔','⭐','🎰','💰','🃏','❤️'];
$mult       = json_decode($config['multiplicadores'], true) ?: ['3x_same'=>5,'4x_same'=>15,'5x_same'=>50,'3x_wild'=>10,'4x_wild'=>25,'5x_wild'=>100];
$nome_jogo  = htmlspecialchars($config['nome_jogo']);
$rtp        = (float)$config['rtp'];
$aposta_min = (float)$config['aposta_minima'];
$aposta_max = (float)$config['aposta_maxima'];
$cor_bg     = htmlspecialchars($config['cor_fundo']);
$cor_pri    = htmlspecialchars($config['cor_primaria']);
$modo_demo  = (bool)$config['modo_demo'];
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<title><?= $nome_jogo ?></title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{
  background:<?= $cor_bg ?>;
  font-family:'Segoe UI',sans-serif;
  display:flex;flex-direction:column;align-items:center;
  min-height:100vh;color:#fff;overflow-x:hidden;
}
.game-wrapper{
  width:100%;max-width:480px;
  padding:12px;display:flex;flex-direction:column;align-items:center;gap:10px;
}
.header{
  width:100%;display:flex;justify-content:space-between;align-items:center;
  background:rgba(0,0,0,0.4);border-radius:12px;padding:10px 16px;
}
.header h1{font-size:1.1rem;font-weight:700;color:<?= $cor_pri ?>;}
.back-btn{
  background:rgba(255,255,255,0.1);border:none;color:#fff;
  padding:6px 12px;border-radius:8px;cursor:pointer;font-size:0.85rem;
}
.canvas-wrap{
  position:relative;width:100%;border-radius:16px;overflow:hidden;
  background:linear-gradient(180deg,#0d0020 0%,#1a0035 100%);
  box-shadow:0 0 40px rgba(<?= ltrim($cor_pri,'#') ?>,0.3);
}
canvas{display:block;width:100%;height:auto;}
.win-overlay{
  position:absolute;top:0;left:0;width:100%;height:100%;
  display:none;align-items:center;justify-content:center;
  font-size:2.5rem;font-weight:900;color:#FFD700;
  text-shadow:0 0 20px rgba(255,215,0,0.9),0 0 40px rgba(255,215,0,0.6);
  animation:winPulse 0.4s ease infinite alternate;
  pointer-events:none;background:rgba(0,0,0,0.3);border-radius:16px;
}
@keyframes winPulse{from{transform:scale(1);}to{transform:scale(1.08);}}
.stats-row{
  width:100%;display:flex;gap:8px;
}
.stat-card{
  flex:1;background:rgba(0,0,0,0.45);border-radius:10px;
  padding:10px;text-align:center;
}
.stat-label{font-size:0.65rem;color:#aaa;text-transform:uppercase;letter-spacing:1px;}
.stat-value{font-size:1.1rem;font-weight:700;color:<?= $cor_pri ?>;}
.controls{
  width:100%;background:rgba(0,0,0,0.45);border-radius:14px;padding:14px;
  display:flex;flex-direction:column;gap:12px;
}
.bet-row{display:flex;align-items:center;gap:8px;}
.bet-label{font-size:0.8rem;color:#aaa;min-width:50px;}
.bet-btn{
  background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
  color:#fff;width:36px;height:36px;border-radius:8px;font-size:1rem;
  cursor:pointer;transition:all 0.2s;
}
.bet-btn:hover{background:rgba(255,255,255,0.2);}
.bet-display{
  flex:1;text-align:center;font-size:1.1rem;font-weight:700;
  color:<?= $cor_pri ?>;background:rgba(0,0,0,0.3);
  border-radius:8px;padding:6px;
}
.spin-btn{
  width:100%;padding:14px;font-size:1.2rem;font-weight:700;
  background:linear-gradient(135deg,<?= $cor_pri ?>,#e07800);
  border:none;border-radius:12px;color:#000;cursor:pointer;
  box-shadow:0 4px 20px rgba(240,165,0,0.5);
  transition:all 0.2s;letter-spacing:1px;
}
.spin-btn:hover:not(:disabled){transform:translateY(-2px);box-shadow:0 6px 25px rgba(240,165,0,0.7);}
.spin-btn:disabled{opacity:0.5;cursor:not-allowed;transform:none;}
.paylines{
  width:100%;background:rgba(0,0,0,0.3);border-radius:10px;
  padding:10px 14px;font-size:0.75rem;color:#bbb;
}
.paylines strong{color:<?= $cor_pri ?>;}
.demo-badge{
  position:absolute;top:8px;left:8px;background:rgba(255,120,0,0.85);
  padding:3px 8px;border-radius:6px;font-size:0.65rem;font-weight:700;
  letter-spacing:1px;
}
.history-panel{
  width:100%;background:rgba(0,0,0,0.35);border-radius:10px;
  padding:10px 14px;max-height:100px;overflow-y:auto;
}
.history-panel h4{font-size:0.75rem;color:#aaa;margin-bottom:4px;}
.history-item{font-size:0.75rem;padding:2px 0;border-bottom:1px solid rgba(255,255,255,0.06);}
.history-item.win{color:#4cff80;}
.history-item.loss{color:#ff6060;}
</style>
</head>
<body>
<div class="game-wrapper">
  <div class="header">
    <h1>🎰 <?= $nome_jogo ?></h1>
    <button class="back-btn" onclick="history.back()">← Voltar</button>
  </div>

  <div class="canvas-wrap" id="canvasWrap">
    <?php if ($modo_demo && !$logado): ?>
    <div class="demo-badge">DEMO</div>
    <?php endif; ?>
    <canvas id="slotCanvas"></canvas>
    <div class="win-overlay" id="winOverlay"></div>
  </div>

  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-label">Saldo</div>
      <div class="stat-value" id="displayBalance">R$ <?= number_format($saldo_raw,2,',','.') ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Último ganho</div>
      <div class="stat-value" id="displayWin">—</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Rodadas</div>
      <div class="stat-value" id="displayRounds">0</div>
    </div>
  </div>

  <div class="controls">
    <div class="bet-row">
      <span class="bet-label">Aposta</span>
      <button class="bet-btn" onclick="changeBet(-1)">−</button>
      <div class="bet-display" id="betDisplay">R$ <?= number_format($aposta_min,2,',','.') ?></div>
      <button class="bet-btn" onclick="changeBet(1)">+</button>
    </div>
    <button class="spin-btn" id="spinBtn" onclick="doSpin()">🎰 GIRAR</button>
  </div>

  <div class="paylines">
    <strong>Pagamentos:</strong>
    3 iguais: <?= $mult['3x_same'] ?>x | 4 iguais: <?= $mult['4x_same'] ?>x | 5 iguais: <?= $mult['5x_same'] ?>x |
    3 Wild: <?= $mult['3x_wild'] ?>x | 4 Wild: <?= $mult['4x_wild'] ?>x | 5 Wild: <?= $mult['5x_wild'] ?>x
  </div>

  <div class="history-panel" id="historyPanel">
    <h4>Histórico de rodadas</h4>
  </div>
</div>

<script>
const SYMBOLS   = <?= json_encode($simbolos) ?>;
const WILD      = SYMBOLS[0];
const MULT      = <?= json_encode($mult) ?>;
const RTP       = <?= $rtp ?> / 100;
const BET_MIN   = <?= $aposta_min ?>;
const BET_MAX   = <?= $aposta_max ?>;
const BET_STEPS = [1,2,5,10,20,50,100,200,500].filter(v=>v>=BET_MIN&&v<=BET_MAX);
const LOGGED_IN = <?= $logado ? 'true' : 'false' ?>;

let balance   = <?= $saldo_raw ?>;
let bet       = BET_MIN;
let betIdx    = 0;
let spinning  = false;
let rounds    = 0;

const canvas  = document.getElementById('slotCanvas');
const ctx     = canvas.getContext('2d');
const COLS    = 5, ROWS = 3;
let cellW, cellH;

// Reel state
let reels = Array.from({length:COLS},()=>Array.from({length:ROWS},()=>randSym()));
let spinPos = Array(COLS).fill(0);
let spinVel = Array(COLS).fill(0);
let spinTarget = Array(COLS).fill(null);
let colDone = Array(COLS).fill(true);
let finalSyms = null;

function randSym(){return SYMBOLS[Math.floor(Math.random()*SYMBOLS.length)];}

function resize(){
  const wrap = document.getElementById('canvasWrap');
  const w = wrap.clientWidth;
  canvas.width  = w;
  canvas.height = Math.round(w * 0.65);
  cellW = w/COLS;
  cellH = canvas.height/ROWS;
  drawStatic();
}

function drawStatic(){
  ctx.clearRect(0,0,canvas.width,canvas.height);
  drawBackground();
  for(let c=0;c<COLS;c++){
    for(let r=0;r<ROWS;r++){
      drawCell(c,r,reels[c][r],0);
    }
  }
  drawGrid();
}

function drawBackground(){
  const grad = ctx.createLinearGradient(0,0,0,canvas.height);
  grad.addColorStop(0,'#0d0020');
  grad.addColorStop(1,'#1a0035');
  ctx.fillStyle = grad;
  ctx.fillRect(0,0,canvas.width,canvas.height);
}

function drawCell(col,row,sym,offset){
  const x = col*cellW;
  const y = row*cellH + offset;
  ctx.save();
  ctx.beginPath();
  ctx.roundRect(x+3,y+3,cellW-6,cellH-6,10);
  ctx.fillStyle='rgba(255,255,255,0.05)';
  ctx.fill();
  ctx.font = `${Math.floor(cellH*0.42)}px serif`;
  ctx.textAlign='center';
  ctx.textBaseline='middle';
  ctx.fillText(sym, x+cellW/2, y+cellH/2);
  ctx.restore();
}

function drawGrid(){
  ctx.save();
  ctx.strokeStyle='rgba(255,255,255,0.12)';
  ctx.lineWidth=1;
  for(let c=1;c<COLS;c++){
    ctx.beginPath();ctx.moveTo(c*cellW,0);ctx.lineTo(c*cellW,canvas.height);ctx.stroke();
  }
  for(let r=1;r<ROWS;r++){
    ctx.beginPath();ctx.moveTo(0,r*cellH);ctx.lineTo(canvas.width,r*cellH);ctx.stroke();
  }
  // Center payline highlight
  ctx.strokeStyle='rgba(240,165,0,0.35)';
  ctx.lineWidth=2;
  ctx.beginPath();ctx.moveTo(0,cellH*1.5);ctx.lineTo(canvas.width,cellH*1.5);ctx.stroke();
  ctx.restore();
}

function highlightWinCells(cells){
  cells.forEach(({c,r})=>{
    ctx.save();
    ctx.beginPath();
    ctx.roundRect(c*cellW+3,r*cellH+3,cellW-6,cellH-6,10);
    ctx.strokeStyle='#FFD700';
    ctx.lineWidth=3;
    ctx.shadowColor='#FFD700';
    ctx.shadowBlur=15;
    ctx.stroke();
    ctx.restore();
  });
}

// Animation loop
let animFrame = null;
let colOffsets = Array(COLS).fill(0);
let spinSpeeds = Array(COLS).fill(0);
let colStopped = Array(COLS).fill(true);
let stopQueue  = [];

function startSpin(targets){
  finalSyms = targets;
  for(let c=0;c<COLS;c++){
    colOffsets[c]=0;
    spinSpeeds[c]=cellH*0.35+Math.random()*cellH*0.15;
    colStopped[c]=false;
  }
  stopQueue = [0,1,2,3,4].map((c,i)=>setTimeout(()=>stopCol(c),700+i*200));
  if(animFrame) cancelAnimationFrame(animFrame);
  animFrame = requestAnimationFrame(animLoop);
}

function stopCol(c){
  colStopped[c]='stopping';
}

function animLoop(){
  ctx.clearRect(0,0,canvas.width,canvas.height);
  drawBackground();

  let allDone = true;
  for(let c=0;c<COLS;c++){
    if(colStopped[c]===true){
      // already stopped — draw final
      for(let r=0;r<ROWS;r++) drawCell(c,r,reels[c][r],0);
    } else {
      allDone = false;
      colOffsets[c] += spinSpeeds[c];

      if(colStopped[c]==='stopping' && colOffsets[c]>=cellH){
        // snap to final
        colOffsets[c]=0;
        // shift reel down, insert random at top, until all rows match target
        for(let r=ROWS-1;r>=1;r--) reels[c][r]=reels[c][r-1];
        reels[c][0]=randSym();

        // Check if the bottom 3 rows match the target — simple version: set final after enough spins
        colStopped[c]='snapping';
        let snaps = 0;
        const snapInterval = setInterval(()=>{
          snaps++;
          colOffsets[c]=0;
          for(let r=ROWS-1;r>=1;r--) reels[c][r]=reels[c][r-1];
          reels[c][0]=randSym();
          if(snaps>=3){
            clearInterval(snapInterval);
            // Set final target symbols
            for(let r=0;r<ROWS;r++) reels[c][r]=finalSyms[c][r];
            colStopped[c]=true;
          }
        },80);
      } else if(colStopped[c]!=='snapping'){
        if(colOffsets[c]>=cellH){
          colOffsets[c]-=cellH;
          for(let r=ROWS-1;r>=1;r--) reels[c][r]=reels[c][r-1];
          reels[c][0]=randSym();
        }
        const off = colOffsets[c];
        for(let r=0;r<ROWS;r++) drawCell(c,r,reels[c][r],off);
        // ghost top
        drawCell(c,-1,reels[c][0],off);
      } else {
        for(let r=0;r<ROWS;r++) drawCell(c,r,reels[c][r],0);
      }
    }
  }

  drawGrid();

  if(!allDone){
    animFrame = requestAnimationFrame(animLoop);
  } else {
    // Spin done
    onSpinComplete();
  }
}

function onSpinComplete(){
  spinning = false;
  document.getElementById('spinBtn').disabled = false;

  const result = evaluateSpin(reels);
  if(result.win>0){
    showWin(result.win, result.cells);
  } else {
    document.getElementById('displayWin').textContent='R$ 0,00';
  }

  if(LOGGED_IN){
    // Push result to server
    fetch('/slot_canvas/api.php',{
      method:'POST',
      headers:{'Content-Type':'application/json'},
      credentials:'include',
      body:JSON.stringify({action:'result',bet,win:result.win})
    }).then(r=>r.json()).then(d=>{
      if(d.saldo!==undefined){
        balance=d.saldo;
        document.getElementById('displayBalance').textContent='R$ '+fmtMoney(balance);
      }
    }).catch(()=>{});
  } else {
    balance += result.win - bet;
    document.getElementById('displayBalance').textContent='R$ '+fmtMoney(Math.max(balance,0));
  }

  addHistory(bet, result.win);
  document.getElementById('displayRounds').textContent = ++rounds;
}

function evaluateSpin(grid){
  // Check center row (row 1)
  const row = [grid[0][1],grid[1][1],grid[2][1],grid[3][1],grid[4][1]];
  let win = 0;
  let winCells = [];

  // Count matching from left
  let base = row[0];
  let count = 1;
  for(let i=1;i<5;i++){
    if(row[i]===base || row[i]===WILD || base===WILD){
      if(base===WILD) base=row[i];
      count++;
    } else break;
  }

  const isWild = (base===WILD);
  if(count>=3){
    const key = (isWild ? count+'x_wild' : count+'x_same');
    const mult = MULT[key] || (MULT[(count-1)+'x_same']||1);
    win = bet * mult;
    winCells = Array.from({length:count},(_, i)=>({c:i,r:1}));
  }

  // Use RTP to bias results
  const rand = Math.random();
  if(win===0 && rand < (RTP - 0.5)*0.4){
    win = bet * (MULT['3x_same']||5);
    winCells = [{c:0,r:1},{c:1,r:1},{c:2,r:1}];
    for(let c=0;c<3;c++) grid[c][1] = SYMBOLS[1];
  }

  return {win, cells:winCells};
}

function showWin(amount, cells){
  const overlay = document.getElementById('winOverlay');
  overlay.textContent = '🎉 +R$ '+fmtMoney(amount);
  overlay.style.display='flex';
  if(cells && cells.length) highlightWinCells(cells);
  document.getElementById('displayWin').textContent='R$ '+fmtMoney(amount);
  setTimeout(()=>{
    overlay.style.display='none';
    drawStatic();
  },2000);
}

function addHistory(bet, win){
  const panel = document.getElementById('historyPanel');
  const item = document.createElement('div');
  item.className = 'history-item '+(win>0?'win':'loss');
  const now = new Date();
  const t = now.getHours().toString().padStart(2,'0')+':'+now.getMinutes().toString().padStart(2,'0');
  item.textContent = `[${t}] Aposta: R$${fmtMoney(bet)} → ${win>0?'+R$'+fmtMoney(win):'Sem ganho'}`;
  panel.insertBefore(item, panel.children[1]||null);
  while(panel.children.length>11) panel.removeChild(panel.lastChild);
}

function fmtMoney(v){
  return v.toFixed(2).replace('.',',').replace(/\B(?=(\d{3})+(?!\d))/g,'.');
}

function changeBet(dir){
  if(spinning) return;
  betIdx = Math.max(0,Math.min(BET_STEPS.length-1, betIdx+dir));
  bet = BET_STEPS[betIdx];
  document.getElementById('betDisplay').textContent='R$ '+fmtMoney(bet);
}

function doSpin(){
  if(spinning) return;
  if(!LOGGED_IN && balance<=0){
    alert('Saldo insuficiente no modo demo. Faça login para jogar com saldo real.');
    return;
  }
  if(LOGGED_IN && balance<bet){
    alert('Saldo insuficiente!');return;
  }
  spinning=true;
  document.getElementById('spinBtn').disabled=true;
  document.getElementById('winOverlay').style.display='none';

  if(!LOGGED_IN){ balance -= bet; }

  // Generate target grid
  const target = Array.from({length:COLS},()=>Array.from({length:ROWS},()=>randSym()));
  startSpin(target);
}

window.addEventListener('resize', resize);
resize();
</script>
</body>
</html>
