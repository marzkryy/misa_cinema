<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Show> $groupedShows
 * @var array $availableDates
 * @var string $selectedDate
 */
$this->assign('title', 'Schedule - MisaCinema');
$authUser = $this->request->getSession()->read('Auth.User');
$isAdmin = isset($authUser['role']) && $authUser['role'] === 'admin';
?>
<div class="container mt-5 mb-5 pb-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="text-white fw-bold tracking-wider text-uppercase border-bottom border-danger d-inline-block pb-2">
            <?= $isAdmin ? 'MANAGE SHOWS' : 'NOW SHOWING' ?>
        </h1>
        <p class="text-white small mt-2">
            <?= $isAdmin ? 'This Section is for Admin to Add and Manage Movies' : 'Book Your Movie Tickets Here !' ?>
        </p>

        <?php if ($isAdmin): ?>
            <div class="mt-3">
                <?= $this->Html->link(__('<i class="fas fa-plus me-2"></i>ADD NEW SHOW'), ['action' => 'add'], ['class' => 'btn btn-danger fw-bold shadow-sm px-4 py-2', 'escape' => false, 'style' => 'border-radius: 50px;']) ?>
            </div>
        <?php endif; ?>
    </div>

    <?php
    // Helper closure to render date list
    $view = $this;
    $currentViewParam = $this->request->getQuery('view');
    $renderDateList = function($dates, $currentSelected, $viewContext) use ($view, $currentViewParam) {
        if (empty($dates)) return '<p class="text-white-50 text-center small fst-italic py-3">No dates available.</p>';
        
        $html = '<div class="d-flex overflow-auto py-3 px-2 no-scrollbar bg-dark rounded-pill border border-secondary border-opacity-10" style="gap: 10px;">';
        foreach ($dates as $date) {
            $dateObj = ($date instanceof \DateTimeInterface) ? $date : new \DateTime($date);
            $dateStr = $dateObj->format('Y-m-d');
            
            // Active only if date matches AND (view matches OR view is not set/defaults)
            $isActive = ($currentSelected == $dateStr) && ($currentViewParam === $viewContext || ($viewContext === 'active' && !$currentViewParam));
            
            $activeClass = $isActive ? 'active bg-danger shadow-danger' : 'bg-black opacity-75';
            $textColor = $isActive ? 'text-white' : 'text-danger';
            
            // Add view context to URL
            $url = $view->Url->build(['action' => 'index', '?' => ['date' => $dateStr, 'view' => $viewContext]]);
            
            $html .= '<a href="' . $url . '" class="date-tab text-decoration-none text-center rounded-pill py-2 px-4 transition-all ' . $activeClass . '" style="min-width: 90px; flex-shrink: 0;">';
            $html .= '<span class="d-block small fw-bold text-uppercase opacity-75 ' . $textColor . '">' . $dateObj->format('D') . '</span>';
            $html .= '<span class="d-block h4 mb-0 fw-bold text-white">' . $dateObj->format('d') . '</span>';
            $html .= '<span class="d-block x-small fw-bold text-white opacity-50">' . $dateObj->format('M') . '</span>';
            $html .= '</a>';
        }
        $html .= '</div>';
        return $html;
    };
    ?>

    <div class="date-selector-wrapper mb-5">
        <?php if ($isAdmin): ?>
            <!-- Admin View: Split Active vs History -->
            
            <h6 class="text-white text-uppercase tracking-wider mb-3 ps-3 border-start border-3 border-danger">Active Schedules</h6>
            <div class="mb-4 shadow-lg">
                <?= $renderDateList($activeDates, $selectedDate, 'active') ?>
            </div>

            <?php if (!empty($historyDates)): ?>
                <h6 class="text-white text-uppercase tracking-wider mb-3 ps-3 border-start border-3 border-secondary opacity-75">History Schedules</h6>
                <div class="mb-4 shadow-lg opacity-75">
                    <?= $renderDateList($historyDates, $selectedDate, 'history') ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Customer View: Single List (Active Only) -->
            <div class="shadow-lg">
                <?= $renderDateList($activeDates, $selectedDate, 'active') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Movie Listings -->
    <div class="movie-listings">
        <?php if (!empty($groupedShows)): ?>
            <?php foreach ($groupedShows as $title => $sessions):
                $firstShow = $sessions[0];
                ?>
                <div class="movie-card bg-dark bg-opacity-50 border border-secondary border-opacity-10 rounded-4 overflow-hidden shadow-sm hover-shadow transition-all" 
                     style="margin-bottom: 4rem;">
                    <div class="row g-0">
                        <!-- Poster Section -->
                        <div class="col-4 col-md-3 col-lg-2">
                            <?php if ($firstShow->avatar): ?>
                                <div class="poster-wrapper">
                                    <img src="<?= $this->Url->build('/img/shows/' . $firstShow->avatar) ?>"
                                        class="movie-poster img-fluid w-100">
                                </div>
                            <?php else: ?>
                                <div class="w-100 h-100 bg-black d-flex flex-column align-items-center justify-content-center"
                                     style="min-height: 150px;">
                                    <i class="fas fa-film fa-2x text-danger mb-2"></i>
                                    <span class="x-small text-white-50">NO POSTER</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Details & Showtimes Section -->
                        <div class="col-8 col-md-9 col-lg-10 p-3 p-md-4 d-flex flex-column justify-content-center position-relative">
                            
                            <!-- Admin Tools (Responsive: Relative on Mobile, Absolute on Desktop) -->
                            <?php if ($isAdmin): ?>
                                <div class="admin-tools-responsive p-1 p-md-3 z-3">
                                    <?= $this->Html->link(__('<i class="fas fa-edit"></i>'), ['action' => 'edit', $firstShow->id, '?' => ['view' => $currentViewParam]], ['class' => 'btn btn-outline-warning border-0 me-1 check-icon', 'escape' => false, 'title' => 'Edit Movie']) ?>
                                    
                                    <?php 
                                        $delFormId = 'delete-group-' . $firstShow->id;
                                        $delMsg = __('Are you sure you want to delete ALL sessions for "{0}"?', $title);
                                    ?>
                                    <div class="d-inline-block">
                                        <?= $this->Form->create(null, ['url' => ['action' => 'deleteGroup', $firstShow->id, '?' => ['view' => $currentViewParam]], 'id' => $delFormId, 'style' => 'display:none;']) ?>
                                        <?= $this->Form->end() ?>
                                        
                                        <a href="#" 
                                           class="btn btn-outline-danger border-0 check-icon" 
                                           title="Delete Group" 
                                           onclick="return confirmDelete(event, '<?= $delFormId ?>', '<?= h($delMsg) ?>')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <h2 class="h5 h4-md fw-bold text-white mb-1 tracking-tight"><?= h($title) ?></h2>
                                <div class="movie-meta d-flex flex-wrap gap-2 gap-md-3">
                                    <span class="text-danger small fw-bold"><i
                                            class="fas fa-tags me-1"></i><?= h($firstShow->genre) ?></span>
                                    <span class="text-white small opacity-75">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php 
                                            $dur = $firstShow->duration ?? 120; // Default 120 minutes
                                            $h = floor($dur / 60);
                                            $m = $dur % 60;
                                            echo "{$h}h {$m}m";
                                        ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Showtimes Grid -->
                            <div class="showtime-section mt-2 mt-md-0">
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($sessions as $session): 
                                        $timeObj = ($session->show_time instanceof \DateTimeInterface) ? $session->show_time : new \DateTime($session->show_time);
                                        $formattedTime = $timeObj->format('h:i A');
                                    ?>
                                        <?php if (isset($isAdmin) && $isAdmin): ?>
                                            <!-- Admin View: Non-clickable Display -->
                                            <div class="showtime-badge d-inline-block bg-black border border-secondary border-opacity-50 text-white rounded text-center">
                                                <div class="fw-bold time-text"><?= $formattedTime ?></div>
                                                <div class="hall-text text-danger fw-bold opacity-75">
                                                    <?= $session->has('hall') ? h($session->hall->hall_type) : '2D Digital' ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <!-- Customer View: Booking Link -->
                                            <a href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'chooseSeat', $session->id]) ?>"
                                                class="showtime-btn text-decoration-none transition-all">
                                                <div class="showtime-badge bg-black border border-secondary border-opacity-50 text-white rounded text-center">
                                                    <div class="fw-bold time-text"><?= $formattedTime ?></div>
                                                    <div class="hall-text text-danger fw-bold opacity-75">
                                                        <?= $session->has('hall') ? h($session->hall->hall_type) : '2D Digital' ?>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 bg-dark rounded-4 border border-secondary border-opacity-10 shadow-sm mt-5">
                <div class="p-5">
                    <i class="fas fa-calendar-times fa-4x text-danger opacity-25 mb-4"></i>
                    <h3 class="text-white fw-bold">No Movies on this Day</h3>
                    <p class="text-white">Please select another date to see the show schedule.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .tracking-wider { letter-spacing: 3px; }
    .tracking-tight { letter-spacing: -0.5px; }
    .x-small { font-size: 0.7rem; }
    .transition-all { transition: all 0.25s ease-in-out; }

    .date-tab {
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .date-tab:hover:not(.active) {
        background-color: rgba(255, 255, 255, 0.05) !important;
        transform: translateY(-2px);
    }
    .date-tab.active {
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }

    .poster-wrapper {
        aspect-ratio: 2/3;
        width: 100%;
        background: #000;
        overflow: hidden;
    }

    .movie-poster {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    @media (min-width: 768px) {
        .poster-wrapper {
            height: 100%;
            aspect-ratio: auto;
        }
        .movie-poster {
            min-height: 250px;
            max-height: 350px;
        }
    }

    .showtime-btn, .showtime-badge {
        flex: 1 1 calc(50% - 10px);
        min-width: 80px;
    }

    .showtime-badge {
        padding: 0.5rem 0.25rem;
        border-radius: 10px !important;
    }

    .time-text { font-size: 0.8rem; margin-bottom: 0; }
    .hall-text { font-size: 0.6rem; }

    @media (min-width: 768px) {
        .showtime-btn, .showtime-badge {
            flex: 0 0 auto;
            min-width: 120px;
        }
        .showtime-badge {
            padding: 1rem;
            border-radius: 12px !important;
        }
        .time-text { font-size: 1rem; }
        .hall-text { font-size: 0.7rem; }
    }

    .showtime-btn:hover .showtime-badge {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }
    
    .showtime-btn:hover .text-white { color: white !important; }
    .showtime-btn:hover .text-danger { color: white !important; }

    .movie-card:hover {
        transform: translateY(-5px);
        border-color: rgba(220, 53, 69, 0.4) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
    }

        .movie-card:hover { transform: none; }
        .movie-card { margin-bottom: 2rem !important; }
    }

    .admin-tools-responsive {
        position: relative;
        text-align: right;
        margin-bottom: 0.5rem;
    }

    @media (min-width: 768px) {
        .admin-tools-responsive {
            position: absolute;
            top: 0;
            right: 0;
            margin-bottom: 0;
        }
    }
</style>