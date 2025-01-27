<?php
session_start();
include '../includes/db_connection.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');

function getCategories($conn)
{
  $query = "SELECT id, name, created_by, image FROM categories ORDER BY arrange";
  $result = mysqli_query($conn, $query);

  if (!$result) {
    return ['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn)];
  }

  $categories = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = [
      'id' => $row['id'],
      'name' => $row['name'],
      'created_by' => $row['created_by'],
      'image' => 'php/' . $row['image']
    ];
  }

  return ['status' => 'success', 'categories' => $categories];
}

echo json_encode(getCategories($conn));
