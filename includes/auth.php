<?php
session_start();

function login($email, $password, $conn)
{
  // Sanitize inputs
  $email = mysqli_real_escape_string($conn, $email);

  // Get user from database
  $query = "SELECT id, email, email, password, fullname, role, status 
              FROM users 
              WHERE email = '$email' 
              AND status = 'active'";

  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);

    if (password_verify($password, $user['password'])) {
      // Set session variables
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['fullname'] = $user['fullname'];
      $_SESSION['role'] = $user['role'];
      
      // Update last login
      updateLastLogin($user['id'], $conn);

      if ($_SESSION['role'] === 'admin') {
        header('Location: dashboard');
      }

      return "success";
    }
  }
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
  return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isSysAdmin()
{
  return (isset($_SESSION['sysadmin']) && $_SESSION['sysadmin'] === true);
}

function isSysAdminOrAdmin()
{
  return (isset($_SESSION['sysadmin']) && $_SESSION['sysadmin'] === true) ||
    (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

function requireLogin()
{
  if (!isLoggedIn()) {
    header('Location: /login');
    exit();
  }
}

function requireAdmin()
{
  requireLogin();
  if (!isAdmin()) {
    header('Location: /pos');
    exit();
  }
}
