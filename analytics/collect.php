<?php
// Hearth Analytics — ingest endpoint. Privacy-respecting: NO conversation content is ever
// sent or stored (the model runs locally; we never see it). IP is one-way hashed with a
// secret salt for unique-visitor counting only — never stored raw.
require __DIR__ . '/db.php';
require __DIR__ . '/ua.php';

header('Content-Type: text/plain');
header('Cache-Control: no-store');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { http_response_code(204); exit; }

$raw = file_get_contents('php://input');
if (strlen($raw) > 4000) { http_response_code(204); exit; }
$d = json_decode($raw, true);
if (!is_array($d) || empty($d['event'])) { http_response_code(204); exit; }

$cfg = hearth_config();
$ua  = $_SERVER['HTTP_USER_AGENT'] ?? '';
$p   = hearth_parse_ua($ua);

$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? ($_SERVER['REMOTE_ADDR'] ?? '');
$ip = trim(explode(',', $ip)[0]);
$ip_hash = $ip ? substr(hash('sha256', $ip . '|' . ($cfg['ip_salt'] ?? '')), 0, 32) : '';

$now = time();
$s = function($v, $len) { return $v === null ? null : substr((string)$v, 0, $len); };

try {
    $st = hearth_db()->prepare("INSERT INTO events
        (ts,day,event,page,session,visitor,os,browser,device,is_mobile,is_bot,
         screen,viewport,dpr,locale,tz,referrer,webgpu,tier,load_ms,value,ip_hash,ua)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $st->execute([
        $now, gmdate('Y-m-d', $now),
        $s($d['event'] ?? '', 40),
        $s($d['page'] ?? '', 40),
        $s($d['session'] ?? '', 64),
        $s($d['visitor'] ?? '', 64),
        $p['os'], $p['browser'], $p['device'],
        $p['is_mobile'] ? 1 : 0, $p['is_bot'] ? 1 : 0,
        $s($d['screen'] ?? '', 16),
        $s($d['viewport'] ?? '', 16),
        isset($d['dpr']) ? (float)$d['dpr'] : null,
        $s($d['locale'] ?? '', 16),
        $s($d['tz'] ?? '', 48),
        $s($d['referrer'] ?? '', 255),
        isset($d['webgpu']) ? ((int)!!$d['webgpu']) : null,
        ($d['tier'] ?? '') !== '' ? $s($d['tier'], 24) : null,
        isset($d['load_ms']) ? (int)$d['load_ms'] : null,
        ($d['value'] ?? '') !== '' ? $s($d['value'], 120) : null,
        $ip_hash,
        $s($ua, 255),
    ]);
} catch (\Throwable $e) { /* never break the user's page over analytics */ }

http_response_code(204);
