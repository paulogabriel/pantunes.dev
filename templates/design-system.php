<?php
// Public design system. Expects $ds_lang = 'en' | 'pt'.
$pt = ($ds_lang ?? 'en') === 'pt';
$L  = fn(string $en, string $ptxt) => $pt ? $ptxt : $en;
$e  = fn(string $s) => htmlspecialchars($s, ENT_QUOTES);

require_once __DIR__ . '/partials/tokens.php';
$tokens = ds_tokens();

$groups = [
  $L('Accents', 'Acentos')        => ['accent', 'accent-btn', 'accent-ink', 'green', 'cyan', 'orange', 'pink'],
  $L('Backgrounds', 'Fundos')     => ['bg-hero', 'bg-about', 'bg-contact', 'bg-card', 'bg-terminal'],
  $L('Text', 'Texto')             => ['white', 'ink', 'ink-2', 'ink-3', 'ink-4', 'ink-muted'],
  $L('Borders', 'Bordas')         => ['border', 'border-2', 'border-hov'],
];

$textTokens = array_values(array_filter(DS_TEXT, fn($k) => isset($tokens[$k])));
$bgTokens   = array_values(array_filter(DS_BG, fn($k) => isset($tokens[$k])));

$pairLabels = [
  'cta'         => $L('Primary button (CTA)', 'Botão principal (CTA)'),
  'submit'      => $L('Submit button', 'Botão de envio'),
  'selection'   => $L('Text selection', 'Seleção de texto'),
  'placeholder' => $L('Input placeholder', 'Placeholder de campo'),
  'skip'        => $L('Skip link', 'Link de pular conteúdo'),
];
$pairs = [];
foreach (DS_PAIRS as $id => [$fg, $bg]) $pairs[] = [$pairLabels[$id], $fg, $bg];

$home   = $pt ? '/pt' : '/';
$other  = $pt ? '/design-system' : '/pt/design-system';
$toc = [
  'color'      => $L('Color', 'Cores'),
  'contrast'   => $L('Contrast', 'Contraste'),
  'type'       => $L('Typography', 'Tipografia'),
  'layout'     => $L('Spacing & layout', 'Espaçamento e layout'),
  'components' => $L('Components', 'Componentes'),
  'motion'     => $L('Motion', 'Movimento'),
  'a11y'       => $L('Accessibility', 'Acessibilidade'),
  'colophon'   => $L('How it’s built', 'Como é feito'),
];
?>
<!DOCTYPE html>
<html lang="<?= $pt ? 'pt-BR' : 'en' ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $L('Design System — Paulo Antunes', 'Design System — Paulo Antunes') ?></title>
<meta name="description" content="<?= $e($L('The tokens, type, components and accessibility decisions behind pantunes.dev, rendered with the live site CSS.', 'Os tokens, a tipografia, os componentes e as decisões de acessibilidade do pantunes.dev, renderizados com o CSS real do site.')) ?>">
<link rel="alternate" hreflang="en" href="/design-system">
<link rel="alternate" hreflang="pt-BR" href="/pt/design-system">
<link rel="preload" href="/fonts/hanken-grotesk/hanken-grotesk-v12-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/fonts/space-grotesk/space-grotesk-v22-latin-700.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/fonts/space-mono/space-mono-v17-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<meta name="theme-color" content="#272822">
<link rel="stylesheet" href="/dist/main.css">
</head>
<body class="ds">

<a href="#ds-main" class="skip-link"><?= $L('Skip to content', 'Pular para o conteúdo') ?></a>

<header class="ds-header">
  <div class="ds-header-inner">
    <a href="<?= $home ?>" aria-label="<?= $e($L('Paulo Antunes — home', 'Paulo Antunes — início')) ?>">
      <img src="/img/paulo-antunes-white.svg" alt="Paulo Antunes" height="32">
    </a>
    <nav class="ds-header-links mono" aria-label="<?= $e($L('Design system', 'Design system')) ?>">
      <a href="<?= $home ?>">← <?= $L('back to site', 'voltar ao site') ?></a>
      <a href="<?= $other ?>" class="site-nav-lang" lang="<?= $pt ? 'en' : 'pt-BR' ?>"
         aria-label="<?= $pt ? 'EN, English version' : 'PT, versão em português' ?>"><?= $pt ? 'EN' : 'PT' ?></a>
    </nav>
  </div>
</header>

<div class="ds-layout">
  <aside class="ds-toc mono" aria-label="<?= $e($L('On this page', 'Nesta página')) ?>">
    <p class="ds-toc-title"><?= $L('On this page', 'Nesta página') ?></p>
    <ol>
      <?php foreach ($toc as $id => $label): ?><li><a href="#ds-<?= $id ?>"><?= $label ?></a></li><?php endforeach; ?>
    </ol>
    <a class="ds-toc-back" href="<?= $home ?>">← <?= $L('back to site', 'voltar ao site') ?></a>
  </aside>

  <main class="ds-main" id="ds-main">
    <div class="ds-intro">
      <p class="hero-eyebrow mono is-visible">// pantunes.dev · design system</p>
      <h1 class="ds-h1"><?= $L('Design System', 'Design System') ?><span class="cursor" aria-hidden="true">|</span></h1>
      <p class="ds-lede hg"><?= $L(
        'The tokens, type, components and accessibility decisions behind this site. Everything here is rendered with the same stylesheet the site uses, and the colors and contrast ratios are read straight from the source tokens at build time, so this page can’t drift from the real thing.',
        'Os tokens, a tipografia, os componentes e as decisões de acessibilidade deste site. Tudo aqui é renderizado com a mesma folha de estilo do site, e as cores e razões de contraste são lidas direto dos tokens do código na hora do build, então esta página não fica desatualizada.'
      ) ?></p>
      <p class="ds-note mono"><?= $L('Palette: Monokai · Stack: SCSS + custom properties · Target: WCAG 2.2 AA', 'Paleta: Monokai · Stack: SCSS + custom properties · Meta: WCAG 2.2 AA') ?></p>
    </div>

    <!-- ── Color ── -->
    <section class="ds-section" id="ds-color" aria-labelledby="ds-color-h">
      <h2 class="ds-h2" id="ds-color-h"><?= $toc['color'] ?></h2>
      <p class="ds-p hg"><?= $L('A dark Monokai base: warm near-black grounds, off-white text with hue-matched greys, and four saturated accents used sparingly. Green marks interaction and focus, cyan is the primary action, orange is secondary information.', 'Uma base Monokai escura: fundos quase pretos e quentes, texto quase branco com cinzas na mesma matiz, e quatro acentos saturados usados com moderação. Verde marca interação e foco, ciano é a ação principal, laranja é informação secundária.') ?></p>
      <?php foreach ($groups as $gname => $keys): ?>
      <h3 class="ds-h3"><?= $gname ?></h3>
      <ul class="ds-swatches">
        <?php foreach ($keys as $k): if (!isset($tokens[$k])) continue; $tk = $tokens[$k]; ?>
        <li class="ds-swatch">
          <span class="ds-chip" style="background: var(--<?= $k ?>)"></span>
          <code class="mono">--<?= $k ?></code>
          <span class="ds-hex mono"><?= $tk['hex'] ?></span>
          <?php if ($pt && $tk['note']): ?><span class="ds-swatch-note hg"><?= $e($tk['note']) ?></span><?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endforeach; ?>
    </section>

    <!-- ── Contrast ── -->
    <section class="ds-section" id="ds-contrast" aria-labelledby="ds-contrast-h">
      <h2 class="ds-h2" id="ds-contrast-h"><?= $toc['contrast'] ?></h2>
      <p class="ds-p hg"><?= $L('Every text token against every background token, computed with the WCAG 2.x relative-luminance formula. AA needs 4.5:1 for body text and 3:1 for large text (24px, or 18.7px bold). Pairs marked AA Large are only used for headings or UI accents, never for body copy.', 'Cada token de texto contra cada token de fundo, calculado com a fórmula de luminância relativa da WCAG 2.x. AA exige 4,5:1 para texto comum e 3:1 para texto grande (24px, ou 18,7px em negrito). Pares marcados como AA Large só são usados em títulos ou acentos de interface, nunca em texto corrido.') ?></p>

      <div class="ds-table-wrap" role="region" aria-label="<?= $e($L('Contrast matrix', 'Matriz de contraste')) ?>" tabindex="0">
        <table class="ds-table ds-matrix">
          <caption class="visually-hidden"><?= $L('Contrast ratio of each text token (rows) on each background token (columns)', 'Razão de contraste de cada token de texto (linhas) em cada token de fundo (colunas)') ?></caption>
          <thead>
            <tr>
              <th scope="col"><?= $L('Text', 'Texto') ?> \ <?= $L('Background', 'Fundo') ?></th>
              <?php foreach ($bgTokens as $bg): ?><th scope="col"><code>--<?= $bg ?></code></th><?php endforeach; ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($textTokens as $tx): ?>
            <tr>
              <th scope="row"><code>--<?= $tx ?></code></th>
              <?php foreach ($bgTokens as $bg): $r = ds_ratio($tokens[$tx]['hex'], $tokens[$bg]['hex']); [$g, $cls] = ds_grade($r); ?>
              <td style="background: var(--<?= $bg ?>)">
                <span class="ds-sample" style="color: var(--<?= $tx ?>)">Aa</span>
                <span class="ds-ratio mono"><?= ds_fmt($r, $pt) ?>:1</span>
                <span class="ds-badge mono <?= $cls ?>"><?= $g ?></span>
              </td>
              <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <h3 class="ds-h3"><?= $L('Component pairs', 'Pares de componentes') ?></h3>
      <div class="ds-table-wrap" role="region" aria-label="<?= $e($L('Component contrast pairs', 'Pares de contraste de componentes')) ?>" tabindex="0">
        <table class="ds-table">
          <thead>
            <tr>
              <th scope="col"><?= $L('Where', 'Onde') ?></th>
              <th scope="col"><?= $L('Text', 'Texto') ?></th>
              <th scope="col"><?= $L('Background', 'Fundo') ?></th>
              <th scope="col"><?= $L('Ratio', 'Razão') ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pairs as [$where, $fg, $bg]): if (!isset($tokens[$fg], $tokens[$bg])) continue; $r = ds_ratio($tokens[$fg]['hex'], $tokens[$bg]['hex']); [$g, $cls] = ds_grade($r); ?>
            <tr>
              <th scope="row"><?= $where ?></th>
              <td><code>--<?= $fg ?></code></td>
              <td><code>--<?= $bg ?></code></td>
              <td>
                <span class="ds-pill mono" style="color: var(--<?= $fg ?>); background: var(--<?= $bg ?>)">Aa</span>
                <span class="ds-ratio mono"><?= ds_fmt($r, $pt) ?>:1</span>
                <span class="ds-badge mono <?= $cls ?>"><?= $g ?></span>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- ── Typography ── -->
    <section class="ds-section" id="ds-type" aria-labelledby="ds-type-h">
      <h2 class="ds-h2" id="ds-type-h"><?= $toc['type'] ?></h2>
      <p class="ds-p hg"><?= $L('Three self-hosted families. Space Grotesk carries headings and UI, Hanken Grotesk carries long-form reading, and Space Mono carries labels, code and the terminal voice. Display sizes scale fluidly with clamp().', 'Três famílias hospedadas no próprio site. Space Grotesk leva títulos e interface, Hanken Grotesk leva a leitura de textos longos, e Space Mono leva rótulos, código e a voz de terminal. Os tamanhos grandes escalam com clamp().') ?></p>

      <div class="ds-type">
        <div class="ds-type-row">
          <div class="ds-type-meta mono">Display · <code>.hero-headline</code><br>Space Grotesk 700 · clamp(2rem, 4.5vw, 3.75rem) · 1.05</div>
          <p class="hero-headline"><?= $L('20 years building a web that works for <span class="accent">everyone</span>.', '20 anos construindo uma web que funciona para <span class="accent">todos</span>.') ?></p>
        </div>
        <div class="ds-type-row">
          <div class="ds-type-meta mono">Heading 2 · <code>.about-title</code><br>Space Grotesk 700 · clamp(2rem, 3.5vw, 2.5rem) · 1.08</div>
          <p class="about-title"><?= $L('About me', 'Sobre mim') ?></p>
        </div>
        <div class="ds-type-row">
          <div class="ds-type-meta mono">Heading 3 · <code>.skills-title</code><br>Space Grotesk 700 · 22px</div>
          <p class="skills-title is-visible"><?= $L('What I do', 'O que eu faço') ?></p>
        </div>
        <div class="ds-type-row">
          <div class="ds-type-meta mono">Lead · <code>.hero-sub</code><br>Hanken Grotesk 400 · clamp(16px, 1.8vw, 18px) · 1.55</div>
          <p class="hero-sub hg is-visible"><?= $L('Code, accessibility, tight deadlines, demanding clients — different facets of the same craft.', 'Código, acessibilidade, prazo apertado, cliente exigente — partes diferentes do mesmo ofício.') ?></p>
        </div>
        <div class="ds-type-row">
          <div class="ds-type-meta mono">Body · <code>.about-text</code><br>Hanken Grotesk 400 · clamp(14px, 1.65vw, 16.5px) · 1.62</div>
          <p class="about-text hg is-visible"><?= $L('// In 1999 the web became my playground; in 2006, my profession. Body copy stays near 65 characters per line for comfortable reading.', '// Em 1999 a web virou meu playground; em 2006, minha profissão. O texto corrido fica perto de 65 caracteres por linha para uma leitura confortável.') ?></p>
        </div>
        <div class="ds-type-row">
          <div class="ds-type-meta mono">Eyebrow · <code>.hero-eyebrow</code><br>Space Mono 400 · 13px · 0.08em</div>
          <p class="hero-eyebrow mono is-visible">// 20 years · web · html · css · js · a11y</p>
        </div>
        <div class="ds-type-row">
          <div class="ds-type-meta mono">Label · <code>.field-label</code><br>Space Mono · uppercase</div>
          <p class="field-label mono"><?= $L('EMAIL', 'E-MAIL') ?> <span class="required-mark" aria-hidden="true">*</span></p>
        </div>
      </div>
    </section>

    <!-- ── Layout ── -->
    <section class="ds-section" id="ds-layout" aria-labelledby="ds-layout-h">
      <h2 class="ds-h2" id="ds-layout-h"><?= $toc['layout'] ?></h2>
      <p class="ds-p hg"><?= $L('Content sits in a single 1280px column with a 20px gutter. Sibling groups are laid out with flex or grid and gap, never per-element margins. Section rhythm is fluid, and wide media (the hero video, the work band) caps at 1520px.', 'O conteúdo fica numa coluna única de 1280px com margem lateral de 20px. Grupos de elementos usam flex ou grid com gap, nunca margens por elemento. O ritmo entre seções é fluido, e mídias largas (o vídeo do hero, a faixa de trabalhos) param em 1520px.') ?></p>
      <div class="ds-table-wrap" role="region" aria-label="<?= $e($L('Layout values', 'Valores de layout')) ?>" tabindex="0">
        <table class="ds-table">
          <thead><tr><th scope="col"><?= $L('Value', 'Valor') ?></th><th scope="col"><?= $L('Use', 'Uso') ?></th></tr></thead>
          <tbody>
            <tr><th scope="row"><code>--max-w: 1280px</code></th><td><?= $L('Content column', 'Coluna de conteúdo') ?></td></tr>
            <tr><th scope="row"><code>--px: 20px</code></th><td><?= $L('Side gutter at every width', 'Margem lateral em qualquer largura') ?></td></tr>
            <tr><th scope="row"><code>1520px</code></th><td><?= $L('Max width of full-bleed media', 'Largura máxima de mídias de ponta a ponta') ?></td></tr>
            <tr><th scope="row"><code>clamp(50px, 10vw, 100px)</code></th><td><?= $L('Vertical section padding', 'Espaçamento vertical das seções') ?></td></tr>
            <tr><th scope="row"><code>gap: 16px · 24px · 80px</code></th><td><?= $L('Cards · stacks · two-column layouts', 'Cards · pilhas · layouts em duas colunas') ?></td></tr>
            <tr><th scope="row"><code>radius: 3px · 4px · 8px · 10px</code></th><td><?= $L('Inputs & tags · cards & chips · CTA · terminal window', 'Campos e etiquetas · cards e selos · CTA · janela do terminal') ?></td></tr>
            <tr><th scope="row"><code>500 · 560 · 700 · 860px</code></th><td><?= $L('Breakpoints: compact hero · skills grid collapses · hero stacks and nav hides · about stacks', 'Breakpoints: hero compacto · grid de skills recolhe · hero empilha e menu some · sobre empilha') ?></td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- ── Components ── -->
    <section class="ds-section" id="ds-components" aria-labelledby="ds-components-h">
      <h2 class="ds-h2" id="ds-components-h"><?= $toc['components'] ?></h2>
      <p class="ds-p hg"><?= $L('Live components with the site’s real classes. Hover them, or press Tab to walk through their focus states.', 'Componentes reais, com as classes do site. Passe o mouse ou use o Tab para ver os estados de foco.') ?></p>

      <h3 class="ds-h3"><?= $L('Buttons', 'Botões') ?></h3>
      <div class="ds-demo">
        <div class="ds-demo-row">
          <button type="button" class="btn-cta is-visible"><?= $L('Get to know me', 'Me conhecer melhor') ?> <span class="cta-arrow" aria-hidden="true">↓</span></button>
          <div class="ds-demo-fixed"><button type="button" class="btn-submit"><?= $L('Send message →', 'Enviar mensagem →') ?></button></div>
          <div class="ds-demo-fixed"><button type="button" class="btn-submit" disabled><?= $L('Sending…', 'Enviando…') ?></button></div>
        </div>
        <p class="ds-caption mono"><code>.btn-cta</code> · <code>.btn-submit</code> · <code>.btn-submit:disabled</code> — <?= $L('min-height 44px', 'altura mínima de 44px') ?></p>
      </div>

      <h3 class="ds-h3"><?= $L('Chips, tags & small controls', 'Selos, etiquetas e controles pequenos') ?></h3>
      <div class="ds-demo">
        <div class="ds-demo-row">
          <button class="agent-chip mono" type="button">
            <span class="agent-dot" aria-hidden="true"></span>
            <span class="agent-label">AGENT-READY</span>
            <span class="nav-sep" aria-hidden="true">·</span>
            <span><?= $L('connect via MCP', 'conectar via MCP') ?></span>
          </button>
          <span class="exp-tag"><?= $L('Own clients', 'Clientes próprios') ?></span>
          <span class="client-role mono">front-end</span>
          <a href="<?= $other ?>" class="site-nav-lang mono"><?= $pt ? 'EN' : 'PT' ?></a>
          <button class="clients-toggle mono" type="button" data-state="playing">
            <svg class="icon-pause" width="10" height="10" viewBox="0 0 10 10" fill="currentColor" aria-hidden="true"><rect x="1" y="1" width="3" height="8"/><rect x="6" y="1" width="3" height="8"/></svg>
            <span><?= $L('pause', 'pausar') ?></span>
          </button>
        </div>
        <p class="ds-caption mono"><code>.agent-chip</code> · <code>.exp-tag</code> · <code>.client-role</code> · <code>.site-nav-lang</code> · <code>.clients-toggle</code></p>
      </div>

      <h3 class="ds-h3"><?= $L('Form fields', 'Campos de formulário') ?></h3>
      <div class="ds-demo">
        <div class="ds-demo-grid">
          <div class="form-group">
            <label for="ds-f1" class="field-label mono"><?= $L('NAME', 'NOME') ?> <span class="required-mark" aria-hidden="true">*</span></label>
            <input type="text" id="ds-f1" placeholder="<?= $e($L('Your name', 'Seu nome')) ?>">
          </div>
          <div class="form-group">
            <label for="ds-f2" class="field-label mono"><?= $L('EMAIL', 'E-MAIL') ?> <span class="required-mark" aria-hidden="true">*</span></label>
            <input type="email" id="ds-f2" value="paulo@" aria-invalid="true" aria-describedby="ds-f2-hint">
            <span class="field-hint" id="ds-f2-hint"><?= $L('Please enter a valid email address.', 'Informe um e-mail válido.') ?></span>
          </div>
        </div>
        <p class="ds-caption mono"><code>.form-group</code> · <code>.field-label</code> · <code>[aria-invalid="true"]</code> + <code>.field-hint</code> — <?= $L('errors are announced with role="alert" on submit', 'os erros são anunciados com role="alert" no envio') ?></p>
      </div>

      <h3 class="ds-h3"><?= $L('Cards', 'Cards') ?></h3>
      <div class="ds-demo">
        <ul class="ds-demo-cards">
          <li class="skill-card is-visible" data-n="08">
            <span class="skill-num mono" aria-hidden="true">08</span>
            <div>
              <div class="skill-name">A11y auditing</div>
              <div class="skill-desc hg"><?= $L('Reports free of false-positive noise, prioritised by real impact.', 'Relatórios sem ruído de falso positivo, priorizados por impacto real.') ?></div>
            </div>
          </li>
          <li class="client-card is-own">
            <a href="https://sbar.com.br/" target="_blank" rel="noopener" aria-label="SBAR — <?= $L('direct client', 'cliente direto') ?> (<?= $L('opens in new tab', 'abre em nova aba') ?>)">
              <span class="client-domain mono">sbar.com.br</span>
              <span class="client-name">SBAR</span>
              <span class="client-roles"><span class="client-role mono"><?= $L('concept → deploy', 'conceito → deploy') ?></span></span>
              <span class="client-origin mono"><?= $L('direct client', 'cliente direto') ?></span>
            </a>
          </li>
          <li class="client-card is-taoti">
            <a href="https://www.tohowater.com/" target="_blank" rel="noopener" aria-label="Toho Water Authority — <?= $L('while at Taoti', 'na Taoti') ?> (<?= $L('opens in new tab', 'abre em nova aba') ?>)">
              <span class="client-domain mono">tohowater.com</span>
              <span class="client-name">Toho Water Authority</span>
              <span class="client-roles"><span class="client-role mono">tech lead</span><span class="client-role mono">front-end</span><span class="client-role mono">a11y</span></span>
              <span class="client-origin mono"><?= $L('while at Taoti', 'na Taoti') ?></span>
            </a>
          </li>
        </ul>
        <p class="ds-caption mono"><code>.skill-card</code> · <code>.client-card.is-own</code> · <code>.client-card.is-taoti</code> — <?= $L('origin is stated in text, not only color', 'a origem aparece em texto, não só pela cor') ?></p>
      </div>

      <h3 class="ds-h3"><?= $L('Terminal', 'Terminal') ?></h3>
      <div class="ds-demo">
        <div class="modal-box ds-static">
          <div class="sh-bar">
            <span class="sh-btn sh-btn--close" aria-hidden="true"></span>
            <span class="sh-btn sh-btn--min" aria-hidden="true"></span>
            <span class="sh-btn sh-btn--max" aria-hidden="true"></span>
            <span class="sh-title">paulo@antunes: ~</span>
          </div>
          <div class="sh-body ds-sh-body">
            <div class="sh-line"><span class="sh-g">$</span> <span class="sh-w">help</span></div>
            <div class="sh-line sh-g"><?= $L('Available commands:', 'Comandos disponíveis:') ?></div>
            <div class="sh-line sh-d">  about   → <?= $L('background', 'trajetória') ?></div>
            <div class="sh-line sh-d">  agent   → <?= $L('connect AI agents', 'conectar agentes de IA') ?></div>
            <div class="sh-line sh-o">  mcp     https://pantunes.dev/api/mcp</div>
            <div class="sh-line sh-c">  npx -y mcp-remote https://pantunes.dev/api/mcp</div>
          </div>
        </div>
        <p class="ds-caption mono"><code>.modal-box</code> · <code>.sh-bar</code> · <code>.sh-line</code> + <code>.sh-g</code> <code>.sh-c</code> <code>.sh-o</code> <code>.sh-d</code> <code>.sh-w</code> — <?= $L('role="dialog", aria-modal, Esc to close, focus returns to the trigger', 'role="dialog", aria-modal, Esc fecha e o foco volta ao botão que abriu') ?></p>
      </div>
    </section>

    <!-- ── Motion ── -->
    <section class="ds-section" id="ds-motion" aria-labelledby="ds-motion-h">
      <h2 class="ds-h2" id="ds-motion-h"><?= $toc['motion'] ?></h2>
      <p class="ds-p hg"><?= $L('Motion is short, eased out and tied to a moment: a heading types itself when its section arrives, content rises into place, and only two things loop. Every animation has a reduced-motion path.', 'O movimento é curto, com desaceleração, e ligado a um momento: o título se digita quando a seção chega, o conteúdo sobe para o lugar, e só duas coisas ficam em loop. Toda animação tem uma versão para movimento reduzido.') ?></p>
      <div class="ds-table-wrap" role="region" aria-label="<?= $e($L('Motion tokens', 'Tokens de movimento')) ?>" tabindex="0">
        <table class="ds-table">
          <thead><tr><th scope="col"><?= $L('Pattern', 'Padrão') ?></th><th scope="col"><?= $L('Timing', 'Tempo') ?></th><th scope="col"><?= $L('Reduced motion', 'Movimento reduzido') ?></th></tr></thead>
          <tbody>
            <tr><th scope="row"><?= $L('Heading typing', 'Digitação de título') ?></th><td class="mono">30–50ms / char (hero) · 55–85ms (sections)</td><td><?= $L('Full text shown at once', 'Texto inteiro de uma vez') ?></td></tr>
            <tr><th scope="row"><?= $L('Reveal', 'Reveal') ?></th><td class="mono">opacity + translateY(8px) · .5s ease · 150–300ms stagger</td><td><?= $L('Everything visible at load', 'Tudo visível ao carregar') ?></td></tr>
            <tr><th scope="row"><?= $L('Hover lift', 'Elevação no hover') ?></th><td class="mono">translateY(-5px) · .22s</td><td><?= $L('No lift', 'Sem elevação') ?></td></tr>
            <tr><th scope="row"><?= $L('Cursor blink', 'Cursor piscando') ?></th><td class="mono">1.1s step-end ∞</td><td>—</td></tr>
            <tr><th scope="row"><?= $L('Agent-ready pulse', 'Pulso do agent-ready') ?></th><td class="mono">2s ease-out ∞</td><td><?= $L('Static ring', 'Anel parado') ?></td></tr>
            <tr><th scope="row"><?= $L('Work band', 'Faixa de trabalhos') ?></th><td class="mono">60s linear ∞</td><td><?= $L('Starts paused; pause button always present', 'Começa pausada; botão de pausa sempre presente') ?></td></tr>
            <tr><th scope="row"><?= $L('Easing', 'Easing') ?></th><td class="mono"><code>--ease-out: cubic-bezier(0.25, 1, 0.5, 1)</code></td><td>—</td></tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- ── Accessibility ── -->
    <section class="ds-section" id="ds-a11y" aria-labelledby="ds-a11y-h">
      <h2 class="ds-h2" id="ds-a11y-h"><?= $toc['a11y'] ?></h2>
      <p class="ds-p hg"><?= $L('Accessibility is part of the system, not a pass at the end. These are the rules every component follows.', 'Acessibilidade faz parte do sistema, não é uma revisão no fim. Estas são as regras que todo componente segue.') ?></p>
      <ul class="ds-rules">
        <li><span class="ds-wcag mono">1.4.3</span><?= $L('Body text meets 4.5:1 on its background. The contrast matrix above is the source of truth.', 'Texto corrido atinge 4,5:1 sobre o fundo. A matriz de contraste acima é a referência.') ?></li>
        <li><span class="ds-wcag mono">1.4.1</span><?= $L('Color never carries meaning alone: card origin, form errors and required fields are also stated in text.', 'A cor nunca carrega significado sozinha: origem dos cards, erros de formulário e campos obrigatórios também aparecem em texto.') ?></li>
        <li><span class="ds-wcag mono">2.4.7</span><?= $L('Every interactive element has a visible focus ring, 2px, in the accent of its context.', 'Todo elemento interativo tem anel de foco visível, de 2px, na cor de destaque do contexto.') ?></li>
        <li><span class="ds-wcag mono">2.4.11</span><?= $L('Focused elements are never hidden: the work band stops and scrolls the focused card into view.', 'Elementos em foco nunca ficam escondidos: a faixa de trabalhos para e rola até mostrar o card focado.') ?></li>
        <li><span class="ds-wcag mono">2.2.2</span><?= $L('Anything that moves on its own for more than 5 seconds can be paused, and the choice is remembered.', 'Tudo que se move sozinho por mais de 5 segundos pode ser pausado, e a escolha fica salva.') ?></li>
        <li><span class="ds-wcag mono">2.3.3</span><?= $L('prefers-reduced-motion is respected by every animation.', 'prefers-reduced-motion é respeitado por toda animação.') ?></li>
        <li><span class="ds-wcag mono">2.5.8</span><?= $L('Primary controls are at least 44px tall; small controls are at least 32px.', 'Controles principais têm pelo menos 44px de altura; controles pequenos, pelo menos 32px.') ?></li>
        <li><span class="ds-wcag mono">4.1.2</span><?= $L('Native elements first: real buttons, links, labels and lists; ARIA only where HTML has no equivalent.', 'Elementos nativos primeiro: botões, links, labels e listas de verdade; ARIA só onde o HTML não tem equivalente.') ?></li>
      </ul>
    </section>
    <!-- ── Colophon ── -->
    <section class="ds-section" id="ds-colophon" aria-labelledby="ds-colophon-h">
      <h2 class="ds-h2" id="ds-colophon-h"><?= $toc['colophon'] ?></h2>
      <p class="ds-p hg"><?= $L('The stack behind this site, kept deliberately small.', 'A stack por trás deste site, pequena de propósito.') ?></p>
      <dl class="ds-colophon">
        <div><dt class="mono"><?= $L('Pages', 'Páginas') ?></dt><dd><?= $L('PHP templates rendered to static HTML at build time, in English and Portuguese.', 'Templates PHP renderizados como HTML estático no build, em inglês e português.') ?></dd></div>
        <div><dt class="mono"><?= $L('Styles', 'Estilos') ?></dt><dd><?= $L('SCSS with CSS custom properties. The same tokens drive this page.', 'SCSS com CSS custom properties. Os mesmos tokens alimentam esta página.') ?></dd></div>
        <div><dt class="mono">JavaScript</dt><dd><?= $L('Vanilla JS bundled with webpack. No framework.', 'JavaScript puro, empacotado com webpack. Sem framework.') ?></dd></div>
        <div><dt class="mono"><?= $L('Hosting', 'Hospedagem') ?></dt><dd><?= $L('Vercel, deployed on every push to GitHub, with a preview for each branch.', 'Vercel, com deploy a cada push no GitHub e um preview para cada branch.') ?></dd></div>
        <div><dt class="mono"><?= $L('Functions', 'Funções') ?></dt><dd><?= $L('The contact form and the MCP server run as Vercel Functions.', 'O formulário de contato e o servidor MCP rodam como Vercel Functions.') ?></dd></div>
        <div><dt class="mono"><?= $L('For agents', 'Para agentes') ?></dt><dd><?= $L('<a href="/llms.txt">llms.txt</a>, <a href="/resume.json">resume.json</a>, <a href="/AGENTS.md" hreflang="en">AGENTS.md</a> and an MCP server at <code>/api/mcp</code>.', '<a href="/llms.txt" hreflang="en">llms.txt</a>, <a href="/resume.json">resume.json</a>, <a href="/AGENTS.md" hreflang="en">AGENTS.md</a> e um servidor MCP em <code>/api/mcp</code>.') ?></dd></div>
        <div><dt class="mono"><?= $L('Security', 'Segurança') ?></dt><dd><?= $L('Strict Content Security Policy with no inline scripts, same-origin form endpoints, and rate limits.', 'Content Security Policy estrita, sem scripts inline, endpoints de formulário só para o próprio site e limites de envio.') ?></dd></div>
        <div><dt class="mono"><?= $L('Accessibility', 'Acessibilidade') ?></dt><dd><?= $L('Audited with axe-core against WCAG 2.2 AA, with contrast on gradients checked by hand.', 'Auditado com axe-core segundo a WCAG 2.2 AA, com o contraste sobre degradês verificado à mão.') ?></dd></div>
        <div><dt class="mono"><?= $L('Tools', 'Ferramentas') ?></dt><dd><?= $L('Built with Claude Code as a pair programmer.', 'Construído com o Claude Code como par de programação.') ?></dd></div>
      </dl>
    </section>
  </main>
</div>

<footer class="site-footer">
  <div class="site-footer-inner">
    <p class="contact-footer mono is-visible">© <?= date('Y') ?> paulo antunes · <?php if ($pt): ?><a href="/resume" hreflang="en">currículo (em inglês)</a><?php else: ?><a href="/resume">resume</a><?php endif; ?></p>
  </div>
</footer>

<script src="/dist/js/design-system.js" defer></script>

</body>
</html>
