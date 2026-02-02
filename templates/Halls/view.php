<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Hall $hall
 */
?>
<style>
    .btn-outline-light:hover {
        color: #000000 !important;
    }
    .btn-outline-light:hover i {
        color: #000000 !important;
    }

    /* Fix Hall Details Table on Mobile (Unhide 2nd column) */
    @media (max-width: 991.98px) {
        .hall-details-table td:nth-child(2) {
            display: table-cell !important;
        }
    }
    
    /* Force columns to show on mobile for Related Seats */
    @media (max-width: 991.98px) {
        .force-mobile-cols thead th:nth-child(2),
        .force-mobile-cols tbody td:nth-child(2),
        .force-mobile-cols thead th:nth-child(4),
        .force-mobile-cols tbody td:nth-child(4) {
            display: table-cell !important;
        }
        
        /* Optional: adjust font size if table gets too wide */
        .force-mobile-cols td, .force-mobile-cols th {
            font-size: 0.75rem !important;
            padding: 0.5rem 0.25rem !important;
        }
    }
</style>

<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6 mb-3 mb-md-0">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= h($hall->hall_type) ?></h3>
        </div>
        <div class="col-12 col-md-6 text-md-end">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                <?= $this->Html->link(__('<i class="fas fa-arrow-left me-2"></i>Back to List'), ['action' => 'index'], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false]) ?>
                <?= $this->Html->link(__('<i class="fas fa-edit me-2"></i>Edit Hall'), ['action' => 'edit', $hall->id], ['class' => 'btn btn-danger rounded-pill px-4', 'escape' => false]) ?>
            </div>
        </div>
    </div>

    <div class="card shadow-lg bg-black border-0 rounded-4 overflow-hidden" 
         style="transform: none !important; transition: none !important; border: 3px solid #e50914 !important;">
        <div class="card-header bg-transparent py-3 px-4" style="border-bottom: 3px solid #e50914 !important;">
            <h5 class="mb-0 fw-bold d-flex align-items-center text-white">
                <i class="fas fa-university text-danger me-2"></i>HALL DETAILS
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive no-scrollbar">
                <table class="table table-dark mb-0 hall-details-table" style="background-color: #000; --bs-table-border-color: rgba(255, 255, 255, 0.1);">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger" style="width: 30%;"><?= __('Hall Type') ?></th>
                        <td class="py-3 fw-bold text-white"><?= h($hall->hall_type) ?></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Status') ?></th>
                        <td class="py-3 text-white">
                            <?php if ($hall->status == 1): ?>
                                <span class="text-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                            <?php else: ?>
                                <span class="text-secondary"><i class="fas fa-times-circle me-1"></i>Inactive</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Created') ?></th>
                        <td class="py-3 text-white"><?= h($hall->created->format('d M Y, h:i A')) ?></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Modified') ?></th>
                        <td class="py-3 text-white"><?= h($hall->modified->format('d M Y, h:i A')) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card-footer bg-black border-top p-3 text-end" style="border-top: 3px solid #e50914 !important;">
            <?= $this->Form->postLink(__('<i class="fas fa-trash me-2"></i>Delete Hall'), ['action' => 'delete', $hall->id], ['confirm' => __('Are you sure you want to delete # {0}?', $hall->id), 'class' => 'btn btn-outline-danger fw-bold rounded-pill px-4 py-2', 'escape' => false]) ?>
        </div>
    </div>

    <?php if (!empty($hall->seats)): ?>
    <div class="mt-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
            <h4 class="text-white fw-bold mb-0 border-start border-4 border-danger ps-3">RELATED SEATS</h4>
            <?= $this->Html->link(__('<i class="fas fa-th me-2"></i>GO TO SEAT MAP MANAGEMENT'), ['controller' => 'Seats', 'action' => 'index', '?' => ['hall_id' => $hall->id]], ['class' => 'btn btn-danger rounded-pill px-4 fw-bold shadow-lg w-100 w-md-auto', 'escape' => false]) ?>
        </div>
        <div class="card shadow-lg bg-black border-0 rounded-4 overflow-hidden" 
             style="transform: none !important; transition: none !important; border: 1px solid rgba(255,255,255,0.05) !important;">
             <div class="card-body p-0">
                <div class="table-responsive no-scrollbar">
                    <table class="table table-dark mb-0 table-hover force-mobile-cols" style="background-color: #000; --bs-table-border-color: rgba(255, 255, 255, 0.1);">
                        <thead class="bg-danger text-white text-uppercase small fw-bold">
                            <tr>
                                <th class="text-center py-3"><?= __('Seat No') ?></th>
                                <th class="text-center py-3"><?= __('Type') ?></th>
                                <th class="text-center py-3"><?= __('Price') ?></th>
                                <th class="text-center py-3"><?= __('Status') ?></th>
                                <th class="text-center py-3"><?= __('Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($hall->seats as $seats): ?>
                            <tr>
                                <td class="text-center py-3 text-white fw-bold"><?= h($seats->seat_number) ?></td>
                                <td class="text-center py-3 text-white-50 small"><?= h($seats->seat_type) ?></td>
                                <td class="text-center py-3 text-white">RM <?= $this->Number->format($seats->seat_price, ['places' => 2]) ?></td>
                                <td class="text-center py-3 text-white">
                                    <?php if ($seats->status == 1): ?>
                                        <span class="badge bg-success rounded-pill px-3">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary rounded-pill px-3">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3 text-center">
                                    <?= $this->Html->link(__('<i class="fas fa-eye"></i>'), ['controller' => 'Seats', 'action' => 'view', $seats->id], ['class' => 'btn btn-outline-light btn-sm rounded-pill px-3', 'escape' => false]) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
