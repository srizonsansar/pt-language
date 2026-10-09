<?php
require __DIR__ . '/includes/init.php';
$page = 'conversations';
$id = $_GET['id'] ?? '';
$d = $DIALOGUES[$id] ?? null;
$ids = array_keys($DIALOGUES);
$levels = [1 => 'Easy', 2 => 'Medium', 3 => 'Bit harder'];

if ($d) {
  $title = $d['title'];
  $i = array_search($id, $ids);
  $next = $ids[($i + 1) % count($ids)];
  // Distractor pool: learner lines from other dialogues
  $pool = [];
  foreach ($DIALOGUES as $oid => $od) {
    if ($oid === $id) continue;
    foreach ($od['lines'] as $l) if ($l[0] === $od['you']) $pool[] = [$l[1], $l[2]];
  }
  $pageData = ['id' => $id, 'dialogue' => $d, 'pool' => $pool];
} else {
  $title = 'Everyday conversations';
  $pageData = ['ids' => $ids];
}
$scripts = ['chat.js'];
include __DIR__ . '/includes/header.php';
?>

<?php if (!$d): ?>
  <section class="pagehead">
    <div class="wrap">
      <p class="eyebrow">💬 Conversas do dia a dia</p>
      <h1>Everyday conversations</h1>
      <p>Real situations from life in Portugal. Watch the chat, listen to each line, then <b>role-play</b> — you choose what to say.</p>
    </div>
  </section>
  <section class="wrap convo-grid convo-grid--all">
    <?php foreach ($DIALOGUES as $k => $c): ?>
      <a class="convo-card reveal" href="<?= url('conversations') ?>?id=<?= $k ?>" style="--c: <?= $c['color'] ?>">
        <span class="convo-card__level lvl-<?= $c['level'] ?>"><?= $levels[$c['level']] ?></span>
        <span class="convo-card__icon"><?= $c['icon'] ?></span>
        <span class="convo-card__pt"><?= e($c['pt']) ?></span>
        <span class="convo-card__en"><?= e($c['title']) ?></span>
        <span class="convo-card__scene"><?= e($c['scene']) ?></span>
        <span class="convo-card__bubble">“<?= e($c['lines'][0][1]) ?>”</span>
        <span class="convo-card__done" data-done="<?= $k ?>" hidden>✓ Done</span>
      </a>
    <?php endforeach; ?>
  </section>

<?php else: ?>
  <section class="pagehead pagehead--topic" style="--c: <?= $d['color'] ?>">
    <div class="wrap pagehead__row">
      <div>
        <a href="<?= url('conversations') ?>" class="back">← All conversations</a>
        <h1><span class="pagehead__icon"><?= $d['icon'] ?></span> <?= e($d['pt']) ?> <small><?= e($d['title']) ?></small></h1>
        <p>🎬 <?= e($d['scene']) ?></p>
      </div>
      <div class="toggles">
        <button class="switch" data-toggle="slow" aria-pressed="false"><span class="switch__dot"></span> 🐢 Slow audio</button>
      </div>
    </div>
  </section>

  <section class="wrap chat-layout">
    <div class="phone">
      <div class="phone__top">
        <div class="phone__people">
          <?php foreach ($d['speakers'] as $s): ?>
            <span class="phone__person"><span class="av"><?= $s['av'] ?></span><?= e($s['name']) ?></span>
          <?php endforeach; ?>
        </div>
        <div class="seg" role="tablist" aria-label="Mode">
          <button class="seg__btn is-active" data-mode="watch">👀 Watch</button>
          <button class="seg__btn" data-mode="role">🎭 Role-play</button>
        </div>
      </div>
      <div class="chat" aria-live="polite"></div>
      <div class="chat__choices" hidden></div>
      <div class="phone__bottom">
        <button class="btn btn--primary" data-chat="next">▶ Next line</button>
        <button class="btn btn--ghost" data-chat="all">⏩ Show all</button>
        <button class="btn btn--ghost" data-chat="play">🔊 Play all</button>
        <label class="check"><input type="checkbox" data-chat-en checked> English</label>
      </div>
    </div>

    <aside class="chat-side">
      <div class="side-card">
        <h3>💡 Tips</h3>
        <ul>
          <?php foreach ($d['tips'] as $t): ?><li><?= e($t) ?></li><?php endforeach; ?>
        </ul>
      </div>
      <div class="side-card">
        <h3>🎭 How to practise</h3>
        <ol>
          <li><b>Watch</b> the conversation and listen to each line.</li>
          <li>Tap a bubble to hide/show the English.</li>
          <li>Switch to <b>Role-play</b>: you are <b><?= e($d['speakers'][$d['you']]['en']) ?></b> — choose the right reply.</li>
          <li>Say your lines out loud! 🗣️</li>
        </ol>
      </div>
      <a class="side-next" href="<?= url('conversations') ?>?id=<?= $next ?>" style="--c: <?= $DIALOGUES[$next]['color'] ?>">
        <small>Next conversation</small>
        <b><?= $DIALOGUES[$next]['icon'] ?> <?= e($DIALOGUES[$next]['pt']) ?></b>
        <span><?= e($DIALOGUES[$next]['title']) ?> →</span>
      </a>
    </aside>
  </section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
