<section class="hero" id="sec-hero" aria-labelledby="hero-headline">
  <!-- nav -->
  <div class="site-nav">
    <div class="site-nav-inner">
      <a href="/" aria-label="Paulo Antunes — início">
        <img src="/img/paulo-antunes-white.svg" alt="Paulo Antunes" height="32">
      </a>
      <div class="site-nav-right">
        <nav class="site-nav-links mono" aria-label="Navegação principal">
          <a href="#sec-about">sobre</a>
          <span aria-hidden="true" class="nav-sep">·</span>
          <a href="#sec-clients">trabalhos</a>
          <span aria-hidden="true" class="nav-sep">·</span>
          <a href="#sec-faq">faq</a>
          <span aria-hidden="true" class="nav-sep">·</span>
          <a href="#sec-contact">contato</a>
          <span aria-hidden="true" class="nav-sep">·</span>
          <button class="nav-terminal-btn mono" id="open-terminal" aria-label="help: abrir terminal interativo">&gt;help<span class="nav-caret" aria-hidden="true">|</span></button>
        </nav>
        <a href="/" class="site-nav-lang mono" lang="en" aria-label="EN, English version">EN</a>
      </div>
    </div>
  </div>

  <!-- main content -->
  <div class="hero-body">
    <video class="hero-video" autoplay loop muted playsinline aria-hidden="true">
      <source src="/img/video.mp4" type="video/mp4">
    </video>
    <div class="hero-grid">
      <div class="hero-copy">
        <p class="hero-eyebrow mono" aria-hidden="true">// desenvolvedor front-end sênior · acessibilidade web (WCAG 2.2 AA)</p>
        <h1 class="hero-headline" id="hero-headline"
            aria-label="<?= $label_anos ?> anos construindo uma web que funciona para todos.">
          <?= $label_anos ?> anos construindo uma web que funciona para
          <span class="accent">todos</span>.<span class="cursor" aria-hidden="true">|</span>
        </h1>
        <p class="hero-sub hg">Código, acessibilidade, prazo apertado, cliente exigente — partes diferentes do mesmo ofício: entender o que precisa ser resolvido e resolver.</p>
        <button class="agent-chip mono" id="open-agent" type="button" aria-label="Agent-ready: veja como agentes de IA se conectam via MCP">
          <span class="agent-dot" aria-hidden="true"></span>
          <span class="agent-label">AGENT-READY</span>
          <span class="nav-sep" aria-hidden="true">·</span>
          <span><span class="agent-long">conectar via </span>MCP</span>
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 9L9 3M4 3h5v5"/></svg>
        </button>
      </div>
    </div>

    <!-- CTA -->
    <div class="hero-cta">
      <button class="btn-cta" data-scroll-to="#sec-about" type="button">
        Me conhecer melhor <span class="cta-arrow" aria-hidden="true">↓</span>
      </button>
    </div>
  </div>
</section>
