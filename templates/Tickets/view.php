<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Ticket $ticket
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Ticket'), ['action' => 'edit', $ticket->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Ticket'), ['action' => 'delete', $ticket->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ticket->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Tickets'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Ticket'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="tickets view content">
             <h3><?= __('Ticket Details for ') . h($ticket->booking->customer->name) ?></h3>
            <table>
                <tr>
                    <th><?= __('Seat') ?></th>
                    <td><?= $ticket->has('seat') ? $this->Html->link($ticket->seat->seat_type, ['controller' => 'Seats', 'action' => 'view', $ticket->seat->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Hall') ?></th>
                    <td><?= $ticket->has('hall') ? $this->Html->link($ticket->hall->hall_type, ['controller' => 'Halls', 'action' => 'view', $ticket->hall->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Booking') ?></th>
                    <td><?= $ticket->has('booking') ? $this->Html->link($ticket->booking->ticket_price, ['controller' => 'Bookings', 'action' => 'view', $ticket->booking->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Show') ?></th>
                    <td><?= $ticket->has('show') ? $this->Html->link($ticket->show->show_title, ['controller' => 'Shows', 'action' => 'view', $ticket->show->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($ticket->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $this->Number->format($ticket->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($ticket->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($ticket->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
