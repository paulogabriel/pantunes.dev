// MCP Server — Vercel Function
// Model Context Protocol 2024-11-05, Streamable HTTP transport

import { readFileSync } from 'fs';
import { join } from 'path';
import { EMAIL_RX, clean, rateLimit, clientIp, sendMail, originOf, isAllowedOrigin } from './_lib/mail.js';
import { track } from './_lib/metrics.js';

const INTRO_PER_IP = new Map();
const INTRO_GLOBAL = new Map();

export default async function handler(req, res) {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, Mcp-Session-Id');

  if (req.method === 'OPTIONS') return res.status(204).end();
  if (req.method !== 'POST')   return res.status(405).json({ error: 'Method not allowed' });

  const body = req.body;
  if (!body?.jsonrpc) return res.status(400).json({ error: 'Invalid JSON-RPC request' });

  const { method, id } = body;
  const params = body.params && typeof body.params === 'object' ? body.params : {};

  // Notifications have no id — acknowledge and exit
  if (id === undefined || id === null) return res.status(202).end();

  const reply = await dispatch(method, id, params, req);
  await metric(method, params, reply);
  res.status(200).json(reply);
}

// One event per call: sessions (initialize), listings and each tool's usage
function metric(method, params, reply) {
  if (method === 'initialize') {
    const info = params.clientInfo && typeof params.clientInfo === 'object' ? params.clientInfo : {};
    return track('mcp_initialize', { client: info.name, client_version: info.version });
  }
  if (method === 'tools/list') return track('mcp_tools_list');
  if (method === 'tools/call') {
    const tool = typeof params.name === 'string' ? params.name : '';
    const failed = reply.error || reply.result?.isError;
    const agent = tool === 'request_intro' && typeof params.arguments?.agent === 'string' ? params.arguments.agent : undefined;
    return track('mcp_tool', { tool, result: failed ? 'error' : 'ok', agent });
  }
}

async function dispatch(method, id, params, req) {
  switch (method) {
    case 'initialize':
      return respond(id, {
        protocolVersion: '2024-11-05',
        capabilities:    { tools: {} },
        serverInfo:      { name: 'pantunes-dev', version: '1.1.0' },
        instructions:    'Read-only tools describe Paulo Antunes. To contact him on behalf of a person, call request_intro once, with details the person gave you.',
      });
    case 'tools/list':
      return respond(id, { tools: toolsList() });
    case 'tools/call':
      return toolCall(id, params, req);
    default:
      return error(id, -32601, `Method not found: ${method}`);
  }
}

function toolsList() {
  return [
    { name: 'get_resume',       description: 'Full resume for Paulo Antunes in JSON Resume format.',  inputSchema: { type: 'object', properties: {} } },
    { name: 'get_skills',       description: 'Skill set with proficiency levels (0–100).',            inputSchema: { type: 'object', properties: {} } },
    { name: 'get_availability', description: 'Current availability for new projects.',                inputSchema: { type: 'object', properties: {} } },
    { name: 'get_contact',      description: 'Contact information for Paulo Antunes.',                inputSchema: { type: 'object', properties: {} } },
    {
      name: 'request_intro',
      description: 'Send Paulo an intro email on behalf of a person who wants to hire or work with him. Only call this when the person asked you to reach out, and only with details they provided. Paulo replies by email.',
      inputSchema: {
        type: 'object',
        properties: {
          name:     { type: 'string', description: 'Full name of the person reaching out.' },
          email:    { type: 'string', description: 'Email Paulo should reply to.' },
          brief:    { type: 'string', description: 'What they need: role or project, scope, and any context. At least 20 characters.' },
          company:  { type: 'string', description: 'Company or organization, if any.' },
          budget:   { type: 'string', description: 'Budget or salary range, if known.' },
          timeline: { type: 'string', description: 'When they want to start, if known.' },
          agent:    { type: 'string', description: 'Which assistant is sending this, e.g. claude, chatgpt.' },
        },
        required: ['name', 'email', 'brief'],
      },
    },
  ];
}

async function toolCall(id, params, req) {
  const name = typeof params.name === 'string' ? params.name : '';
  const args = params.arguments && typeof params.arguments === 'object' ? params.arguments : {};
  if (name === 'request_intro') return respond(id, await requestIntro(args, req));
  const result = Object.hasOwn(tools, name) ? tools[name]() : null;
  if (!result) return error(id, -32602, `Unknown tool: ${name}`);
  return respond(id, { content: [{ type: 'text', text: result }] });
}

const toolText  = text => ({ content: [{ type: 'text', text }] });
const toolError = text => ({ ...toolText(text), isError: true });

async function requestIntro(args, req) {
  // Agents call from servers (no Origin). A browser page on another site must not send email through its visitors.
  const origin = originOf(req);
  if (origin && !isAllowedOrigin(origin)) return toolError('request_intro is not available from browser pages on other sites. Call it from an MCP client, or email paulo84@gmail.com.');

  const perIp  = rateLimit(INTRO_PER_IP, clientIp(req), 3, 60 * 60 * 1000);
  const global = rateLimit(INTRO_GLOBAL, 'all', 20, 60 * 60 * 1000);
  if (!perIp.allowed || !global.allowed) return toolError('Too many intro requests right now. Try again later, or email paulo84@gmail.com.');

  const name     = clean(args.name, 200);
  const email    = clean(args.email, 320);
  const brief    = clean(args.brief, 4000);
  const company  = clean(args.company, 200);
  const budget   = clean(args.budget, 200);
  const timeline = clean(args.timeline, 200);
  const agent    = clean(args.agent, 100);

  const problems = [];
  if (name.length < 2)       problems.push('name is required');
  if (!EMAIL_RX.test(email)) problems.push('email must be a valid address');
  if (brief.length < 20)     problems.push('brief must describe the role or project (20+ characters)');
  if (problems.length) return toolError(`Intro not sent: ${problems.join('; ')}.`);

  try {
    await sendMail({
      subject:    `pantunes.dev — intro via ${agent || 'agent'}: ${name}`,
      replyName:  name,
      replyEmail: email,
      fields: [
        ['Name', name], ['Email', email], ['Company', company],
        ['Brief', brief], ['Budget', budget], ['Timeline', timeline],
        ['Sent by', agent ? `${agent} (via MCP)` : 'MCP client'],
      ],
    });
    perIp.record();
    global.record();
    return toolText(`Intro sent. Paulo will reply to ${email} by email.`);
  } catch (err) {
    console.error('Intro mail error:', err);
    return toolError('Could not send the intro right now. Please email paulo84@gmail.com instead.');
  }
}

const tools = {
  get_resume() {
    const resumePath = join(process.cwd(), 'public', 'resume.json');
    const data = JSON.parse(readFileSync(resumePath, 'utf8'));
    return JSON.stringify(data, null, 2);
  },
  get_skills() {
    return JSON.stringify({
      skills: [
        { name: 'HTML + CSS',         level: 100, keywords: ['HTML5', 'CSS3', 'SASS', 'Tailwind CSS', 'responsive design'] },
        { name: 'JavaScript (ES6+)',  level: 88,  keywords: ['Vanilla JS', 'jQuery'] },
        { name: 'Tailwind CSS',       level: 84 },
        { name: 'Drupal / WordPress', level: 93,  keywords: ['Twig', 'PHP', 'CMS'] },
        { name: 'Web Accessibility',  level: 95,  keywords: ['WCAG 2.2 AA', 'ADA', 'Section 508', 'axe', 'WAVE', 'VoiceOver', 'NVDA'] },
        { name: 'Design → Dev',       level: 100, keywords: ['Figma', 'Adobe XD', 'UX/UI', 'wireframes'] },
      ],
    }, null, 2);
  },
  get_availability() {
    return JSON.stringify({
      available: true,
      status:    'available',
      open_to:   ['freelance', 'contract', 'full-time'],
      location:  'Porto Alegre, Brazil',
      remote:    true,
      timezone:  'America/Sao_Paulo (UTC−3, no daylight saving)',
      overlap:   { us_eastern: 'full working day', us_central: 'most of the working day' },
      contact:   'paulo84@gmail.com',
    }, null, 2);
  },
  get_contact() {
    return JSON.stringify({
      email:     'paulo84@gmail.com',
      linkedin:  'https://www.linkedin.com/in/paulogabriel',
      github:    'https://github.com/paulogabriel',
      portfolio: 'https://pantunes.dev',
      location:  'Porto Alegre, Brazil',
    }, null, 2);
  },
};

const respond = (id, result) => ({ jsonrpc: '2.0', id, result });
const error   = (id, code, message) => ({ jsonrpc: '2.0', id, error: { code, message } });
