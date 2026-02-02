<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ticket $ticket
 * @var string[]|\Cake\Collection\CollectionInterface $seats
 * @var string[]|\Cake\Collection\CollectionInterface $halls
 * @var string[]|\Cake\Collection\CollectionInterface $bookings
 * @var string[]|\Cake\Collection\CollectionInterface $shows
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $ticket->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $ticket->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Tickets'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tickets form content">
            <?= $this->Form->create($ticket) ?>
            <fieldset>
                <legend><?= __('Edit Ticket') ?></legend>
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
