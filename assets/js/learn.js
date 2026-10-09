/* Learn page: topic grid progress + flashcard deck + word list */
(function () {
  const { Progress, speak, listen, canListen, sfx, toast, confetti, similar } = window.PT;
  const D = window.PT_DATA;

  /* ----- Topic overview ----- */
  if (D.topics) {
    const render = () => document.querySelectorAll('.topic-card').forEach(c => {
      const list = D.topics[c.dataset.topic];
      const n = Progress.learnedCount(list);
      c.querySelector('[data-count]').textContent = `${n}/${list.length}`;
      c.querySelector('.minibar i').style.width = (n / list.length * 100) + '%';
      c.classList.toggle('is-done', n === list.length);
    });
    render();
    return;
  }

  /* ----- Single topic ----- */
  const words = D.words;
  let pos = 0;
  const $ = s => document.querySelector(s);
  const flash = $('.flash');
  const fb = $('.deck__feedback');

  // Tabs
  document.querySelectorAll('[data-tab]').forEach(b => b.addEventListener('click', () => {
    document.querySelectorAll('[data-tab]').forEach(x => x.classList.toggle('is-active', x === b));
    document.querySelectorAll('[data-panel]').forEach(p => p.hidden = p.dataset.panel !== b.dataset.tab);
    window.PT.reveal();
    sfx.tap();
  }));

  function show(i, dir = 0) {
    pos = (i + words.length) % words.length;
    const w = words[pos];
    flash.classList.remove('is-flipped');
    flash.classList.remove('slide-l', 'slide-r');
    void flash.offsetWidth;
    if (dir) flash.classList.add(dir > 0 ? 'slide-l' : 'slide-r');
    // wait for flip-back before swapping content
    setTimeout(() => {
      document.querySelectorAll('[data-f]').forEach(el => el.textContent = w[el.dataset.f] || '');
      $('.flash__badge').hidden = !Progress.isLearned(w.pt);
    }, dir ? 0 : 0);
    $('[data-pos]').textContent = pos + 1;
    $('.deck__progress i').style.width = ((pos + 1) / words.length * 100) + '%';
    fb.textContent = '';
    fb.className = 'deck__feedback';
  }

  const flip = () => { flash.classList.toggle('is-flipped'); sfx.flip(); };
  flash.addEventListener('click', flip);

  function know() {
    const w = words[pos];
    if (Progress.markLearned(w.pt)) { Progress.addXP(5, $('[data-act="know"]')); sfx.good(); }
    next();
  }
  function next() {
    if (pos === words.length - 1) return finish();
    show(pos + 1, 1);
  }
  function finish() {
    const known = Progress.learnedCount(words.map(w => w.pt));
    $('.deck').hidden = true;
    $('.deck-done').hidden = false;
    $('[data-known]').textContent = known;
    sfx.win();
    if (known === words.length) confetti();
  }

  // Microphone pronunciation check
  const mic = $('[data-act="mic"]');
  if (canListen) mic.hidden = false;
  async function sayIt() {
    const target = words[pos].pt.split('/')[0].trim();
    mic.classList.add('is-listening');
    fb.className = 'deck__feedback'; fb.textContent = '🎙️ Listening… say: ' + target;
    try {
      const heard = await listen();
      const best = Math.max(0, ...heard.map(h => similar(h, target)));
      const pct = Math.round(best * 100);
      if (best >= 0.75) {
        fb.className = 'deck__feedback good'; fb.textContent = `✅ Excelente! (${pct}% match) — I heard “${heard[0]}”`;
        sfx.good(); Progress.addXP(3, mic);
      } else {
        fb.className = 'deck__feedback bad'; fb.textContent = heard.length ? `🤔 I heard “${heard[0]}” (${pct}%). Listen and try again!` : '🤔 I didn’t catch that. Try again!';
        sfx.bad();
      }
    } catch (e) {
      fb.textContent = '🎤 Microphone not available — check browser permission.';
    }
    mic.classList.remove('is-listening');
  }

  document.addEventListener('click', e => {
    const b = e.target.closest('[data-act]');
    if (!b) return;
    const w = words[pos];
    switch (b.dataset.act) {
      case 'say': speak(w.pt, { el: b }); break;
      case 'slow': speak(w.pt, { el: b, slow: true }); break;
      case 'mic': sayIt(); break;
      case 'prev': show(pos - 1, -1); break;
      case 'again': sfx.tap(); if (Progress.isLearned(w.pt)) Progress.unmarkLearned(w.pt); next(); break;
      case 'know': know(); break;
      case 'restart': $('.deck').hidden = false; $('.deck-done').hidden = true; show(0); break;
    }
  });

  document.addEventListener('keydown', e => {
    if (document.querySelector('[data-panel="cards"]').hidden || $('.deck').hidden) return;
    if (e.target.matches('input, textarea')) return;
    if (e.key === ' ' || e.key === 'Enter') { e.preventDefault(); flip(); }
    else if (e.key === 'ArrowRight') next();
    else if (e.key === 'ArrowLeft') show(pos - 1, -1);
    else if (e.key.toLowerCase() === 'k') know();
    else if (e.key.toLowerCase() === 's') speak(words[pos].pt);
  });

  // Swipe on mobile
  let sx = null;
  flash.addEventListener('touchstart', e => { sx = e.touches[0].clientX; }, { passive: true });
  flash.addEventListener('touchend', e => {
    if (sx === null) return;
    const dx = e.changedTouches[0].clientX - sx; sx = null;
    if (Math.abs(dx) > 60) { dx < 0 ? next() : show(pos - 1, -1); }
  });

  show(0);
  // auto-speak first card after first user interaction is not allowed; do nothing

  /* ----- Word list: learned checkmarks ----- */
  function renderList() {
    document.querySelectorAll('.word').forEach(el => el.classList.toggle('is-learned', Progress.isLearned(el.dataset.word)));
  }
  document.querySelectorAll('.word__check').forEach(btn => btn.addEventListener('click', () => {
    const pt = btn.closest('.word').dataset.word;
    if (Progress.isLearned(pt)) { Progress.unmarkLearned(pt); }
    else { Progress.markLearned(pt); Progress.addXP(5, btn); sfx.good(); }
    renderList();
  }));
  renderList();
})();
