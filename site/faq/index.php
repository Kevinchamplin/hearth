<?php
$title = 'Hearth FAQ — is it really free, and really private?';
$desc  = 'Honest answers about Hearth: cost, privacy, what you need to run it, and whether it works offline.';
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';

$faqs = [
  ['Is Hearth really free?',
   'Yes &mdash; completely, and forever. It runs on your own computer, so there\'s nothing for us to bill you for. No subscription, no credit card, no &ldquo;free trial&rdquo; that ends.'],
  ['Will Hearth ever start charging, or get more expensive?',
   'No. Hearth runs on <em>your</em> computer, not on a server we pay for &mdash; so there\'s no cost to pass on and no meter to switch on later. Cloud AI is cheap right now because big companies are subsidizing it; those prices can rise. Hearth\'s can\'t: it\'s free today, and free in ten years. <a href="/compare">See how it compares &rarr;</a>'],
  ['Is it actually private?',
   'Yes, by design. The AI runs on your device, so your conversations are never sent to us or anyone else. There\'s no server in the middle that could read them.'],
  ['Can you see my conversations?',
   'No &mdash; we can\'t. The model lives on your machine, so there is literally nothing on our end that could read what you type. We don\'t want your data; the whole point is that it stays with you.'],
  ['What do I need to run it?',
   'A reasonably modern computer and the Chrome or Edge browser for the instant, no-install version. The first time you open it, it downloads the AI once (about a gigabyte). After that it loads quickly.'],
  ['Why is the download about a gigabyte?',
   'Because you\'re bringing the whole AI &ldquo;brain&rdquo; onto your computer, once &mdash; instead of borrowing it over the internet every time you ask something. It\'s like downloading a movie to watch on a flight instead of streaming it: the first download is big, but after that it\'s instant and works anywhere, even offline. And it only happens once &mdash; it stays on your computer for next time.'],
  ['Why does it work best in Chrome or Edge?',
   'To think quickly, Hearth uses your computer\'s graphics chip &mdash; the same part that makes games and videos look smooth. Right now, Chrome and Edge are the browsers that let a website use it. Safari and Firefox are adding it soon. For the smoothest experience today, open Hearth in Chrome or Edge.'],
  ['Does it really work without internet?',
   'Yes &mdash; after that first download, Hearth doesn\'t need the internet at all. On a plane, in a cabin, with the wifi switched off, it keeps right on working. Try it: turn your wifi off mid-conversation and keep chatting.'],
  ['Can I install it like a real app?',
   'Yes! In Chrome or Edge, look for the <strong>Install</strong> button in Hearth\'s top corner (or the install icon in your browser\'s address bar). It puts Hearth in your dock or home screen, opens in its own window, and works fully offline. Your conversations are saved too &mdash; on your device only, never uploaded.'],
  ['Do I need to make an account?',
   'Nope. You open it and start talking. No sign-up, no login, no email required.'],
  ['Can it look at my photos?',
   'Yes. You can attach an image and ask about it, and that runs privately on your device too &mdash; the picture never leaves your computer.'],
  ['Is this a competitor to ChatGPT or Gemini?',
   'No &mdash; and that\'s on purpose. ChatGPT and Gemini run on enormous data centers. Hearth runs on <em>your</em> computer, which makes it a different, simpler thing: fast, free, and completely private. It\'s wonderful for recipes, everyday questions, explaining something tricky, writing and rewriting, and brainstorming. It won\'t build you a whole website or tackle huge, complex projects &mdash; for that, the big cloud tools are still the right call. Inside the app you can also pick a bigger, smarter brain (just tap the brain button at the top) if your computer is up to it. A more powerful Hearth desktop app, with even bigger brains, is on the way.'],
  ['So what\'s the catch? Why would anyone give this away?',
   'Fair question &mdash; &ldquo;free&rdquo; usually means you\'re the product. Not here. Hearth runs on <em>your</em> computer, so there\'s no server bill to cover and no data worth selling. It\'s free because the builder wanted AI to be free for the curious, loves building things, and saw a hole worth filling. What\'s in it for him? His name on work he\'s proud of. The full <a href="https://github.com/Kevinchamplin/hearth">source is public</a> &mdash; you can read every line yourself, and contributions are welcome. <a href="/about">The longer answer is here.</a>'],
  ['Why is it called &ldquo;Hearth&rdquo;?',
   'The hearth is the fire at the center of a home &mdash; the warmth and light that <em>belonged to the house</em>. Nobody billed you monthly for your own fire. The cloud is somebody else\'s fire, far away, rented. Hearth brings the fire home: a warm light in a cold cloud.'],
  ['Who made this?',
   'Kevin Champlin, an AI engineer. You can find more of his work at <a href="https://kevinchamplin.com">kevinchamplin.com</a>, or just <a href="/contact">say hello</a>.'],
];
echo '<script type="application/ld+json">' . json_encode([
  '@context' => 'https://schema.org', '@type' => 'FAQPage',
  'mainEntity' => array_map(fn($f) => [
    '@type' => 'Question', 'name' => trim(html_entity_decode(strip_tags($f[0]))),
    'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim(html_entity_decode(strip_tags($f[1])))],
  ], $faqs),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
?>
<main class="page"><div class="wrap">
  <p class="eyebrow">Questions &amp; answers</p>
  <h1 class="serif">The honest FAQ.</h1>
  <p class="lead">No fine print, no spin. Here's what people ask most.</p>
  <figure class="ph"><img src="/assets/photos/reading-nook.jpg" alt="A quiet, sunlit reading corner — a calm place to think" width="1600" height="1067" loading="lazy"><figcaption>Free, private, and yours &mdash; no catch to read between the lines.</figcaption></figure>
  <?php foreach ($faqs as $f): ?>
    <div class="faq-item">
      <div class="faq-q serif"><?= $f[0] ?></div>
      <div class="faq-a"><?= $f[1] ?></div>
    </div>
  <?php endforeach; ?>
  <p style="margin-top:34px"><a href="/app" style="display:inline-block;background:radial-gradient(circle at 35% 30%,#FFD89A,#EE9A45 78%);color:#3A2206;font-weight:700;padding:14px 28px;border-radius:12px;text-decoration:none">Try Hearth free &rarr;</a></p>
</div></main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
