<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    sendResponse('error', 'Unauthorized access');
    exit;
}

try {
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $productName = mysqli_real_escape_string($conn, trim($_POST['product_name']));

    if ($productId <= 0 || empty($productName)) {
        throw new Exception('Invalid product ID or name.');
    }

    // Get existing data (including image)
    $getCategoryQuery = "SELECT category_id, image FROM products WHERE id = ?";
    $getCategoryStmt = mysqli_prepare($conn, $getCategoryQuery);
    mysqli_stmt_bind_param($getCategoryStmt, "i", $productId);
    mysqli_stmt_execute($getCategoryStmt);
    $getCategoryResult = mysqli_stmt_get_result($getCategoryStmt);
    $productData = mysqli_fetch_assoc($getCategoryResult);
    $categoryId = $productData['category_id'];
    $existingImage = $productData['image'];


    // Check for duplicate product name within the category
    $checkQuery = "SELECT COUNT(*) AS count FROM products WHERE name = ? AND category_id = ? AND id != ?";
    $checkStmt = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($checkStmt, "sii", $productName, $categoryId, $productId);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    $duplicateCount = mysqli_fetch_assoc($checkResult)['count'];

    if ($duplicateCount > 0) {
        throw new Exception('A product with this name already exists in this category.');
    }

    // Handle image upload
    $imagePath = $existingImage; 
    if (!empty($_FILES['image']['name'])) {
        $imagePath = handleImageUpload($_FILES['image']);
        // Delete the old image if a new one is uploaded
        if ($existingImage && file_exists(__DIR__ . '/' . $existingImage)) {
            unlink(__DIR__ . '/' . $existingImage);
        }
    }

    // Update product information
    $updateQuery = "UPDATE products SET name = ?";
    $params = [$productName];
    $paramTypes = "s";

    if ($imagePath !== null) {
        $updateQuery .= ", image = ?";
        $params[] = $imagePath;
        $paramTypes .= "s";
    }

    $updateQuery .= " WHERE id = ?";
    $params[] = $productId;
    $paramTypes .= "i";


    $updateStmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($updateStmt, $paramTypes, ...$params);
    mysqli_stmt_execute($updateStmt);

    if (mysqli_affected_rows($conn) === 0) {
        throw new Exception('Product not found or no changes made.');
    }

    sendResponse('success', 'Product updated successfully.');

} catch (Exception $e) {
    sendResponse('error', $e->getMessage());
} finally {
    mysqli_close($conn);
}

function handleImageUpload($file) {
    $targetDir = __DIR__ . '/uploads/products/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception('Invalid file type.');
    }

    $filename = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetFile = $targetDir . $filename;
    
    if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
        throw new Exception('Failed to upload image.');
    }
    
    return 'uploads/products/' . $filename;
}
?>