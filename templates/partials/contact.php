<section class="contact" id="sec-contact" aria-labelledby="contact-title">
  <div class="contact-inner">
    <div class="contact-grid">

      <div class="contact-header">
        <h2 class="contact-title" id="contact-title" aria-label="Falar comigo">
          Falar comigo<span class="cursor" aria-hidden="true">|</span>
        </h2>
        <p class="contact-sub hg">// Conta o que você precisa resolver — respondo rápido.</p>
        <div class="contact-photos">
          <img src="/img/footer-paulo1.webp" alt="" class="contact-photo" aria-hidden="true">
          <img src="/img/footer-paulo2.webp" alt="" class="contact-photo" aria-hidden="true">
          <img src="/img/footer-paulo3.webp" alt="" class="contact-photo" aria-hidden="true">
          <img src="/img/footer-paulo4.webp" alt="" class="contact-photo" aria-hidden="true">
          <img src="/img/footer-paulo5.webp" alt="" class="contact-photo" aria-hidden="true">
        </div>
      </div>

      <div class="contact-card">
        <form class="contact-form" novalidate aria-label="Formulário de contato" data-action="/api/contato.php">

          <!-- honeypot: hidden via CSS, bots fill it, humans don't -->
          <div class="visually-hidden" aria-hidden="true">
            <label for="field-website">Website</label>
            <input type="text" id="field-website" name="website" tabindex="-1" autocomplete="off">
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="field-name" class="field-label mono">NOME <span class="required-mark" aria-hidden="true">*</span></label>
              <input type="text" id="field-name" name="name"
                placeholder="Seu nome" autocomplete="name" required>
            </div>
            <div class="form-group">
              <label for="field-email" class="field-label mono">E-MAIL <span class="required-mark" aria-hidden="true">*</span></label>
              <input type="email" id="field-email" name="email"
                placeholder="voce@email.com" autocomplete="email" required>
            </div>
          </div>

          <div class="form-group">
            <label for="field-message" class="field-label mono">MENSAGEM <span class="required-mark" aria-hidden="true">*</span></label>
            <textarea id="field-message" name="message"
              placeholder="No que posso ajudar?" required></textarea>
          </div>

          <button type="submit" class="btn-submit">Enviar mensagem →</button>

        </form>
      </div>

    </div>
  </div>
</section>
