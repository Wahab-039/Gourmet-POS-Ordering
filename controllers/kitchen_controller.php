<?php
// controllers/kitchen_controller.php
requireRole(ROLE_KITCHEN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRFToken($_POST['csrf_token'] ?? '');
    
    $order_id = (int)($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    
    $valid_statuses = ['Accepted', 'Preparing', 'Ready'];
    
    if ($order_id > 0 && in_array($status, $valid_statuses)) {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        if ($stmt->execute([$status, $order_id])) {
            setFlashMessage('success', "Order #$order_id status updated to $status.");
            logAction($pdo, $_SESSION['user_id'], 'Updated Order Status', "Order #$order_id to $status");
        } else {
            setFlashMessage('error', 'Failed to update order status.');
        }
    }
    
    redirect('index.php?page=kitchen_dashboard');
} else {
    redirect('index.php?page=kitchen_dashboard');
}
