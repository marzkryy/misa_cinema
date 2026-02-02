<?php
/**
 * @var \App\View\AppView $this
 * @var string[]|\Cake\Collection\CollectionInterface $halls
 * @var int|null $defaultHallId
 */
?>
<style>
    @media (max-width: 576px) {
        .card-body { padding: 2rem !important; }
        .generate-card-header { padding: 1.5rem !important; }
        .form-label { font-size: 0.7rem; }
    }

    /* Hide arrows/spinners on number inputs */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<div class="container mt-5">
    <div class="card shadow-lg bg-dark border-0 rounded-4 overflow-hidden" style="max-width: 800px; margin: 0 auto;">
        <div class="card-header generate-card-header bg-black bg-opacity-50 border-bottom border-secondary border-opacity-25 p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="text-white fw-bold text-uppercase tracking-wider mb-0">Generate Seats</h4>
                <p class="text-white-50 mb-0 small mt-1">Auto-generate seat layout for a hall</p>
            </div>
            <?= $this->Html->link('<i class="fas fa-times"></i>', ['action' => 'index'], ['class' => 'btn btn-outline-secondary rounded-circle btn-sm d-flex align-items-center justify-content-center', 'style' => 'width: 32px; height: 32px;', 'escape' => false, 'title' => 'Close']) ?>
        </div>
        <div class="card-body p-5">
            <?= $this->Form->create(null, ['url' => ['action' => 'generate']]) ?>
            
            <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning mb-4 rounded-3 d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-3 fa-2x"></i>
                <div>
                    <strong>Warning:</strong> Generating seats will overwrite the current layout if "Clear Existing" is selected. Be careful!
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-12">
                     <label class="form-label text-white-50 small text-uppercase">Target Hall</label>
                    <?= $this->Form->control('hall_id', ['options' => $halls, 'default' => $defaultHallId, 'class' => 'form-select bg-black text-white border-secondary', 'label' => false]) ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase">Number of Rows</label>
                    <?= $this->Form->number('total_rows', ['class' => 'form-control bg-black text-white border-secondary', 'default' => 8, 'min' => 1, 'max' => 26]) ?>
                    <div class="form-text text-white-50 opacity-50 small">Rows will be labeled A, B, C...</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase">Seats Per Row</label>
                    <?= $this->Form->number('seats_per_row', ['class' => 'form-control bg-black text-white border-secondary', 'default' => 10, 'min' => 1, 'max' => 50]) ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase">Default Seat Type</label>
                    <?= $this->Form->select('seat_type', [
                        'Standard' => 'Standard',
                        'Premium' => 'Premium',
                        'Couple' => 'Couple (Double)',
                        'Bed' => 'Bed (Lux)'
                    ], ['class' => 'form-select bg-black text-white border-secondary', 'default' => 'Standard', 'id' => 'seat-type-select']) ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase">Default Price (RM)</label>
                    <?= $this->Form->number('seat_price', ['class' => 'form-control bg-black text-white border-secondary', 'default' => 25.00, 'step' => 0.01, 'id' => 'seat-price-input']) ?>
                </div>

                 <div class="col-12">
                     <label for="clear-check" class="d-block transition-all" style="cursor: pointer;">
                        <div class="p-4 rounded-3 border border-secondary border-opacity-25 bg-black bg-opacity-25 d-flex align-items-center gap-3">
                            <div class="form-check mb-0">
                                <?= $this->Form->checkbox('clear_existing', ['id' => 'clear-check', 'class' => 'form-check-input', 'checked' => true, 'style' => 'width: 1.25rem; height: 1.25rem; margin-top: 0;']) ?>
                            </div>
                            <div>
                                <span class="text-white fw-bold d-block">Clear existing seats?</span>
                                <small class="text-white-50 d-block mt-1">Check to remove all seats in this hall before generation. Uncheck to append new seats.</small>
                            </div>
                        </div>
                     </label>
                </div>
            </div>
            
            <div class="mt-5 text-end">
                <?= $this->Form->button('<i class="fas fa-magic me-2"></i>' . __('Generate Layout'), ['class' => 'btn btn-danger rounded-pill px-5 py-3 fw-bold shadow-lg text-uppercase tracking-wide', 'escapeTitle' => false]) ?>
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('seat-type-select');
    const priceInput = document.getElementById('seat-price-input');
    
    const prices = {
        'Standard': 25.00,
        'Premium': 40.00,
        'Couple': 70.00,
        'Bed': 180.00
    };

    typeSelect.addEventListener('change', function() {
        if (prices[this.value]) {
            priceInput.value = prices[this.value].toFixed(2);
        }
    });
});
</script>
