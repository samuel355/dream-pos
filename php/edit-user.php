<?php
include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

if(!isAdmin() || !isSysAdmin()){
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}

try {
  $userId = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
  if ($userId <= 0) {
    throw new Exception('Invalid user ID');
  }

  // Input validation and sanitization (add more checks as needed)
  $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
  $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
  $role = mysqli_real_escape_string($conn, trim($_POST['role']));

  $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : null;
  $repassword = isset($_POST['repassword']) && !empty($_POST['repassword']) ? $_POST['repassword'] : null;

  if ($password !== $repassword && !empty($password)) {
    throw new Exception('Passwords do not match');
  }

  // Handle image upload
  $imagePath = null;
  if (!empty($_FILES['image']['name'])) {
    $imagePath = handleImageUpload($_FILES['image']);
  }

  mysqli_begin_transaction($conn);

  // Update user information
  $updateQuery = "UPDATE users SET fullname = ?, phone = ?, role = ?, updated_at = NOW()";
  if ($imagePath) {
    $updateQuery .= ", image = ?";
  }
  if ($password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $updateQuery .= ", password = ?";
  }
  $updateQuery .= " WHERE id = ?";


  $stmt = mysqli_prepare($conn, $updateQuery);

  if ($imagePath && $password) {
    mysqli_stmt_bind_param($stmt, "ssssi", $fullname, $phone, $role, $imagePath, $userId);
  } else if ($imagePath) {
    mysqli_stmt_bind_param($stmt, "sssi", $fullname, $phone, $role, $imagePath, $userId);
  } else if ($password) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($stmt, "sssi", $fullname, $phone, $role, $hashedPassword, $userId);
  } else {
    mysqli_stmt_bind_param($stmt, "sssi", $fullname, $phone, $role, $userId);
  }

  mysqli_stmt_execute($stmt);

  if (mysqli_stmt_affected_rows($stmt) === 0) {
    throw new Exception('User not found or no changes made');
  }

  mysqli_commit($conn);

  sendResponse('success', 'User updated successfully');
} catch (Exception $e) {
  mysqli_rollback($conn);
  sendResponse('error', $e->getMessage());
} finally {
  if (isset($stmt)) {
    mysqli_stmt_close($stmt);
  }
  mysqli_close($conn);
}


function handleImageUpload($file)
{
  try {
    // Check if there was an upload error
    if ($file['error'] !== UPLOAD_ERR_OK) {
      throw new Exception('Upload failed with error code: ' . $file['error']);
    }

    // Define upload directory relative to the script's location
    $target_dir = __DIR__ . '/uploads/profile_images/';

    // Normalize directory path for consistent handling
    $target_dir = str_replace('\\', '/', $target_dir);

    // Create directory if it doesn't exist
    if (!is_dir($target_dir)) {
      if (!mkdir($target_dir, 0777, true)) {
        throw new Exception('Failed to create upload directory: ' . $target_dir);
      }
    }

    // file types
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed_types = [
      'image/jpeg' => 'jpg',
      'image/png' => 'png',
      'image/gif' => 'gif'
    ];

    if (!array_key_exists($mime_type, $allowed_types)) {
      throw new Exception('Invalid file type. Only JPG, PNG & GIF files are allowed');
    }

    // unique filename
    $file_extension = $allowed_types[$mime_type];
    $filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $filename;

    // (5MB max)
    if ($file['size'] > 5000000) {
      throw new Exception('File is too large (max 5MB)');
    }

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $target_file)) {
      throw new Exception('Failed to move uploaded file');
    }

    return 'uploads/profile_images/' . $filename;
  } catch (Exception $e) {
    error_log('Image Upload Error: ' . $e->getMessage());
    throw new Exception('Failed to upload image: ' . $e->getMessage());
  }
}
