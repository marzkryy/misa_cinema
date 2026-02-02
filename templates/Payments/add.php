<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payment $payment
 * @var \Cake\Collection\CollectionInterface|string[] $bookings
 * @var string $priceMap
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Payments'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="payments form content">
            <?= $this->Form->create($payment) ?>
            <fieldset>
                <legend><?= __('Add Payment') ?></legend>
                <?php
                   echo $this->Form->control('booking_id', ['options' => $bookings, 'id' => 'booking-id', 'empty' => 'Select Booking']);
                    echo $this->Form->control('payment_date_time');
                    echo $this->Form->control('payment_method');
                    echo $this->Form->control('total_price', ['id' => 'total-price', 'readonly' => true]);
                    echo $this->Form->control('status');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Terima priceMap dari PHP
    const priceMap = <?= $priceMap ?>; 
    
    // 2. Tangkap elemen (Pastikan ID 'booking-id' dan 'total-price' ada dalam Form)
    const bookingSelect = document.getElementById('booking-id');
    const totalPriceInput = document.getElementById('total-price');

    if (bookingSelect && totalPriceInput) {
        bookingSelect.addEventListener('change', function() {
            const selectedId = this.value;
            
            // 3. Cari harga dalam map menggunakan ID
            if (priceMap[selectedId]) {
                const harga = parseFloat(priceMap[selectedId]);
                totalPriceInput.value = harga.toFixed(2);
            } else {
                totalPriceInput.value = '0.00';
            }
        });
    } else {
        console.error("Elemen form tidak dijumpai. Sila semak ID pada input!");
    }
});
</script>