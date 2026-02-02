<?php
/**
 * @var \App\View\AppView $this
 * @var array $groupedSeats
 * @var array $halls
 * @var int $hallId
 */
$this->assign('title', 'Seats Management - MisaCinema');
?>

<style>
    .seat-map-container {
        width: 100%;
        overflow: hidden;
        position: relative;
        display: flex;
        justify-content: center;
        background: #000;
        padding: 60px 40px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }

    .seat-map-scaler {
        width: max-content;
        flex-shrink: 0;
        transition: transform 0.2s ease-out;
    }

    .screen-container {
        perspective: 300px;
        margin-bottom: 50px;
    }
    .screen-indicator {
        background: #f8f9fa;
        color: #212529;
        font-weight: bold;
        letter-spacing: 5px;
        padding: 8px 0;
        width: 60%;
        margin: 0 auto;
        border-radius: 4px;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.1);
        transform: rotateX(-5deg);
        opacity: 0.6;
        font-size: 0.8rem;
        text-align: center;
    }

    .seat-admin {
        flex: 0 0 52px;
        width: 52px !important;
        height: 52px !important;
        border-radius: 6px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(255,255,255,0.1);
        position: relative;
        padding: 0 !important;
        overflow: hidden;
    }
    .seat-admin span {
        display: block;
        width: 100%;
        line-height: 1;
        text-align: center;
    }
    .seat-admin:hover {
        transform: scale(1.1);
        z-index: 10;
        box-shadow: 0 5px 15px rgba(229, 9, 20, 0.3);
    }
    .seat-admin .btn-delete-seat, .seat-admin .btn-edit-seat {
        position: absolute;
        width: 16px;
        height: 16px;
        color: white;
        border-radius: 50%;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        border: none;
        z-index: 15;
        top: 2px;
    }
    .seat-admin .btn-delete-seat {
        right: 2px;
        background: rgba(229, 9, 20, 0.9);
    }
    .seat-admin .btn-edit-seat {
        left: 2px;
        background: rgba(0, 123, 255, 0.9);
    }
    .seat-admin:hover .btn-delete-seat, .seat-admin:hover .btn-edit-seat {
        display: flex;
    }
    .seat-admin.active {
        background: transparent;
        color: #e50914;
        border: 2px solid #e50914;
    }
    .seat-admin.couple {
        flex: 0 0 110px;
        width: 110px !important;
        border-color: #f72585 !important;
        color: #f72585 !important;
    }
    .seat-admin.bed {
        flex: 0 0 140px;
        width: 140px !important;
        border-color: #4cc9f0 !important;
        color: #4cc9f0 !important;
    }
    .seat-admin.premium {
        border-color: #fd7e14 !important;
        color: #fd7e14 !important;
    }
    .seat-admin .seat-icon {
        font-size: 1.2rem;
        margin-bottom: 2px;
    }
    .seat-admin.couple .seat-icon {
        font-size: 1.4rem;
    }
    .seat-admin.bed .seat-icon {
        font-size: 1.6rem;
    }
    .seat-admin.inactive {
        background: #1a1a1a;
        color: rgba(255,255,255,0.2) !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
    }
    .seat-admin .seat-label {
        font-size: 1.1rem;
        font-weight: 500;
    }
    .seat-admin .seat-type {
        font-size: 0.55rem;
        text-transform: uppercase;
        opacity: 0.9;
        font-weight: 400;
        letter-spacing: -0.3px;
        margin-top: 2px;
        display: block;
        width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        padding: 0 1px;
    }
    .row-label {
        width: 25px;
        min-width: 25px;
        color: rgba(255,255,255,0.5);
        font-weight: bold;
        text-align: center;
        font-size: 0.9rem;
    }
    .hall-selector-card {
        background: rgba(255,255,255,0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 15px;
    }
    .status-badge {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .btn-delete-row {
        width: 18px;
        height: 18px;
        background: rgba(229, 9, 20, 0.2);
        color: #e50914;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        border: 1px solid rgba(229, 9, 20, 0.3);
        cursor: pointer;
        transition: all 0.2s;
        margin: 2px auto;
        opacity: 0;
    }
    .d-flex:hover > .row-controls .btn-delete-row {
        opacity: 1;
    }
    .btn-delete-row:hover {
        background: #e50914;
        color: #fff;
        transform: scale(1.1);
    }
    .btn-deactivate-row {
        width: 18px;
        height: 18px;
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        border: 1px solid rgba(255, 193, 7, 0.3);
        cursor: pointer;
        transition: all 0.2s;
        margin: 2px auto;
        opacity: 0;
    }
    .d-flex:hover > .row-controls .btn-delete-row,
    .d-flex:hover > .row-controls .btn-deactivate-row {
        opacity: 1;
    }
    .btn-deactivate-row:hover {
        background: #ffc107;
        color: #000;
        transform: scale(1.1);
    }
    .btn-config-row {
        width: 18px;
        height: 18px;
        background: rgba(0, 123, 255, 0.1);
        color: #007bff;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        border: 1px solid rgba(0, 123, 255, 0.3);
        cursor: pointer;
        transition: all 0.2s;
        margin: 2px auto;
        opacity: 0;
    }
    .d-flex:hover > .row-controls .btn-delete-row,
    .d-flex:hover > .row-controls .btn-deactivate-row,
    .d-flex:hover > .row-controls .btn-config-row {
        opacity: 1;
    }
    .btn-config-row:hover {
        background: #007bff;
        color: #fff;
        transform: scale(1.1);
    }


    .row-controls {
        width: 35px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
    }

    /* Toned Down Button Hover Effects */
    .btn-danger, .btn-outline-danger, .btn-outline-light {
        transition: all 0.2s ease-in-out;
    }

    .btn-danger:hover {
        background-color: #ff0f1b !important;
        border-color: #ff0f1b !important;
        color: #fff !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(229, 9, 20, 0.2);
    }

    .btn-outline-danger:hover {
        background-color: #e50914 !important;
        color: #fff !important;
        transform: translateY(-2px);
    }

    .btn-outline-light:hover {
        background-color: #fff !important;
        color: #000 !important;
        transform: translateY(-2px);
    }

    /* Responsive scaling */
    @media (max-width: 1200px) {
        .seat-admin {
            flex: 0 0 32px;
            width: 32px !important;
            height: 32px !important;
            font-size: 0.65rem;
        }
        .seat-admin .seat-label { font-size: 0.75rem; }
        .seat-admin .seat-type { display: none; }
        .row-label { width: 15px; min-width: 15px; font-size: 0.6rem; }
        .screen-indicator { width: 80% !important; }
    }
    @media (max-width: 768px) {
        .seat-admin {
            flex: 0 0 28px;
            width: 28px !important;
            height: 28px !important;
        }
    }
    @media (max-width: 576px) {
        .seat-admin-map-wrapper { padding: 20px 10px; }
        .seat-admin {
            flex: 0 0 30px;
            width: 30px !important;
            height: 30px !important;
            font-size: 0.7rem;
        }
        .screen-indicator { width: 100% !important; margin-bottom: 30px; }
    }

    /* Hide arrows/spinners on number inputs */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
    /* Mobile Optimization for Seats Management */
    @media (max-width: 767.98px) {
        .seat-admin {
            flex: 0 0 38px;
            width: 38px !important;
            height: 38px !important;
            font-size: 0.8rem;
        }
        
        .seat-admin .seat-label {
            font-size: 0.5rem !important;
        }
        
        .seat-admin .seat-icon {
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        .seat-admin .seat-type {
            display: none;
        }

        .seat-admin.couple {
            flex: 0 0 80px;
            width: 80px !important;
        }
        .seat-admin.bed {
            flex: 0 0 100px;
            width: 100px !important;
        }
        
        .seat-map-container {
            padding: 30px 0 10px 0 !important;
            min-height: 300px;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }
    }
</style>

<div class="container mt-5 mb-5 pb-5">
    <div class="row mb-5 align-items-end g-4">
        <div class="col-xl-4 col-lg-5">
            <h2 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0">SEATS MANAGEMENT</h2>
        </div>
        <div class="col-xl-8 col-lg-7">
            <div class="d-flex flex-wrap justify-content-center justify-content-lg-end gap-2 gap-sm-3 align-items-center">
                <button type="button" 
                        class="btn btn-outline-danger rounded-pill px-3 px-sm-4 shadow-sm fw-bold text-nowrap"
                        onclick="deleteAllSeats()">
                    <i class="fas fa-trash-alt me-2"></i>Delete All Seats
                </button>
                <?= $this->Html->link(__('<i class="fas fa-magic me-2"></i>Auto Generate'), ['action' => 'generate', '?' => ['hall_id' => $hallId]], ['class' => 'btn btn-outline-light rounded-pill px-3 px-sm-4 shadow-sm fw-bold text-nowrap', 'escape' => false]) ?>
                <?= $this->Html->link(__('<i class="fas fa-plus-circle me-2"></i>ADD SEAT'), ['action' => 'add'], ['class' => 'btn btn-danger rounded-pill px-3 px-sm-4 shadow-sm fw-bold text-nowrap', 'escape' => false]) ?>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="hall-selector-card p-4">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <label class="text-white-50 small fw-bold text-uppercase mb-0 me-3">Select Hall</label>
                    </div>
                    <div class="col">
                        <div class="d-flex gap-2 overflow-auto no-scrollbar py-2">
                            <?php foreach ($halls as $id => $name): ?>
                                <a href="<?= $this->Url->build(['action' => 'index', '?' => ['hall_id' => $id]]) ?>" 
                                   class="btn <?= $hallId == $id ? 'btn-danger' : 'btn-outline-secondary' ?> rounded-pill px-4 transition-all fw-bold text-uppercase small d-flex align-items-center justify-content-center" style="min-width: 120px; min-height: 40px;">
                                   <?= h($name) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="seat-map-container" id="admin-seat-container">
        <div class="seat-map-scaler" id="admin-seat-scaler">
            <div class="screen-container text-center">
                <div class="screen-indicator">SCREEN</div>
            </div>

            <div class="seat-grid d-flex flex-column gap-2">
                <?php if (empty($groupedSeats)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-couch fa-4x text-white-50 opacity-25 mb-4"></i>
                        <h4 class="text-white">No Seats Configured</h4>
                        <p class="text-white-50">Please add seats to this hall to see them here.</p>
                    </div>
                <?php else: ?>
                    <?php 
                    ksort($groupedSeats); // Ensure rows are A-Z
                    foreach ($groupedSeats as $rowLabel => $rowSeats): ?>
                        <div class="d-flex justify-content-center align-items-center gap-2 mb-2 w-100" style="min-width: fit-content;">
                            <div class="row-controls">
                                <div class="row-label"><?= h($rowLabel) ?></div>
                                <div class="d-flex flex-column gap-1">
                                    <button type="button" class="btn-config-row" title="Bulk Configure Row <?= h($rowLabel) ?> (Type/Price)" onclick="configureRow(event, '<?= h($rowLabel) ?>')">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <button type="button" class="btn-deactivate-row" title="Toggle Maintenance for Row <?= h($rowLabel) ?>" onclick="toggleRowStatus(event, '<?= h($rowLabel) ?>')">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                    <button type="button" class="btn-delete-row" title="Delete entire Row <?= h($rowLabel) ?>" onclick="deleteRow(event, '<?= h($rowLabel) ?>')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <?php foreach ($rowSeats as $seat): 
                                    $typeClass = strtolower(h($seat->seat_type));
                                    $iconClass = 'fa-couch';
                                    if ($typeClass == 'couple') $iconClass = 'fa-user-friends';
                                    if ($typeClass == 'bed') $iconClass = 'fa-bed';
                                    if ($typeClass == 'premium') $iconClass = 'fa-crown';
                                ?>
                                    <div class="seat-admin <?= $seat->status == 1 ? 'active' : 'inactive' ?> <?= h($typeClass) ?>" 
                                         data-id="<?= $seat->id ?>"
                                         onclick="toggleSeatStatus(this)">
                                        <div class="seat-icon"><i class="fas <?= $iconClass ?>"></i></div>
                                        <span class="seat-label"><?= h($seat->seat_number) ?></span>
                                        <span class="seat-type"><?= h($seat->seat_type) ?></span>
                                        
                                        <?php if ($seat->status == 0): ?>
                                            <span class="maintenance-badge position-absolute top-50 start-50 translate-middle text-white" style="font-size: 0.8rem;">
                                                <i class="fas fa-tools"></i>
                                            </span>
                                        <?php endif; ?>

                                            <button type="button" class="btn-edit-seat" onclick="editSeat(event, <?= $seat->id ?>)" title="Edit Seat">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button type="button" class="btn-delete-seat" onclick="deleteSeat(event, <?= $seat->id ?>)" title="Delete Seat">
                                                <i class="fas fa-times"></i>
                                            </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="row-controls">
                                <div class="row-label"><?= h($rowLabel) ?></div>
                                <div class="d-flex flex-column gap-1">
                                    <button type="button" class="btn-config-row" title="Bulk Configure Row <?= h($rowLabel) ?> (Type/Price)" onclick="configureRow(event, '<?= h($rowLabel) ?>')">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <button type="button" class="btn-deactivate-row" title="Toggle Maintenance for Row <?= h($rowLabel) ?>" onclick="toggleRowStatus(event, '<?= h($rowLabel) ?>')">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                    <button type="button" class="btn-delete-row" title="Delete entire Row <?= h($rowLabel) ?>" onclick="deleteRow(event, '<?= h($rowLabel) ?>')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="mt-5 pt-4 border-top border-secondary border-opacity-10 d-flex justify-content-center gap-4">
                <div class="d-flex align-items-center text-white small">
                    <span class="status-badge bg-success"></span> Available / Active
                </div>
                <div class="d-flex align-items-center text-white small">
                    <span class="status-badge bg-secondary"></span> Maintenance / Inactive
                </div>
                <div class="d-flex align-items-center text-white-50 small">
                    <i class="fas fa-info-circle me-2"></i> Click a seat to toggle its status
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteAllSeats() {
    Swal.fire({
        title: 'Delete ALL Seats?',
        text: "This will permanently remove every seat in THIS hall. This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e50914',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete EVERYTHING!',
        background: '#1a1a1a',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= $this->Url->build(['action' => 'deleteAllInHall', '?' => ['hall_id' => $hallId]]) ?>';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_csrfToken';
            csrfInput.value = '<?= $this->request->getAttribute('csrfToken') ?>';
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function editSeat(event, seatId) {
    const seatEl = event.currentTarget.closest('.seat-admin');

    event.stopPropagation(); // Prevent toggling status
    window.location.href = '<?= $this->Url->build(['action' => 'edit']) ?>/' + seatId;
}

function configureRow(event, rowLabel) {
    event.stopPropagation();
    
    // Find first seat of this row to pre-fill current values
    const firstSeat = document.querySelector(`.seat-admin[data-id]`); // Simplified, usually first seat in row
    
    Swal.fire({
        title: 'Configure Row ' + rowLabel,
        html: `
            <div class="text-start mb-3">
                <label class="form-label text-white-50 small text-uppercase">New Seat Type</label>
                <select id="swal-seat-type" class="form-select bg-black text-white border-secondary mb-3">
                    <option value="Standard">Standard</option>
                    <option value="Premium">Premium</option>
                    <option value="Couple">Couple (Double)</option>
                    <option value="Bed">Bed (Lux)</option>
                </select>
                <label class="form-label text-white-50 small text-uppercase">New Price (RM)</label>
                <input id="swal-seat-price" type="number" step="0.01" value="25.00" class="form-control bg-black text-white border-secondary mb-3">
                
                <div class="form-check form-switch mt-3">
                    <input class="form-check-input" type="checkbox" id="swal-global-update">
                    <label class="form-check-label text-white small" for="swal-global-update">
                        Apply this price to ALL seats of this type in this hall?
                    </label>
                </div>
            </div>

        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: 'Update Row',
        confirmButtonColor: '#007bff',
        background: '#1a1a1a',
        color: '#fff',
        didOpen: () => {
            const typeSelect = document.getElementById('swal-seat-type');
            const priceInput = document.getElementById('swal-seat-price');
            const prices = {
                'Standard': 25.00,
                'Premium': 40.00,
                'Couple': 70.00,
                'Bed': 180.00
            };
            typeSelect.addEventListener('change', () => {
                if (prices[typeSelect.value]) {
                    priceInput.value = prices[typeSelect.value].toFixed(2);
                }
            });
        },
        preConfirm: () => {
            return {
                seat_type: document.getElementById('swal-seat-type').value,
                seat_price: document.getElementById('swal-seat-price').value,
                update_global: document.getElementById('swal-global-update').checked
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new URLSearchParams();
            formData.append('hall_id', '<?= $hallId ?>');
            formData.append('row_label', rowLabel);
            formData.append('seat_type', result.value.seat_type);
            formData.append('seat_price', result.value.seat_price);
            formData.append('update_global', result.value.update_global);

            fetch('<?= $this->Url->build(['action' => 'ajaxUpdateRowDetails']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
                },
                body: formData.toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Row Updated', text: data.message, background: '#1a1a1a', color: '#fff' })
                    .then(() => location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: data.message, background: '#1a1a1a', color: '#fff' });
                }
            });
        }
    });
}

function toggleRowStatus(event, rowLabel) {
    event.stopPropagation();
    
    Swal.fire({
        title: 'Toggle Row Maintenance?',
        text: "This will switch all seats in Row " + rowLabel + " between Active and Maintenance mode.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        confirmButtonText: 'Yes, toggle status!',
        background: '#1a1a1a',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('<?= $this->Url->build(['action' => 'ajaxToggleRowStatus']) ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
                },
                body: `hall_id=<?= $hallId ?>&row_label=${rowLabel}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload(); // Refresh to update all seats in row
                } else {
                    Swal.fire({ icon: 'error', title: 'Fail', text: data.message, background: '#1a1a1a', color: '#fff' });
                }
            });
        }
    });
}

function deleteRow(event, rowLabel) {
    event.stopPropagation();
    
    Swal.fire({
        title: 'Delete Row ' + rowLabel + '?',
        text: "Every seat in this row will be permanently removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e50914',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete whole row!',
        background: '#1a1a1a',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= $this->Url->build(['action' => 'deleteRow']) ?>/<?= $hallId ?>/' + rowLabel;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_csrfToken';
            csrfInput.value = '<?= $this->request->getAttribute('csrfToken') ?>';
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function deleteSeat(event, seatId) {
    event.stopPropagation(); // Prevent toggling status
    
    Swal.fire({
        title: 'Delete Seat?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#e50914',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        background: '#1a1a1a',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a hidden form to submit the delete request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= $this->Url->build(['action' => 'delete']) ?>/' + seatId;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_csrfToken';
            csrfInput.value = '<?= $this->request->getAttribute('csrfToken') ?>';
            form.appendChild(csrfInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function toggleSeatStatus(el) {

    const seatId = el.dataset.id;
    const isActivating = el.classList.contains('inactive');
    
    // Optimistic UI update
    el.style.pointerEvents = 'none';
    el.style.opacity = '0.5';

    fetch('<?= $this->Url->build(['action' => 'ajaxToggleStatus']) ?>/' + seatId, {
        method: 'POST',
        headers: {
            'X-CSRF-Token': '<?= $this->request->getAttribute('csrfToken') ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        el.style.pointerEvents = 'auto';
        el.style.opacity = '1';

        if (data.status === 'success') {
            if (data.new_status == 1) {
                el.classList.remove('inactive');
                el.classList.add('active');
                // Remove maintenance icon if it exists
                const badge = el.querySelector('.maintenance-badge');
                if (badge) badge.remove();
            } else {
                el.classList.remove('active');
                el.classList.add('inactive');
                // Add maintenance icon if it doesn't exist
                if (!el.querySelector('.maintenance-badge')) {
                    const badge = document.createElement('span');
                    badge.className = 'maintenance-badge position-absolute top-50 start-50 translate-middle text-white';
                    badge.style.fontSize = '0.8rem';
                    badge.innerHTML = '<i class="fas fa-tools"></i>';
                    el.appendChild(badge);
                }
            }
            
            // Subtle toast or sound could go here
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                text: data.message || 'Could not update seat.',
                background: '#1a1a1a',
                color: '#fff'
            });
        }
    })
    .catch(err => {
        el.style.pointerEvents = 'auto';
        el.style.opacity = '1';
        Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'Connection failed.',
            background: '#1a1a1a',
            color: '#fff'
        });
    });
}

// AUTO-SCALE LOGIC FOR SEATS MANAGEMENT (CONNECTED TO GLOBAL)
// AUTO-SCALE LOGIC FOR SEATS MANAGEMENT (CONNECTED TO GLOBAL)
(function() {
    // Override standard initAutoScaler height logic with buffer
    const container = document.getElementById('admin-seat-container');
    const scaler = document.getElementById('admin-seat-scaler');
    
    function updateScale() {
        if (!container || !scaler) return;
        
        scaler.style.transform = 'none';
        scaler.style.width = 'max-content';
        
        const containerWidth = container.offsetWidth;
        const contentWidth = scaler.offsetWidth;
        
        if (contentWidth > containerWidth && containerWidth > 0) {
            const scale = containerWidth / contentWidth;
            scaler.style.transform = `scale(${scale})`;
            scaler.style.transformOrigin = 'top center';
            container.style.height = ((scaler.offsetHeight * scale) + 50) + 'px'; // +50px Buffer
        } else {
            scaler.style.transform = 'none';
            scaler.style.width = '100%';
            container.style.height = 'auto';
        }
    }
    
    window.addEventListener('resize', updateScale);
    window.addEventListener('load', updateScale);
    setTimeout(updateScale, 300);
})();
</script>
