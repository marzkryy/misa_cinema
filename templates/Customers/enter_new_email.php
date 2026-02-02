<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card bg-dark text-white shadow-lg border-0 rounded-4">
                <div class="card-header bg-danger text-center py-4 rounded-top-4">
                    <h3 class="mb-0 fw-bold">Step 2: Enter New Email</h3>
                    <p class="mb-0 text-light opacity-75 small">Identity confirmed. Now enter your new address.</p>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-pen-nib fa-4x text-danger mb-3"></i>
                    </div>

                    <?= $this->Form->create(null) ?>
                    <div class="mb-4">
                        <label class="form-label text-white-50 small text-uppercase fw-bold">New Email Address</label>
                        <?= $this->Form->control('new_email', [
                            'type' => 'email',
                            'label' => false,
                            'class' => 'form-control form-control-lg bg-black text-white border-secondary py-3',
                            'placeholder' => 'name@example.com',
                            'required' => true
                        ]) ?>
                    </div>
                    
                    <div class="d-grid">
                        <?= $this->Form->button(__('Send Verification to New Email'), ['class' => 'btn btn-danger btn-lg fw-bold py-3 shadow']) ?>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
