<?php
/**
 * @var \App\View\AppView $this
 * @var array $data
 */
?>
<div style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #121212; color: #ffffff; border-radius: 15px; overflow: hidden; border: 1px solid #333;">
    <!-- Header -->
    <div style="background-color: #e50914; padding: 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: bold; text-transform: uppercase; color: #ffffff;">New User Inquiry</h1>
        <p style="margin: 5px 0 0; font-size: 14px; opacity: 0.8; color: #ffffff;">MisaCinema Support System</p>
    </div>
    
    <div style="padding: 30px;">
        <!-- Logo/Brand -->
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #e50914; margin: 0; font-size: 28px; font-weight: bold;">MISA CINEMA</h2>
            <p style="margin: 5px 0; font-size: 14px; color: #b3b3b3;">Customer Feedback & Support</p>
        </div>

        <!-- Info Table -->
        <div style="background-color: #1a1a1a; padding: 20px; border-radius: 10px; margin-bottom: 25px; border: 1px solid #333;">
            <h3 style="margin: 0 0 15px; font-size: 14px; color: #b3b3b3; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #333; padding-bottom: 10px;">Reporter Details</h3>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-size: 14px; color: #b3b3b3; width: 40%;">Name:</td>
                    <td style="padding: 8px 0; font-size: 14px; text-align: right; font-weight: bold; color: #ffffff;">
                        <?= h($data['name']) ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-size: 14px; color: #b3b3b3;">Email:</td>
                    <td style="padding: 8px 0; font-size: 14px; text-align: right; font-weight: bold; color: #ffffff;">
                        <a href="mailto:<?= h($data['email']) ?>" style="color: #e50914; text-decoration: none;"><?= h($data['email']) ?></a>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-size: 14px; color: #b3b3b3;">Subject:</td>
                    <td style="padding: 8px 0; font-size: 14px; text-align: right; font-weight: bold; color: #ffc107;">
                        <?= h($data['subject']) ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-size: 14px; color: #b3b3b3;">Time:</td>
                    <td style="padding: 8px 0; font-size: 14px; text-align: right; font-weight: bold; color: #ffffff;">
                        <?= date('d M Y, h:i A') ?>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Message Box -->
        <div style="margin-bottom: 30px;">
            <label style="display: block; font-size: 12px; color: #b3b3b3; text-transform: uppercase; font-weight: bold; margin-bottom: 10px;">Message Content:</label>
            <div style="background-color: #2a2a2a; padding: 20px; border-radius: 8px; border-left: 4px solid #e50914; color: #e0e0e0; line-height: 1.6; white-space: pre-wrap;">
<?= h($data['message']) ?>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="mailto:<?= h($data['email']) ?>" style="background-color: #e50914; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 25px; font-weight: bold; font-size: 14px; display: inline-block;">Reply to Customer</a>
        </div>
    </div>
    
    <!-- Footer -->
    <div style="background-color: #000000; padding: 20px; text-align: center; font-size: 11px; color: #666; border-top: 1px solid #333;">
        <p style="margin: 0;">&copy; <?= date('Y') ?> MisaCinema Management System. All rights reserved.</p>
        <p style="margin: 5px 0 0;">This email was sent automatically from the website.</p>
    </div>
</div>
