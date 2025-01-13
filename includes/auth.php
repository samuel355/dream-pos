<?php
session_start();

function login($username, $password, $conn)
{
  // Sanitize inputs
  $username = mysqli_real_escape_string($conn, $username);

  // Check for too many login attempts
  if (checkLoginAttempts($username, $_SERVER['REMOTE_ADDR'], $conn)) {
    return "Too many failed attempts. Please try again later.";
  }

  // Get user from database
  $query = "SELECT id, username, password, fullname, role, status 
              FROM users 
              WHERE username = '$username' 
              AND status = 'active'";

  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
      // Set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['fullname'] = $user['fullname'];
      $_SESSION['role'] = $user['role'];

      // Update last login
      updateLastLogin($user['id'], $conn);

      return "success";
    }
  }

  // Log failed attempt
  logFailedAttempt($username, $_SERVER['REMOTE_ADDR'], $conn);
  return "Invalid username or password";
}

function checkLoginAttempts($username, $ip, $conn)
{
  // Clear old attempts (older than 15 minutes)
  $query = "DELETE FROM login_attempts 
            WHERE attempted_at < DATE_SUB(NOW(), INTERVAL 15 MINUTE)";
  mysqli_query($conn, $query);

  // Count recent attempts
  $username = mysqli_real_escape_string($conn, $username);
  $ip = mysqli_real_escape_string($conn, $ip);

  $query = "SELECT COUNT(*) as attempt_count 
            FROM login_attempts 
            WHERE (username = '$username' OR ip_address = '$ip') 
            AND attempted_at > DATE_SUB(NOW(), INTERVAL 15 MINUTE)";

  $result = mysqli_query($conn, $query);
  $count = mysqli_fetch_assoc($result)['attempt_count'];

  return $count >= 5;
}

function logFailedAttempt($username, $ip, $conn)
{
  $username = mysqli_real_escape_string($conn, $username);
  $ip = mysqli_real_escape_string($conn, $ip);

  $query = "INSERT INTO login_attempts (username, ip_address) 
            VALUES ('$username', '$ip')";
  mysqli_query($conn, $query);
}

function updateLastLogin($user_id, $conn)
{
  $query = "UPDATE users 
            SET last_login = NOW() 
            WHERE id = " . (int)$user_id;
  mysqli_query($conn, $query);
}

function isLoggedIn()
{
  return isset($_SESSION['user_id']);
}

function isAdmin()
{
  return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

function requireLogin()
{
  if (!isLoggedIn()) {
    header('Location: login');
    exit();
  }
}

function requireAdmin()
{
  requireLogin();
  if (!isAdmin()) {
    header('Location: /');
    exit();
  }
}
