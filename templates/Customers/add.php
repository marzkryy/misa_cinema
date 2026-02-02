<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Customer $customer
 */
$this->assign('title', 'Join MisaCinema Family');
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card bg-dark text-white shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-danger text-center py-4" style="border-radius: 15px 15px 0 0;">
                    <h2 class="mb-0 fw-bold">JOIN THE FAMILY</h2>
                    <p class="mb-0 text-light opacity-75">Create your account to start booking movies</p>
                </div>
                <div class="card-body p-5">
                    <?= $this->Form->create($customer) ?>

                    <div class="mb-4">
                        <label class="form-label text-white small text-uppercase fw-bold">
                            <i class="fas fa-user me-2 text-danger"></i>Full Name
                        </label>
                        <?= $this->Form->text('name', [
                            'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                            'placeholder' => 'Enter your full name',
                            'required' => true
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white small text-uppercase fw-bold">
                            <i class="fas fa-phone me-2 text-danger"></i>Phone Number
                        </label>
                        <div class="input-group gap-2">
                            <div class="bg-dark border-secondary rounded-pill px-2 d-flex align-items-center" style="min-width: 110px;">
                                <select class="form-select bg-transparent text-white border-0 shadow-none fw-bold" id="country-code" style="font-size: 1rem;">
                                    <option value="+60">🇲🇾 +60</option>
                                    <option value="+65">🇸🇬 +65</option>
                                    <option value="+62">🇮🇩 +62</option>
                                    <option value="+66">🇹🇭 +66</option>
                                    <option value="+84">🇻🇳 +84</option>
                                    <option value="+63">🇵🇭 +63</option>
                                    <option value="+81">🇯🇵 +81</option>
                                    <option value="+82">🇰🇷 +82</option>
                                    <option value="+86">🇨🇳 +86</option>
                                    <option value="+61">🇦🇺 +61</option>
                                    <option value="+44">🇬🇧 +44</option>
                                    <option value="+1">🇺🇸 +1</option>
                                </select>
                            </div>
                            <input type="number" id="local-phone" class="form-control bg-dark text-white border-secondary py-3 rounded-pill px-4" placeholder="123456789">
                        </div>
                        <!-- Hidden Input for Actual Submission -->
                        <?= $this->Form->hidden('phone', ['id' => 'full-phone']) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white small text-uppercase fw-bold">
                            <i class="fas fa-envelope me-2 text-danger"></i>Email Address
                        </label>
                        <?= $this->Form->control('email', [
                            'label' => false,
                            'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                            'placeholder' => 'name@example.com',
                            'required' => true
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white small text-uppercase fw-bold">
                            <i class="fas fa-lock me-2 text-danger"></i>Create a Password
                        </label>
                        <div class="input-group align-items-center gap-2">
                            <?= $this->Form->text('password', [
                                'type' => 'password',
                                'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                                'placeholder' => 'Min 6 chars',
                                'required' => true,
                                'id' => 'password-field'
                            ]) ?>
                            <button class="btn btn-outline-secondary border-secondary rounded-circle d-flex align-items-center justify-content-center btn-toggle-pass" style="width: 50px; height: 50px;" type="button" id="toggle-password">
                                <i class="fas fa-eye text-white-50 transition-all"></i>
                            </button>
                        </div>
                        <?= $this->Form->error('password') ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white small text-uppercase fw-bold">
                            <i class="fas fa-lock me-2 text-danger"></i>Confirm Password
                        </label>
                        <div class="input-group align-items-center gap-2">
                            <?= $this->Form->text('confirm_password', [
                                'type' => 'password',
                                'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                                'placeholder' => 'Re-enter password',
                                'required' => true,
                                'id' => 'confirm-password-field'
                            ]) ?>
                            <button class="btn btn-outline-secondary border-secondary rounded-circle d-flex align-items-center justify-content-center btn-toggle-pass" style="width: 50px; height: 50px;" type="button" id="toggle-confirm-password">
                                <i class="fas fa-eye text-white-50 transition-all"></i>
                            </button>
                        </div>
                        <?= $this->Form->error('confirm_password') ?>
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <?= $this->Form->button(__('REGISTER ACCOUNT'), ['class' => 'btn btn-danger btn-lg fw-bold py-3 shadow rounded-pill']) ?>
                    </div>
                    <?= $this->Form->end() ?>

                    <div class="text-center mt-4">
                        <p class="text-white-50 small">Already have an account? <a
                                href="<?= $this->Url->build(['action' => 'login']) ?>" class="text-danger fw-bold text-decoration-none">Login
                                Here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Remove spinners from number input */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<script>
    // Toggle for Password
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

    // Toggle for Confirm Password
    document.getElementById('toggle-confirm-password').addEventListener('click', function() {
        const passwordField = document.getElementById('confirm-password-field');
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

    // Handle Phone Number Combination
    const countryCodeSelect = document.getElementById('country-code');
    const localPhoneInput = document.getElementById('local-phone');
    const fullPhoneInput = document.getElementById('full-phone');

    function updatePhoneNumber() {
        const countryCode = countryCodeSelect.value;
        let localPhone = localPhoneInput.value.toString();
        
        // Active Validation: Remove leading zero immediately from visual input
        if (localPhone.startsWith('0')) {
            localPhone = localPhone.replace(/^0+/, ''); // Remove ALL leading zeros
            localPhoneInput.value = localPhone; // Update the visible field
        }

        if (localPhone) {
            fullPhoneInput.value = countryCode + localPhone;
        } else {
            fullPhoneInput.value = '';
        }
    }

    countryCodeSelect.addEventListener('change', updatePhoneNumber);
    localPhoneInput.addEventListener('input', updatePhoneNumber);
</script>