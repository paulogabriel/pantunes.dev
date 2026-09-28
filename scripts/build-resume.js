// Prints /resume to resume.pdf (tagged, Letter). Run locally with `npm run resume` after editing resume.json,
// then commit resume.pdf: Vercel's build has no browser, so the PDF is generated here and shipped as a file.

const { spawn } = require('child_process');
const path = require('path');
const { chromium } = require('playwright-core');

const root = path.resolve(__dirname, '..');
const port = 3499;

const wait = ms => new Promise(r => setTimeout(r, ms));

(async () => {
  const server = spawn('php', ['-S', `127.0.0.1:${port}`, 'router.php'], { cwd: root, stdio: 'ignore' });
  let browser;
  try {
    await wait(600);
    browser = await chromium.launch();
    const page = await browser.newPage();
    await page.goto(`http://127.0.0.1:${port}/resume`, { waitUntil: 'networkidle' });
    await page.evaluate(() => document.fonts.ready);
    await page.pdf({
      path: path.join(root, 'resume.pdf'),
      format: 'Letter',
      preferCSSPageSize: true,
      printBackground: true,
      tagged: true,
      outline: true,
    });
    console.log('✓ resume.pdf');
  } catch (err) {
    console.error('Could not build resume.pdf:', err.message);
    console.error('If Chromium is missing, run: npx playwright-core install chromium');
    process.exitCode = 1;
  } finally {
    await browser?.close();
    server.kill();
  }
})();
