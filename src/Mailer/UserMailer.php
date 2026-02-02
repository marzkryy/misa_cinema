<?php
declare(strict_types=1);

namespace App\Mailer;

use Cake\Mailer\Mailer;

class UserMailer extends Mailer
{
    public function forgotPassword($user, $role)
    {
        $this
            ->setTransport('default')
            ->setFrom(['misacinemaa@gmail.com' => 'Admin MISA Cinema'])
            ->setTo($user->email)
            ->setSubject('Password Reset Request (' . ucfirst($role) . ') - MisaCinema')
            ->setViewVars([
                'user' => $user,
                'role' => $role,
                'token' => $user->reset_token
            ])
            ->viewBuilder()
                ->setTemplate('password_reset');
    }

    public function verificationEmail($user, $role)
    {
        $this
            ->setTransport('default')
            ->setFrom(['misacinemaa@gmail.com' => 'Admin MISA Cinema'])
            ->setTo($user->email)
            ->setSubject('Verify Your Account - MisaCinema')
            ->setViewVars([
                'user' => $user,
                'role' => $role,
                'token' => $user->verification_token
            ])
            ->viewBuilder()
                ->setTemplate('verification_email');
    }

    public function emailChangeCode($user, $code, $isNew)
    {
        $targetEmail = $isNew ? $user->temp_email : $user->email;
        $subject = $isNew ? 'Verify Your New Email - MisaCinema' : 'Confirm Your Email Change - MisaCinema';

        $this
            ->setTransport('default')
            ->setFrom(['misacinemaa@gmail.com' => 'Admin MISA Cinema'])
            ->setTo($targetEmail)
            ->setSubject($subject)
            ->setViewVars([
                'user' => $user,
                'code' => $code,
                'isNew' => $isNew
            ])
            ->viewBuilder()
                ->setTemplate('email_change_code');
    }
    public function staffEmailChangeLink($staff, $token)
    {
        $this
            ->setTransport('default')
            ->setFrom(['misacinemaa@gmail.com' => 'Admin MISA Cinema'])
            ->setTo($staff->temp_email)
            ->setSubject('Confirm Your New Email Address - MisaCinema')
            ->setViewVars([
                'staff' => $staff,
                'token' => $token
            ])
            ->viewBuilder()
                ->setTemplate('staff_email_change');
    }

    public function bookingReceipt($booking)
    {
        $this
            ->setTransport('default')
            ->setFrom(['misacinemaa@gmail.com' => 'Admin MISA Cinema'])
            ->setTo($booking->customer->email)
            ->setSubject('Booking Confirmation & Ticket - ' . h($booking->customer->name) . ' - MisaCinema')
            ->setViewVars([
                'booking' => $booking
            ])
            ->setEmailFormat('html')
            ->viewBuilder()
                ->setTemplate('booking_receipt');
    }
}
