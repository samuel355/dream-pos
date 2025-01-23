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