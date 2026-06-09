<?php
$title = $title ?? 'Hearth';
$desc  = $desc  ?? 'A warm, private AI that runs on your own computer.';
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= htmlspecialchars($title) ?></title>
<meta name="description" content="<?= htmlspecialchars($desc) ?>" />
<meta property="og:title" content="<?= htmlspecialchars($title) ?>" />
<meta property="og:description" content="<?= htmlspecialchars($desc) ?>" />
<meta property="og:image" content="https://hearth.kevinchamplin.com/assets/hearth-home-dark.png" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/hearth.css" />
<script src="/analytics/a.js" defer></script>
</head>
<body>
