<?php
$title = $title ?? 'Hearth';
$desc  = $desc  ?? 'A warm, private AI that runs on your own computer.';
$canon = 'https://hearth.kevinchamplin.com' . strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
$ogimg = 'https://hearth.kevinchamplin.com/assets/og.png';
$font  = 'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500&display=swap';
$hh = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES);
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= $hh($title) ?></title>
<meta name="description" content="<?= $hh($desc) ?>" />
<link rel="canonical" href="<?= $hh($canon) ?>" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?= $hh($canon) ?>" />
<meta property="og:title" content="<?= $hh($title) ?>" />
<meta property="og:description" content="<?= $hh($desc) ?>" />
<meta property="og:image" content="<?= $ogimg ?>" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="<?= $hh($title) ?>" />
<meta name="twitter:description" content="<?= $hh($desc) ?>" />
<meta name="twitter:image" content="<?= $ogimg ?>" />
<link rel="icon" href="/favicon.svg" type="image/svg+xml" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="preload" as="style" href="<?= $hh($font) ?>" />
<link rel="stylesheet" href="<?= $hh($font) ?>" media="print" onload="this.media='all'" />
<noscript><link rel="stylesheet" href="<?= $hh($font) ?>" /></noscript>
<link rel="stylesheet" href="/hearth.css" />
<script src="/analytics/a.js" defer></script>
</head>
<body>
