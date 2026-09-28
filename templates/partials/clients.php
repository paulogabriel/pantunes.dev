<?php
// "Selected work" band. Expects $clients_lang = 'pt' | 'en'.
$pt = ($clients_lang ?? 'en') === 'pt';

$t = [
  'title'  => $pt ? 'Trabalhos selecionados' : 'Selected work',
  'own'    => $pt ? 'cliente direto' : 'direct client',
  'taoti'  => $pt ? 'na Taoti' : 'while at Taoti',
  'full'   => $pt ? 'conceito → deploy' : 'concept → deploy',
  'newtab' => $pt ? 'abre em nova aba' : 'opens in new tab',
  'aria'   => $pt ? 'Lista de sites de clientes' : 'Client sites list',
  'pause'  => $pt ? 'pausar' : 'pause',
  'play'   => $pt ? 'retomar' : 'play',
  'pauseL' => $pt ? 'Pausar a rolagem dos trabalhos' : 'Pause the scrolling work list',
  'playL'  => $pt ? 'Retomar a rolagem dos trabalhos' : 'Resume the scrolling work list',
];

$own   = fn($name, $domain) => ['name' => $name, 'domain' => $domain, 'roles' => [$t['full']], 'taoti' => false];
$taoti = fn($name, $domain, $roles) => ['name' => $name, 'domain' => $domain, 'roles' => $roles, 'taoti' => true];

$clients = [
  $taoti('Toho Water Authority', 'tohowater.com', ['tech lead', 'front-end', 'a11y']),
  $own('SBAR', 'sbar.com.br'),
  $taoti('Howard County, MD', 'howardcountymd.gov', ['front-end', 'a11y']),
  $own('Fudgetry', 'fudgetry.com'),
  $taoti('The German Marshall Fund', 'gmfus.org', ['front-end']),
  $own('Electric Service', 'electricservice.com.br'),
  $taoti("International Women's Forum", 'iwforum.org', ['front-end']),
  $own('Cia Vital', 'ciavital.com.br'),
  $taoti('IAAPA', 'iaapa.org', ['front-end']),
  $own('Barão Seguros', 'baraoseguros.com.br'),
  $taoti('APTA', 'apta.com', ['front-end']),
];
?>
<section class="clients no-js" id="sec-clients" aria-labelledby="clients-title">
  <div class="clients-head">
    <h2 class="clients-title" id="clients-title" aria-label="<?= $t['title'] ?>"><?= $t['title'] ?><span class="cursor" aria-hidden="true">|</span></h2>
    <div class="clients-controls">
      <p class="clients-legend mono">
        <span class="is-own"><?= $t['own'] ?></span>
        <span class="is-taoti"><?= $t['taoti'] ?></span>
      </p>
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
      <?php foreach ([false, true] as $dup): foreach ($clients as $c): ?>
      <li class="client-card <?= $c['taoti'] ? 'is-taoti' : 'is-own' ?>"<?= $dup ? ' aria-hidden="true"' : '' ?>>
        <a href="https://<?= $c['domain'] ?>/" target="_blank" rel="noopener"<?= $dup ? ' tabindex="-1"' : '' ?>
           aria-label="<?= htmlspecialchars($c['name']) ?> — <?= $c['taoti'] ? $t['taoti'] : $t['own'] ?> (<?= $t['newtab'] ?>)">
          <span class="client-domain mono"><?= $c['domain'] ?></span>
          <span class="client-name"><?= htmlspecialchars($c['name']) ?></span>
          <span class="client-roles">
            <?php foreach ($c['roles'] as $r): ?><span class="client-role mono"><?= $r ?></span><?php endforeach; ?>
          </span>
          <span class="client-origin mono"><?= $c['taoti'] ? $t['taoti'] : $t['own'] ?></span>
        </a>
      </li>
      <?php endforeach; endforeach; ?>
    </ul>
  </div>
</section>
