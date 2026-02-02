<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Staff $staff
 */
?>
<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= __('Add Staff') ?></h3>
        </div>
        <div class="col-md-6 d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
            <?= $this->Html->link('<i class="fas fa-arrow-left me-2"></i>' . __('Back to List'), ['action' => 'index'], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]) ?>
        </div>
    </div>

    <div class="card shadow-lg bg-dark border border-3 border-danger rounded-4 overflow-hidden" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body p-5">
            <?= $this->Form->create($staff) ?>
            <div class="row g-4">
                <div class="col-md-6">
                    <?= $this->Form->control('name', ['class' => 'form-control bg-black text-white border-secondary', 'placeholder' => 'e.g. Ali Bin Abu', 'label' => ['class' => 'form-label text-white-50 small text-uppercase']]) ?>
                </div>
                <div class="col-md-6">
                    <?= $this->Form->control('phone', ['class' => 'form-control bg-black text-white border-secondary', 'placeholder' => 'e.g. 012-3456789', 'label' => ['class' => 'form-label text-white-50 small text-uppercase']]) ?>
                </div>
                <div class="col-12">
                    <?= $this->Form->control('email', ['class' => 'form-control bg-black text-white border-secondary', 'placeholder' => 'e.g. staff@misacinema.com', 'label' => ['class' => 'form-label text-white-50 small text-uppercase']]) ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase"><?= __('Password') ?></label>
                    <div class="input-group align-items-center gap-2">
                        <?= $this->Form->password('password', ['class' => 'form-control bg-black text-white border-secondary rounded-pill px-4 py-3', 'id' => 'password-field', 'required' => true, 'placeholder' => 'Enter secure password']) ?>
                        <button class="btn btn-outline-secondary border-secondary rounded-circle d-flex align-items-center justify-content-center btn-toggle-pass" style="width: 50px; height: 50px;" type="button" onclick="togglePassword('password-field', this)">
                            <i class="fas fa-eye text-white-50"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase"><?= __('Confirm Password') ?></label>
                    <div class="input-group align-items-center gap-2">
                        <?= $this->Form->password('confirm_password', ['class' => 'form-control bg-black text-white border-secondary rounded-pill px-4 py-3', 'id' => 'confirm-password-field', 'required' => true, 'placeholder' => 'Re-enter password']) ?>
                        <button class="btn btn-outline-secondary border-secondary rounded-circle d-flex align-items-center justify-content-center btn-toggle-pass" style="width: 50px; height: 50px;" type="button" onclick="togglePassword('confirm-password-field', this)">
                            <i class="fas fa-eye text-white-50"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6">
                     <label class="form-label text-white-50 small text-uppercase">Role</label>
                     <?= $this->Form->select('role', ['admin' => 'Admin', 'staff' => 'Staff'], ['class' => 'form-select bg-black text-white border-secondary', 'default' => 'admin']) ?>
                </div>
                <div class="col-md-6">
                     <label class="form-label text-white-50 small text-uppercase">Status</label>
                     <?= $this->Form->select('status', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-select bg-black text-white border-secondary', 'default' => 1]) ?>
                </div>
            </div>
            
            <div class="mt-5 text-end">
                <?= $this->Form->button('<i class="fas fa-save me-2"></i>' . __('Save Staff'), ['class' => 'btn btn-danger rounded-pill px-5 py-2 fw-bold shadow-lg', 'escape' => false, 'escapeTitle' => false]) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
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
