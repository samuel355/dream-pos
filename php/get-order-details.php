<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');

try {
    $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($orderId <= 0) {
        throw new Exception('Invalid order ID');
    }

    // Get order details
    $query = "SELECT * FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($result);

    if (!$order) {
        throw new Exception('Order not found');
    }

    // Get order items
    $query = "SELECT oi.*, p.name as product_name 
              FROM order_items oi
              JOIN products p ON oi.product_id = p.id
              WHERE oi.order_id = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $items = [];
    while ($item = mysqli_fetch_assoc($result)) {
        $items[] = $item;
    }

    $order['items'] = $items;

    sendResponse('success', 'Order details fetched successfully', $order);

} catch (Exception $e) {
    sendResponse('error', $e->getMessage());
} finally {
    mysqli_close($conn);
}
?>