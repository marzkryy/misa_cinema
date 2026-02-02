<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Staff> $staffs
 */
?>
<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-3">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-3 mb-md-0"><?= __('Staffs Management') ?></h3>
        </div>
        <div class="col-md-6 text-center mb-3 mb-md-0">
            <div class="mx-auto" style="max-width: 450px;">
                <?= $this->Form->create(null, ['type' => 'get', 'class' => 'd-inline-block w-100']) ?>
                <div class="d-flex gap-2">
                    <input type="text" name="q" class="form-control bg-black text-white border-secondary border-opacity-50 rounded-pill ps-4" 
                           placeholder="Search name or email..." value="<?= h($q ?? '') ?>">
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
        <div class="col-md-3 text-end">
            <?= $this->Html->link(__('<i class="fas fa-plus me-2"></i>New Staff'), ['action' => 'add'], ['class' => 'btn btn-danger rounded-pill px-4 shadow-sm w-100', 'escape' => false]) ?>
        </div>
    </div>

    <div class="card shadow-lg bg-black border-0 rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-dark table-hover mb-0">
                <thead class="bg-danger text-white text-uppercase small fw-bold">
                    <tr>
                        <th class="ps-4 py-3"><?= $this->Paginator->sort('name') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('phone') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('email') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('role') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('status') ?></th>
                        <th class="py-3"><?= $this->Paginator->sort('created') ?></th>
                        <th class="actions text-end pe-4 py-3"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staffs as $staff): ?>
                    <tr>
                        <td class="ps-4 fw-bold"><?= h($staff->name) ?></td>
                        <td><?= h($staff->phone) ?></td>
                        <td><?= h($staff->email) ?></td>
                        <td>
                            <?php if ($staff->role === 'admin'): ?>
                                <span class="badge bg-danger rounded-pill px-3">ADMIN</span>
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill px-3">STAFF</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($staff->status == 1): ?>
                                <span class="text-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                            <?php else: ?>
                                <span class="text-secondary"><i class="fas fa-times-circle me-1"></i>Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-white"><?= h($staff->created->format('d M Y')) ?></td>
                        <td class="actions text-end pe-4">
                            <div class="d-flex justify-content-end gap-2 text-nowrap">
                                <?= $this->Html->link(__('<i class="fas fa-eye"></i>'), ['action' => 'view', $staff->id], ['class' => 'btn btn-sm btn-outline-light rounded-circle btn-action-icon', 'escape' => false, 'title' => 'View']) ?>
                                <?= $this->Html->link(__('<i class="fas fa-edit"></i>'), ['action' => 'edit', $staff->id], ['class' => 'btn btn-sm btn-outline-warning rounded-circle btn-action-icon', 'escape' => false, 'title' => 'Edit']) ?>
                                <?php 
                                    $delId = 'delete-staff-' . $staff->id;
                                    $delMsg = __('Are you sure you want to delete # {0}?', $staff->id);
                                ?>
                                <?= $this->Form->create(null, ['url' => ['action' => 'delete', $staff->id], 'id' => $delId, 'style' => 'display:none;']) ?>
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
                    <?= $this->Paginator->counter(__('Showing {{current}} of {{count}} Staff Members')) ?>
                    <span class="mx-2 text-danger">|</span>
                    Page <?= $this->Paginator->counter('{{page}}') ?> of <?= $this->Paginator->counter('{{pages}}') ?>
                </div>
            </div>
        </div>
    </div>
</div>
