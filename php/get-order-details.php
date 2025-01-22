<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');

try {
    $orderId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($orderId <= 0) {
        throw new Exception('Invalid order ID');
    }

    // Get order basic details
    $orderQuery = "SELECT * FROM orders WHERE id = ?";
    $stmt = mysqli_prepare($conn, $orderQuery);
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $orderResult = mysqli_stmt_get_result($stmt);
    $order = mysqli_fetch_assoc($orderResult);

    if (!$order) {
        throw new Exception('Order not found');
    }

    // Get order items
    $itemsQuery = "SELECT oi.*, p.name as product_name, p.price 
                   FROM order_items oi 
                   JOIN products p ON oi.product_id = p.id 
                   WHERE oi.order_id = ?";
    
    $stmt = mysqli_prepare($conn, $itemsQuery);
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $itemsResult = mysqli_stmt_get_result($stmt);
    
    $items = [];
    while ($item = mysqli_fetch_assoc($itemsResult)) {
        $items[] = $item;
    }

    $order['items'] = $items;

    // Debug log
    error_log("Order data: " . json_encode($order));

    echo json_encode([
        'status' => 'success',
        'data' => $order,
        'debug' => [
            'orderId' => $orderId,
            'itemsCount' => count($items)
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'debug' => [
            'orderId' => $orderId ?? null,
            'error' => $e->getMessage()
        ]
    ]);
} finally {
    mysqli_close($conn);
}
?>