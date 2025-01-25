<?php
include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

if(!isSysAdminOrAdmin()){
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}

try {
  // Validate inputs
  if (
    empty($_POST['fullname']) || empty($_POST['email']) ||
    empty($_POST['password']) || empty($_POST['repassword']) ||
    empty($_POST['role']) || empty($_POST['phone'])
  ) {
    sendResponse('error', 'Complete all fields');
  }

  // Validate passwords match
  if ($_POST['password'] !== $_POST['repassword']) {
    sendResponse('error', 'Passwords do not match');
  }

  // Sanitize inputs
  $fullname = mysqli_real_escape_string($conn, trim($_POST['fullname']));
  $email = mysqli_real_escape_string($conn, trim($_POST['email']));
  $password = mysqli_real_escape_string($conn, trim($_POST['password']));
  $role = mysqli_real_escape_string($conn, trim($_POST['role']));
  $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));

  // Check if email already exists
  $check_email = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
  if (mysqli_num_rows($check_email) > 0) {
    throw new Exception('Email already exists');
  }

  // Generate username from fullname
  $username = generateUsername($fullname, $conn);

  // Hash password
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // Handle image upload
  $image_path = null;
  if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $image_path = handleImageUpload($_FILES['image']);
  }

  // Insert user into database
  $query = "INSERT INTO users (email, phone, username, password, fullname, role, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "sssssss", $email, $phone, $username, $hashed_password, $fullname, $role, $image_path);

  if (!mysqli_stmt_execute($stmt)) {
    throw new Exception('Database error occurred');
  }

  sendResponse('success', 'User Created Successfully');
} catch (Exception $e) {
  echo json_encode([
    'status' => 'error',
    'message' => $e->getMessage()
  ]);
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


// Helper functions
function generateUsername($fullname, $conn)
{
  // Remove special characters and spaces
  $username = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($fullname));

  // Get first 8 characters
  $username = substr($username, 0, 8);

  // Check if username exists
  $check_username = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");

  // If username exists, add random number until unique
  $i = 1;
  $original_username = $username;
  while (mysqli_num_rows($check_username) > 0) {
    $username = $original_username . $i;
    $check_username = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    $i++;
  }

  return $username;
}

mysqli_close($conn);
