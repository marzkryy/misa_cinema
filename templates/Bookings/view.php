<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
$authUser = $this->request->getSession()->read('Auth.User');
$isAdmin = isset($authUser['role']) && $authUser['role'] === 'admin';

// Data QR dan Formatting
$qrData = "BOOKING-MC" . str_pad((string)$booking->id, 6, '0', STR_PAD_LEFT);
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($qrData);

// Format Tarikh dan Masa
$displayDate = $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('l, d M Y') : $booking->show->show_date;
$timeObj = ($booking->show->show_time instanceof \DateTimeInterface) ? $booking->show->show_time : new \DateTime($booking->show->show_time);
$displayTime = $timeObj->format('h:i A');

$pdfFileName = str_replace(' ', '_', h($booking->customer->name ?? 'Customer')) . "_" . str_replace(' ', '_', h($booking->show->show_title)) . ".pdf";

// Ambil data kerusi
$seats = [];
if (!empty($booking->tickets)) {
    foreach ($booking->tickets as $ticket) {
        if ($ticket->has('show_seat')) {
            $label = $ticket->show_seat->seat_row . $ticket->show_seat->seat_number;
            $type = strtoupper(h($ticket->show_seat->seat_type));
            $seats[] = "$label [$type]";
        }
    }
    $seats = array_unique($seats);
    natsort($seats);
}
$seatDisplay = !empty($seats) ? implode(', ', $seats) : h($booking->quantity) . ' Seat(s)';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-light rounded-pill px-4 shadow-sm fw-bold">
            <i class="fas fa-arrow-left me-2"></i>Back to My Bookings
        </a>
    </div>

    <div id="screen-ticket" class="ticket-card bg-dark text-white shadow-lg overflow-hidden border-0" style="border-radius: 30px; position: relative;">
        <div class="row g-0">
            <div class="col-md-4">
                <div class="h-100 min-vh-50">
                    <?php if ($booking->show->avatar): ?>
                        <img src="<?= $this->Url->build('/img/shows/' . $booking->show->avatar) ?>" class="w-100 h-100 object-fit-cover">
                    <?php else: ?>
                        <div class="bg-black w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-film fa-4x text-danger opacity-25"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card-body p-5">
                    <div class="d-flex justify-content-between mb-4">
                        <div>
                            <h1 class="fw-bold text-uppercase mb-1"><?= h($booking->show->show_title) ?></h1>
                            <span class="badge bg-danger rounded-pill px-3"><?= h($booking->show->genre) ?></span>
                        </div>
                        <div class="text-end">
                            <div class="d-flex align-items-center justify-content-end gap-2 mb-1">
                                <?php if (!empty($booking->is_student)): ?>
                                    <span class="badge bg-warning text-dark px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">STUDENT</span>
                                <?php endif; ?>
                                <div class="text-danger fw-bold h4 mb-0">CONFIRMED</div>
                            </div>
                            <small class="text-white-50">ID: #MC-<?= str_pad((string)$booking->id, 6, '0', STR_PAD_LEFT) ?></small>
                        </div>
                    </div>

                    <div class="row g-0 mb-5 p-4 bg-black bg-opacity-50 rounded-4 border border-secondary border-opacity-25">
                        <div class="col-6 p-3 border-end border-secondary border-opacity-25">
                            <label class="x-small text-danger fw-bold text-uppercase d-block mb-1">Cinema Hall</label>
                            <div class="h5 fw-bold"><?= h($booking->hall->hall_type ?? 'Standard') ?></div>
                            <div class="mt-4">
                                <label class="x-small text-danger fw-bold text-uppercase d-block mb-1">Seat Number(s)</label>
                                <div class="h4 fw-bold text-white"><?= $seatDisplay ?></div>
                            </div>
                        </div>
                        <div class="col-6 p-3">
                            <label class="x-small text-danger fw-bold text-uppercase d-block mb-1">Show Date</label>
                            <div class="h5 fw-bold"><?= $displayDate ?></div>
                            <div class="mt-4">
                                <label class="x-small text-danger fw-bold text-uppercase d-block mb-1">Show Time</label>
                                <div class="h5 fw-bold"><?= $displayTime ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between p-4 bg-white text-dark rounded-4 shadow-sm border-start border-5 border-danger">
                        <div class="d-flex align-items-center">
                            <img src="<?= $qrUrl ?>" alt="Ticket QR" class="img-fluid rounded border p-1 me-4 shadow-sm" style="width: 100px;">
                            <div>
                                <h6 class="fw-bold text-danger text-uppercase mb-1 small tracking-wide">Digital E-Ticket</h6>
                                <p class="x-small text-dark fw-bold mb-0">Scan at the cinema entrance.<br>MISA CINEMA OFFICIAL</p>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="x-small text-muted fw-bold">Total Paid</div>
                            <div class="h3 fw-bold text-danger">RM <?= number_format((float)$booking->ticket_price, 2) ?></div>
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        <button onclick="downloadOfficialPDF()" class="btn btn-danger shadow-sm rounded-pill px-5 py-2">
                            <i class="fas fa-file-pdf me-2"></i>Download PDF Ticket
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display: none;">
    <div id="pdf-template" style="padding: 40px; font-family: Arial, sans-serif; color: #000000; background: #ffffff; width: 750px;">
        
        <div style="margin-bottom: 20px;">
            <h1 style="margin: 0; font-size: 2.5rem; text-transform: uppercase; font-weight: bold; color: #000000 !important;">
                <?= h($booking->show->show_title) ?>
                <?php if (!empty($booking->is_student)): ?>
                    <span style="background: #ffc107 !important; color: #000 !important; padding: 2px 10px; border-radius: 5px; font-size: 1rem; vertical-align: middle; margin-left: 10px;">STUDENT</span>
                <?php endif; ?>
            </h1>
            <span style="background: #d9534f !important; color: #ffffff !important; padding: 5px 15px; border-radius: 20px; font-size: 0.9rem; display: inline-block; margin-top: 10px;">
                <?= h($booking->show->genre) ?>
            </span>
        </div>
        
        <div style="border-top: 2px dashed #ddd; padding: 15px 0; margin-bottom: 20px;">
            <div style="font-size: 1rem; color: #000000;">Status: <strong style="color: #5cb85c;">CONFIRMED</strong></div>
            <div style="font-size: 1rem; color: #000000;">Booking ID: <strong>#MC-<?= str_pad((string)$booking->id, 6, '0', STR_PAD_LEFT) ?></strong></div>
        </div>

        <div style="text-align: center; margin-bottom: 10px;">
            <img src="<?= $qrUrl ?>" style="width: 130px; margin-bottom: 5px; display: block; margin: 0 auto;">
            <div style="color: #000000 !important; font-weight: bold; font-size: 1.3rem; text-transform: uppercase; letter-spacing: 2px; margin: 0;">MISA CINEMA</div>
            <div style="color: #666666 !important; font-size: 0.8rem; margin: 0;">OFFICIAL E-TICKET</div>
        </div>

        <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd; margin-top: 10px; margin-bottom: 20px;">
            <tr>
                <td style="padding: 15px; border: 1px solid #ddd; width: 50%; vertical-align: top;">
                    <small style="color: #d9534f; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px;">Cinema Hall</small>
                    <div style="font-size: 1.2rem; font-weight: bold; color: #000000;"><?= h($booking->hall->hall_type ?? 'Standard') ?></div>
                </td>
                <td style="padding: 15px; border: 1px solid #ddd; width: 50%; vertical-align: top;">
                    <small style="color: #d9534f; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px;">Seat Number(s)</small>
                    <div style="font-size: 1.2rem; font-weight: bold; color: #000000;"><?= $seatDisplay ?></div>
                </td>
            </tr>
            <tr>
                <td style="padding: 15px; border: 1px solid #ddd; vertical-align: top;">
                    <small style="color: #d9534f; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px;">Show Date</small>
                    <div style="font-size: 1.2rem; font-weight: bold; color: #000000;"><?= $displayDate ?></div>
                </td>
                <td style="padding: 15px; border: 1px solid #ddd; vertical-align: top;">
                    <small style="color: #d9534f; font-weight: bold; text-transform: uppercase; display: block; margin-bottom: 5px;">Show Time</small>
                    <div style="font-size: 1.2rem; font-weight: bold; color: #000000;"><?= $displayTime ?></div>
                </td>
            </tr>
        </table>

        <div style="border-top: 1px solid #eee; padding-top: 15px;">
            <small style="text-transform: uppercase; font-weight: bold; color: #666; display: block; margin-bottom: 5px;">Total Amount Paid</small>
            <div style="font-size: 2.5rem; font-weight: bold; color: #d9534f;">
                RM <?= number_format((float)$booking->ticket_price, 2) ?>
            </div>
        </div>

        <div style="margin-top: 40px; font-size: 0.8rem; color: #999; border-top: 1px solid #eee; padding-top: 10px;">
            Booked on <?= h($booking->book_date_time) ?> | Customer: <?= h($booking->customer->name ?? 'Guest') ?>
        </div>
    </div>
</div>

<script>
    function downloadOfficialPDF() {
        const element = document.getElementById('pdf-template');
        const opt = {
            margin: 0.3,
            filename: '<?= $pdfFileName ?>',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 3, useCORS: true, letterRendering: true },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>

<style>
    .x-small { font-size: 0.7rem; }
    .rounded-4 { border-radius: 1rem; }
    .object-fit-cover { object-fit: cover; }
    body { background-color: #000 !important; }
    .ticket-card { border: 1px solid rgba(255,255,255,0.1); }
    .tracking-wide { letter-spacing: 1px; }
</style>