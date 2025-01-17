<?php

session_start();
include_once('../includes/db_connection.php');
header('Content-Type: application/json');
include_once('../includes/sendResponse.php');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  sendResponse('error', 'Invalid request method');
}

// Validate and sanitize inputs
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
  sendResponse('error', 'Please enter both email and password');
}

// Check database connection
if (!$conn) {
  sendResponse('error', 'Database connection failed');
}


// Query user
$query = "SELECT *
          FROM users 
          WHERE email = ? 
          AND status = 'active' 
          LIMIT 1";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
  $user = mysqli_fetch_assoc($result);

  if (password_verify($password, $user['password'])) {
    // Success - Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['image'] = $user['image'];

    // Update last login
    mysqli_query(
      $conn,
      "UPDATE users 
             SET last_login = NOW() 
             WHERE id = " . $user['id']
    );

    // Clear login attempts
    mysqli_query(
      $conn,
      "DELETE FROM login_attempts 
             WHERE ip_address = '$ip'"
    );

    // Return success response
    echo json_encode([
      'status' => 'success',
      'redirect' => $user['role'] === 'admin' ? '/' : '/'
    ]);
  } else {

    sendResponse('error', 'Wrong credentials');
  }
} else {

  sendResponse('error', 'Wrong credentials');
}

mysqli_close($conn);
