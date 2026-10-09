<section class="contact" id="sec-contact" aria-labelledby="contact-title">
  <div class="contact-inner">
    <div class="contact-grid">

      <div class="contact-header">
        <h2 class="contact-title" id="contact-title" aria-label="Get in touch">
          Get in touch<span class="cursor" aria-hidden="true">|</span>
        </h2>
        <p class="contact-sub hg">// Tell me what you need solved — I get back fast.</p>
        <div class="contact-photos">
          <img src="/img/footer-paulo1.webp" alt="" class="contact-photo" aria-hidden="true">
          <img src="/img/footer-paulo2.webp" alt="" class="contact-photo" aria-hidden="true">
          <img src="/img/footer-paulo3.webp" alt="" class="contact-photo" aria-hidden="true">
          <img src="/img/footer-paulo4.webp" alt="" class="contact-photo" aria-hidden="true">
          <img src="/img/footer-paulo5.webp" alt="" class="contact-photo" aria-hidden="true">
        </div>
      </div>

      <div class="contact-card">
        <form class="contact-form" novalidate aria-label="Contact form" data-action="/api/contato.php">

          <!-- honeypot: hidden via CSS, bots fill it, humans don't -->
          <div class="visually-hidden" aria-hidden="true">
            <label for="field-website">Website</label>
            <input type="text" id="field-website" name="website" tabindex="-1" autocomplete="off">
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="field-name" class="field-label mono">NAME <span class="required-mark" aria-hidden="true">*</span></label>
              <input type="text" id="field-name" name="name"
                placeholder="Your name" autocomplete="name" required>
            </div>
            <div class="form-group">
              <label for="field-email" class="field-label mono">EMAIL <span class="required-mark" aria-hidden="true">*</span></label>
              <input type="email" id="field-email" name="email"
                placeholder="you@email.com" autocomplete="email" required>
            </div>
          </div>

          <div class="form-group">
            <label for="field-message" class="field-label mono">MESSAGE <span class="required-mark" aria-hidden="true">*</span></label>
            <textarea id="field-message" name="message"
              placeholder="What can I help you with?" required></textarea>
          </div>
          <p class="form-legend mono"><span class="required-mark">*</span> required</p>

          <button type="submit" class="btn-submit">Send message →</button>

        </form>
      </div>

    </div>
  </div>
</section>
