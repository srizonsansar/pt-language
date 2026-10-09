<?php
/** @var string $page  @var string $title */
$nav = [
  'home'          => ['./', 'Home', '🏠'],
  'learn'         => ['learn', 'Learn words', '📚'],
  'conversations' => ['conversations', 'Conversations', '💬'],
  'quiz'          => ['quiz', 'Quiz', '🎯'],
  'basics'        => ['basics', 'Basics', '🧭'],
  'phrasebook'    => ['phrasebook', 'Phrasebook', '📒'],
];
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <base href="<?= e(BASE) ?>">
  <script>document.documentElement.classList.add('js');</script>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? SITE_NAME) ?> · <?= SITE_NAME ?></title>
  <meta name="description" content="<?= e(SITE_TAGLINE) ?> — vocabulary, conversations, quizzes and audio, free.">
  <meta name="theme-color" content="#1d4fa3">
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'%3E%3Crect x='12' y='12' width='26' height='19' rx='7' fill='%23f6c343'/%3E%3Cpath d='M27 29.5 33 37v-8.5z' fill='%23f6c343'/%3E%3Crect x='2' y='3' width='27' height='20' rx='7' fill='%231d4fa3' stroke='%23fff' stroke-width='2'/%3E%3Cpath d='M7.5 21 5 29l9-7z' fill='%231d4fa3'/%3E%3Cg fill='%23fff'%3E%3Ccircle cx='9.5' cy='13' r='2'/%3E%3Ccircle cx='15.5' cy='13' r='2'/%3E%3Ccircle cx='21.5' cy='13' r='2'/%3E%3C/g%3E%3C/svg%3E">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Nunito:wght@400;600;700;800&family=Noto+Sans+Devanagari:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>
<body class="page-<?= e($page) ?>">
  <a class="skip" href="<?= e(strtok($_SERVER['REQUEST_URI'] ?? '', '#')) ?>#main">Skip to content</a>
  <header class="topbar">
    <div class="topbar__inner">
      <a class="brand" href="./" aria-label="<?= SITE_NAME ?> home">
        <svg class="brand__logo" viewBox="0 0 40 40" aria-hidden="true">
          <rect class="b2" x="12" y="12" width="26" height="19" rx="7"/><path class="b2" d="M27 29.5 33 37v-8.5z"/>
          <rect class="b1" x="2" y="3" width="27" height="20" rx="7"/><path class="b1" d="M7.5 21 5 29l9-7z"/>
          <circle cx="9.5" cy="13" r="2"/><circle cx="15.5" cy="13" r="2"/><circle cx="21.5" cy="13" r="2"/>
        </svg>
        <span class="brand__name">Fala<b>Comigo</b></span>
      </a>
      <nav class="nav" id="nav" aria-label="Main">
        <?php foreach ($nav as $key => [$href, $label, $icon]): ?>
          <a href="<?= url($href) ?>" class="nav__link<?= $page === $key ? ' is-active' : '' ?>"><span aria-hidden="true"><?= $icon ?></span> <?= $label ?></a>
        <?php endforeach; ?>
      </nav>
      <div class="stats" title="Your progress (saved in this browser)">
        <span class="stats__item stats__streak" title="Day streak">🔥 <b data-streak>0</b></span>
        <span class="stats__item stats__xp" title="Experience points">⭐ <b data-xp>0</b></span>
      </div>
      <button class="burger" aria-label="Open menu" aria-expanded="false" aria-controls="nav"><span></span><span></span><span></span></button>
    </div>
  </header>
  <main id="main">
