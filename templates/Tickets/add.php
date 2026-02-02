<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ticket $ticket
 * @var \Cake\Collection\CollectionInterface|string[] $seats
 * @var \Cake\Collection\CollectionInterface|string[] $halls
 * @var \Cake\Collection\CollectionInterface|string[] $bookings
 * @var \Cake\Collection\CollectionInterface|string[] $shows
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Tickets'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tickets form content">
            <?= $this->Form->create($ticket) ?>
            <fieldset>
                <legend><?= __('Add Ticket') ?></legend>
                <?php
                    echo $this->Form->control('seat_id', ['options' => $seats]);
                    echo $this->Form->control('hall_id', ['options' => $halls]);
                    echo $this->Form->control('booking_id', ['options' => $bookings]);
                    echo $this->Form->control('show_id', ['options' => $shows]);
                    echo $this->Form->control('status');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
