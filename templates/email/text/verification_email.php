Hello <?= h($user->name) ?> (<?= ucfirst(h($role)) ?>),

Thank you for registering with MisaCinema! Please use the link below to verify your email address and activate your account:

<?= \Cake\Routing\Router::url(['controller' => ($role === 'admin' ? 'Staffs' : 'Customers'), 'action' => 'verifyEmail', $token], true) ?>

If you did not create an account, no further action is required.

Regards,
Admin MISA Cinema
