<?php
$notice = null; $ok = false;
$name = ''; $email = ''; $message = '';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $hp      = trim($_POST['website'] ?? ''); // honeypot — humans never fill this

    if ($hp !== '') {
        // Bot. Pretend success, send nothing.
        $ok = true; $notice = 'Thank you — your message is on its way.';
        $name = $email = $message = '';
    } elseif ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
        $notice = 'Please add your name, a valid email, and a short message.';
    } else {
        $sent = false;
        // First choice: file a real CE Support ticket (threaded conversation, branded
        // confirmation email to the sender). Mailgun below remains the safety net.
        $cs = @include '/var/www/vhosts/kevinchamplin.com/hearth-analytics-data/cesupport.php';
        if (is_array($cs) && !empty($cs['secret'])) {
            if (!defined('CE_SUPPORT_BASE')) {
                define('CE_SUPPORT_BASE', $cs['base']);
                define('CE_SUPPORT_PRODUCT_SLUG', $cs['slug']);
                define('CE_SUPPORT_API_SECRET', $cs['secret']);
            }
            require_once __DIR__ . '/../lib/ce-support.php';
            $r = ce_support_file_ticket([
                'customer_email' => $email,
                'customer_name'  => $name,
                'subject'        => 'Hearth — ' . mb_substr($message, 0, 60),
                'body'           => $message . "\n\n— sent from hearth.kevinchamplin.com/contact",
            ]);
            $sent = !empty($r['ok']);
        }
        $mg = $sent ? null : @include '/var/www/vhosts/kevinchamplin.com/hearth-analytics-data/mailgun.php';
        if (is_array($mg) && !empty($mg['key'])) {
            $endpoint = rtrim($mg['endpoint'] ?: 'https://api.mailgun.net', '/');
            $url  = $endpoint . '/v3/' . $mg['domain'] . '/messages';
            $post = [
                'from'       => 'Hearth Contact <noreply@' . $mg['domain'] . '>',
                'to'         => $mg['to'] ?? 'kevin@kevinchamplin.com',
                'h:Reply-To' => $email,
                'subject'    => 'Hearth contact — ' . mb_substr($name, 0, 60),
                'text'       => "From: $name <$email>\n\n$message\n\n— via hearth.kevinchamplin.com/contact",
                'o:tag'      => 'hearth-contact',
            ];
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_USERPWD => 'api:' . $mg['key'],
                CURLOPT_POSTFIELDS => http_build_query($post),
                CURLOPT_TIMEOUT => 15,
            ]);
            curl_exec($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $sent = ($code >= 200 && $code < 300);
        }
        if ($sent) {
            $ok = true; $notice = "Thank you — your message is in. Check your email for a confirmation; I'll get back to you soon.";
            $name = $email = $message = '';
        } else {
            $notice = "Hmm, that didn't go through. You can email me directly at kevin@kevinchamplin.com.";
        }
    }
}

$title = 'Contact — Hearth';
$desc  = 'Questions or ideas about Hearth? Send a note — it comes straight to Kevin.';
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
$h = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES);
?>
<main class="page"><div class="wrap">
  <p class="eyebrow">Contact</p>
  <h1 class="serif">Say hello.</h1>
  <p class="lead">A question, an idea, or just want to tell me Hearth made you smile? Send a note &mdash; it comes straight to me.</p>

  <?php if ($notice): ?><div class="notice <?= $ok ? 'ok' : 'err' ?>"><?= $h($notice) ?></div><?php endif; ?>

  <form class="form" method="post" action="/contact/">
    <div class="field"><label for="name">Your name</label><input id="name" name="name" value="<?= $h($name) ?>" required></div>
    <div class="field"><label for="email">Your email</label><input id="email" name="email" type="email" value="<?= $h($email) ?>" required></div>
    <div class="field"><label for="message">Message</label><textarea id="message" name="message" required><?= $h($message) ?></textarea></div>
    <div class="hp"><label>Website<input name="website" tabindex="-1" autocomplete="off"></label></div>
    <button class="btn-send" type="submit">Send message</button>
  </form>

  <p class="contact-alt">Prefer email? Reach me directly at <a href="mailto:kevin@kevinchamplin.com">kevin@kevinchamplin.com</a>.</p>
</div></main>
<?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
