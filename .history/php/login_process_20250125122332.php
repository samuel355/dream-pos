<?php
session_start();
include '../includes/db_connection.php';
// Added proper JSON header to ensure JSON response
header('Content-Type: application/json');
include '../includes/sendResponse.php';

// Added exit after response
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse('error', 'Invalid request method');
    exit; // Added exit
}

// Added try-catch block for better error handling
try {
    // Validate and sanitize inputs
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password'] ?? '');

    // Added exit after response
    if (empty($email) || empty($password)) {
        sendResponse('error', 'Please enter both email and password');
        exit; // Added exit
    }

    // Added exit after response
    if (!$conn) {
        sendResponse('error', 'Database connection failed');
        exit; // Added exit
    }

    // Query user - No changes here
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
            // Session variables - No changes here
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['image'] = $user['image'];
            
            if ($user['role'] === 'admin' && $user['is_sysadmin']) {
                $_SESSION['sysadmin'] = true;
            }

            // Changed from direct query to prepared statement for security
            $updateQuery = "UPDATE users SET last_login = NOW() WHERE id = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "i", $user['id']);
            mysqli_stmt_execute($updateStmt);

            // Removed login_attempts code that wasn't being used

            // Modified to use sendResponse function consistently
            sendResponse('success', 'Login successful', [
                'redirect' => $user['role'] === 'admin' ? '/dashboard' : '/pos'
            ]);
        } else {
            sendResponse('error', 'Wrong credentials');
        }
    } else {
        sendResponse('error', 'Wrong credentials');
    }

// Added catch block for error handling
} catch (Exception $e) {
    sendResponse('error', 'An error occurred: ' . $e->getMessage());
// Added finally block to ensure connection is closed
} finally {
    mysqli_close($conn);
}