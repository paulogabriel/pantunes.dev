// Shared mail + rate-limit helpers. The leading underscore keeps Vercel from deploying this as a function.
// Env vars required: GMAIL_USER, GMAIL_PASS, MAIL_TO (optional)

import nodemailer from 'nodemailer';

export const EMAIL_RX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

export const clean = (v, max = 5000) => String(v ?? '').replace(/<[^>]*>/g, '').trim().slice(0, max);

const escapeHtml = s => s.replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

// In-memory, per warm instance: resets on cold start, which is enough to blunt bursts
export function rateLimit(map, key, max, windowMs) {
  const now = Date.now();
  const hits = (map.get(key) ?? []).filter(t => now - t < windowMs);
  return {
    allowed: hits.length < max,
    record: () => map.set(key, [...hits, now]),
  };
}

// Browsers always send Origin on POST. Only the site itself (and local dev) may use these endpoints from a page.
// Exact hosts only: a wildcard like *.vercel.app would let anyone's Vercel project pass.
const ALLOWED_ORIGINS = new Set(['https://pantunes.dev', 'https://www.pantunes.dev']);
const LOCALHOST = /^http:\/\/localhost(:\d+)?$/;

export const originOf = req => req.headers.origin ?? null;
export const isAllowedOrigin = origin => ALLOWED_ORIGINS.has(origin) || LOCALHOST.test(origin ?? '');

export const clientIp = req => req.headers['x-forwarded-for']?.split(',')[0]?.trim() ?? 'unknown';

// fields: [[label, value], ...]; empty values are skipped
export async function sendMail({ subject, replyName, replyEmail, fields }) {
  const rows = fields.filter(([, v]) => v);
  const transporter = nodemailer.createTransport({
    host: 'smtp.gmail.com',
    port: 587,
    secure: false,
    auth: { user: process.env.GMAIL_USER, pass: process.env.GMAIL_PASS },
  });

  await transporter.sendMail({
    from:    `"pantunes.dev" <${process.env.GMAIL_USER}>`,
    to:      process.env.MAIL_TO ?? process.env.GMAIL_USER,
    replyTo: { name: replyName, address: replyEmail },
    subject,
    text:    rows.map(([k, v]) => `${k}: ${v}`).join('\n\n'),
    html:    rows.map(([k, v]) => `<p><strong>${escapeHtml(k)}:</strong><br>${escapeHtml(v).replace(/\n/g, '<br>')}</p>`).join(''),
  });
}
