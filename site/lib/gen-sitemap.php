<?php
// Regenerate site/sitemap.xml from the static pages + every blog post.
// Run at deploy time and after each daily blog post (cron) so the sitemap is always current.
// Usage: php lib/gen-sitemap.php   (writes ../sitemap.xml)

require_once __DIR__ . '/blog.php';
$SITE = __DIR__ . '/..';
$base = 'https://hearth.kevinchamplin.com';

$mtime = function (string $rel) use ($SITE): string {
    $f = $SITE . '/' . ltrim($rel, '/');
    $t = is_file($f) ? @filemtime($f) : false;
    return date('Y-m-d', $t ?: time());
};

// Static pages: [loc, source-file-for-lastmod, changefreq, priority]
$pages = [
    ['/',          'index.html',        'weekly',  '1.0'],
    ['/app/',      null,                'weekly',  '0.9'],
    ['/compare/',  'compare/index.php', 'monthly', '0.8'],
    ['/blog/',     null,                'daily',   '0.8'],
    ['/about/',    'about/index.php',   'monthly', '0.7'],
    ['/tutorial/', 'tutorial/index.php','monthly', '0.7'],
    ['/faq/',      'faq/index.php',     'monthly', '0.7'],
    ['/contact/',  'contact/index.php', 'yearly',  '0.4'],
];

$posts = blog_posts();
$newestPost = $posts ? $posts[0]['date'] : date('Y-m-d');

$rows = [];
foreach ($pages as [$loc, $src, $freq, $pri]) {
    $last = $loc === '/blog/' ? $newestPost : ($src ? $mtime($src) : date('Y-m-d'));
    $rows[] = "  <url><loc>{$base}{$loc}</loc><lastmod>{$last}</lastmod><changefreq>{$freq}</changefreq><priority>{$pri}</priority></url>";
}
foreach ($posts as $p) {
    $loc = $base . '/blog/' . $p['slug'];
    $last = $p['updated'] ?: $p['date'];
    $rows[] = "  <url><loc>{$loc}</loc><lastmod>{$last}</lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>";
}

$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
     . "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n"
     . implode("\n", $rows) . "\n</urlset>\n";

file_put_contents($SITE . '/sitemap.xml', $xml);
echo "Wrote sitemap.xml — " . (count($pages) + count($posts)) . " URLs (" . count($posts) . " posts)\n";
