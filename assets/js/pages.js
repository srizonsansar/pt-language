/* Basics (gender game, verb tabs) + Phrasebook (search, filter) */
(function () {
  const { Progress, speak, sfx, shuffle, norm } = window.PT;
  const $ = s => document.querySelector(s);

  /* ----- Verb tabs ----- */
  document.querySelectorAll('[data-verb]').forEach(b => b.addEventListener('click', () => {
    document.querySelectorAll('[data-verb]').forEach(x => x.classList.toggle('is-active', x === b));
    document.querySelectorAll('[data-verb-table]').forEach(t => t.hidden = t.dataset.verbTable !== b.dataset.verb);
    sfx.tap();
  }));

  /* ----- Gender mini game ----- */
  const words = window.PT_DATA && window.PT_DATA.gender;
  if (words && $('.gender-game')) {
    let deck = shuffle(words), i = 0, score = 0, total = 0, locked = false;
    const art = $('[data-g-art]'), word = $('[data-g-word]'), en = $('[data-g-en]'), msg = $('.gender-game__msg'), card = $('.gender-game__card');
    const show = () => {
      if (i >= deck.length) { deck = shuffle(words); i = 0; }
      const w = deck[i];
      art.textContent = '?'; art.className = 'gender-game__article';
      word.textContent = w[0]; en.textContent = w[2];
      card.classList.remove('is-right', 'is-wrong'); locked = false;
    };
    document.querySelectorAll('[data-g]').forEach(b => b.addEventListener('click', () => {
      if (locked) return; locked = true;
      const w = deck[i];
      const ok = b.dataset.g === w[1];
      total++; if (ok) score++;
      art.textContent = w[1];
      art.classList.add(w[1] === 'o' ? 'is-m' : 'is-f');
      card.classList.add(ok ? 'is-right' : 'is-wrong');
      msg.textContent = (ok ? '✅ Certo! ' : '❌ Errado. ') + `${w[1]} ${w[0]}` + (w[3] ? ' — ' + w[3] : '');
      ok ? sfx.good() : sfx.bad();
      speak(`${w[1]} ${w[0]}`);
      if (ok) Progress.addXP(2, b);
      $('[data-g-score]').textContent = score; $('[data-g-total]').textContent = total;
      i++;
      setTimeout(show, w[3] ? 2200 : 1300);
    }));
    show();
  }

  /* ----- Phrasebook search & filter ----- */
  const search = $('#pbSearch');
  if (search) {
    let cat = 'all';
    const apply = () => {
      const q = norm(search.value);
      let any = false;
      document.querySelectorAll('.pb-group').forEach(g => {
        const inCat = cat === 'all' || g.dataset.group === cat;
        let vis = 0;
        g.querySelectorAll('.phrase').forEach(p => {
          const show = inCat && (!q || norm(p.dataset.text).includes(q));
          p.hidden = !show; if (show) { vis++; p.classList.add('in'); }
        });
        g.hidden = !vis; if (vis) any = true;
      });
      $('.pb-empty').hidden = any;
    };
    search.addEventListener('input', apply);
    document.querySelectorAll('[data-cat]').forEach(b => b.addEventListener('click', () => {
      cat = b.dataset.cat;
      document.querySelectorAll('[data-cat]').forEach(x => x.classList.toggle('is-active', x === b));
      sfx.tap(); apply();
    }));
  }
})();
