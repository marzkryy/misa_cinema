<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Customer $customer
 */
$this->assign('title', 'Edit Profile - MisaCinema');
?>



<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card bg-dark text-white shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-danger text-center py-4" style="border-radius: 15px 15px 0 0;">
                    <h2 class="mb-0 fw-bold">EDIT YOUR PROFILE</h2>
                    <p class="mb-0 text-light opacity-75">Update your personal information below</p>
                </div>
                <div class="card-body p-5">
                    <?= $this->Form->create($customer) ?>

                    <div class="mb-4">
                        <label class="form-label text-white small text-uppercase fw-bold"><i class="fas fa-user me-2 text-danger"></i>Full Name</label>
                        <?= $this->Form->control('name', [
                            'label' => false,
                            'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                            'required' => true
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white small text-uppercase fw-bold"><i class="fas fa-phone me-2 text-danger"></i>Phone Number</label>
                        <?= $this->Form->control('phone', [
                            'label' => false,
                            'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                            'required' => true
                        ]) ?>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white small text-uppercase fw-bold"><i class="fas fa-envelope me-2 text-danger"></i>Email Address</label>
                        <div class="input-group gap-3">
                            <?= $this->Form->text('email', [
                                'class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4',
                                'readonly' => true,
                                'style' => 'background-color: rgba(0,0,0,0.3) !important; cursor: not-allowed;'
                            ]) ?>
                            <button type="submit" formaction="<?= $this->Url->build(['action' => 'requestEmailChange', $customer->id]) ?>" class="btn btn-danger px-4 fw-bold py-3 rounded-pill transition-all shadow-sm">
                                CHANGE EMAIL
                            </button>
                        </div>
                        <p class="text-white-50 small mt-2"><i class="fas fa-info-circle me-1"></i> Changing email requires a 2-step verification process.</p>
                    </div>

                    <div class="col-12">
                        <hr class="border-secondary opacity-25 my-4">
                        <p class="text-white-50 small text-uppercase fw-bold mb-3"><i class="fas fa-lock me-2 text-danger"></i>Change Password (Leave blank to keep current)</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label text-white-50 small text-uppercase fw-bold"><?= __('Current Password') ?></label>
                            <div class="input-group gap-2">
                                <?= $this->Form->password('current_password', ['class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4', 'id' => 'current-password', 'value' => '', 'placeholder' => '••••••••']) ?>
                                <button class="btn btn-outline-secondary border-secondary px-3 py-3 toggle-btn rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;" type="button" onclick="togglePassword('current-password', 'curr-icon')">
                                    <i class="fas fa-eye text-white-50 transition-all" id="curr-icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label text-white-50 small text-uppercase fw-bold"><?= __('New Password') ?></label>
                            <div class="input-group gap-2">
                                <?= $this->Form->password('new_password', ['class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4', 'id' => 'new-password', 'value' => '', 'placeholder' => '••••••••']) ?>
                                <button class="btn btn-outline-secondary border-secondary px-3 py-3 toggle-btn rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;" type="button" onclick="togglePassword('new-password', 'new-icon')">
                                    <i class="fas fa-eye text-white-50 transition-all" id="new-icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4 mb-3">
                            <label class="form-label text-white-50 small text-uppercase fw-bold"><?= __('Confirm New Password') ?></label>
                            <div class="input-group gap-2">
                                <?= $this->Form->password('confirm_new_password', ['class' => 'form-control bg-dark text-white border-secondary py-3 rounded-pill px-4', 'id' => 'confirm-new-password', 'value' => '', 'placeholder' => '••••••••']) ?>
                                <button class="btn btn-outline-secondary border-secondary px-3 py-3 toggle-btn rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;" type="button" onclick="togglePassword('confirm-new-password', 'conf-icon')">
                                    <i class="fas fa-eye text-white-50 transition-all" id="conf-icon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-3 mt-5">
                        <?= $this->Form->button(__('UPDATE PROFILE'), ['class' => 'btn btn-danger btn-lg fw-bold py-3 shadow rounded-pill']) ?>
                        <a href="<?= $this->Url->build(['action' => 'view', $customer->id]) ?>"
                            class="btn btn-outline-secondary btn-lg fw-bold py-3 rounded-pill text-decoration-none">CANCEL AND RETURN</a>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all {
        transition: all 0.2s ease-in-out !important;
    }
    /* Ensure icon inside password toggle turns black on hover (since bg turns white) */
    .toggle-btn:hover i {
        color: #000000 !important;
    }
    /* Ensure CHANGE EMAIL icon/text remains red on hover if any global styles conflict */
    .btn-danger:hover {
        color: #e50914 !important;
    }
</style>

<script>
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (field.type === "password") {
        field.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        field.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>