<p>Hello <?= h($user->name) ?>,</p>

<?php if ($isNew): ?>
    <p>You are verifying your <strong>new</strong> email address for your MisaCinema account.</p>
    <p>Please enter the following 4-digit verification code to complete the change:</p>
<?php else: ?>
    <p>You have requested to change your email address for your MisaCinema account.</p>
    <p>Please enter the following 4-digit verification code to confirm it's you:</p>
<?php endif; ?>

<div style="background-color: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; text-align: center; border-radius: 10px; margin: 20px 0;">
    <h1 style="color: #dc3545; font-size: 3rem; letter-spacing: 10px; margin: 0;"><?= h($code) ?></h1>
    <p style="color: #6c757d; margin-top: 10px;">This code will expire in 15 minutes.</p>
</div>

<p>If you did not request this change, please ignore this email and ensure your account is secure.</p>

<p>Regards,<br>
<strong>Admin MISA Cinema</strong></p>
