<?php
require_once __DIR__ . '/../lib/blog.php';

$slug = $_GET['slug'] ?? '';
$post = blog_post($slug);

if (!$post || $post['date'] > date('Y-m-d')) {
    http_response_code(404);
    $title = 'Post not found — Hearth';
    $desc  = 'That post could not be found.';
    $canon = HEARTH_BASE . '/blog';
    include __DIR__ . '/../partials/head.php';
    include __DIR__ . '/../partials/header.php';
    echo '<main class="page"><div class="wrap"><p class="eyebrow">404</p><h1 class="serif">That post isn\'t here.</h1>'
       . '<p class="lead">It may have moved. <a href="/blog">Browse the blog</a> or <a href="/app">try Hearth</a>.</p></div></main>';
    include __DIR__ . '/../partials/footer.php';
    echo "\n</body>\n</html>";
    exit;
}

$title  = $post['title'] . ' — Hearth';
$desc   = blog_excerpt($post);
$canon  = blog_url($post);
$ogimg  = blog_image($post);
$ogtype = 'article';
$ogalt  = $post['title'];
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';

// BlogPosting + BreadcrumbList structured data.
$ld = ['@context' => 'https://schema.org', '@graph' => [
    [
        '@type' => 'BlogPosting',
        '@id'   => $canon . '#post',
        'headline' => mb_substr($post['title'], 0, 110),
        'description' => $desc,
        'datePublished' => $post['date'],
        'dateModified' => $post['updated'] ?: $post['date'],
        'url' => $canon,
        'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canon],
        'image' => [$ogimg],
        'inLanguage' => 'en',
        'isPartOf' => ['@id' => HEARTH_BASE . '/blog#blog'],
        'author' => ['@type' => 'Person', 'name' => $post['author'], 'url' => 'https://kevinchamplin.com'],
        'publisher' => ['@id' => HEARTH_BASE . '/#org'],
        'keywords' => implode(', ', $post['tags']),
    ],
    ['@type' => 'BreadcrumbList', 'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => HEARTH_BASE . '/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => HEARTH_BASE . '/blog'],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $post['title'], 'item' => $canon],
    ]],
]];
echo '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
?>
<main class="page"><div class="wrap">
  <nav class="crumbs"><a href="/">Home</a> <span>/</span> <a href="/blog">Blog</a></nav>
  <article class="post">
    <header class="post-head">
      <div class="blog-card-meta">
        <time datetime="<?= $hh($post['date']) ?>"><?= $hh(blog_date_human($post['date'])) ?></time>
        <span>·</span><span><?= blog_reading_time($post) ?> min read</span>
        <span>·</span><span>by <?= $hh($post['author']) ?></span>
      </div>
      <h1 class="serif post-title"><?= $hh($post['title']) ?></h1>
    </header>
    <div class="post-body">
      <?= blog_html($post) ?>
    </div>
    <?php if ($post['tags']): ?>
      <div class="post-tags"><?php foreach ($post['tags'] as $t): ?><span class="tag"><?= $hh($t) ?></span><?php endforeach; ?></div>
    <?php endif; ?>
    <div class="post-cta">
      <p class="serif" style="font-size:1.25rem;margin:0 0 12px">Hearth is the free, private AI that runs on your own computer.</p>
      <a href="/app" class="blog-cta">Try Hearth free &rarr;</a>
      <a href="/blog" class="blog-cta-ghost">More posts</a>
    </div>
  </article>
</div></main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
