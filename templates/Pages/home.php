<?php
/**
 * MisaCinema Home Page
 */
$this->assign('title', 'Home');
?>

<!-- Hero Slider -->
<div id="movieCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#movieCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#movieCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#movieCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <!-- Video Background -->
            <div class="video-container" style="height: 500px; position: relative; overflow: hidden; background: #000;">
                <video autoplay muted loop playsinline class="w-100" style="height: 100%; object-fit: cover; opacity: 0.6;">
                    <source src="<?= $this->Url->build('/video/ghostbusters.mp4') ?>" type="video/mp4">
                    <img src="https://placehold.co/1200x500/000000/e50914?text=FEATURED+MOVIE+1" alt="Fallback">
                </video>
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);"></div>
            </div>
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100 text-center" style="z-index: 2; top: 0; bottom: 0;">
                <h1 class="display-3 fw-bold text-danger text-uppercase d-none d-md-block">Featured Movie 1</h1>
                <h1 class="h3 fw-bold text-danger text-uppercase d-md-none">Featured Movie 1</h1>
                
                <h2 class="display-5 fw-bold text-white text-uppercase mb-3 d-none d-md-block">Blockbuster Season</h2>
                <h2 class="h5 fw-bold text-white text-uppercase mb-2 d-md-none">Blockbuster Season</h2>
                
                <p class="lead mb-4 d-none d-md-block">Experience the thrill in our Dolby Atmos halls.</p>
                <p class="small mb-3 d-md-none">Experience the thrill in our Dolby Atmos halls.</p>
                
                <a href="<?= $this->Url->build('/shows') ?>" class="btn btn-primary btn-lg rounded-pill px-5 d-none d-md-inline-block">Book Now</a>
                <a href="<?= $this->Url->build('/shows') ?>" class="btn btn-primary btn-sm rounded-pill px-4 d-md-none">Book Now</a>
            </div>
        </div>
        <div class="carousel-item">
            <!-- Video Background 2 -->
            <div class="video-container" style="height: 500px; position: relative; overflow: hidden; background: #000;">
                <video autoplay muted loop playsinline class="w-100" style="height: 100%; object-fit: cover; opacity: 0.6;">
                    <source src="<?= $this->Url->build('/video/anaconda.mp4') ?>" type="video/mp4">
                    <img src="https://placehold.co/1200x500/1f1f1f/1f1f1f" alt="Fallback">
                </video>
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);"></div>
            </div>
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100 text-center" style="z-index: 2; top: 0; bottom: 0;">
                <h1 class="display-3 fw-bold text-danger text-uppercase d-none d-md-block">Family Favorites</h1>
                <h1 class="h3 fw-bold text-danger text-uppercase d-md-none">Family Favorites</h1>
                
                <h2 class="display-5 fw-bold text-white text-uppercase mb-3 d-none d-md-block">The Ultimate Experience</h2>
                <h2 class="h5 fw-bold text-white text-uppercase mb-2 d-md-none">The Ultimate Experience</h2>
                
                <p class="lead mb-4 d-none d-md-block">Bring the whole family for a weekend of fun.</p>
                <p class="small mb-3 d-md-none">Bring the whole family for a weekend of fun.</p>
                
                <a href="<?= $this->Url->build('/shows') ?>" class="btn btn-primary btn-lg rounded-pill px-5 d-none d-md-inline-block">View Showtimes</a>
                <a href="<?= $this->Url->build('/shows') ?>" class="btn btn-primary btn-sm rounded-pill px-4 d-md-none">View Showtimes</a>
            </div>
        </div>
        <div class="carousel-item">
            <!-- Video Background 3 -->
            <div class="video-container" style="height: 500px; position: relative; overflow: hidden; background: #000;">
                <video autoplay muted loop playsinline class="w-100" style="height: 100%; object-fit: cover; opacity: 0.6;">
                    <source src="<?= $this->Url->build('/video/spiderman.mp4') ?>" type="video/mp4">
                    <img src="https://placehold.co/1200x500/111111/111111" alt="Fallback">
                </video>
                <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);"></div>
            </div>
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100 text-center" style="z-index: 2; top: 0; bottom: 0;">
                <h1 class="display-3 fw-bold text-danger text-uppercase d-none d-md-block">Coming Soon</h1>
                <h1 class="h3 fw-bold text-danger text-uppercase d-md-none">Coming Soon</h1>
                
                <h2 class="display-5 fw-bold text-white text-uppercase mb-3 d-none d-md-block">Stay Tuned</h2>
                <h2 class="h5 fw-bold text-white text-uppercase mb-2 d-md-none">Stay Tuned</h2>
                
                <p class="lead d-none d-md-block">Be the first to see the next big hit.</p>
                <p class="small d-md-none mb-0">Be the first to see the next big hit.</p>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#movieCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#movieCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<!-- Now Showing Section -->
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white border-start border-4 border-danger ps-3">NOW SHOWING</h2>
        <a href="<?= $this->Url->build('/shows') ?>" class="btn btn-outline-danger btn-view-all rounded-pill px-4 fw-bold shadow-sm">
            View All <i class="fas fa-chevron-right ms-1 small"></i>
        </a>
    </div>

    <div class="position-relative">
        <!-- Navigation Arrows -->
        <button class="btn btn-dark position-absolute top-50 start-0 translate-middle-y z-3 rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
                id="scrollLeftBtn" style="width: 50px; height: 50px; opacity: 0.8; margin-left: -20px;">
            <i class="fas fa-chevron-left text-white"></i>
        </button>
        <button class="btn btn-dark position-absolute top-50 end-0 translate-middle-y z-3 rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
                id="scrollRightBtn" style="width: 50px; height: 50px; opacity: 0.8; margin-right: -20px;">
            <i class="fas fa-chevron-right text-white"></i>
        </button>

        <!-- Carousel Container -->
        <div class="d-flex overflow-auto gap-4 py-3 px-2 no-scrollbar scroll-smooth" id="nowShowingCarousel">
            <?php if (!empty($shows)): ?>
                <?php foreach ($shows as $show): ?>
                    <div class="movie-card-wrapper" style="min-width: 250px; width: 250px;">
                        <div class="movie-card position-relative overflow-hidden rounded-3 shadow-sm h-100 border border-secondary border-opacity-25 bg-black">
                            <!-- Movie Image -->
                            <div style="padding-top: 150%; position: relative;">
                                <?php if ($show->avatar): ?>
                                    <img src="<?= $this->Url->build('/img/shows/' . $show->avatar) ?>" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover"
                                        alt="<?= h($show->show_title) ?>">
                                <?php else: ?>
                                    <img src="https://placehold.co/300x450/222/e50914?text=<?= urlencode($show->show_title) ?>"
                                        class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" alt="Movie Title">
                                <?php endif; ?>
                            </div>

                            <!-- Hover Overlay -->
                            <div class="movie-overlay position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-3 text-white" 
                                 style="background: linear-gradient(to top, rgba(0,0,0,0.9), rgba(0,0,0,0.6), transparent);">
                                <h5 class="fw-bold mb-1 text-truncate"><?= h($show->show_title) ?></h5>
                                <p class="small mb-2 text-white-50 text-truncate">
                                    <i class="fas fa-tag text-danger me-1"></i> <?= h($show->genre) ?>
                                </p>
                                <a href="<?= $this->Url->build(['controller' => 'Shows', 'action' => 'index']) ?>"
                                    class="btn btn-danger fw-bold text-white w-100 shadow-sm rounded-pill btn-buy-ticket">
                                    BUY TICKET
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <p class="text-white-50">No movies currently showing. Please check back later!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scroll-smooth {
        scroll-behavior: smooth;
    }
    .movie-overlay {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .movie-card:hover .movie-overlay {
        opacity: 1;
    }
    .movie-card {
        transition: transform 0.3s ease;
    }
    .movie-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(220, 53, 69, 0.2) !important;
        border-color: #dc3545 !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carousel = document.getElementById('nowShowingCarousel');
        const leftBtn = document.getElementById('scrollLeftBtn');
        const rightBtn = document.getElementById('scrollRightBtn');
        
        // Scroll amount (approx card width + gap)
        const scrollAmount = 280; 

        // Arrow Navigation
        leftBtn.addEventListener('click', () => {
            carousel.scrollLeft -= scrollAmount;
            resetAutoScroll();
            // Wrap handling for infinite feel check inside scroll listener if needed, 
            // but native smooth scroll usually handles bounds gracefully.
        });

        rightBtn.addEventListener('click', () => {
            carousel.scrollLeft += scrollAmount;
            resetAutoScroll();
        });

        // Auto Scroll Logic
        let autoScrollInterval;
        const autoScrollSpeed = 3000; // 3 seconds

        function startAutoScroll() {
            autoScrollInterval = setInterval(() => {
                // Check if we are near the end
                if (carousel.scrollLeft + carousel.clientWidth >= carousel.scrollWidth - 10) {
                    // Reset to beginning smoothly
                     carousel.scrollTo({
                        top: 0,
                        left: 0,
                        behavior: 'smooth'
                    });
                } else {
                    carousel.scrollLeft += scrollAmount;
                }
            }, autoScrollSpeed);
        }

        function stopAutoScroll() {
            clearInterval(autoScrollInterval);
        }

        function resetAutoScroll() {
            stopAutoScroll();
            startAutoScroll();
        }

        // Pause on hover
        carousel.addEventListener('mouseenter', stopAutoScroll);
        carousel.addEventListener('mouseleave', startAutoScroll);

        // Start initially
        startAutoScroll();
    });
</script>

<!-- Promotions Section -->
<div class="container mt-5 mb-5">
    <h2 class="text-white border-start border-4 border-danger ps-3 mb-4">PROMOTIONS</h2>
    <div class="row">
        <div class="col-12 mb-3">
            <div class="p-5 rounded shadow-lg position-relative overflow-hidden" style="background: linear-gradient(45deg, #b20710, #333);">
                <div class="position-relative z-1">
                    <h3 class="fw-bold display-6 mb-3"><i class="fas fa-graduation-cap me-3"></i>Student Special</h3>
                    <p class="fs-5 mb-4">Get <span class="fw-bold text-warning">20% OFF</span> on all weekday shows before 6 PM. Just flash your student ID!</p>
                    <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'display', 'student_terms']) ?>" class="btn btn-light btn-lg rounded-pill fw-bold px-5 text-danger shadow-sm">Learn More</a>
                </div>
                <!-- Artistic Element -->
                <i class="fas fa-ticket-alt position-absolute" style="font-size: 15rem; color: rgba(255,255,255,0.05); bottom: -50px; right: 20px; transform: rotate(-15deg);"></i>
            </div>
        </div>
    </div>
</div>