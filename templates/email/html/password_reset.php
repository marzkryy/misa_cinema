<p>Hello <?= h($user->name) ?> (<?= ucfirst(h($role)) ?>),</p>

<p>You have requested to reset your password for your <?= h($role) ?> account.</p>
<p>Please click the link below to reset your password:</p>

<p><a href="<?= \Cake\Routing\Router::url(['controller' => 'Customers', 'action' => 'resetPassword', $token, '?' => ['role' => $role]], true) ?>">Reset Password</a></p>

<p>If you did not request this, please ignore this email.</p>

<p>Regards,<br>
<strong>Admin MISA Cinema</strong></p>
