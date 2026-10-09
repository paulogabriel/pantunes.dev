// Runs in <head>, before first paint: flags that the H1 will be typed,
// so CSS can hide it and the static text doesn't flash before typing starts.
if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  document.documentElement.classList.add('hero-typing');
}
