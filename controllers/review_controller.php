<?php
// controllers/review_controller.php
requireRole(ROLE_CUSTOMER);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCSRFToken($_POST['csrf_token'] ?? '');
    
    $order_id = (int)($_POST['order_id'] ?? 0);
    $food_id = (int)($_POST['food_id'] ?? 0);
    $rating = (int)($_POST['rating'] ?? 5);
    $comment = trim($_POST['comment'] ?? '');
    
    if ($rating < 1 || $rating > 5 || empty($comment)) {
        setFlashMessage('error', 'Invalid review data.');
        redirect('index.php?page=order_details&id=' . $order_id);
    }
    
    // Verify user ordered this item in a completed order
    $stmt = $pdo->prepare("
        SELECT o.id 
        FROM orders o 
        JOIN order_items oi ON o.id = oi.order_id 
        WHERE o.user_id = ? AND o.id = ? AND oi.food_id = ? AND o.status = 'Completed'
    ");
    $stmt->execute([$_SESSION['user_id'], $order_id, $food_id]);
    
    if ($stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO reviews (user_id, food_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $food_id, $rating, $comment]);
        setFlashMessage('success', 'Review submitted successfully. Thank you!');
    } else {
        setFlashMessage('error', 'You can only review items you have received.');
    }
    
    redirect('index.php?page=order_details&id=' . $order_id);
} else {
    redirect('index.php?page=history');
}
