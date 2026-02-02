<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('body_class', 'login-page');
?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <!-- Auto-Scale Container -->
        <div class="login-map-container d-flex justify-content-center align-items-start pt-5" style="min-height: 80vh; overflow: hidden;">
            <div id="login-scaler" class="login-map-scaler" style="transform-origin: top center; transition: transform 0.2s ease-out;">
                <div class="card shadow-lg" style="background-color: #1f1f1f; color: #fff; border: 1px solid #333; width: 400px; border-radius: 20px;">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold" style="color: var(--primary-red);">MISA CINEMA</h3>
                            <p class="text-white-50">Welcome back! Please login.</p>
                        </div>
                        <?= $this->Form->create(null) ?>
                        
                        <!-- Role Switcher -->
                        <div class="mb-5 text-center">
                            <div class="d-flex w-100 p-1 bg-black bg-opacity-50 rounded-pill role-toggle-container" aria-label="Login Role">
                                <div class="w-50">
                                    <input type="radio" class="btn-check" name="role" id="role-customer" value="customer" checked autocomplete="off">
                                    <label class="btn role-btn rounded-pill fw-bold border-0 py-2 d-flex align-items-center justify-content-center w-100" for="role-customer">
                                        <i class="fas fa-user me-2"></i>CUSTOMER
                                    </label>
                                </div>

                                <div class="w-50">
                                    <input type="radio" class="btn-check" name="role" id="role-admin" value="admin" autocomplete="off">
                                    <label class="btn role-btn rounded-pill fw-bold border-0 py-2 d-flex align-items-center justify-content-center w-100" for="role-admin">
                                        <i class="fas fa-user-shield me-2"></i>ADMIN
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label text-white small text-uppercase fw-bold">
                                <i class="fas fa-envelope me-2 text-danger"></i>Email Address
                            </label>
                            <?= $this->Form->control('email', [
                                'label' => false,
                                'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                                'placeholder' => 'Enter your email',
                                'required' => true
                            ]) ?>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label text-white small text-uppercase fw-bold">
                                <i class="fas fa-lock me-2 text-danger"></i>Password
                            </label>
                            <div class="input-group align-items-center gap-2">
                                <?= $this->Form->text('password', [
                                    'type' => 'password',
                                    'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                                    'placeholder' => 'Enter your password',
                                    'required' => true,
                                    'id' => 'password-field'
                                ]) ?>
                                <button class="btn btn-outline-secondary border-secondary rounded-circle d-flex align-items-center justify-content-center transition-all btn-toggle-pass" style="width: 50px; height: 50px;" type="button" id="toggle-password">
                                    <i class="fas fa-eye text-white-50"></i>
                                </button>
                            </div>
                            <div class="text-end mt-2 px-3">
                                <a href="<?= $this->Url->build(['action' => 'forgotPassword']) ?>" class="text-white-50 small text-decoration-none hover-red fw-bold">
                                    Forgot Password?
                                </a>
                            </div>
                        </div>
                        <div class="d-grid gap-3 mt-5">
                            <?= $this->Form->button(__('LOGIN TO ACCOUNT'), ['class' => 'btn btn-danger btn-lg fw-bold py-3 shadow rounded-pill']) ?>
                            <a href="<?= $this->Url->build(['action' => 'guest']) ?>" class="btn btn-outline-light btn-lg fw-bold py-3 shadow rounded-pill">
                                CONTINUE AS GUEST
                            </a>
                        </div>
                        <?= $this->Form->end() ?>
                        <div class="text-center mt-4">
                            <p class="text-white-50 small">New to MisaCinema? <a
                                    href="<?= $this->Url->build(['action' => 'add']) ?>" class="text-danger fw-bold text-decoration-none">Register
                                    here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('toggle-password').addEventListener('click', function() {
        const passwordField = document.getElementById('password-field');
        const icon = this.querySelector('i');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Auto-Scale Logic
    function updateLoginScale() {
        const container = document.querySelector('.login-map-container');
        const scaler = document.getElementById('login-scaler');
        if (!container || !scaler) return;

        const containerWidth = container.offsetWidth;
        const targetWidth = 420; // Card width (400) + some padding
        
        // Calculate scale factor, but don't upscale beyond 1
        let scale = Math.min(1, containerWidth / targetWidth);
        
        // Apply scale
        scaler.style.transform = `scale(${scale})`;
        
        // Handle vertical gap (Scaling doesn't change element flow size)
        const scaledHeight = scaler.offsetHeight * scale;
        container.style.height = `${scaledHeight + 40}px`;
    }

    // Initialize and Listen
    window.addEventListener('resize', updateLoginScale);
    window.addEventListener('load', updateLoginScale);
    updateLoginScale();
</script>

<style>
    .transition-all {
        transition: all 0.2s ease-in-out !important;
    }
    
    /* Role Switcher Refinement: Ngam-ngam inside the track */
    .role-toggle-container {
        border: 1px solid #333;
    }
    
    .role-btn {
        color: rgba(255, 255, 255, 0.5) !important;
        transition: all 0.2s ease-in-out !important;
        background: transparent !important;
    }
    
    .role-btn:hover {
        color: #fff !important;
    }
    
    .btn-check:checked + .role-btn {
        background-color: var(--primary-red) !important;
        color: #ffffff !important;
        box-shadow: 0 2px 10px rgba(229, 9, 20, 0.3) !important;
    }
    
    .hover-red:hover {
        color: var(--primary-red) !important;
    }
</style>