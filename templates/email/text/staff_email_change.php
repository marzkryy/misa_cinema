Verify Your New Email Address - MisaCinema

Hello <?= h($staff->name) ?>,

A request was made to update your staff email address for MisaCinema.

To confirm this change and verify your new email address, please click the link below:

<?= $this->Url->build(['_full' => true, 'controller' => 'Staffs', 'action' => 'verifyEmailChange', $token]) ?>

If you did not request this change, please ignore this email or contact the system administrator.

Regards,
Admin MISA Cinema
