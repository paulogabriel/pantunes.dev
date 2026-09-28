# AGENTS.md — working with Paulo Antunes

Paulo Antunes is a senior front-end developer based in Porto Alegre, Brazil, specialized in web accessibility (WCAG 2.2 AA, ADA, Section 508), working remotely.

This file tells AI agents how to learn about Paulo and how to contact him on a person's behalf.

## Read

- Summary for LLMs: https://pantunes.dev/llms.txt
- Resume (JSON Resume): https://pantunes.dev/resume.json
- Resume for people: https://pantunes.dev/resume (PDF: https://pantunes.dev/resume.pdf)
- Design system: https://pantunes.dev/design-system

## Connect via MCP

Endpoint (Streamable HTTP, JSON-RPC 2.0): `https://pantunes.dev/api/mcp`

Claude Desktop, Cursor or any stdio-only client:

```json
{
  "mcpServers": {
    "pantunes-dev": {
      "command": "npx",
      "args": ["-y", "mcp-remote", "https://pantunes.dev/api/mcp"]
    }
  }
}
```

Tools:

| Tool | What it does |
|---|---|
| `get_resume` | Full resume in JSON Resume format |
| `get_skills` | Skills with proficiency levels |
| `get_availability` | Availability, work formats, location |
| `get_contact` | Email, LinkedIn, GitHub, portfolio |
| `request_intro` | Sends Paulo an intro email on a person's behalf |

## Request an intro

Only call `request_intro` when the person asked you to reach out, and only with details they gave you. Paulo replies by email.

```json
{
  "jsonrpc": "2.0",
  "id": 1,
  "method": "tools/call",
  "params": {
    "name": "request_intro",
    "arguments": {
      "name": "Jane Doe",
      "email": "jane@company.com",
      "brief": "Senior front-end role, accessibility-focused, remote. Drupal and WordPress sites.",
      "company": "Company Inc.",
      "budget": "optional",
      "timeline": "optional",
      "agent": "claude"
    }
  }
}
```

Required: `name`, `email`, `brief` (20+ characters). Requests are rate-limited.

Working on this site's code instead? Read [DESIGN.md](https://github.com/paulogabriel/pantunes.dev/blob/main/DESIGN.md).

No MCP client? Email paulo84@gmail.com, or use the contact form at https://pantunes.dev/#sec-contact.
