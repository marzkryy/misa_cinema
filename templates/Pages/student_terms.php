<?php
/**
 * Student Terms Page
 */
$this->assign('title', 'Student Promo Terms');
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-dark text-white border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Header -->
                <div class="card-header bg-danger p-5 text-center position-relative overflow-hidden">
                    <div class="position-relative z-1">
                        <i class="fas fa-graduation-cap fa-4x mb-3 text-white"></i>
                        <h1 class="fw-bold display-5">STUDENT SPECIAL</h1>
                        <p class="lead mb-0 fw-bold" style="letter-spacing: 2px;">TERMS & CONDITIONS</p>
                    </div>
                    <!-- Background Pattern -->
                    <i class="fas fa-university position-absolute" style="font-size: 15rem; color: rgba(0,0,0,0.1); bottom: -30px; left: -30px; transform: rotate(15deg);"></i>
                </div>

                <div class="card-body p-5">
                    <h4 class="text-danger fw-bold mb-4 border-bottom border-secondary pb-2 border-opacity-25">ELIGIBILITY REQUIREMENTS</h4>
                    
                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1 shadow-sm" style="width: 40px; height: 40px;">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="fw-bold">Valid Student ID</h5>
                            <p class="text-white-50 mb-0">You must present a valid physical or digital Student ID card issued by a recognized educational institution upon ticket verification at the hall entrance.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1 shadow-sm" style="width: 40px; height: 40px;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="fw-bold">Applicable Showtimes</h5>
                            <p class="text-white-50 mb-0">Valid for movies showing on <strong>Weekdays (Monday to Friday)</strong> before <strong>6:00 PM</strong> excluding Public Holidays.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1 shadow-sm" style="width: 40px; height: 40px;">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="fw-bold">Verification Process</h5>
                            <p class="text-white-50 mb-0">Our staff reserves the right to verify your age and student status. If you fail to produce a valid ID, you may be required to top up the difference to a Standard Adult ticket price.</p>
                        </div>
                    </div>

                    <div class="bg-black text-white border border-warning p-4 rounded d-flex align-items-center mt-5 shadow-sm" role="alert">
                        <i class="fas fa-exclamation-triangle fa-2x me-3 text-warning"></i>
                        <div>
                            <strong class="text-warning">Note:</strong> Not applicable for Couple Seats, Gold Class, or Special Screenings. Cannot be combined with other promotional vouchers.
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <a href="<?= $this->Url->build('/shows') ?>" class="btn btn-danger btn-lg rounded-pill px-5 shadow fw-bold">
                            Find a Movie Now <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                        <div class="mt-4">
                            <a href="<?= $this->Url->build('/') ?>" class="btn btn-outline-light rounded-pill px-4 fw-bold border-secondary text-white-50">
                                <i class="fas fa-chevron-left me-2"></i>Back to Home
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
