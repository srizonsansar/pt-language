/* Home page: word of the day + dashboard */
(function () {
  const { Progress, speak, sfx } = window.PT;
  const D = window.PT_DATA;

  // Word of the day — same word for everyone on a given date
  const dayNum = Math.floor(Date.now() / 864e5);
  const w = D.words[dayNum % D.words.length];
  document.querySelectorAll('[data-wotd]').forEach(el => { el.textContent = w[el.dataset.wotd] || ''; });
  document.querySelector('[data-wotd-link]').href = 'learn' + (window.PT_EXT || '') + '?topic=' + w.topic;
  document.querySelector('[data-wotd-say]').onclick = e => speak(w.pt, { el: e.currentTarget, slow: false });
  document.querySelector('[data-wotd-slow]').onclick = e => speak(w.pt, { el: e.currentTarget, slow: true });

  const card = document.querySelector('.wotd__card');
  const flip = () => { card.classList.toggle('is-flipped'); sfx.flip(); };
  card.addEventListener('click', flip);
  card.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); flip(); } });

  function render() {
    const P = Progress.data;
    const lvl = Progress.level();
    document.querySelector('[data-level-num]').textContent = lvl;
    document.querySelector('[data-level-ring]').style.setProperty('--p', Progress.levelPct());
    document.querySelector('[data-next-level]').textContent = lvl * 150 - P.xp;

    const g = Math.min(P.daily.xp, Progress.dailyGoal);
    document.querySelector('[data-goal-num]').textContent = P.daily.xp;
    document.querySelector('[data-goal-ring]').style.setProperty('--p', g / Progress.dailyGoal * 100);
    document.querySelector('[data-goal-text]').textContent = g >= Progress.dailyGoal
      ? 'Goal complete! Muito bem! 🎉' : `${Progress.dailyGoal - g} XP left today to reach your goal.`;

    document.querySelector('[data-learned]').textContent = Progress.learnedCount();

    document.querySelectorAll('.path__node').forEach(n => {
      const list = D.topics[n.dataset.topic];
      const c = Progress.learnedCount(list);
      n.querySelector('[data-count]').textContent = `${c}/${list.length}`;
      n.querySelector('.path__bar i').style.width = (c / list.length * 100) + '%';
      n.classList.toggle('is-done', c === list.length);
    });
    document.querySelectorAll('[data-done]').forEach(el => { el.hidden = !Progress.isDialogueDone(el.dataset.done); });
  }
  render();
  document.addEventListener('progress:change', render);
})();
