<section class="hero" id="sec-hero" aria-labelledby="hero-headline">
  <!-- nav -->
  <div class="site-nav">
    <div class="site-nav-inner">
      <a href="/" aria-label="Paulo Antunes — home">
        <img src="/img/paulo-antunes-white.svg" alt="Paulo Antunes" height="32">
      </a>
      <div class="site-nav-right">
        <nav class="site-nav-links mono" aria-label="Main navigation">
          <a href="#sec-about">about</a>
          <span aria-hidden="true" class="nav-sep">·</span>
          <a href="#sec-clients">work</a>
          <span aria-hidden="true" class="nav-sep">·</span>
          <a href="#sec-faq">faq</a>
          <span aria-hidden="true" class="nav-sep">·</span>
          <a href="#sec-contact">contact</a>
          <span aria-hidden="true" class="nav-sep">·</span>
          <button class="nav-terminal-btn mono" id="open-terminal" aria-label="help: open interactive terminal">&gt;help<span class="nav-caret" aria-hidden="true">|</span></button>
        </nav>
        <a href="/pt" class="site-nav-lang mono" lang="pt-BR" aria-label="PT, versão em português">PT</a>
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
        <p class="hero-eyebrow mono" aria-hidden="true">// <?= $label_anos ?> years · web · html · css · js · a11y</p>
        <h1 class="hero-headline" id="hero-headline"
            aria-label="<?= $label_anos ?> years building a web that works for everyone.">
          <?= $label_anos ?> years building a web that works for
          <span class="accent">everyone</span>.<span class="cursor" aria-hidden="true">|</span>
        </h1>
        <p class="hero-sub hg">Code, accessibility, tight deadlines, demanding clients — different facets of the same craft: understanding what needs to be solved, then solving it.</p>
        <button class="agent-chip mono" id="open-agent" type="button" aria-label="Agent-ready: show how AI agents connect via MCP">
          <span class="agent-dot" aria-hidden="true"></span>
          <span class="agent-label">AGENT-READY</span>
          <span class="nav-sep" aria-hidden="true">·</span>
          <span><span class="agent-long">connect via </span>MCP</span>
          <svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 9L9 3M4 3h5v5"/></svg>
        </button>
      </div>
    </div>

    <!-- CTA -->
    <div class="hero-cta">
      <button class="btn-cta" data-scroll-to="#sec-about" type="button">
        Get to know me <span class="cta-arrow" aria-hidden="true">↓</span>
      </button>
    </div>
  </div>

</section>
