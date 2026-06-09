<?php
$title = 'Hearth FAQ — is it really free, and really private?';
$desc  = 'Honest answers about Hearth: cost, privacy, what you need to run it, and whether it works offline.';
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';

$faqs = [
  ['Is Hearth really free?',
   'Yes &mdash; completely, and forever. It runs on your own computer, so there\'s nothing for us to bill you for. No subscription, no credit card, no &ldquo;free trial&rdquo; that ends.'],
  ['Is it actually private?',
   'Yes, by design. The AI runs on your device, so your conversations are never sent to us or anyone else. There\'s no server in the middle that could read them.'],
  ['Can you see my conversations?',
   'No &mdash; we can\'t. The model lives on your machine, so there is literally nothing on our end that could read what you type. We don\'t want your data; the whole point is that it stays with you.'],
  ['What do I need to run it?',
   'A reasonably modern computer and the Chrome or Edge browser for the instant, no-install version. The first time you open it, it downloads the AI once (about a gigabyte). After that it loads quickly.'],
  ['Does it work offline?',
   'Yes &mdash; after that first download. On a plane, in a cabin, with the wifi switched off, Hearth keeps right on working.'],
  ['Do I need to make an account?',
   'Nope. You open it and start talking. No sign-up, no login, no email required.'],
  ['Can it look at my photos?',
   'Yes. You can attach an image and ask about it, and that runs privately on your device too &mdash; the picture never leaves your computer.'],
  ['Is it as smart as ChatGPT?',
   'Honestly, not quite &mdash; the free version runs a smaller model so it can fit on your device. It\'s wonderful for everyday questions, explanations, writing help, and brainstorming. A more powerful desktop version, with bigger models, is on the way.'],
  ['So what\'s the catch?',
   'There isn\'t one. Hearth is a passion project, built because it should exist. It\'s <a href="https://github.com/Kevinchamplin/hearth">open source</a>, so you can read every line of it yourself.'],
  ['Who made this?',
   'Kevin Champlin, an AI engineer. You can find more of his work at <a href="https://kevinchamplin.com">kevinchamplin.com</a>, or just <a href="/contact">say hello</a>.'],
];
?>
<main class="page"><div class="wrap">
  <p class="eyebrow">Questions &amp; answers</p>
  <h1 class="serif">The honest FAQ.</h1>
  <p class="lead">No fine print, no spin. Here's what people ask most.</p>
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
