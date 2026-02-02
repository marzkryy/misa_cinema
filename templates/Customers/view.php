<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Customer $customer
 */
$this->assign('title', 'My Profile - ' . h($customer->name));
?>



<div class="container mt-5">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <?php 
            $authUser = $this->request->getSession()->read('Auth.User');
            $isAdmin = isset($authUser['role']) && $authUser['role'] === 'admin';
            
            if ($isAdmin): ?>
                <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= h($customer->name) ?></h3>
            <?php else: ?>
                <h3 class="text-white fw-bold text-uppercase tracking-wider border-start border-4 border-danger ps-3 mb-0"><?= __('MY PROFILE') ?></h3>
            <?php endif; ?>
        </div>
        <div class="col-md-6 d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
            <?php 
            $authUser = $this->request->getSession()->read('Auth.User');
            $isAdmin = isset($authUser['role']) && $authUser['role'] === 'admin';
            
            if ($isAdmin): ?>
                <?= $this->Html->link('<i class="fas fa-arrow-left me-2"></i>' . __('Back to List'), ['action' => 'index'], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]) ?>
            <?php else: ?>
                <?= $this->Html->link('<i class="fas fa-home me-2"></i>' . __('Back to Home'), ['controller' => 'Pages', 'action' => 'home'], ['class' => 'btn btn-outline-light rounded-pill px-4', 'escape' => false, 'escapeTitle' => false]) ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-dark text-white shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-danger text-center py-4" style="border-radius: 15px 15px 0 0;">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-4x"></i>
                    </div>
                    <h2 class="mb-0 fw-bold"><?= h($customer->name) ?></h2>
                    <p class="mb-0 text-light opacity-75">MisaCinema Member since
                        <?= $customer->created->format('M Y') ?></p>
                </div>
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="text-white small text-uppercase fw-bold d-block mb-1"><i class="fas fa-user me-2 text-danger"></i>Full Name</label>
                            <div class="h5 border-bottom border-secondary pb-2"><?= h($customer->name) ?></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="text-white small text-uppercase fw-bold d-block mb-1"><i class="fas fa-envelope me-2 text-danger"></i>Email Address</label>
                            <div class="h5 border-bottom border-secondary pb-2"><?= h($customer->email) ?></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="text-white small text-uppercase fw-bold d-block mb-1"><i class="fas fa-phone me-2 text-danger"></i>Phone Number</label>
                            <div class="h5 border-bottom border-secondary pb-2"><?= h($customer->phone) ?></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="text-white small text-uppercase fw-bold d-block mb-1"><i class="fas fa-id-card me-2 text-danger"></i>Membership Status</label>
                            <div class="h5 border-bottom border-secondary pb-2">
                                <span class="badge bg-danger">Active Member</span>
                            </div>
                        </div>
                    </div>

                    <?php 
                    $session = $this->request->getSession();
                    $currentUser = $session->read('Auth.User');
                    // Only show controls if the logged-in user is the owner of this profile
                    if ($currentUser && isset($currentUser['id']) && (int)$currentUser['id'] === $customer->id): 
                    ?>
                    <div class="d-grid gap-2 mt-4">
                        <?= $this->Html->link(__('EDIT PROFILE'), ['action' => 'edit', $customer->id], ['class' => 'btn btn-outline-danger fw-bold py-3 rounded-pill']) ?>
                        <a href="<?= $this->Url->build('/bookings') ?>" class="btn btn-danger-custom fw-bold py-3 rounded-pill">VIEW MY BOOKINGS</a>
                    </div>
                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>
</div>