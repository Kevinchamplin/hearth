<?php
// Hearth Analytics — god-admin dashboard. Password-gated. Read-only over the events DB.
require __DIR__ . '/db.php';
session_start();
$cfg = hearth_config();

// ---- auth ----
if (isset($_GET['logout'])) { $_SESSION = []; session_destroy(); header('Location: index.php'); exit; }
if (empty($_SESSION['hearth_admin'])) {
    $err = '';
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['pw'])) {
        if (!empty($cfg['admin_hash']) && password_verify($_POST['pw'], $cfg['admin_hash'])) {
            session_regenerate_id(true);
            $_SESSION['hearth_admin'] = true;
            header('Location: index.php'); exit;
        }
        $err = 'That password didn\'t match.';
        usleep(600000);
    }
    ?><!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex"><title>Hearth Analytics</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <style>
      body{margin:0;height:100vh;display:grid;place-items:center;background:radial-gradient(125% 90% at 50% 20%,#221A10,#14120E 60%,#100D09);font-family:Inter,system-ui,sans-serif;color:#F5F1E8}
      .box{width:320px;text-align:center}
      .seed{width:46px;height:46px;border-radius:50%;margin:0 auto 18px;background:radial-gradient(circle at 37% 31%,#FFEEC9,#FFCC84 30%,#EE9A45 70%);box-shadow:0 0 40px 8px rgba(238,154,69,.5)}
      h1{font-family:Fraunces,serif;font-weight:600;font-size:24px;margin:0 0 4px}
      p.s{color:rgba(245,241,232,.6);font-size:13px;margin:0 0 22px}
      input{width:100%;padding:13px 15px;border-radius:12px;border:1px solid rgba(245,241,232,.15);background:rgba(255,255,255,.05);color:#F5F1E8;font-size:15px;box-sizing:border-box;outline:none}
      input:focus{border-color:#E4C27C}
      button{width:100%;margin-top:12px;padding:13px;border:0;border-radius:12px;cursor:pointer;font-weight:600;font-size:15px;background:radial-gradient(circle at 35% 30%,#FFD89A,#EE9A45 78%);color:#3A2206}
      .err{color:#E9967A;font-size:13px;margin-top:12px}
    </style></head><body><form class="box" method="post">
      <div class="seed"></div><h1>Hearth Analytics</h1><p class="s">Private dashboard · sign in</p>
      <input type="password" name="pw" placeholder="Password" autofocus>
      <button>Enter</button>
      <?php if ($err) echo '<div class="err">'.htmlspecialchars($err).'</div>'; ?>
    </form></body></html><?php
    exit;
}

// ---- query helpers ----
$db = hearth_db();
$days = isset($_GET['days']) ? (int)$_GET['days'] : 30;          // 0 = all time
$bots = isset($_GET['bots']) && $_GET['bots'] === '1';
$where = 'WHERE 1=1'; $params = [];
if (!$bots) $where .= ' AND is_bot=0';
if ($days > 0) { $where .= ' AND ts >= ?'; $params[] = time() - $days * 86400; }
function q($db,$sql,$p=[]){ $s=$db->prepare($sql); $s->execute($p); return $s->fetchAll(); }
function q1($db,$sql,$p=[]){ $s=$db->prepare($sql); $s->execute($p); return $s->fetchColumn(); }

// ---- CSV export ----
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv'); header('Content-Disposition: attachment; filename="hearth-events.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['ts','day','event','page','os','browser','device','device_mobile','bot','tz','locale','webgpu','tier','load_ms','value','referrer','session','visitor']);
    foreach (q($db, "SELECT * FROM events $where ORDER BY ts DESC LIMIT 50000", $params) as $r) {
        fputcsv($out, [date('c',$r['ts']),$r['day'],$r['event'],$r['page'],$r['os'],$r['browser'],$r['device'],$r['is_mobile'],$r['is_bot'],$r['tz'],$r['locale'],$r['webgpu'],$r['tier'],$r['load_ms'],$r['value'],$r['referrer'],$r['session'],$r['visitor']]);
    }
    exit;
}

// ---- KPIs ----
$kpi = [
  'events'   => (int)q1($db,"SELECT COUNT(*) FROM events $where",$params),
  'visitors' => (int)q1($db,"SELECT COUNT(DISTINCT visitor) FROM events $where",$params),
  'sessions' => (int)q1($db,"SELECT COUNT(DISTINCT session) FROM events $where",$params),
  'pageviews'=> (int)q1($db,"SELECT COUNT(*) FROM events $where AND event='pageview'",$params),
  'lit'      => (int)q1($db,"SELECT COUNT(*) FROM events $where AND event='hearth_lit'",$params),
  'ready'    => (int)q1($db,"SELECT COUNT(*) FROM events $where AND event='model_ready'",$params),
  'messages' => (int)q1($db,"SELECT COUNT(*) FROM events $where AND event='message_sent'",$params),
  'avg_load' => (float)q1($db,"SELECT AVG(load_ms) FROM events $where AND event='model_ready' AND load_ms>0",$params),
];
$wg_total = (int)q1($db,"SELECT COUNT(*) FROM events $where AND event='pageview' AND webgpu IS NOT NULL",$params);
$wg_yes   = (int)q1($db,"SELECT COUNT(*) FROM events $where AND event='pageview' AND webgpu=1",$params);
$kpi['webgpu_rate'] = $wg_total ? round($wg_yes*100/$wg_total) : 0;

// ---- funnel (by distinct session) ----
function sess($db,$where,$params,$extra){ return (int)q1($db,"SELECT COUNT(DISTINCT session) FROM events $where $extra",$params); }
$funnel = [
  ['App opened',  sess($db,$where,$params,"AND page='app'")],
  ['Hearth lit',  sess($db,$where,$params,"AND event='hearth_lit'")],
  ['Model ready', sess($db,$where,$params,"AND event='model_ready'")],
  ['Sent a message', sess($db,$where,$params,"AND event='message_sent'")],
];

// ---- breakdowns ----
function breakdown($db,$where,$params,$col,$limit=8){
    $rows = q($db,"SELECT COALESCE(NULLIF($col,''),'(none)') k, COUNT(*) c FROM events $where AND event='pageview' GROUP BY k ORDER BY c DESC LIMIT $limit",$params);
    return $rows;
}
$by_os      = breakdown($db,$where,$params,'os');
$by_browser = breakdown($db,$where,$params,'browser');
$by_device  = breakdown($db,$where,$params,'device');
$by_page    = breakdown($db,$where,$params,'page');
$by_tz      = breakdown($db,$where,$params,'tz',10);
$by_locale  = breakdown($db,$where,$params,'locale',10);
$by_ref = q($db,"SELECT CASE WHEN referrer='' OR referrer IS NULL THEN '(direct)' ELSE referrer END k, COUNT(*) c FROM events $where AND event='pageview' GROUP BY k ORDER BY c DESC LIMIT 10",$params);

// ---- time series (per day) ----
$tsdays = $days > 0 ? $days : 30;
$series = q($db,"SELECT day, COUNT(*) ev, COUNT(DISTINCT visitor) vis FROM events $where GROUP BY day ORDER BY day DESC LIMIT $tsdays",$params);
$series = array_reverse($series);

// ---- recent ----
$recent = q($db,"SELECT ts,event,page,os,browser,device,tz,value,is_bot FROM events $where ORDER BY ts DESC LIMIT 60",$params);

$total_all = (int)q1($db,"SELECT COUNT(*) FROM events",[]);
function pct($a,$b){ return $b ? round($a*100/$b) : 0; }
function bars($rows){ $m=0; foreach($rows as $r)$m=max($m,$r['c']); return $m?:1; }
$h = fn($v)=>htmlspecialchars((string)$v, ENT_QUOTES);
?><!DOCTYPE html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex,nofollow"><title>Hearth Analytics</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
  :root{--bg:#14120E;--card:#1c1812;--line:rgba(245,241,232,.10);--ink:#F5F1E8;--soft:rgba(245,241,232,.58);--brass:#E4C27C;--ember:#EE9A45}
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:Inter,system-ui,sans-serif;background:var(--bg);color:var(--ink);-webkit-font-smoothing:antialiased}
  .wrap{max-width:1180px;margin:0 auto;padding:26px 24px 70px}
  header.top{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:14px;margin-bottom:8px}
  .brand{display:flex;align-items:center;gap:11px;font-family:Fraunces,serif;font-weight:600;font-size:22px}
  .seed{width:15px;height:15px;border-radius:50%;background:radial-gradient(circle at 37% 31%,#FFEEC9,#FFCC84 30%,#EE9A45 70%);box-shadow:0 0 10px 1px rgba(238,154,69,.6)}
  .sub{color:var(--soft);font-size:13px;margin-bottom:22px}
  .controls{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
  .controls a,.controls .btn{font-size:13px;color:var(--soft);text-decoration:none;padding:7px 13px;border:1px solid var(--line);border-radius:999px}
  .controls a.on{color:#3A2206;background:var(--brass);border-color:var(--brass);font-weight:600}
  .controls a:hover{color:var(--ink)}
  .grid{display:grid;gap:16px}
  .kpis{grid-template-columns:repeat(4,1fr);margin-bottom:20px}
  .card{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:18px 20px}
  .kpi .n{font-family:Fraunces,serif;font-weight:600;font-size:30px;line-height:1}
  .kpi .l{color:var(--soft);font-size:12.5px;margin-top:7px;font-family:"JetBrains Mono",monospace;letter-spacing:.04em;text-transform:uppercase}
  .kpi .n small{font-size:15px;color:var(--soft)}
  .two{grid-template-columns:1.6fr 1fr}
  .three{grid-template-columns:repeat(3,1fr)}
  h2{font-family:Fraunces,serif;font-weight:600;font-size:16px;margin-bottom:16px;display:flex;align-items:center;gap:8px}
  h2 .dot{width:9px;height:9px;border-radius:50%;background:var(--ember)}
  .bar-row{display:flex;align-items:center;gap:10px;margin-bottom:10px;font-size:13.5px}
  .bar-row .k{width:120px;color:var(--soft);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;flex:none}
  .bar-row .track{flex:1;height:9px;background:rgba(245,241,232,.07);border-radius:99px;overflow:hidden}
  .bar-row .fill{height:100%;background:linear-gradient(90deg,#FFCC84,#EE9A45);border-radius:99px}
  .bar-row .c{width:42px;text-align:right;color:var(--ink);font-variant-numeric:tabular-nums;flex:none}
  .funnel-step{display:flex;align-items:center;gap:14px;margin-bottom:12px}
  .funnel-step .fk{width:130px;font-size:13.5px;color:var(--soft);flex:none}
  .funnel-step .ftrack{flex:1;height:30px;background:rgba(245,241,232,.06);border-radius:8px;overflow:hidden;position:relative}
  .funnel-step .ffill{height:100%;background:linear-gradient(90deg,rgba(238,154,69,.85),rgba(228,194,124,.85));display:flex;align-items:center;padding-left:12px;font-size:13px;font-weight:600;color:#2B1A06;min-width:34px}
  .funnel-step .fp{width:48px;text-align:right;font-size:12.5px;color:var(--soft);flex:none}
  table{width:100%;border-collapse:collapse;font-size:13px}
  th,td{text-align:left;padding:8px 10px;border-bottom:1px solid var(--line);white-space:nowrap}
  th{color:var(--soft);font-weight:500;font-family:"JetBrains Mono",monospace;font-size:11px;letter-spacing:.05em;text-transform:uppercase}
  td.ev{color:var(--brass)}
  .tag{font-size:10.5px;color:var(--soft);border:1px solid var(--line);border-radius:6px;padding:1px 6px}
  .scrollx{overflow-x:auto}
  .mut{color:var(--soft);font-size:12px}
  .secttitle{margin:34px 0 14px;font-family:Fraunces,serif;font-weight:600;font-size:19px}
  @media(max-width:880px){.kpis{grid-template-columns:repeat(2,1fr)}.two,.three{grid-template-columns:1fr}}
</style></head><body><div class="wrap">

<header class="top">
  <div class="brand"><span class="seed"></span>Hearth Analytics</div>
  <div class="controls">
    <?php foreach ([1=>'24h',7=>'7d',30=>'30d',90=>'90d',0=>'All'] as $d=>$lbl): ?>
      <a class="<?= $days===$d?'on':'' ?>" href="?days=<?= $d ?><?= $bots?'&bots=1':'' ?>"><?= $lbl ?></a>
    <?php endforeach; ?>
    <a href="?days=<?= $days ?><?= $bots?'':'&bots=1' ?>"><?= $bots?'Hiding bots: off':'Bots: hidden' ?></a>
    <a href="?days=<?= $days ?><?= $bots?'&bots=1':'' ?>&export=csv">Export CSV</a>
    <a href="?logout=1">Sign out</a>
  </div>
</header>
<p class="sub">Anonymous, content-free usage telemetry · <?= $days?($days.'-day window'):'all time' ?> · <?= number_format($total_all) ?> events stored total</p>

<div class="grid kpis">
  <div class="card kpi"><div class="n"><?= number_format($kpi['visitors']) ?></div><div class="l">Unique visitors</div></div>
  <div class="card kpi"><div class="n"><?= number_format($kpi['pageviews']) ?></div><div class="l">Pageviews</div></div>
  <div class="card kpi"><div class="n"><?= number_format($kpi['sessions']) ?></div><div class="l">Sessions</div></div>
  <div class="card kpi"><div class="n"><?= $kpi['webgpu_rate'] ?><small>%</small></div><div class="l">WebGPU support</div></div>
  <div class="card kpi"><div class="n"><?= number_format($kpi['lit']) ?></div><div class="l">Hearth lit</div></div>
  <div class="card kpi"><div class="n"><?= number_format($kpi['ready']) ?></div><div class="l">Models ready</div></div>
  <div class="card kpi"><div class="n"><?= number_format($kpi['messages']) ?></div><div class="l">Messages sent</div></div>
  <div class="card kpi"><div class="n"><?= $kpi['avg_load']?number_format($kpi['avg_load']/1000,1):'—' ?><small>s</small></div><div class="l">Avg load time</div></div>
</div>

<div class="grid two" style="margin-bottom:20px">
  <div class="card"><h2><span class="dot"></span>Activity over time</h2><canvas id="ts" height="110"></canvas></div>
  <div class="card"><h2><span class="dot"></span>Activation funnel</h2>
    <?php $top=$funnel[0][1]?:1; foreach($funnel as $i=>$f): ?>
      <div class="funnel-step">
        <div class="fk"><?= $h($f[0]) ?></div>
        <div class="ftrack"><div class="ffill" style="width:<?= max(6,pct($f[1],$top)) ?>%"><?= number_format($f[1]) ?></div></div>
        <div class="fp"><?= $i? pct($f[1],$funnel[$i-1][1]).'%' : '100%' ?></div>
      </div>
    <?php endforeach; ?>
    <p class="mut" style="margin-top:6px">% = step-to-step conversion (by session)</p>
  </div>
</div>

<div class="grid three">
  <?php
  function barcard($title,$rows,$h){ $max=bars($rows); echo '<div class="card"><h2><span class="dot"></span>'.$title.'</h2>';
    if(!$rows) echo '<p class="mut">No data yet.</p>';
    foreach($rows as $r){ echo '<div class="bar-row"><div class="k" title="'.$h($r['k']).'">'.$h($r['k']).'</div><div class="track"><div class="fill" style="width:'.max(3,round($r['c']*100/$max)).'%"></div></div><div class="c">'.number_format($r['c']).'</div></div>'; }
    echo '</div>'; }
  barcard('Operating system',$by_os,$h);
  barcard('Browser',$by_browser,$h);
  barcard('Device',$by_device,$h);
  ?>
</div>

<div class="secttitle">Reports</div>
<div class="grid three">
  <?php barcard('Top referrers',$by_ref,$h); barcard('Timezones',$by_tz,$h); barcard('Languages',$by_locale,$h); ?>
</div>

<div class="card" style="margin-top:20px">
  <h2><span class="dot"></span>Recent events <span class="mut" style="font-weight:400">— live feed</span></h2>
  <div class="scrollx"><table>
    <tr><th>When</th><th>Event</th><th>Page</th><th>OS</th><th>Browser</th><th>Device</th><th>Timezone</th><th>Detail</th></tr>
    <?php foreach($recent as $r): ?>
      <tr>
        <td class="mut"><?= date('M j, H:i',$r['ts']) ?></td>
        <td class="ev"><?= $h($r['event']) ?><?= $r['is_bot']?' <span class="tag">bot</span>':'' ?></td>
        <td><?= $h($r['page']) ?></td>
        <td><?= $h($r['os']) ?></td>
        <td><?= $h($r['browser']) ?></td>
        <td><?= $h($r['device']) ?></td>
        <td class="mut"><?= $h($r['tz']) ?></td>
        <td class="mut"><?= $h($r['value']) ?></td>
      </tr>
    <?php endforeach; ?>
    <?php if(!$recent): ?><tr><td colspan="8" class="mut">No events yet — they'll appear here as people visit.</td></tr><?php endif; ?>
  </table></div>
</div>

<p class="mut" style="margin-top:26px">🔒 Hearth never collects conversation content — the model runs on each visitor's device. This dashboard holds anonymous usage signals only; IPs are one-way hashed (never stored raw).</p>
</div>

<script>
const S = <?= json_encode(array_map(fn($r)=>['d'=>substr($r['day'],5),'ev'=>(int)$r['ev'],'vis'=>(int)$r['vis']], $series)) ?>;
new Chart(document.getElementById('ts'), {
  type:'line',
  data:{ labels:S.map(x=>x.d), datasets:[
    {label:'Events', data:S.map(x=>x.ev), borderColor:'#EE9A45', backgroundColor:'rgba(238,154,69,.12)', fill:true, tension:.35, pointRadius:0, borderWidth:2},
    {label:'Visitors', data:S.map(x=>x.vis), borderColor:'#E4C27C', backgroundColor:'transparent', tension:.35, pointRadius:0, borderWidth:2, borderDash:[4,4]}
  ]},
  options:{plugins:{legend:{labels:{color:'rgba(245,241,232,.7)',boxWidth:12,font:{size:11}}}},
    scales:{x:{ticks:{color:'rgba(245,241,232,.45)',maxTicksLimit:8,font:{size:10}},grid:{display:false}},
            y:{ticks:{color:'rgba(245,241,232,.45)',font:{size:10},precision:0},grid:{color:'rgba(245,241,232,.06)'},beginAtZero:true}}}
});
</script>
</body></html>
