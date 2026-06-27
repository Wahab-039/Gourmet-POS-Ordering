<?php require_once "views/layouts/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-shadow mt-4 mb-5">
            <div class="card-body p-4">
                <h3 class="text-center mb-4"><i class="bi bi-person-plus"></i> Create Account</h3>
                <form action="<?= BASE_URL ?>/index.php?page=register" method="POST">
                    <?= csrfField() ?>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-4">
                        <label for="address" class="form-label">Delivery Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Register</button>
                </form>
                <div class="text-center mt-3">
                    <a href="<?= BASE_URL ?>/index.php?page=login" class="text-decoration-none">Already have an account? Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "views/layouts/footer.php"; ?>
