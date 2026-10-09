<?php
require __DIR__ . '/includes/init.php';
$page = 'home';
$title = 'Learn beginner Portuguese';

// Word list for "word of the day" (picked in JS by date)
$allWords = [];
foreach ($VOCAB as $key => $t) foreach ($t['words'] as $w) $allWords[] = $w + ['topic' => $key];
$topicsForJs = [];
foreach ($VOCAB as $key => $t) $topicsForJs[$key] = array_column($t['words'], 'pt');

$pageData = ['words' => $allWords, 'topics' => $topicsForJs, 'dialogues' => array_keys($DIALOGUES)];
$scripts = ['home.js'];
include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="wrap hero__grid">
    <div class="hero__text">
      <p class="eyebrow">🇳🇵 → 🇵🇹 Beginner · Português de Portugal</p>
      <h1 class="hero__title">Olá! <span class="hl">Vamos aprender</span> português.</h1>
      <p class="hero__ne lang-ne">नमस्ते! सँगै पोर्चुगिज भाषा सिकौँ।</p>
      <p class="hero__lead">Short lessons for real life in Portugal — the café, the bus, work, the doctor. Listen to every word, play quick quizzes and practise conversations.</p>
      <div class="hero__cta">
        <a href="learn" class="btn btn--primary btn--lg">Começar · Start 🚀</a>
        <a href="quiz" class="btn btn--ghost btn--lg">Play a quiz 🎯</a>
      </div>
      <ul class="hero__facts">
        <li><b><?= vocab_count($VOCAB) ?></b> words</li>
        <li><b><?= count($DIALOGUES) ?></b> conversations</li>
        <li><b>6</b> quiz games</li>
      </ul>
    </div>

    <div class="wotd reveal" aria-live="polite">
      <div class="wotd__label">✨ Palavra do dia · Word of the day</div>
      <div class="wotd__card" tabindex="0" role="button" aria-label="Flip card">
        <div class="wotd__face wotd__front">
          <div class="wotd__emoji" data-wotd="e">👋</div>
          <div class="wotd__pt" data-wotd="pt">Olá</div>
          <div class="wotd__hint">Tap to see the meaning</div>
        </div>
        <div class="wotd__face wotd__back">
          <div class="wotd__en" data-wotd="en">Hello</div>
          <div class="wotd__ne lang-ne" data-wotd="ne">नमस्ते</div>
          <div class="wotd__note" data-wotd="n"></div>
        </div>
      </div>
      <div class="wotd__actions">
        <button class="btn btn--round" data-wotd-say aria-label="Listen">🔊</button>
        <button class="btn btn--round" data-wotd-slow aria-label="Listen slowly">🐢</button>
        <a class="btn btn--small" data-wotd-link href="learn">Open topic →</a>
      </div>
    </div>
  </div>
</section>

<section class="wrap dash">
  <div class="dash__card dash__level reveal">
    <div class="ring" style="--p:0" data-level-ring><span><small>Nível</small><b data-level-num>1</b></span></div>
    <div>
      <h3>Your level</h3>
      <p><b data-xp>0</b> XP · <span data-next-level>150</span> XP to next level</p>
    </div>
  </div>
  <div class="dash__card dash__goal reveal">
    <div class="ring ring--sun" style="--p:0" data-goal-ring><span><b data-goal-num>0</b><small>/ 50</small></span></div>
    <div>
      <h3>Daily goal</h3>
      <p data-goal-text>Earn 50 XP today to keep your streak 🔥</p>
    </div>
  </div>
  <div class="dash__card reveal">
    <div class="dash__big">🔥 <b data-streak>0</b></div>
    <div><h3>Day streak</h3><p>Come back every day — even 5 minutes helps.</p></div>
  </div>
  <div class="dash__card reveal">
    <div class="dash__big">📚 <b data-learned>0</b></div>
    <div><h3>Words learned</h3><p>of <?= vocab_count($VOCAB) ?> words in all topics.</p></div>
  </div>
</section>

<section class="wrap section">
  <div class="section__head">
    <h2>Your learning path <span class="lang-ne">· सिक्ने बाटो</span></h2>
    <a href="learn" class="more">All topics →</a>
  </div>
  <div class="path">
    <?php $i = 0; foreach ($VOCAB as $key => $t): $i++; ?>
      <a class="path__node reveal" href="learn?topic=<?= $key ?>" style="--c: <?= $t['color'] ?>" data-topic="<?= $key ?>">
        <span class="path__icon"><?= $t['icon'] ?></span>
        <span class="path__label"><b><?= e($t['title']) ?></b><small><?= e($t['pt']) ?></small></span>
        <span class="path__bar"><i style="width:0%"></i></span>
        <span class="path__count" data-count>0/<?= count($t['words']) ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="wrap section">
  <div class="section__head">
    <h2>Everyday conversations <span class="lang-ne">· दैनिक कुराकानी</span></h2>
    <a href="conversations" class="more">All conversations →</a>
  </div>
  <div class="convo-grid">
    <?php foreach (array_slice($DIALOGUES, 0, 4, true) as $id => $d): ?>
      <a class="convo-card reveal" href="conversations?id=<?= $id ?>" style="--c: <?= $d['color'] ?>">
        <span class="convo-card__icon"><?= $d['icon'] ?></span>
        <span class="convo-card__pt"><?= e($d['pt']) ?></span>
        <span class="convo-card__en"><?= e($d['title']) ?></span>
        <span class="convo-card__bubble">“<?= e($d['lines'][0][1]) ?>”</span>
        <span class="convo-card__done" data-done="<?= $id ?>" hidden>✓ Done</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="wrap section">
  <div class="quiz-banner reveal">
    <div class="quiz-banner__text">
      <h2>Ready for a challenge? 🎯</h2>
      <p>Multiple choice, listening, typing, matching pairs, sentence building and grammar. Earn XP and climb levels!</p>
      <a class="btn btn--sun btn--lg" href="quiz">Play now</a>
    </div>
    <div class="quiz-banner__chips" aria-hidden="true">
      <span>🔤 Choose</span><span>🎧 Listen</span><span>⌨️ Type</span><span>🧩 Match</span><span>🧱 Build</span><span>📐 Grammar</span>
    </div>
  </div>
</section>

<section class="wrap section tips">
  <div class="tip reveal"><span>🇵🇹</span><div><h3>Portugal ≠ Brazil</h3><p>This site teaches the Portuguese spoken in Portugal: <em>autocarro</em> (bus), <em>telemóvel</em> (phone), <em>pequeno-almoço</em> (breakfast), and “estou <b>a</b> trabalhar”.</p></div></div>
  <div class="tip reveal"><span>🔊</span><div><h3>Listen to everything</h3><p>Tap any 🔊 button to hear the word. Use 🐢 for slow mode. Repeat out loud — speaking is the fastest way to learn.</p></div></div>
  <div class="tip reveal"><span>🇳🇵</span><div><h3>Nepali helper</h3><p>Vocabulary also shows the meaning in Nepali. You can turn it on or off on the Learn page.</p></div></div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
