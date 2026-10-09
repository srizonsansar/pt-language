<?php
require __DIR__ . '/includes/init.php';
$page = 'quiz';
$title = 'Quiz games';

$words = [];
foreach ($VOCAB as $k => $t) foreach ($t['words'] as $w) $words[] = $w + ['topic' => $k];
$topics = [];
foreach ($VOCAB as $k => $t) $topics[$k] = ['title' => $t['title'], 'icon' => $t['icon']];
$sel = isset($_GET['topic'], $VOCAB[$_GET['topic']]) ? $_GET['topic'] : 'all';

$pageData = [
  'words' => $words,
  'topics' => $topics,
  'sentences' => $PHRASES['sentences'],
  'grammar' => $PHRASES['grammar'],
  'selected' => $sel,
];
$modes = [
  'mix'     => ['🌟', 'Daily mix', 'A bit of everything — the best daily practice.', '#1d4fa3'],
  'choose'  => ['🔤', 'Choose the meaning', 'See a word, pick the right translation.', '#2e9e6b'],
  'listen'  => ['🎧', 'Listen & pick', 'Hear the word, choose how it is written.', '#7a4fd1'],
  'type'    => ['⌨️', 'Type it', 'Write the Portuguese word yourself.', '#e0703f'],
  'match'   => ['🧩', 'Match pairs', 'Connect Portuguese and English as fast as you can.', '#d94a7a'],
  'build'   => ['🧱', 'Build the sentence', 'Put the word tiles in the right order.', '#c2871b'],
  'grammar' => ['📐', 'Grammar check', 'um/uma, o/a, ser/estar — pick the correct sentence.', '#3b8a9e'],
];
$scripts = ['quiz.js'];
include __DIR__ . '/includes/header.php';
?>

<section class="pagehead">
  <div class="wrap">
    <p class="eyebrow">🎯 Jogos e quiz</p>
    <h1>Quiz games</h1>
    <p>Choose a game. Each round has 10 questions — earn XP and build combos 🔥</p>
  </div>
</section>

<section class="wrap quiz-hub" data-screen="hub">
  <div class="quiz-hub__filter">
    <label for="topicSel">Words from:</label>
    <div class="selectwrap">
      <select id="topicSel">
        <option value="all">🌍 All topics</option>
        <?php foreach ($topics as $k => $t): ?>
          <option value="<?= $k ?>" <?= $sel === $k ? 'selected' : '' ?>><?= $t['icon'] ?> <?= e($t['title']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <span class="quiz-hub__best">🏅 Best score: <b data-best>0</b>% · Played: <b data-played>0</b></span>
  </div>
  <div class="mode-grid">
    <?php foreach ($modes as $k => [$ic, $name, $desc, $c]): ?>
      <button class="mode reveal<?= $k === 'mix' ? ' mode--feature' : '' ?>" data-start="<?= $k ?>" style="--c: <?= $c ?>">
        <span class="mode__icon"><?= $ic ?></span>
        <span class="mode__name"><?= $name ?></span>
        <span class="mode__desc"><?= $desc ?></span>
        <span class="mode__go">Play ▶</span>
      </button>
    <?php endforeach; ?>
  </div>
</section>

<section class="wrap game" data-screen="game" hidden>
  <div class="game__top">
    <button class="game__quit" data-quit aria-label="Quit">✕</button>
    <div class="game__bar"><i></i></div>
    <div class="game__combo" data-combo hidden>🔥 <b>0</b></div>
    <div class="game__score">⭐ <b data-score>0</b></div>
  </div>
  <div class="game__stage" aria-live="polite"></div>
  <div class="game__feedback" hidden>
    <div class="game__feedback-inner">
      <div class="game__feedback-msg"></div>
      <button class="btn btn--lg" data-continue>Continuar ↵</button>
    </div>
  </div>
</section>

<section class="wrap results" data-screen="results" hidden>
  <div class="results__card">
    <div class="results__emoji">🏆</div>
    <h2 data-r-title>Muito bem!</h2>
    <div class="results__stats">
      <div><b data-r-correct>0</b><small>correct</small></div>
      <div><b data-r-pct>0%</b><small>accuracy</small></div>
      <div><b data-r-xp>0</b><small>XP earned</small></div>
      <div><b data-r-combo>0</b><small>best combo</small></div>
    </div>
    <div class="results__review" hidden>
      <h3>Review your mistakes</h3>
      <ul></ul>
    </div>
    <div class="results__actions">
      <button class="btn btn--ghost" data-hub>← All games</button>
      <button class="btn btn--primary" data-again>🔁 Play again</button>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
