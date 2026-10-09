// Page copy and data, delivered by PHP in a JSON block (not executable)
const el = document.getElementById('site-data');
window.SITE = el ? JSON.parse(el.textContent) : {};
