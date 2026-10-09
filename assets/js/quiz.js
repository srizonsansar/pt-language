/* ==========================================================================
   Quiz engine — 7 modes: mix, choose, listen, type, match, build, grammar
   ========================================================================== */
(function () {
  'use strict';
  const { Progress, speak, sfx, toast, confetti, shuffle, sample, esc, norm, stripArticle, similar } = window.PT;
  const D = window.PT_DATA;
  const $ = s => document.querySelector(s);
  const screens = { hub: $('[data-screen="hub"]'), game: $('[data-screen="game"]'), results: $('[data-screen="results"]') };
  const stage = $('.game__stage');
  const fbBox = $('.game__feedback');
  const fbMsg = $('.game__feedback-msg');
  const contBtn = $('[data-continue]');

  const variants = pt => pt.split(' / ').map(s => s.trim());
  const first = pt => variants(pt)[0];

  let G = null; // game state

  /* ---------- Screens ---------- */
  function showScreen(name) {
    Object.entries(screens).forEach(([k, el]) => el.hidden = k !== name);
    document.querySelector('.pagehead').hidden = name !== 'hub';
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
  function renderHub() {
    $('[data-best]').textContent = Progress.data.quiz.best;
    $('[data-played]').textContent = Progress.data.quiz.played;
  }

  /* ---------- Word pools ---------- */
  function pool() {
    const t = $('#topicSel').value;
    return t === 'all' ? D.words : D.words.filter(w => w.topic === t);
  }
  // distractors: prefer same topic, unique by key
  function distractors(word, n, key) {
    const used = new Set([norm(word[key])]);
    const same = shuffle(D.words.filter(w => w.topic === word.topic));
    const rest = shuffle(D.words);
    const out = [];
    for (const w of [...same, ...rest]) {
      const k = norm(w[key]);
      if (used.has(k)) continue;
      used.add(k); out.push(w);
      if (out.length === n) break;
    }
    return out;
  }

  /* ---------- Question generators ---------- */
  const gen = {
    choose(w) {
      const dir = Math.random() < 0.6 ? 'pt-en' : 'en-pt';
      const opts = shuffle([w, ...distractors(w, 3, dir === 'pt-en' ? 'en' : 'pt')]);
      return { type: 'choose', w, dir, opts };
    },
    listen(w) {
      return { type: 'listen', w, opts: shuffle([w, ...distractors(w, 3, 'pt')]) };
    },
    type(w) { return { type: 'type', w }; },
    match() {
      const seen = new Set(); const pairs = [];
      for (const w of shuffle(pool())) {
        if (seen.has(w.en) || seen.has(w.pt)) continue;
        seen.add(w.en); seen.add(w.pt); pairs.push(w);
        if (pairs.length === 5) break;
      }
      return { type: 'match', pairs };
    },
    build() {
      const s = G.sentences.pop() || sample(D.sentences, 1)[0];
      const tokens = s[0].split(' ');
      const others = D.sentences.flatMap(x => x[0].split(' ')).filter(t => !tokens.map(norm).includes(norm(t)));
      const extra = sample([...new Set(others)], 2);
      return { type: 'build', s, tokens, bank: shuffle([...tokens, ...extra]) };
    },
    grammar() {
      const g = G.grammar.pop() || sample(D.grammar, 1)[0];
      return { type: 'grammar', g, opts: shuffle([g.a, ...g.w]) };
    }
  };

  function buildQuestions(mode) {
    const words = shuffle(pool());
    let wi = 0;
    const nextWord = () => words[wi++ % words.length];
    G.sentences = shuffle(D.sentences);
    G.grammar = shuffle(D.grammar);
    let plan;
    if (mode === 'mix') plan = ['choose', 'listen', 'grammar', 'type', 'build', 'choose', 'match', 'listen', 'build', 'grammar'];
    else if (mode === 'match') plan = Array(4).fill('match');
    else plan = Array(10).fill(mode);
    return plan.map(t => (t === 'match' || t === 'build' || t === 'grammar') ? gen[t]() : gen[t](nextWord()));
  }

  /* ---------- Game flow ---------- */
  function start(mode) {
    G = { mode, i: 0, score: 0, correct: 0, combo: 0, bestCombo: 0, mistakes: [], locked: false };
    G.qs = buildQuestions(mode);
    showScreen('game');
    updateTop();
    render();
  }

  function updateTop() {
    $('.game__bar i').style.width = (G.i / G.qs.length * 100) + '%';
    $('[data-score]').textContent = G.score;
    const c = $('[data-combo]');
    c.hidden = G.combo < 2;
    c.querySelector('b').textContent = G.combo;
  }

  function render() {
    const q = G.qs[G.i];
    G.locked = false;
    fbBox.hidden = true;
    stage.className = 'game__stage q-in';
    void stage.offsetWidth;
    stage.innerHTML = '';
    R[q.type](q);
  }

  function answer(ok, info = {}) {
    if (G.locked) return;
    G.locked = true;
    const q = G.qs[G.i];
    if (ok) {
      G.correct++; G.combo++; G.bestCombo = Math.max(G.bestCombo, G.combo);
      const pts = 10 + (G.combo >= 3 ? 5 : 0);
      G.score += pts;
      sfx.good();
    } else {
      G.combo = 0; sfx.bad();
      if (info.review) G.mistakes.push(info.review);
      stage.classList.add('shake');
    }
    updateTop();
    const praise = ['Muito bem!', 'Excelente!', 'Perfeito!', 'Boa!', 'Fantástico!', 'Isso mesmo!'];
    const head = ok ? `✅ ${praise[(Math.random() * praise.length) | 0]}${G.combo >= 3 ? ` <span class="combo-tag">🔥 ${G.combo} combo +5</span>` : ''}` : '❌ Not quite…';
    const ans = info.answerPt ? `<div class="fb-ans"><button class="fb-say" data-say="${esc(info.answerPt)}">🔊</button> <b>${esc(info.answerPt)}</b>${info.answerEn ? ` — ${esc(info.answerEn)}` : ''}</div>` : '';
    const note = info.note ? `<div class="fb-note">💡 ${esc(info.note)}</div>` : '';
    fbMsg.innerHTML = `<div class="fb-head">${head}</div>${ans}${note}`;
    fbBox.className = 'game__feedback ' + (ok ? 'is-good' : 'is-bad');
    fbBox.hidden = false;
    contBtn.className = 'btn btn--lg ' + (ok ? 'btn--good' : 'btn--bad');
    setTimeout(() => contBtn.focus(), 50);
    if (info.answerPt && q.type !== 'listen') speak(info.answerPt);
    else if (info.answerPt && !ok) speak(info.answerPt);
  }

  function next() {
    G.i++;
    if (G.i >= G.qs.length) return finish();
    updateTop();
    render();
  }

  function finish() {
    const total = G.qs.length;
    const pct = Math.round(G.correct / total * 100);
    const xp = Math.max(5, Math.round(G.score / 2));
    Progress.quizResult(G.correct, total);
    showScreen('results');
    $('[data-r-correct]').textContent = `${G.correct}/${total}`;
    $('[data-r-pct]').textContent = pct + '%';
    $('[data-r-xp]').textContent = '+' + xp;
    $('[data-r-combo]').textContent = G.bestCombo;
    const [emoji, title] = pct === 100 ? ['🏆', 'Perfeito! 100%'] : pct >= 80 ? ['🌟', 'Excelente!'] : pct >= 50 ? ['👏', 'Muito bem!'] : ['💪', 'Keep going — vais conseguir!'];
    $('.results__emoji').textContent = emoji;
    $('[data-r-title]').textContent = title;
    const rv = $('.results__review');
    const uniq = [...new Map(G.mistakes.map(m => [m.pt, m])).values()];
    rv.hidden = !uniq.length;
    rv.querySelector('ul').innerHTML = uniq.map(m =>
      `<li><button class="fb-say" data-say="${esc(m.pt)}">🔊</button><b>${esc(m.pt)}</b><span>${esc(m.en)}</span></li>`).join('');
    sfx.win();
    if (pct >= 80) confetti();
    setTimeout(() => Progress.addXP(xp, $('[data-r-xp]')), 300);
  }

  /* ---------- Renderers ---------- */
  const h = (html) => { stage.insertAdjacentHTML('beforeend', html); };
  const keyHint = '<p class="q-keys">Tip: press <kbd>1</kbd>–<kbd>4</kbd> to answer</p>';

  function optionButtons(opts, label, isRight, onPick) {
    const wrap = document.createElement('div');
    wrap.className = 'opts';
    opts.forEach((o, k) => {
      const b = document.createElement('button');
      b.className = 'opt';
      b.innerHTML = `<span class="opt__k">${k + 1}</span><span class="opt__t">${esc(label(o))}</span>`;
      b.addEventListener('click', () => {
        if (G.locked) return;
        const ok = isRight(o);
        b.classList.add(ok ? 'is-right' : 'is-wrong');
        if (!ok) wrap.querySelectorAll('.opt').forEach((x, j) => { if (isRight(opts[j])) x.classList.add('is-right'); });
        wrap.querySelectorAll('.opt').forEach(x => x.disabled = true);
        onPick(ok, o);
      });
      wrap.appendChild(b);
    });
    stage.appendChild(wrap);
  }

  const R = {
    choose(q) {
      const w = q.w;
      if (q.dir === 'pt-en') {
        h(`<p class="q-label">What does this mean?</p>
           <div class="q-prompt"><button class="q-say" data-say="${esc(first(w.pt))}">🔊</button><span class="q-word">${esc(w.pt)}</span></div>`);
        optionButtons(q.opts, o => o.en, o => o.en === w.en, ok => answer(ok, { answerPt: w.pt, answerEn: w.en, note: w.n, review: w }));
        speak(first(w.pt));
      } else {
        h(`<p class="q-label">How do you say this in Portuguese?</p>
           <div class="q-prompt"><span class="q-emoji">${w.e}</span><span class="q-word q-word--en">${esc(w.en)}</span></div>`);
        optionButtons(q.opts, o => o.pt, o => o.pt === w.pt, ok => answer(ok, { answerPt: w.pt, answerEn: w.en, note: w.n, review: w }));
      }
      h(keyHint);
    },

    listen(q) {
      const w = q.w;
      h(`<p class="q-label">Listen and choose what you hear 🎧</p>
         <div class="q-listen">
           <button class="q-big-say" data-say="${esc(first(w.pt))}" aria-label="Play">🔊</button>
           <button class="q-slow-say" data-say="${esc(first(w.pt))}" data-slow aria-label="Play slowly">🐢</button>
         </div>`);
      optionButtons(q.opts, o => o.pt, o => o.pt === w.pt, ok => answer(ok, { answerPt: w.pt, answerEn: w.en, review: w }));
      h(keyHint);
      setTimeout(() => speak(first(w.pt)), 250);
    },

    type(q) {
      const w = q.w;
      h(`<p class="q-label">Write it in Portuguese ✍️</p>
         <div class="q-prompt"><span class="q-emoji">${w.e}</span><span class="q-word q-word--en">${esc(w.en)}</span></div>
         <form class="typebox" autocomplete="off">
           <input type="text" class="typebox__input" placeholder="Escreve aqui…" autocapitalize="off" spellcheck="false" aria-label="Your answer">
           <div class="accents">${['á', 'à', 'â', 'ã', 'é', 'ê', 'í', 'ó', 'ô', 'õ', 'ú', 'ç'].map(a => `<button type="button" class="accent">${a}</button>`).join('')}</div>
           <div class="typebox__row">
             <button type="button" class="btn btn--ghost btn--small" data-hint>💡 Hint</button>
             <button type="submit" class="btn btn--primary">Check ✓</button>
           </div>
         </form>`);
      const form = stage.querySelector('form');
      const input = form.querySelector('input');
      setTimeout(() => input.focus(), 80);
      form.querySelectorAll('.accent').forEach(b => b.addEventListener('click', () => {
        const s = input.selectionStart ?? input.value.length;
        input.value = input.value.slice(0, s) + b.textContent + input.value.slice(input.selectionEnd ?? s);
        input.focus(); input.setSelectionRange(s + 1, s + 1);
      }));
      let hints = 0;
      form.querySelector('[data-hint]').addEventListener('click', () => {
        hints++;
        const target = first(w.pt);
        input.value = target.slice(0, Math.min(target.length - 1, hints * 2));
        input.focus();
      });
      form.addEventListener('submit', ev => {
        ev.preventDefault();
        if (G.locked) return;
        const val = input.value.trim();
        if (!val) { input.classList.add('shake-x'); setTimeout(() => input.classList.remove('shake-x'), 400); return; }
        const cands = variants(w.pt).flatMap(c => [c, stripArticle(norm(c))]);
        const exact = variants(w.pt).some(c => c.toLowerCase().replace(/[?!.…]/g, '').trim() === val.toLowerCase().replace(/[?!.…]/g, '').trim()
          || stripArticle(c.toLowerCase()) === val.toLowerCase());
        const loose = cands.some(c => norm(c) === norm(val) || stripArticle(norm(c)) === stripArticle(norm(val)));
        const close = !loose && cands.some(c => similar(c, val) >= 0.84 && norm(val).length > 3);
        input.disabled = true;
        if (exact) answer(true, { answerPt: w.pt, answerEn: w.en });
        else if (loose) answer(true, { answerPt: w.pt, answerEn: w.en, note: 'Correct! Watch the accents ✍️' });
        else if (close) answer(true, { answerPt: w.pt, answerEn: w.en, note: 'Almost perfect — small typo. Check the spelling.' });
        else answer(false, { answerPt: w.pt, answerEn: w.en, note: w.n, review: w });
        input.classList.add(exact || loose || close ? 'is-right' : 'is-wrong');
      });
    },

    match(q) {
      h(`<p class="q-label">Match the pairs 🧩 <small>(tap one on each side)</small></p><div class="match"><div class="match__col" data-side="pt"></div><div class="match__col" data-side="en"></div></div>`);
      const colPt = stage.querySelector('[data-side="pt"]'), colEn = stage.querySelector('[data-side="en"]');
      let sel = null, done = 0, errs = 0;
      const mk = (w, side) => {
        const b = document.createElement('button');
        b.className = 'tile'; b.dataset.id = w.pt; b.dataset.side = side;
        b.textContent = side === 'pt' ? w.pt : w.en;
        b.addEventListener('click', () => pick(b));
        return b;
      };
      shuffle(q.pairs).forEach(w => colPt.appendChild(mk(w, 'pt')));
      shuffle(q.pairs).forEach(w => colEn.appendChild(mk(w, 'en')));
      function pick(b) {
        if (G.locked || b.classList.contains('is-matched')) return;
        if (b.dataset.side === 'pt') speak(first(b.dataset.id));
        if (!sel || sel.dataset.side === b.dataset.side) {
          if (sel) sel.classList.remove('is-sel');
          sel = b; b.classList.add('is-sel'); sfx.tap(); return;
        }
        if (sel.dataset.id === b.dataset.id) {
          [sel, b].forEach(x => { x.classList.remove('is-sel'); x.classList.add('is-matched'); x.disabled = true; });
          sfx.good(); done++;
          if (done === q.pairs.length) {
            const ok = errs <= 1;
            setTimeout(() => answer(ok, { note: ok ? (errs ? '1 mistake — still great!' : 'All pairs, no mistakes!') : `${errs} mistakes. Try to be careful!`, review: ok ? null : q.pairs[0] }), 250);
          }
        } else {
          errs++; sfx.bad();
          [sel, b].forEach(x => { x.classList.add('is-bad'); setTimeout(() => x.classList.remove('is-bad', 'is-sel'), 450); });
        }
        sel = null;
      }
    },

    build(q) {
      h(`<p class="q-label">Build the sentence in Portuguese 🧱</p>
         <div class="q-prompt q-prompt--sm"><span class="q-word q-word--en">“${esc(q.s[1])}”</span></div>
         <div class="build">
           <div class="build__answer" aria-label="Your sentence"></div>
           <div class="build__bank"></div>
           <div class="typebox__row">
             <button class="btn btn--ghost btn--small" data-clear>↺ Clear</button>
             <button class="btn btn--primary" data-check disabled>Check ✓</button>
           </div>
         </div>`);
      const ans = stage.querySelector('.build__answer'), bank = stage.querySelector('.build__bank');
      const check = stage.querySelector('[data-check]');
      q.bank.forEach((t, k) => {
        const b = document.createElement('button');
        b.className = 'chip'; b.textContent = t; b.dataset.k = k;
        b.addEventListener('click', () => {
          if (G.locked) return;
          sfx.tap();
          (b.parentElement === bank ? ans : bank).appendChild(b);
          check.disabled = !ans.children.length;
        });
        bank.appendChild(b);
      });
      stage.querySelector('[data-clear]').addEventListener('click', () => {
        [...ans.children].forEach(c => bank.appendChild(c)); check.disabled = true;
      });
      check.addEventListener('click', () => {
        const built = [...ans.children].map(c => c.textContent).join(' ');
        const ok = norm(built) === norm(q.s[0]);
        ans.classList.add(ok ? 'is-right' : 'is-wrong');
        stage.querySelectorAll('.chip').forEach(c => c.disabled = true);
        answer(ok, { answerPt: q.s[0], answerEn: q.s[1], review: { pt: q.s[0], en: q.s[1] } });
      });
    },

    grammar(q) {
      h(`<p class="q-label">Choose the correct Portuguese 📐</p>
         <div class="q-prompt q-prompt--sm"><span class="q-word q-word--en">“${esc(q.g.q)}”</span></div>`);
      optionButtons(q.opts, o => o, o => o === q.g.a, ok => answer(ok, { answerPt: q.g.a, answerEn: q.g.q, note: q.g.h, review: { pt: q.g.a, en: q.g.q } }));
      h(keyHint);
    }
  };

  /* ---------- Events ---------- */
  document.addEventListener('click', e => {
    const s = e.target.closest('[data-start]');
    if (s) { sfx.tap(); start(s.dataset.start); }
    if (e.target.closest('[data-continue]')) next();
    if (e.target.closest('[data-quit]')) {
      if (G && G.i > 0 && !confirm('Quit this game? Your progress in this round will be lost.')) return;
      showScreen('hub'); renderHub();
    }
    if (e.target.closest('[data-again]')) start(G.mode);
    if (e.target.closest('[data-hub]')) { showScreen('hub'); renderHub(); }
  });

  document.addEventListener('keydown', e => {
    if (screens.game.hidden) return;
    if (!fbBox.hidden && e.key === 'Enter') { e.preventDefault(); next(); return; }
    if (e.target.matches('input')) return;
    if (/^[1-4]$/.test(e.key)) {
      const o = stage.querySelectorAll('.opt')[+e.key - 1];
      if (o && !o.disabled) o.click();
    }
  });

  renderHub();
  // Deep link e.g. quiz?topic=food&mode=choose
  const m = new URLSearchParams(location.search).get('mode');
  if (m && gen[m] || m === 'mix') start(m);
})();
