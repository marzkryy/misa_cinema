<style>
    .btn-password-toggle {
        transition: all 0.3s ease;
        border-color: rgba(255,255,255,0.1) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px;
    }
    .btn-password-toggle:hover {
        background-color: #ffffff !important;
        border-color: #ffffff !important;
    }
    .btn-password-toggle:hover i {
        color: #000000 !important;
    }
    .btn-password-toggle i {
        transition: all 0.3s ease;
    }
</style>

<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= __('Edit Staff') ?></h3>
        </div>
        <div class="col-md-6 d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
             <?= $this->Html->link('<i class="fas fa-arrow-left me-2"></i>' . __('Back to List'), ['action' => 'index'], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]) ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-trash me-2"></i>' . __('Delete'),
                ['action' => 'delete', $staff->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $staff->id), 'class' => 'btn btn-outline-danger rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]
            ) ?>
        </div>
    </div>

    <div class="card shadow-lg bg-dark border border-3 border-danger rounded-4 overflow-hidden" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body p-5">
            <?= $this->Form->create($staff) ?>
            <div class="row g-4">
                <div class="col-md-6">
                    <?= $this->Form->control('name', ['class' => 'form-control bg-black text-white border-secondary', 'label' => ['class' => 'form-label text-white small text-uppercase fw-bold', 'text' => '<i class="fas fa-user me-2 text-danger"></i>' . __('Name'), 'escape' => false]]) ?>
                </div>
                <div class="col-md-6">
                    <?= $this->Form->control('phone', ['class' => 'form-control bg-black text-white border-secondary', 'label' => ['class' => 'form-label text-white small text-uppercase fw-bold', 'text' => '<i class="fas fa-phone me-2 text-danger"></i>' . __('Phone'), 'escape' => false]]) ?>
                </div>
                <div class="col-12">
                    <?= $this->Form->control('email', ['class' => 'form-control bg-black text-white border-secondary', 'label' => ['class' => 'form-label text-white small text-uppercase fw-bold', 'text' => '<i class="fas fa-envelope me-2 text-danger"></i>' . __('Email'), 'escape' => false]]) ?>
                    <?php if ($staff->temp_email): ?>
                        <p class="text-warning small mt-2"><i class="fas fa-hourglass-half me-1"></i> Pending verification for: <strong><?= h($staff->temp_email) ?></strong></p>
                    <?php endif; ?>
                    <p class="text-white-50 small mt-1"><i class="fas fa-info-circle me-1"></i> Changing email will send a verification link to the new address.</p>
                </div>
                <div class="col-12">
                    <hr class="border-secondary opacity-25 my-2">
                    <p class="text-white-50 small text-uppercase fw-bold mb-3"><i class="fas fa-lock me-2"></i>Change Password (Leave blank to keep current)</p>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-white-50 small text-uppercase"><?= __('Current Password') ?></label>
                    <div class="d-flex gap-2">
                        <?= $this->Form->password('current_password', ['class' => 'form-control bg-black text-white border-secondary rounded-pill ps-4', 'id' => 'current-password', 'value' => '']) ?>
                        <button class="btn btn-outline-secondary btn-password-toggle rounded-pill" type="button" onclick="togglePassword('current-password', 'curr-icon')">
                            <i class="fas fa-eye text-white-50" id="curr-icon"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-white-50 small text-uppercase"><?= __('New Password') ?></label>
                    <div class="d-flex gap-2">
                        <?= $this->Form->password('new_password', ['class' => 'form-control bg-black text-white border-secondary rounded-pill ps-4', 'id' => 'new-password', 'value' => '']) ?>
                        <button class="btn btn-outline-secondary btn-password-toggle rounded-pill" type="button" onclick="togglePassword('new-password', 'new-icon')">
                            <i class="fas fa-eye text-white-50" id="new-icon"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label text-white-50 small text-uppercase"><?= __('Confirm New Password') ?></label>
                    <div class="d-flex gap-2">
                        <?= $this->Form->password('confirm_new_password', ['class' => 'form-control bg-black text-white border-secondary rounded-pill ps-4', 'id' => 'confirm-new-password', 'value' => '']) ?>
                        <button class="btn btn-outline-secondary btn-password-toggle rounded-pill" type="button" onclick="togglePassword('confirm-new-password', 'conf-icon')">
                            <i class="fas fa-eye text-white-50" id="conf-icon"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12">
                    <hr class="border-secondary opacity-25 my-2">
                </div>
                <div class="col-md-6">
                     <label class="form-label text-white-50 small text-uppercase">Role</label>
                     <?= $this->Form->select('role', ['admin' => 'Admin', 'staff' => 'Staff'], ['class' => 'form-select bg-black text-white border-secondary']) ?>
                </div>
                <div class="col-md-6">
                     <label class="form-label text-white-50 small text-uppercase">Status</label>
                     <?= $this->Form->select('status', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-select bg-black text-white border-secondary']) ?>
                </div>
            </div>
            
            <div class="mt-5 text-end">
                <?= $this->Form->button('<i class="fas fa-save me-2"></i>' . __('Update Staff'), ['class' => 'btn btn-danger rounded-pill px-5 py-2 fw-bold shadow-lg', 'escape' => false, 'escapeTitle' => false]) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

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
