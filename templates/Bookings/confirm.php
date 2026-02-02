<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 */
$this->assign('title', 'Confirm Your Booking - MisaCinema');
?>



<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-danger text-center py-4" style="border-radius: 15px 15px 0 0;">
                    <h2 class="mb-0 fw-bold">CONFIRM YOUR BOOKING</h2>
                    <p class="mb-0 text-light opacity-75">Please review your movie details before payment</p>
                </div>
                <div class="card-body p-5">
                    <div class="row mb-4 mb-md-5">
                        <div class="col-5 col-md-4 text-center mb-3 mb-md-0">
                            <?php if ($booking->show->avatar): ?>
                                <img src="<?= $this->Url->build('/img/shows/' . $booking->show->avatar) ?>"
                                    class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <i class="fas fa-film fa-3x opacity-25"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-7 col-md-8">
                            <h3 class="text-danger fw-bold mb-3 h4 h3-md">
                                <?= h($booking->show->show_title) ?>
                            </h3>
                            <div class="mb-2 mb-md-3">
                                <label class="text-white-50 small text-uppercase fw-bold d-block" style="font-size: 0.7rem;">Show Date</label>
                                <div class="h6 h5-md mb-0">
                                    <i class="far fa-calendar-alt me-2 text-danger"></i>
                                    <?php
                                    $dayName = $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('D') : (new \DateTime($booking->show->show_date))->format('D');
                                    $date = $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('d M Y') : $booking->show->show_date;
                                    echo h($dayName) . ', ' . h($date);
                                    ?>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-3">
                                <label class="text-white-50 small text-uppercase fw-bold d-block" style="font-size: 0.7rem;">Show Time</label>
                                <div class="h6 h5-md mb-0">
                                    <i class="far fa-clock me-2 text-danger"></i>
                                    <?php
                                    $timeObj = ($booking->show->show_time instanceof \DateTimeInterface) ? $booking->show->show_time : new \DateTime($booking->show->show_time);
                                    echo $timeObj->format('h:i A');
                                    ?>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-3">
                                <label class="text-white-50 small text-uppercase fw-bold d-block" style="font-size: 0.7rem;">Hall & Seats</label>
                                <div class="h6 h5-md mb-0">
                                    <i class="fas fa-couch me-2 text-danger"></i>
                                    <?= h($booking->hall->hall_type) ?> |
                                    <?= $booking->quantity ?> Seat(s)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-black rounded mb-5 border border-secondary shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h4 class="mb-0 fw-bold">TOTAL AMOUNT</h4>
                                <p class="text-light small mb-0 opacity-75">Inclusive of all taxes</p>
                            </div>
                            <div class="text-danger h2 fw-bold mb-0" id="displayPrice">
                                RM <?= number_format((float) $booking->ticket_price, 2) ?>
                            </div>
                        </div>

                        <?php if (isset($isStudentEligible) && $isStudentEligible): ?>
                            <div class="mt-3 pt-3 border-top border-secondary border-opacity-25">
                                <div class="form-check">
                                    <input class="form-check-input bg-dark border-secondary" type="checkbox" name="is_student" value="1" id="studentCheck" form="confirmForm">
                                    <label class="form-check-label text-white" for="studentCheck">
                                        <i class="fas fa-graduation-cap text-warning me-2"></i>
                                        I am a Student <span class="badge bg-warning text-dark fw-bold ms-1">20% OFF</span>
                                    </label>
                                </div>
                                <div class="small text-white-50 mt-1 ms-4">
                                    * Must present valid Student ID at entrance.
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const checkbox = document.getElementById('studentCheck');
                                    const priceDisplay = document.getElementById('displayPrice');
                                    const originalPrice = <?= (float)$booking->ticket_price ?>;
                                    const discountedPrice = originalPrice * 0.8;

                                    checkbox.addEventListener('change', function() {
                                        if (this.checked) {
                                            priceDisplay.innerHTML = 'RM ' + discountedPrice.toFixed(2);
                                        } else {
                                            priceDisplay.innerHTML = 'RM ' + originalPrice.toFixed(2);
                                        }
                                    });
                                });
                            </script>
                        <?php endif; ?>
                    </div>

                    <?= $this->Form->create(null, ['id' => 'confirmForm']) ?>
                    <div class="row g-3">
                        <div class="col-6">
                            <?= $this->Html->link(__('CANCEL BOOKING'), ['action' => 'chooseSeat', $booking->show_id], ['class' => 'btn btn-outline-secondary btn-lg w-100 fw-bold py-3']) ?>
                        </div>
                        <div class="col-6">
                            <?= $this->Form->button(__('CONFIRM & PAY'), ['class' => 'btn btn-danger btn-lg w-100 fw-bold py-3 shadow']) ?>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>