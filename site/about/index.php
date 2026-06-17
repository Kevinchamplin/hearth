<?php
$title = 'About Hearth — private, local AI that is yours';
$desc  = 'Hearth is a beautiful, free AI that runs entirely on your own computer. Private by nature, free for good. Here is the why behind it.';
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
echo '<script type="application/ld+json">' . json_encode([
  '@context' => 'https://schema.org',
  '@graph' => [
    [
      '@type' => 'AboutPage',
      '@id' => 'https://hearth.kevinchamplin.com/about#about',
      'url' => 'https://hearth.kevinchamplin.com/about',
      'name' => 'About Hearth',
      'description' => 'Hearth is a beautiful, free AI that runs entirely on your own computer. Private by nature, free for good.',
      'isPartOf' => ['@id' => 'https://hearth.kevinchamplin.com/#site'],
      'about' => ['@id' => 'https://hearth.kevinchamplin.com/#org'],
      'primaryImageOfPage' => 'https://hearth.kevinchamplin.com/assets/photos/fireside.jpg',
    ],
    [
      '@type' => 'Person',
      '@id' => 'https://kevinchamplin.com/#kevin',
      'name' => 'Kevin Champlin',
      'url' => 'https://kevinchamplin.com',
      'jobTitle' => 'AI engineer',
      'image' => 'https://hearth.kevinchamplin.com/assets/kevin-champlin.png',
    ],
    [
      '@type' => 'BreadcrumbList',
      '@id' => 'https://hearth.kevinchamplin.com/about#breadcrumb',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://hearth.kevinchamplin.com/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'About', 'item' => 'https://hearth.kevinchamplin.com/about'],
      ],
    ],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
?>
<main class="page"><div class="wrap">
  <p class="eyebrow">About</p>
  <h1 class="serif">A warm light in a cold cloud.</h1>
  <p class="lead">Hearth is a beautiful, free AI that lives on your own computer &mdash; private, unlimited, and yours.</p>

  <figure class="ph">
    <img src="/assets/photos/fireside.jpg" alt="Someone enjoying tea beside a glowing wood stove" width="1600" height="1067" loading="lazy">
    <figcaption>Your own quiet fire.</figcaption>
  </figure>

  <p>Every AI most of us use lives in a giant data center somewhere far away. You rent it by the month, a company keeps a copy of everything you type, and the moment they change their pricing or their rules, your assistant changes with them. Hearth is the opposite of that.</p>

  <p>When you use Hearth, the AI runs <strong>on your device</strong> &mdash; right in your web browser, or as an app on your computer. Nothing you say is sent anywhere. There's no account to create, no subscription, and no meter ticking. Once it's set up, it even keeps working with the internet switched off.</p>

  <h2 class="serif">Why bring your AI home now?</h2>
  <p>Cloud AI feels almost free right now &mdash; and there's a reason for that. The big companies are spending enormous sums to win you over while the field is young: generous free tiers, low monthly prices, &ldquo;unlimited&rdquo; that really feels unlimited. It's a land grab, and you're the land. That kind of pricing is a phase, not a promise.</p>
  <p>Phases end. We've watched it happen to streaming, to ride-shares, to food delivery, to every cheap-at-first service that eventually had to earn its keep. The pattern is familiar: prices drift up, free tiers shrink, new limits and meters appear, and the &ldquo;pro&rdquo; features you got used to quietly move behind a higher tier. None of that is villainy &mdash; it's just what a rented thing does once the company stops subsidizing it. The terms can change, and one day they will.</p>

  <p class="pull">The cloud was never yours.</p>

  <p>Hearth's price can't move, because there's no one to move it. It runs on a computer you already own, using power you already pay for. That means it's free today &mdash; and it's still free in ten years, no matter what any company decides. No renewal email, no &ldquo;your plan is changing,&rdquo; no morning where the thing you relied on suddenly costs more or works less. The fire is in <em>your</em> house.</p>

  <figure class="ph">
    <img src="/assets/photos/cozy-laptop.jpg" alt="Using Hearth on a laptop at home in warm light" width="1600" height="1067" loading="lazy">
    <figcaption>Free today, free in ten years &mdash; because it runs on what you already own.</figcaption>
  </figure>

  <p>To be fair to the cloud: for the truly heavy jobs &mdash; the largest, smartest models doing serious research-grade work &mdash; the big services are genuinely impressive, and there's nothing wrong with reaching for them when you need that horsepower. Hearth isn't here to win every benchmark. It's here so that for the everyday things &mdash; thinking out loud, drafting, asking questions, sorting out a problem &mdash; you have a capable assistant that's private and permanent and costs nothing. Bring your AI home for the daily fire; rent the cloud's furnace when you actually need a furnace. (Curious where each one shines? <a href="https://hearth.kevinchamplin.com/compare">See how Hearth compares</a>.)</p>

  <h2 class="serif">Why is it called Hearth?</h2>
  <p>The hearth is the oldest technology a home ever had: the fire at the center of it. It's where the warmth came from, where the light was, where people gathered at the end of the day. And it <em>belonged to the house</em> &mdash; nobody billed you monthly for your own fire.</p>
  <p>That's the whole idea here. &ldquo;The cloud&rdquo; is somebody else's fire, burning far away, and you rent a little of its heat. Hearth is the opposite: a small, warm intelligence that lives where you live. That's why the AI appears as a glowing ember &mdash; a quiet fire, at home, on your machine. <em>A warm light in a cold cloud.</em></p>

  <h2 class="serif">How can it really be free?</h2>
  <p>People ask what the catch is, and it's a fair question &mdash; &ldquo;free&rdquo; usually means <em>you're</em> the product. So here it is, plainly. Hearth is free for three reasons:</p>
  <ul>
    <li><strong>For the curious.</strong> A lot of people are AI-curious but not ready to hand a credit card &mdash; and their conversations &mdash; to a tech giant. There should be a free, safe first seat at the fire. This is it.</li>
    <li><strong>For the love of it.</strong> I'm an engineer. I love technology, I love AI, and I love building things. Hearth is the thing I make because I can't <em>not</em> make it.</li>
    <li><strong>Because it was missing.</strong> Beautiful, private AI for everyday people simply didn't exist &mdash; local AI was for tinkerers with terminals. That hole bugged me. So I filled it.</li>
  </ul>
  <p>And what's in it for me? My name on work I'm proud of. Since Hearth runs on <em>your</em> computer, it costs me almost nothing to give away &mdash; there's no server bill ticking, no data worth selling, no investor waiting for a return. The economics of free are real, not a trick. If you're still wondering, the <a href="https://hearth.kevinchamplin.com/faq">FAQ</a> answers the catch-questions head on.</p>

  <h2 class="serif">Why does it look so warm?</h2>
  <p>Most AI tools feel cold and technical &mdash; all blue glow and robot icons, the kind of thing that makes a normal person feel like they're doing it wrong. Hearth is built to feel like a warm room you'd actually want to sit in: a quiet ember, soft light, plain words. If your mom or dad can open it and start talking in five seconds, it's doing its job.</p>

  <figure class="ph">
    <img src="/assets/photos/reading-nook.jpg" alt="A quiet, sunlit reading corner" width="1600" height="1067" loading="lazy">
    <figcaption>Made to feel like a room, not a control panel.</figcaption>
  </figure>

  <h2 class="serif">Who made Hearth?</h2>
  <p>
    <img src="/assets/kevin-champlin.png" alt="Kevin Champlin" width="96" height="96" loading="lazy" style="float:left;width:84px;height:84px;border-radius:50%;object-fit:cover;margin:4px 20px 10px 0;box-shadow:0 8px 22px rgba(43,39,32,.18)">
    Hearth is built by <a href="https://kevinchamplin.com">Kevin Champlin</a>, an AI engineer who believes some of AI's future belongs right here &mdash; on the machine in front of you, warm and private and yours, not only in distant data centers. It's built in the open &mdash; the full source is public, you can read every line, and contributions are welcome. (One ask: Hearth stays Hearth &mdash; the source is for reading, learning, and improving, not for cloning into a competing product.) If you'd like the longer thinking behind it, that's what the <a href="https://hearth.kevinchamplin.com/blog">blog</a> is for.
  </p>

  <p style="clear:both">Bring your AI home before the meter starts. It takes about a minute, it costs nothing, and it's yours to keep.</p>

  <p style="margin-top:30px"><a href="/app" style="display:inline-block;background:radial-gradient(circle at 35% 30%,#FFD89A,#EE9A45 78%);color:#3A2206;font-weight:700;padding:14px 28px;border-radius:12px;text-decoration:none">Try Hearth free &rarr;</a></p>
</div></main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
