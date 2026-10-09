<?php
require __DIR__ . '/includes/init.php';
$page = 'learn';
$key = $_GET['topic'] ?? '';
$topic = $VOCAB[$key] ?? null;
$keys = array_keys($VOCAB);

if ($topic) {
  $title = $topic['title'] . ' — vocabulary';
  $idx = array_search($key, $keys);
  $next = $keys[($idx + 1) % count($keys)];
  $pageData = ['topic' => $key, 'words' => $topic['words'], 'next' => $next, 'nextTitle' => $VOCAB[$next]['title']];
  $scripts = ['learn.js'];
} else {
  $title = 'Learn words';
  $topicsForJs = [];
  foreach ($VOCAB as $k => $t) $topicsForJs[$k] = array_column($t['words'], 'pt');
  $pageData = ['topics' => $topicsForJs];
  $scripts = ['learn.js'];
}
include __DIR__ . '/includes/header.php';
?>

<?php if (!$topic): ?>
  <section class="pagehead">
    <div class="wrap">
      <p class="eyebrow">📚 Vocabulário</p>
      <h1>Learn words by topic</h1>
      <p>Pick a topic. Flip the cards, listen, say it out loud and mark the words you know.</p>
    </div>
  </section>
  <section class="wrap topic-grid">
    <?php foreach ($VOCAB as $k => $t): ?>
      <a class="topic-card reveal" href="learn?topic=<?= $k ?>" style="--c: <?= $t['color'] ?>" data-topic="<?= $k ?>">
        <span class="topic-card__icon"><?= $t['icon'] ?></span>
        <span class="topic-card__pt"><?= e($t['pt']) ?></span>
        <span class="topic-card__title"><?= e($t['title']) ?></span>
        <span class="topic-card__intro"><?= e($t['intro']) ?></span>
        <span class="topic-card__foot">
          <span class="minibar"><i style="width:0%"></i></span>
          <span data-count>0/<?= count($t['words']) ?></span>
        </span>
      </a>
    <?php endforeach; ?>
  </section>

<?php else: ?>
  <section class="pagehead pagehead--topic" style="--c: <?= $topic['color'] ?>">
    <div class="wrap pagehead__row">
      <div>
        <a href="learn" class="back">← All topics</a>
        <h1><span class="pagehead__icon"><?= $topic['icon'] ?></span> <?= e($topic['title']) ?> <small><?= e($topic['pt']) ?></small></h1>
        <p><?= e($topic['intro']) ?></p>
      </div>
      <div class="toggles">
        <button class="switch" data-toggle="nepali" aria-pressed="false"><span class="switch__dot"></span> 🇳🇵 Nepali</button>
        <button class="switch" data-toggle="slow" aria-pressed="false"><span class="switch__dot"></span> 🐢 Slow audio</button>
      </div>
    </div>
  </section>

  <section class="wrap" style="--c: <?= $topic['color'] ?>">
    <div class="tabs" role="tablist">
      <button class="tabs__btn is-active" role="tab" data-tab="cards">🃏 Flashcards</button>
      <button class="tabs__btn" role="tab" data-tab="list">📋 Word list</button>
      <a class="tabs__btn tabs__btn--cta" href="quiz?topic=<?= $key ?>">🎯 Quiz this topic</a>
    </div>

    <div class="panel" data-panel="cards">
      <div class="deck">
        <div class="deck__progress"><i></i></div>
        <div class="deck__counter"><span data-pos>1</span> / <?= count($topic['words']) ?></div>

        <div class="flash" tabindex="0" role="button" aria-label="Flip card">
          <div class="flash__inner">
            <div class="flash__face flash__front">
              <div class="flash__emoji" data-f="e"></div>
              <div class="flash__pt" data-f="pt"></div>
              <div class="flash__tap">tap to flip ↻</div>
            </div>
            <div class="flash__face flash__back">
              <div class="flash__en" data-f="en"></div>
              <div class="flash__ne lang-ne" data-f="ne"></div>
              <div class="flash__note" data-f="n"></div>
            </div>
          </div>
          <div class="flash__badge" hidden>✓ Learned</div>
        </div>

        <div class="deck__tools">
          <button class="btn btn--round btn--lg-round" data-act="say" aria-label="Listen">🔊</button>
          <button class="btn btn--round" data-act="slow" aria-label="Listen slowly">🐢</button>
          <button class="btn btn--round" data-act="mic" aria-label="Say it — pronunciation check" hidden>🎤</button>
        </div>
        <div class="deck__feedback" aria-live="polite"></div>

        <div class="deck__nav">
          <button class="btn btn--ghost" data-act="prev">← Anterior</button>
          <button class="btn btn--soft" data-act="again">🔁 Still learning</button>
          <button class="btn btn--primary" data-act="know">✓ I know it</button>
        </div>
        <p class="deck__keys">Keyboard: <kbd>Space</kbd> flip · <kbd>←</kbd> <kbd>→</kbd> move · <kbd>K</kbd> know · <kbd>S</kbd> speak</p>
      </div>

      <div class="deck-done" hidden>
        <div class="deck-done__emoji">🎉</div>
        <h2>Parabéns! Topic finished.</h2>
        <p>You know <b data-known>0</b> of <?= count($topic['words']) ?> words in this topic.</p>
        <div class="deck-done__actions">
          <button class="btn btn--ghost" data-act="restart">🔁 Practise again</button>
          <a class="btn btn--sun" href="quiz?topic=<?= $key ?>">🎯 Take the quiz</a>
          <a class="btn btn--primary" data-next-topic href="learn?topic=<?= $pageData['next'] ?>">Next: <?= e($pageData['nextTitle']) ?> →</a>
        </div>
      </div>
    </div>

    <div class="panel" data-panel="list" hidden>
      <div class="wordlist">
        <?php foreach ($topic['words'] as $w): ?>
          <div class="word reveal" data-word="<?= e($w['pt']) ?>">
            <button class="word__say" data-say="<?= e($w['pt']) ?>" aria-label="Listen to <?= e($w['pt']) ?>">🔊</button>
            <span class="word__emoji"><?= $w['e'] ?></span>
            <span class="word__text">
              <b><?= e($w['pt']) ?></b>
              <span><?= e($w['en']) ?></span>
              <span class="lang-ne"><?= e($w['ne']) ?></span>
              <?php if (!empty($w['n'])): ?><small>💡 <?= e($w['n']) ?></small><?php endif; ?>
            </span>
            <button class="word__check" aria-label="Mark as learned" title="Mark as learned">✓</button>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
