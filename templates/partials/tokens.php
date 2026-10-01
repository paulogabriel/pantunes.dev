<?php
// Color tokens read from SCSS + WCAG contrast. Used by the design system and the "Built on a system" section.

if (!function_exists('ds_tokens')) {

define('DS_TEXT', ['white', 'ink', 'ink-2', 'ink-3', 'ink-4', 'ink-muted', 'green', 'cyan', 'orange', 'pink']);
define('DS_BG', ['bg-hero', 'bg-about', 'bg-card', 'bg-terminal']);
define('DS_PAIRS', [
  'cta'         => ['accent-ink', 'accent-btn'],
  'submit'      => ['bg-hero',    'accent'],
  'selection'   => ['bg-hero',    'pink'],
  'placeholder' => ['ink-muted',  'bg-card'],
  'skip'        => ['bg-hero',    'accent'],
]);

function ds_tokens(): array {
  static $tokens = null;
  if ($tokens !== null) return $tokens;
  $scss = file_get_contents(__DIR__ . '/../../sass/_0.tokens.scss');
  preg_match_all('/--([a-z0-9-]+):\s*(#[0-9a-fA-F]{3,6})\s*;[ \t]*(?:\/\/[ \t]*(.*))?/', $scss, $m, PREG_SET_ORDER);
  $tokens = [];
  foreach ($m as $row) $tokens[$row[1]] = ['hex' => strtolower($row[2]), 'note' => trim($row[3] ?? '')];
  return $tokens;
}

function ds_lum(string $hex): float {
  $hex = ltrim($hex, '#');
  if (strlen($hex) === 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
  $c = array_map(function ($v) {
    $v = hexdec($v) / 255;
    return $v <= 0.03928 ? $v / 12.92 : (($v + 0.055) / 1.055) ** 2.4;
  }, str_split($hex, 2));
  return 0.2126 * $c[0] + 0.7152 * $c[1] + 0.0722 * $c[2];
}

function ds_ratio(string $a, string $b): float {
  [$l1, $l2] = [ds_lum($a), ds_lum($b)];
  return (max($l1, $l2) + 0.05) / (min($l1, $l2) + 0.05);
}

function ds_grade(float $r): array {
  if ($r >= 7)   return ['AAA', 'is-aaa'];
  if ($r >= 4.5) return ['AA', 'is-aa'];
  if ($r >= 3)   return ['AA Large', 'is-large'];
  return ['Fail', 'is-fail'];
}

function ds_fmt(float $r, bool $pt): string {
  return number_format($r, 2, $pt ? ',' : '.', '');
}

// All matrix pairs + component pairs: [passing AA, total]
function ds_summary(): array {
  $t = ds_tokens();
  $pairs = [];
  foreach (DS_TEXT as $fg) foreach (DS_BG as $bg) $pairs[] = [$fg, $bg];
  foreach (DS_PAIRS as $p) $pairs[] = $p;
  $pairs = array_filter($pairs, fn($p) => isset($t[$p[0]], $t[$p[1]]));
  $pass = count(array_filter($pairs, fn($p) => ds_ratio($t[$p[0]]['hex'], $t[$p[1]]['hex']) >= 4.5));
  return [$pass, count($pairs)];
}

}
