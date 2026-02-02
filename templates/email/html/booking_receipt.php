<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
$timeObj = ($booking->show->show_time instanceof \DateTimeInterface) ? $booking->show->show_time : new \DateTime($booking->show->show_time);
$bookingId = '#MC-' . str_pad((string)$booking->id, 6, '0', STR_PAD_LEFT);
?>
<div style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; max-width: 600px; margin: 0 auto; background-color: #121212; color: #ffffff; border-radius: 15px; overflow: hidden; border: 1px solid #333;">
    <div style="background-color: #e50914; padding: 30px; text-align: center;">
        <h1 style="margin: 0; font-size: 24px; font-weight: bold; text-transform: uppercase; color: #ffffff;">Booking Receipt</h1>
        <p style="margin: 5px 0 0; font-size: 14px; opacity: 0.8; color: #ffffff;">Thank you for choosing MisaCinema!</p>
    </div>
    
    <div style="padding: 30px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #e50914; margin: 0; font-size: 28px; font-weight: bold;">MISA CINEMA</h2>
            <p style="margin: 5px 0; font-size: 14px; color: #b3b3b3;">Experience the Best in Cinema</p>
        </div>

        <!-- Digital Ticket Section -->
        <div style="text-align: center; margin-bottom: 30px; background-color: #1a1a1a; padding: 20px; border-radius: 12px; border: 1px dashed #e50914;">
            <label style="display: block; font-size: 11px; color: #b3b3b3; text-transform: uppercase; font-weight: bold; margin-bottom: 10px; letter-spacing: 2px;">Digital Ticket</label>
            <div style="background-color: #ffffff; padding: 10px; display: inline-block; border-radius: 8px; margin-bottom: 10px;">
                <?php
                $qrData = "BOOKING-MC" . str_pad((string)$booking->id, 6, '0', STR_PAD_LEFT);
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);
                ?>
                <img src="<?= $qrUrl ?>" alt="Ticket QR Code" style="display: block; width: 150px; height: 150px;">
            </div>
            <p style="margin: 5px 0 0; font-size: 12px; color: #b3b3b3; font-family: monospace;">
                MC-<?= str_pad((string)$booking->id, 6, '0', STR_PAD_LEFT) ?>
                <?php if (!empty($booking->is_student)): ?>
                    <span style="background-color: #ffc107; color: #000; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; margin-left: 10px; vertical-align: middle;">STUDENT</span>
                <?php endif; ?>
            </p>
        </div>

        <div style="text-align: center; margin-bottom: 30px; border-top: 1px solid #333; border-bottom: 1px solid #333; padding: 15px 0;">
            <label style="display: block; font-size: 11px; color: #b3b3b3; text-transform: uppercase; font-weight: bold; margin-bottom: 5px;">Movie Title</label>
            <span style="font-size: 24px; font-weight: bold; color: #e50914;"><?= h($booking->show->show_title) ?></span>
        </div>

        <div style="background-color: #1a1a1a; padding: 20px; border-radius: 10px; margin-bottom: 25px; border: 1px solid #333;">
            <h3 style="margin: 0 0 15px; font-size: 14px; color: #b3b3b3; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #333; padding-bottom: 10px;">Show Details</h3>
            
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-size: 14px; color: #b3b3b3;">Show Date:</td>
                    <td style="padding: 8px 0; font-size: 14px; text-align: right; font-weight: bold;">
                        <?php
                        $dayName = $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('D') : (new \DateTime($booking->show->show_date))->format('D');
                        $dateStr = $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('d M Y') : $booking->show->show_date;
                        echo h($dayName) . ', ' . h($dateStr);
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-size: 14px; color: #b3b3b3;">Showtime:</td>
                    <td style="padding: 8px 0; font-size: 14px; text-align: right; font-weight: bold;">
                        <?= $timeObj->format('h:i A') ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-size: 14px; color: #b3b3b3;">Cinema Hall:</td>
                    <td style="padding: 8px 0; font-size: 14px; text-align: right; font-weight: bold;">
                        <?= $booking->has('hall') ? h($booking->hall->hall_type) : 'Standard Hall' ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-size: 14px; color: #b3b3b3; vertical-align: top;">Seats Details:</td>
                    <td style="padding: 8px 0; font-size: 14px; text-align: right; font-weight: bold;">
                        <?php if (!empty($booking->tickets)): ?>
                            <?php foreach ($booking->tickets as $ticket): ?>
                                <div style="margin-bottom: 4px;">
                                    <span style="color: #ffffff;"><?= h($ticket->show_seat->seat_row . $ticket->show_seat->seat_number) ?></span>
                                    <span style="color: #e50914; font-size: 11px; text-transform: uppercase; margin-left: 5px;">[<?= h($ticket->show_seat->seat_type) ?>]</span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?= $booking->quantity ?> Seat(s) (<?= h($booking->seat_selection) ?>)
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <div style="background-color: #000; padding: 15px; border-radius: 8px; margin-top: 15px; border: 1px solid #e50914;">
                <table style="width: 100%;">
                    <tr>
                        <td style="font-size: 16px; font-weight: bold;">TOTAL PAID</td>
                        <td style="font-size: 20px; font-weight: bold; text-align: right; color: #e50914;">RM <?= number_format((float)$booking->ticket_price, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 11px; color: #b3b3b3; text-align: right; padding-top: 5px;">
                            Paid via <?= h($booking->payments[0]->payment_method ?? 'Online Banking') ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <div style="text-align: center; border-top: 1px solid #333; padding-top: 20px; color: #b3b3b3;">
            <p style="margin: 0; font-size: 12px;">This is an automated receipt generated by MisaCinema.</p>
            <p style="margin: 5px 0 0; font-size: 14px; font-weight: bold; color: #e50914; text-transform: uppercase;">Enjoy Your Movie!</p>
        </div>
    </div>
    
    <div style="background-color: #1a1a1a; padding: 20px; text-align: center; font-size: 12px; color: #666;">
        <p style="margin: 0;">&copy; <?= date('Y') ?> MisaCinema. All rights reserved.</p>
    </div>
</div>
