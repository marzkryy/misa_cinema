<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Seat $seat
 */
?>
<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-12 col-md-6 mb-3 mb-md-0">
            <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= h($seat->seat_type) ?></h3>
        </div>
        <div class="col-12 col-md-6 text-md-end">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                <?= $this->Html->link(__('<i class="fas fa-arrow-left me-2"></i>Back to Hall'), ['controller' => 'Halls', 'action' => 'view', $seat->hall_id], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false]) ?>
            </div>
        </div>
    </div>

    <div class="card shadow-lg bg-black border-0 rounded-4 overflow-hidden" 
         style="transform: none !important; transition: none !important; border: 3px solid #e50914 !important;">
        <div class="card-header bg-transparent py-3 px-4" style="border-bottom: 3px solid #e50914 !important;">
            <h5 class="mb-0 fw-bold d-flex align-items-center text-white">
                <i class="fas fa-chair text-danger me-2"></i>SEAT DETAILS
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive no-scrollbar">
                <table class="table table-dark mb-0 seat-details-table" style="background-color: #000; --bs-table-border-color: rgba(255, 255, 255, 0.1);">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger" style="width: 30%;"><?= __('Hall') ?></th>
                        <td class="py-3 fw-bold text-white"><?= $seat->has('hall') ? $this->Html->link($seat->hall->hall_type, ['controller' => 'Halls', 'action' => 'view', $seat->hall->id], ['class' => 'text-decoration-none text-white hover-red']) : '' ?></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Seat Type') ?></th>
                        <td class="py-3 fw-bold text-white"><?= h($seat->seat_type) ?></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Seat Price') ?></th>
                        <td class="py-3 text-white">RM <?= $this->Number->format($seat->seat_price, ['places' => 2]) ?></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Status') ?></th>
                        <td class="py-3 text-white">
                            <?php if ($seat->status == 1): ?>
                                <span class="text-success"><i class="fas fa-check-circle me-1"></i>Active</span>
                            <?php else: ?>
                                <span class="text-secondary"><i class="fas fa-times-circle me-1"></i>Inactive</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Created') ?></th>
                        <td class="py-3 text-white"><?= h($seat->created->format('d M Y, h:i A')) ?></td>
                    </tr>
                    <tr>
                        <th class="ps-4 py-3 text-uppercase text-danger"><?= __('Modified') ?></th>
                        <td class="py-3 text-white"><?= h($seat->modified->format('d M Y, h:i A')) ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card-footer bg-black border-top p-3 text-end" style="border-top: 3px solid #e50914 !important;">
            <p class="text-white-50 small mb-0"><i class="fas fa-info-circle me-1"></i>To modify this seat, please use the <b>Seat Map Management</b> center.</p>
        </div>
    </div>
</div>

<style>
    .hover-red:hover {
        color: #dc3545 !important;
    }
    
    /* Fix Seat Details Table on Mobile (Unhide 2nd column) */
    @media (max-width: 991.98px) {
        .seat-details-table td:nth-child(2) {
            display: table-cell !important;
        }
    }
</style>
