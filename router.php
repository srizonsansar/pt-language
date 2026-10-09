<?php
/**
 * Router for PHP's built-in server (no Apache needed), gives clean URLs:
 *   php -S localhost:8000 router.php
 * On a real Apache host, .htaccess does the same job and this file is ignored.
 */
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$root = __DIR__;
$qs = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] !== '' ? '?' . $_SERVER['QUERY_STRING'] : '';

// Block content/include folders
if (preg_match('#^/(data|includes)/#', $path)) { http_response_code(403); exit('Forbidden'); }

// "/learn/" -> "/learn" (keeps relative links working)
if ($path !== '/' && substr($path, -1) === '/' && !is_dir($root . $path)) { header('Location: ' . rtrim($path, '/') . $qs, true, 301); exit; }

// Old pages -> new pages
if (preg_match('#^/(introduction|timepass|last-week)(\.php)?/?$#', $path)) { header('Location: /', true, 301); exit; }
if (preg_match('#^/translations(\.php)?/?$#', $path)) { header('Location: /phrasebook', true, 301); exit; }

// /index.php -> /   and   /quiz.php -> /quiz
if ($path === '/index.php') { header('Location: /' . $qs, true, 301); exit; }
if (preg_match('#^/(.+)\.php$#', $path, $m) && is_file($root . $path)) { header('Location: /' . $m[1] . $qs, true, 301); exit; }

// Real static files (css, js, images) are served as-is
if ($path !== '/' && is_file($root . $path)) return false;

// /quiz -> quiz.php
$file = $path === '/' ? '/index.php' : rtrim($path, '/') . '.php';
if (is_file($root . $file) && strpos(realpath($root . $file), $root) === 0) {
  $_SERVER['SCRIPT_NAME'] = $file;
  chdir($root);
  require $root . $file;
  return true;
}

http_response_code(404);
echo '<!doctype html><meta charset="utf-8"><title>Not found</title><body style="font-family:sans-serif;text-align:center;padding:60px">'
   . '<h1>404 — Página não encontrada</h1><p>Page not found. <a href="/">Go home</a></p>';
