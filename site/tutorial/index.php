<?php
$title = 'How to use Hearth — the simple local AI tutorial';
$desc  = 'How do I use Hearth? Open it in your browser, let it download once, and start typing — no account, no setup. A plain-words, picture-by-picture guide to chatting, showing it photos, switching brains, finding past conversations, and installing it like an app.';
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';

$steps = [
  ['n' => 1, 'name' => 'Open Hearth in your browser',
   'text' => 'Go to hearth.kevinchamplin.com/app in Chrome or Edge and click the big "Light the hearth" button. No account, no sign-up, no email — you are in.',
   'img'  => 'https://hearth.kevinchamplin.com/assets/tutorial/1-light.png'],
  ['n' => 2, 'name' => 'Let it download once',
   'text' => 'The first visit downloads Hearth onto your own computer — a few minutes, just once. After that it opens in a blink and keeps working even with no internet.',
   'img'  => 'https://hearth.kevinchamplin.com/assets/tutorial/2-download.png'],
  ['n' => 3, 'name' => 'Start typing',
   'text' => 'Type in the box at the bottom and press Enter. Ask for a recipe, help wording an email, or "explain this like I am 10." Hover any answer to copy it, or print the whole conversation. You can also show it a photo — the picture never leaves your computer.',
   'img'  => 'https://hearth.kevinchamplin.com/assets/tutorial/3-chat.png'],
  ['n' => 4, 'name' => 'Choose how clever it is',
   'text' => 'Tap the brain button in the top-right. Hearth has three brains — Fast, Smart, and Genius. Cleverer brains give better answers but need a stronger computer. Hearth checks yours and tells you which ones fit, in plain words.',
   'img'  => 'https://hearth.kevinchamplin.com/assets/tutorial/4-brains.png'],
  ['n' => 5, 'name' => 'Find your past conversations',
   'text' => 'Tap the chat-bubble button in the top-left. Every conversation saves by itself — on your device only, never uploaded — so you can pick any of them back up where you left off.',
   'img'  => 'https://hearth.kevinchamplin.com/assets/tutorial/5-chats.png'],
];

$howToSteps = [];
foreach ($steps as $s) {
  $howToSteps[] = [
    '@type' => 'HowToStep',
    'position' => $s['n'],
    'name' => $s['name'],
    'text' => $s['text'],
    'image' => $s['img'],
    'url'  => 'https://hearth.kevinchamplin.com/tutorial#step' . $s['n'],
  ];
}

echo '<script type="application/ld+json">' . json_encode([
  '@context' => 'https://schema.org',
  '@graph' => [
    [
      '@type' => 'HowTo',
      '@id'   => 'https://hearth.kevinchamplin.com/tutorial#howto',
      'name'  => 'How to use Hearth',
      'description' => 'Use Hearth in about a minute: open it in your browser, let it download once, and start typing. No account, no setup — a private AI that runs on your own computer.',
      'totalTime' => 'PT1M',
      'image' => 'https://hearth.kevinchamplin.com/assets/og.png?v=2',
      'tool'  => [['@type' => 'HowToTool', 'name' => 'A web browser (Chrome or Edge work best)']],
      'step'  => $howToSteps,
    ],
    [
      '@type' => 'BreadcrumbList',
      'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://hearth.kevinchamplin.com/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'How to use', 'item' => 'https://hearth.kevinchamplin.com/tutorial'],
      ],
    ],
  ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
?>
<main class="page"><div class="wrap" style="max-width:780px">
  <p class="eyebrow">The simple guide</p>
  <h1 class="serif">How to use Hearth.</h1>
  <p class="lead">Hearth takes about a minute to start using: open it in your browser, let it download once, and start typing — no account, no setup. If you can send a text message, you can use Hearth.</p>

  <div class="tut">
    <div class="tut-step" id="step1">
      <div class="tut-num">1</div>
      <div class="tut-body">
        <h2 class="serif">Open it in your browser</h2>
        <p>Go to <a href="https://hearth.kevinchamplin.com/app">hearth.kevinchamplin.com/app</a> in <strong>Chrome or Edge</strong> and click the big <strong>&ldquo;Light the hearth&rdquo;</strong> button. That&rsquo;s it — no account, no sign-up, no email.</p>
        <img src="/assets/tutorial/1-light.png" alt="Hearth's start screen with the Light the hearth button" width="1180" height="760" loading="lazy" />
      </div>
    </div>

    <div class="tut-step" id="step2">
      <div class="tut-num">2</div>
      <div class="tut-body">
        <h2 class="serif">Let it download once</h2>
        <p>The first visit copies Hearth onto your own computer — <strong>a few minutes, just once</strong>. While you wait, it shows you little tips. After today, Hearth opens in a blink and <strong>keeps working even with no internet</strong>.</p>
        <img src="/assets/tutorial/2-download.png" alt="The one-time download screen with a progress bar and tips" width="1180" height="760" loading="lazy" />
      </div>
    </div>

    <div class="tut-step" id="step3">
      <div class="tut-num">3</div>
      <div class="tut-body">
        <h2 class="serif">Start typing</h2>
        <p>Type in the box at the bottom and press <strong>Enter</strong> to send. Ask anything — a recipe from what&rsquo;s in the fridge, help wording a tricky email, &ldquo;explain this like I&rsquo;m 10.&rdquo; Hover over any answer to <strong>copy</strong> it, or use the <strong>printer button</strong> (top right) to print the whole conversation.</p>
        <img src="/assets/tutorial/3-chat.png" alt="A Hearth conversation answering a dinner question" width="1180" height="760" loading="lazy" />
        <p class="tut-tip">📎 <strong>Show it a photo:</strong> tap the paperclip next to the box, pick a picture, and ask about it. The photo never leaves your computer.</p>
      </div>
    </div>

    <div class="tut-step" id="step4">
      <div class="tut-num">4</div>
      <div class="tut-body">
        <h2 class="serif">Choose how clever it is</h2>
        <p>Tap the <strong>brain button</strong> in the top-right corner (it says ⚡ Fast). Hearth has three brains — <strong>Fast, Smart, and Genius</strong>. A cleverer brain gives better answers, but takes a bigger one-time download and needs a stronger computer. Hearth checks <em>your</em> computer and tells you which ones fit, in plain words.</p>
        <img src="/assets/tutorial/4-brains.png" alt="The Choose Hearth's brain panel showing Fast, Smart and Genius with plain-language fit verdicts" width="1180" height="760" loading="lazy" />
      </div>
    </div>

    <div class="tut-step" id="step5">
      <div class="tut-num">5</div>
      <div class="tut-body">
        <h2 class="serif">Find your past conversations</h2>
        <p>Tap the <strong>💬 chat-bubble button</strong> in the top-left corner. Every conversation saves by itself — <strong>on your device only, never uploaded</strong> — so you can pick any of them back up right where you left off. Like having it handy? Tap <strong>📌 Pin open</strong> to keep the list on screen, just like other chat apps.</p>
        <img src="/assets/tutorial/5-chats.png" alt="The conversations panel pinned open beside a chat" width="1180" height="760" loading="lazy" />
      </div>
    </div>

    <div class="tut-step" id="step6">
      <div class="tut-num">6</div>
      <div class="tut-body">
        <h2 class="serif">Keep it forever (optional)</h2>
        <p>Want Hearth in your Dock like a real app? Click the <strong>⬇ Install</strong> button at the top of Hearth — or follow the <a href="/#install">step-by-step install guide</a> for your exact browser. It opens in its own window and works fully offline.</p>
        <p class="tut-tip">✈️ <strong>The party trick:</strong> once Hearth is set up, turn your wifi off mid-conversation. It keeps right on going — because it&rsquo;s running on <em>your</em> computer, not in the cloud.</p>
      </div>
    </div>
  </div>

  <p style="margin-top:36px"><a href="/app" style="display:inline-block;background:radial-gradient(circle at 35% 30%,#FFD89A,#EE9A45 78%);color:#3A2206;font-weight:700;padding:14px 28px;border-radius:12px;text-decoration:none">Open Hearth &rarr;</a></p>
  <p style="font-size:15px;color:var(--ink-soft)">Curious how it stacks up? See <a href="/compare" style="color:var(--brass-text)">Hearth next to other AI apps</a>, read <a href="/faq" style="color:var(--brass-text)">the FAQ</a> for honest answers, or <a href="/contact" style="color:var(--brass-text)">send a note</a> — it goes straight to the builder.</p>
</div></main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
