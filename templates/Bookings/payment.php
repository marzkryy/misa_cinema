<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
$this->assign('title', 'Payment - MisaCinema');
?>



<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card bg-dark text-white shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-danger text-center py-4" style="border-radius: 15px 15px 0 0;">
                    <h2 class="mb-0 fw-bold"><i class="fas fa-lock me-3"></i>SECURE PAYMENT</h2>
                    <p class="mb-0 text-light opacity-75">Finalize your booking with our secure portal</p>
                </div>
                <div class="card-body p-5">
                    <div class="row mb-4">
                        <div class="col-md-7">
                            <h5 class="text-danger fw-bold mb-3">Booking Summary</h5>
                            <div class="ps-3 border-start border-danger border-3">
                                <p class="mb-1 h4 fw-bold"><?= h($booking->show->show_title) ?></p>
                                <p class="mb-1 text-white-50">
                                    <i class="far fa-clock me-2"></i><?php
                                            $timeObj = ($booking->show->show_time instanceof \DateTimeInterface) ? $booking->show->show_time : new \DateTime($booking->show->show_time);
                                            echo $timeObj->format('h:i A');
                                        ?>
                                    <i class="far fa-calendar-alt ms-3 me-2"></i><?php
                                        $dayName = $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('D') : (new \DateTime($booking->show->show_date))->format('D');
                                        $date = $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('d M Y') : $booking->show->show_date;
                                        echo h($dayName) . ', ' . h($date);
                                    ?>
                                </p>
                                <p class="mb-0 text-white-50">
                                    <i class="fas fa-couch me-2"></i><?= $booking->quantity ?> Seat(s) |
                                    <?= h($booking->hall->hall_type ?? 'Standard Hall') ?>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-5 text-end d-flex flex-column justify-content-center">
                            <p class="text-white-50 small mb-0 font-monospace">TOTAL AMOUNT</p>
                            <h2 class="display-5 fw-bold text-danger mb-0">RM
                                <?= number_format((float) $booking->ticket_price, 2) ?>
                            </h2>
                        </div>
                    </div>

                    <hr class="border-secondary my-5">

                    <h5 class="mb-4 fw-bold"><i class="fas fa-credit-card me-2"></i>Select Payment Method</h5>

                    <style>
                        .payment-method-btn {
                            border-radius: 12px !important;
                            text-transform: none !important;
                            padding: 1.5rem !important;
                            background: rgba(0,0,0,0.3);
                            border: 2px solid rgba(229, 9, 20, 0.2) !important;
                            transition: all 0.2s ease-in-out !important;
                            letter-spacing: normal !important;
                        }
                        .payment-method-btn:hover {
                            background: rgba(229, 9, 20, 0.1) !important;
                            border-color: #e50914 !important;
                            transform: translateY(-3px) !important;
                            color: #e50914 !important;
                            box-shadow: 0 5px 15px rgba(229, 9, 20, 0.2) !important;
                        }
                        .btn-check:checked + .payment-method-btn {
                            background: #e50914 !important;
                            border-color: #e50914 !important;
                            color: #ffffff !important;
                        }
                        .payment-method-btn i {
                            transition: transform 0.2s ease;
                        }
                        .payment-method-btn:hover i {
                            transform: scale(1.1);
                        }
                    </style>

                    <?= $this->Form->create(null) ?>
                    <div class="row g-3 mb-5">
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="payment_method" id="pay-visa"
                                value="Visa/Mastercard" autocomplete="off" checked>
                            <label
                                class="btn btn-outline-danger payment-method-btn d-flex flex-column align-items-center justify-content-center w-100 h-100"
                                for="pay-visa">
                                <i class="fab fa-cc-visa fa-3x mb-3"></i>
                                <span class="fw-bold h6 mb-0">Card Payment</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="payment_method" id="pay-fpx"
                                value="Online Banking (FPX)" autocomplete="off">
                            <label
                                class="btn btn-outline-danger payment-method-btn d-flex flex-column align-items-center justify-content-center w-100 h-100"
                                for="pay-fpx">
                                <i class="fas fa-university fa-3x mb-3"></i>
                                <span class="fw-bold h6 mb-0">Online Banking</span>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <input type="radio" class="btn-check" name="payment_method" id="pay-ewallet"
                                value="E-Wallet" autocomplete="off">
                            <label
                                class="btn btn-outline-danger payment-method-btn d-flex flex-column align-items-center justify-content-center w-100 h-100"
                                for="pay-ewallet">
                                <i class="fas fa-wallet fa-3x mb-3"></i>
                                <span class="fw-bold h6 mb-0">E-Wallet</span>
                            </label>
                        </div>
                    </div>

                    <div class="bg-black p-3 rounded mb-4 text-center border border-secondary">
                        <small class="text-white-50"><i class="fas fa-info-circle me-2"></i>You will be redirected to
                            the
                            secure gateway of your choice.</small>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <?= $this->Html->link(__('BACK TO SUMMARY'), ['action' => 'confirm', $booking->id], ['class' => 'btn btn-outline-secondary btn-lg w-100 fw-bold py-3']) ?>
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold py-3 shadow">
                                PAY RM <?= number_format((float) $booking->ticket_price, 2) ?>
                            </button>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>