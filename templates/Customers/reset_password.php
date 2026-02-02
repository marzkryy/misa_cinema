<?php
/**
 * @var \App\View\AppView $this
 * @var string $role
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
                            <h3 class="fw-bold" style="color: var(--primary-red);">NEW PASSWORD</h3>
                            <p class="text-white-50">Set a new password for your <?= h($role) ?> account.</p>
                        </div>
                        <?= $this->Form->create($user) ?>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label text-white small text-uppercase fw-bold">
                                <i class="fas fa-lock me-2 text-danger"></i>New Password
                            </label>
                            <div class="input-group align-items-center gap-2">
                                <?= $this->Form->control('password', [
                                    'label' => false,
                                    'type' => 'password',
                                    'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                                    'placeholder' => 'Enter new password',
                                    'required' => true,
                                    'id' => 'password-field',
                                    'templates' => [
                                        'inputContainer' => '{{content}}'
                                    ]
                                ]) ?>
                                <button class="btn btn-outline-secondary border-secondary rounded-circle d-flex align-items-center justify-content-center transition-all btn-toggle-pass" style="width: 50px; height: 50px;" type="button" onclick="togglePassword('password-field', this)">
                                    <i class="fas fa-eye text-white-50"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="confirm_password" class="form-label text-white small text-uppercase fw-bold">
                                <i class="fas fa-check-circle me-2 text-danger"></i>Confirm Password
                            </label>
                            <div class="input-group align-items-center gap-2">
                                <?= $this->Form->control('confirm_password', [
                                    'label' => false,
                                    'type' => 'password',
                                    'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                                    'placeholder' => 'Confirm new password',
                                    'required' => true,
                                    'id' => 'confirm-password-field',
                                    'templates' => [
                                        'inputContainer' => '{{content}}'
                                    ]
                                ]) ?>
                                <button class="btn btn-outline-secondary border-secondary rounded-circle d-flex align-items-center justify-content-center transition-all btn-toggle-pass" style="width: 50px; height: 50px;" type="button" onclick="togglePassword('confirm-password-field', this)">
                                    <i class="fas fa-eye text-white-50"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-grid gap-3 mt-5">
                            <?= $this->Form->button(__('UPDATE PASSWORD'), ['class' => 'btn btn-danger btn-lg fw-bold py-3 shadow rounded-pill']) ?>
                            <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="btn btn-outline-light btn-lg fw-bold py-3 shadow rounded-pill">
                                CANCEL
                            </a>
                        </div>
                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId, btn) {
        const field = document.getElementById(fieldId);
        const icon = btn.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Auto-Scale Logic
    function updateLoginScale() {
        const container = document.querySelector('.login-map-container');
        const scaler = document.getElementById('login-scaler');
        if (!container || !scaler) return;

        const containerWidth = container.offsetWidth;
        const targetWidth = 420; // Card width (400) + some padding
        
        let scale = Math.min(1, containerWidth / targetWidth);
        scaler.style.transform = `scale(${scale})`;
        
        const scaledHeight = scaler.offsetHeight * scale;
        container.style.height = `${scaledHeight + 40}px`;
    }

    window.addEventListener('resize', updateLoginScale);
    window.addEventListener('load', updateLoginScale);
    updateLoginScale();
</script>

<style>
    .transition-all {
        transition: all 0.2s ease-in-out !important;
    }
</style>
