<?php
require __DIR__ . '/includes/init.php';
$page = 'phrasebook';
$title = 'Survival phrasebook';
$book = $PHRASES['phrasebook'];
$icons = ['Essentials' => '🆘', 'About me' => '🙋', 'Questions' => '❓', 'Feelings' => '💛', 'Emergencies' => '🚑'];
$scripts = ['pages.js'];
include __DIR__ . '/includes/header.php';
?>

<section class="pagehead">
  <div class="wrap">
    <p class="eyebrow">📒 Guia de conversação</p>
    <h1>Survival phrasebook</h1>
    <p>The sentences you need most. Search, listen and practise them out loud.</p>
  </div>
</section>

<section class="wrap">
  <div class="pb-tools">
    <div class="search">
      <span aria-hidden="true">🔎</span>
      <input type="search" id="pbSearch" placeholder="Search in Portuguese or English…" aria-label="Search phrases">
    </div>
    <div class="chips" role="group" aria-label="Category">
      <button class="chipf is-active" data-cat="all">All</button>
      <?php foreach ($book as $cat => $_): ?>
        <button class="chipf" data-cat="<?= e($cat) ?>"><?= $icons[$cat] ?? '•' ?> <?= e($cat) ?></button>
      <?php endforeach; ?>
    </div>
  </div>

  <?php foreach ($book as $cat => $items): ?>
    <div class="pb-group" data-group="<?= e($cat) ?>">
      <h2><?= $icons[$cat] ?? '' ?> <?= e($cat) ?></h2>
      <div class="pb-grid">
        <?php foreach ($items as [$pt, $en]): ?>
          <div class="phrase reveal" data-text="<?= e(mb_strtolower($pt . ' ' . $en)) ?>">
            <button class="phrase__say" data-say="<?= e($pt) ?>" aria-label="Listen">🔊</button>
            <div class="phrase__text"><b><?= e($pt) ?></b><span><?= e($en) ?></span></div>
            <button class="phrase__slow" data-say="<?= e($pt) ?>" data-slow aria-label="Listen slowly">🐢</button>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
  <p class="pb-empty" hidden>Nada encontrado — nothing found. 🤷</p>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
