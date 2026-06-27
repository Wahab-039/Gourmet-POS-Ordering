<?php
// controllers/cashier_controller.php
requireRole(ROLE_CASHIER);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRFToken($_POST['csrf_token'] ?? '');
    
    $order_id = (int)($_POST['order_id'] ?? 0);
    
    if ($order_id > 0) {
        try {
            $pdo->beginTransaction();
            
            // Get order details
            $stmt = $pdo->prepare("SELECT total_amount FROM orders WHERE id = ? AND status = 'Ready'");
            $stmt->execute([$order_id]);
            $order = $stmt->fetch();
            
            if ($order) {
                // Update Order Status
                $stmt = $pdo->prepare("UPDATE orders SET status = 'Completed' WHERE id = ?");
                $stmt->execute([$order_id]);
                
                // Record Payment
                $stmt = $pdo->prepare("INSERT INTO payments (order_id, cashier_id, amount, method, status) VALUES (?, ?, ?, 'Cash', 'Paid')");
                $stmt->execute([$order_id, $_SESSION['user_id'], $order['total_amount']]);
                
                $pdo->commit();
                
                setFlashMessage('success', "Payment processed for Order #$order_id.");
                redirect("index.php?page=cashier_receipt&id=$order_id");
            } else {
                $pdo->rollBack();
                setFlashMessage('error', 'Order not found or not Ready.');
                redirect('index.php?page=cashier_dashboard');
            }
        } catch (Exception $e) {
            $pdo->rollBack();
            setFlashMessage('error', 'An error occurred during payment processing.');
            redirect('index.php?page=cashier_dashboard');
        }
    }
} else {
    redirect('index.php?page=cashier_dashboard');
}
