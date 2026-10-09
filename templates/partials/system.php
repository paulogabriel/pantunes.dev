<?php
// "Built on a system" section: design system preview. Expects $system_lang = 'en' | 'pt'.
require_once __DIR__ . '/tokens.php';

$pt = ($system_lang ?? 'en') === 'pt';
$L  = fn(string $en, string $ptxt) => $pt ? $ptxt : $en;
$tk = ds_tokens();
[$pass, $total] = ds_summary();

$bg = 'bg-about';
$swatches = [];
foreach (['ink', 'green', 'cyan', 'orange', 'ink-muted', 'pink'] as $k) {
  if (!isset($tk[$k])) continue;
  $r = ds_ratio($tk[$k]['hex'], $tk[$bg]['hex']);
  [$grade, $cls] = ds_grade($r);
  $swatches[] = ['name' => $k, 'ratio' => ds_fmt($r, $pt), 'grade' => $grade, 'cls' => $cls];
}
$title = $L('Built on a system', 'Construído sobre um sistema');
?>
<section class="system" id="sec-system" aria-labelledby="system-title">
  <div class="system-inner">
    <div class="system-head">
      <div class="system-intro">
        <h2 class="system-title" id="system-title" aria-label="<?= $title ?>"><?= $title ?><span class="cursor" aria-hidden="true">|</span></h2>
        <p class="system-sub hg"><?= $L(
          '// This site gets the same care as client work: shared tokens, real components, and every color pair checked against WCAG.',
          '// Este site recebe o mesmo cuidado que o trabalho para clientes: tokens compartilhados, componentes reais e cada par de cores verificado na WCAG.'
        ) ?></p>
      </div>
      <p class="system-stat">
        <span class="system-stat-num"><?= $pass ?>/<?= $total ?></span>
        <span class="system-stat-label mono"><?= $L('color pairs pass WCAG 2.2 AA', 'pares de cores passam na WCAG 2.2 AA') ?></span>
      </p>
    </div>

    <div class="system-grid">
      <div class="system-card">
        <h3 class="system-card-title mono"><?= $L('Type', 'Tipografia') ?></h3>
        <ul class="system-type">
          <li><span class="system-aa" style="font-family: var(--font-sans); font-weight: 700" aria-hidden="true">Aa</span><span><strong>Space Grotesk</strong><span class="mono"><?= $L('headings · UI', 'títulos · interface') ?></span></span></li>
          <li><span class="system-aa hg" aria-hidden="true">Aa</span><span><strong>Hanken Grotesk</strong><span class="mono"><?= $L('reading', 'leitura') ?></span></span></li>
          <li><span class="system-aa mono is-mono" aria-hidden="true">Aa</span><span><strong>Space Mono</strong><span class="mono"><?= $L('labels · terminal', 'rótulos · terminal') ?></span></span></li>
        </ul>
      </div>

      <div class="system-card">
        <h3 class="system-card-title mono"><?= $L('Components', 'Componentes') ?></h3>
        <!-- Visual showcase: inert keeps the demo controls out of Tab order and screen readers -->
        <div class="system-components" inert>
          <span class="btn-cta is-visible"><?= $L('Get to know me', 'Me conhecer melhor') ?> <span class="cta-arrow" aria-hidden="true">↓</span></span>
          <span class="agent-chip mono">
            <span class="agent-dot" aria-hidden="true"></span>
            <span class="agent-label">AGENT-READY</span>
          </span>
          <div class="form-group">
            <label for="system-demo-email" class="field-label mono"><?= $L('EMAIL', 'E-MAIL') ?> <span class="required-mark" aria-hidden="true">*</span></label>
            <input type="email" id="system-demo-email" value="paulo@" aria-invalid="true" readonly>
            <span class="field-hint"><?= $L('Please enter a valid email address.', 'Informe um e-mail válido.') ?></span>
          </div>
        </div>
      </div>

      <div class="system-card">
        <div class="system-card-head">
          <h3 class="system-card-title mono"><?= $L('Color · contrast', 'Cor · contraste') ?></h3>
          <span class="system-card-note mono"><?= $L('on', 'sobre') ?> --<?= $bg ?></span>
        </div>
        <ul class="system-colors">
          <?php foreach ($swatches as $s): ?>
          <li>
            <span class="system-swatch" style="background: var(--<?= $s['name'] ?>)" aria-hidden="true"></span>
            <code class="mono">--<?= $s['name'] ?></code>
            <span class="system-ratio mono"><?= $s['ratio'] ?>:1</span>
            <span class="system-badge mono <?= $s['cls'] ?>"><?= $s['grade'] ?></span>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <a class="system-link mono" href="<?= $pt ? '/pt/design-system' : '/design-system' ?>"><?= $L('Explore the design system', 'Ver o design system') ?> <span class="system-arrow" aria-hidden="true">→</span></a>
  </div>
</section>
