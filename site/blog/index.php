<?php
require_once __DIR__ . '/../lib/blog.php';
$posts = blog_posts();

$title = 'The Hearth Blog — private, local AI explained';
$desc  = 'Plain-English writing on private, local AI: how Hearth works, why it runs on your own computer, features, and how it compares to the cloud tools.';
$canon = HEARTH_BASE . '/blog/';
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';

// Blog + ItemList + Breadcrumb structured data.
$itemList = [];
foreach ($posts as $i => $p) {
    $itemList[] = ['@type' => 'ListItem', 'position' => $i + 1, 'url' => blog_url($p), 'name' => $p['title']];
}
$ld = ['@context' => 'https://schema.org', '@graph' => [
    [
        '@type' => 'Blog',
        '@id'   => HEARTH_BASE . '/blog#blog',
        'url'   => HEARTH_BASE . '/blog/',
        'name'  => 'The Hearth Blog',
        'description' => $desc,
        'inLanguage' => 'en',
        'publisher' => ['@id' => HEARTH_BASE . '/#org'],
        'blogPost' => array_map(fn($p) => [
            '@type' => 'BlogPosting',
            'headline' => $p['title'],
            'url' => blog_url($p),
            'datePublished' => $p['date'],
            'author' => ['@type' => 'Person', 'name' => $p['author']],
        ], array_slice($posts, 0, 20)),
    ],
    ['@type' => 'ItemList', 'itemListElement' => $itemList],
    ['@type' => 'BreadcrumbList', 'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => HEARTH_BASE . '/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => HEARTH_BASE . '/blog'],
    ]],
]];
echo '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
?>
<main class="page"><div class="wrap">
  <p class="eyebrow">The Hearth blog</p>
  <h1 class="serif">Private AI, in plain English.</h1>
  <p class="lead">Short, honest writing about AI that runs on your own computer — how Hearth works, what's new, and why local-first matters.</p>

  <?php if (!$posts): ?>
    <p>The first posts are on their way. <a href="/app">Try Hearth</a> in the meantime.</p>
  <?php else: ?>
    <div class="blog-list">
      <?php foreach ($posts as $p): ?>
        <article class="blog-card">
          <a class="blog-card-link" href="/blog/<?= $hh($p['slug']) ?>">
            <div class="blog-card-meta">
              <time datetime="<?= $hh($p['date']) ?>"><?= $hh(blog_date_human($p['date'])) ?></time>
              <span>·</span><span><?= blog_reading_time($p) ?> min read</span>
            </div>
            <h2 class="serif blog-card-title"><?= $hh($p['title']) ?></h2>
            <p class="blog-card-desc"><?= $hh(blog_excerpt($p)) ?></p>
            <span class="blog-card-more">Read &rarr;</span>
          </a>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <p style="margin-top:40px"><a href="/app" class="blog-cta">Try Hearth free &rarr;</a></p>
</div></main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
