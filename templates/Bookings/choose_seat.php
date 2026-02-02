<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Show $show
 * @var array $soldSeats
 * @var array $lockedByOthers
 * @var array $lockedByMe
 * @var array $groupedSeats
 */
$this->assign('title', 'Select Seats - ' . $show->show_title);
?>

<style>
    .seat-btn {
        flex: 0 0 52px;
        width: 52px !important;
        height: 52px !important;
        font-size: 1.1rem;
        font-weight: 500;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        padding: 0 !important;
        overflow: hidden;
    }
    .seat-btn span {
        line-height: 1;
        display: block;
    }
    .seat-btn:hover:not(.disabled) {
        transform: scale(1.1);
        z-index: 10;
        box-shadow: 0 5px 15px rgba(229, 9, 20, 0.3);
    }
    .seat-btn .seat-type-label {
        font-size: 0.55rem;
        text-transform: uppercase;
        opacity: 0.9;
        font-weight: 400;
        letter-spacing: -0.3px;
        margin-top: 1px;
        display: block;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding: 0 1px;
    }
    .seat-btn.couple {
        flex: 0 0 110px;
        width: 110px !important;
    }
    .seat-btn.bed {
        flex: 0 0 140px;
        width: 140px !important;
    }
    .seat-btn .seat-icon {
        font-size: 1.1rem;
        line-height: 1;
        margin-bottom: 2px;
    }
    .seat-btn.couple .seat-icon { font-size: 1.4rem; }
    .seat-btn.bed .seat-icon { font-size: 1.6rem; }

    /* Type Specific Colors (Available state only) */
    .seat-btn.btn-outline-danger.premium { border-color: #fd7e14 !important; color: #fd7e14 !important; }
    .seat-btn.btn-outline-danger.couple { border-color: #f72585 !important; color: #f72585 !important; } /* Magenta/Pink */
    .seat-btn.btn-outline-danger.bed { border-color: #4cc9f0 !important; color: #4cc9f0 !important; } /* Light Blue */

    /* Legend Icons Colors Sync */
    .legend-item .fa-crown { color: #fd7e14; }
    .legend-item .fa-user-friends { color: #f72585; }
    .legend-item .fa-bed { color: #4cc9f0; }

    /* Hover fixes for types */
    .seat-btn.btn-outline-danger.premium:hover:not(.disabled) { background: #ffc107 !important; color: #fff !important; border-color: #ffc107 !important; }
    .seat-btn.btn-outline-danger.couple:hover:not(.disabled) { background: #f72585 !important; color: #fff !important; border-color: #f72585 !important; }
    .seat-btn.btn-outline-danger.bed:hover:not(.disabled) { background: #17a2b8 !important; color: #fff !important; }
    .seat-map-container {
        width: 100%;
        overflow: hidden;
        position: relative;
        display: flex;
        justify-content: center;
        padding: 20px 0;
    }

    .seat-map-scaler {
        width: max-content;
        flex-shrink: 0;
        transition: transform 0.2s ease-out;
    }

    .seat-map-scroll {
        display: block;
        margin: 0 auto;
    }
    .seat-map-scroll::-webkit-scrollbar {
        display: none;
    }

    .screen-container {
        perspective: 300px;
    }
    .screen-indicator {
        background: #f8f9fa;
        color: #212529;
        font-weight: bold;
        letter-spacing: 5px;
        padding: 8px 0;
        width: 60%;
        margin: 0 auto;
        border-radius: 4px;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
        transform: rotateX(-5deg);
        opacity: 0.6;
        font-size: 0.8rem;
    }
    .legend-item {
        font-size: 0.65rem;
        padding: 4px 12px !important;
        border-radius: 20px !important;
    }
    .row-label {
        width: 20px;
        min-width: 20px;
        color: rgba(255,255,255,0.3);
        font-weight: bold;
        text-align: center;
        font-size: 0.7rem;
    }

    /* Mobile Optimization for Seat Map */
    @media (max-width: 767.98px) {
        .seat-btn {
            flex: 0 0 38px;
            width: 38px !important;
            height: 38px !important;
            font-size: 0.8rem;
        }
        .seat-btn .seat-type-label {
             font-size: 0.45rem;
             letter-spacing: -0.5px;
        }
        .seat-btn .seat-icon {
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        .seat-btn.couple {
            flex: 0 0 80px;
            width: 80px !important;
        }
        .seat-btn.bed {
            flex: 0 0 100px;
            width: 100px !important;
        }
        
        .seat-map-container {
            padding: 30px 0 10px 0; /* Extra top padding */
            min-height: 300px;
            flex-direction: column; /* Switch to column for better centering control */
            align-items: center;    /* Horizontal Center */
            justify-content: flex-start; /* Vertical Top */
        }
    }
</style>

<div class="container mt-5 pb-3">
    <div class="mb-4 text-center text-md-start">
        <a href="<?= $this->Url->build(['controller' => 'Shows', 'action' => 'index']) ?>" class="btn btn-outline-light btn-sm rounded-pill px-3 shadow-sm fw-bold">
            <i class="fas fa-arrow-left me-2"></i>Back
        </a>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-2 gap-2">
        <h2 class="text-white border-start border-4 border-danger ps-3 mb-0 text-uppercase fw-bold">SELECT SEATS</h2>
    </div>

    <div class="row g-4">
        <div class="col-lg-9">
            <div class="card bg-black border-0 rounded-4 p-3 shadow-lg">
                <!-- Theater Screen (Matching Admin) -->
                <div class="screen-container text-center mb-4">
                    <div class="screen-indicator">
                        SCREEN
                    </div>
                </div>

                <div class="seat-map-container" id="customer-seat-container">
                    <div class="seat-map-scaler pt-2" id="customer-seat-scaler"> <!-- Added pt-2 -->
                        <div class="seat-map d-flex flex-column align-items-center gap-1 gap-md-2">
                            <?php 
                            $presentTypes = [];
                            foreach ($groupedSeats as $rowLabel => $rowSeats) {
                                foreach ($rowSeats as $seat) {
                                    $presentTypes[] = strtolower($seat->seat_type);
                                }
                            }
                            $presentTypes = array_unique($presentTypes);
                            
                            // Sort rows alphabetically (A-Z) to ensure consistent display
                            ksort($groupedSeats);

                            // Apply custom sorting if needed (e.g. reverse for screen position) - assuming A is closest to screen (Top)
                            // If screen is top, usually A is top.

                            foreach ($groupedSeats as $rowLabel => $rowSeats): ?>
                                <div class="d-flex justify-content-center align-items-center gap-2 mb-2 w-100" style="min-width: fit-content;">
                                    <div class="text-white-50 fw-bold me-3" style="width: 25px; font-size: 0.9rem;"><?= h($rowLabel) ?></div>
                                    <div class="d-flex gap-2">
                                        <?php foreach ($rowSeats as $seat): ?>
                                            <?php
                                            $seatLabel = $seat->seat_row . $seat->seat_number;
                                            $isSold = isset($soldSeats) && in_array($seatLabel, $soldSeats);
                                            $isLockedByOthers = isset($lockedByOthers) && in_array($seatLabel, $lockedByOthers);
                                            $isLockedByMe = isset($lockedByMe) && in_array($seatLabel, $lockedByMe);
                                            $isMaintenance = ($seat->status == 0);
                                            
                                            $typeClass = strtolower(h($seat->seat_type));
                                            $iconClass = 'fa-couch';
                                            if ($typeClass == 'couple') $iconClass = 'fa-user-friends';
                                            if ($typeClass == 'bed') $iconClass = 'fa-bed';
                                            if ($typeClass == 'premium') $iconClass = 'fa-crown';

                                            $btnClass = 'btn-outline-danger seat-btn ' . $typeClass;
                                            $disabled = '';
                                            $tooltip = '';
                                            $extraStyle = '';

                                            if ($isMaintenance) {
                                                $btnClass = 'btn-dark disabled seat-btn ' . $typeClass;
                                                $disabled = 'disabled';
                                                $tooltip = 'Maintenance';
                                            } elseif ($isSold) {
                                                $btnClass = 'btn-danger disabled seat-btn ' . $typeClass;
                                                $disabled = 'disabled';
                                                $extraStyle = 'background-color: #e50914 !important; border-color: #e50914 !important; color: #ffffff !important; opacity: 1 !important;';
                                            } elseif ($isLockedByOthers) {
                                                $btnClass = 'btn-warning locked-btn disabled text-dark seat-btn ' . $typeClass;
                                                $disabled = 'disabled';
                                                $tooltip = 'Reserved by others';
                                                $extraStyle = 'background-color: #ffab00 !important; border-color: #ffab00 !important; color: #000000 !important; opacity: 1 !important;';
                                            } elseif ($isLockedByMe) {
                                                $btnClass = 'btn-success seat-btn ' . $typeClass;
                                            }
                                            $extraStyle = $extraStyle ?? '';
                                            ?>
                                            <button type="button" class="btn <?= $btnClass ?> position-relative"
                                                style="<?= $extraStyle ?>"
                                                data-seat="<?= $seatLabel ?>" 
                                                data-price="<?= $seat->seat_price ?>"
                                                data-type="<?= h($seat->seat_type) ?>"
                                                <?= $disabled ?> title="<?= $tooltip ?>">
                                                
                                                <?php if ($isMaintenance): ?>
                                                    <i class="fas fa-tools" style="font-size: 1.2rem; color: #ffffff !important;"></i>
                                                <?php elseif ($isLockedByOthers): ?>
                                                    <i class="fas fa-lock" style="font-size: 1.2rem; color: #000000 !important;"></i>
                                                <?php else: ?>
                                                    <div class="seat-icon"><i class="fas <?= $iconClass ?>"></i></div>
                                                    <span><?= $seat->seat_number ?></span>
                                                <?php endif; ?>
                                                
                                            </button>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="text-white-50 fw-bold ms-3" style="width: 25px; font-size: 0.9rem;"><?= h($rowLabel) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Updated Map Legend (Matching Admin) -->
                <div class="mt-4 p-3 bg-black bg-opacity-25 rounded shadow-sm border border-light border-opacity-10" style="max-width: 800px; margin: 0 auto;">
                    <div class="d-flex justify-content-center flex-wrap gap-4">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success rounded-pill p-0 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.6rem;"><i class="fas fa-check"></i></span>
                            <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Selected</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill p-0 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.6rem; background-color: #e50914 !important;"><i class="fas fa-couch"></i></span>
                            <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Booked</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge text-dark rounded-pill p-0 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.6rem; background-color: #ffab00 !important;"><i class="fas fa-lock"></i></span>
                            <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Reserved</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge border border-danger text-danger rounded-pill p-0 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.6rem;"><i class="fas fa-couch"></i></span>
                            <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Available</span>
                        </div>
                        <?php if (in_array('couple', $presentTypes)): ?>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge border rounded-pill p-0 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.65rem; border-color: #f72585 !important; color: #f72585 !important;"><i class="fas fa-user-friends"></i></span>
                                <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Couple</span>
                            </div>
                        <?php endif; ?>
                        <?php if (in_array('premium', $presentTypes)): ?>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge border rounded-pill p-0 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.6rem; border-color: #fd7e14 !important; color: #fd7e14 !important;"><i class="fas fa-crown"></i></span>
                                <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Premium</span>
                            </div>
                        <?php endif; ?>
                        <?php if (in_array('bed', $presentTypes)): ?>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge border rounded-pill p-0 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.6rem; border-color: #4cc9f0 !important; color: #4cc9f0 !important;"><i class="fas fa-bed"></i></span>
                                <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Bed</span>
                            </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-dark border border-secondary border-opacity-50 text-white rounded-pill p-0 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.6rem;"><i class="fas fa-tools"></i></span>
                            <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Maintenance</span>
                        </div>
                    </div>
                </div>
                
                <div id="lock-timer" class="mt-4 text-white small d-none d-flex justify-content-center">
                    <div class="bg-black d-inline-flex align-items-center px-4 py-2 rounded-pill border border-danger shadow-lg" style="border-width: 2px !important;">
                        <i class="fas fa-clock me-2 text-danger"></i> 
                        <span class="me-1">Seats Reserved for:</span>
                        <span id="countdown" class="text-danger fw-bold h5 mb-0 ms-1" style="line-height: 1;">10:00</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 text-center">
            <div class="mb-3">
                <h4 class="text-white mb-1 h5 fw-bold"><?= h($show->show_title) ?></h4>
                <div class="text-white-50 small d-flex flex-wrap justify-content-center gap-1 gap-md-2">
                    <span><i class="fas fa-calendar-alt me-1 text-danger"></i>
                    <?php
                        $dayName = $show->show_date instanceof \DateTimeInterface ? $show->show_date->format('D') : (new \DateTime($show->show_date))->format('D');
                        $date = $show->show_date instanceof \DateTimeInterface ? $show->show_date->format('d M') : $show->show_date;
                        echo h($dayName) . ', ' . h($date);
                    ?></span>
                    <span class="d-none d-sm-inline mx-1">|</span>
                    <span><i class="fas fa-clock me-1 text-danger"></i>
                    <?php 
                        $timeObj = ($show->show_time instanceof \DateTimeInterface) ? $show->show_time : new \DateTime($show->show_time);
                        echo h($timeObj->format('h:i A'));
                    ?></span>
                    <span class="d-none d-sm-inline mx-1">|</span>
                    <span><i class="fas fa-video me-1 text-danger"></i> <?= $show->has('hall') ? h($show->hall->hall_type) : 'Hall' ?></span>
                </div>
            </div>
            <div class="card bg-dark text-white shadow-lg border-0 rounded-4 overflow-hidden text-start">
                <div class="card-header bg-danger fw-bold py-3 text-center tracking-wider">BOOKING SUMMARY</div>
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold text-warning mb-3">
                        <?= h($show->show_title) ?>
                    </h5>
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-danger rounded-circle p-2 me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-calendar-alt small"></i>
                            </div>
                            <span>
                                <?php
                                $dayName = $show->show_date instanceof \DateTimeInterface ? $show->show_date->format('D') : (new \DateTime($show->show_date))->format('D');
                                $date = $show->show_date instanceof \DateTimeInterface ? $show->show_date->format('d M Y') : $show->show_date;
                                echo h($dayName) . ', ' . h($date);
                                ?>
                            </span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-danger rounded-circle p-2 me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock small"></i>
                            </div>
                            <span>
                                <?php
                                $timeObj = ($show->show_time instanceof \DateTimeInterface) ? $show->show_time : new \DateTime($show->show_time);
                                echo h($timeObj->format('h:i A'));
                                ?>
                            </span>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="bg-danger rounded-circle p-2 me-3" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-couch small"></i>
                            </div>
                            <span><?= $show->has('hall') ? h($show->hall->hall_type) : 'Standard Hall' ?></span>
                        </div>
                    </div>
                    
                    <hr class="border-secondary opacity-25">
                    
                    <div id="selected-seats-display" class="mb-4">
                        <small class="text-white-50 d-block mb-2 text-uppercase tracking-widest">Seats Selected</small>
                        <div class="fw-bold text-white h4" id="seat-list">-</div>
                    </div>

                    <div class="bg-black rounded-3 p-3 mb-4 d-flex flex-column align-items-center border border-secondary">
                        <span class="text-white-50 small text-uppercase tracking-wider mb-1">Total Amount</span>
                        <span class="h2 text-warning mb-0 fw-bold">RM <span id="total-price">0.00</span></span>
                    </div>

                    <?= $this->Form->create(null, ['url' => ['action' => 'processBooking'], 'id' => 'booking-form']) ?>
                    <?= $this->Form->hidden('show_id', ['value' => $show->id, 'id' => 'show-id']) ?>
                    <?= $this->Form->hidden('selected_seats', ['id' => 'input-selected-seats']) ?>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold py-3 shadow-lg" id="btn-checkout" disabled>
                            CONFIRM & PAY
                        </button>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
            
            <div class="mt-4 p-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-4 text-center">
                <small class="text-white-50">Tickets are non-refundable once purchased.</small>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const seatBtns = document.querySelectorAll('.seat-btn');
        const seatListDisplay = document.getElementById('seat-list');
        const totalPriceDisplay = document.getElementById('total-price');
        const inputSelectedSeats = document.getElementById('input-selected-seats');
        const btnCheckout = document.getElementById('btn-checkout');
        const showId = document.getElementById('show-id').value;
        const countdownTimer = document.getElementById('lock-timer');
        const countdownDisplay = document.getElementById('countdown');

        // Initial seats from server (if refreshed)
        let selectedSeats = <?= json_encode($lockedByMe ?? []) ?>;
        let lockInterval = null;
        let remainingTime = <?= (int)($timerSeconds ?? 600) ?> || 600; 
        const csrfToken = '<?= $this->request->getAttribute('csrfToken') ?>';

        const container = document.getElementById('customer-seat-container');
        const scaler = document.getElementById('customer-seat-scaler');

        function updateScale() {
            if (!container || !scaler) return;

            scaler.style.transform = 'none';
            scaler.style.width = 'max-content';

            const containerWidth = container.offsetWidth;
            const contentWidth = scaler.offsetWidth;

            if (contentWidth > containerWidth && containerWidth > 0) {
                const scale = containerWidth / contentWidth;
                scaler.style.transform = `scale(${scale})`;
                scaler.style.transformOrigin = 'top center';
                // Adjust container height because transform scale doesn't affect document flow
                // Add buffer to prevent bottom clipping due to rounding or margins
                container.style.height = ((scaler.offsetHeight * scale) + 50) + 'px';
            } else {
                scaler.style.transform = 'none';
                scaler.style.width = '100%';
                container.style.height = 'auto';
            }
        }

        window.addEventListener('resize', updateScale);
        updateScale(); // Initial call
        setTimeout(updateScale, 300); // Delayed call to ensure layout is ready

        // Initialize state if seats are pre-selected
        if (selectedSeats.length > 0) {
            updateSummary();
            startTimer();
        }

        seatBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const seat = this.dataset.seat;
                const isSelecting = this.classList.contains('btn-outline-danger');
                
                this.style.pointerEvents = 'none';
                this.classList.add('opacity-50');

                fetch('<?= $this->Url->build(['action' => 'ajaxLockSeat']) ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-Token': csrfToken
                    },
                    body: `show_id=${showId}&seat_label=${seat}&lock_action=${isSelecting ? 'lock' : 'unlock'}`
                })
                .then(response => response.json())
                .then(data => {
                    this.style.pointerEvents = 'auto';
                    this.classList.remove('opacity-50');

                    if (data.status === 'success') {
                        if (isSelecting) {
                            this.classList.remove('btn-outline-danger');
                            this.classList.add('btn-success');
                            selectedSeats.push(seat);
                            startTimer();
                        } else {
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-danger');
                            selectedSeats = selectedSeats.filter(s => s !== seat);
                            if (selectedSeats.length === 0) stopTimer();
                        }
                        updateSummary();
                    } else if (data.status === 'locked') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Seat Reserved',
                            text: data.message,
                            background: '#1a1a1a',
                            color: '#fff',
                            confirmButtonColor: '#dc3545'
                        });
                        this.classList.remove('btn-outline-danger', 'btn-success');
                        this.classList.add('btn-warning', 'locked-btn', 'disabled');
                        this.disabled = true;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Booking Error',
                            text: data.message || 'Could not process seat selection.',
                            background: '#1a1a1a',
                            color: '#fff',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(err => {
                    this.style.pointerEvents = 'auto';
                    this.classList.remove('opacity-50');
                    Swal.fire({
                        icon: 'error',
                        title: 'Connection Error',
                        text: 'Failed to communicate with the server.',
                        background: '#1a1a1a',
                        color: '#fff',
                        confirmButtonColor: '#dc3545'
                    });
                });
            });
        });

        function updateSummary() {
            if (selectedSeats.length > 0) {
                // Generate HTML with icons, numbers, and types
                const summaryHtml = selectedSeats.map(seatLabel => {
                    const btn = document.querySelector(`.seat-btn[data-seat="${seatLabel}"]`);
                    let iconHtml = '<i class="fas fa-couch"></i>'; // Default fallback
                    let seatType = 'Standard';

                    if (btn) {
                        const iconEl = btn.querySelector('.seat-icon i');
                        if (iconEl) {
                            iconHtml = iconEl.outerHTML;
                        }
                        if (btn.dataset.type) {
                            seatType = btn.dataset.type;
                        }
                    }
                    
                    return `
                        <div class="d-inline-flex flex-column align-items-center bg-dark border border-light border-opacity-25 rounded-3 px-3 py-2 m-1 text-center shadow-sm" style="min-width: 80px;">
                            <span class="text-danger mb-1 h4">${iconHtml}</span>
                            <span class="text-white fw-bold h6 mb-0 text-nowrap">${seatLabel}</span>
                            <span class="text-warning x-small text-uppercase fw-bold mt-1" style="font-size: 0.65rem; letter-spacing: 1px;">${seatType}</span>
                        </div>
                    `;
                }).join('');

                seatListDisplay.innerHTML = '<div class="d-flex flex-wrap justify-content-center gap-1">' + summaryHtml + '</div>';
                inputSelectedSeats.value = selectedSeats.join(',');
                
                // New logic: Sum up prices of all selected seat buttons
                let total = 0;
                selectedSeats.forEach(seatLabel => {
                    const btn = document.querySelector(`.seat-btn[data-seat="${seatLabel}"]`);
                    if (btn && btn.dataset.price) {
                        total += parseFloat(btn.dataset.price);
                    }
                });
                totalPriceDisplay.textContent = total.toFixed(2);
                btnCheckout.disabled = false;
            } else {
                seatListDisplay.innerHTML = '<span class="text-white-50">-</span>';
                inputSelectedSeats.value = '';
                totalPriceDisplay.textContent = '0.00';
                btnCheckout.disabled = true;
            }
        }

        function startTimer(customTime = null) {
            if (lockInterval) return;
            if (customTime !== null) remainingTime = customTime;
            else if (remainingTime <= 0) remainingTime = 600;
            
            countdownTimer.classList.remove('d-none');
            updateTimerDisplay();
            lockInterval = setInterval(() => {
                remainingTime--;
                updateTimerDisplay();
                if (remainingTime <= 0) {
                    stopTimer();
                    Swal.fire({
                        icon: 'info',
                        title: 'Time Expired',
                        text: 'Your seat reservations have expired.',
                        background: '#1a1a1a',
                        color: '#fff',
                        confirmButtonColor: '#dc3545'
                    }).then(() => location.reload());
                }
            }, 1000);
        }

        function stopTimer() {
            clearInterval(lockInterval);
            lockInterval = null;
            remainingTime = 600; // Reset time to 10 minutes for next selection
            countdownTimer.classList.add('d-none');
        }

        function updateTimerDisplay() {
            const mins = Math.floor(remainingTime / 60);
            const secs = remainingTime % 60;
            countdownDisplay.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
        }

        setInterval(() => {
            fetch(`<?= $this->Url->build(['action' => 'ajaxRefreshLocks']) ?>/${showId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const othersLocked = data.locked_seats;
                    document.querySelectorAll('.seat-btn, .locked-btn').forEach(btn => {
                        const seat = btn.dataset.seat;
                        if (selectedSeats.includes(seat)) return;

                        if (othersLocked.includes(seat)) {
                            btn.classList.remove('btn-outline-danger', 'btn-success');
                            btn.classList.add('btn-warning', 'locked-btn', 'disabled');
                            btn.disabled = true;
                            if (!btn.querySelector('.fa-lock')) {
                                btn.innerHTML += '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem;"><i class="fas fa-lock"></i></span>';
                            }
                        } else if (btn.classList.contains('locked-btn')) {
                            if (!btn.classList.contains('btn-secondary')) {
                                btn.classList.remove('btn-warning', 'locked-btn', 'disabled');
                                btn.classList.add('btn-outline-danger', 'seat-btn');
                                btn.disabled = false;
                                const lockIcon = btn.querySelector('.badge');
                                if (lockIcon) lockIcon.remove();
                            }
                        }
                    });
                }
            });
        }, 10000);
    });
</script>
