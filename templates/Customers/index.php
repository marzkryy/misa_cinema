<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Customer> $customers
 */
?>
<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-5">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-3 mb-md-0"><?= __('Customer Management') ?></h3>
        </div>
        <div class="col-md-7 text-end">
            <div class="ms-auto" style="max-width: 400px;">
                <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-inline-block w-100']) ?>
                <div class="d-flex gap-2">
                    <input type="text" name="q" class="form-control bg-black text-white border-secondary border-opacity-50 rounded-pill ps-4" 
                           placeholder="Search by Name or Email" value="<?= h($q ?? '') ?>">
                    <button class="btn btn-danger rounded-pill px-4 shadow-sm" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                    <?php if (!empty($q)): ?>
                        <?= $this->Html->link('<i class="fas fa-times"></i>', ['action' => 'index'], ['class' => 'btn btn-outline-secondary rounded-pill d-flex align-items-center', 'escape' => false, 'title' => 'Clear Search']) ?>
                    <?php endif; ?>
                </div>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>

    <div class="card shadow-lg bg-black border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead class="bg-danger text-white text-uppercase small fw-bold">
                    <tr>
                        <th class="ps-4 py-3"><?= $this->Paginator->sort('name') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('email') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('phone') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('created', 'Joined') ?></th>
                        <th class="actions text-end pe-4 py-3"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?= h($customer->name) ?></td>
                        <td><?= h($customer->email) ?></td>
                        <td>
                            <?php if ($customer->phone): ?>
                                <?= h($customer->phone) ?>
                            <?php else: ?>
                                <span class="text-secondary">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-white"><?= h($customer->created->format('d M Y, h:i A')) ?></td>
                        <td class="actions text-end pe-4">
                            <div class="d-flex justify-content-end gap-2 text-nowrap">
                                <?= $this->Html->link(__('<i class="fas fa-eye"></i>'), ['action' => 'view', $customer->id], ['class' => 'btn btn-sm btn-outline-light rounded-circle btn-action-icon', 'escape' => false, 'title' => 'View']) ?>
                                <?php 
                                    $delId = 'delete-cust-' . $customer->id;
                                    $delMsg = __('Are you sure you want to delete customer "{0}"?', $customer->name);
                                ?>
                                <?= $this->Form->create(null, ['url' => ['action' => 'delete', $customer->id], 'id' => $delId, 'style' => 'display:none;']) ?>
                                <?= $this->Form->end() ?>
                                
                                <a href="#" 
                                   onclick="return confirmDelete(event, '<?= $delId ?>', '<?= h($delMsg) ?>')"
                                   class="btn btn-sm btn-outline-danger rounded-circle btn-action-icon" 
                                   title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
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
                    <?= $this->Paginator->counter(__('Showing {{current}} of {{count}} Customers')) ?>
                    <span class="mx-2 text-danger">|</span>
                    Page <?= $this->Paginator->counter('{{page}}') ?> of <?= $this->Paginator->counter('{{pages}}') ?>
                </div>
            </div>
        </div>
    </div>
</div>