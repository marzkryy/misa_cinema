<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Booking $booking
 * @var \Cake\Collection\CollectionInterface|string[] $customers
 * @var \Cake\Collection\CollectionInterface|string[] $shows
 */
?>
<div class="row">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg bg-dark text-white border-0" style="border-radius: 20px; overflow: hidden;">
                    <!-- Header Component -->
                    <div class="card-header bg-danger py-4 text-center border-0">
                        <h3 class="fw-bold mb-0 text-uppercase tracking-wider">
                            <i class="fas fa-ticket-alt me-2"></i><?= __('Reserve Your Seats') ?>
                        </h3>
                        <p class="small opacity-75 mb-0 mt-1">Experience the magic of cinema at MisaCinema</p>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <?= $this->Form->create($booking, ['class' => 'cinematic-form']) ?>

                        <!-- Identity Section -->
                        <?php
                        $authUser = $this->request->getSession()->read('Auth.User');
                        echo $this->Form->hidden('cust_id', ['value' => $authUser['id'] ?? '']);
                        ?>
                        <div class="mb-4 text-center">
                            <div class="d-inline-block p-3 rounded-circle bg-secondary bg-opacity-10 mb-2">
                                <i class="fas fa-user-circle fa-2x text-danger"></i>
                            </div>
                            <div class="small text-white text-uppercase fw-bold"><?= __('Booking for Account:') ?></div>
                            <div class="h5 fw-bold text-white"><?= h($authUser['name'] ?? 'Guest') ?></div>
                        </div>

                        <hr class="border-secondary opacity-25 mb-4">

                        <!-- Show Selection -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase text-white fw-bold">
                                <i class="fas fa-film me-2 text-danger"></i><?= __('Select Movie') ?>
                            </label>
                            <?= $this->Form->control('show_id', [
                                'options' => $shows,
                                'label' => false,
                                'class' => 'form-select form-select-lg bg-black text-white border-secondary border-opacity-25',
                                'style' => 'border-radius: 10px;'
                            ]) ?>
                        </div>

                        <!-- Date/Time Selection -->
                        <div class="mb-4">
                            <label class="form-label small text-uppercase text-white fw-bold">
                                <i class="fas fa-calendar-day me-2 text-danger"></i><?= __('Booking Date & Time') ?>
                            </label>
                            <div
                                class="cinematic-datetime bg-black rounded p-2 border border-secondary border-opacity-25">
                                <?= $this->Form->control('book_date_time', [
                                    'label' => false,
                                    'class' => 'form-control bg-transparent text-white border-0',
                                    'style' => 'color-scheme: dark;'
                                ]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Hall Selection -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label small text-uppercase text-white fw-bold">
                                    <i class="fas fa-door-open me-2 text-danger"></i><?= __('Cinema Hall') ?>
                                </label>
                                <?= $this->Form->control('hall_id', [
                                    'options' => $halls,
                                    'id' => 'hall-id',
                                    'empty' => 'Select Hall',
                                    'label' => false,
                                    'class' => 'form-select bg-black text-white border-secondary border-opacity-25',
                                    'style' => 'border-radius: 10px;'
                                ]) ?>
                            </div>

                            <!-- Seat Selection -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label small text-uppercase text-white fw-bold">
                                    <i class="fas fa-chair me-2 text-danger"></i><?= __('Select Seat') ?>
                                </label>
                                <?= $this->Form->control('seat_id', [
                                    'options' => $seats,
                                    'id' => 'seat-id',
                                    'empty' => 'Select Seat',
                                    'label' => false,
                                    'class' => 'form-select bg-black text-white border-secondary border-opacity-25',
                                    'style' => 'border-radius: 10px;'
                                ]) ?>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Quantity -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label small text-uppercase text-white fw-bold">
                                    <i class="fas fa-users me-2 text-danger"></i><?= __('Quantity') ?>
                                </label>
                                <?= $this->Form->control('quantity', [
                                    'id' => 'quantity',
                                    'type' => 'number',
                                    'min' => 1,
                                    'label' => false,
                                    'class' => 'form-control bg-black text-white border-secondary border-opacity-25',
                                    'style' => 'border-radius: 10px;',
                                    'placeholder' => 'Number of tickets'
                                ]) ?>
                            </div>

                            <!-- Total Price -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label small text-uppercase text-white fw-bold">
                                    <i class="fas fa-coins me-2 text-warning"></i><?= __('Total Amount') ?>
                                </label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text bg-black border-secondary border-opacity-25 text-warning fw-bold">RM</span>
                                    <?= $this->Form->control('ticket_price', [
                                        'id' => 'ticket-price',
                                        'readonly' => true,
                                        'label' => false,
                                        'class' => 'form-control bg-black text-warning fw-bold border-secondary border-opacity-25',
                                        'style' => 'border-radius: 0 10px 10px 0;'
                                    ]) ?>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-4">
                            <?= $this->Form->button(__('CONFIRM BOOKING'), [
                                'class' => 'btn btn-danger btn-lg fw-bold py-3 shadow-sm',
                                'style' => 'border-radius: 12px; letter-spacing: 1px;'
                            ]) ?>
                            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-link text-muted text-decoration-none small mt-2']) ?>
                        </div>

                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-black {
            background-color: #000 !important;
        }

        .tracking-wider {
            letter-spacing: 2px;
        }

        .form-select,
        .form-control {
            padding: 0.75rem 1rem;
            transition: border-color 0.2s;
        }

        .form-select:focus,
        .form-control:focus {
            background-color: #000;
            color: #fff;
            border-color: #dc3545;
            box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
        }
    </style>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const seatsData = <?= $seatsJson ?>;
        const seatSelect = document.querySelector('select[name="seat_id"]');
        const quantityInput = document.querySelector('input[name="quantity"]');
        const totalPriceInput = document.querySelector('input[name="ticket_price"]'); // Kolum harga dalam table bookings

        function calculateTotal() {
            const selectedSeatId = seatSelect.value;
            const qty = parseInt(quantityInput.value) || 0;

            // Cari harga seat dari data JSON
            const seat = seatsData.find(s => s.id == selectedSeatId);

            if (seat && qty > 0) {
                const pricePerSeat = parseFloat(seat.seat_price);
                const total = pricePerSeat * qty;
                totalPriceInput.value = total.toFixed(2);
            } else {
                totalPriceInput.value = '0.00';
            }
        }

        // Jalankan kiraan bila seat atau quantity berubah
        seatSelect.addEventListener('change', calculateTotal);
        quantityInput.addEventListener('input', calculateTotal);
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Bila dropdown Hall berubah
        $('#hall-id').change(function () {
            var hallId = $(this).val();

            if (hallId) {
                $.ajax({
                    // Panggil fungsi getSeatsByHall yang kita buat tadi
                    url: '<?= $this->Url->build(['action' => 'getSeatsByHall']) ?>/' + hallId,
                    type: 'GET',
                    success: function (data) {
                        // Gantikan isi dropdown Seat dengan data baru yang dah ditapis
                        $('#seat-id').html('<option value="">Select Seat</option>' + data);
                    }
                });
            } else {
                $('#seat-id').html('<option value="">Select Seat</option>');
            }
        });
    });
</script>