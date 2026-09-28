<?php
// Generates static HTML into /public for Vercel deployment

$root   = dirname(__DIR__);
$outDir = $root . '/public';

// ── Helpers ──────────────────────────────────────────────────────────────────

function render(string $template, array $vars = []): string {
    extract($vars);
    ob_start();
    require $template;
    return ob_get_clean();
}

function mkdir_p(string $dir): void {
    if (!is_dir($dir)) mkdir($dir, 0755, true);
}

function copy_dir(string $src, string $dst): void {
    if (!is_dir($src)) return;
    mkdir_p($dst);
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($it as $item) {
        $target = $dst . '/' . $it->getSubPathname();
        $item->isDir() ? mkdir_p($target) : copy($item->getPathname(), $target);
    }
}

// ── Generate HTML ─────────────────────────────────────────────────────────────

mkdir_p($outDir);
mkdir_p($outDir . '/pt');

file_put_contents($outDir . '/index.html', render($root . '/templates/landing-en.php'));
echo "✓ public/index.html\n";

file_put_contents($outDir . '/pt/index.html', render($root . '/templates/landing-pt.php'));
echo "✓ public/pt/index.html\n";

foreach (['en' => '/design-system', 'pt' => '/pt/design-system'] as $lang => $dir) {
    mkdir_p($outDir . $dir);
    file_put_contents($outDir . $dir . '/index.html', render($root . '/templates/design-system.php', ['ds_lang' => $lang]));
    echo "✓ public$dir/index.html\n";
}

file_put_contents($outDir . '/404.html', render($root . '/templates/404.php'));
echo "✓ public/404.html\n";

// ── Copy static assets ────────────────────────────────────────────────────────

$assets = ['dist', 'img', 'fonts', 'favicons'];
foreach ($assets as $dir) {
    copy_dir("$root/$dir", "$outDir/$dir");
    echo "✓ public/$dir/\n";
}

$files = ['favicon.ico', 'site.webmanifest', 'llms.txt', 'resume.json', 'AGENTS.md'];
foreach ($files as $file) {
    if (file_exists("$root/$file")) {
        copy("$root/$file", "$outDir/$file");
        echo "✓ public/$file\n";
    }
}

echo "\nBuild complete → public/\n";
