<?php
/** @var string $appName */
/** @var string $resetUrl */
/** @var string $supportEmail */
/** @var string $primaryColor */
/** @var string $accentColor */
/** @var string $textColor */
/** @var string $logoUrl */
/** @var array  $footerLinks */
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= esc($appName) ?> Password Reset</title>
  <style>
    body { background:#f5f7fb; margin:0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, 'Helvetica Neue', sans-serif; color: <?= esc($textColor) ?>; }
    .container { max-width:600px; margin:0 auto; padding:24px; }
    .card { background:#fff; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.06); overflow:hidden; }
    .header { background: <?= esc($primaryColor) ?>; padding:20px; text-align:center; }
    .header img { height:48px; }
    .content { padding:28px; }
    h1 { margin:0 0 12px; font-size:22px; color:#0f172a; }
    p { margin: 0 0 10px; line-height:1.55; }
    .cta { display:inline-block; background: <?= esc($accentColor) ?>; color:#fff; padding:12px 20px; border-radius:8px; text-decoration:none; font-weight:600; margin-top:14px; }
    .muted { color:#64748b; font-size:14px; }
    .footer { padding:18px; text-align:center; font-size:12px; color:#64748b; }
    .links a { color:#64748b; margin:0 8px; text-decoration:none; }
  </style>
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="header">
        <img src="<?= esc($logoUrl) ?>" alt="<?= esc($appName) ?>">
      </div>
      <div class="content">
        <h1>Reset your password</h1>
        <p>We received a request to reset your password for your <?= esc($appName) ?> account.</p>
        <p>Click the button below to set a new password. This link will expire in 1 hour.</p>
        <p><a href="<?= esc($resetUrl) ?>" class="cta">Reset Password</a></p>
        <p class="muted">If the button doesn't work, copy and paste this URL into your browser:</p>
        <p class="muted" style="word-break: break-all;"><?= esc($resetUrl) ?></p>
        <p class="muted">If you didn't request a password reset, you can safely ignore this email.</p>
        <?php if (!empty($supportEmail)): ?>
          <p class="muted">Need help? Contact us at <a href="mailto:<?= esc($supportEmail) ?>"><?= esc($supportEmail) ?></a>.</p>
        <?php endif; ?>
      </div>
      <div class="footer">
        <div class="links">
          <?php foreach (($footerLinks ?? []) as $label => $href): ?>
            <a href="<?= esc($href) ?>"><?= esc($label) ?></a>
          <?php endforeach; ?>
        </div>
        <div style="margin-top:8px;">&copy; <?= date('Y') ?> <?= esc($appName) ?>. All rights reserved.</div>
      </div>
    </div>
  </div>
</body>
</html>
