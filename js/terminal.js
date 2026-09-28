// ── Modal Terminal ──

(function () {
  const modal    = document.getElementById('modal-terminal');
  const openBtn  = document.getElementById('open-terminal');
  const closeBtn = document.getElementById('sh-close');
  const minBtn   = document.getElementById('sh-min');
  const maxBtn   = document.getElementById('sh-max');
  const out      = document.getElementById('sh-out');
  const inp      = document.getElementById('sh-in');
  if (!modal || !openBtn || !inp) return;

  let booted = false;
  let state  = 'closed';

  const L = (window.SITE && window.SITE.lang) || {};
  const isEN = L.aboutTitle === 'About me';

  function setState(next) {
    state = next;
    modal.classList.toggle('open',      next !== 'closed');
    modal.classList.toggle('minimized', next === 'minimized');
    modal.classList.toggle('maximized', next === 'maximized');
    if (next === 'open' || next === 'maximized') inp.focus();
  }

  function open(e, cmd = 'help') {
    if (e) e.preventDefault();
    setState('open');
    if (!booted || cmd !== 'help') { booted = true; setTimeout(() => run(cmd), 300); }
  }

  function close() {
    if (state === 'minimized') {
      modal.style.transition = 'none';
      modal.querySelector('.modal-box').style.transition = 'none';
      setState('closed');
      requestAnimationFrame(() => {
        modal.style.transition = '';
        modal.querySelector('.modal-box').style.transition = '';
      });
    } else {
      setState('closed');
    }
    openBtn.focus();
  }

  function toggleMin() { setState(state === 'minimized' ? 'open' : 'minimized'); }
  function toggleMax() { setState(state === 'maximized' ? 'open' : 'maximized'); }

  openBtn.addEventListener('click', e => open(e));
  document.getElementById('open-agent')?.addEventListener('click', e => open(e, 'agent'));
  closeBtn.addEventListener('click', close);
  minBtn.addEventListener('click', toggleMin);
  maxBtn.addEventListener('click', toggleMax);
  modal.querySelector('.sh-bar').addEventListener('click', e => {
    if (state === 'minimized' && !e.target.closest('button')) setState('open');
  });
  modal.addEventListener('click', e => {
    if (e.target === modal && state !== 'minimized') close();
  });
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && state !== 'closed') {
      state === 'minimized' ? setState('open') : close();
    }
  });

  const CMDS_PT = {
    help:    () => [{c:'g',v:'Comandos disponíveis:'},{c:'d',v:'  about   → trajetória'},{c:'d',v:'  skills  → habilidades'},{c:'d',v:'  exp     → histórico'},{c:'d',v:'  contact → contato'},{c:'d',v:'  agent   → conectar agentes de IA'},{c:'d',v:'  clear   → limpar'}],
    agent:   () => [{c:'g',v:'Este site conversa com agentes de IA.'},{c:'d',v:'────────────────────────────────────────'},{c:'o',v:'  mcp     https://pantunes.dev/api/mcp'},{c:'',v:'  tools   get_resume · get_skills · get_availability · get_contact · request_intro'},{c:'o',v:'  llms    https://pantunes.dev/llms.txt'},{c:'o',v:'  resume  https://pantunes.dev/resume.json'},{c:'o',v:'  guide   https://pantunes.dev/AGENTS.md'},{c:'',v:' '},{c:'d',v:'  Claude Desktop, Cursor ou qualquer cliente MCP:'},{c:'c',v:'  npx -y mcp-remote https://pantunes.dev/api/mcp'},{c:'',v:' '},{c:'d',v:'  Ou peça ao seu agente:'},{c:'w',v:'  "Leia pantunes.dev e diga se o Paulo serve para esta vaga."'}],
    about:   () => [{c:'w',v:'Paulo Antunes — Front-end Developer'},{c:'d',v:'──────────────────────────────────'},{c:'',v:'1999 · web virou playground'},{c:'',v:'2006 · virou profissão'},{c:'g',v:'2026 · 20 anos depois, ainda de pé.'}],
    skills:  () => [{c:'g',v:'Principais habilidades:'},{c:'o',v:'  HTML + CSS      ████████████ 100%'},{c:'o',v:'  JS ES6+         █████████░░░ 88%'},{c:'o',v:'  Tailwind        ████████░░░░ 84%'},{c:'o',v:'  Drupal / WP     ██████████░░ 93%'},{c:'o',v:'  Acessibilidade  ███████████░ 95%'},{c:'o',v:'  Design → Dev   ████████████ 100%'}],
    exp:     () => [{c:'g',v:'Histórico (cronologia inversa):'},{c:'d',v:'────────────────────────────────'},{c:'o',v:'2006–hoje  Freelancer [Clientes próprios]'},{c:'',v:'2018–2026  Taoti.com [Sr FE Dev]'},{c:'',v:'2017       Crossover.com [Visual Design Eng]'},{c:'',v:'2012–2016  Printi.com.br [UX/UI + FE]'},{c:'',v:'2008–2019  Abduzeedo.com [Escritor]'},{c:'',v:'2006–2012  Zee.com.br [UX/UI + FE]'}],
    contact: () => [{c:'g',v:'Contato:'},{c:'',v:'  email     paulo84@gmail.com'},{c:'',v:'  linkedin  https://www.linkedin.com/in/paulogabriel/'},{c:'',v:'  local     Porto Alegre, BR'},{c:'g',v:'  status    disponível'}],
    clear:   () => { out.innerHTML = ''; return []; },
  };

  const CMDS_EN = {
    help:    () => [{c:'g',v:'Available commands:'},{c:'d',v:'  about   → background'},{c:'d',v:'  skills  → skill set'},{c:'d',v:'  exp     → work history'},{c:'d',v:'  contact → get in touch'},{c:'d',v:'  agent   → connect AI agents'},{c:'d',v:'  clear   → clear screen'}],
    agent:   () => [{c:'g',v:'This site speaks to AI agents.'},{c:'d',v:'────────────────────────────────────────'},{c:'o',v:'  mcp     https://pantunes.dev/api/mcp'},{c:'',v:'  tools   get_resume · get_skills · get_availability · get_contact · request_intro'},{c:'o',v:'  llms    https://pantunes.dev/llms.txt'},{c:'o',v:'  resume  https://pantunes.dev/resume.json'},{c:'o',v:'  guide   https://pantunes.dev/AGENTS.md'},{c:'',v:' '},{c:'d',v:'  Claude Desktop, Cursor or any MCP client:'},{c:'c',v:'  npx -y mcp-remote https://pantunes.dev/api/mcp'},{c:'',v:' '},{c:'d',v:'  Or just ask your agent:'},{c:'w',v:'  "Read pantunes.dev and tell me if Paulo fits this role."'}],
    about:   () => [{c:'w',v:'Paulo Antunes — Front-end Developer'},{c:'d',v:'──────────────────────────────────'},{c:'',v:'1999 · web became playground'},{c:'',v:'2006 · became profession'},{c:'g',v:'2026 · 20 years in, still standing.'}],
    skills:  () => [{c:'g',v:'Top skills:'},{c:'o',v:'  HTML + CSS      ████████████ 100%'},{c:'o',v:'  JS ES6+         █████████░░░ 88%'},{c:'o',v:'  Tailwind        ████████░░░░ 84%'},{c:'o',v:'  Drupal / WP     ██████████░░ 93%'},{c:'o',v:'  Accessibility   ███████████░ 95%'},{c:'o',v:'  Design → Dev   ████████████ 100%'}],
    exp:     () => [{c:'g',v:'Work history (reverse):'},{c:'d',v:'────────────────────────────────'},{c:'o',v:'2006–now   Freelancer [Own clients]'},{c:'',v:'2018–2026  Taoti.com [Sr FE Dev]'},{c:'',v:'2017       Crossover.com [Visual Design Eng]'},{c:'',v:'2012–2016  Printi.com.br [UX/UI + FE]'},{c:'',v:'2008–2019  Abduzeedo.com [Writer]'},{c:'',v:'2006–2012  Zee.com.br [UX/UI + FE]'}],
    contact: () => [{c:'g',v:'Contact:'},{c:'',v:'  email     paulo84@gmail.com'},{c:'',v:'  linkedin  https://www.linkedin.com/in/paulogabriel/'},{c:'',v:'  based     Porto Alegre, BR'},{c:'g',v:'  status    available'}],
    clear:   () => { out.innerHTML = ''; return []; },
  };

  const CMDS = isEN ? CMDS_EN : CMDS_PT;
  const CLS  = { g: 'sh-g', c: 'sh-c', o: 'sh-o', d: 'sh-d', w: 'sh-w', '': '' };
  const NOT_FOUND_PT = cmd => [{c:'d',v:`comando não encontrado: ${cmd}`},{c:'d',v:'Digite help para ver opções.'}];
  const NOT_FOUND_EN = cmd => [{c:'d',v:`command not found: ${cmd}`},{c:'d',v:'Type help to see options.'}];
  const notFound = isEN ? NOT_FOUND_EN : NOT_FOUND_PT;

  function run(raw) {
    const cmd = raw.trim().toLowerCase();
    const echo   = document.createElement('div');
    const line   = document.createElement('span');
    const prompt = document.createElement('span');
    const typed  = document.createElement('span');
    line.className   = 'sh-line';
    prompt.className = 'sh-g';
    typed.className  = 'sh-w';
    prompt.textContent = '$';
    typed.textContent  = raw || ' ';
    line.append(prompt, ' ', typed);
    echo.appendChild(line);
    out.appendChild(echo);
    const block = document.createElement('div');
    block.style.marginBottom = '8px';
    const lines = cmd && CMDS[cmd] ? CMDS[cmd]()
      : cmd ? notFound(cmd)
      : [];
    lines.forEach(l => {
      const el = document.createElement('div');
      el.className = 'sh-line ' + (CLS[l.c] || '');
      el.textContent = l.v;
      block.appendChild(el);
    });
    out.appendChild(block);
    out.scrollTop = out.scrollHeight;
  }

  inp.addEventListener('keydown', e => {
    if (e.key === 'Enter') { run(inp.value); inp.value = ''; }
  });
}());
