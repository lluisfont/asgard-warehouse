<?php
/**
 * Vendor compatibility patches applied after every composer dump-autoload.
 *
 * 1. Removes PHPUnit's Assert/Functions.php from Composer's eager-load lists.
 *    PHPUnit registers Functions.php as a `files` entry so it gets required on
 *    every request — even in production.  That file uses PHP 7.4+ syntax which
 *    breaks older Apache PHP versions.  PHPUnit classes stay in the classmap
 *    (lazy-loaded), so the test runner is unaffected.
 *
 * 2. Patches Slim 2's Util.php to not call get_magic_quotes_gpc(), which was
 *    removed in PHP 8.0.
 *
 * Run automatically via composer.json post-autoload-dump hook.
 */

// ── Patch 1: remove PHPUnit eager-load ───────────────────────────────────────

$phpunitKey = 'ec07570ca5a812141189b1fa81503674';
$autoloadFiles = array(
    __DIR__ . '/../vendor/composer/autoload_files.php',
    __DIR__ . '/../vendor/composer/autoload_static.php',
);

foreach ($autoloadFiles as $path) {
    if (!file_exists($path)) {
        continue;
    }
    $content  = file_get_contents($path);
    $original = $content;
    $content  = preg_replace("/['\"]" . $phpunitKey . "['\"][^\n]+\n/", '', $content);
    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "  [patch] Removed PHPUnit eager-load entry from " . basename($path) . PHP_EOL;
    }
}

// ── Patch 2: Slim 2 get_magic_quotes_gpc() removed in PHP 8.0 ────────────────

$slimUtil = __DIR__ . '/../vendor/slim/slim/Slim/Http/Util.php';
if (file_exists($slimUtil)) {
    $content  = file_get_contents($slimUtil);
    $original = $content;
    $content  = str_replace(
        'is_null($overrideStripSlashes) ? get_magic_quotes_gpc() : $overrideStripSlashes',
        'is_null($overrideStripSlashes) ? (function_exists(\'get_magic_quotes_gpc\') && get_magic_quotes_gpc()) : $overrideStripSlashes',
        $content
    );
    if ($content !== $original) {
        file_put_contents($slimUtil, $content);
        echo "  [patch] Fixed get_magic_quotes_gpc() in Slim/Http/Util.php" . PHP_EOL;
    }
}
