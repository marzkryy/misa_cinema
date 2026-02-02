<?php
/**
 * @var \App\View\AppView $this
 * @var array|null $user
 */
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card bg-black border-0 shadow-lg rounded-4 overflow-hidden" style="border: 2px solid #e50914 !important;">
                <div class="card-header bg-danger py-3 text-white">
                    <h3 class="fw-bold mb-0 text-uppercase d-flex align-items-center">
                        <i class="fas fa-headset me-3"></i>Contact Us / Report Issue
                    </h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <p class="text-white-50 mb-4">Have a question or encountered a bug? Fill out the form below and our team will get back to you shortly.</p>
                    
                    <?= $this->Form->create(null, ['class' => 'needs-validation', 'novalidate' => true]) ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-white fw-bold">Your Name</label>
                                <?= $this->Form->control('name', [
                                    'label' => false,
                                    'class' => 'form-control bg-dark text-white border-secondary',
                                    'value' => $user['name'] ?? '',
                                    'placeholder' => 'Enter your name',
                                    'required' => true
                                ]) ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white fw-bold">Email Address</label>
                                <?= $this->Form->control('email', [
                                    'label' => false,
                                    'class' => 'form-control bg-dark text-white border-secondary',
                                    'value' => $user['email'] ?? '',
                                    'placeholder' => 'Enter your email',
                                    'required' => true,
                                    'type' => 'email'
                                ]) ?>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label text-white fw-bold">Subject / Issue Type</label>
                                <?= $this->Form->select('subject', [
                                    'General Inquiry' => 'General Inquiry',
                                    'Bug Report' => 'Bug Report / Error',
                                    'Complaint' => 'Complaint',
                                    'Feedback' => 'Suggestion / Feedback',
                                    'Other' => 'Other'
                                ], [
                                    'class' => 'form-select bg-dark text-white border-secondary',
                                    'empty' => 'Select a subject...',
                                    'required' => true
                                ]) ?>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label text-white fw-bold">Message</label>
                                <?= $this->Form->textarea('message', [
                                    'class' => 'form-control bg-dark text-white border-secondary',
                                    'rows' => 5,
                                    'placeholder' => 'Describe your issue or inquiry here...',
                                    'required' => true
                                ]) ?>
                            </div>
                            
                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-danger btn-lg px-5 rounded-pill shadow-lg fw-bold">
                                    <i class="fas fa-paper-plane me-2"></i>SEND REPORT
                                </button>
                            </div>
                        </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
