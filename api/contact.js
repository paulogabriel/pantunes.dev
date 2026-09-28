// Contact form — Vercel Function

import { EMAIL_RX, clean, rateLimit, clientIp, sendMail, originOf, isAllowedOrigin } from './_lib/mail.js';

const RATE_MAP = new Map();

export default async function handler(req, res) {
  if (req.method !== 'POST') {
    return res.status(405).json({ ok: false, error: 'Method not allowed.' });
  }

  // ── Same-site only: blocks other pages from posting through visitors' browsers ──
  if (!isAllowedOrigin(originOf(req))) {
    return res.status(403).json({ ok: false, error: 'Forbidden.' });
  }

  // ── Rate limit: 5 per IP per 10 min ──
  const limit = rateLimit(RATE_MAP, clientIp(req), 5, 10 * 60 * 1000);
  if (!limit.allowed) {
    return res.status(429).json({ ok: false, error: 'Too many attempts. Please wait a few minutes.' });
  }

  // ── Honeypot ──
  if (req.body?.website) {
    return res.status(200).json({ ok: true });
  }

  // ── Validate ──
  const name    = clean(req.body?.name, 200);
  const email   = clean(req.body?.email, 320);
  const message = clean(req.body?.message);

  if (name.length < 2)       return res.status(422).json({ ok: false, error: 'Name is required.' });
  if (!EMAIL_RX.test(email)) return res.status(422).json({ ok: false, error: 'Invalid email address.' });
  if (message.length < 10)   return res.status(422).json({ ok: false, error: 'Message is too short.' });

  try {
    await sendMail({
      subject:    `pantunes.dev — ${name}`,
      replyName:  name,
      replyEmail: email,
      fields:     [['Name', name], ['Email', email], ['Message', message]],
    });
    limit.record();
    return res.status(200).json({ ok: true });
  } catch (err) {
    console.error('Mail error:', err);
    return res.status(500).json({ ok: false, error: 'Failed to send. Please try again.' });
  }
}
