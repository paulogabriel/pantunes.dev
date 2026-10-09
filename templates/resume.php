<?php
// Resume page (/resume) rendered from resume.json. The same page is printed to resume.pdf by scripts/build-resume.js.
$r = json_decode(file_get_contents(__DIR__ . '/../resume.json'), true);
$b = $r['basics'];
$e = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES);
$year = fn($d) => $d ? substr($d, 0, 4) : 'present';
$period = function (array $w) use ($year) {
  $from = $year($w['startDate'] ?? '');
  $to   = $year($w['endDate'] ?? '');
  return $from === $to ? $from : "{$from}–{$to}";
};
$bare = fn($url) => preg_replace('#^https?://(www\.)?#', '', rtrim($url, '/'));
$profiles = array_filter($b['profiles'] ?? [], fn($p) => in_array($p['network'], ['LinkedIn', 'GitHub', 'Behance'], true));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $e($b['name']) ?> — Resume · Senior Front-End Developer, Web Accessibility</title>
<meta name="description" content="Resume of Paulo Antunes, senior front-end developer focused on web accessibility (WCAG 2.2 AA, ADA). Drupal, WordPress, HTML, CSS, JS. Remote from Brazil.">
<link rel="canonical" href="https://pantunes.dev/resume">
<meta property="og:type" content="profile">
<meta property="og:site_name" content="Paulo Antunes">
<meta property="og:title" content="Paulo Antunes — Resume · Senior Front-End Developer, Web Accessibility">
<meta property="og:description" content="Senior front-end developer focused on web accessibility (WCAG 2.2 AA, ADA). Drupal, WordPress, HTML, CSS, JS. Remote from Brazil.">
<meta property="og:url" content="https://pantunes.dev/resume">
<meta property="og:image" content="https://pantunes.dev/img/og-image.png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Paulo Antunes — Senior Front-End Developer, Web Accessibility (WCAG 2.2 AA)">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="https://pantunes.dev/img/og-image.png">
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'ProfilePage',
  '@id' => 'https://pantunes.dev/resume#profilepage',
  'url' => 'https://pantunes.dev/resume',
  'name' => 'Paulo Antunes — Resume',
  'inLanguage' => 'en',
  'mainEntity' => [
    '@type' => 'Person',
    '@id' => 'https://pantunes.dev/#person',
    'name' => $b['name'],
    'alternateName' => 'Paulo Gabriel Antunes',
    'jobTitle' => 'Senior Front-End Developer',
    'url' => $b['url'],
    'email' => $b['email'],
    'knowsLanguage' => ['en', 'pt-BR'],
    'address' => ['@type' => 'PostalAddress', 'addressLocality' => $b['location']['city'], 'addressRegion' => $b['location']['region'], 'addressCountry' => $b['location']['countryCode']],
    'sameAs' => array_values(array_map(fn($p) => $p['url'], $profiles)),
  ],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>
<meta name="author" content="<?= $e($b['name']) ?>">
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="alternate" type="application/json" href="/resume.json">
<style>
@font-face { font-family: 'Space Grotesk'; font-weight: 700; font-display: swap; src: url('/fonts/space-grotesk/space-grotesk-v22-latin-700.woff2') format('woff2'); }
@font-face { font-family: 'Space Grotesk'; font-weight: 500; font-display: swap; src: url('/fonts/space-grotesk/space-grotesk-v22-latin-500.woff2') format('woff2'); }
@font-face { font-family: 'Hanken Grotesk'; font-weight: 400; font-display: swap; src: url('/fonts/hanken-grotesk/hanken-grotesk-v12-latin-regular.woff2') format('woff2'); }
@font-face { font-family: 'Hanken Grotesk'; font-weight: 600; font-display: swap; src: url('/fonts/hanken-grotesk/hanken-grotesk-v12-latin-600.woff2') format('woff2'); }

@page { size: Letter; margin: 0.55in 0.6in; }

:root {
  --paper: #ffffff;
  --desk: #272822;
  --ink: #1d1e19;
  --ink-2: #3c3d35;
  --muted: #5e5f54;
  --rule: #d9d8cc;
  --accent: #3f6a00;
  --link: #0a6a80;
}

* { box-sizing: border-box; margin: 0; padding: 0; }
html { background: var(--desk); }
body {
  font-family: 'Hanken Grotesk', system-ui, sans-serif;
  font-size: 10.5pt;
  line-height: 1.45;
  color: var(--ink);
  -webkit-font-smoothing: antialiased;
}

.toolbar {
  max-width: 8.5in;
  margin: 0 auto;
  padding: 20px 16px;
  display: flex;
  justify-content: space-between;
  gap: 16px;
  font-size: 14px;
}
.toolbar a { color: #66d9ef; }
.toolbar a:hover { color: #fff; }
.toolbar a:focus-visible { outline: 2px solid #a6e22e; outline-offset: 3px; }

.paper {
  max-width: 8.5in;
  margin: 0 auto 48px;
  padding: 0.55in 0.6in;
  background: var(--paper);
  border-radius: 4px;
}

h1, h2, h3 { font-family: 'Space Grotesk', sans-serif; line-height: 1.15; }
h1 { font-size: 24pt; font-weight: 700; letter-spacing: -.01em; }
.label { margin-top: 2px; font-size: 12pt; color: var(--ink-2); font-weight: 600; }
.contact { margin-top: 8px; display: flex; flex-wrap: wrap; gap: 4px 14px; list-style: none; font-size: 9.5pt; color: var(--ink-2); }
a { color: var(--link); text-decoration: none; }
a:hover { text-decoration: underline; }
a:focus-visible { outline: 2px solid var(--link); outline-offset: 2px; }
.contact a { display: inline-block; min-height: 24px; min-width: 24px; line-height: 24px; }
.contact { gap: 0 14px; }

section { margin-top: 16px; }
h2 {
  font-size: 10pt;
  font-weight: 700;
  letter-spacing: 0;
  text-transform: uppercase;
  color: var(--accent);
  padding-bottom: 4px;
  margin-bottom: 8px;
  border-bottom: 1px solid var(--rule);
}
p { max-width: none; }

.skills { list-style: none; display: flex; flex-direction: column; gap: 3px; }
.skills strong { font-weight: 600; }

.job { break-inside: avoid; }
.job + .job { margin-top: 12px; }
.job-head { display: flex; justify-content: space-between; align-items: baseline; gap: 16px; }
h3 { font-size: 11pt; font-weight: 700; }
.when { font-size: 9.5pt; color: var(--muted); white-space: nowrap; font-variant-numeric: tabular-nums; }
.where { font-size: 9.5pt; color: var(--ink-2); }
.job ul { margin-top: 4px; padding-left: 1.1em; display: flex; flex-direction: column; gap: 2px; }
.job li::marker { color: var(--muted); }

@media print {
  html { background: none; }
  .toolbar { display: none; }
  .paper { max-width: none; margin: 0; padding: 0; border-radius: 0; }
  a { color: var(--ink); }
}
@media screen and (max-width: 700px) {
  .paper { padding: 28px 20px; border-radius: 0; }
  .job-head { flex-direction: column; gap: 0; }
}
</style>
</head>
<body>

<nav class="toolbar" aria-label="Resume">
  <a href="/">← pantunes.dev</a>
  <a href="/resume.pdf" download>Download PDF</a>
</nav>

<main class="paper">
  <header>
    <h1><?= $e($b['name']) ?></h1>
    <p class="label"><?= $e($b['label']) ?></p>
    <ul class="contact" aria-label="Contact">
      <li><?= $e($b['location']['city']) ?>, Brazil · remote</li>
      <li><a href="mailto:<?= $e($b['email']) ?>"><?= $e($b['email']) ?></a></li>
      <li><a href="<?= $e($b['url']) ?>"><?= $e($bare($b['url'])) ?></a></li>
      <?php foreach ($profiles as $p): ?><li><a href="<?= $e($p['url']) ?>"><?= $e($bare($p['url'])) ?></a></li><?php endforeach; ?>
    </ul>
  </header>

  <section aria-labelledby="h-summary">
    <h2 id="h-summary">Summary</h2>
    <p><?= $e($b['summary']) ?></p>
  </section>

  <section aria-labelledby="h-skills">
    <h2 id="h-skills">Skills</h2>
    <ul class="skills">
      <?php foreach ($r['skills'] as $s): ?>
      <li><strong><?= $e($s['name']) ?>:</strong> <?= $e(implode(', ', $s['keywords'] ?? [])) ?></li>
      <?php endforeach; ?>
    </ul>
  </section>

  <section aria-labelledby="h-work">
    <h2 id="h-work">Experience</h2>
    <?php foreach ($r['work'] as $w): ?>
    <article class="job">
      <div class="job-head">
        <h3><?= $e($w['position']) ?> · <?= $e($w['name']) ?></h3>
        <span class="when"><?= $e($period($w)) ?></span>
      </div>
      <?php if (!empty($w['url']) || !empty($w['location'])): ?>
      <p class="where"><?php if (!empty($w['url'])): ?><?= $e($bare($w['url'])) ?><?php endif; ?><?= !empty($w['url']) && !empty($w['location']) ? ' · ' : '' ?><?= $e($w['location'] ?? '') ?></p>
      <?php endif; ?>
      <?php if (!empty($w['highlights'])): ?>
      <ul>
        <?php foreach ($w['highlights'] as $h): ?><li><?= $e($h) ?></li><?php endforeach; ?>
      </ul>
      <?php else: ?>
      <p><?= $e($w['summary'] ?? '') ?></p>
      <?php endif; ?>
    </article>
    <?php endforeach; ?>
  </section>

  <section aria-labelledby="h-edu">
    <h2 id="h-edu">Education</h2>
    <?php foreach ($r['education'] as $ed): ?>
    <p><strong><?= $e($ed['studyType'] . ' in ' . $ed['area']) ?></strong> · <?= $e($ed['institution']) ?>, Porto Alegre, Brazil · <?= $e($year($ed['endDate'] ?? '')) ?></p>
    <?php endforeach; ?>
  </section>

  <section aria-labelledby="h-lang">
    <h2 id="h-lang">Languages</h2>
    <p><?= $e(implode(' · ', array_map(fn($l) => $l['language'] . ' (' . strtolower($l['fluency']) . ')', $r['languages']))) ?></p>
  </section>
</main>

</body>
</html>
