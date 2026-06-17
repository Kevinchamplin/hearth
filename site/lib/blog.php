<?php
// Hearth blog — a tiny, file-based engine. Each post is a Markdown file with front matter
// at site/blog/posts/<slug>.md. No database: the daily generator simply drops a new .md here,
// and everything (index, post pages, RSS, sitemap) reads from this directory at request time.

require_once __DIR__ . '/Parsedown.php';

if (!defined('HEARTH_BASE')) define('HEARTH_BASE', 'https://hearth.kevinchamplin.com');

function blog_posts_dir(): string { return __DIR__ . '/../blog/posts'; }

/** Parse one post file into a structured array (front matter + body). */
function blog_parse_file(string $path): ?array {
    $raw = @file_get_contents($path);
    if ($raw === false) return null;
    $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw); // strip BOM

    $meta = [];
    $body = $raw;
    if (preg_match('/^---\s*\n(.*?)\n---\s*\n?(.*)$/s', $raw, $m)) {
        $body = $m[2];
        foreach (preg_split('/\n/', $m[1]) as $line) {
            if (preg_match('/^([A-Za-z0-9_]+):\s*(.*)$/', $line, $kv)) {
                $meta[strtolower($kv[1])] = trim($kv[2], " \t\"'");
            }
        }
    }

    $slug = $meta['slug'] ?? pathinfo($path, PATHINFO_FILENAME);
    $tags = (isset($meta['tags']) && $meta['tags'] !== '')
        ? array_values(array_filter(array_map('trim', explode(',', $meta['tags']))))
        : [];

    return [
        'slug'    => $slug,
        'title'   => $meta['title'] ?? ucfirst(str_replace('-', ' ', $slug)),
        'desc'    => $meta['description'] ?? '',
        'date'    => $meta['date'] ?? date('Y-m-d', @filemtime($path) ?: time()),
        'updated' => $meta['updated'] ?? ($meta['date'] ?? null),
        'author'  => $meta['author'] ?? 'Kevin Champlin',
        'tags'    => $tags,
        'image'   => $meta['image'] ?? null,
        'body'    => $body,
        'path'    => $path,
    ];
}

/** All published posts (front-dated posts are hidden), newest first. */
function blog_posts(): array {
    $files = glob(blog_posts_dir() . '/*.md') ?: [];
    $today = date('Y-m-d');
    $posts = [];
    foreach ($files as $f) {
        $p = blog_parse_file($f);
        if ($p && $p['date'] <= $today) $posts[] = $p;
    }
    usort($posts, fn($a, $b) => strcmp($b['date'] . $b['slug'], $a['date'] . $a['slug']));
    return $posts;
}

/** One post by slug, or null. */
function blog_post(string $slug): ?array {
    $slug = preg_replace('/[^a-z0-9-]/', '', strtolower($slug));
    if ($slug === '') return null;
    $path = blog_posts_dir() . '/' . $slug . '.md';
    return is_file($path) ? blog_parse_file($path) : null;
}

function blog_url(array $p): string { return HEARTH_BASE . '/blog/' . $p['slug']; }

function blog_image(array $p): string {
    if (!empty($p['image'])) {
        return preg_match('#^https?://#', $p['image']) ? $p['image'] : HEARTH_BASE . $p['image'];
    }
    return HEARTH_BASE . '/assets/og.png?v=2';
}

/** Rendered HTML body. Safe mode on: posts are authored by us / the generator, but we never
 *  want raw HTML (or AI-generated HTML) injected verbatim. */
function blog_html(array $p): string {
    $pd = new Parsedown();
    $pd->setSafeMode(true);
    $pd->setBreaksEnabled(false);
    return $pd->text($p['body']);
}

function blog_excerpt(array $p, int $len = 160): string {
    if (!empty($p['desc'])) return $p['desc'];
    $txt = trim(preg_replace('/\s+/', ' ', strip_tags(blog_html($p))));
    return mb_strlen($txt) > $len ? rtrim(mb_substr($txt, 0, $len - 1)) . '…' : $txt;
}

function blog_reading_time(array $p): int {
    $words = str_word_count(strip_tags(blog_html($p)));
    return max(1, (int) round($words / 200));
}

function blog_date_human(string $ymd): string {
    $t = strtotime($ymd);
    return $t ? date('M j, Y', $t) : $ymd;
}
