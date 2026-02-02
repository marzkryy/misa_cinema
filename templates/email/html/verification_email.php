<p>Hello <?= h($user->name) ?> (<?= ucfirst(h($role)) ?>),</p>

<p>Thank you for registering with MisaCinema! Please click the link below to verify your email address and activate your account:</p>

<p>
    <a href="<?= \Cake\Routing\Router::url(['controller' => ($role === 'admin' ? 'Staffs' : 'Customers'), 'action' => 'verifyEmail', $token], true) ?>" 
       style="background-color: #dc3545; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">
        Verify Email Address
    </a>
</p>

<p>If you cannot click the link above, copy and paste this URL into your browser:</p>
<p><?= \Cake\Routing\Router::url(['controller' => ($role === 'admin' ? 'Staffs' : 'Customers'), 'action' => 'verifyEmail', $token], true) ?></p>

<p>If you did not create an account, no further action is required.</p>

<p>Regards,<br>
<strong>Admin MISA Cinema</strong></p>
