import { inject } from '@vercel/analytics';

inject();

// Marks the section in the middle of the viewport in the table of contents
(function () {
  var links = [].slice.call(document.querySelectorAll('.ds-toc ol a'));
  var sections = links.map(function (a) { return document.querySelector(a.getAttribute('href')); });
  if (!('IntersectionObserver' in window) || sections.some(function (s) { return !s; })) return;

  var visible = new Set();
  function setActive(i) {
    links.forEach(function (a, j) {
      if (j === i) a.setAttribute('aria-current', 'true');
      else a.removeAttribute('aria-current');
    });
  }
  function update() {
    var atBottom = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 4;
    if (atBottom) return setActive(sections.length - 1);
    var i = sections.findIndex(function (s) { return visible.has(s); });
    setActive(i);
  }

  var obs = new IntersectionObserver(function (entries) {
    entries.forEach(function (e) { e.isIntersecting ? visible.add(e.target) : visible.delete(e.target); });
    update();
  }, { rootMargin: '-45% 0px -50% 0px' });
  sections.forEach(function (s) { obs.observe(s); });
  window.addEventListener('scroll', update, { passive: true });
}());
