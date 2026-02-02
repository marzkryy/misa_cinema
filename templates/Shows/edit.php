<div class="container py-5">
    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <div class="card bg-dark text-white shadow-lg border-0 overflow-hidden" style="border-radius: 20px;">
                <div class="card-header bg-danger py-4 text-center border-0">
                    <h3 class="fw-bold mb-0 text-uppercase tracking-wider">
                        <i class="fas fa-edit me-2"></i><?= __('Edit Movie Session') ?>
                    </h3>
                    <p class="small opacity-75 mb-0 mt-1">Updating session details for #<?= h($show->id) ?></p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?= $this->Form->create($show, ['type' => 'file']) ?>

                    <!-- Movie Section -->
                    <div class="mb-5 pb-4 border-bottom border-secondary border-opacity-25">
                        <h5 class="text-danger fw-bold mb-4 tracking-wide"><i class="fas fa-film me-2"></i>MOVIE
                            INFORMATION</h5>

                        <div class="row g-3">
                            <div class="col-md-5">
                                <?= $this->Form->control('show_title', [
                                    'class' => 'form-control bg-black text-white border-secondary border-opacity-50 py-2',
                                    'label' => ['class' => 'form-label small text-white fw-bold text-uppercase', 'text' => 'Movie Title']
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->control('genre', [
                                    'class' => 'form-control bg-black text-white border-secondary border-opacity-50 py-2',
                                    'label' => ['class' => 'form-label small text-white fw-bold text-uppercase', 'text' => 'Genre']
                                ]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $this->Form->control('duration', [
                                    'type' => 'number',
                                    'class' => 'form-control bg-black text-white border-secondary border-opacity-50 py-2',
                                    'label' => ['class' => 'form-label small text-white fw-bold text-uppercase', 'text' => 'Duration (min)']
                                ]) ?>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small text-white fw-bold text-uppercase">Movie Poster</label>
                                <div
                                    class="d-flex align-items-start gap-4 p-3 bg-black bg-opacity-25 rounded-3 border border-secondary border-opacity-10">
                                    <?php if ($show->avatar): ?>
                                        <img id="movie-poster-preview" src="<?= $this->Url->build('/img/shows/' . $show->avatar) ?>"
                                            class="rounded shadow-sm"
                                            style="height: 120px; width: 85px; object-fit: cover;">
                                    <?php else: ?>
                                        <div id="poster-placeholder" class="bg-dark rounded d-flex align-items-center justify-content-center border border-secondary border-opacity-25"
                                            style="height: 120px; width: 85px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                        <img id="movie-poster-preview" src="" class="rounded shadow-sm d-none"
                                            style="height: 120px; width: 85px; object-fit: cover;">
                                    <?php endif; ?>

                                    <div class="flex-grow-1">
                                        <?= $this->Form->file('avatar', ['id' => 'movie-poster-input', 'class' => 'form-control bg-black text-white border-secondary border-opacity-50']) ?>
                                        <p class="small text-white-50 mt-2 mb-0">
                                            <i class="fas fa-info-circle me-1"></i> Upload a new poster to replace the current one.
                                        </p>
                                        <?= $this->Form->hidden('avatar_dir') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Session Section -->
                    <div class="mb-5">
                        <h5 class="text-danger fw-bold mb-4 tracking-wide"><i class="fas fa-clock me-2"></i>SESSION
                            DETAILS</h5>
                        <div
                            class="row g-3 p-4 bg-black bg-opacity-25 rounded-4 border border-secondary border-opacity-10">
                            <div class="col-md-4">
                                <?= $this->Form->control('show_date', [
                                    'class' => 'form-control bg-black text-white border-secondary border-opacity-50 py-2',
                                    'label' => ['class' => 'form-label x-small text-white fw-bold text-uppercase opacity-75', 'text' => 'Screening Date']
                                ]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $this->Form->control('show_time', [
                                    'class' => 'form-control bg-black text-white border-secondary border-opacity-50 py-2',
                                    'label' => ['class' => 'form-label x-small text-white fw-bold text-uppercase opacity-75', 'text' => 'Start Time']
                                ]) ?>
                            </div>
                            <div class="col-md-5">
                                <?= $this->Form->control('hall_id', [
                                    'options' => $halls,
                                    'empty' => __('-- Select Hall --'),
                                    'class' => 'form-select bg-black text-white border-secondary border-opacity-50 py-2',
                                    'label' => ['class' => 'form-label x-small text-white fw-bold text-uppercase opacity-75', 'text' => 'Cinema Hall']
                                ]) ?>
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input bg-black border-secondary" type="checkbox" name="apply_to_all_day" id="applyAllDay">
                                        <label class="form-check-label small text-white-50" for="applyAllDay">
                                            Apply <strong class="text-danger">Hall, Duration & Movie Meta</strong> to ALL sessions today
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mt-3 pt-3 border-top border-secondary border-opacity-10">
                                <?= $this->Form->control('status', [
                                    'type' => 'select',
                                    'options' => [1 => 'Active / Available', 0 => 'Hidden / Unavailable'],
                                    'class' => 'form-select bg-black text-white border-secondary border-opacity-50 py-2',
                                    'label' => ['class' => 'form-label x-small text-white fw-bold text-uppercase opacity-75', 'text' => 'Global Status']
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <?= $this->Form->hidden('redirect_to_session', ['id' => 'redirect-to-session']) ?>

                    <div class="text-center pt-3 d-flex gap-3">
                        <?php 
                            $cancelUrl = ['action' => 'index'];
                            $viewContext = $this->request->getQuery('view');
                            if ($viewContext) {
                                $cancelUrl['?'] = ['date' => $show->show_date instanceof \DateTimeInterface ? $show->show_date->format('Y-m-d') : $show->show_date, 'view' => $viewContext];
                            }
                        ?>
                        <?= $this->Html->link(__('CANCEL'), $cancelUrl, ['class' => 'btn btn-outline-secondary btn-lg px-4 flex-grow-1', 'style' => 'border-radius: 50px;']) ?>
                        <?= $this->Form->button(__('SAVE UPDATES'), ['class' => 'btn btn-danger btn-lg px-5 fw-bold shadow-lg flex-grow-2 w-100', 'style' => 'border-radius: 50px;']) ?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Info Column -->
        <div class="col-lg-4" style="align-self: stretch;">
            <div class="premium-sidebar-sticky">
            <!-- Related Sessions Switcher -->
            <div class="card bg-black border border-secondary border-opacity-10 shadow-lg mb-4 sidebar-ref-card"
                style="border-radius: 20px; transition: transform 0.3s ease;">
                <div class="card-header bg-dark py-3 border-0">
                    <h6 class="mb-0 text-white fw-bold">
                        <i class="fas fa-list-ul text-danger me-2"></i>OTHER SESSIONS TODAY
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="p-4 border-bottom border-secondary border-opacity-25">
                        <p class="small text-white-50 mb-0">Editing sessions for:
                            <br>
                            <span class="text-white fw-bold h5 mb-0"><?= $show->show_date instanceof \DateTimeInterface ? $show->show_date->format('d-m-Y') : $show->show_date ?></span>
                        </p>
                    </div>

                    <div class="custom-scrollbar" style="max-height: 400px; overflow-y: auto; padding: 1.5rem;">
                        <?php if (!$relatedSessions->isEmpty()): ?>
                            <div class="sessions-wrapper">
                                <?php foreach ($relatedSessions as $session): ?>
                                    <a href="<?= $this->Url->build(['action' => 'edit', $session->id]) ?>"
                                        class="session-item text-decoration-none <?= $session->id == $show->id ? 'active-session' : '' ?>"
                                        data-session-id="<?= $session->id ?>"
                                        onclick="return handleSessionSwitch(event, this)">
                                        <span class="session-time">
                                            <?= $session->show_time instanceof \DateTimeInterface ? $session->show_time->format('h:i A') : (new \Cake\I18n\FrozenTime($session->show_time))->format('h:i A') ?>
                                        </span>
                                        <span class="session-hall">
                                            <?= $session->has('hall') ? h($session->hall->hall_type) : 'Standard Hall' ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4 opacity-50">
                                <i class="fas fa-calendar-alt fa-2x mb-2 text-danger"></i>
                                <p class="small mb-0 text-white-50">No other sessions for this movie on this date.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

                <!-- Layout Sync (Master Control) -->
                <div class="card bg-info bg-opacity-10 border border-info border-opacity-25 shadow-sm mb-4"
                    style="border-radius: 20px;">
                    <div class="card-body p-4 text-center">
                        <h6 class="text-info fw-bold small text-uppercase mb-3">
                            <i class="fas fa-sync-alt me-2"></i>Layout Management
                        </h6>
                        <p class="small text-white opacity-75 mb-3">
                            Added or removed seats in the **Master Hall Template**? Use this to refresh this specific session's layout.
                        </p>
                        <?php 
                            $syncMsg = __('Are you sure? This will wipe the current seat layout for this session and re-clone it from the Master Hall Template. Existing bookings will block this sync to prevent data loss.');
                        ?>
                        <?= $this->Form->postLink(
                            '<i class="fas fa-magic me-2"></i>SYNC WITH MASTER',
                            ['action' => 'syncWithMaster', $show->id],
                            [
                                'escape' => false,
                                'class' => 'btn btn-info btn-sm w-100 rounded-pill fw-bold text-dark pulsate-slow',
                                'onclick' => "return confirmSync(event, this, '" . h($syncMsg) . "')"
                            ]
                        ) ?>
                    </div>
                </div>

                <!-- Helpful Tip -->
                <div class="card bg-danger bg-opacity-10 border border-danger border-opacity-25 shadow-sm"
                    style="border-radius: 20px;">
                    <div class="card-body p-4">
                        <h6 class="text-danger fw-bold small text-uppercase mb-3"><i class="fas fa-lightbulb me-2"></i>Admin
                            Tip</h6>
                        <p class="small text-white opacity-75 mb-0">
                            To edit the hall for all sessions on this day, use the **Quick Switcher** above to rapidly
                            update each session record.
                        </p>
                    </div>
                </div>

                <?php 
                    $delFormId = 'delete-session-' . $show->id;
                    $delMsg = __('Are you sure you want to delete this session?');
                ?>
                <?= $this->Form->create(null, ['url' => ['action' => 'delete', $show->id, '?' => ['view' => $this->request->getQuery('view')]], 'id' => $delFormId, 'style' => 'display:none;']) ?>
                <?= $this->Form->end() ?>
                
                <a href="#" 
                   class="btn btn-outline-danger w-100 mt-3 fw-bold shadow-sm" 
                   style="border-radius: 50px;"
                   onclick="return confirmDelete(event, '<?= $delFormId ?>', '<?= h($delMsg) ?>')">
                    <i class="fas fa-trash-alt me-2"></i><?= __('Delete This Session') ?>
                </a>
            </div> <!-- End premium-sidebar-sticky -->
        </div> <!-- End col-lg-4 -->
    </div> <!-- End row g-4 -->
</div> <!-- End container -->

<style>
    .form-control:focus,
    .form-select:focus {
        background-color: #000;
        border-color: #dc3545;
        box-shadow: 0 0 15px rgba(220, 53, 69, 0.2);
    }

    .x-small {
        font-size: 0.65rem;
    }

    .tracking-wider {
        letter-spacing: 3px;
    }

    .tracking-wide {
        letter-spacing: 1.5px;
    }

    .flex-grow-2 {
        flex-grow: 2;
    }

    .hover-white:hover {
        color: white !important;
    }

    .rounded-4 {
        border-radius: 1rem;
    }

    .session-item {
        background: rgba(229, 9, 20, 0.1);
        border: 1px solid rgba(229, 9, 20, 0.2);
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.85rem;
        display: flex;
        flex-direction: column;
        gap: 4px;
        color: #ffffff;
        min-width: 100px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .session-item:hover {
        background: rgba(229, 9, 20, 0.2);
        border-color: rgba(229, 9, 20, 0.5);
        transform: translateY(-3px);
    }

    .active-session {
        background: rgba(229, 9, 20, 0.3) !important;
        border-color: #e50914 !important;
        box-shadow: 0 0 15px rgba(229, 9, 20, 0.2);
    }

    .session-time {
        font-weight: 800;
        color: #ffffff;
        font-size: 0.9rem;
    }

    .session-hall {
        font-size: 0.7rem;
        color: #ffcccc;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 6px;
        margin-top: 2px;
    }

    .sessions-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.2);
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e50914;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #f40a16;
    }

    /* Premium Feel */
    .sidebar-ref-card:hover {
        transform: translateY(-5px);
        border-color: rgba(229, 9, 20, 0.3) !important;
    }

    .pulse-hover:hover {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }

        100% {
            opacity: 1;
        }
    }
    /* Premium Sidebar Sticky */
    .premium-sidebar-sticky {
        position: -webkit-sticky;
        position: sticky;
        top: 100px;
        z-index: 10;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    @media (max-width: 991.98px) {
        .premium-sidebar-sticky {
            position: static !important;
            margin-top: 2rem;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const posterInput = document.getElementById('movie-poster-input');
    const posterPreview = document.getElementById('movie-poster-preview');
    const placeholder = document.getElementById('poster-placeholder');

    if (posterInput) {
        posterInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    posterPreview.src = e.target.result;
                    posterPreview.classList.remove('d-none');
                    if (placeholder) {
                        placeholder.classList.add('d-none');
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Save & Switch Handler
    window.handleSessionSwitch = function(e, element) {
        const sessionId = element.getAttribute('data-session-id');
        const currentId = '<?= $show->id ?>';
        
        if (sessionId === currentId) return false;
        
        e.preventDefault();
        
        // Set the hidden field and submit the form
        document.getElementById('redirect-to-session').value = sessionId;
        element.closest('.container').querySelector('form').submit();
        
        return false;
    };

    window.confirmSync = function(e, element, message) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Master Sync?',
            text: message,
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#0dcaf0', // Info blue
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Sync Now!',
            background: '#000000',
            color: '#ffffff',
            customClass: { 
                popup: 'my-system-dialog border border-info border-opacity-25' 
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // postLink generates a hidden form and submits it. 
                // We need to trigger that form. 
                // Usually it's the next sibling or we can use the element's data-submit attributes if any,
                // but CakePHP's postLink usually handles this via a hidden form.
                // The easiest way is to let the auto-generated JS handle it by manually clicking the inner invisible submit or similar,
                // BUT since we prevented default, we must submit the form ourselves.
                
                const form = element.nextElementSibling; // postLink usually places form immediately after or uses a global map
                // In CakePHP 4+, postLink creates a form and injects JS.
                // Let's use the most reliable way:
                const formName = element.getAttribute('data-form');
                if (formName) {
                    document[formName].submit();
                } else {
                    // Fallback: search for the hidden form nearby
                    const form = element.previousElementSibling || element.nextElementSibling;
                    if (form && form.tagName === 'FORM') {
                        form.submit();
                    }
                }
            }
        });
        return false;
    };
});
</script>