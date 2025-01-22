<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');

try {
    $userId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($userId <= 0) {
        throw new Exception('Invalid user ID');
    }

    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if (!$user) {
        throw new Exception('User not found');
    }

    sendResponse('success', 'User fetched successfully', ['user' => $user]);

} catch (Exception $e) {
    sendResponse('error', $e->getMessage());
} finally {
    mysqli_close($conn);
}
?>