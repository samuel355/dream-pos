<?php
session_start();
include '../includes/db_connection.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

try {
    $query = "SELECT * FROM users WHERE email != 'addsamuel355@gmail.com'  ORDER BY id";
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception("Database error: " . mysqli_error($conn));
    }
    
    $users = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }

    sendResponse('success', 'Users fetched successfully', ['users' => $users]);

} catch (Exception $e) {
    sendResponse('error', $e->getMessage());
} finally {
    mysqli_close($conn);
}
?>