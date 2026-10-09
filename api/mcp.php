<?php
// MCP Server — pantunes.dev
// Model Context Protocol 2024-11-05, Streamable HTTP transport

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Mcp-Session-Id');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$body = json_decode($raw, true);

if (!$body || !isset($body['jsonrpc'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON-RPC request']);
    exit;
}

$method = $body['method'] ?? '';
$id     = $body['id'] ?? null;
$params = $body['params'] ?? [];

// Notifications have no id and need no response
if ($id === null) {
    http_response_code(202);
    exit;
}

echo json_encode(dispatch($method, $id, $params));

// ── Dispatcher ──────────────────────────────────────────────────────────────

function dispatch($method, $id, $params) {
    return match($method) {
        'initialize'  => respond($id, [
            'protocolVersion' => '2024-11-05',
            'capabilities'    => ['tools' => new stdClass()],
            'serverInfo'      => ['name' => 'pantunes-dev', 'version' => '1.0.0'],
        ]),
        'tools/list'  => respond($id, ['tools' => tools_list()]),
        'tools/call'  => tool_call($id, $params),
        default       => error($id, -32601, 'Method not found: ' . $method),
    };
}

// ── Tools ───────────────────────────────────────────────────────────────────

function tools_list() {
    return [
        [
            'name'        => 'get_resume',
            'description' => 'Full resume for Paulo Antunes in JSON Resume format.',
            'inputSchema' => ['type' => 'object', 'properties' => new stdClass()],
        ],
        [
            'name'        => 'get_skills',
            'description' => 'Skill set with proficiency levels (0–100).',
            'inputSchema' => ['type' => 'object', 'properties' => new stdClass()],
        ],
        [
            'name'        => 'get_availability',
            'description' => 'Current availability for new projects.',
            'inputSchema' => ['type' => 'object', 'properties' => new stdClass()],
        ],
        [
            'name'        => 'get_contact',
            'description' => 'Contact information for Paulo Antunes.',
            'inputSchema' => ['type' => 'object', 'properties' => new stdClass()],
        ],
    ];
}

function tool_call($id, $params) {
    $name = $params['name'] ?? '';

    $result = match($name) {
        'get_resume'       => tool_resume(),
        'get_skills'       => tool_skills(),
        'get_availability' => tool_availability(),
        'get_contact'      => tool_contact(),
        default            => null,
    };

    if ($result === null) {
        return error($id, -32602, 'Unknown tool: ' . $name);
    }

    return respond($id, ['content' => [['type' => 'text', 'text' => $result]]]);
}

// ── Tool implementations ─────────────────────────────────────────────────────

function tool_resume() {
    $path = __DIR__ . '/../resume.json';
    $data = json_decode(file_get_contents($path), true);
    return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}

function tool_skills() {
    return json_encode([
        'skills' => [
            ['name' => 'HTML + CSS',         'level' => 100, 'keywords' => ['HTML5', 'CSS3', 'SASS', 'Tailwind CSS', 'responsive design']],
            ['name' => 'JavaScript (ES6+)',  'level' => 88,  'keywords' => ['Vanilla JS', 'jQuery']],
            ['name' => 'Tailwind CSS',       'level' => 84],
            ['name' => 'Drupal / WordPress', 'level' => 93,  'keywords' => ['Twig', 'PHP', 'CMS']],
            ['name' => 'Web Accessibility',  'level' => 95,  'keywords' => ['WCAG 2.2 AA', 'ADA', 'Section 508', 'axe', 'WAVE', 'VoiceOver', 'NVDA']],
            ['name' => 'Design → Dev',       'level' => 100, 'keywords' => ['Figma', 'Adobe XD', 'UX/UI', 'wireframes', 'style guides']],
        ],
    ], JSON_PRETTY_PRINT);
}

function tool_availability() {
    return json_encode([
        'available' => true,
        'status'    => 'available',
        'open_to'   => ['freelance', 'contract', 'full-time'],
        'location'  => 'Porto Alegre, Brazil',
        'remote'    => true,
        'contact'   => 'paulo84@gmail.com',
    ], JSON_PRETTY_PRINT);
}

function tool_contact() {
    return json_encode([
        'email'     => 'paulo84@gmail.com',
        'linkedin'  => 'https://www.linkedin.com/in/paulogabriel',
        'github'    => 'https://github.com/paulogabriel',
        'portfolio' => 'https://pantunes.dev',
        'location'  => 'Porto Alegre, Brazil',
    ], JSON_PRETTY_PRINT);
}

// ── Helpers ─────────────────────────────────────────────────────────────────

function respond($id, $result) {
    return ['jsonrpc' => '2.0', 'id' => $id, 'result' => $result];
}

function error($id, $code, $message) {
    return ['jsonrpc' => '2.0', 'id' => $id, 'error' => ['code' => $code, 'message' => $message]];
}
