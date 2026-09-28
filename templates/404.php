<?php // Static 404 page (Vercel serves public/404.html with a 404 status). Bilingual: the route's language can't be known. ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Page not found — Paulo Antunes</title>
<meta name="robots" content="noindex">
<link rel="preload" href="/fonts/space-grotesk/space-grotesk-v22-latin-700.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/fonts/space-mono/space-mono-v17-latin-regular.woff2" as="font" type="font/woff2" crossorigin>
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<meta name="theme-color" content="#272822">
<link rel="stylesheet" href="/dist/main.css">
</head>
<body class="nf">

<main class="nf-main">
  <a href="/" class="nf-logo"><img src="/img/paulo-antunes-white.svg" alt="Paulo Antunes — home" height="32"></a>

  <p class="nf-code mono" aria-hidden="true">404</p>
  <h1 class="nf-title">This page doesn’t exist.</h1>
  <p class="nf-sub" lang="pt-BR">Esta página não existe.</p>

  <pre class="nf-shell mono"><span class="sh-g">➜</span> <span class="sh-c">~</span> cd ./this-page
<span class="sh-d">cd: no such file or directory</span>
<span class="sh-g">➜</span> <span class="sh-c">~</span> <span class="nf-caret" aria-hidden="true"></span></pre>

  <nav class="nf-links" aria-label="Where to go">
    <a href="/" class="btn-cta is-visible">Back to home</a>
    <a href="/pt" lang="pt-BR" class="nf-link mono">Voltar ao início →</a>
    <a href="/design-system" class="nf-link mono">Design system →</a>
  </nav>
</main>

</body>
</html>
