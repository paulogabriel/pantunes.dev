<?php
$ano_atual    = (int) date('Y');
$anos_web     = $ano_atual - 2006;
$label_anos   = $anos_web > 20 ? 'mais de 20' : $anos_web;
$label_footer = $anos_web > 20 ? '20+ anos de web' : "{$anos_web} anos de web";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <?php require __DIR__ . '/partials/pt/head.php'; ?>
  <!-- Google tag (gtag.js) -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=G-2EZ35VK2T3"></script>
  <script src="/dist/js/analytics.js"></script>
  <script src="/dist/js/boot.js"></script>
</head>


<body>

<?php require __DIR__ . '/partials/header.php'; ?>

<div class="modal-ov" id="modal-terminal" role="dialog" aria-modal="true" aria-labelledby="sh-title">
  <div class="modal-box">
    <div class="sh-bar">
      <button class="sh-btn sh-btn--close" id="sh-close" aria-label="Fechar terminal"></button>
      <button class="sh-btn sh-btn--min"   id="sh-min"   aria-label="Minimizar terminal"></button>
      <button class="sh-btn sh-btn--max"   id="sh-max"   aria-label="Maximizar terminal"></button>
      <span class="sh-title" id="sh-title">paulo@antunes: ~</span>
    </div>
    <div class="sh-body" id="sh-out" role="log" aria-live="polite" tabindex="0" aria-label="Saída do terminal"></div>
    <div class="sh-p">
      <span class="sh-sym">$</span>
      <input class="sh-in" id="sh-in" type="text" placeholder="Digite um comando…" autocomplete="off" spellcheck="false" aria-label="Comando">
    </div>
  </div>
</div>

<div id="scroll-root">
  <main id="main-content">
    <?php require __DIR__ . '/partials/hero.php'; ?>
    <?php require __DIR__ . '/partials/about.php'; ?>
    <?php $clients_lang = 'pt'; require __DIR__ . '/partials/clients.php'; ?>
    <?php $system_lang = 'pt'; require __DIR__ . '/partials/system.php'; ?>
    <?php $faq_lang = 'pt'; require __DIR__ . '/partials/faq.php'; ?>
    <?php require __DIR__ . '/partials/contact.php'; ?>
  </main>

  <footer class="site-footer">
    <div class="site-footer-inner">
    <p class="contact-footer mono">© <?= $ano_atual ?> paulo antunes · <a href="/pt/design-system">design system</a></p>
    <div class="social-links">
      <a href="https://www.linkedin.com/in/paulogabriel" target="_blank" rel="noopener" aria-label="LinkedIn (abre em nova aba)" class="social-link">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
      </a>
      <a href="https://github.com/paulogabriel" target="_blank" rel="noopener" aria-label="GitHub (abre em nova aba)" class="social-link">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor" aria-hidden="true"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222 0 1.606-.014 2.898-.014 3.293 0 .322.216.694.825.576C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
      </a>
      <a href="mailto:paulo84@gmail.com" aria-label="E-mail" class="social-link">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2.5" y="4.5" width="19" height="15" rx="2.5"/><path d="M3 6l9 6.5L21 6"/></svg>
      </a>
    </div>
    </div>
  </footer>
</div>

<?php
$site_data = [
  'anosWeb'  => $anos_web,
  'anoAtual' => $ano_atual,
  'lang'     => [
    'heroParts' => [
      ['text' => "{$label_anos} anos construindo uma web que funciona para ", 'cls' => ''],
      ['text' => "todos", 'cls' => 'accent'],
      ['text' => ".", 'cls' => ''],
    ],
    'aboutTitle' => 'Sobre mim',
    'contactTitle' => 'Falar comigo',
    'formSending' => 'Enviando…',
    'formSuccess' => 'Mensagem enviada! Respondo em breve.',
    'formError' => 'Erro ao enviar. Tente novamente.',
    'formNetworkError' => 'Erro de conexão. Verifique sua internet e tente novamente.',
    'ruleNameError' => 'Informe seu nome (mínimo 2 caracteres).',
    'ruleEmailError' => 'Informe um e-mail válido.',
    'ruleMsgError' => 'Mensagem muito curta ({len}/10 caracteres mínimos).',
  ],
];
?>
<script type="application/json" id="site-data"><?= json_encode($site_data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
<script src="/dist/js/main.js"></script>
</body>
</html>
