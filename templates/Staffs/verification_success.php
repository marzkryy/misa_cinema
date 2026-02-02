<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card bg-dark text-white shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-success text-center py-5 border-0">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-5x text-white bounce-animation"></i>
                    </div>
                    <h2 class="fw-bold mb-0">Verification Successful!</h2>
                </div>
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-envelope-open-text fa-3x text-success mb-3 opacity-75"></i>
                        <h4 class="fw-bold">Your new email has been verified.</h4>
                        <p class="text-white-50 lead">The change has been updated in our system records.</p>
                    </div>

                    <div class="bg-black bg-opacity-50 rounded-3 p-4 mb-4 border border-secondary border-opacity-25">
                        <p class="mb-2 text-uppercase small fw-bold text-success">Next Step:</p>
                        <p class="mb-0">Please contact your <strong>system administrator</strong> to inform them that your email address has been successfully changed and verified.</p>
                    </div>

                    <div class="d-grid">
                        <a href="<?= $this->Url->build(['controller' => 'Customers', 'action' => 'login']) ?>" class="btn btn-success btn-lg fw-bold py-3 shadow-sm rounded-pill">
                            <i class="fas fa-sign-in-alt me-2"></i> RETURN TO LOGIN
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bounce-animation {
    animation: bounce 2s infinite;
}
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
    40% {transform: translateY(-20px);}
    60% {transform: translateY(-10px);}
}
</style>
