<div class="container py-5">
    <div class="row g-4">
        <!-- Main Form Column -->
        <div class="col-lg-8">
            <div class="card bg-dark text-white shadow-lg border-0 overflow-hidden" style="border-radius: 20px;">
                <div class="card-header bg-danger py-4 text-center border-0">
                    <h3 class="fw-bold mb-0 text-uppercase tracking-wider">
                        <i class="fas fa-calendar-plus me-2"></i><?= __('Add Shows') ?>
                    </h3>
                    <p class="small opacity-75 mb-0 mt-1">You Can Add Multiple Shows In Minutes</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <?= $this->Form->create($show, ['type' => 'file', 'id' => 'multi-sched-form']) ?>
                    
                    <!-- Movie Section -->
                    <div class="mb-5 pb-4 border-bottom border-secondary border-opacity-25">
                        <h5 class="text-danger fw-bold mb-4 tracking-wide"><i class="fas fa-film me-2"></i>MOVIE INFORMATION</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-12 mb-2">
                                <label class="form-label small text-white fw-bold text-uppercase">Pick Existing Movie (Faster)</label>
                                <select id="movie-selector" class="form-select bg-black text-white border-secondary border-opacity-50 py-2">
                                    <option value=""><?= __('-- Create New or Select --') ?></option>
                                    <?php foreach ($existingMovies as $movie): ?>
                                    <option value="<?= h($movie->show_title) ?>" 
                                            data-genre="<?= h($movie->genre) ?>" 
                                            data-avatar="<?= h($movie->avatar) ?>"
                                            data-avatar-dir="<?= h($movie->avatar_dir) ?>"
                                            data-duration="<?= h($movie->duration) ?>">
                                            <?= h($movie->show_title) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="col-md-5">
                                <?= $this->Form->control('show_title', [
                                    'class' => 'form-control bg-black text-white border-secondary border-opacity-50 py-2',
                                    'label' => ['class' => 'form-label small text-white fw-bold text-uppercase', 'text' => 'Movie Title'],
                                    'id' => 'field-title',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $this->Form->control('genre', [
                                    'class' => 'form-control bg-black text-white border-secondary border-opacity-50 py-2',
                                    'label' => ['class' => 'form-label small text-white fw-bold text-uppercase', 'text' => 'Genre'],
                                    'id' => 'field-genre',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-3">
                                <?= $this->Form->control('duration', [
                                    'type' => 'number',
                                    'class' => 'form-control bg-black text-white border-secondary border-opacity-50 py-2',
                                    'label' => ['class' => 'form-label small text-white fw-bold text-uppercase', 'text' => 'Duration (min)'],
                                    'id' => 'field-duration',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small text-white fw-bold text-uppercase">Movie Poster</label>
                                
                                <!-- Checkbox to toggle between existing and new poster -->
                                <div id="poster-toggle-container" class="form-check mb-2 d-none">
                                    <input class="form-check-input bg-dark border-secondary" type="checkbox" id="use-existing-poster" checked>
                                    <label class="form-check-label text-white small" for="use-existing-poster">
                                        Use existing poster from database
                                    </label>
                                </div>

                                <!-- File Input Container -->
                                <div id="new-poster-input-container">
                                    <?= $this->Form->file('avatar', ['class' => 'form-control bg-black text-white border-secondary border-opacity-50 py-2', 'id' => 'field-avatar']) ?>
                                    <div class="form-text small text-white-50 mt-2">
                                        <i class="fas fa-info-circle me-1"></i> Upload a new poster (JPG, PNG).
                                    </div>
                                </div>

                                <?= $this->Form->hidden('existing_avatar', ['id' => 'field-existing-avatar']) ?>
                                
                                <div id="poster-preview-container" class="mt-3 d-none">
                                    <div class="d-flex align-items-center bg-black bg-opacity-25 p-3 rounded border border-secondary border-opacity-25">
                                        <img id="poster-preview" src="" class="rounded shadow-sm" style="height: 80px; width: 60px; object-fit: cover;">
                                        <div class="ms-3">
                                            <span class="d-block small text-white fw-bold mb-1">Selected Movie Poster</span>
                                            <span class="badge bg-success bg-opacity-75 text-white border border-success border-opacity-25">Matched</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Section -->
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="text-danger fw-bold mb-0 tracking-wide"><i class="fas fa-clock me-2"></i>INSERT SHOWDATE AND SHOWTIME</h5>
                            <button type="button" id="add-date-btn" class="btn btn-sm btn-outline-danger px-3 rounded-pill fw-bold">
                                <i class="fas fa-plus me-1"></i>ADD NEW DATE
                            </button>
                        </div>

                        <div id="schedule-container">
                            <?php 
                            $submittedSchedule = $this->request->getData('schedule');
                            if (!empty($submittedSchedule) && is_array($submittedSchedule)): 
                                foreach ($submittedSchedule as $index => $dateGroup):
                                    $dateVal = $dateGroup['show_date'] ?? '';
                            ?>
                                <!-- Rendered Date Group from Submission -->
                                <div class="date-group bg-black bg-opacity-25 border border-secondary border-opacity-10 rounded-4 p-4 mb-3 position-relative">
                                    <?php if ($index > 0): ?>
                                        <button type="button" class="btn btn-link remove-date-group p-0"><i class="fas fa-times-circle"></i></button>
                                    <?php endif; ?>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label x-small text-white fw-bold text-uppercase opacity-75">Date</label>
                                            <input type="date" name="schedule[<?= $index ?>][show_date]" value="<?= h($dateVal) ?>" class="form-control bg-black text-white border-secondary border-opacity-50 py-2" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label x-small text-white fw-bold text-uppercase opacity-75">Sessions & Halls</label>
                                            <div class="sessions-container d-flex flex-column gap-2">
                                                <?php 
                                                $sessionData = $dateGroup['sessions'] ?? [[]];
                                                foreach ($sessionData as $sIndex => $session):
                                                    $timeVal = $session['show_time'] ?? '';
                                                    $selectedHall = $session['hall_id'] ?? '';
                                                ?>
                                                <div class="session-row d-flex gap-2">
                                                    <input type="time" name="schedule[<?= $index ?>][sessions][<?= $sIndex ?>][show_time]" value="<?= h($timeVal) ?>" class="form-control bg-black text-white border-secondary border-opacity-50 py-2" style="width: 130px;" required>
                                                    <select name="schedule[<?= $index ?>][sessions][<?= $sIndex ?>][hall_id]" class="form-select bg-black text-white border-secondary border-opacity-50 py-2" required>
                                                        <option value=""><?= __('-- Select Hall --') ?></option>
                                                        <?php foreach ($halls as $hallId => $type): ?>
                                                            <option value="<?= $hallId ?>" <?= $hallId == $selectedHall ? 'selected' : '' ?>><?= h($type) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php if ($sIndex === 0): ?>
                                                        <div class="d-flex gap-1">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary add-session-btn px-3" title="Add Hall">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-info sync-time-btn px-3" title="Sync this time to all halls below">
                                                                <i class="fas fa-sync-alt"></i>
                                                            </button>
                                                        </div>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-link remove-session-btn p-0 text-danger text-decoration-none px-2" style="font-size: 1.2rem;">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            else: 
                            ?>
                                <!-- Initial Empty Date Group -->
                                <div class="date-group bg-black bg-opacity-25 border border-secondary border-opacity-10 rounded-4 p-4 mb-3 position-relative">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label x-small text-white fw-bold text-uppercase opacity-75">Date</label>
                                            <input type="date" name="schedule[0][show_date]" class="form-control bg-black text-white border-secondary border-opacity-50 py-2" required>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label x-small text-white fw-bold text-uppercase opacity-75">Sessions & Halls</label>
                                            <div class="sessions-container d-flex flex-column gap-2">
                                                <div class="session-row d-flex gap-2">
                                                    <input type="time" name="schedule[0][sessions][0][show_time]" class="form-control bg-black text-white border-secondary border-opacity-50 py-2" style="width: 130px;" required>
                                                    <select name="schedule[0][sessions][0][hall_id]" class="form-select bg-black text-white border-secondary border-opacity-50 py-2" required>
                                                        <option value=""><?= __('-- Select Hall --') ?></option>
                                                        <?php foreach ($halls as $id => $type): ?>
                                                            <option value="<?= $id ?>"><?= h($type) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <div class="d-flex gap-1">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary add-session-btn px-3" title="Add Hall">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-info sync-time-btn px-3" title="Sync this time to all halls below">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?= $this->Form->hidden('status', ['value' => 1]) ?>

                    <div class="text-center pt-3">
                        <?= $this->Form->button(__('PROCESS ALL SESSIONS'), ['class' => 'btn btn-danger btn-lg px-5 fw-bold shadow-lg w-100', 'style' => 'border-radius: 50px;']) ?>
                        <div class="mt-4">
                            <?= $this->Html->link(__('Back to Schedule'), ['action' => 'index'], ['class' => 'btn btn-outline-secondary rounded-pill px-4 fw-bold hover-white']) ?>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>

        <!-- Sidebar Info Column -->
        <div class="col-lg-4">
            <!-- Schedule Reference Panel -->
            <div class="card bg-black border border-secondary border-opacity-10 shadow-lg sticky-top sidebar-ref-card" style="border-radius: 20px; top: 6rem; transition: transform 0.3s ease;">
                <div class="card-header bg-dark py-3 border-0 d-flex flex-column gap-3">
                    <h6 class="mb-0 text-white fw-bold"><i class="fas fa-info-circle text-danger me-2"></i>SCHEDULE REFERENCE</h6>
                    <!-- Tab Navigation -->
                    <div class="nav nav-pills nav-justified bg-black bg-opacity-50 p-1 rounded-pill border border-secondary border-opacity-25" id="nav-tab" role="tablist">
                        <button class="nav-link active rounded-pill small fw-bold py-2" id="nav-movie-tab" data-bs-toggle="tab" data-bs-target="#nav-movie" type="button" role="tab" aria-selected="true">BY MOVIE</button>
                        <button class="nav-link rounded-pill small fw-bold py-2" id="nav-hall-tab" data-bs-toggle="tab" data-bs-target="#nav-hall" type="button" role="tab" aria-selected="false">DAILY HALL VIEW</button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="nav-tabContent">
                        <!-- Tab 1: By Movie -->
                        <div class="tab-pane fade show active" id="nav-movie" role="tabpanel" aria-labelledby="nav-movie-tab">
                            <div id="history-container" class="custom-scrollbar" style="max-height: 65vh; overflow-y: auto; padding: 1.5rem;">
                                <div class="text-center py-5">
                                    <i class="fas fa-search fa-3x mb-3 text-danger opacity-75"></i>
                                    <p class="small mb-0 text-white-50">Select a movie to view its<br>existing screening timeline</p>
                                </div>
                            </div>
                        </div>
                        <!-- Tab 2: Daily Hall View -->
                        <div class="tab-pane fade" id="nav-hall" role="tabpanel" aria-labelledby="nav-hall-tab">
                            <div id="global-hall-container" class="custom-scrollbar" style="max-height: 65vh; overflow-y: auto; padding: 1.5rem;">
                                <?php if (!empty($globalDailyData)): ?>
                                    <?php foreach ($globalDailyData as $date => $hallsSchedule): ?>
                                        <div class="mb-5">
                                            <h6 class="small fw-bold text-danger text-uppercase mb-3 border-bottom border-danger border-opacity-25 pb-2"><?= $date ?></h6>
                                            <?php foreach ($hallsSchedule as $hallName => $sessions): ?>
                                                <div class="mb-4 bg-black bg-opacity-25 p-3 rounded-3 border border-secondary border-opacity-10 position-relative hall-block">
                                                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger x-small ms-4 mt-2"><?= $hallName ?></span>
                                                    <div class="sessions-wrapper mt-3 pt-1">
                                                        <?php foreach ($sessions as $s): ?>
                                                            <div class="session-item global-session border-opacity-10" style="min-width: 130px;">
                                                                <span class="session-time" style="font-size: 0.85rem;"><?= $s['time'] ?></span>
                                                                <span class="session-end-time text-white-50 x-small fw-bold mb-1">Ends at <?= $s['end'] ?></span>
                                                                <span class="session-movie-title text-danger fw-bold lh-sm mt-1" style="font-size: 0.75rem;"><?= h($s['title']) ?></span>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-5 opacity-50">
                                        <p class="small">No shows scheduled yet.</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus {
        background-color: #000;
        border-color: #dc3545;
        box-shadow: 0 0 15px rgba(220, 53, 69, 0.2);
    }
    .x-small { font-size: 0.65rem; }
    .tracking-wider { letter-spacing: 3px; }
    .tracking-wide { letter-spacing: 1.5px; }
    .hover-white:hover { color: white !important; }
    .rounded-4 { border-radius: 1rem; }
    
    .remove-date-group {
        position: absolute;
        top: 1rem;
        right: 1rem;
        opacity: 0.3;
        transition: opacity 0.2s;
    }
    .remove-date-group:hover { opacity: 1; color: #dc3545; }
    
    .session-item {
        background: rgba(229, 9, 20, 0.15);
        border: 1px solid rgba(229, 9, 20, 0.3);
        padding: 10px 14px;
        border-radius: 10px;
        font-size: 0.9rem;
        display: flex;
        flex-direction: column;
        gap: 4px;
        color: #ffffff;
        min-width: 110px;
        text-align: center;
    }
    .session-time {
        font-weight: 800;
        color: #ffffff;
        font-size: 1rem;
    }
    .session-hall {
        font-size: 0.75rem;
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
        gap: 8px;
    }
    .remove-session-btn:hover { opacity: 1; color: #dc3545; }

    /* Tabs Styling */
    #nav-tab .nav-link {
        color: #ffffff;
        opacity: 0.5;
        border: none;
        transition: all 0.3s ease;
    }
    #nav-tab .nav-link.active {
        background-color: #dc3545 !important;
        color: #ffffff !important;
        opacity: 1;
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }
    #nav-tab .nav-link:hover:not(.active) {
        opacity: 0.8;
        background: rgba(255, 255, 255, 0.05);
    }

    .session-end-time {
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 4px;
        letter-spacing: 0.5px;
    }

    .hall-block {
        transition: border-color 0.3s;
    }
    .hall-block:hover {
        border-color: rgba(220, 53, 69, 0.3) !important;
    }
    
    .global-session {
        background: rgba(255, 255, 255, 0.03) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    .global-session:hover {
        background: rgba(220, 53, 69, 0.1) !important;
        border-color: rgba(220, 53, 69, 0.3) !important;
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

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const historyData = <?= json_encode($historyGrouped) ?>;
    const hallsOptions = `<?php foreach ($halls as $id => $type): ?><option value="<?= $id ?>"><?= h($type) ?></option><?php endforeach; ?>`;
    const movieSelector = document.getElementById('movie-selector');
    const scheduleContainer = document.getElementById('schedule-container');
    const addDateBtn = document.getElementById('add-date-btn');
    const historyContainer = document.getElementById('history-container');
    
    // Poster Toggle Elements
    const posterToggleContainer = document.getElementById('poster-toggle-container');
    const useExistingPosterCheckbox = document.getElementById('use-existing-poster');
    const newPosterInputContainer = document.getElementById('new-poster-input-container');
    const fieldAvatar = document.getElementById('field-avatar');
    const fieldExistingAvatar = document.getElementById('field-existing-avatar');
    const posterPreviewContainer = document.getElementById('poster-preview-container');

    let dateIndex = document.querySelectorAll('.date-group').length;

    // --- AUTO-FILL & HISTORY LOGIC ---
    movieSelector.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];
        const title = this.value;
        
        if (title) {
            document.getElementById('field-title').value = title;
            document.getElementById('field-genre').value = option.dataset.genre;
            
            // Poster Logic
            const avatar = option.dataset.avatar;
            if (avatar) {
                // Movie has an existing avatar
                posterToggleContainer.classList.remove('d-none');
                useExistingPosterCheckbox.checked = true;
                
                // Hide file input, Show Preview
                newPosterInputContainer.classList.add('d-none');
                fieldAvatar.required = false; // Not required since updating existing
                
                fieldExistingAvatar.value = avatar;
                document.getElementById('poster-preview').src = '<?= $this->Url->build('/img/shows/') ?>' + avatar;
                posterPreviewContainer.classList.remove('d-none');
            } else {
                // No existing avatar, force upload
                posterToggleContainer.classList.add('d-none');
                newPosterInputContainer.classList.remove('d-none');
                posterPreviewContainer.classList.add('d-none');
                fieldExistingAvatar.value = '';
            }

            // Auto-fill duration if available
            const duration = option.dataset.duration;
            if (duration) {
                document.getElementById('field-duration').value = duration;
            } else {
                 document.getElementById('field-duration').value = 120; // Default
            }
            
            // Render History
            renderHistory(title);
        } else {
            // Reset
            document.getElementById('field-title').value = '';
            document.getElementById('field-genre').value = '';
            
            posterToggleContainer.classList.add('d-none');
            newPosterInputContainer.classList.remove('d-none'); // Show input by default
            posterPreviewContainer.classList.add('d-none');
            fieldExistingAvatar.value = '';
            
            historyContainer.innerHTML = '<div class="text-center py-4 opacity-50"><i class="fas fa-search fa-3x mb-3 text-danger"></i><p class="small mb-0">Select a movie to view its<br>existing screening timeline</p></div>';
        }
    });

    // Checkbox Toggle Logic
    useExistingPosterCheckbox.addEventListener('change', function() {
        if (this.checked) {
            // "Use Existing" TICKED
            newPosterInputContainer.classList.add('d-none');
            fieldAvatar.value = ''; // Clear any selected file
            fieldAvatar.required = false;
            
            posterPreviewContainer.classList.remove('d-none');
            
            // Restore the existing avatar value from select option
            const option = movieSelector.options[movieSelector.selectedIndex];
            if (option) {
                fieldExistingAvatar.value = option.dataset.avatar;
            }
        } else {
            // "Use Existing" UNTICKED -> Must upload new
            newPosterInputContainer.classList.remove('d-none');
            fieldAvatar.required = true; // Make it required
            
            posterPreviewContainer.classList.add('d-none');
            fieldExistingAvatar.value = ''; // Clear existing reference so controller knows strictly to look for upload
        }
    });

    function renderHistory(title) {
        if (!historyData[title]) {
            historyContainer.innerHTML = '<div class="alert alert-info bg-dark border-0 text-white small">No previous screenings found for this movie.</div>';
            return;
        }

        let html = '<h6 class="small fw-bold text-danger text-uppercase mb-3 mt-1">Existing Schedule:</h6>';
        for (const [date, sessions] of Object.entries(historyData[title])) {
            html += `<div class="mb-3 pb-3 border-bottom border-secondary border-opacity-25">
                        <div class="fw-bold small text-white opacity-75 mb-2">${date}</div>
                        <div class="sessions-wrapper">
                            ${sessions.map(s => `
                                <div class="session-item" style="min-width: 125px;">
                                    <span class="session-time">${s.time}</span>
                                    <span class="session-end-time text-white-50 x-small fw-bold mb-1">Ends ${s.end}</span>
                                    <span class="session-hall">${s.hall}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>`;
        }
        historyContainer.innerHTML = html;
    }

    // --- DYNAMIC BLOCKS LOGIC ---
    addDateBtn.addEventListener('click', function() {
        const div = document.createElement('div');
        div.className = 'date-group bg-black bg-opacity-25 border border-secondary border-opacity-10 rounded-4 p-4 mb-3 position-relative';
        div.innerHTML = `
            <button type="button" class="btn btn-link remove-date-group p-0"><i class="fas fa-times-circle"></i></button>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label x-small text-white fw-bold text-uppercase opacity-75">Date</label>
                    <input type="date" name="schedule[${dateIndex}][show_date]" class="form-control bg-black text-white border-secondary border-opacity-50 py-2" required>
                </div>
                <div class="col-md-8">
                    <label class="form-label x-small text-white fw-bold text-uppercase opacity-75">Sessions & Halls</label>
                    <div class="sessions-container d-flex flex-column gap-2">
                        <div class="session-row d-flex gap-2">
                            <input type="time" name="schedule[${dateIndex}][sessions][0][show_time]" class="form-control bg-black text-white border-secondary border-opacity-50 py-2" style="width: 130px;" required>
                            <select name="schedule[${dateIndex}][sessions][0][hall_id]" class="form-select bg-black text-white border-secondary border-opacity-50 py-2" required>
                                <option value="">-- Select Hall --</option>
                                ${hallsOptions}
                            </select>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary add-session-btn px-3" title="Add Hall">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info sync-time-btn px-3" title="Sync this time to all halls below">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        scheduleContainer.appendChild(div);
        dateIndex++;
    });

    scheduleContainer.addEventListener('click', function(e) {
        // Add Session
        if (e.target.closest('.add-session-btn')) {
            const container = e.target.closest('.sessions-container');
            const group = e.target.closest('.date-group');
            const dateBlockIndex = Array.from(scheduleContainer.children).indexOf(group);
            const sessionIndex = container.querySelectorAll('.session-row').length;
            
            const row = document.createElement('div');
            row.className = 'session-row d-flex gap-2';
            row.innerHTML = `
                <input type="time" name="schedule[${dateBlockIndex}][sessions][${sessionIndex}][show_time]" class="form-control bg-black text-white border-secondary border-opacity-50 py-2" style="width: 130px;" required>
                <select name="schedule[${dateBlockIndex}][sessions][${sessionIndex}][hall_id]" class="form-select bg-black text-white border-secondary border-opacity-50 py-2" required>
                    <option value="">-- Select Hall --</option>
                    ${hallsOptions}
                </select>
                <button type="button" class="btn btn-link remove-session-btn p-0 text-danger text-decoration-none px-2" style="font-size: 1.2rem;">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(row);
        }
        
        // Remove Date Group
        if (e.target.closest('.remove-date-group')) {
            e.target.closest('.date-group').remove();
        }
        
        // Remove Session
        if (e.target.closest('.remove-session-btn')) {
            e.target.closest('.session-row').remove();
        }

        // Sync Time
        if (e.target.closest('.sync-time-btn')) {
            const container = e.target.closest('.sessions-container');
            const firstTimeInput = container.querySelector('.session-row input[type="time"]');
            if (firstTimeInput && firstTimeInput.value) {
                const timeToSync = firstTimeInput.value;
                container.querySelectorAll('.session-row input[type="time"]').forEach((input, idx) => {
                    if (idx > 0) { // Don't sync the first one (it's the source)
                        input.value = timeToSync;
                    }
                });
            }
        }
    });

    // --- INITIALIZATION (ON LOAD) ---
    // Check if we have an initial state (form re-rendered after failure)
    const initialTitle = document.getElementById('field-title').value;
    const initialExistingAvatar = fieldExistingAvatar.value;

    if (initialTitle) {
        // 1. Find and set the selector to match the title
        for (let i = 0; i < movieSelector.options.length; i++) {
            if (movieSelector.options[i].value === initialTitle) {
                movieSelector.selectedIndex = i;
                break;
            }
        }
        
        // 2. Render schedule history for this title
        renderHistory(initialTitle);
    }

    // 3. Robust Poster Re-initialization (Independent of Title)
    if (initialExistingAvatar) {
        // Show poster controls
        posterToggleContainer.classList.remove('d-none');
        useExistingPosterCheckbox.checked = true;
        
        // Layout adjustments
        newPosterInputContainer.classList.add('d-none');
        fieldAvatar.required = false;
        
        // Set image source and show container
        document.getElementById('poster-preview').src = '<?= $this->Url->build('/img/shows/') ?>' + initialExistingAvatar;
        posterPreviewContainer.classList.remove('d-none');
    }

    // Live preview for new poster upload
    fieldAvatar.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('poster-preview').src = e.target.result;
                posterPreviewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>