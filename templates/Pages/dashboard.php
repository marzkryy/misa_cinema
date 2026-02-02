<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center mb-4 gap-3">
        <div>
            <h2 class="text-white border-start border-4 border-danger ps-3 fw-bold mb-0">ANALYTICS DASHBOARD</h2>
            <p class="text-white-50 small ps-3 mt-1 mb-0 fw-bold text-uppercase tracking-wider">Business Insights & Performance Tracking of Misa Cinema</p>
        </div>
        <div class="text-white bg-dark bg-opacity-50 px-3 px-lg-4 py-2 rounded-pill border border-secondary border-opacity-25 shadow-sm">
            <i class="fas fa-user-circle text-danger me-2"></i>
            <span class="text-white-50 small">Welcome back,</span>
            <span class="fw-bold text-danger ms-1">
                <?= $this->request->getSession()->read('Auth.User.name') ?>
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <!-- Total Sales -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card bg-black text-white h-100 border-0 shadow-lg overflow-hidden"
                style="background: #000000; border-radius: 20px; border: 3px solid #e50914 !important;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="z-1">
                            <p class="text-white-50 mb-1 text-uppercase small fw-bold tracking-wider">Total Earnings</p>
                            <h2 class="fw-bold mb-0 text-success" style="text-shadow: 0 0 15px rgba(40, 167, 69, 0.3);">RM <?= number_format($totalSales, 2) ?></h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-4 border border-success border-opacity-25">
                            <i class="fas fa-wallet text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Bookings -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card bg-black text-white h-100 border-0 shadow-lg overflow-hidden"
                style="background: #000000; border-radius: 20px; border: 3px solid #e50914 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1 text-uppercase small fw-bold tracking-wider">Bookings</p>
                            <h2 class="fw-bold mb-0 text-white"><?= number_format($totalBookings) ?></h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4 border border-primary border-opacity-25">
                            <i class="fas fa-ticket-alt text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card bg-black text-white h-100 border-0 shadow-lg overflow-hidden"
                style="background: #000000; border-radius: 20px; border: 3px solid #e50914 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1 text-uppercase small fw-bold tracking-wider">Customers</p>
                            <h2 class="fw-bold mb-0 text-info"><?= number_format($totalCustomers) ?></h2>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-4 border border-info border-opacity-25">
                            <i class="fas fa-users text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Movies -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card bg-black text-white h-100 border-0 shadow-lg overflow-hidden"
                style="background: #000000; border-radius: 20px; border: 3px solid #e50914 !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-white-50 mb-1 text-uppercase small fw-bold tracking-wider">Now Showing</p>
                            <h2 class="fw-bold mb-0 text-danger" style="text-shadow: 0 0 15px rgba(229, 9, 20, 0.3);"><?= number_format($activeMovies) ?></h2>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-4 border border-danger border-opacity-25">
                            <i class="fas fa-film text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Charts Row -->
    <div class="row g-4 mb-5">
        <!-- Revenue Trend Chart -->
        <div class="col-12 col-lg-8">
            <div class="card bg-black text-white border-0 shadow-lg h-100" style="border-radius: 20px; background: #000000; border: 1px solid rgba(255,255,255,0.05) !important;">
                <div class="card-header bg-transparent p-4 mb-3" style="border-bottom: 2px solid rgba(229, 9, 20, 0.5) !important;">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="fas fa-chart-line text-danger me-2"></i>REVENUE TREND (7 DAYS)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <canvas id="revenueChart" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Movie Popularity Chart -->
        <div class="col-12 col-lg-4">
            <div class="card bg-black text-white border-0 shadow-lg h-100" style="border-radius: 20px; background: #000000; border: 1px solid rgba(255,255,255,0.05) !important;">
                <div class="card-header bg-transparent p-4 mb-3" style="border-bottom: 2px solid rgba(229, 9, 20, 0.5) !important;">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="fas fa-chart-pie text-danger me-2"></i>POPULARITY
                    </h5>
                </div>
                <div class="card-body p-4 d-flex align-items-center justify-content-center">
                    <canvas id="popularityChart" style="max-height: 280px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Bookings -->
        <div class="col-12 col-lg-8 mb-4">
            <div class="card bg-black text-white border-0 shadow-sm" style="background: #000000; border-radius: 20px; border: 1px solid rgba(255,255,255,0.05) !important;">
                <div class="card-header bg-transparent border-0 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3 mb-3 gap-3" style="border-bottom: 3px solid #e50914 !important;">
                    <h5 class="mb-0 text-white font-weight-bold">
                        <i class="fas fa-clock text-danger me-2"></i><b>RECENT BOOKINGS</b>
                    </h5>
                    <div class="d-flex flex-wrap gap-2 w-100 w-md-auto">
                        <form method="get" action="" class="d-flex flex-grow-1 flex-md-grow-0">
                            <input type="text" name="search" class="form-control form-control-sm bg-black text-white border-secondary me-2" 
                                placeholder="Search ID, Movie, User..." value="<?= h($search ?? '') ?>" style="min-width: 150px;">
                            <button type="submit" class="btn btn-sm btn-danger px-3"><i class="fas fa-search"></i></button>
                            <?php if (!empty($search)): ?>
                                <a href="<?= $this->Url->build(['action' => 'dashboard']) ?>" class="btn btn-sm btn-outline-secondary ms-1"><i class="fas fa-times"></i></a>
                            <?php endif; ?>
                        </form>
                        <a href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'index']) ?>"
                            class="btn btn-sm btn-outline-danger">View All</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0 w-100">
                        <thead class="bg-danger text-white small text-uppercase fw-bold">
                            <tr>
                                <th class="ps-4 py-3" style="width: 120px;">Ref ID</th>
                                <th class="py-3">Customer</th>
                                <th class="py-3">Movie</th>
                                <th class="py-3">Date</th>
                                <th class="py-3 text-center">Amount</th>
                                <th class="py-3 text-center pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentBookings as $booking): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                                    <td class="ps-4 fw-bold text-white-50">#MC-<?= str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar rounded-circle bg-danger bg-opacity-25 text-danger d-flex align-items-center justify-content-center me-2 border border-danger border-opacity-25"
                                                style="width: 30px; height: 30px; font-size: 12px; font-weight: 800;">
                                                <?= strtoupper(substr($booking->customer->name ?? 'Guest', 0, 1)) ?>
                                            </div>
                                            <?= h($booking->customer->name ?? 'Guest') ?>
                                        </div>
                                    </td>
                                    <td class="fw-bold">
                                        <?= h($booking->show->show_title) ?>
                                    </td>
                                    <td class="small">
                                        <?= h($booking->book_date_time->format('d M Y, h:i A')) ?>
                                    </td>
                                    <td class="fw-bold text-center">RM <?= number_format((float) $booking->ticket_price, 2) ?></td>
                                    <td class="text-center pe-4">
                                        <?php if ($booking->status == 1): ?>
                                            <span class="badge bg-success text-white fw-bold px-2 py-2">Paid</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark fw-bold px-2 py-2">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recentBookings)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-white-50">
                                        <i class="fas fa-search fa-2x mb-3 d-block opacity-25"></i>
                                        No recent bookings found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Side Panel -->
        <div class="col-12 col-lg-4">
            <!-- Quick Actions -->
            <div class="card bg-black text-white border-0 shadow-lg mb-4 hover-lift" style="border-radius: 20px; background: #000000; border: 1px solid rgba(255,255,255,0.05) !important;">
                <div class="card-header bg-transparent py-4 px-4 mb-3" style="border-bottom: 3px solid #e50914 !important;">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="fas fa-bolt text-warning me-2"></i>QUICK ACTIONS
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <a href="<?= $this->Url->build(['controller' => 'Shows', 'action' => 'add']) ?>"
                                class="btn btn-danger py-3 text-start d-flex align-items-center rounded-4 shadow-lg text-white border-0 btn-add-movie-custom" 
                                style="background: linear-gradient(45deg, #ff0000, #cc0000); border: 2px solid rgba(255,255,255,0.2) !important; color: #ffffff !important;">
                                <i class="fas fa-plus-circle fa-2x me-3 text-white"></i>
                                <div>
                                    <div class="fw-bold h5 mb-0 text-white" style="color: #ffffff !important;">ADD NEW MOVIE</div>
                                    <div class="small text-white-50 opacity-75">Upload new screening session</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= $this->Url->build(['controller' => 'Bookings', 'action' => 'index']) ?>"
                                class="btn btn-outline-light py-3 px-3 text-start d-flex align-items-center rounded-4 h-100 border-opacity-25">
                                <i class="fas fa-calendar-alt me-2 text-danger"></i>
                                <div class="fw-bold small">Bookings</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= $this->Url->build(['controller' => 'Customers', 'action' => 'index']) ?>"
                                class="btn btn-outline-light py-3 px-3 text-start d-flex align-items-center rounded-4 h-100 border-opacity-25">
                                <i class="fas fa-users me-2 text-primary"></i>
                                <div class="fw-bold small">Customers</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= $this->Url->build(['controller' => 'Staffs', 'action' => 'index']) ?>"
                                class="btn btn-outline-light py-3 px-3 text-start d-flex align-items-center rounded-4 h-100 border-opacity-25">
                                <i class="fas fa-user-shield me-2 text-warning"></i>
                                <div class="fw-bold small">Staffs</div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= $this->Url->build(['controller' => 'Halls', 'action' => 'index']) ?>"
                                class="btn btn-outline-light py-3 px-3 text-start d-flex align-items-center rounded-4 h-100 border-opacity-25">
                                <i class="fas fa-building me-2 text-info"></i>
                                <div class="fw-bold small">Halls</div>
                            </a>
                        </div>
                        <div class="col-12">
                            <a href="<?= $this->Url->build(['controller' => 'Seats', 'action' => 'index']) ?>"
                                class="btn btn-outline-light py-3 px-3 text-start d-flex align-items-center rounded-4 h-100 border-opacity-25">
                                <i class="fas fa-chair me-3 text-success"></i>
                                <div class="fw-bold small">Manage Seat Map & Rules</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="card bg-black text-white border-0 shadow-lg" style="border-radius: 20px; background: #000000; border: 1px solid rgba(255,255,255,0.05) !important;">
                <div class="card-body p-4">
                    <h6 class="text-white text-uppercase small fw-bold tracking-wider mb-4 d-flex align-items-center pb-3" style="border-bottom: 3px solid #e50914 !important;">
                        <i class="fas fa-server text-info me-2"></i>System Status
                    </h6>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold">Server Uptime</span>
                            <span class="text-success small fw-bold">99.9%</span>
                        </div>
                        <div class="progress bg-black bg-opacity-50" style="height: 6px; border-radius: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 99.9%; box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);"></div>
                        </div>
                    </div>

                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold">Database</span>
                            <span class="text-info small fw-bold">Optimal</span>
                        </div>
                        <div class="progress bg-black bg-opacity-50" style="height: 6px; border-radius: 10px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%; box-shadow: 0 0 10px rgba(13, 202, 240, 0.5);"></div>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 text-center">
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill x-small fw-bold">
                            <i class="fas fa-check-circle me-1"></i> ALL SYSTEMS NOMINAL
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Revenue Chart
    const ctxRevenue = document.getElementById('revenueChart').getContext('2d');
    const revenueGradient = ctxRevenue.createLinearGradient(0, 0, 0, 350);
    revenueGradient.addColorStop(0, 'rgba(229, 9, 20, 0.4)');
    revenueGradient.addColorStop(1, 'rgba(229, 9, 20, 0)');

    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: <?= json_encode($days) ?>,
            datasets: [{
                label: 'Revenue (RM)',
                data: <?= json_encode($revenueData) ?>,
                borderColor: '#e50914',
                backgroundColor: revenueGradient,
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointBackgroundColor: '#e50914',
                pointBorderColor: '#fff',
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#e50914',
                borderWidth: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleFont: { weight: 'bold' },
                    padding: 12,
                    cornerRadius: 10,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                    ticks: { color: '#888', font: { size: 11 }, padding: 10 }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#888', font: { size: 11 }, padding: 10 }
                }
            }
        }
    });

    // 2. Popularity Chart
    const ctxPopularity = document.getElementById('popularityChart').getContext('2d');
    new Chart(ctxPopularity, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($movieLabels) ?>,
            datasets: [{
                data: <?= json_encode($movieCounts) ?>,
                backgroundColor: [
                    '#198754', // Emerald Green (Replaced Red)
                    '#ffc107', // Gold
                    '#0dcaf0', // Cyan
                    '#d63384', // Pink
                    '#f8f9fa'  // Silver/White
                ],
                hoverBackgroundColor: [
                    '#157347',
                    '#ffcd39',
                    '#31d2f2',
                    '#e685b5',
                    '#ffffff'
                ],
                borderWidth: 0,
                hoverOffset: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#ddd', padding: 20, font: { size: 12, weight: 'bold' }, usePointStyle: true, pointStyle: 'circle' }
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    padding: 12,
                    cornerRadius: 10
                }
            }
        }
    });
});
</script>

<style>
.rounded-4 { border-radius: 1rem !important; }
.tracking-wider { letter-spacing: 2px !important; }
.x-small { font-size: 0.75rem; }
.hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.4) !important; }
canvas { filter: drop-shadow(0 0 15px rgba(0,0,0,0.3)); }

.btn-add-movie-custom {
    transition: all 0.3s ease;
}

.btn-add-movie-custom:hover {
    transform: scale(1.03) translateY(-3px);
    filter: brightness(1.2);
    box-shadow: 0 15px 30px rgba(229, 9, 20, 0.4) !important;
}
</style>
