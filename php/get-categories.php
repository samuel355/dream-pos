<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function getCategories($conn)
{
  $query = "SELECT id, name, image FROM categories ORDER BY name";
  $result = mysqli_query($conn, $query);

  if (!$result) {
    return ['status' => 'error', 'message' => 'Database error: ' . mysqli_error($conn)];
  }

  $categories = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = [
      'id' => $row['id'],
      'name' => $row['name'],
      'image' => 'php/' . $row['image']
    ];
  }

  return ['status' => 'success', 'data' => $categories];
}

echo json_encode(getCategories($conn));
