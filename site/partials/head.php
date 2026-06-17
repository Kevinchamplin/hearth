<?php
$title = $title ?? 'Hearth';
$desc  = $desc  ?? 'A warm, private AI that runs on your own computer.';
$canon = $canon ?? ('https://hearth.kevinchamplin.com' . strtok($_SERVER['REQUEST_URI'] ?? '/', '?'));
$ogimg = $ogimg ?? 'https://hearth.kevinchamplin.com/assets/og.png?v=2';
$ogtype = $ogtype ?? 'website';
$ogalt = $ogalt ?? 'Hearth — a free, private AI that runs on your own computer.';
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
<meta property="og:type" content="<?= $hh($ogtype) ?>" />
<meta property="og:site_name" content="Hearth" />
<meta property="og:url" content="<?= $hh($canon) ?>" />
<meta property="og:title" content="<?= $hh($title) ?>" />
<meta property="og:description" content="<?= $hh($desc) ?>" />
<meta property="og:image" content="<?= $hh($ogimg) ?>" />
<meta property="og:image:alt" content="<?= $hh($ogalt) ?>" />
<meta property="og:image:width" content="1200" />
<meta property="og:image:height" content="630" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="<?= $hh($title) ?>" />
<meta name="twitter:description" content="<?= $hh($desc) ?>" />
<meta name="twitter:image" content="<?= $hh($ogimg) ?>" />
<meta name="twitter:image:alt" content="<?= $hh($ogalt) ?>" />
<meta name="theme-color" content="#14120E" />
<link rel="icon" href="/favicon.svg" type="image/svg+xml" />
<link rel="apple-touch-icon" href="/assets/apple-touch-icon.png" />
<link rel="alternate" type="application/rss+xml" title="Hearth — Blog" href="/blog/feed.xml" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link rel="preload" as="style" href="<?= $hh($font) ?>" />
<link rel="stylesheet" href="<?= $hh($font) ?>" media="print" onload="this.media='all'" />
<noscript><link rel="stylesheet" href="<?= $hh($font) ?>" /></noscript>
<link rel="stylesheet" href="/hearth.css" />
<script src="/analytics/a.js" defer></script>
</head>
<body>
