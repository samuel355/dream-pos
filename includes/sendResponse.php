<?php
// Helper function to send a JSON response
function sendResponse($status, $message, $data = [])
{
  echo json_encode(array_merge(['status' => $status, 'message' => $message], $data));
  exit;
}