<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $customerId = isset($data['customer_id']) ? intval($data['customer_id']) : 0;

    if ($customerId <= 0) {
        throw new Exception('Invalid customer ID');
    }

    $query = "DELETE FROM customers WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $customerId);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) === 0) {
        throw new Exception('Customer not found');
    }

    sendResponse('success', 'Customer deleted successfully');

} catch (Exception $e) {
    sendResponse('error', $e->getMessage());
} finally {
    mysqli_close($conn);
}
?>