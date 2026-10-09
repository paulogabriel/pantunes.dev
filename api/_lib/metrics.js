// MCP usage counts in GA4 (Measurement Protocol).
// Without GA_API_SECRET (local, preview) nothing is sent. No IP, name or email is sent:
// only the event, the tool and the MCP client name when it identifies itself.

import { randomUUID } from 'crypto';

const MEASUREMENT_ID = 'G-2EZ35VK2T3';

const param = v => (typeof v === 'string' ? v.slice(0, 100) : v);

export async function track(name, params = {}) {
  const secret = process.env.GA_API_SECRET;
  if (!secret) return;

  const clean = Object.fromEntries(
    Object.entries(params).filter(([, v]) => v !== undefined && v !== '').map(([k, v]) => [k, param(v)])
  );

  try {
    await fetch(`https://www.google-analytics.com/mp/collect?measurement_id=${MEASUREMENT_ID}&api_secret=${encodeURIComponent(secret)}`, {
      method: 'POST',
      body: JSON.stringify({
        client_id: randomUUID(),
        non_personalized_ads: true,
        events: [{ name, params: { ...clean, engagement_time_msec: 1 } }],
      }),
      signal: AbortSignal.timeout(1500),
    });
  } catch {
    // Metrics never break the MCP response
  }
}
