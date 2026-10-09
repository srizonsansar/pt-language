<?php
/**
 * Bootstrap: loads content files and small helpers. No database — all content lives in /data.
 */
$VOCAB     = require __DIR__ . '/../data/vocab.php';
$DIALOGUES = require __DIR__ . '/../data/dialogues.php';
$PHRASES   = require __DIR__ . '/../data/phrases.php';

const SITE_NAME = 'Fala Comigo';
const SITE_TAGLINE = 'Beginner Portuguese for everyday life in Portugal';

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
