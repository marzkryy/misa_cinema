<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Seat $seat
 * @var string[]|\Cake\Collection\CollectionInterface $halls
 */
?>
<style>
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
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= __('Add New Seat') ?></h3>
        </div>
        <div class="col-md-6 d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
             <?= $this->Html->link('<i class="fas fa-arrow-left me-2"></i>' . __('Back to Seats Management'), ['action' => 'index', '?' => ['hall_id' => $this->request->getQuery('hall_id')]], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false]) ?>
        </div>
    </div>

    <div class="card shadow-lg bg-dark border border-3 border-danger rounded-4 overflow-hidden" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body p-5">
            <?= $this->Form->create($seat) ?>
            <div class="row g-4">
                <div class="col-md-12">
                     <label class="form-label text-white-50 small text-uppercase">Target Hall</label>
                    <?= $this->Form->control('hall_id', ['options' => $halls, 'class' => 'form-select bg-black text-white border-secondary', 'label' => false]) ?>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase">Seat Row</label>
                    <?= $this->Form->text('seat_row', ['class' => 'form-control bg-black text-white border-secondary', 'placeholder' => 'e.g. A, B, C', 'required' => true]) ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase">Seat Number(s)</label>
                    <?= $this->Form->text('seat_number', ['class' => 'form-control bg-black text-white border-secondary', 'placeholder' => 'e.g. 1, 2, 5 or 1-10', 'required' => true]) ?>
                    <div class="form-text text-white-50 opacity-50 small">Enter multiple numbers separated by commas or use a dash for a range.</div>
                </div>

                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase">Seat Type</label>
                    <?= $this->Form->select('seat_type', [
                        'Standard' => 'Standard',
                        'Premium' => 'Premium',
                        'Couple' => 'Couple (Double)',
                        'Bed' => 'Bed (Lux)'
                    ], ['class' => 'form-select bg-black text-white border-secondary', 'default' => 'Standard', 'id' => 'seat-type-select']) ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase">Seat Price (RM)</label>
                    <?= $this->Form->number('seat_price', ['class' => 'form-control bg-black text-white border-secondary', 'default' => 25.00, 'step' => 0.01, 'id' => 'seat-price-input']) ?>
                </div>

                 <div class="col-md-12">
                     <label class="form-label text-white-50 small text-uppercase">Initial Status</label>
                     <?= $this->Form->select('status', [1 => 'Active / Available', 0 => 'Inactive / Maintenance'], ['class' => 'form-select bg-black text-white border-secondary', 'default' => 1]) ?>
                </div>
            </div>
            
            <div class="mt-5 text-end">
                <?= $this->Form->button('<i class="fas fa-save me-2"></i>' . __('Save Seat'), ['class' => 'btn btn-danger rounded-pill px-5 py-3 fw-bold shadow-lg text-uppercase tracking-wide', 'escapeTitle' => false]) ?>
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
