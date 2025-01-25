<?php
include '../includes/db_connection.php';
include '../includes/auth.php';
include '../includes/sendResponse.php';
header('Content-Type: application/json');


if(!iss){
  sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}

try {
  $query = "UPDATE notifications SET is_read = 1";
  
  if (!mysqli_query($conn, $query)) {
      throw new Exception("Error clearing notifications");
  }

  sendResponse('success', 'All notifications cleared');

} catch (Exception $e) {
  sendResponse('error', $e->getMessage());
} finally {
  mysqli_close($conn);
}