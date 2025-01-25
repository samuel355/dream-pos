<?php
session_start();
include '../includes/db_connection.php';
include '../includes/sendResponse.php';

try {
    // Check if reset email exists in session
    if (!isset($_SESSION['reset_email'])) {
        throw new Exception('Invalid reset attempt');
    }

    $email = $_SESSION['reset_email'];
    $password = $_POST['password'];

    if (empty($password)) {
        throw new Exception('Password is required');
    }

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update password
    $query = "UPDATE users SET password = ? WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $email);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Failed to update password');
    }

    // Clear reset email from session
    unset($_SESSION['reset_email']);

    sendResponse('success', 'Password updated successfully. Please login with your new password.');

} catch (Exception $e) {
    sendResponse('error', $e->getMessage());
} finally {
    mysqli_close($conn);
}
