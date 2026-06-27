<?php require_once "views/layouts/header.php"; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card card-shadow mt-5">
            <div class="card-body p-4">
                <h3 class="text-center mb-4"><i class="bi bi-box-arrow-in-right"></i> Login</h3>
                <form action="<?= BASE_URL ?>/index.php?page=login" method="POST">
                    <?= csrfField() ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                </form>
                <div class="text-center mt-3">
                    <a href="<?= BASE_URL ?>/index.php?page=register" class="text-decoration-none">Don't have an account? Register here</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "views/layouts/footer.php"; ?>
