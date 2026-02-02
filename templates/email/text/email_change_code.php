Hello <?= h($user->name) ?>,

<?php if ($isNew): ?>
You are verifying your NEW email address for your MisaCinema account.
Please enter the following 4-digit verification code to complete the change:
<?php else: ?>
You have requested to change your email address for your MisaCinema account.
Please enter the following 4-digit verification code to confirm it's you:
<?php endif; ?>

Verification Code: <?= h($code) ?>

This code will expire in 15 minutes.

If you did not request this change, please ignore this email and ensure your account is secure.

Regards,
Admin MISA Cinema
