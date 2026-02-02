<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Hall> $halls
 */
?>
<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-6">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= __('Halls Management') ?></h3>
        </div>
        <div class="col-6 text-end">
            <?= $this->Html->link(__('<i class="fas fa-plus me-2"></i>New Hall'), ['action' => 'add'], ['class' => 'btn btn-danger rounded-pill px-4 shadow-sm', 'escape' => false]) ?>
        </div>
    </div>

    <div class="card shadow-lg bg-black border-0 rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead class="bg-danger text-white text-uppercase small fw-bold">
                            <tr>
                                <th class="ps-4 py-3"><?= $this->Paginator->sort('hall_type') ?></th>
                                <th class="py-3"><?= $this->Paginator->sort('status') ?></th>
                                <th class="py-3"><?= $this->Paginator->sort('created') ?></th>
                                <th class="py-3"><?= $this->Paginator->sort('modified') ?></th>
                                <th class="actions text-end pe-4 py-3"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($halls as $hall): ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?= h($hall->hall_type) ?></td>
                                <td>
                                    <?php if ($hall->status == 1): ?>
                                        <span class="text-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                                    <?php else: ?>
                                        <span class="text-secondary"><i class="fas fa-times-circle me-1"></i>Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="small text-white"><?= h($hall->created->format('d M Y')) ?></td>
                                <td class="small text-white"><?= h($hall->modified->format('d M Y')) ?></td>
                                <td class="actions text-end pe-4">
                                    <?= $this->Html->link(__('<i class="fas fa-eye"></i>'), ['action' => 'view', $hall->id], ['class' => 'btn btn-sm btn-outline-light rounded-circle me-1 btn-action-icon', 'escape' => false, 'title' => 'View']) ?>
                                    <?= $this->Html->link(__('<i class="fas fa-edit"></i>'), ['action' => 'edit', $hall->id], ['class' => 'btn btn-sm btn-outline-warning rounded-circle me-1 btn-action-icon', 'escape' => false, 'title' => 'Edit']) ?>
                                    <?php 
                                        $delId = 'delete-hall-' . $hall->id;
                                        $delMsg = __('Are you sure you want to delete # {0}?', $hall->id);
                                    ?>
                                    <?= $this->Form->create(null, ['url' => ['action' => 'delete', $hall->id], 'id' => $delId, 'style' => 'display:none;']) ?>
                                    <?= $this->Form->end() ?>
                                    
                                    <a href="#" 
                                       onclick="return confirmDelete(event, '<?= $delId ?>', '<?= h($delMsg) ?>')"
                                       class="btn btn-sm btn-outline-danger rounded-circle btn-action-icon" 
                                       title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        <div class="card-footer bg-black border-top border-secondary border-opacity-25 py-4">
            <div class="d-flex flex-column align-items-center">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-md mb-3">
                        <?= $this->Paginator->first('<i class="fas fa-angle-double-left"></i>', [
                            'escape' => false,
                            'class' => 'page-item',
                            'linkClass' => 'page-link bg-dark border-secondary px-3'
                        ]) ?>
                        <?= $this->Paginator->prev('<i class="fas fa-chevron-left"></i>', [
                            'escape' => false,
                            'class' => 'page-item',
                            'linkClass' => 'page-link bg-dark border-secondary px-3'
                        ]) ?>
                        <?= $this->Paginator->numbers([
                            'class' => 'page-item',
                            'linkClass' => 'page-link border-secondary mx-1 px-3',
                            'activeClass' => 'active',
                            'modulus' => 4
                        ]) ?>
                        <?= $this->Paginator->next('<i class="fas fa-chevron-right"></i>', [
                            'escape' => false,
                            'class' => 'page-item',
                            'linkClass' => 'page-link bg-dark border-secondary px-3'
                        ]) ?>
                        <?= $this->Paginator->last('<i class="fas fa-angle-double-right"></i>', [
                            'escape' => false,
                            'class' => 'page-item',
                            'linkClass' => 'page-link bg-dark border-secondary px-3'
                        ]) ?>
                    </ul>
                </nav>
                <div class="text-white-50 small fw-bold text-uppercase tracking-wider">
                    <?= $this->Paginator->counter(__('Showing {{current}} of {{count}} Halls')) ?>
                    <span class="mx-2 text-danger">|</span>
                    Page <?= $this->Paginator->counter('{{page}}') ?> of <?= $this->Paginator->counter('{{pages}}') ?>
                </div>
            </div>
        </div>
    </div>
</div>
