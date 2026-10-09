<?php
/**
 * Bootstrap: loads content files and small helpers. No database — all content lives in /data.
 */
$VOCAB     = require __DIR__ . '/../data/vocab.php';
$DIALOGUES = require __DIR__ . '/../data/dialogues.php';
$PHRASES   = require __DIR__ . '/../data/phrases.php';

const SITE_NAME = 'Fala Comigo';
const SITE_TAGLINE = 'Beginner Portuguese for everyday life in Portugal';

/**
 * Base URL of the site, e.g. "/" locally or "/pt/" when uploaded into a folder.
 * Used in <base href> so CSS, JS and links work from any folder and any URL.
 */
define('BASE', (function () {
  $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
  return rtrim($dir, '/') . '/';
})());

/**
 * Clean URLs (/learn instead of /learn.php) are used only when the server supports them:
 * .htaccess (Apache) or router.php (PHP built-in server) sets CLEAN_URLS. Otherwise links fall back to .php.
 */
define('CLEAN_URLS', !empty($_SERVER['CLEAN_URLS']) || !empty($_SERVER['REDIRECT_CLEAN_URLS']) || getenv('CLEAN_URLS'));

/** Link to a page: url('learn') -> "learn" or "learn.php"; url('') -> home. */
function url($page = '') {
  if ($page === '' || $page === './') return './';
  return $page . (CLEAN_URLS ? '' : '.php');
}

/** Escape for HTML output. */
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/** Cache-busting version for CSS/JS based on file modification time. */
function asset($path) {
  $file = __DIR__ . '/../' . $path;
  $v = file_exists($file) ? filemtime($file) : time();
  return $path . '?v=' . $v;
}

/** Encode PHP data for JS safely. */
function js($data) {
  return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);
}

/** Total word count across topics. */
function vocab_count($vocab) {
  $n = 0; foreach ($vocab as $t) $n += count($t['words']); return $n;
}
