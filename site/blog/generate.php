<?php
/**
 * Hearth daily blog generator (CLI, run by cron).
 * Picks the next topic, asks an LLM to write an on-brand, SEO-shaped post, writes
 * posts/<slug>.md, then regenerates the sitemap. Idempotent: at most one post per calendar day.
 *
 *   php blog/generate.php            # generate today's post (skips if one already exists today)
 *   php blog/generate.php --force    # generate even if today already has a post
 *
 * Config (server-only, never in git): ../../hearth-analytics-data/blog.php returns
 *   ['openai_key' => 'sk-...', 'model' => 'gpt-4o'].
 * Topic backlog: blog/topics.txt (one per line; '#' comments). Used topics move to topics.used.txt.
 */

require_once __DIR__ . '/../lib/blog.php';

$FORCE = in_array('--force', $argv, true);
function logline(string $m): void { fwrite(STDERR, '[' . date('Y-m-d H:i:s') . '] ' . $m . "\n"); }
function slugify(string $s): string {
    $s = strtolower(trim($s));
    $s = preg_replace('/[^a-z0-9]+/', '-', $s);
    return trim($s, '-');
}

$today = date('Y-m-d');
$existing = blog_posts();
if (!$FORCE) {
    foreach ($existing as $p) {
        if ($p['date'] === $today) { logline("A post already exists for {$today} ({$p['slug']}). Skipping."); exit(0); }
    }
}

// --- config / key ---
$cfgPath = __DIR__ . '/../../hearth-analytics-data/blog.php';
if (!is_file($cfgPath)) { logline("Missing config $cfgPath"); exit(1); }
$cfg = require $cfgPath;
$key = $cfg['openai_key'] ?? '';
$model = $cfg['model'] ?? 'gpt-4o';
if (!$key) { logline('No openai_key in config'); exit(1); }

// --- pick a topic ---
$existingTitles = array_map(fn($p) => $p['title'], $existing);
$existingSlugs  = array_map(fn($p) => $p['slug'], $existing);
$topicsFile = __DIR__ . '/topics.txt';
$usedFile   = __DIR__ . '/topics.used.txt';
$topics = is_file($topicsFile) ? array_values(array_filter(array_map('trim', file($topicsFile)), fn($l) => $l !== '' && $l[0] !== '#')) : [];
$used   = is_file($usedFile) ? array_map('trim', file($usedFile, FILE_IGNORE_NEW_LINES)) : [];
$topic = null;
foreach ($topics as $t) { if (!in_array($t, $used, true)) { $topic = $t; break; } }

// --- build the prompt ---
$system = <<<SYS
You are the writer for the Hearth blog. Hearth is a free, private AI chat app that runs entirely on the user's OWN computer (in the browser, or as a native desktop app), so conversations never leave the machine. No account, no subscription, works offline after a one-time download. It's great for everyday things (questions, writing help, explanations, brainstorming) but is NOT a competitor to ChatGPT/Gemini for huge complex projects.

Write for normal, non-technical people — warm, calm, plain-English, confident, never salesy or fear-mongering. You may use Hearth's signature ideas in moderation: "a warm light in a cold cloud"; the cloud is "someone else's computer, rented"; downloading the model once is "like downloading a movie for a flight instead of streaming it."

STRICT RULES:
- NEVER write "open source" — say "the source is public" or "fair source".
- NEVER name the underlying engines (no "Ollama", "WebLLM", "llama.cpp"). You may say it uses "your computer's graphics chip".
- Be fair to ChatGPT/Gemini/Claude — they're great for big jobs; don't bash them.
- Do NOT invent statistics, fake quotes, download links, or features Hearth doesn't have. Real features: private/local, free, offline, no account, voice (on-device), image understanding, a desktop app with bigger models, install-as-an-app.

SEO STRUCTURE:
- The FIRST paragraph must directly, quotably answer the post's core question in 1-2 sentences (this is what AI search engines extract).
- Use H2 headings (##) phrased as natural questions people ask.
- 650-1000 words, scannable, short paragraphs, at least one bullet list.
- Mention "Hearth" naturally and include 1-2 markdown links using absolute https URLs to relevant pages: https://hearth.kevinchamplin.com/app (try it), https://hearth.kevinchamplin.com/compare (vs ChatGPT), https://hearth.kevinchamplin.com/faq, https://hearth.kevinchamplin.com/tutorial.
- End with one warm, soft call-to-action sentence.
- Markdown ONLY in the body (no raw HTML, no H1 — the title is rendered separately).
SYS;

$avoid = $existingTitles ? "Do NOT duplicate or closely overlap these existing posts:\n- " . implode("\n- ", $existingTitles) : '';
$ask = $topic
    ? "Write today's Hearth blog post on this topic:\n\n\"$topic\"\n\n$avoid"
    : "Pick a fresh, useful angle about private/local AI or a Hearth feature that does NOT overlap existing posts, then write it.\n\n$avoid";

$userMsg = $ask . "\n\nReturn STRICT JSON with exactly these keys: "
    . '{"title": string (<=70 chars, compelling, no "Hearth:" prefix needed), '
    . '"description": string (<=155 chars meta description), '
    . '"tags": string (3-4 comma-separated lowercase tags), '
    . '"body_markdown": string (the full post in Markdown, following all rules above)}';

// --- call OpenAI ---
$payload = json_encode([
    'model' => $model,
    'messages' => [
        ['role' => 'system', 'content' => $system],
        ['role' => 'user', 'content' => $userMsg],
    ],
    'response_format' => ['type' => 'json_object'],
    'temperature' => 0.8,
    'max_tokens' => 2600,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $key],
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_TIMEOUT => 120,
]);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);
if ($resp === false || $code !== 200) { logline("OpenAI error (HTTP $code): " . ($err ?: substr((string)$resp, 0, 300))); exit(1); }

$data = json_decode($resp, true);
$content = $data['choices'][0]['message']['content'] ?? '';
$post = json_decode($content, true);
if (!is_array($post) || empty($post['title']) || empty($post['body_markdown'])) {
    logline('Could not parse model JSON: ' . substr($content, 0, 300));
    exit(1);
}

// --- assemble + write ---
$title = trim($post['title']);
$desc  = trim($post['description'] ?? '');
$tags  = trim($post['tags'] ?? 'local AI, privacy');
$body  = trim($post['body_markdown']);

$slug = slugify($title);
if ($slug === '') { logline('Empty slug'); exit(1); }
if (in_array($slug, $existingSlugs, true)) { $slug .= '-' . date('m-d'); }

$fm = "---\n"
    . 'title: ' . str_replace(["\n", '"'], [' ', "'"], $title) . "\n"
    . 'description: ' . str_replace(["\n", '"'], [' ', "'"], $desc) . "\n"
    . 'date: ' . $today . "\n"
    . "author: Kevin Champlin\n"
    . 'tags: ' . $tags . "\n"
    . "---\n\n";

$path = blog_posts_dir() . '/' . $slug . '.md';
if (file_put_contents($path, $fm . $body . "\n") === false) { logline("Failed to write $path"); exit(1); }

// record the used topic
if ($topic) { file_put_contents($usedFile, $topic . "\n", FILE_APPEND); }

// regenerate sitemap with the same PHP binary that's running this (cron PATH may lack `php`)
@exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__DIR__ . '/../lib/gen-sitemap.php') . ' 2>&1');

logline("Published: $slug — \"$title\"");
echo "Published: https://hearth.kevinchamplin.com/blog/$slug\n";
