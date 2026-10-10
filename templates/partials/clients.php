<?php
// "Selected work" band. Expects $clients_lang = 'pt' | 'en'.
$pt = ($clients_lang ?? 'en') === 'pt';

$t = [
  'title'  => $pt ? 'Trabalhos selecionados' : 'Selected work',
  'full'   => $pt ? 'conceito → deploy' : 'concept → deploy',
  'newtab' => $pt ? 'abre em nova aba' : 'opens in new tab',
  'aria'   => $pt ? 'Lista de sites de clientes' : 'Client sites list',
  'pause'  => $pt ? 'pausar' : 'pause',
  'play'   => $pt ? 'retomar' : 'play',
  'pauseL' => $pt ? 'Pausar a rolagem dos trabalhos' : 'Pause the scrolling work list',
  'playL'  => $pt ? 'Retomar a rolagem dos trabalhos' : 'Resume the scrolling work list',
];

$own = fn($name, $domain) => ['name' => $name, 'domain' => $domain, 'roles' => [$t['full']]];

$clients = [
  $own('SBAR', 'sbar.com.br'),
  $own('Fudgetry', 'fudgetry.com'),
  $own('Electric Service', 'electricservice.com.br'),
  $own('Cia Vital', 'ciavital.com.br'),
  $own('Barão Seguros', 'baraoseguros.com.br'),
];

// The band loops by sliding one copy of the list; a short list is repeated so one copy is wider than the viewport.
// Only the first pass is exposed to assistive tech and the Tab order.
$copy  = count($clients) < 8 ? array_merge($clients, $clients) : $clients;
$items = [];
foreach ([0, 1] as $set) foreach ($copy as $i => $c) $items[] = [$c, $set > 0 || $i >= count($clients)];
?>
<section class="clients no-js" id="sec-clients" aria-labelledby="clients-title">
  <div class="clients-head">
    <h2 class="clients-title" id="clients-title" aria-label="<?= $t['title'] ?>"><?= $t['title'] ?><span class="cursor" aria-hidden="true">|</span></h2>
    <div class="clients-controls">
      <button class="clients-toggle mono" type="button" hidden
              data-state="playing"
              data-pause="<?= $t['pause'] ?>" data-play="<?= $t['play'] ?>"
              data-pause-label="<?= $t['pauseL'] ?>" data-play-label="<?= $t['playL'] ?>"
              aria-label="<?= $t['pauseL'] ?>">
        <svg class="icon-pause" width="10" height="10" viewBox="0 0 10 10" fill="currentColor" aria-hidden="true"><rect x="1" y="1" width="3" height="8"/><rect x="6" y="1" width="3" height="8"/></svg>
        <svg class="icon-play" width="10" height="10" viewBox="0 0 10 10" fill="currentColor" aria-hidden="true"><path d="M2 1l7 4-7 4z"/></svg>
        <span class="clients-toggle-text"><?= $t['pause'] ?></span>
      </button>
    </div>
  </div>
  <div class="clients-viewport" role="region" aria-label="<?= $t['aria'] ?>">
    <ul class="clients-track">
      <?php foreach ($items as [$c, $dup]): ?>
      <li class="client-card"<?= $dup ? ' aria-hidden="true"' : '' ?>>
        <a href="https://<?= $c['domain'] ?>/" target="_blank" rel="noopener"<?= $dup ? ' tabindex="-1"' : '' ?>
           aria-label="<?= htmlspecialchars($c['name']) ?> (<?= $t['newtab'] ?>)">
          <span class="client-domain mono"><?= $c['domain'] ?></span>
          <span class="client-name"><?= htmlspecialchars($c['name']) ?></span>
          <span class="client-roles">
            <?php foreach ($c['roles'] as $r): ?><span class="client-role mono"><?= $r ?></span><?php endforeach; ?>
          </span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
