<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');

include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
//header('Content-Type: application/json');

while (true) {
  $query = "SELECT COUNT(*) as count FROM orders WHERE created_at >= NOW() - INTERVAL 2 SECOND";
  $result = $conn->query($query);
  $row = $result->fetch_assoc();
  
  if ($row['count'] > 0) {
    echo "data: " . json_encode(['type' => 'new_order']) . "\n\n";
  }
  
  ob_flush();
  flush();
  
  sleep(5);
}