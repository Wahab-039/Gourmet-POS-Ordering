<?php
require_once "views/layouts/header.php";

$roles = $pdo->query("SELECT * FROM roles")->fetchAll();
$users = $pdo->query("
    SELECT u.*, r.name as role_name 
    FROM users u 
    JOIN roles r ON u.role_id = r.id 
    ORDER BY u.id DESC
")->fetchAll();
?>

<div class="row mb-4">
    <div class="col-md-12">
        <h2><i class="bi bi-people"></i> Manage Users & Roles</h2>
    </div>
</div>

<div class="card card-shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Current Role</th>
                        <th>Change Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= escape($user['name']) ?></td>
                        <td><?= escape($user['email']) ?></td>
                        <td><?= escape($user['phone']) ?></td>
                        <td>
                            <?php
                            $badge = 'bg-secondary';
                            if ($user['role_id'] == ROLE_ADMIN) $badge = 'bg-danger';
                            elseif ($user['role_id'] == ROLE_CASHIER) $badge = 'bg-success';
                            elseif ($user['role_id'] == ROLE_KITCHEN) $badge = 'bg-warning text-dark';
                            elseif ($user['role_id'] == ROLE_CUSTOMER) $badge = 'bg-primary';
                            ?>
                            <span class="badge <?= $badge ?>"><?= escape($user['role_name']) ?></span>
                        </td>
                        <td>
                            <?php if ($user['id'] !== $_SESSION['user_id']): ?>
                            <form action="<?= BASE_URL ?>/index.php?page=admin_action&action=update_user_role" method="POST" class="d-flex align-items-center">
                                <?= csrfField() ?>
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <select name="role_id" class="form-select form-select-sm me-2" style="width: auto;">
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= $r['id'] ?>" <?= $r['id'] == $user['role_id'] ? 'selected' : '' ?>><?= escape($r['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                            <?php else: ?>
                            <span class="text-muted small">Cannot change own role</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once "views/layouts/footer.php"; ?>
