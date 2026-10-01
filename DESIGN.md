# DESIGN.md — rules for working on this site

Read this before changing any template, style or script. It is written for people and for AI coding agents alike. The live reference is [pantunes.dev/design-system](https://pantunes.dev/design-system), rendered from the same code.

## Principles

1. **Accessible by default.** WCAG 2.2 AA is the floor, not a final pass. A change that breaks it is not done.
2. **Tokens, not values.** Every color comes from `sass/_0.tokens.scss`. The build fails otherwise (see [Checks](#checks)).
3. **Native HTML first.** Real `<button>`, `<a href>`, `<label>`, lists and headings. ARIA only where HTML has no equivalent.
4. **Small and static.** Static HTML from PHP templates, vanilla JS, no framework. Don't add a dependency for something CSS or a few lines of JS can do.
5. **Text stays text.** Nothing important lives only in an image, a color or an animation.

## Color tokens

Source of truth: `sass/_0.tokens.scss`. Use `var(--token)`; for transparency use `color-mix(in srgb, var(--token) 12%, transparent)`.

| Token | Use |
|---|---|
| `--ink` | Default text |
| `--ink-2`, `--ink-3` | Secondary and body text |
| `--ink-4`, `--ink-muted` | Meta text, labels, captions. `--ink-muted` is the lowest-contrast text allowed |
| `--white` | Hover and emphasis on dark backgrounds |
| `--green` / `--accent` | Accent text, cursor, focus rings, "agent-ready" |
| `--cyan` / `--accent-btn` | Links; the CTA button background, always with `--accent-ink` text |
| `--orange` | "while at Taoti" origin, secondary highlights |
| `--pink` | Error text, text selection. Lightened from Monokai to pass AA |
| `--red` | Error rings and borders only. **Never text** |
| `--bg-hero`, `--bg-about`, `--bg-contact`, `--bg-card`, `--bg-terminal` | Section and surface backgrounds |
| `--border`, `--border-2`, `--border-hov` | Hairlines, card borders, hover borders |
| `--black` | Shadows, overlays, masks — always mixed with transparent |
| `--chrome-*`, `--dot-*` | Terminal window bar, title, text and the three window dots only |
| `--bg-video` | Behind the hero video while it loads |

Contrast: every text token (`white`, `ink`…`ink-muted`, `green`, `cyan`, `orange`, `pink`) must pass 4.5:1 on every background token. The matrix on `/design-system` is computed from the SCSS at build time; if you add a text or background token, add it to `DS_TEXT` / `DS_BG` in `templates/partials/tokens.php` and check the matrix. Text over gradients, video or images is not measured automatically: check it by hand.

**Adding a token:** add it to `_0.tokens.scss` with a comment saying where it's used (the comment is shown on `/design-system`), list it in the right group in `templates/design-system.php`, add the Portuguese version of the comment to `$notesPt` in the same file, and only then use it.

## Type

Three self-hosted families (`fonts/`), set through tokens:

| Role | Family | Token |
|---|---|---|
| Headings, UI | Space Grotesk 700 | `--font-sans` |
| Reading | Hanken Grotesk 400 | `--font-hg` |
| Labels, code, terminal | Space Mono 400 | `--font-mono` |

Display and section headings scale with `clamp()`; body text stays near 65 characters per line. One `<h1>` per page, headings in order.

## Layout

- One content column: `--max-w: 1280px`, `--px: 20px` gutter at every width. Full-bleed media caps at 1520px.
- Lay out sibling groups with flex or grid and `gap`, not per-element margins.
- Breakpoints: 500 · 560 · 700 · 860 · 960px. No horizontal page scroll at 320px.
- Radius: 3px inputs and tags · 4px cards and chips · 8px CTA · 10px terminal window.

## Components

Live examples: `/design-system#ds-components`.

- **Buttons** (`.btn-cta`, `.btn-submit`): real `<button>`, min-height 44px, visible 2px focus ring. The cyan CTA is for the one primary action of a section.
- **Links**: underlined or clearly distinguished by more than color. External links say "(opens in new tab)" in their accessible name.
- **Accessible names**: when a control has an `aria-label`, it must contain the visible text (WCAG 2.5.3). `>help` → `help: open interactive terminal`; `PT` → `PT, versão em português`.
- **Form fields** (`.form-group`): a visible `<label for>`; errors set `aria-invalid="true"`, point to a hint with `aria-describedby` and are announced with `role="alert"` on submit. Required fields use the `required` attribute; the `*` in labels is `aria-hidden`, and a "* required" legend below the last field explains it.
- **Cards** (`.skill-card`, `.client-card`): list items. Card origin ("direct client", "while at Taoti") is written, never color-only.
- **Work band** (`.clients`): moves on its own, so it has a pause button (2.2.2), stops and scrolls a focused card into view (2.4.11), and stays still without JS.
- **FAQ**: `<details>`/`<summary>`; the `+`/`−` marker is CSS and survives Windows high contrast.
- **Terminal modal**: `role="dialog"`, `aria-modal`, focus moves in and returns on close, Esc closes. Output is inserted with `textContent`, never as HTML. The input's focus ring shows only in keyboard mode (`.kbd-nav`).
- **Showcase copies** (design system, "Built on a system"): wrap demo controls in `inert` so they leave the Tab order and the accessibility tree.

## Motion

Short, eased out (`--ease-out`), tied to a moment: a heading types when its section arrives, content rises 8px into place. Only the cursor, the agent-ready pulse and the work band loop.

Every animation has a `prefers-reduced-motion` path that shows the final state. Content hidden for an entrance must reappear if JS fails (see the H1: `boot.js` + CSS failsafe).

## Content

- Site copy exists in English (`/`) and Portuguese (`/pt`). Change both.
- Code comments are in English.
- Taoti client work is always framed as "while at Taoti" with the specific role.
- No phone number and no personal data beyond city, email and public profiles.

## Security

- CSP has no `unsafe-inline` for scripts: no inline `<script>` except JSON-LD and the `application/json` data block. Put JS in `js/` and bundle it.
- Security headers live in `vercel.json`; `router.php` mirrors them locally. Change both together.
- Form and MCP endpoints accept same-origin browser requests only (exact host list in `api/_lib/mail.js`), validate and cap every field, and escape user text in emails.
- Secrets come from environment variables. Never commit them; `config/mail.php` is gitignored.

## Checks

Run before opening a PR:

```bash
npm run build
```

It runs, in order:

1. `npm run lint:css` — Stylelint fails on hex, `rgb()`/`hsl()` or named colors outside `_0.tokens.scss`.
2. sass → webpack → `scripts/build-html.php` into `public/`.

Then check by hand, with `php -S localhost:3456 router.php`:

- **axe-core** (WCAG 2.2 AA + best practice) on `/`, `/pt`, `/design-system`, `/resume` and the 404: 0 violations.
- **Keyboard**: Tab through the page; every focus is visible and nothing is trapped except the open modal.
- **Reduced motion** on: everything readable at load.
- If you changed `resume.json`: `npm run resume` and commit `resume.pdf`.

## Workflow

Work on a branch and open a pull request to `main`. Vercel builds a preview for every PR; `main` deploys to production.
