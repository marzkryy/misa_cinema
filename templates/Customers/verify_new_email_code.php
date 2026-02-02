<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card bg-dark text-white shadow-lg border-0 rounded-4">
                <div class="card-header bg-danger text-center py-4 rounded-top-4">
                    <h3 class="mb-0 fw-bold">Step 3: Final Verification</h3>
                    <p class="mb-0 text-light opacity-75 small">Verify your new email address</p>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-check-double fa-4x text-danger mb-3"></i>
                        <p>We've sent a 4-digit code to your NEW email:<br>
                           <strong class="text-danger"><?= h($customer->temp_email) ?></strong></p>
                    </div>

                    <?= $this->Form->create(null) ?>
                    <div class="mb-4">
                        <label class="form-label text-white-50 small text-uppercase fw-bold">Enter Verification Code</label>
                        <?= $this->Form->control('code', [
                            'label' => false,
                            'class' => 'form-control form-control-lg bg-black text-white border-secondary text-center fw-bold',
                            'placeholder' => '0000',
                            'required' => true,
                            'maxlength' => 4,
                            'style' => 'letter-spacing: 15px; font-size: 2rem;'
                        ]) ?>
                    </div>
                    
                    <div class="d-grid mb-3">
                        <?= $this->Form->button(__('Confirm & Update Email'), ['class' => 'btn btn-danger btn-lg fw-bold py-3 shadow']) ?>
                    </div>
                    <?= $this->Form->end() ?>

                    <div class="text-center">
                        <p class="text-white-50 small mb-0">Entered wrong email? 
                            <a href="<?= $this->Url->build(['action' => 'enterNewEmail', $customer->id]) ?>" class="text-danger fw-bold text-decoration-none">
                                <i class="fas fa-undo me-1"></i> Change here
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
