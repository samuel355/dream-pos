<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

include_once('../includes/auth.php');

if(!isAdmin() || !isSysAdmin()){
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}

try {
  if (empty($_POST['category-name'])) {
    sendResponse('error', 'Enter category name');
  }

  $categoryname = mysqli_real_escape_string($conn, trim($_POST['category-name']));
  $created_by =   $_SESSION['fullname'];
  $user_id = $_SESSION['user_id'];
  // Check if category already exists
  $check_cat = mysqli_query($conn, "SELECT id FROM categories WHERE name = '$categoryname'");
  if (mysqli_num_rows($check_cat) > 0) {
    sendResponse('error', 'Sorry category name already exist');
  }

  // Handle image upload
  $image_path = null;
  if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
    $image_path = handleImageUpload($_FILES['image']);
  }

  //insert data
  $query = "INSERT INTO categories(name, created_by, image, user_id) 
            VALUES(?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $query);
  mysqli_stmt_bind_param($stmt, "ssss", $categoryname, $created_by, $image_path, $user_id);

  if(!mysqli_stmt_execute($stmt)){
    throw new Exception('Database error occurred');
  }

  sendResponse('success', 'Category created successfully');

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
    $target_dir = __DIR__ . '/uploads/categories/';

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

    return 'uploads/categories/' . $filename;
  } catch (Exception $e) {
    error_log('Image Upload Error: ' . $e->getMessage());
    throw new Exception('Failed to upload image: ' . $e->getMessage());
  }
}

mysqli_close($conn);