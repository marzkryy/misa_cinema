<!DOCTYPE html>
<html>
<head>
    <style>
        .button {
            background-color: #dc3545;
            color: white !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2 style="color: #dc3545;">Verify Your New Email Address</h2>
    <p>Hello <strong><?= h($staff->name) ?></strong>,</p>
    <p>A request was made to update your staff email address for MisaCinema.</p>
    <p>To confirm this change and verify your new email address, please click the button below:</p>
    
    <p style="text-align: center; margin: 30px 0;">
        <a href="<?= $this->Url->build(['_full' => true, 'controller' => 'Staffs', 'action' => 'verifyEmailChange', $token]) ?>" class="button">Confirm Email Change</a>
    </p>
    
    <p>If you did not request this change, please ignore this email or contact the system administrator.</p>
    <br>
    <p>Regards,<br><strong>Admin MISA Cinema</strong></p>
</body>
</html>
