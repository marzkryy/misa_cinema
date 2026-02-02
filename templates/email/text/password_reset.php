Hello <?= $user->name ?> (<?= ucfirst($role) ?>),

You have requested to reset your password for your <?= $role ?> account.
Please click the link below to reset your password:

<?= \Cake\Routing\Router::url(['controller' => 'Customers', 'action' => 'resetPassword', $token, '?' => ['role' => $role]], true) ?>


If you did not request this, please ignore this email.

Regards,
Admin MISA Cinema
