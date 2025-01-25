<?php
session_start();
include '../includes/db_connection.php';
include '../includes/sendResponse.php';

try {
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

  if (!$email) {
    throw new Exception('Please enter a valid email');
  }

  // Check if email exists
  $query = "SELECT id FROM users WHERE email = ? AND status = 'active'";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) === 0) {
    throw new Exception('Email not found');
  }

  // Store email in session for reset page
  $_SESSION['reset_email'] = $email;

  sendResponse('success', 'Email verified. Redirecting to reset password page.');
} catch (Exception $e) {
  sendResponse('error', $e->getMessage());
} finally {
  mysqli_close($conn);
}
