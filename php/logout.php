<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Send success response
header('Content-Type: application/json');
echo json_encode([
  'status' => 'success',
  'message' => 'Logged out successfully',
  'redirect' => 'login.php'
]);
