<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
header('Content-Type: application/json');

function processOrder($conn)
{
  try {
    $session_id = session_id();

    // Sanitize inputs
    $customerName = mysqli_real_escape_string($conn, trim($_POST['customer_name']));
    $customerContact = mysqli_real_escape_string($conn, trim($_POST['customer_phone']));
    $invoiceNumber = $_POST['invoice_number'];

    // Calculate totals
    $cart_query = "SELECT ci.*, p.name as product_name 
                    FROM cart_items ci 
                    JOIN products p ON ci.product_id = p.id 
                    WHERE ci.session_id = ?";
    $stmt = mysqli_prepare($conn, $cart_query);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
    $cart_items = mysqli_stmt_get_result($stmt);

    $subtotal = 0;
    $items = [];
    while ($item = mysqli_fetch_assoc($cart_items)) {
      $item_total = $item['quantity'] * $item['price'];
      $subtotal += $item_total;
      $items[] = $item;
    }

    $total_amount = $subtotal;

    // Create order
    $order_query = "INSERT INTO orders (
          user_id, 
          customer_name, 
          customer_phone,
          total_amount,
          receipt_number
      ) VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $order_query);
    $user_id = $_SESSION['user_id'] ?? 1;

    mysqli_stmt_bind_param(
      $stmt,
      "issds",
      $user_id,
      $customerName,
      $customerContact,
      $total_amount,
      $invoiceNumber
    );

    if (!mysqli_stmt_execute($stmt)) {
      sendResponse('error', 'Error creating orders');
    }

    $order_id = mysqli_insert_id($conn);

    //Add order items
    $item_query = "INSERT INTO order_items (
          order_id, 
          product_id, 
          quantity, 
          unit_price, 
          total_price
      ) VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $item_query);

    foreach ($items as $item) {
      $item_total = $item['quantity'] * $item['price'];
      mysqli_stmt_bind_param(
        $stmt,
        "iiddd",
        $order_id,
        $item['product_id'],
        $item['quantity'],
        $item['price'],
        $item_total
      );
      mysqli_stmt_execute($stmt);
    }


    // // Add to order history
    $history_query = "INSERT INTO order_history (
          order_id, 
          user_id, 
          action, 
          new_status
      ) VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $history_query);
    $action = 'order_created';
    $status = 'completed';

    mysqli_stmt_bind_param(
      $stmt,
      "iiss",
      $order_id,
      $user_id,
      $action,
      $status
    );
    mysqli_stmt_execute($stmt);


    //Add to notifications
    $notification_query = "INSERT INTO notifications (order_id) VALUES (?)";
    $stmt = mysqli_prepare($conn, $notification_query);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    mysqli_stmt_execute($stmt);

    // Commit transaction
    mysqli_commit($conn);

    return [
      'status' => 'success',
      'message' => 'Order processed successfully',
      'order_id' => $order_id,
      'receipt_number' => $invoiceNumber
    ];
  } catch (Exception $e) {
    mysqli_rollback($conn);
    return [
      'status' => 'error',
      'message' => 'Error processing order: ' . $e->getMessage()
    ];
  }
}

echo json_encode(processOrder($conn));
