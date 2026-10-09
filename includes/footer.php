  </main>
  <footer class="footer">
    <div class="footer__inner">
      <p><strong>Fala<b>Comigo</b></strong> — <?= e(SITE_TAGLINE) ?>. Made with ❤️ by a Nepali in Portugal 🇳🇵 → 🇵🇹</p>
      <p class="footer__small">Audio uses your browser's built-in Portuguese voice. Progress is saved only in this browser.
        <button class="linkbtn" data-reset>Reset progress</button></p>
    </div>
  </footer>

  <div class="toast-zone" aria-live="polite"></div>
  <div class="levelup" hidden>
    <div class="levelup__card">
      <div class="levelup__emoji">🏆</div>
      <h3>Nível <span data-level>2</span>!</h3>
      <p>Muito bem! You reached a new level.</p>
      <button class="btn btn--primary" data-close-levelup>Continuar</button>
    </div>
  </div>
  <canvas class="confetti" aria-hidden="true"></canvas>

  <script>window.PT_EXT = <?= CLEAN_URLS ? "''" : "'.php'" ?>;</script>
  <?php if (!empty($pageData)): ?>
  <script>window.PT_DATA = <?= js($pageData) ?>;</script>
  <?php endif; ?>
  <script src="<?= asset('assets/js/app.js') ?>"></script>
  <?php foreach (($scripts ?? []) as $s): ?>
  <script src="<?= asset('assets/js/' . $s) ?>"></script>
  <?php endforeach; ?>
</body>
</html>
