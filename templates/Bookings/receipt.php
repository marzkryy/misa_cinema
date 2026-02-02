<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
$this->assign('title', 'Booking Receipt - MisaCinema');
?>

<style>
    @media print {
        .no-print {
            display: none !important;
        }

        body {
            background-color: white !important;
            color: black !important;
        }

        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            background-color: white !important;
            color: black !important;
        }

        .text-white {
            color: black !important;
        }

        .bg-dark {
            background-color: white !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
            -webkit-print-color-adjust: exact;
        }

        .text-danger {
            color: #dc3545 !important;
        }
    }
</style>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-7">
            <!-- Receipt Card -->
            <div class="card bg-dark text-white shadow-lg border-0" id="receipt-card" style="border-radius: 20px;">
                <div class="card-header bg-danger text-center py-4" style="border-radius: 20px 20px 0 0;">
                    <h3 class="mb-0 fw-bold">BOOKING RECEIPT</h3>
                    <p class="mb-0 text-light opacity-75 small uppercase tracking-wider">MisaCinema Official</p>
                </div>
                <div class="card-body p-3 p-md-5">
                    <div class="text-center mb-4">
                        <div class="h2 fw-bold text-danger mb-0">MISA CINEMA</div>
                        <p class="text-light small opacity-75">Experience the Best in Cinema</p>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="text-light small text-uppercase fw-bold d-block opacity-75 x-small">Booking ID</label>
                            <span class="fw-bold small">#MC-<?= str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT) ?></span>
                            <?php if ($booking->is_student): ?>
                                <span class="badge bg-warning text-dark fw-bold ms-2 x-small" style="font-size: 0.65rem; vertical-align: middle;">STUDENT</span>
                            <?php endif; ?>
                        </div>
                        <div class="col-6 text-end">
                            <label class="text-light small text-uppercase fw-bold d-block opacity-75 x-small">Date</label>
                            <span class="fw-bold small">
                                <?= $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('d M Y') : $booking->show->show_date ?>
                            </span>
                        </div>
                    </div>

                    <hr class="border-secondary opacity-25 my-4">

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3 text-white-50 text-uppercase tracking-widest small">Movie Details</h6>
                        <div class="d-flex justify-content-between mb-2 text-white-50 gap-2 align-items-start">
                            <span class="small pt-1"><b>Movie Title:</b></span>
                            <span class="fw-bold text-danger text-end font-monospace">
                                <?= h($booking->show->show_title) ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-white-50 gap-2 align-items-start">
                            <span class="small pt-1"><b>Showtime:</b></span>
                            <span class="fw-bold text-white text-end font-monospace">
                                <?php
                                $dayName = $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('D') : (new \DateTime($booking->show->show_date))->format('D');
                                $date = $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('d M Y') : $booking->show->show_date;
                                $timeObj = ($booking->show->show_time instanceof \DateTimeInterface) ? $booking->show->show_time : new \DateTime($booking->show->show_time);
                                echo h($dayName) . ', ' . h($date) . ', ' . $timeObj->format('h:i A');
                                ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-white-50 gap-2 align-items-start">
                            <span class="small pt-1"><b>Hall:</b></span>
                            <span class="fw-bold text-white text-end font-monospace">
                                <?= $booking->has('hall') ? h($booking->hall->hall_type) : 'Standard Hall' ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-white-50 gap-2 align-items-start">
                            <span class="small pt-1"><b>Seats:</b></span>
                            <div class="text-end font-monospace">
                                <?php if (!empty($booking->tickets)): ?>
                                    <?php 
                                    $uniqueSeats = [];
                                    foreach ($booking->tickets as $ticket) {
                                        $label = $ticket->show_seat->seat_row . $ticket->show_seat->seat_number;
                                        $uniqueSeats[$label] = $ticket->show_seat;
                                    }
                                    natsort($uniqueSeats); // Not really sorting object keys well, let's fix below
                                    
                                    // Better sorting
                                    ksort($uniqueSeats, SORT_NATURAL);
                                    ?>
                                    <?php foreach ($uniqueSeats as $label => $seat): ?>
                                        <div class="fw-bold text-white small">
                                            <?= h($label) ?> 
                                            <span class="text-danger fw-bold ms-1" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">(<?= h($seat->seat_type) ?>)</span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="fw-bold text-white"><?= $booking->quantity ?> Seat(s)</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-black rounded-3 mb-4 border border-secondary border-opacity-25">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold small">TOTAL PAID</span>
                            <span class="h4 mb-0 fw-bold text-danger">RM <?= number_format((float) $booking->ticket_price, 2) ?></span>
                        </div>
                        <div class="text-end x-small text-light opacity-75">
                            Paid via <span class="text-white fw-bold"><?= h($booking->payments[0]->payment_method ?? 'Online Banking') ?></span>
                        </div>
                    </div>

                    <div class="text-center py-3 border-top border-secondary border-opacity-25">
                        <div class="x-small text-white-50 mb-1">This is a system generated receipt.</div>
                        <div class="small fw-bold text-danger tracking-widest text-uppercase">Enjoy Your Movie!</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 no-print d-flex flex-column flex-sm-row gap-3">
                <button onclick="downloadPDF()" class="btn btn-danger btn-lg px-4 fw-bold shadow-lg w-100 d-flex align-items-center justify-content-center" style="border-radius: 50px;">
                    <i class="fas fa-file-pdf me-2"></i>DOWNLOAD PDF
                </button>
                <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-outline-light btn-lg px-4 fw-bold shadow-sm w-100 d-flex align-items-center justify-content-center" style="border-radius: 50px;">
                    MY BOOKINGS
                </a>
                <a href="<?= $this->Url->build('/') ?>" class="btn btn-outline-light btn-lg px-4 fw-bold shadow-sm w-100 d-flex align-items-center justify-content-center" style="border-radius: 50px;">
                    BACK TO HOME
                </a>
            </div>
        </div>
    </div>
</div>

<!-- html2pdf Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function downloadPDF() {
        // Select the element
        const element = document.getElementById('receipt-card');
        
        // Clone the element to manipulate styles for PDF without affecting the view
        const clone = element.cloneNode(true);
        
        // Force white background and black text on the clone for professional printing
        clone.classList.remove('bg-dark', 'text-white');
        clone.classList.add('bg-white', 'text-dark');
        clone.style.color = 'black';
        clone.style.backgroundColor = 'white';
        clone.style.border = '1px solid #ddd';
        
        // Fix internal colors
        const darkBgs = clone.querySelectorAll('.bg-dark, .bg-black');
        darkBgs.forEach(el => {
            el.classList.remove('bg-dark', 'bg-black');
            el.classList.add('bg-light');
            el.style.backgroundColor = '#f8f9fa';
            el.style.color = 'black';
        });

        const whiteTexts = clone.querySelectorAll('.text-white, .text-light, .text-muted, .text-white-50');
        whiteTexts.forEach(el => {
            el.classList.remove('text-white', 'text-light', 'text-muted', 'text-white-50');
            el.classList.add('text-dark');
            el.style.color = 'black';
        });

        // Maintain the Red Header but ensure text is readable
        const header = clone.querySelector('.card-header');
        if(header) {
            header.classList.remove('bg-danger');
            header.style.backgroundColor = '#dc3545'; // Keep red
            header.style.color = 'white';
            const headerTexts = header.querySelectorAll('*');
            headerTexts.forEach(t => t.style.color = 'white');
        }

        // Options for html2pdf
        const opt = {
            margin:       [10, 10, 10, 10], // Top, Left, Bottom, Right
            filename:     'MisaCinema_Receipt_<?= $booking->id ?>.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, logging: false, useCORS: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Generate and Save
        html2pdf().set(opt).from(clone).save();
    }
</script>