<?php
session_start();
include '../includes/db_connection.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

try {
    $query = "SELECT * FROM customers ORDER BY id DESC";
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception("Database error: " . mysqli_error($conn));
    }
    
    $customers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $customers[] = $row;
    }

    sendResponse('success', 'Customers fetched successfully', ['customers' => $customers]);

} catch (Exception $e) {
    sendResponse('error', $e->getMessage());
} finally {
    mysqli_close($conn);
}
