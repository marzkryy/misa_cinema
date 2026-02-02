<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hall $hall
 */
?>
<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= __('Edit Hall') ?></h3>
        </div>
        <div class="col-md-6 d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
             <?= $this->Html->link('<i class="fas fa-arrow-left me-2"></i>' . __('Back to List'), ['action' => 'index'], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]) ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-trash me-2"></i>' . __('Delete'),
                ['action' => 'delete', $hall->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $hall->id), 'class' => 'btn btn-outline-danger rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]
            ) ?>
        </div>
    </div>

    <div class="card shadow-lg bg-dark border border-3 border-danger rounded-4 overflow-hidden" style="max-width: 800px; margin: 0 auto; transform: none !important; transition: none !important;">
        <div class="card-body p-5">
            <?= $this->Form->create($hall) ?>
            <div class="row g-4">
                <div class="col-md-6">
                    <?= $this->Form->control('hall_type', ['class' => 'form-control bg-black text-white border-secondary', 'label' => ['class' => 'form-label text-white-50 small text-uppercase']]) ?>
                </div>
                <!-- Status is integer in DB (0/1), usually handled as checkbox or select. Using text input or select as per Staffs example -->
                 <div class="col-md-6">
                     <label class="form-label text-white-50 small text-uppercase">Status</label>
                     <?= $this->Form->select('status', [1 => 'Active', 0 => 'Inactive'], ['class' => 'form-select bg-black text-white border-secondary', 'value' => $hall->status]) ?>
                </div>
            </div>
            
            <div class="mt-5 text-end">
                <?= $this->Form->button('<i class="fas fa-save me-2"></i>' . __('Update Hall'), ['class' => 'btn btn-danger rounded-pill px-5 py-2 fw-bold shadow-lg', 'escape' => false, 'escapeTitle' => false]) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
