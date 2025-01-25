<?php
include '../includes/db_connection.php';
include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

if (!isSysAdminOrAdmin()) {
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}
try {
    if (!isset($_POST['price_id']) || !isset($_POST['price'])) {
        throw new Exception('Required fields are missing');
    }

    $priceId = intval($_POST['price_id']);
    $price = floatval($_POST['price']);

    if ($price <= 0) {
        throw new Exception('Price must be greater than zero');
    }

    $query = "UPDATE product_pricing SET price = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "di", $price, $priceId);
    
    if (mysqli_stmt_execute($stmt)) {
        sendResponse('success', 'Price updated successfully');
    } else {
        throw new Exception('Failed to update price');
    }

} catch (Exception $e) {
    sendResponse('error', $e->getMessage());
} finally {
    mysqli_close($conn);
}