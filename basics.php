<?php
require __DIR__ . '/includes/init.php';
$page = 'basics';
$title = 'Portuguese basics';

$verbs = [
  'ser'    => ['to be (permanent)', ['sou', 'és', 'é', 'somos', 'são']],
  'estar'  => ['to be (temporary)', ['estou', 'estás', 'está', 'estamos', 'estão']],
  'ter'    => ['to have', ['tenho', 'tens', 'tem', 'temos', 'têm']],
  'ir'     => ['to go', ['vou', 'vais', 'vai', 'vamos', 'vão']],
  'falar'  => ['to speak (-ar verbs)', ['falo', 'falas', 'fala', 'falamos', 'falam']],
  'comer'  => ['to eat (-er verbs)', ['como', 'comes', 'come', 'comemos', 'comem']],
  'partir' => ['to leave / break (-ir verbs)', ['parto', 'partes', 'parte', 'partimos', 'partem']],
  'fazer'  => ['to do / make', ['faço', 'fazes', 'faz', 'fazemos', 'fazem']],
  'querer' => ['to want', ['quero', 'queres', 'quer', 'queremos', 'querem']],
  'poder'  => ['can', ['posso', 'podes', 'pode', 'podemos', 'podem']],
];
$pronouns = [['eu', 'I'], ['tu', 'you (informal)'], ['ele / ela / você', 'he / she / you (formal)'], ['nós', 'we'], ['eles / elas / vocês', 'they / you all']];

$genderWords = [
  ['carro', 'o', 'car'], ['casa', 'a', 'house'], ['livro', 'o', 'book'], ['mesa', 'a', 'table'],
  ['pão', 'o', 'bread'], ['maçã', 'a', 'apple'], ['dia', 'o', 'day', 'Exception! Ends in -a but masculine.'],
  ['chave', 'a', 'key', 'Words ending in -e can be either — learn them with the article.'],
  ['problema', 'o', 'problem', 'Exception! -ma words from Greek are masculine.'],
  ['estação', 'a', 'station', 'Words ending in -ção are feminine.'], ['telemóvel', 'o', 'mobile phone'],
  ['água', 'a', 'water'], ['cão', 'o', 'dog'], ['mão', 'a', 'hand', 'Exception! Ends in -ão but feminine.'],
  ['menino', 'o', 'boy'], ['menina', 'a', 'girl'], ['comboio', 'o', 'train'], ['praia', 'a', 'beach'],
];

$pageData = ['gender' => $genderWords];
$scripts = ['pages.js'];
include __DIR__ . '/includes/header.php';
?>

<section class="pagehead">
  <div class="wrap">
    <p class="eyebrow">🧭 O básico</p>
    <h1>Portuguese basics</h1>
    <p>The small rules that make everything else easier. Tap 🔊 on anything to hear it.</p>
  </div>
</section>

<nav class="wrap jump">
  <a href="#sounds">🔊 Sounds</a><a href="#gender">⚖️ o / a</a><a href="#serestar">🧍 ser vs estar</a><a href="#verbs">⚡ Verbs</a><a href="#formal">🎩 tu / você</a><a href="#small">🧩 Small words</a><a href="#questions">❓ Questions</a>
</nav>

<section class="wrap basics">

  <article class="lesson reveal" id="sounds">
    <header><span class="lesson__no">1</span><h2>Sounds of Portugal</h2></header>
    <p>European Portuguese “swallows” many vowels and has some special sounds. Listen and repeat:</p>
    <div class="sound-grid">
      <?php foreach ([
        ['ão', 'like “ung” through the nose', 'pão, não, estação'],
        ['ç', 'always “s”', 'açúcar, começar'],
        ['lh', 'like “lli” in million', 'filho, trabalho'],
        ['nh', 'like “ny” in canyon', 'vinho, amanhã'],
        ['ch', 'like “sh”', 'chave, chá'],
        ['j / ge', 'like “s” in pleasure', 'janela, gente'],
        ['s (end)', 'like “sh” in Portugal', 'mais, dois, três'],
        ['rr / r-', 'strong, from the throat', 'carro, Rui'],
        ['ã', 'nasal “a”', 'maçã, irmã'],
        ['e (end)', 'almost silent', 'leite, sete, noite'],
      ] as [$s, $d, $ex]): ?>
        <button class="sound" data-say="<?= e($ex) ?>">
          <span class="sound__s"><?= e($s) ?></span>
          <span class="sound__d"><?= e($d) ?></span>
          <span class="sound__ex">🔊 <?= e($ex) ?></span>
        </button>
      <?php endforeach; ?>
    </div>
  </article>

  <article class="lesson reveal" id="gender">
    <header><span class="lesson__no">2</span><h2>Every noun is masculine or feminine</h2></header>
    <div class="article-table">
      <div class="at at--m"><b>Masculine</b><span><em>o</em> carro · the car</span><span><em>os</em> carros · the cars</span><span><em>um</em> carro · a car</span><span><em>uns</em> carros · some cars</span></div>
      <div class="at at--f"><b>Feminine</b><span><em>a</em> casa · the house</span><span><em>as</em> casas · the houses</span><span><em>uma</em> casa · a house</span><span><em>umas</em> casas · some houses</span></div>
    </div>
    <p>👉 Most words ending in <b>-o</b> are masculine, most ending in <b>-a</b> are feminine. Words ending in <b>-ção</b> and <b>-dade</b> are feminine. Always learn a new word <em>with</em> its article!</p>

    <div class="gender-game">
      <div class="gender-game__head">
        <h3>🎮 Mini game: o or a?</h3>
        <span class="gender-game__score">Score: <b data-g-score>0</b> / <b data-g-total>0</b></span>
      </div>
      <div class="gender-game__card">
        <span class="gender-game__article" data-g-art>?</span>
        <span class="gender-game__word" data-g-word>carro</span>
        <span class="gender-game__en" data-g-en>car</span>
      </div>
      <div class="gender-game__btns">
        <button class="btn btn--blue btn--lg" data-g="o">o (masc.)</button>
        <button class="btn btn--pink btn--lg" data-g="a">a (fem.)</button>
      </div>
      <p class="gender-game__msg" aria-live="polite"></p>
    </div>
  </article>

  <article class="lesson reveal" id="serestar">
    <header><span class="lesson__no">3</span><h2>Two verbs for “to be”: ser &amp; estar</h2></header>
    <div class="versus">
      <div class="versus__side versus__side--ser">
        <h3>SER <small>permanent — who / what you are</small></h3>
        <ul>
          <li><button data-say="Eu sou do Nepal.">🔊</button> Eu <b>sou</b> do Nepal. <small>Origin</small></li>
          <li><button data-say="Ela é enfermeira.">🔊</button> Ela <b>é</b> enfermeira. <small>Job</small></li>
          <li><button data-say="O carro é vermelho.">🔊</button> O carro <b>é</b> vermelho. <small>Description</small></li>
          <li><button data-say="Hoje é segunda-feira.">🔊</button> Hoje <b>é</b> segunda-feira. <small>Day / time</small></li>
        </ul>
      </div>
      <div class="versus__vs">vs</div>
      <div class="versus__side versus__side--estar">
        <h3>ESTAR <small>temporary — how / where you are now</small></h3>
        <ul>
          <li><button data-say="Eu estou em casa.">🔊</button> Eu <b>estou</b> em casa. <small>Location</small></li>
          <li><button data-say="Estou cansado.">🔊</button> <b>Estou</b> cansado. <small>Feeling</small></li>
          <li><button data-say="Está a chover.">🔊</button> <b>Está</b> a chover. <small>Weather</small></li>
          <li><button data-say="Estou a trabalhar.">🔊</button> <b>Estou a</b> trabalhar. <small>Doing now</small></li>
        </ul>
      </div>
    </div>
    <p class="callout">⭐ Special: age uses <b>ter</b> (to have): <button class="inline-say" data-say="Tenho trinta anos.">🔊 Tenho trinta anos.</button> = I am 30 years old.</p>
  </article>

  <article class="lesson reveal" id="verbs">
    <header><span class="lesson__no">4</span><h2>Verb conjugation (present tense)</h2></header>
    <p>Pick a verb. Notice the pattern: <b>-o</b> for eu, <b>-s</b> for tu, <b>-mos</b> for nós, <b>-m</b> for eles.</p>
    <div class="verb-tabs" role="tablist">
      <?php $first = true; foreach ($verbs as $v => $_): ?>
        <button class="verb-tab<?= $first ? ' is-active' : '' ?>" data-verb="<?= $v ?>"><?= $v ?></button>
      <?php $first = false; endforeach; ?>
    </div>
    <?php $first = true; foreach ($verbs as $v => [$meaning, $forms]): ?>
      <div class="verb-table" data-verb-table="<?= $v ?>" <?= $first ? '' : 'hidden' ?>>
        <div class="verb-table__head"><b><?= $v ?></b> — <?= e($meaning) ?> <button class="btn btn--small btn--ghost" data-say="<?= e(implode(', ', array_map(fn($p, $f) => explode(' / ', $p[0])[0] . ' ' . $f, $pronouns, $forms))) ?>">🔊 Hear all</button></div>
        <?php foreach ($pronouns as $i => [$p, $en]): ?>
          <button class="verb-row" data-say="<?= e(explode(' / ', $p)[0] . ' ' . $forms[$i]) ?>">
            <span class="verb-row__p"><?= e($p) ?> <small><?= e($en) ?></small></span>
            <span class="verb-row__f"><?= e($forms[$i]) ?></span>
            <span class="verb-row__s">🔊</span>
          </button>
        <?php endforeach; ?>
      </div>
    <?php $first = false; endforeach; ?>
  </article>

  <article class="lesson reveal" id="formal">
    <header><span class="lesson__no">5</span><h2>Tu, você or o senhor?</h2></header>
    <div class="cards3">
      <div class="mini"><span>😊</span><b>tu</b><p>Friends, family, colleagues, children. Verb ends in <b>-s</b>: <em>Tu falas português?</em></p></div>
      <div class="mini"><span>🙂</span><b>você</b><p>Neutral, but in Portugal it can sound a bit cold. Many people just drop the pronoun: <em>Fala português?</em></p></div>
      <div class="mini"><span>🎩</span><b>o senhor / a senhora</b><p>Polite: older people, offices, doctors. <em>O senhor fala inglês?</em></p></div>
    </div>
    <p class="callout">💡 Safe trick: use the verb without a pronoun — <button class="inline-say" data-say="Pode ajudar-me?">🔊 Pode ajudar-me?</button> (Can you help me?) — polite in every situation.</p>
  </article>

  <article class="lesson reveal" id="small">
    <header><span class="lesson__no">6</span><h2>Small words that join together</h2></header>
    <p>Prepositions merge with the articles. You will see these everywhere:</p>
    <div class="combine">
      <?php foreach ([
        ['de', 'o', 'do', 'Sou do Nepal.'], ['de', 'a', 'da', 'A casa da Ana.'],
        ['em', 'o', 'no', 'Estou no trabalho.'], ['em', 'a', 'na', 'O copo está na mesa.'],
        ['a', 'o', 'ao', 'Vou ao café.'], ['a', 'a', 'à', 'Vamos à praia.'],
        ['por', 'o', 'pelo', 'Obrigado pelo café.'], ['em', 'um', 'num', 'Trabalho num restaurante.'],
      ] as [$x, $y, $z, $ex]): ?>
        <button class="combine__item" data-say="<?= e($ex) ?>">
          <span class="combine__eq"><i><?= $x ?></i> + <i><?= $y ?></i> = <b><?= $z ?></b></span>
          <span class="combine__ex">🔊 <?= e($ex) ?></span>
        </button>
      <?php endforeach; ?>
    </div>
  </article>

  <article class="lesson reveal" id="questions">
    <header><span class="lesson__no">7</span><h2>Question words</h2></header>
    <div class="qwords">
      <?php foreach ([
        ['Quem?', 'Who?', 'Quem é?'], ['O quê? / O que…?', 'What?', 'O que é isto?'], ['Onde?', 'Where?', 'Onde moras?'],
        ['Quando?', 'When?', 'Quando é a folga?'], ['Porquê? / Porque…?', 'Why?', 'Porque estás triste?'],
        ['Como?', 'How?', 'Como estás?'], ['Quanto?', 'How much?', 'Quanto custa?'], ['Qual?', 'Which?', 'Qual é o teu nome?'],
        ['A que horas?', 'At what time?', 'A que horas começa?'],
      ] as [$q, $en, $ex]): ?>
        <button class="qword" data-say="<?= e($ex) ?>"><b><?= e($q) ?></b><span><?= e($en) ?></span><small>🔊 <?= e($ex) ?></small></button>
      <?php endforeach; ?>
    </div>
  </article>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
