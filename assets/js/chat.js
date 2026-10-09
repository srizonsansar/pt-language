/* Conversations: chat playback + role-play */
(function () {
  const { Progress, speak, sfx, toast, confetti, shuffle, sample, esc } = window.PT;
  const D = window.PT_DATA;

  // Overview page: mark completed
  if (D.ids) {
    document.querySelectorAll('[data-done]').forEach(el => { el.hidden = !Progress.isDialogueDone(el.dataset.done); });
    return;
  }

  const dlg = D.dialogue;
  const chat = document.querySelector('.chat');
  const choices = document.querySelector('.chat__choices');
  const nextBtn = document.querySelector('[data-chat="next"]');
  const enBox = document.querySelector('[data-chat-en]');
  let mode = 'watch', i = 0, busy = false, mistakes = 0, playing = false;

  const isYou = who => who === dlg.you;

  function bubble(line, animate = true) {
    const [who, pt, en] = line;
    const sp = dlg.speakers[who];
    const row = document.createElement('div');
    row.className = 'msg ' + (isYou(who) ? 'msg--me' : 'msg--them') + (animate ? ' pop' : '');
    row.innerHTML = `
      <span class="msg__av" title="${esc(sp.en)}">${sp.av}</span>
      <div class="msg__bubble" tabindex="0">
        <span class="msg__name">${esc(sp.name)}</span>
        <span class="msg__pt">${esc(pt)}</span>
        <span class="msg__en">${esc(en)}</span>
      </div>
      <button class="msg__say" data-say="${esc(pt)}" aria-label="Listen">🔊</button>`;
    row.querySelector('.msg__bubble').addEventListener('click', e => e.currentTarget.classList.toggle('show-en'));
    chat.appendChild(row);
    chat.scrollTo({ top: chat.scrollHeight, behavior: 'smooth' });
    return row;
  }

  function typing(who) {
    const row = document.createElement('div');
    row.className = 'msg ' + (isYou(who) ? 'msg--me' : 'msg--them') + ' msg--typing';
    row.innerHTML = `<span class="msg__av">${dlg.speakers[who].av}</span><div class="msg__bubble"><span class="dots"><i></i><i></i><i></i></span></div>`;
    chat.appendChild(row);
    chat.scrollTo({ top: chat.scrollHeight, behavior: 'smooth' });
    return row;
  }

  function reset() {
    chat.innerHTML = '';
    i = 0; mistakes = 0; busy = false; playing = false;
    choices.hidden = true; choices.innerHTML = '';
    const intro = document.createElement('div');
    intro.className = 'chat__intro';
    intro.innerHTML = mode === 'watch'
      ? '👀 Press <b>Next line</b> to start the conversation.'
      : `🎭 You are <b>${esc(dlg.speakers[dlg.you].en)}</b>. Choose the right reply when it’s your turn.`;
    chat.appendChild(intro);
    nextBtn.hidden = false;
    nextBtn.disabled = false;
    nextBtn.textContent = mode === 'watch' ? '▶ Next line' : '▶ Start';
    document.querySelectorAll('[data-chat="all"],[data-chat="play"]').forEach(b => b.hidden = mode !== 'watch');
  }

  function step() {
    if (busy) return;
    if (i >= dlg.lines.length) return end();
    const line = dlg.lines[i];
    busy = true;
    nextBtn.textContent = '▶ Next line';
    if (mode === 'role' && isYou(line[0])) return askChoice(line);
    const t = typing(line[0]);
    setTimeout(() => {
      t.remove(); bubble(line);
      if (mode === 'role') speak(line[1]);
      i++; busy = false;
      if (mode === 'role') {
        // continue automatically to user's turn
        if (i < dlg.lines.length && isYou(dlg.lines[i][0])) setTimeout(step, 400);
        else if (i >= dlg.lines.length) setTimeout(end, 500);
      } else if (i >= dlg.lines.length) setTimeout(end, 400);
    }, mode === 'role' ? 700 : 450);
  }

  function askChoice(line) {
    nextBtn.hidden = true;
    const wrong = sample(D.pool.filter(p => p[0] !== line[1]), 2);
    const opts = shuffle([[line[1], line[2], true], ...wrong.map(w => [w[0], w[1], false])]);
    choices.innerHTML = '<p class="chat__prompt">Your turn — what do you say? 🤔</p>' + opts.map((o, k) =>
      `<button class="choice" data-ok="${o[2] ? 1 : 0}"><span class="choice__k">${k + 1}</span><span><b>${esc(o[0])}</b><small>${esc(o[1])}</small></span></button>`).join('');
    choices.hidden = false;
    choices.querySelectorAll('.choice').forEach(b => b.addEventListener('click', () => {
      if (b.dataset.ok === '1') {
        b.classList.add('is-right'); sfx.good();
        setTimeout(() => {
          choices.hidden = true;
          bubble(line); speak(line[1]);
          i++; busy = false; nextBtn.hidden = false;
          setTimeout(step, 1100);
        }, 450);
      } else {
        mistakes++; b.classList.add('is-wrong'); b.disabled = true; sfx.bad();
      }
    }));
  }

  function end() {
    busy = true;
    nextBtn.hidden = true;
    const box = document.createElement('div');
    box.className = 'chat__end pop';
    const firstTime = !Progress.isDialogueDone(D.id);
    if (mode === 'role') {
      const xp = Math.max(10, 25 - mistakes * 5);
      box.innerHTML = `<div class="chat__end-emoji">${mistakes === 0 ? '🏆' : '👏'}</div>
        <b>${mistakes === 0 ? 'Perfeito! No mistakes.' : `Muito bem! ${mistakes} mistake${mistakes > 1 ? 's' : ''}.`}</b>
        <span>+${xp} XP</span>
        <button class="btn btn--ghost btn--small" data-chat="restart">🔁 Again</button>`;
      chat.appendChild(box);
      Progress.dialogueDone(D.id);
      Progress.addXP(xp, box);
      sfx.win(); if (mistakes === 0) confetti();
    } else {
      box.innerHTML = `<div class="chat__end-emoji">✅</div><b>Fim da conversa!</b>
        <span>Now try <b>Role-play</b> to practise your part.</span>
        <button class="btn btn--primary btn--small" data-go-role>🎭 Role-play</button>`;
      chat.appendChild(box);
      if (firstTime) Progress.addXP(5, box);
    }
    chat.scrollTo({ top: chat.scrollHeight, behavior: 'smooth' });
  }

  function showAll() {
    chat.innerHTML = '';
    dlg.lines.forEach(l => bubble(l, false));
    i = dlg.lines.length;
    end();
  }

  // Play the whole conversation with audio, one line after another
  function playAll() {
    if (playing) { playing = false; speechSynthesis.cancel(); return; }
    if (i >= dlg.lines.length) { reset(); }
    playing = true; busy = false;
    const go = () => {
      if (!playing || i >= dlg.lines.length) { playing = false; if (i >= dlg.lines.length && !chat.querySelector('.chat__end')) end(); return; }
      const line = dlg.lines[i];
      const row = bubble(line); i++;
      row.querySelector('.msg__bubble').classList.add('is-speaking');
      speak(line[1], { onend: () => { row.querySelector('.msg__bubble').classList.remove('is-speaking'); setTimeout(go, 350); } });
    };
    chat.querySelector('.chat__intro')?.remove();
    go();
  }

  document.addEventListener('click', e => {
    const b = e.target.closest('[data-chat]');
    if (b) {
      const a = b.dataset.chat;
      if (a === 'next') { chat.querySelector('.chat__intro')?.remove(); step(); }
      if (a === 'all') showAll();
      if (a === 'play') playAll();
      if (a === 'restart') reset();
    }
    const m = e.target.closest('[data-mode]');
    if (m) {
      mode = m.dataset.mode;
      document.querySelectorAll('[data-mode]').forEach(x => x.classList.toggle('is-active', x === m));
      speechSynthesis && speechSynthesis.cancel();
      sfx.tap(); reset();
    }
    if (e.target.closest('[data-go-role]')) document.querySelector('[data-mode="role"]').click();
  });

  document.addEventListener('keydown', e => {
    if (mode === 'role' && !choices.hidden && /^[1-3]$/.test(e.key)) {
      choices.querySelectorAll('.choice')[+e.key - 1]?.click();
    }
  });

  enBox.addEventListener('change', () => chat.classList.toggle('hide-en', !enBox.checked));
  reset();
})();
