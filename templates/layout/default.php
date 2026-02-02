<?php
/**
 * MisaCinema Layout
 */

$cakeDescription = 'MisaCinema: Experience the Best';
?>
<!DOCTYPE html>
<html>

<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon', 'img/logomisacinema.png') ?>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome 6.5.1 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <!-- Library SweetAlert2 CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= $this->Html->css(['cake', 'miligram', 'cinema'], ['timestamp' => 'force']) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body class="<?= $this->fetch('body_class') ?> bg-cinema">
    <?php
    $authUser = $this->request->getSession()->read('Auth.User');
    $isAdmin = isset($authUser['role']) && $authUser['role'] === 'admin';
    $isAuthPage = $this->fetch('body_class') === 'login-page' ||
        ($this->request->getParam('controller') === 'Customers' && $this->request->getParam('action') === 'add');
    ?>

    <nav class="navbar navbar-expand-lg navbar-dark bg-black sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?= $this->Url->build('/home') ?>">
                <i class="fas fa-film"></i> MISA CINEMA
            </a>
            
            <?php if (!$isAuthPage): ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php 
                    $currController = $this->request->getParam('controller');
                    $currAction = $this->request->getParam('action');
                    ?>

                    <li class="nav-item">
                        <a class="nav-link <?= $currController === 'Pages' && in_array($currAction, ['home', 'dashboard', 'display']) ? 'active' : '' ?>" href="<?= $this->Url->build('/home') ?>"><?= $isAdmin ? 'Dashboard' : 'Home' ?></a>
                    </li>

                    <li class="nav-item">
                        <?php if ($isAdmin): ?>
                            <a class="nav-link <?= $currController === 'Shows' ? 'active' : '' ?>" href="<?= $this->Url->build('/shows') ?>">Add Shows</a>
                        <?php else: ?>
                            <a class="nav-link <?= $currController === 'Shows' ? 'active' : '' ?>" href="<?= $this->Url->build('/shows') ?>">Now Showing</a>
                        <?php endif; ?>
                    </li>
                    
                    <?php if ($authUser): ?>
                        <li class="nav-item">
                            <?php
                            $bookingLabel = $isAdmin ? 'Customer Bookings' : 'My Bookings';
                            ?>
                            <a class="nav-link <?= $currController === 'Bookings' ? 'active' : '' ?>" href="<?= $this->Url->build('/bookings') ?>"><?= h($bookingLabel) ?></a>
                        </li>
                    <?php endif; ?>
                    
                    <!-- Check for Session User -->
                    <?php if ($authUser): ?>

                        <?php if (isset($authUser['role']) && $authUser['role'] === 'admin'): ?>
                            <!-- Admin Management Dropdown -->
                            <?php 
                                $isManagement = in_array($currController, ['Customers', 'Staffs', 'Halls', 'Seats']);
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle <?= $isManagement ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">
                                    Management
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li><a class="dropdown-item <?= $currController === 'Customers' ? 'active text-danger fw-bold' : '' ?>" href="<?= $this->Url->build('/customers') ?>">Customers</a></li>
                                    <li><a class="dropdown-item <?= $currController === 'Staffs' ? 'active text-danger fw-bold' : '' ?>" href="<?= $this->Url->build('/staffs') ?>">Staffs</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>

                                    <li><a class="dropdown-item <?= $currController === 'Halls' ? 'active text-danger fw-bold' : '' ?>" href="<?= $this->Url->build('/halls') ?>">Halls</a></li>
                                    <li><a class="dropdown-item <?= $currController === 'Seats' ? 'active text-danger fw-bold' : '' ?>" href="<?= $this->Url->build('/seats') ?>">Seats</a></li>
                                </ul>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                Hello, <?= h($authUser['name']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <?php if (!$isAdmin): ?>
                                    <li><a class="dropdown-item <?= ($currController === 'Customers' && $currAction === 'view') ? 'active text-danger fw-bold' : '' ?>"
                                            href="<?= $this->Url->build(['controller' => 'Customers', 'action' => 'view', $authUser['id']]) ?>">My
                                            Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="<?= $this->Url->build('/customers/logout') ?>">Logout</a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $this->Url->build('/customers/login') ?>">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </nav>

    <main class="main py-4">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>

    <footer class="footer mt-auto py-5 border-top border-secondary border-opacity-10">
        <div class="container text-center">
            <?php if ($authUser): ?>
                <div class="footer-customer-nav mb-5">
                    <?php if ($isAdmin): ?>
                        <a href="<?= $this->Url->build('/shows') ?>" class="f-customer-pill">
                            <i class="fas fa-plus-circle"></i>ADD SHOWS
                        </a>
                        <a href="<?= $this->Url->build('/bookings') ?>" class="f-customer-pill">
                            <i class="fas fa-list-alt"></i>CUSTOMER BOOKINGS
                        </a>
                        <div class="dropdown d-inline-block">
                            <a href="#" class="f-customer-pill dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-tasks"></i>MANAGEMENT
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-premium shadow-lg border-0 rounded-4 p-2">
                                <li><a class="dropdown-item rounded-3 py-2 px-3 mb-1" href="<?= $this->Url->build('/customers') ?>"><i class="fas fa-users-cog me-2 text-danger"></i>Customers</a></li>
                                <li><a class="dropdown-item rounded-3 py-2 px-3 mb-1" href="<?= $this->Url->build('/staffs') ?>"><i class="fas fa-user-shield me-2 text-danger"></i>Staffs</a></li>
                                <li><hr class="dropdown-divider border-secondary border-opacity-10"></li>
                                <li><a class="dropdown-item rounded-3 py-2 px-3 mb-1" href="<?= $this->Url->build('/halls') ?>"><i class="fas fa-door-open me-2 text-danger"></i>Halls</a></li>
                                <li><a class="dropdown-item rounded-3 py-2 px-3" href="<?= $this->Url->build('/seats') ?>"><i class="fas fa-chair me-2 text-danger"></i>Seats</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= $this->Url->build('/shows') ?>" class="f-customer-pill">
                            <i class="fas fa-play-circle"></i>NOW SHOWING
                        </a>
                        <a href="<?= $this->Url->build('/bookings') ?>" class="f-customer-pill">
                            <i class="fas fa-ticket-alt"></i>MY BOOKINGS
                        </a>
                        <a href="<?= $this->Url->build(['controller' => 'Customers', 'action' => 'view', $authUser['id']]) ?>" class="f-customer-pill">
                            <i class="fas fa-user-circle"></i>MY PROFILE
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="row justify-content-center g-4">
                <div class="col-md-4">
                    <h5 class="footer-title fw-bold mb-3">
                        <i class="fas fa-film me-2 text-danger"></i>MISA CINEMA
                    </h5>
                    <p class="small text-white-50"><b>Experience Movies Like Never Before In Our Premium Halls. We Bring You The Latest Movies With World-Class Sound and Visual Quality.</b></p>
                </div>
                <div class="col-md-4">
                    <h5 class="footer-title fw-bold mb-3 text-uppercase">Contact Information</h5>
                    <p class="small text-white-50 mb-2"><b>For any other inquiries:</b></p>
                    <ul class="list-unstyled small text-white-50 mb-3">
                        <li class="mb-2"><i class="fas fa-envelope text-danger me-2"></i>misacinemaa@gmail.com</li>
                        <li><i class="fas fa-phone text-danger me-2"></i>+6017-741 4139</li>
                    </ul>
                    <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'contact']) ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3 py-1 small">
                        <i class="fas fa-exclamation-circle me-1"></i>Report an Issue
                    </a>
                </div>
                <div class="col-md-4">
                    <h5 class="footer-title fw-bold mb-3 text-uppercase">Follow Our Story</h5>
                    <div class="d-flex justify-content-center gap-3 mt-3">
                        <a href="https://www.facebook.com/people/Misa-CinemaMy/pfbid02XX2CcxD7pbKvmpBYt9J3WbZRCn6yQvfUtGJHmL4RoDN42FVtLwbBEAgVjFq2x5sGl/" target="_blank" class="social-link" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/misacinema/" target="_blank" class="social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://x.com/MisaCinema" target="_blank" class="social-link" title="Twitter"><i class="fab fa-x-twitter"></i></a>
                    </div>
                </div>
            </div>
            <hr class="border-secondary border-opacity-10 my-5 w-75 mx-auto">
            <p class="copyright text-white-50 small mb-0 tracking-widest">&copy; <b><?= date('Y') ?> MISACINEMA. ALL RIGHTS RESERVED.</b></p>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button type="button" class="btn btn-danger btn-floating btn-lg shadow-lg" id="btn-back-to-top" title="Back to Top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Custom JS (Java)
        console.log("MisaCinema Initialized");
    </script>

    <!-- SweetAlert2 (Loaded once) -> Already loaded in head, checking... -->
    <!-- In step 447 check: line 26 has it. line 170 has it. Removing line 170 one. -->

<script>
// Define Global Functions immediately
window.confirmDelete = function(e, formId, message) {
    e.preventDefault(); 
    
    Swal.fire({
        title: 'Are you sure?',
        text: message,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        background: '#000000',
        color: '#ffffff',
        customClass: { popup: 'my-system-dialog' }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById(formId);
            if (form) {
                console.log('Submitting form: ' + formId);
                form.submit();
            } else {
                console.error('Delete form not found: ' + formId);
                Swal.fire('Error', 'Could not find the deletion form. Please refresh and try again.', 'error');
            }
        }
    });
    
    return false;
};

document.addEventListener('DOMContentLoaded', function() {
    // 1. Force white text on specific elements
    const movieLinks = document.querySelectorAll('table td a, h2, h3, .movie-title');
    movieLinks.forEach(el => {
        el.style.setProperty('color', '#ffffff', 'important');
    });
});
document.addEventListener('DOMContentLoaded', function() {
    // 1. Cari elemen bar ralat yang degil itu
    const flashMessageError = document.querySelector('.message.error');
    const flashMessageSuccess = document.querySelector('.message.success');
    
    // Handle Errors
    if (flashMessageError) {
        const errorText = flashMessageError.innerText;
        flashMessageError.style.setProperty('display', 'none', 'important');
        
        Swal.fire({
            title: 'Error',
            text: errorText,
            icon: 'error',
            background: '#000000',
            color: '#ffffff',
            confirmButtonText: 'Close',
            confirmButtonColor: '#ff4d4d',
            width: '400px',
            allowOutsideClick: false,
            customClass: { popup: 'my-system-dialog' }
        });
    }

    // Handle Success (Specifically Welcome Back)
    if (flashMessageSuccess) {
        const successText = flashMessageSuccess.innerText;
        
        // Only popup for 'Welcome back' messages
        if (successText.includes('Welcome back')) {
            flashMessageSuccess.style.setProperty('display', 'none', 'important');

            Swal.fire({
                title: 'Welcome!',
                text: successText,
                icon: 'success',
                background: '#000000',
                color: '#ffffff',
                confirmButtonText: 'CLOSE',
                confirmButtonColor: '#dc3545', // Red theme
                width: '400px',
                timer: 3000,
                timerProgressBar: true,
                customClass: { popup: 'my-system-dialog' }
            });
        }
        
        // Popup for 'Welcome, Guest'
        if (successText.includes('Welcome, Guest')) {
            flashMessageSuccess.style.setProperty('display', 'none', 'important');

            Swal.fire({
                title: 'Guest Mode',
                text: successText,
                icon: 'info',
                background: '#000000',
                color: '#ffffff',
                confirmButtonText: 'CLOSE',
                confirmButtonColor: '#17a2b8', // Info theme
                width: '400px',
                timer: 4000,
                timerProgressBar: true,
                customClass: { popup: 'my-system-dialog' }
            });
        }
        
        // Popup for 'logged out'
        if (successText.includes('logged out')) {
            flashMessageSuccess.style.setProperty('display', 'none', 'important');

            Swal.fire({
                title: 'Goodbye!',
                text: 'You have been successfully logged out.',
                icon: 'info',
                background: '#000000',
                color: '#ffffff',
                confirmButtonText: 'CLOSE',
                confirmButtonColor: '#6c757d', // Grey theme
                width: '400px',
                timer: 3000,
                timerProgressBar: true,
                customClass: { popup: 'my-system-dialog' }
            });
        }

        // Popup for 'Add Show' (Scheduled)
        if (successText.includes('scheduled')) {
            flashMessageSuccess.style.setProperty('display', 'none', 'important');
            Swal.fire({
                title: 'Success!',
                text: successText,
                icon: 'success',
                background: '#000000',
                color: '#ffffff',
                confirmButtonText: 'CLOSE',
                confirmButtonColor: '#28a745', // Green
                width: '400px',
                customClass: { popup: 'my-system-dialog' }
            });
        }

        if (successText.includes('updated') || successText.includes('saved') || successText.includes('synchronized') || successText.includes('Payment successful') || successText.includes('reset link') || successText.includes('successfully') || successText.includes('Registration successful') || successText.includes('sent') || successText.includes('verification') || successText.includes('sent successfully')) {
            flashMessageSuccess.style.setProperty('display', 'none', 'important');
            
            let dialogTitle = 'Success!'; // Default

            if (successText.includes('Payment successful')) {
                dialogTitle = 'Payment Confirmed!';
            } else if (successText.includes('reset link')) {
                dialogTitle = 'Check Email!';
            } else if (successText.includes('Registration successful')) {
                dialogTitle = 'Welcome Aboard!';
            } else if (successText.includes('report has been sent')) {
                dialogTitle = 'Report Sent!'; // Clearer message for Contact Form
            } else if (successText.includes('verification') || successText.includes('sent')) {
                dialogTitle = 'Check Your Email';
            } else if (successText.includes('updated')) {
                dialogTitle = 'Update Complete';
            } else if (successText.includes('saved')) {
                dialogTitle = 'Saved Successfully';
            }

            Swal.fire({
                title: dialogTitle,
                text: successText,
                icon: 'success',
                background: '#000000',
                color: '#ffffff',
                confirmButtonText: 'CLOSE',
                confirmButtonColor: '#e50914', // Misa Cinema Red
                width: '450px',
                customClass: { 
                    popup: 'my-system-dialog border border-danger border-opacity-25'
                }
            });
        }

        // Popup for 'Delete Show' or 'Removed'
        if (successText.includes('deleted') || successText.includes('removed')) {
            flashMessageSuccess.style.setProperty('display', 'none', 'important');
            Swal.fire({
                title: 'Deleted!',
                text: successText,
                icon: 'success',
                background: '#000000',
                color: '#ffffff',
                confirmButtonText: 'CLOSE',
                confirmButtonColor: '#dc3545', // Red
                width: '400px',
                customClass: { popup: 'my-system-dialog' }
            });
        }
    }
    // Generic Delete Confirmation Function
    window.confirmDelete = function(e, formId, message) {
        e.preventDefault(); // Stop default form submit/link behavior
        
        Swal.fire({
            title: 'Are you sure?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Red for delete
            cancelButtonColor: '#3085d6', // Blue for cancel
            confirmButtonText: 'Yes, delete it!',
            background: '#000000', // Dark theme
            color: '#ffffff',
            customClass: { popup: 'my-system-dialog' }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById(formId);
                if (form) {
                    form.submit();
                } else {
                    console.error('Delete form not found: ' + formId);
                }
            }
        });
        
        return false;
    };
});
// --- Global Auto-Scale Logic ---
// --- Back to Top Logic ---
document.addEventListener("DOMContentLoaded", function () {
    const mybutton = document.getElementById("btn-back-to-top");
    if(mybutton) {
        window.onscroll = function () {
            if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        };
        mybutton.addEventListener("click", function() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    }
});

window.initAutoScaler = function(containerId, scalerId) {
    const container = document.getElementById(containerId);
    const scaler = document.getElementById(scalerId);
    
    function updateScale() {
        if (!container || !scaler) return;
        
        // Reset for measurement
        scaler.style.transform = 'none';
        scaler.style.width = 'max-content';
        
        const containerWidth = container.offsetWidth - 40; // Horizontal Padding
        const contentWidth = scaler.offsetWidth;
        
        if (contentWidth > containerWidth && containerWidth > 0) {
            const scale = containerWidth / contentWidth;
            scaler.style.transform = `scale(${scale})`;
            scaler.style.transformOrigin = 'top center';
            // Scale doesn't change parent height, so we adjust manually
            container.style.height = (scaler.offsetHeight * scale + 40) + 'px';
        } else {
            scaler.style.transform = 'none';
            scaler.style.width = '100%';
            container.style.height = 'auto';
        }
    }

    window.addEventListener('resize', updateScale);
    window.addEventListener('load', updateScale);
    // Initial run with a slight delay for dynamic layouts
    setTimeout(updateScale, 100);
    setTimeout(updateScale, 500); // Second pass for slower renders
};
</script>

<style>
    #btn-back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        display: none;
        z-index: 1000;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        padding: 10px;
        border: 2px solid #ffffff;
        transition: transform 0.3s ease;
    }
    #btn-back-to-top:hover {
        transform: scale(1.1);
        background-color: #ff0000;
    }
    /* Paksa bar ralat asal hilang sepenuhnya */
    .message.error {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
    }
    
    .my-system-dialog {
        border: 1px solid #000000 !important;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3) !important;
    }
</style>
</body>

</html>