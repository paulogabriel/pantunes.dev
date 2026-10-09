<?php
// FAQ for recruiters and agents. Expects $faq_lang = 'en' | 'pt'.
$pt = ($faq_lang ?? 'en') === 'pt';
$L  = fn(string $en, string $ptxt) => $pt ? $ptxt : $en;

$mcp    = 'https://pantunes.dev/api/mcp';
$agents = 'https://pantunes.dev/AGENTS.md';
$prompt = "Connect to the MCP server at $mcp and read Paulo Antunes's resume. "
        . "If he fits the role I describe below, send him an intro with the request_intro tool, using my name and email. "
        . "Role: [describe the role or project].";

// html: answer on the page · text: same answer without markup, for the JSON-LD
$faq = [
  [
    'q'    => $L('Are you available for new work?', 'Você está disponível para novos trabalhos?'),
    'html' => $L('Yes. I’m open to full-time, contract and freelance roles, working remotely.',
                 'Sim. Estou aberto a vagas full-time, contratos e freelas, em trabalho remoto.'),
  ],
  [
    'q'    => $L('Do you work in US time zones?', 'Você trabalha no fuso dos EUA?'),
    'html' => $L('Yes. Porto Alegre is UTC−3, one to two hours ahead of New York, so my working day overlaps fully with US Eastern hours and most of US Central.',
                 'Sim. Porto Alegre fica em UTC−3, de uma a duas horas à frente de Nova York, então meu dia de trabalho coincide totalmente com o horário da costa leste dos EUA e com boa parte do horário central.'),
  ],
  [
    'q'    => $L('Can you audit my site for accessibility?', 'Você faz auditoria de acessibilidade?'),
    'html' => $L('Yes. I audit against WCAG 2.2 AA, ADA and Section 508, combining automated tools (axe, WAVE, Lighthouse) with manual screen-reader testing (NVDA, VoiceOver), and deliver a remediation plan prioritised by real impact.',
                 'Sim. Faço auditorias com base na WCAG 2.2 AA, na ADA e na Section 508, combinando ferramentas automáticas (axe, WAVE, Lighthouse) com testes manuais em leitores de tela (NVDA, VoiceOver), e entrego um plano de correção priorizado pelo impacto real.'),
  ],
  [
    'q'    => $L('Can you invoice as a company?', 'Você emite nota como empresa?'),
    'html' => $L('Yes. Contracts and invoices go through my own Brazilian company, so the engagement is B2B.',
                 'Sim. Contratos e notas fiscais saem pela minha própria empresa, então a contratação é PJ.'),
  ],
  [
    'q'    => $L('Which CMSs do you work with?', 'Com quais CMSs você trabalha?'),
    'html' => $L('Drupal and WordPress, mostly on enterprise builds, including migrating legacy ACF fields to native Gutenberg blocks.',
                 'Drupal e WordPress, principalmente em projetos enterprise, incluindo a migração de campos ACF antigos para blocos nativos do Gutenberg.'),
  ],
  [
    'q'    => $L('Can your agent hire me?', 'Seu agente pode me contratar?'),
    'agent'=> true,
    'html' => $L(
      'Yes. This site runs an MCP server. Connect your AI assistant to <code>' . $mcp . '</code> and it can read my resume and send me an intro on your behalf. Setup and an example request are in <a href="/AGENTS.md" hreflang="en">AGENTS.md</a>.',
      'Sim. Este site tem um servidor MCP. Conecte seu assistente de IA a <code>' . $mcp . '</code> e ele consegue ler meu currículo e me enviar uma apresentação em seu nome. A configuração e um exemplo de pedido estão no <a href="/AGENTS.md" hreflang="en">AGENTS.md</a> (em inglês).'
    ),
  ],
];

$jsonld = [
  '@context'   => 'https://schema.org',
  '@type'      => 'FAQPage',
  'inLanguage' => $pt ? 'pt-BR' : 'en',
  'mainEntity' => array_map(fn($item) => [
    '@type' => 'Question',
    'name'  => $item['q'],
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => html_entity_decode(strip_tags($item['html']), ENT_QUOTES)],
  ], $faq),
];
$title = $L('Frequently asked questions', 'Perguntas frequentes');
?>
<section class="faq" id="sec-faq" aria-labelledby="faq-title">
  <div class="faq-inner">
    <div class="faq-head">
      <h2 class="faq-title" id="faq-title" aria-label="<?= $title ?>"><?= $title ?><span class="cursor" aria-hidden="true">|</span></h2>
      <p class="faq-sub hg"><?= $L('// Straight answers for recruiters, teams and their AI assistants.', '// Respostas diretas para recrutadores, equipes e seus assistentes de IA.') ?></p>
    </div>

    <div class="faq-list">
      <?php foreach ($faq as $i => $item): ?>
      <details class="faq-item" id="faq-<?= $i + 1 ?>">
        <summary class="faq-q"><span><?= $item['q'] ?></span></summary>
        <div class="faq-a hg">
          <p><?= $item['html'] ?></p>
          <?php if (!empty($item['agent'])): ?>
          <figure class="faq-prompt">
            <figcaption class="faq-prompt-label mono"><?= $L('Prompt for your assistant', 'Prompt para o seu assistente') ?></figcaption>
            <pre class="faq-prompt-text mono" id="faq-prompt-text" lang="en" tabindex="-1"><?= htmlspecialchars($prompt) ?></pre>
            <button type="button" class="faq-copy mono" hidden
                    data-copy-target="faq-prompt-text"
                    data-done="<?= $L('Copied', 'Copiado') ?>"
                    data-fail="<?= $L('Select the text and copy it', 'Selecione o texto e copie') ?>">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="4.5" y="4.5" width="8" height="8" rx="1.5"/><path d="M9.5 4.5v-2a1 1 0 0 0-1-1h-6a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2"/></svg>
              <span class="faq-copy-text"><?= $L('Copy prompt', 'Copiar prompt') ?></span>
            </button>
            <p class="visually-hidden" role="status" aria-live="polite" id="faq-copy-status"></p>
          </figure>
          <?php endif; ?>
        </div>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
  <script type="application/ld+json"><?= json_encode($jsonld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?></script>
</section>
