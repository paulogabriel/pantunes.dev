# pantunes.dev

Source of [pantunes.dev](https://pantunes.dev), the portfolio of Paulo Antunes, a senior front-end developer in Porto Alegre, Brazil.

- **Static HTML from PHP templates.** `scripts/build-html.php` renders `templates/` into `public/`, one file per route (EN, `/pt`, `/design-system`, 404).
- **Styles:** SCSS with CSS custom properties (`sass/`). Color tokens are parsed at build time to compute the WCAG contrast matrix shown on the [design system](https://pantunes.dev/design-system).
- **Scripts:** vanilla JS bundled with webpack (`js/`). No inline scripts, so the CSP needs no `unsafe-inline`.
- **Vercel Functions** (`api/*.js`): the contact form and an [MCP server](https://pantunes.dev/AGENTS.md) that lets AI assistants read the resume and send an intro.
- **Agent-readable files:** `llms.txt`, `resume.json`, `AGENTS.md`.

## Working on the code

Read [DESIGN.md](DESIGN.md) first: tokens, components, accessibility rules and the checks every change must pass.

## Run locally

Requires PHP 8.2+ and Node 18+.

```bash
npm install
npm run build
php -S localhost:3456 router.php
```

`router.php` serves the PHP templates directly and sends the same security headers as `vercel.json`. The local contact form uses PHPMailer: run `composer install` and copy `config/mail.example.php` to `config/mail.php` with your own SMTP credentials (it is gitignored).

## Deploy

Vercel builds with `npm run build` and serves `public/`. The functions read `GMAIL_USER`, `GMAIL_PASS`, `MAIL_TO` (optional) and `GA_API_SECRET` (optional, MCP usage metrics) from environment variables.

## License

Code is MIT. The site's written content, images, logos and identity are not; see [LICENSE](LICENSE).
