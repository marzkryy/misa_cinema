<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Staff $staff
 */
?>
<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6 mb-3 mb-md-0">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= h($staff->name) ?></h3>
        </div>
        <div class="col-12 col-md-6 text-md-end">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                <?= $this->Html->link('<i class="fas fa-arrow-left me-2"></i>' . __('Back to List'), ['action' => 'index'], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]) ?>
                <?= $this->Html->link('<i class="fas fa-edit me-2"></i>' . __('Edit Staff'), ['action' => 'edit', $staff->id], ['class' => 'btn btn-danger rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]) ?>
            </div>
        </div>
    </div>

    <div class="card shadow-lg bg-black border-0 rounded-4 overflow-hidden" 
         style="transform: none !important; transition: none !important; border: 3px solid #e50914 !important;">
        <div class="card-header bg-transparent py-3 px-4" style="border-bottom: 3px solid #e50914 !important;">
            <h5 class="mb-0 fw-bold d-flex align-items-center text-white">
                <i class="fas fa-info-circle text-danger me-2"></i>STAFF DETAILS
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive no-scrollbar">
                <table class="table table-dark mb-0 staff-details-table" style="background-color: #000; --bs-table-border-color: rgba(255, 255, 255, 0.1);">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger" style="width: 30%;"><?= __('Name') ?></th>
                        <td class="py-3 fw-bold text-white"><?= h($staff->name) ?></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Role') ?></th>
                        <td class="py-3 text-white">
                            <?php if ($staff->role === 'admin'): ?>
                                <span class="badge bg-danger rounded-pill px-3">ADMIN</span>
                            <?php else: ?>
                                <span class="badge bg-secondary rounded-pill px-3">STAFF</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Email') ?></th>
                        <td class="py-3 text-white"><?= h($staff->email) ?></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Phone') ?></th>
                        <td class="py-3 text-white"><?= h($staff->phone) ?></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Status') ?></th>
                        <td class="py-3 text-white">
                            <?php if ($staff->status == 1): ?>
                                <span class="text-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                            <?php else: ?>
                                <span class="text-secondary"><i class="fas fa-times-circle me-1"></i>Inactive</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Created') ?></th>
                        <td class="py-3 text-white"><?= h($staff->created->format('d M Y, h:i A')) ?></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Modified') ?></th>
                        <td class="py-3 text-white"><?= h($staff->modified->format('d M Y, h:i A')) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card-footer bg-black border-top p-3 text-end" style="border-top: 3px solid #e50914 !important;">
            <?= $this->Form->postLink(__('<i class="fas fa-trash me-2"></i>Delete Staff'), ['action' => 'delete', $staff->id], ['confirm' => __('Are you sure you want to delete # {0}?', $staff->id), 'class' => 'btn btn-outline-danger fw-bold rounded-pill px-4 py-2', 'escape' => false]) ?>
        </div>
    </div>
</div>

<style>
    /* Fix Staff Details Table on Mobile (Unhide 2nd column) */
    @media (max-width: 991.98px) {
        .staff-details-table td:nth-child(2) {
            display: table-cell !important;
        }
    }
</style>
