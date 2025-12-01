<?php
// Data passed: $appName, $verifyUrl, $supportEmail, $primaryColor, $accentColor, $textColor, $logoUrl, $footerLinks
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify your email</title>
    <style>
        /* Use inline styles as much as possible for email clients */
        :root { color-scheme: light; }
        body { margin:0; padding:0; background:#f6f7fb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Helvetica Neue', Arial, sans-serif; }
        .wrapper { width:100%; background:#f6f7fb; padding:24px 0; }
        .container { width:100%; max-width:640px; margin:0 auto; background:#ffffff; border-radius:12px; box-shadow:0 6px 24px rgba(0,0,0,0.06); overflow:hidden; }
        .brand { text-align:center; padding:24px 24px 8px; background: <?= htmlspecialchars($primaryColor ?? '#0d6efd', ENT_QUOTES) ?>; }
        .brand img { max-width:160px; height:auto; display:block; margin:0 auto 8px; }
        .brand h1 { margin:0; font-size:20px; color:#fff; font-weight:600; letter-spacing:.2px; }
        .content { padding:28px 28px 8px; color: <?= htmlspecialchars($textColor ?? '#1f2937', ENT_QUOTES) ?>; }
        .content h2 { margin:0 0 12px; font-size:22px; color: <?= htmlspecialchars($primaryColor ?? '#0d6efd', ENT_QUOTES) ?>; }
        .content p { margin:0 0 16px; line-height:1.6; font-size:15px; }
        .card { background:#f9fafb; border:1px solid #eef2f7; border-radius:10px; padding:16px; margin:12px 0 20px; }
        .btn { display:inline-block; padding:12px 20px; border-radius:10px; text-decoration:none; font-weight:600; letter-spacing:.2px; color:#fff; background: <?= htmlspecialchars($accentColor ?? '#22c55e', ENT_QUOTES) ?>; box-shadow:0 8px 16px rgba(34,197,94,.25); }
        .btn:hover { filter: brightness(1.05); }
        .muted { color:#6b7280; font-size:13px; }
        .code { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace; background:#111827; color:#f9fafb; padding:10px 12px; border-radius:8px; display:block; margin:8px 0; font-size:13px; }
        .footer { text-align:center; padding:18px; color:#6b7280; font-size:12px; }
        .links a { color: <?= htmlspecialchars($primaryColor ?? '#0d6efd', ENT_QUOTES) ?>; text-decoration:none; margin:0 6px; }
        @media (prefers-color-scheme: dark) {
          body { background:#0b0e14; }
          .container { background:#0f1420; box-shadow:none; }
          .content { color:#e5e7eb; }
          .card { background:#0b0e14; border-color:#1f2937; }
          .muted { color:#9aa4b2; }
          .footer { color:#9aa4b2; }
        }
    </style>
  </head>
  <body>
    <div class="wrapper">
      <div class="container" role="article">
        <div class="brand">
          <?php if (!empty($logoUrl)): ?>
            <img src="<?= htmlspecialchars($logoUrl, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($appName ?? 'Tuklas Nasugbu', ENT_QUOTES) ?> logo">
          <?php endif; ?>
          <h1><?= htmlspecialchars($appName ?? 'Tuklas Nasugbu', ENT_QUOTES) ?></h1>
        </div>
        <div class="content">
          <h2>Verify your Gmail</h2>
          <p>Thanks for signing up! To keep our community safe, please verify that this Gmail address belongs to you.</p>

          <div class="card">
            <p style="margin:0 0 12px;">Click the button below to verify your email and complete your account setup.</p>
            <p style="margin:0;"><a class="btn" href="<?= htmlspecialchars($verifyUrl, ENT_QUOTES) ?>" target="_blank" rel="noopener">Verify Email</a></p>
          </div>

          <p class="muted">If the button doesn't work, copy and paste this link into your browser:</p>
          <span class="code"><?= htmlspecialchars($verifyUrl, ENT_QUOTES) ?></span>

          <p class="muted">This link will expire in 24 hours. If you didn't create an account, you can safely ignore this email.</p>

          <?php if (!empty($supportEmail)): ?>
            <p class="muted">Need help? Contact us at <a href="mailto:<?= htmlspecialchars($supportEmail, ENT_QUOTES) ?>" style="color: <?= htmlspecialchars($primaryColor ?? '#0d6efd', ENT_QUOTES) ?>; text-decoration:none;"><?= htmlspecialchars($supportEmail, ENT_QUOTES) ?></a>.</p>
          <?php endif; ?>
        </div>
        <div class="footer">
          <div class="links">
            <?php if (!empty($footerLinks) && is_array($footerLinks)): ?>
              <?php foreach ($footerLinks as $text => $url): ?>
                <a href="<?= htmlspecialchars($url, ENT_QUOTES) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($text, ENT_QUOTES) ?></a>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <div style="margin-top:8px;">&copy; <?= date('Y') ?> <?= htmlspecialchars($appName ?? 'Tuklas Nasugbu', ENT_QUOTES) ?>. All rights reserved.</div>
        </div>
      </div>
    </div>
  </body>
</html>
