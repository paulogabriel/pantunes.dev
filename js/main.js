// pantunes.dev — main.js
import { inject } from '@vercel/analytics';
import './site-data.js';
import './terminal.js';

inject();

// ── Input modality: .kbd-nav on <html> while the visitor uses the keyboard ──
(function () {
  const root = document.documentElement;
  document.addEventListener('keydown', e => {
    if (e.key === 'Tab') root.classList.add('kbd-nav');
  }, true);
  document.addEventListener('pointerdown', () => root.classList.remove('kbd-nav'), true);
})();

// ── Hero video fade-in ────────────────────────────────────────────────────────
(function () {
  const v = document.querySelector('.hero-video');
  if (!v) return;
  const show = () => v.classList.add('is-loaded');
  if (v.readyState >= 3) { requestAnimationFrame(show); }
  else { v.addEventListener('canplay', show, { once: true }); setTimeout(show, 2000); }
})();

// ── Hero entrance sequence ───────────────────────────────────────────────────
(function () {
  const eyebrow = document.querySelector('.hero-eyebrow');
  const headline = document.querySelector('.hero-headline');
  const sub      = document.querySelector('.hero-sub');
  const cta      = document.querySelector('.btn-cta');
  if (!eyebrow || !headline || !sub) return;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Reduced motion: show everything at once
  if (reduced) {
    eyebrow.classList.add('is-visible');
    sub.classList.add('is-visible');
    cta?.classList.add('is-visible');
    return;
  }

  // ── Step 2: type the H1 ──
  const cursor = headline.querySelector('.cursor');
  const lang  = (window.SITE && window.SITE.lang) || {};
  const parts = lang.heroParts || (() => {
    const n = new Date().getFullYear() - 2006;
    const l = n > 20 ? 'mais de 20' : n;
    return [
      { text: `${l} anos construindo uma web que funciona para `, cls: '' },
      { text: 'todos', cls: 'accent' },
      { text: '.', cls: '' },
    ];
  })();
  const chars = [];
  parts.forEach(p => [...p.text].forEach(ch => chars.push({ ch, cls: p.cls })));

  // Clear the visible text, keep the cursor
  while (headline.firstChild && headline.firstChild !== cursor) {
    headline.removeChild(headline.firstChild);
  }
  document.documentElement.classList.remove('hero-typing');

  function startTyping(onDone) {
    let idx = 0, currentSpan = null, currentCls = null;
    function type() {
      if (idx >= chars.length) { onDone(); return; }
      const { ch, cls } = chars[idx];
      if (cls !== currentCls) {
        const span = document.createElement('span');
        if (cls) span.className = cls;
        headline.insertBefore(span, cursor);
        currentSpan = span;
        currentCls  = cls;
      }
      currentSpan.textContent += ch;
      idx++;
      setTimeout(type, 30 + Math.random() * 20);
    }
    type();
  }

  // ── Step 1: reveal eyebrow → Step 2: typing → Step 3: fade in sub ──
  requestAnimationFrame(() => {
    eyebrow.classList.add('is-visible');           // 1. reveal eyebrow

    // wait for the eyebrow transition (500ms) + margin
    setTimeout(() => {
      startTyping(() => {                          // 2. type the H1
        setTimeout(() => {
          sub.classList.add('is-visible');                       // 3. fade sub
          setTimeout(() => cta?.classList.add('is-visible'), 500); // 4. reveal button
        }, 150);
      });
    }, 600);
  });
}());

// ── Porto Alegre local time, in the About subheader ─────────────────────────
(function () {
  const clock = document.querySelector('.about-clock');
  const time  = clock?.querySelector('.about-time');
  if (!clock || !time) return;

  const fmt = new Intl.DateTimeFormat('en-GB', { timeZone: 'America/Sao_Paulo', hour: '2-digit', minute: '2-digit', hour12: false });
  const tick = () => {
    const hm = fmt.format(new Date());
    time.textContent = hm;
    time.dateTime = hm;
  };
  tick();
  clock.hidden = false;
  // Updates without aria-live: the time is looked up, not announced every minute
  setInterval(tick, 30 * 1000);
}());

// ── About section animations ──────────────────────────────────────────────────
(function () {
  const section    = document.querySelector('#sec-about');
  const aboutTitle = document.querySelector('.about-title');
  const aboutText  = document.querySelector('.about-text');
  const aboutMeta  = document.querySelector('.about-meta');
  const expTitle   = document.querySelector('.exp-title');
  const expList    = document.querySelector('.exp-list');
  const skillTitle = document.querySelector('.skills-title');
  const cards      = [...document.querySelectorAll('.skill-card')];
  const aboutCta   = document.querySelector('.about-cta .btn-cta');
  if (!section || !aboutTitle) return;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function revealAll() {
    aboutText?.classList.add('is-visible');
    aboutMeta?.classList.add('is-visible');
    expTitle?.classList.add('is-visible');
    expList?.classList.add('is-visible');
    skillTitle?.classList.add('is-visible');
    cards.forEach(c => c.classList.add('is-visible'));
    aboutCta?.classList.add('is-visible');
  }

  if (reduced) { revealAll(); return; }

  // Type the title
  const cursor = aboutTitle.querySelector('.cursor');
  const aboutChars = [...((window.SITE && window.SITE.lang && window.SITE.lang.aboutTitle) || 'Sobre mim')];
  while (aboutTitle.firstChild && aboutTitle.firstChild !== cursor) {
    aboutTitle.removeChild(aboutTitle.firstChild);
  }

  function typeAbout(onDone) {
    let idx = 0;
    const textNode = document.createTextNode('');
    aboutTitle.insertBefore(textNode, cursor);
    function step() {
      if (idx >= aboutChars.length) { onDone(); return; }
      textNode.nodeValue += aboutChars[idx++];
      setTimeout(step, 55 + Math.random() * 30);
    }
    step();
  }

  let animated = false;
  const obs = new IntersectionObserver(entries => {
    if (!entries[0].isIntersecting || animated) return;
    animated = true;
    obs.disconnect();

    // 1. About me — typing
    typeAbout(() => {
      // 2. Bio — reveal
      setTimeout(() => {
        aboutMeta?.classList.add('is-visible');
        aboutText?.classList.add('is-visible');
        setTimeout(() => {
          expTitle?.classList.add('is-visible');
          expList?.classList.add('is-visible');
        }, 200);

        // 3. What I do — reveal
        setTimeout(() => {
          skillTitle?.classList.add('is-visible');

          // 4. Cards — stagger
          setTimeout(() => {
            cards.forEach((card, i) => {
              setTimeout(() => card.classList.add('is-visible'), i * 80);
            });
            setTimeout(() => aboutCta?.classList.add('is-visible'), cards.length * 80 + 150);
          }, 300);
        }, 350);
      }, 150);
    });
  }, { threshold: Math.min(0.3, (window.innerHeight * 0.25) / section.offsetHeight) });

  obs.observe(section);
}());

// ── Selected work reveal ──────────────────────────────────────────────────────
(function () {
  const section = document.querySelector('#sec-clients');
  if (!section) return;
  const els = section.querySelectorAll('.clients-controls, .clients-viewport');
  const show = () => els.forEach(el => el.classList.add('is-visible'));
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (reduced) show();
  else {
    const title  = section.querySelector('.clients-title');
    const cursor = title.querySelector('.cursor');
    const chars  = [...title.getAttribute('aria-label')];
    while (title.firstChild && title.firstChild !== cursor) title.removeChild(title.firstChild);
    const textNode = document.createTextNode('');
    title.insertBefore(textNode, cursor);

    const type = onDone => {
      let idx = 0;
      (function step() {
        if (idx >= chars.length) { onDone(); return; }
        textNode.nodeValue += chars[idx++];
        setTimeout(step, 55 + Math.random() * 30);
      }());
    };

    const obs = new IntersectionObserver(entries => {
      if (!entries[0].isIntersecting) return;
      obs.disconnect();
      type(() => setTimeout(show, 150));
    }, { threshold: 0.25 });
    obs.observe(section);
  }

  // ── Work band: pause (WCAG 2.2.2) and focus not obscured (2.4.11) ──
  const viewport = section.querySelector('.clients-viewport');
  const track    = section.querySelector('.clients-track');
  const btn      = section.querySelector('.clients-toggle');
  const KEY      = 'clientsPaused';

  section.classList.remove('no-js');
  btn.hidden = false;

  let stored = null;
  try { stored = localStorage.getItem(KEY); } catch (e) {}
  let userPaused = stored === null ? reduced : stored === '1';
  let focusInside = false;

  // Swap the animation for manual scrolling, keeping the visible position
  function enterManual() {
    if (viewport.classList.contains('is-manual')) return;
    const x = -new DOMMatrixReadOnly(getComputedStyle(track).transform).m41;
    viewport.classList.add('is-manual');
    viewport.tabIndex = 0;
    viewport.scrollLeft = x;
  }

  function exitManual() {
    if (!viewport.classList.contains('is-manual')) return;
    const half = track.scrollWidth / 2;
    const progress = (viewport.scrollLeft % half) / half;
    const duration = parseFloat(getComputedStyle(track).animationDuration) || 60;
    track.style.animationDelay = `${-progress * duration}s`;
    viewport.classList.remove('is-manual');
    viewport.removeAttribute('tabindex');
    viewport.scrollLeft = 0;
  }

  function sync() {
    (userPaused || focusInside) ? enterManual() : exitManual();
    btn.dataset.state = userPaused ? 'paused' : 'playing';
    btn.querySelector('.clients-toggle-text').textContent = userPaused ? btn.dataset.play : btn.dataset.pause;
    btn.setAttribute('aria-label', userPaused ? btn.dataset.playLabel : btn.dataset.pauseLabel);
  }

  btn.addEventListener('click', () => {
    userPaused = !userPaused;
    try { localStorage.setItem(KEY, userPaused ? '1' : '0'); } catch (e) {}
    sync();
  });

  viewport.addEventListener('focusin', e => {
    focusInside = true;
    sync();
    const card = e.target.closest('.client-card');
    if (card) card.scrollIntoView({ block: 'nearest', inline: 'nearest' });
  });

  viewport.addEventListener('focusout', e => {
    if (viewport.contains(e.relatedTarget)) return;
    focusInside = false;
    sync();
  });

  sync();
}());

// ── Built on a system: title typing + reveal ──────────────────────────────────
(function () {
  const section = document.querySelector('#sec-system');
  if (!section) return;
  const els  = section.querySelectorAll('.system-sub, .system-stat, .system-grid, .system-link');
  const show = () => els.forEach(el => el.classList.add('is-visible'));

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) { show(); return; }

  const title  = section.querySelector('.system-title');
  const cursor = title.querySelector('.cursor');
  const chars  = [...title.getAttribute('aria-label')];
  while (title.firstChild && title.firstChild !== cursor) title.removeChild(title.firstChild);
  const textNode = document.createTextNode('');
  title.insertBefore(textNode, cursor);

  const obs = new IntersectionObserver(entries => {
    if (!entries[0].isIntersecting) return;
    obs.disconnect();
    let idx = 0;
    (function step() {
      if (idx >= chars.length) { setTimeout(show, 150); return; }
      textNode.nodeValue += chars[idx++];
      setTimeout(step, 55 + Math.random() * 30);
    }());
  }, { threshold: 0.25 });
  obs.observe(section);
}());

// ── FAQ: title typing, reveal and copy-prompt button ──────────────────────────
(function () {
  const section = document.querySelector('#sec-faq');
  if (!section) return;

  const btn = section.querySelector('.faq-copy');
  if (btn) {
    const target = document.getElementById(btn.dataset.copyTarget);
    const status = section.querySelector('#faq-copy-status');
    const label  = btn.querySelector('.faq-copy-text');
    const idle   = label.textContent;
    let timer;
    btn.hidden = false;

    const report = (msg, state) => {
      label.textContent = msg;
      btn.dataset.state = state;
      status.textContent = '';
      requestAnimationFrame(() => { status.textContent = msg; });
      clearTimeout(timer);
      timer = setTimeout(() => { label.textContent = idle; delete btn.dataset.state; }, 3000);
    };

    btn.addEventListener('click', async () => {
      try {
        await navigator.clipboard.writeText(target.textContent.trim());
        report(btn.dataset.done, 'done');
      } catch (e) {
        // No clipboard access: select the text so it can be copied by hand
        const range = document.createRange();
        range.selectNodeContents(target);
        const sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
        target.focus();
        report(btn.dataset.fail, 'fail');
      }
    });
  }

  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  section.classList.add('is-animated');
  const els    = section.querySelectorAll('.faq-sub, .faq-list');
  const title  = section.querySelector('.faq-title');
  const cursor = title.querySelector('.cursor');
  const chars  = [...title.getAttribute('aria-label')];
  while (title.firstChild && title.firstChild !== cursor) title.removeChild(title.firstChild);
  const textNode = document.createTextNode('');
  title.insertBefore(textNode, cursor);

  const obs = new IntersectionObserver(entries => {
    if (!entries[0].isIntersecting) return;
    obs.disconnect();
    let idx = 0;
    (function step() {
      if (idx >= chars.length) { setTimeout(() => els.forEach(el => el.classList.add('is-visible')), 150); return; }
      textNode.nodeValue += chars[idx++];
      setTimeout(step, 55 + Math.random() * 30);
    }());
  }, { threshold: 0.2 });
  obs.observe(section);
}());

// ── Contact section animations ────────────────────────────────────────────────
(function () {
  const section      = document.querySelector('#sec-contact');
  const contactTitle = document.querySelector('.contact-title');
  const contactSub   = document.querySelector('.contact-sub');
  const card         = document.querySelector('.contact-card');
  const footer       = document.querySelector('.contact-footer');
  const icons        = [...document.querySelectorAll('.social-link')];
  if (!section || !contactTitle) return;

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function revealAll() {
    contactSub?.classList.add('is-visible');
    card?.classList.add('is-visible');
    document.querySelectorAll('.contact-photo').forEach(p => p.classList.add('is-visible'));
    icons.forEach(i => i.classList.add('is-visible'));
    footer?.classList.add('is-visible');
  }

  if (reduced) { revealAll(); return; }

  // Type the title
  const cursor = contactTitle.querySelector('.cursor');
  const chars  = [...((window.SITE && window.SITE.lang && window.SITE.lang.contactTitle) || 'Falar comigo')];
  while (contactTitle.firstChild && contactTitle.firstChild !== cursor) {
    contactTitle.removeChild(contactTitle.firstChild);
  }

  function typeContact(onDone) {
    let idx = 0;
    const textNode = document.createTextNode('');
    contactTitle.insertBefore(textNode, cursor);
    function step() {
      if (idx >= chars.length) { onDone(); return; }
      textNode.nodeValue += chars[idx++];
      setTimeout(step, 55 + Math.random() * 30);
    }
    step();
  }

  let animated = false;
  const obs = new IntersectionObserver(entries => {
    if (!entries[0].isIntersecting || animated) return;
    animated = true;
    obs.disconnect();

    // 1. Get in touch — typing
    typeContact(() => {
      // 2. Subtitle — reveal
      setTimeout(() => {
        contactSub?.classList.add('is-visible');

        // 3. Photos — 80ms stagger
        const photos = [...document.querySelectorAll('.contact-photo')];
        photos.forEach((p, i) => setTimeout(() => p.classList.add('is-visible'), 80 * i));

        // 4. Form — reveal
        setTimeout(() => {
          card?.classList.add('is-visible');

          // 5. Footer icons — stagger
          setTimeout(() => {
            icons.forEach((icon, i) => setTimeout(() => icon.classList.add('is-visible'), i * 80));
            setTimeout(() => footer?.classList.add('is-visible'), icons.length * 80 + 150);
          }, 350);
        }, 350);
      }, 150);
    });
  }, { threshold: 0.3 });

  obs.observe(section);
}());

// ── Anchor navigation ─────────────────────────────────────────────────────────

function scrollTo(target) {
  if (!target) return;
  target.scrollIntoView({ behavior: 'smooth' });
}

// Hero CTA button → first section
document.querySelectorAll('[data-scroll-to]').forEach(btn => {
  btn.addEventListener('click', () => scrollTo(document.querySelector(btn.dataset.scrollTo)));
});

// Nav anchor links (#sec-about, #sec-contact)
document.querySelectorAll('a[href^="#sec-"]').forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    scrollTo(document.querySelector(link.getAttribute('href')));
  });
});

// ── Contact form ──────────────────────────────────────────────────────────────

const form = document.querySelector('.contact-form');
if (form) {
  const btn = form.querySelector('.btn-submit');
  let submitted = false;

  const L = (window.SITE && window.SITE.lang) || {};
  const rules = {
    name(v)    { return v.length < 2 ? (L.ruleNameError  || 'Informe seu nome (mínimo 2 caracteres).') : null; },
    email(v)   { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) ? null : (L.ruleEmailError || 'Informe um e-mail válido.'); },
    message(v) { return v.length < 10 ? (L.ruleMsgError  || `Mensagem muito curta (${v.length}/10 caracteres mínimos).`).replace('{len}', v.length) : null; },
  };

  function setFieldError(field, msg) {
    const group = field.closest('.form-group') || field.parentElement;
    let hint = group.querySelector('.field-hint');
    if (msg) {
      field.setAttribute('aria-invalid', 'true');
      field.setAttribute('aria-describedby', field.id + '-hint');
      if (!hint) {
        hint = document.createElement('span');
        hint.className = 'field-hint';
        hint.id = field.id + '-hint';
        hint.setAttribute('role', 'alert');
        group.appendChild(hint);
      }
      hint.textContent = msg;
    } else {
      field.removeAttribute('aria-invalid');
      field.removeAttribute('aria-describedby');
      hint?.remove();
    }
  }

  function validateAll() {
    let firstInvalid = null;
    let valid = true;
    for (const [name, rule] of Object.entries(rules)) {
      const field = form.elements[name];
      if (!field) continue;
      const msg = rule(field.value.trim());
      setFieldError(field, msg);
      if (msg && !firstInvalid) firstInvalid = field;
      if (msg) valid = false;
    }
    if (firstInvalid) firstInvalid.focus();
    return valid;
  }

  for (const name of Object.keys(rules)) {
    const field = form.elements[name];
    if (!field) continue;
    field.addEventListener('blur',  () => { if (!submitted) return; setFieldError(field, rules[name](field.value.trim())); });
    field.addEventListener('input', () => { if (!submitted) return; setFieldError(field, rules[name](field.value.trim())); });
  }

  form.addEventListener('submit', async e => {
    e.preventDefault();
    if (btn.disabled) return;
    submitted = true;
    if (!validateAll()) return;

    const origText = btn.textContent;
    btn.disabled = true;
    btn.setAttribute('aria-busy', 'true');
    btn.textContent = L.formSending || 'Enviando…';
    clearBanner(form);

    try {
      const res  = await fetch(form.dataset.action, { method: 'POST', body: new URLSearchParams(new FormData(form)) });
      const data = await res.json();
      if (data.ok) {
        const ok = document.createElement('div');
        ok.className = 'form-success';
        ok.setAttribute('role', 'status');
        ok.textContent = L.formSuccess || 'Mensagem enviada! Respondo em breve.';
        form.replaceWith(ok);
      } else {
        showBanner(form, data.error || L.formError || 'Erro ao enviar. Tente novamente.');
        btn.textContent = origText;
        btn.disabled = false;
        btn.removeAttribute('aria-busy');
      }
    } catch {
      showBanner(form, L.formNetworkError || 'Erro de conexão. Verifique sua internet e tente novamente.');
      btn.textContent = origText;
      btn.disabled = false;
      btn.removeAttribute('aria-busy');
    }
  });
}

function showBanner(form, msg) {
  let el = form.querySelector('.form-error');
  if (!el) {
    el = document.createElement('p');
    el.className = 'form-error';
    el.setAttribute('role', 'alert');
    form.querySelector('.btn-submit').insertAdjacentElement('beforebegin', el);
  }
  el.textContent = msg;
}

function clearBanner(form) {
  form.querySelector('.form-error')?.remove();
}
