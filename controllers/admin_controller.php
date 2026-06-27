<?php
// controllers/admin_controller.php
requireRole(ROLE_ADMIN);

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRFToken($_POST['csrf_token'] ?? '');

    // --- CATEGORIES ---
    if ($action === 'add_category') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        if (empty($name)) {
            setFlashMessage('error', 'Category name is required.');
        } else {
            $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
            setFlashMessage('success', 'Category added successfully.');
        }
        redirect('index.php?page=admin_categories');
    }
    elseif ($action === 'delete_category') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        setFlashMessage('success', 'Category deleted.');
        redirect('index.php?page=admin_categories');
    }

    // --- FOODS ---
    elseif ($action === 'add_food') {
        $category_id = (int)($_POST['category_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = (float)($_POST['price'] ?? 0);
        $is_available = isset($_POST['is_available']) ? 1 : 0;
        
        $image_path = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($_FILES['image']['type'], $allowedTypes)) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], UPLOAD_DIR . $filename)) {
                    $image_path = $filename;
                }
            } else {
                setFlashMessage('error', 'Invalid image format. Only JPG, PNG, WEBP allowed.');
                redirect('index.php?page=admin_foods');
            }
        }
        
        if (empty($name) || $price <= 0 || $category_id <= 0) {
            setFlashMessage('error', 'Invalid input data.');
        } else {
            $stmt = $pdo->prepare("INSERT INTO foods (category_id, name, description, price, image_path, is_available) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$category_id, $name, $description, $price, $image_path, $is_available]);
            setFlashMessage('success', 'Food item added.');
        }
        redirect('index.php?page=admin_foods');
    }
    elseif ($action === 'delete_food') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $pdo->prepare("DELETE FROM foods WHERE id = ?");
        $stmt->execute([$id]);
        setFlashMessage('success', 'Food item deleted.');
        redirect('index.php?page=admin_foods');
    }

    // --- USERS ---
    elseif ($action === 'update_user_role') {
        $id = (int)($_POST['id'] ?? 0);
        $role_id = (int)($_POST['role_id'] ?? 0);
        
        if ($id === $_SESSION['user_id']) {
            setFlashMessage('error', 'You cannot change your own role.');
        } else {
            $stmt = $pdo->prepare("UPDATE users SET role_id = ? WHERE id = ?");
            $stmt->execute([$role_id, $id]);
            setFlashMessage('success', 'User role updated.');
        }
        redirect('index.php?page=admin_users');
    }
}
