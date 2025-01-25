<?php
session_start();
include '../includes/db_connection.php';
include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';

try {
    if (!isset($_POST['price_id'])) {
        throw new Exception('Price ID is required');
    }

    $priceId = intval($_POST['price_id']);
    
    $query = "SELECT pp.*, c.name as category_name 
              FROM product_pricing pp
              JOIN categories c ON pp.category_id = c.id
              WHERE pp.id = ?";
              
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $priceId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($pricing = mysqli_fetch_assoc($result)) {
        sendResponse('success', 'Pricing details fetched successfully', ['pricing' => $pricing]);
    } else {
        throw new Exception('Pricing not found');
    }

} catch (Exception $e) {
    sendResponse('error', $e->getMessage());
} finally {
    mysqli_close($conn);
}