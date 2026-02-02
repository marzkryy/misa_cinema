<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Show $show
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Show'), ['action' => 'edit', $show->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Show'), ['action' => 'delete', $show->id], ['confirm' => __('Are you sure you want to delete # {0}?', $show->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Shows'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Show'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="shows view content">
            <h3><?= h($show->show_title) ?></h3>
            <table>
                <tr>
                    <th><?= __('Show Title') ?></th>
                    <td><?= h($show->show_title) ?></td>
                </tr>
                <tr>
                    <th><?= __('Avatar') ?></th>
                    <td><?= h($show->avatar) ?></td>
                </tr>
                <tr>
                    <th><?= __('Avatar Dir') ?></th>
                    <td><?= h($show->avatar_dir) ?></td>
                </tr>
                <tr>
                    <th><?= __('Genre') ?></th>
                    <td><?= h($show->genre) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($show->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Status') ?></th>
                    <td><?= $this->Number->format($show->status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Show Time') ?></th>
                    <td><?= h($show->show_time) ?></td>
                </tr>
                <tr>
                    <th><?= __('Show Date') ?></th>
                    <td><?= h($show->show_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($show->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($show->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Bookings') ?></h4>
                <?php if (!empty($show->bookings)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Cust Id') ?></th>
                            <th><?= __('Show Id') ?></th>
                            <th><?= __('Book Date Time') ?></th>
                            <th><?= __('Quantity') ?></th>
                            <th><?= __('Ticket Price') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($show->bookings as $bookings) : ?>
                        <tr>
                            <td><?= h($bookings->id) ?></td>
                            <td><?= h($bookings->cust_id) ?></td>
                            <td><?= h($bookings->show_id) ?></td>
                            <td><?= h($bookings->book_date_time) ?></td>
                            <td><?= h($bookings->quantity) ?></td>
                            <td><?= h($bookings->ticket_price) ?></td>
                            <td><?= h($bookings->status) ?></td>
                            <td><?= h($bookings->created) ?></td>
                            <td><?= h($bookings->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Bookings', 'action' => 'view', $bookings->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Bookings', 'action' => 'edit', $bookings->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Bookings', 'action' => 'delete', $bookings->id], ['confirm' => __('Are you sure you want to delete # {0}?', $bookings->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Tickets') ?></h4>
                <?php if (!empty($show->tickets)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Id') ?></th>
                            <th><?= __('Seat Id') ?></th>
                            <th><?= __('Hall Id') ?></th>
                            <th><?= __('Booking Id') ?></th>
                            <th><?= __('Show Id') ?></th>
                            <th><?= __('Status') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($show->tickets as $tickets) : ?>
                        <tr>
                            <td><?= h($tickets->id) ?></td>
                            <td><?= h($tickets->seat_id) ?></td>
                            <td><?= h($tickets->hall_id) ?></td>
                            <td><?= h($tickets->booking_id) ?></td>
                            <td><?= h($tickets->show_id) ?></td>
                            <td><?= h($tickets->status) ?></td>
                            <td><?= h($tickets->created) ?></td>
                            <td><?= h($tickets->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Tickets', 'action' => 'view', $tickets->id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Tickets', 'action' => 'edit', $tickets->id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Tickets', 'action' => 'delete', $tickets->id], ['confirm' => __('Are you sure you want to delete # {0}?', $tickets->id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
