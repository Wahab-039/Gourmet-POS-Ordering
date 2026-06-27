<?php
// controllers/cart_controller.php
requireRole(ROLE_CUSTOMER);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRFToken($_POST['csrf_token'] ?? '');

    if ($action === 'add') {
        $food_id = (int)($_POST['food_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 1);

        if ($food_id > 0 && $quantity > 0) {
            // Verify food exists and is available
            $stmt = $pdo->prepare("SELECT id, name, price FROM foods WHERE id = ? AND is_available = 1");
            $stmt->execute([$food_id]);
            $food = $stmt->fetch();

            if ($food) {
                if (isset($_SESSION['cart'][$food_id])) {
                    $_SESSION['cart'][$food_id]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$food_id] = [
                        'name' => $food['name'],
                        'price' => $food['price'],
                        'quantity' => $quantity
                    ];
                }
                setFlashMessage('success', $food['name'] . ' added to cart.');
            } else {
                setFlashMessage('error', 'Item is not available.');
            }
        }
        redirect('index.php?page=menu');
    }
    
    elseif ($action === 'update') {
        $food_id = (int)($_POST['food_id'] ?? 0);
        $quantity = (int)($_POST['quantity'] ?? 0);
        
        if (isset($_SESSION['cart'][$food_id])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$food_id]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$food_id]);
            }
            setFlashMessage('success', 'Cart updated.');
        }
        redirect('index.php?page=cart');
    }
    
    elseif ($action === 'remove') {
        $food_id = (int)($_POST['food_id'] ?? 0);
        if (isset($_SESSION['cart'][$food_id])) {
            unset($_SESSION['cart'][$food_id]);
            setFlashMessage('success', 'Item removed from cart.');
        }
        redirect('index.php?page=cart');
    }
    
    elseif ($action === 'clear') {
        $_SESSION['cart'] = [];
        setFlashMessage('success', 'Cart cleared.');
        redirect('index.php?page=cart');
    }
}
