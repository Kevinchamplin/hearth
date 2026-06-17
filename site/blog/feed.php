<?php
require_once __DIR__ . '/../lib/blog.php';
$posts = array_slice(blog_posts(), 0, 30);
header('Content-Type: application/rss+xml; charset=utf-8');
$now = date(DATE_RSS);
$lastBuild = $posts ? date(DATE_RSS, strtotime($posts[0]['date'])) : $now;
$x = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES | ENT_XML1, 'UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">
  <channel>
    <title>The Hearth Blog</title>
    <link>https://hearth.kevinchamplin.com/blog</link>
    <atom:link href="https://hearth.kevinchamplin.com/blog/feed.xml" rel="self" type="application/rss+xml" />
    <description>Plain-English writing on private, local AI — how Hearth works, what's new, and why it runs on your own computer.</description>
    <language>en-us</language>
    <lastBuildDate><?= $lastBuild ?></lastBuildDate>
<?php foreach ($posts as $p): $url = blog_url($p); ?>
    <item>
      <title><?= $x($p['title']) ?></title>
      <link><?= $x($url) ?></link>
      <guid isPermaLink="true"><?= $x($url) ?></guid>
      <pubDate><?= date(DATE_RSS, strtotime($p['date'])) ?></pubDate>
      <description><?= $x(blog_excerpt($p)) ?></description>
      <content:encoded><![CDATA[<?= blog_html($p) ?>]]></content:encoded>
<?php foreach ($p['tags'] as $t): ?>      <category><?= $x($t) ?></category>
<?php endforeach; ?>    </item>
<?php endforeach; ?>
  </channel>
</rss>
