<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hall $hall
 */
?>
<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= __('Add New Hall') ?></h3>
        </div>
        <div class="col-md-6 d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
            <?= $this->Html->link('<i class="fas fa-arrow-left me-2"></i>' . __('Back to List'), ['action' => 'index'], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]) ?>
        </div>
    </div>

    <div class="card shadow-lg bg-dark border border-3 border-danger rounded-4 overflow-hidden" style="max-width: 800px; margin: 0 auto; transform: none !important; transition: none !important;">
        <div class="card-body p-5">
            <?= $this->Form->create($hall) ?>
            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">Hall Type / Name</label>
                    <?= $this->Form->control('hall_type', [
                        'class' => 'form-control bg-black text-white border-secondary border-opacity-50 py-2',
                        'label' => false,
                        'placeholder' => 'e.g. Hall 1 (IMAX)'
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase fw-bold">Status</label>
                    <?= $this->Form->select('status', [1 => 'Active', 0 => 'Inactive'], [
                        'class' => 'form-select bg-black text-white border-secondary border-opacity-50 py-2',
                        'default' => 1
                    ]) ?>
                </div>
            </div>
            
            <div class="mt-5 text-center">
                <?= $this->Form->button('<i class="fas fa-plus-circle me-2"></i>' . __('Create Hall'), [
                    'class' => 'btn btn-danger rounded-pill px-5 py-3 fw-bold shadow-lg w-100',
                    'escape' => false,
                    'escapeTitle' => false,
                    'style' => 'letter-spacing: 1px;'
                ]) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus {
        background-color: #000 !important;
        border-color: #dc3545 !important;
        box-shadow: 0 0 15px rgba(220, 53, 69, 0.2) !important;
        color: white !important;
    }
    .tracking-wider { letter-spacing: 2px; }
</style>
