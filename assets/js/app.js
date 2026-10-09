/* ==========================================================================
   Fala Comigo — core app: progress, speech, sounds, toasts, confetti
   No database: progress lives in localStorage (this browser only).
   ========================================================================== */
(function () {
  'use strict';

  const KEY = 'falacomigo.v1';
  const XP_PER_LEVEL = 150;
  const DAILY_GOAL = 50;

  /* ---------- Storage (safe) ---------- */
  const store = {
    read() {
      try { return JSON.parse(localStorage.getItem(KEY)) || {}; } catch (e) { return {}; }
    },
    write(d) {
      try { localStorage.setItem(KEY, JSON.stringify(d)); } catch (e) { /* private mode */ }
    }
  };

  const today = () => new Date().toISOString().slice(0, 10);
  const dayDiff = (a, b) => Math.round((new Date(b) - new Date(a)) / 864e5);

  const defaults = () => ({ xp: 0, streak: 0, lastDay: null, learned: {}, dialogues: {}, quiz: { played: 0, best: 0, correct: 0 }, daily: { date: today(), xp: 0 }, settings: { nepali: true, slow: false } });

  let P = Object.assign(defaults(), store.read());
  P.settings = Object.assign(defaults().settings, P.settings || {});
  if (!P.daily || P.daily.date !== today()) P.daily = { date: today(), xp: 0 };

  // Streak broken if last activity was more than a day ago
  if (P.lastDay && dayDiff(P.lastDay, today()) > 1) P.streak = 0;

  const save = () => store.write(P);

  /* ---------- Progress API ---------- */
  const Progress = {
    get data() { return P; },
    level(xp = P.xp) { return Math.floor(xp / XP_PER_LEVEL) + 1; },
    levelPct(xp = P.xp) { return ((xp % XP_PER_LEVEL) / XP_PER_LEVEL) * 100; },
    dailyGoal: DAILY_GOAL,
    addXP(n, el) {
      const before = this.level();
      P.xp += n;
      P.daily.xp += n;
      // streak counts once per day on first XP
      if (P.lastDay !== today()) {
        P.streak = (P.lastDay && dayDiff(P.lastDay, today()) === 1) ? P.streak + 1 : 1;
        P.lastDay = today();
        if (P.streak > 1) toast(`🔥 ${P.streak} day streak! Continua assim!`);
      }
      save();
      renderStats(true);
      if (el) floatXP(el, n);
      if (this.level() > before) levelUp(this.level());
    },
    markLearned(pt) {
      if (P.learned[pt]) return false;
      P.learned[pt] = 1; save(); return true;
    },
    unmarkLearned(pt) { delete P.learned[pt]; save(); },
    isLearned(pt) { return !!P.learned[pt]; },
    learnedCount(list) { return list ? list.filter(w => P.learned[w]).length : Object.keys(P.learned).length; },
    dialogueDone(id) { P.dialogues[id] = 1; save(); },
    isDialogueDone(id) { return !!P.dialogues[id]; },
    quizResult(correct, total) {
      P.quiz.played++; P.quiz.correct += correct;
      const pct = Math.round(correct / total * 100);
      if (pct > P.quiz.best) P.quiz.best = pct;
      save();
    },
    setting(k, v) { if (v === undefined) return P.settings[k]; P.settings[k] = v; save(); },
    reset() { P = defaults(); save(); location.reload(); }
  };

  /* ---------- Header stats ---------- */
  function renderStats(bump) {
    document.querySelectorAll('[data-xp]').forEach(el => el.textContent = P.xp);
    document.querySelectorAll('[data-streak]').forEach(el => el.textContent = P.streak);
    if (bump) {
      const x = document.querySelector('.stats__xp');
      if (x) { x.classList.remove('bump'); void x.offsetWidth; x.classList.add('bump'); }
    }
    document.dispatchEvent(new CustomEvent('progress:change'));
  }

  /* ---------- Speech (text-to-speech) ---------- */
  let voices = [];
  function loadVoices() {
    if (!('speechSynthesis' in window)) return;
    voices = speechSynthesis.getVoices();
  }
  if ('speechSynthesis' in window) {
    loadVoices();
    speechSynthesis.onvoiceschanged = loadVoices;
  }
  function pickVoice() {
    return voices.find(v => v.lang === 'pt-PT') ||
      voices.find(v => /pt[-_]PT/i.test(v.lang)) ||
      voices.find(v => /^pt/i.test(v.lang)) || null;
  }
  function speak(text, opts = {}) {
    if (!('speechSynthesis' in window)) { toast('🔇 Your browser has no speech support'); return; }
    speechSynthesis.cancel();
    // strip helper characters like "…" and slashes for better reading
    const clean = String(text).replace(/…/g, '').replace(/\s\/\s/g, ', ');
    const u = new SpeechSynthesisUtterance(clean);
    const v = pickVoice();
    if (v) u.voice = v;
    u.lang = v ? v.lang : 'pt-PT';
    const slow = opts.slow ?? P.settings.slow;
    u.rate = slow ? 0.65 : 0.9;
    if (opts.onend) u.onend = opts.onend;
    if (opts.el) {
      opts.el.classList.add('is-speaking');
      u.onend = () => { opts.el.classList.remove('is-speaking'); opts.onend && opts.onend(); };
      u.onerror = () => opts.el.classList.remove('is-speaking');
    }
    speechSynthesis.speak(u);
    return u;
  }

  /* ---------- Speech recognition (optional, Chrome/Edge) ---------- */
  const Rec = window.SpeechRecognition || window.webkitSpeechRecognition;
  function listen() {
    return new Promise((resolve, reject) => {
      if (!Rec) return reject(new Error('unsupported'));
      const r = new Rec();
      r.lang = 'pt-PT'; r.interimResults = false; r.maxAlternatives = 3;
      r.onresult = ev => resolve(Array.from(ev.results[0]).map(a => a.transcript));
      r.onerror = ev => reject(ev);
      r.onnomatch = () => resolve([]);
      r.start();
    });
  }

  /* ---------- Text compare helpers ---------- */
  const norm = s => String(s).toLowerCase()
    .normalize('NFD').replace(/[̀-ͯ]/g, '')
    .replace(/[^a-z0-9\s]/g, ' ').replace(/\s+/g, ' ').trim();
  const stripArticle = s => s.replace(/^(o|a|os|as|um|uma) /, '');
  function similar(a, b) {
    a = norm(a); b = norm(b);
    if (a === b) return 1;
    // Levenshtein ratio
    const m = a.length, n = b.length;
    if (!m || !n) return 0;
    const d = Array.from({ length: m + 1 }, (_, i) => [i]);
    for (let j = 1; j <= n; j++) d[0][j] = j;
    for (let i = 1; i <= m; i++) for (let j = 1; j <= n; j++)
      d[i][j] = Math.min(d[i - 1][j] + 1, d[i][j - 1] + 1, d[i - 1][j - 1] + (a[i - 1] === b[j - 1] ? 0 : 1));
    return 1 - d[m][n] / Math.max(m, n);
  }

  /* ---------- Sounds (WebAudio, no files) ---------- */
  let ctx;
  function tone(freqs, dur = 0.12, type = 'sine', gain = 0.08) {
    try {
      ctx = ctx || new (window.AudioContext || window.webkitAudioContext)();
      freqs.forEach((f, i) => {
        const o = ctx.createOscillator(), g = ctx.createGain();
        o.type = type; o.frequency.value = f;
        const t = ctx.currentTime + i * dur;
        g.gain.setValueAtTime(gain, t);
        g.gain.exponentialRampToValueAtTime(0.001, t + dur * 1.6);
        o.connect(g).connect(ctx.destination);
        o.start(t); o.stop(t + dur * 1.7);
      });
    } catch (e) { /* ignore */ }
  }
  const sfx = {
    good: () => tone([660, 880], 0.09, 'triangle'),
    bad: () => tone([220, 170], 0.14, 'sawtooth', 0.04),
    win: () => tone([523, 659, 784, 1046], 0.11, 'triangle'),
    tap: () => tone([520], 0.04, 'sine', 0.04),
    flip: () => tone([380, 480], 0.05, 'sine', 0.035),
  };

  /* ---------- Toast ---------- */
  function toast(msg, ms = 2600) {
    const zone = document.querySelector('.toast-zone');
    if (!zone) return;
    const t = document.createElement('div');
    t.className = 'toast'; t.textContent = msg;
    zone.appendChild(t);
    requestAnimationFrame(() => t.classList.add('in'));
    setTimeout(() => { t.classList.remove('in'); setTimeout(() => t.remove(), 400); }, ms);
  }

  function floatXP(el, n) {
    const r = el.getBoundingClientRect();
    const f = document.createElement('div');
    f.className = 'xp-float'; f.textContent = `+${n} XP`;
    f.style.left = (r.left + r.width / 2) + 'px';
    f.style.top = (r.top + window.scrollY) + 'px';
    document.body.appendChild(f);
    setTimeout(() => f.remove(), 1100);
  }

  /* ---------- Level up ---------- */
  function levelUp(lvl) {
    const m = document.querySelector('.levelup');
    if (!m) return;
    m.querySelector('[data-level]').textContent = lvl;
    m.hidden = false; sfx.win(); confetti();
  }
  document.addEventListener('click', e => {
    if (e.target.closest('[data-close-levelup]')) document.querySelector('.levelup').hidden = true;
  });

  /* ---------- Confetti ---------- */
  function confetti(count = 140) {
    const c = document.querySelector('.confetti');
    if (!c || matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    const g = c.getContext('2d');
    c.width = innerWidth; c.height = innerHeight;
    const colors = ['#1d4fa3', '#f6c343', '#e0703f', '#2e9e6b', '#d94a7a', '#ffffff'];
    const parts = Array.from({ length: count }, () => ({
      x: innerWidth / 2 + (Math.random() - .5) * 200, y: innerHeight / 3,
      vx: (Math.random() - .5) * 14, vy: Math.random() * -14 - 4,
      s: Math.random() * 8 + 4, r: Math.random() * 6, vr: (Math.random() - .5) * .3,
      c: colors[(Math.random() * colors.length) | 0]
    }));
    let frame = 0;
    (function loop() {
      g.clearRect(0, 0, c.width, c.height);
      parts.forEach(p => {
        p.vy += .35; p.x += p.vx; p.y += p.vy; p.r += p.vr; p.vx *= .99;
        g.save(); g.translate(p.x, p.y); g.rotate(p.r);
        g.fillStyle = p.c; g.fillRect(-p.s / 2, -p.s / 4, p.s, p.s / 2);
        g.restore();
      });
      if (++frame < 160) requestAnimationFrame(loop); else g.clearRect(0, 0, c.width, c.height);
    })();
  }

  /* ---------- Utilities ---------- */
  const shuffle = arr => { const a = arr.slice(); for (let i = a.length - 1; i > 0; i--) { const j = (Math.random() * (i + 1)) | 0; [a[i], a[j]] = [a[j], a[i]]; } return a; };
  const sample = (arr, n) => shuffle(arr).slice(0, n);
  const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

  /* ---------- Global UI wiring ---------- */
  document.addEventListener('click', e => {
    const say = e.target.closest('[data-say]');
    if (say) { e.preventDefault(); speak(say.dataset.say, { el: say, slow: say.hasAttribute('data-slow') ? true : undefined }); }

    const burger = e.target.closest('.burger');
    if (burger) {
      const open = document.body.classList.toggle('nav-open');
      burger.setAttribute('aria-expanded', open);
    }
    if (e.target.closest('[data-reset]')) {
      if (window.confirm('Reset all your progress (XP, streak, learned words)?')) Progress.reset();
    }
  });

  // Global toggles (slow audio / Nepali)
  function syncToggles() {
    document.querySelectorAll('[data-toggle]').forEach(t => {
      const k = t.dataset.toggle;
      t.setAttribute('aria-pressed', !!P.settings[k]);
      t.classList.toggle('is-on', !!P.settings[k]);
    });
    document.body.classList.toggle('show-ne', !!P.settings.nepali);
  }
  document.addEventListener('click', e => {
    const t = e.target.closest('[data-toggle]');
    if (!t) return;
    Progress.setting(t.dataset.toggle, !P.settings[t.dataset.toggle]);
    syncToggles(); sfx.tap();
  });

  // Reveal-on-scroll animations
  const io = 'IntersectionObserver' in window ? new IntersectionObserver(entries => {
    entries.forEach(en => { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
  }, { threshold: .12 }) : null;
  function reveal(root = document) {
    root.querySelectorAll('.reveal:not(.in)').forEach((el, i) => {
      el.style.transitionDelay = Math.min(i * 40, 400) + 'ms';
      io ? io.observe(el) : el.classList.add('in');
    });
  }

  document.addEventListener('DOMContentLoaded', () => { renderStats(); syncToggles(); reveal(); });

  window.PT = { Progress, speak, listen, canListen: !!Rec, sfx, toast, confetti, shuffle, sample, esc, norm, stripArticle, similar, reveal, today };
})();
