<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Ticket> $tickets
 */
?>
<div class="tickets index content">
    <?= $this->Html->link(__('New Ticket'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Tickets') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('seat_id') ?></th>
                    <th><?= $this->Paginator->sort('hall_id') ?></th>
                    <th><?= $this->Paginator->sort('booking_id') ?></th>
                    <th><?= $this->Paginator->sort('show_id') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?= $this->Number->format($ticket->id) ?></td>
                    <td><?= $ticket->has('seat') ? $this->Html->link($ticket->seat->seat_type, ['controller' => 'Seats', 'action' => 'view', $ticket->seat->id]) : '' ?></td>
                    <td><?= $ticket->has('hall') ? $this->Html->link($ticket->hall->hall_type, ['controller' => 'Halls', 'action' => 'view', $ticket->hall->id]) : '' ?></td>
                    <td><?= $ticket->has('booking') ? $this->Html->link($ticket->booking->ticket_price, ['controller' => 'Bookings', 'action' => 'view', $ticket->booking->id]) : '' ?></td>
                    <td><?= $ticket->has('show') ? $this->Html->link($ticket->show->show_title, ['controller' => 'Shows', 'action' => 'view', $ticket->show->id]) : '' ?></td>
                    <td><?= $this->Number->format($ticket->status) ?></td>
                    <td><?= h($ticket->created) ?></td>
                    <td><?= h($ticket->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $ticket->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $ticket->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $ticket->id], ['confirm' => __('Are you sure you want to delete # {0}?', $ticket->id)]) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>
