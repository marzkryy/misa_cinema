<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Booking> $bookings
 * @var bool $isAdmin
 */
$authUser = $this->request->getSession()->read('Auth.User');
$isAdmin = isset($authUser['role']) && $authUser['role'] === 'admin';
?>
<div class="container mt-5 mb-5">
    <div class="d-flex flex-column align-items-center mb-5 text-center">
        <div class="mb-4">
            <h2 class="text-white fw-bold mb-0 text-uppercase" style="letter-spacing: 2px; border-bottom: 3px solid #dc3545; padding-bottom: 10px; display: inline-block;">
                <?= $isAdmin ? __('BOOKING MANAGEMENT') : __('MY BOOKINGS') ?>
            </h2>
        </div>
        <?php if (!$isAdmin): ?>
            <?= $this->Html->link(__('<i class="fas fa-ticket-alt me-2"></i>Book New Movie'), ['controller' => 'Shows', 'action' => 'index'], ['class' => 'btn btn-danger fw-bold shadow-lg rounded-pill px-5 py-2', 'escape' => false]) ?>
        <?php endif; ?>
    </div>

    <?php 
    $hasBookings = false;
    // New variable to track if we should show the list or the empty state
    $bookingsFoundInView = false;

    if ($isAdmin) {
        $hasBookings = count($bookings) > 0;
        $bookingsFoundInView = count($bookings) > 0;
    } else {
        $hasBookings = (count($activeBookings) > 0) || (count($historyBookings) > 0);
        $bookingsFoundInView = $hasBookings;
    }
    ?>

    <?php if (isset($isAdmin) && $isAdmin): ?>
        <!-- Admin Filter Form -->
        <div class="card bg-dark border border-secondary border-opacity-25 mb-4">
            <div class="card-body p-4">
                <form method="get" action="">
                    <div class="row g-2 g-md-3 align-items-end">
                        <div class="col-6 col-md-3">
                            <label class="form-label text-white-50 small text-uppercase fw-bold">Filter by Date</label>
                            <input type="date" name="show_date" class="form-control form-control-sm bg-black text-white border-secondary" 
                                   value="<?= h($searchDate ?? '') ?>">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label text-white-50 small text-uppercase fw-bold">Search Movie</label>
                            <input type="text" name="search_query" class="form-control form-control-sm bg-black text-white border-secondary" 
                                   placeholder="Movie name" value="<?= h($searchQuery ?? '') ?>">
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label text-white-50 small text-uppercase fw-bold">Search Customer</label>
                            <input type="text" name="search_customer" class="form-control form-control-sm bg-black text-white border-secondary" 
                                   placeholder="Name/Email" value="<?= h($searchCustomer ?? '') ?>">
                        </div>
                        <div class="col-6 col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-danger flex-grow-1 fw-bold">
                                <i class="fas fa-filter me-1"></i><span class="d-none d-sm-inline">Filter</span>
                            </button>
                            <?php if (!empty($searchDate) || !empty($searchQuery) || !empty($searchCustomer)): ?>
                                <a href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-sm btn-outline-secondary flex-grow-1">
                                    <span class="d-none d-sm-inline">Clear</span><i class="fas fa-times d-sm-none"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>


        <?php if ($isAdmin): ?>
            <?php
            // Group bookings by screening (show_id)
            $screenings = [];
            foreach ($bookings as $booking) {
                $showId = $booking->show_id;
                if (!isset($screenings[$showId])) {
                    $screenings[$showId] = [
                        'show' => $booking->show,
                        'hall' => $booking->hall,
                        'bookings' => []
                    ];
                }
                $screenings[$showId]['bookings'][] = $booking;
            }
            ?>

            <?php foreach ($screenings as $showId => $screening): ?>
                <div class="card bg-dark text-white shadow-lg border-0 mb-4" style="border-radius: 20px;">
                    <div class="card-header bg-black border-0 p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="fw-bold mb-1"><?= h($screening['show']->show_title) ?></h4>
                                <div class="text-white-50 small">
                                    <i class="fas fa-calendar me-2"></i>
                                    <?= $screening['show']->show_date instanceof \DateTimeInterface ? $screening['show']->show_date->format('l, d M Y') : $screening['show']->show_date ?>
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-clock me-2"></i>
                                    <?php
                                    $timeObj = ($screening['show']->show_time instanceof \DateTimeInterface) ? $screening['show']->show_time : new \DateTime($screening['show']->show_time);
                                    echo $timeObj->format('h:i A');
                                    ?>
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-video me-2"></i>
                                    <?= $screening['hall'] ? h($screening['hall']->hall_type) : 'Standard Hall' ?>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                    <?= count($screening['bookings']) ?> Booking(s)
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-5">
                        <!-- Auto-Scaling Seat Map Wrapper -->
                        <div class="seat-map-container mt-4 mb-5 mx-auto" id="container-<?= $showId ?>" style="background: #000; border-radius: 20px; padding: 60px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); max-width: 100%;">
                            <div class="seat-map-scaler" id="scaler-<?= $showId ?>">
                                
                                <!-- Theater Screen (Now Inside Scaler) -->
                                <div class="screen-container text-center mb-5">
                                    <div class="screen bg-light text-dark py-1 px-5 d-inline-block rounded shadow-sm opacity-50"
                                        style="width: 80%; transform: perspective(300px) rotateX(-5deg); font-weight: bold; letter-spacing: 5px;">
                                        SCREEN
                                    </div>
                                </div>

                                <div class="seat-map d-flex flex-column align-items-center">
                                    <?php
                                    $bookedSeats = [];
                                    foreach ($allTickets as $ticket) {
                                        if ($ticket->show_id === $showId && $ticket->has('show_seat')) {
                                            $seatLabel = $ticket->show_seat->seat_row . $ticket->show_seat->seat_number;
                                            $customerName = ($ticket->has('booking') && $ticket->booking->has('customer'))
                                                ? $ticket->booking->customer->name
                                                : 'Guest';

                                            $bookedSeats[$seatLabel] = [
                                                'customer' => $customerName,
                                                'booking_id' => $ticket->booking_id
                                            ];
                                        }
                                    }

                                    $hallLayout = $groupedSeatsByShow[$showId] ?? [];
                                    $presentTypes = [];
                                    foreach ($hallLayout as $rowLabel => $rowSeats) {
                                        foreach ($rowSeats as $seat) {
                                            $presentTypes[] = strtolower($seat->seat_type);
                                        }
                                    }
                                    $presentTypes = array_unique($presentTypes);
                                    ?>
                                    
                                    <?php if (empty($hallLayout)): ?>
                                        <div class="text-white-50 small mb-4">No hall layout configured for this show.</div>
                                    <?php else: ?>
                                        <?php 
                                        // Sort rows alphabetically (A-Z)
                                        ksort($hallLayout);
                                        foreach ($hallLayout as $rowLabel => $rowSeats): ?>
                                            <div class="d-flex align-items-center justify-content-center gap-2 mb-2 w-100" style="min-width: fit-content;">
                                                <!-- Row Label -->
                                                <div class="text-white-50 fw-bold me-3" style="width: 25px; font-size: 0.9rem;"><?= h($rowLabel) ?></div>
                                                
                                                <div class="d-flex gap-2">
                                                    <?php
                                                         foreach ($rowSeats as $seatNum => $seat):
                                                         $seatLabel = $rowLabel . $seatNum;
                                                         $isBooked = isset($bookedSeats[$seatLabel]);
                                                         $isMaintenance = ($seat->status == 0);
                                                         $type = strtolower($seat->seat_type);
                                                                                                                 // Visual Logic for Admin View (Matching Customer)
                                                         $btnClass = $isBooked ? 'btn-danger shadow-red' : 'btn-outline-danger';
                                                         $disabledAttr = '';
                                                         $tooltip = '';

                                                         if ($isMaintenance) {
                                                             $btnClass = 'btn-dark disabled opacity-25';
                                                             $disabledAttr = 'disabled';
                                                             $tooltip = 'Maintenance / Unavailable';
                                                         } elseif ($isBooked) {
                                                             $tooltip = 'Booked by: ' . $bookedSeats[$seatLabel]['customer'];
                                                         } else {
                                                             $btnClass .= ' ' . $type;
                                                             $tooltip = 'Available (' . ucfirst($type) . ')';
                                                         }
                                                         
                                                         $iconClass = 'fa-couch';
                                                         if ($type == 'couple') $iconClass = 'fa-user-friends';
                                                         if ($type == 'bed') $iconClass = 'fa-bed';
                                                         if ($type == 'premium') $iconClass = 'fa-crown';
                                                         ?>
                                                        <button type="button" 
                                                                class="btn <?= $btnClass ?> seat-btn position-relative"
                                                                title="<?= $tooltip ?>"
                                                                <?= $disabledAttr ?>
                                                                data-bs-toggle="tooltip">
                                                            <div class="seat-icon"><i class="fas <?= $iconClass ?>"></i></div>
                                                            <span style="font-size: 0.65rem; font-weight: 800;"><?= $seatNum ?></span>
                                                            <?php if ($isMaintenance): ?>
                                                                <span class="position-absolute top-50 start-50 translate-middle text-white" style="font-size: 0.8rem;">
                                                                    <i class="fas fa-tools"></i>
                                                                </span>
                                                            <?php elseif (!$isBooked): ?>
                                                                <span class="seat-type-label d-none d-md-block" style="font-size: 0.5rem; opacity: 0.7;"><?= h($type) ?></span>
                                                            <?php endif; ?>
                                                        </button>
                                                    <?php endforeach; ?>
                                                </div>

                                                <!-- Row Label (Mirror) -->
                                                <div class="text-white-50 fw-bold ms-3" style="width: 25px; font-size: 0.9rem;"><?= h($rowLabel) ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <script>
                        (function() {
                            const container = document.getElementById('container-<?= $showId ?>');
                            const scaler = document.getElementById('scaler-<?= $showId ?>');
                            
                            function updateScale() {
                                if (!container || !scaler) return;
                                
                                // Reset to measure
                                scaler.style.transform = 'none';
                                scaler.style.width = 'max-content';
                                
                                const containerWidth = container.offsetWidth;
                                const contentWidth = scaler.offsetWidth;
                                
                                if (contentWidth > containerWidth && containerWidth > 0) {
                                    const scale = containerWidth / contentWidth;
                                    scaler.style.transform = `scale(${scale})`;
                                    scaler.style.transformOrigin = 'top center';
                                    // Adjust container height because transform scale doesn't affect document flow
                                    scaler.style.transformOrigin = 'top center';
                                    // Adjust container height because transform scale doesn't affect document flow
                                    container.style.height = ((scaler.offsetHeight * scale) + 50) + 'px'; // +50px Buffer
                                } else {
                                    scaler.style.transform = 'none';
                                    scaler.style.width = '100%';
                                    container.style.height = 'auto';
                                }
                            }

                            window.addEventListener('resize', updateScale);
                            // Run after all images/fonts are likely loaded
                            window.addEventListener('load', updateScale);
                            setTimeout(updateScale, 300);
                        })();
                        </script>

                        <!-- Map Legend (Matching Customer View) -->
                        <div class="mt-4 p-3 bg-black bg-opacity-25 rounded shadow-sm border border-light border-opacity-10" style="max-width: 800px; margin: 0 auto;">
                            <div class="d-flex justify-content-center flex-wrap gap-4">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-danger rounded-pill p-0 d-flex align-items-center justify-content-center" style="width: 22px; height: 22px; font-size: 0.6rem;"><i class="fas fa-couch"></i></span>
                                    <span class="text-white-50 x-small fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Booked</span>
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

                        <div class="mt-5">
                            <h6 class="text-danger fw-bold mb-3 d-flex align-items-center">
                                <i class="fas fa-list me-2"></i>BOOKING DETAILS
                            </h6>
                            <div class="table-responsive border border-secondary border-opacity-25 rounded-3 overflow-hidden">
                                <table class="table table-dark table-hover mb-0 align-middle">
                                    <thead class="bg-danger text-white text-uppercase small fw-bold">
                                        <tr>
                                            <th class="ps-4 py-3" style="width: 15%;">Booking ID</th>
                                            <th class="py-3" style="width: 20%;">Customer</th>
                                            <th class="py-3" style="width: 30%;">Seats</th>
                                            <th class="py-3 text-center" style="width: 15%;">Amount</th>
                                            <th class="py-3 text-center" style="width: 10%;">Status</th>
                                            <th class="py-3 text-center pe-4" style="width: 10%;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="small text-white">
                                        <?php foreach ($screening['bookings'] as $booking): ?>
                                            <tr class="admin-booking-row" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                                <td class="ps-4 py-3 text-white-50 fw-bold">#MC-<?= str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT) ?></td>
                                                <td class="fw-bold"><?= $booking->has('customer') ? h($booking->customer->name) : 'Guest' ?></td>
                                                <td>
                                                    <span class="badge bg-black border border-secondary border-opacity-75 text-white px-3 py-2 fw-bold shadow-sm" style="font-size: 0.9rem; letter-spacing: 0.5px;">
                                                        <?php
                                                        $seats = [];
                                                        if (!empty($booking->tickets)) {
                                                            foreach ($booking->tickets as $ticket) {
                                                                // Prioritize show_seat snapshot
                                                                if ($ticket->has('show_seat')) {
                                                                    $seats[] = $ticket->show_seat->seat_row . $ticket->show_seat->seat_number;
                                                                } elseif ($ticket->has('seat')) {
                                                                    $seats[] = $ticket->seat->seat_row . $ticket->seat->seat_number;
                                                                }
                                                            }
                                                        }
                                                        
                                                        if (!empty($seats)) {
                                                            natsort($seats); // Natural sort (A1, A2, A10)
                                                            echo implode(', ', $seats);
                                                        } else {
                                                            echo $booking->quantity . ' seat(s)';
                                                        }
                                                        ?>
                                                    </span>
                                                </td>
                                                <td class="text-center fw-bold">RM <?= number_format((float) $booking->ticket_price, 2) ?></td>
                                                <td class="text-center">
                                                    <?php if ($booking->status == 1): ?>
                                                        <span class="badge bg-success text-white fw-bold px-3 py-2">PAID</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning text-white fw-bold px-3 py-2">PENDING</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center pe-4">
                                                    <?= $this->Html->link('<i class="fas fa-eye"></i>', ['action' => 'view', $booking->id], ['class' => 'btn btn-outline-light btn-sm rounded-circle btn-action-icon', 'escape' => false, 'title' => 'View Details']) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <!-- Customer View: Active & History -->
            
            <?php
            // Helper function to render table rows
            $view = $this;
            $renderRows = function ($bookingsList, $isHistory) use ($view) {
                if (empty($bookingsList)) {
                    return '<tr><td colspan="6" class="text-center py-4 text-white-50">No tickets found.</td></tr>';
                }
                $html = '';
                foreach ($bookingsList as $booking) {
                    $statusBadge = '';
                    if ($isHistory) {
                        $statusBadge = '<span class="badge rounded-pill bg-success text-white fw-bold px-3 py-2">SUCCESSFUL</span>';
                    } else {
                        // For Active tickets
                        if ($booking->status == 1) {
                            $statusBadge = '<span class="badge rounded-pill bg-success text-white fw-bold px-3 py-2">CONFIRMED</span>';
                        } else {
                            $statusBadge = '<span class="badge rounded-pill bg-warning text-dark fw-bold px-3 py-2">PENDING</span>';
                        }
                    }

                    $dayName = $booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('D') : (new \DateTime($booking->show->show_date))->format('D');
                    $showDate = ($booking->show->show_date instanceof \DateTimeInterface ? $booking->show->show_date->format('d M Y') : $booking->show->show_date);
                    $showDate = h($dayName) . ', ' . h($showDate);
                    $timeObj = ($booking->show->show_time instanceof \DateTimeInterface) ? $booking->show->show_time : new \DateTime($booking->show->show_time);
                    $showTime = $timeObj->format('h:i A');
                    
                    $avatar = '';
                    if ($booking->show->avatar) {
                        $imgUrl = $view->Url->build('/img/shows/' . $booking->show->avatar);
                        $avatar = '<img src="' . $imgUrl . '" class="rounded me-3 border border-secondary border-opacity-25 shadow-sm" style="height: 80px; width: 55px; object-fit: cover;">';
                    }

                    if ($booking->status == 1) {
                        $viewBtn = $view->Html->link(__('<i class="fas fa-eye me-1"></i> <span>VIEW</span>'), ['action' => 'view', $booking->id], ['class' => 'btn btn-danger btn-sm px-3 rounded-pill fw-bold view-btn', 'escape' => false]);
                    } else {
                        $viewBtn = $view->Html->link(__('<i class="fas fa-credit-card me-1"></i> <span>PAY NOW</span>'), ['action' => 'payment', $booking->id], ['class' => 'btn btn-warning btn-sm px-3 rounded-pill fw-bold pay-btn', 'escape' => false]);
                    }

                    $seatLabels = [];
                    if (!empty($booking->tickets)) {
                        foreach ($booking->tickets as $ticket) {
                            if ($ticket->has('show_seat')) {
                                $seatLabels[] = $ticket->show_seat->seat_row . $ticket->show_seat->seat_number;
                            }
                        }
                    } elseif (!empty($booking->seat_selection)) {
                        // Fallback to seat_selection string (common for pending bookings)
                        $seatLabels = explode(',', $booking->seat_selection);
                    }
                    
                    $seatLabels = array_unique(array_filter(array_map('trim', $seatLabels)));
                    natsort($seatLabels);
                    $seatInfo = !empty($seatLabels) ? implode(', ', $seatLabels) : $booking->quantity . ' Seat(s)';

                    $html .= '<tr class="booking-row transition-all" style="border-bottom: 1px solid rgba(255,255,255,0.05);">';
                    $html .= '<td class="ps-4 py-3"><div class="d-flex align-items-center flex-wrap flex-md-nowrap">' . $avatar . '<div class="mt-2 mt-md-0"><div class="fs-5 fw-bold text-wrap" style="max-width: 250px;">' . h($booking->show->show_title) . '</div><div class="small text-danger fw-bold">' . h($booking->hall->hall_type) . ' | ' . $seatInfo . '</div></div></div></td>';
                    $bookedDate = $booking->book_date_time instanceof \DateTimeInterface 
                        ? $booking->book_date_time->format('d M Y, h:i A') 
                        : $booking->book_date_time;
                    $html .= '<td><div class="small fw-bold">' . $showDate . ' <span class="text-danger mx-1">@</span> ' . $showTime . '</div><div class="x-small text-white-50">Booked: ' . h($bookedDate) . '</div></td>';
                    $html .= '<td class="text-center fw-bold text-white">RM ' . number_format((float) $booking->ticket_price, 2) . '</td>';
                    $html .= '<td class="text-center">' . $statusBadge . '</td>';
                    $html .= '<td class="text-center pe-4"><div class="btn-group shadow-sm">' . $viewBtn . '</div></td>';
                    $html .= '</tr>';
                }
                return $html;
            };
            ?>

            <!-- ACTIVE TICKETS SECTION -->
            <?php if (count($activeBookings) > 0): ?>
                <div class="mb-5">
                    <h4 class="text-white mb-3 fw-bold"><i class="fas fa-ticket-alt text-danger me-2"></i> ACTIVE TICKETS</h4>
                    <div class="card bg-black text-white shadow-lg border-0 overflow-hidden" style="border-radius: 20px; border: 1px solid rgba(255,255,255,0.05) !important;">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0 align-middle">
                                <thead class="bg-danger text-white text-uppercase small fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3">MOVIE DETAILS</th>
                                        <th class="py-3">SHOW DATE/TIME</th>
                                        <th class="py-3 text-center">AMOUNT</th>
                                        <th class="py-3 text-center">STATUS</th>
                                        <th class="py-3 text-center pe-4">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $uniqueActive = [];
                                    $filteredActive = [];
                                    foreach ($activeBookings as $b) {
                                        $key = $b->show_id . '_' . $b->seat_selection;
                                        if (!isset($uniqueActive[$key])) {
                                            $uniqueActive[$key] = true;
                                            $filteredActive[] = $b;
                                        }
                                    }
                                    echo $renderRows($filteredActive, false);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- HISTORY TICKETS SECTION -->
            <?php if (count($historyBookings) > 0): ?>
                <div class="mb-5">
                    <h4 class="text-white mb-3 fw-bold"><i class="fas fa-history text-danger me-2"></i> HISTORY TICKETS</h4>
                    <div class="card bg-black text-white shadow-lg border-0 overflow-hidden" style="border-radius: 20px; border: 1px solid rgba(255,255,255,0.05) !important;">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0 align-middle">
                                <thead class="bg-danger text-white text-uppercase small fw-bold">
                                    <tr>
                                        <th class="ps-4 py-3">MOVIE DETAILS</th>
                                        <th class="py-3">SHOW DATE/TIME</th>
                                        <th class="py-3 text-center">AMOUNT</th>
                                        <th class="py-3 text-center">STATUS</th>
                                        <th class="py-3 text-center pe-4">ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $uniqueHistory = [];
                                    $filteredHistory = [];
                                    foreach ($historyBookings as $b) {
                                        $key = $b->show_id . '_' . $b->seat_selection;
                                        if (!isset($uniqueHistory[$key])) {
                                            $uniqueHistory[$key] = true;
                                            $filteredHistory[] = $b;
                                        }
                                    }
                                    echo $renderRows($filteredHistory, true);
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    <!-- Empty State Logic -->
    <?php if (!$bookingsFoundInView): ?>
        <div class="card bg-dark text-white border-0 shadow-lg p-5 text-center" style="border-radius: 20px;">
            <div class="py-5">
                <i class="fas fa-calendar-times fa-4x text-danger opacity-25 mb-4"></i>
                <h4 class="fw-bold">No bookings found</h4>
                <p class="text-white-50">
                    <?= (!empty($searchDate) || !empty($searchQuery) || !empty($searchCustomer)) ? 'No bookings match your filters.' : 'You haven\'t made any bookings yet.' ?>
                </p>
                <?php if (!$isAdmin): ?>
                    <div class="mt-4">
                        <?= $this->Html->link(__('Explore Now Showing'), ['controller' => 'Shows', 'action' => 'index'], ['class' => 'btn btn-outline-danger rounded-pill px-4']) ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Paginator (Only for Admin) -->
    <?php if ($isAdmin && count($bookings) > 0): ?>
    <div class="card bg-black border border-secondary border-opacity-25 mt-4 overflow-hidden rounded-4 shadow-lg">
        <div class="card-body py-4">
            <div class="d-flex flex-column align-items-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-md mb-3">
                        <?= $this->Paginator->first('<i class="fas fa-angle-double-left"></i>', [
                            'escape' => false,
                            'class' => 'page-item',
                            'linkClass' => 'page-link bg-dark border-secondary px-3'
                        ]) ?>
                        <?= $this->Paginator->prev('<i class="fas fa-chevron-left"></i>', [
                            'escape' => false,
                            'class' => 'page-item',
                            'linkClass' => 'page-link bg-dark border-secondary px-3'
                        ]) ?>
                        <?= $this->Paginator->numbers([
                            'class' => 'page-item',
                            'linkClass' => 'page-link border-secondary mx-1 px-3',
                            'activeClass' => 'active',
                            'modulus' => 4
                        ]) ?>
                        <?= $this->Paginator->next('<i class="fas fa-chevron-right"></i>', [
                            'escape' => false,
                            'class' => 'page-item',
                            'linkClass' => 'page-link bg-dark border-secondary px-3'
                        ]) ?>
                        <?= $this->Paginator->last('<i class="fas fa-angle-double-right"></i>', [
                            'escape' => false,
                            'class' => 'page-item',
                            'linkClass' => 'page-link bg-dark border-secondary px-3'
                        ]) ?>
                    </ul>
                </nav>
                <div class="text-white-50 small fw-bold text-uppercase tracking-wider">
                    <?= $this->Paginator->counter(__('Showing {{current}} of {{count}} Bookings')) ?>
                    <span class="mx-2 text-danger">|</span>
                    Page <?= $this->Paginator->counter('{{page}}') ?> of <?= $this->Paginator->counter('{{pages}}') ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
    .x-small {
        font-size: 0.75rem;
    }

    .transition-all {
        transition: all 0.2s ease;
    }

    .movie-card:hover {
        transform: translateY(-5px);
    }

    /* Smooth VIEW button hover: White background with Red text/icon */
    .table .btn.view-btn:hover {
        background-color: #ffffff !important;
        border-color: #ffffff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 20px rgba(255, 255, 255, 0.3) !important;
    }

    .table .btn.view-btn:hover i,
    .table .btn.view-btn:hover span {
        color: #e50914 !important;
        background-color: transparent !important;
    }

    .table .btn.pay-btn {
        background-color: #ffab00 !important;
        color: #000000 !important;
        border: none !important;
    }

    .table .btn.pay-btn:hover {
        background-color: #ffffff !important;
        color: #ffab00 !important;
        border-color: #ffffff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 20px rgba(255, 255, 255, 0.3) !important;
    }

    .table .btn.pay-btn:hover i,
    .table .btn.pay-btn:hover span {
        color: #ffab00 !important;
        background-color: transparent !important;
    }

    .view-btn, .pay-btn {
        transition: all 0.3s ease !important;
    }

    .booking-row {
        transition: all 0.2s ease;
    }

    .booking-row:hover {
        background: rgba(255, 0, 0, 0.05) !important;
    }

    .table thead th {
        border-bottom: none !important;
    }

    .table td {
        color: #ffffff !important;
        background-color: transparent !important;
    }

    /* Force white text in booking details table */
    .table-dark thead th {
        color: #ffffff !important;
    }

    .table-dark tbody td {
        color: #ffffff !important;
    }

    .table-dark tbody tr {
        color: #ffffff !important;
    }

    /* Better responsiveness for Bookings table - Stack Layout for Mobile */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: visible !important;
            border: none !important;
        }
        
        .table thead {
            display: none; /* Hide headers on mobile */
        }
        
        .table, .table tbody, .table tr, .table td {
            display: block;
            width: 100% !important;
        }
        
        .table tr {
            margin-bottom: 2rem;
            background: rgba(255,255,255,0.03);
            border-radius: 15px;
            padding: 1.5rem;
            border: 1px solid rgba(255,255,255,0.08) !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .table td {
            text-align: left !important;
            border: none !important;
            padding: 0.8rem 0 !important;
            position: relative;
        }

        .booking-row td:first-child {
            padding-top: 0 !important;
            border-bottom: 1px solid rgba(255,255,255,0.05) !important;
            padding-bottom: 1rem !important;
            margin-bottom: 0.5rem;
        }
        
        /* Add labels for mobile */
        .booking-row td:nth-child(2):before { content: "SHOWTIME"; display: block; font-size: 0.65rem; color: #dc3545; font-weight: 800; margin-bottom: 0.2rem; }
        .booking-row td:nth-child(3):before { content: "TOTAL AMOUNT"; display: block; font-size: 0.65rem; color: #dc3545; font-weight: 800; margin-bottom: 0.2rem; }
        .booking-row td:nth-child(4):before { content: "STATUS"; display: block; font-size: 0.65rem; color: #dc3545; font-weight: 800; margin-bottom: 0.2rem; }

        .booking-row td:last-child {
            padding-bottom: 0 !important;
            margin-top: 1rem;
            padding-top: 1rem !important;
            border-top: 1px solid rgba(255,255,255,0.05) !important;
        }

        /* FORCE COLUMNS TO SHOW (Override cinema.css global hiding) */
        .admin-booking-row td:nth-child(2),
        .admin-booking-row td:nth-child(4) {
            display: block !important;
        }

        /* ADMIN TABLE LABELS */
        .admin-booking-row td:nth-child(1):before { content: "BOOKING ID"; display: block; font-size: 0.65rem; color: #dc3545; font-weight: 800; margin-bottom: 0.2rem; }
        .admin-booking-row td:nth-child(2):before { content: "CUSTOMER"; display: block; font-size: 0.65rem; color: #dc3545; font-weight: 800; margin-bottom: 0.2rem; }
        .admin-booking-row td:nth-child(3):before { content: "SEATS"; display: block; font-size: 0.65rem; color: #dc3545; font-weight: 800; margin-bottom: 0.2rem; }
        .admin-booking-row td:nth-child(4):before { content: "AMOUNT"; display: block; font-size: 0.65rem; color: #dc3545; font-weight: 800; margin-bottom: 0.2rem; }
        .admin-booking-row td:nth-child(5):before { content: "STATUS"; display: block; font-size: 0.65rem; color: #dc3545; font-weight: 800; margin-bottom: 0.2rem; }

        .fs-5 {
            font-size: 1.25rem !important;
        }
        
        .btn-group {
            width: 100%;
        }
        
        .btn-group .view-btn {
            width: 100%;
            padding: 12px !important;
            font-size: 0.9rem !important;
        }
    }
    
    .text-wrap {
        white-space: normal !important;
        word-wrap: break-word;
    }

    /* --- MASTER SEAT STYLES (Ported from Seats/index.php) --- */
    .seat-btn {
        flex: 0 0 52px;
        width: 52px !important;
        height: 52px !important;
        border-radius: 6px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(255,255,255,0.1);
        position: relative;
        padding: 0 !important;
        overflow: hidden;
        background: transparent; /* Default */
    }

    .seat-btn:hover {
        transform: scale(1.15);
        z-index: 10;
        box-shadow: 0 5px 20px rgba(255, 255, 255, 0.3);
    }

    /* Active/Booked/Type States */
    .seat-btn.btn-outline-danger {
        /* Available Standard */
        border-color: #e50914 !important;
        color: #e50914 !important;
    }

    /* Hover Effects for Available Seats (Matching Customer Experience) */
    .seat-btn.btn-outline-danger:hover {
        background-color: #e50914 !important;
        color: #fff !important;
    }
    
    .seat-btn.btn-danger {
        /* Booked */
        background-color: #e50914 !important;
        border-color: #e50914 !important;
        color: #fff !important;
        opacity: 1 !important;
    }

    /* Seat Types Configuration */
    .seat-btn.couple {
        flex: 0 0 110px;
        width: 110px !important;
        border-color: #f72585 !important;
        color: #f72585 !important;
    }
    .seat-btn.couple.btn-danger {
        background-color: #f72585 !important;
        color: #fff !important;
    }
    .seat-btn.couple:hover:not(.btn-danger):not(.disabled) {
        background-color: #f72585 !important;
        color: #fff !important;
        border-color: #f72585 !important;
    }

    .seat-btn.bed {
        flex: 0 0 140px;
        width: 140px !important;
        border-color: #4cc9f0 !important;
        color: #4cc9f0 !important;
    }
    .seat-btn.bed.btn-danger {
        background-color: #4cc9f0 !important;
        color: #fff !important;
    }
    .seat-btn.bed:hover:not(.btn-danger):not(.disabled) {
        background-color: #4cc9f0 !important;
        color: #fff !important;
        border-color: #4cc9f0 !important;
    }

    .seat-btn.premium {
        border-color: #fd7e14 !important;
        color: #fd7e14 !important;
    }
    .seat-btn.premium.btn-danger {
        background-color: #fd7e14 !important;
        color: #fff !important;
    }
    .seat-btn.premium:hover:not(.btn-danger):not(.disabled) {
        background-color: #fd7e14 !important;
        color: #fff !important;
        border-color: #fd7e14 !important;
    }

    /* Internal Elements */
    .seat-btn .seat-icon {
        font-size: 1.2rem;
        margin-bottom: 2px;
    }
    .seat-btn.couple .seat-icon { font-size: 1.4rem; }
    .seat-btn.bed .seat-icon { font-size: 1.6rem; }

    .seat-btn span:not(.seat-icon) {
        /* Seat Number */
        font-size: 0.9rem !important; /* Match master size */
        font-weight: 500 !important;
        line-height: 1;
    }

    .seat-type-label {
        font-size: 0.55rem !important;
        text-transform: uppercase;
        opacity: 0.9;
        font-weight: 400;
        letter-spacing: -0.3px;
        margin-top: 2px;
        display: block;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding: 0 1px;
    }

    /* Disabled/Maintenance */
    .seat-btn.disabled {
        background: #1a1a1a !important;
        color: rgba(255,255,255,0.3) !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
        opacity: 1 !important;
    }

    /* Responsive adjustments matching Master */
    @media (max-width: 1200px) {
        .seat-btn {
            flex: 0 0 32px;
            width: 32px !important;
            height: 32px !important;
        }
        .seat-btn .seat-icon { font-size: 0.8rem; }
        /* Hide label on small screens if needed, or adjust */
        .seat-btn span:not(.seat-icon) { font-size: 0.7rem !important; }
        .seat-type-label { display: none !important; }
        .seat-type-label { display: none !important; }
    }

    /* Mobile Optimization for Admin Seat Map (Matched with Customer View) */
    @media (max-width: 767.98px) {
        .seat-btn {
            flex: 0 0 38px;
            width: 38px !important;
            height: 38px !important;
        }
        
        .seat-btn span {
             font-size: 0.5rem !important; 
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
        
        /* Apply the centering & padding fix */
        .seat-map-container {
            padding: 30px 0 10px 0 !important;
            min-height: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize Auto-Scalers for all screening maps
        <?php foreach ($groupedSeatsByShow as $showId => $layout): ?>
            initAutoScaler('container-<?= $showId ?>', 'scaler-<?= $showId ?>');
        <?php endforeach; ?>
    });
</script>