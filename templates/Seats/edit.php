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
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= __('Edit Seat') ?></h3>
        </div>
        <div class="col-md-6 d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
             <?= $this->Html->link('<i class="fas fa-arrow-left me-2"></i>' . __('Back to Seats Management'), ['action' => 'index', '?' => ['hall_id' => $this->request->getQuery('hall_id')]], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]) ?>
            <?= $this->Form->postLink(
                '<i class="fas fa-trash me-2"></i>' . __('Delete'),
                ['action' => 'delete', $seat->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $seat->id), 'class' => 'btn btn-outline-danger rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]
            ) ?>
        </div>
    </div>

    <div class="card shadow-lg bg-dark border border-3 border-danger rounded-4 overflow-hidden" style="max-width: 800px; margin: 0 auto; transform: none !important; transition: none !important;">
        <div class="card-body p-5">
            <?= $this->Form->create($seat) ?>
            <div class="row g-4">
                <div class="col-md-6">
                    <?= $this->Form->control('hall_id', ['options' => $halls, 'class' => 'form-select bg-black text-white border-secondary', 'label' => ['class' => 'form-label text-white-50 small text-uppercase']]) ?>
                </div>
                <div class="col-md-6">
                    <?= $this->Form->control('seat_row', ['class' => 'form-control bg-black text-white border-secondary', 'label' => ['class' => 'form-label text-white-50 small text-uppercase']]) ?>
                </div>
                <div class="col-md-6">
                    <?= $this->Form->control('seat_number', ['type' => 'number', 'class' => 'form-control bg-black text-white border-secondary', 'label' => ['class' => 'form-label text-white-50 small text-uppercase']]) ?>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white-50 small text-uppercase">Seat Type</label>
                    <?= $this->Form->select('seat_type', [
                        'Standard' => 'Standard',
                        'Premium' => 'Premium',
                        'Couple' => 'Couple (Double)',
                        'Bed' => 'Bed (Lux)'
                    ], ['class' => 'form-select bg-black text-white border-secondary', 'default' => $seat->seat_type, 'id' => 'seat-type-select']) ?>
                </div>
                <div class="col-md-6">
                    <?= $this->Form->control('seat_price', ['type' => 'number', 'step' => '0.01', 'id' => 'seat-price-input', 'class' => 'form-control bg-black text-white border-secondary', 'label' => ['class' => 'form-label text-white-50 small text-uppercase']]) ?>
                </div>
                  <div class="col-md-12 mt-3">
                      <div class="p-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3">
                          <label class="form-label text-white fw-bold mb-3 tracking-wide">
                              <i class="fas fa-sync-alt me-2 text-warning"></i>PRICE SYNCHRONIZATION
                          </label>
                          <?= $this->Form->select('sync_scope', [
                              'none' => 'Update this seat only',
                              'hall' => 'Apply to all '.strtoupper($seat->seat_type).' seats in CURRENT hall only',
                              'global' => 'Apply to all '.strtoupper($seat->seat_type).' seats in ALL HALLS (Global Update)'
                          ], [
                              'class' => 'form-select bg-black text-white border-warning border-opacity-50 fw-bold',
                              'default' => 'none'
                          ]) ?>
                          <div class="small text-white-50 mt-2">
                              <i class="fas fa-info-circle me-1"></i> Global update will synchronize prices for all similar seats across the entire cinema system.
                          </div>
                      </div>
                  </div>
                 <div class="col-md-12">
                     <label class="form-label text-white-50 small text-uppercase">Status</label>
                     <?= $this->Form->select('status', [1 => 'Active / Available', 0 => 'Inactive / Maintenance'], ['class' => 'form-select bg-black text-white border-secondary', 'value' => $seat->status]) ?>
                </div>
            </div>
            
            <div class="mt-5 text-end">
                <?= $this->Form->button('<i class="fas fa-save me-2"></i>' . __('Update Seat'), ['class' => 'btn btn-danger rounded-pill px-5 py-2 fw-bold shadow-lg', 'escape' => false, 'escapeTitle' => false]) ?>
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
