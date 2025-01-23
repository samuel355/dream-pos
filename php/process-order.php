<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
include_once('../includes/auth.php');
header('Content-Type: application/json');

requireLogin();

function processOrder($conn) {
    try {
        mysqli_begin_transaction($conn);
        
        // 1. Validate Session and Input Data
        if (!validateInput()) {
            return [
                'status' => 'error',
                'message' => 'Invalid or missing input data'
            ];
        }

        // 2. Get and Sanitize Input Data
        $data = getAndSanitizeInput($conn);
        
        // 3. Get Cart Items and Calculate Totals
        $cartData = getCartItems($conn, $data['session_id']);
        if ($cartData['status'] === 'error') {
            return $cartData;
        }

        // 4. Create Order
        $orderResult = createOrder($conn, $data, $cartData['subtotal']);
        if ($orderResult['status'] === 'error') {
            return $orderResult;
        }

        // 5. Add Customer
        $customerResult = addCustomer($conn, $data);
        if ($customerResult['status'] === 'error') {
            return $customerResult;
        }

        // 6. Add Order Items
        $orderItemsResult = addOrderItems($conn, $orderResult['order_id'], $cartData['items']);
        if ($orderItemsResult['status'] === 'error') {
            return $orderItemsResult;
        }

        // 7. Add Order History
        $historyResult = addOrderHistory($conn, $orderResult['order_id'], $data['user_id']);
        if ($historyResult['status'] === 'error') {
            return $historyResult;
        }

        // 8. Add Notification
        $notificationResult = addNotification($conn, $orderResult['order_id']);
        if ($notificationResult['status'] === 'error') {
            return $notificationResult;
        }

        mysqli_commit($conn);
        
        return [
            'status' => 'success',
            'message' => 'Order processed successfully',
            'order_id' => $orderResult['order_id'],
            'receipt_number' => $data['invoice_number']
        ];

    } catch (Exception $e) {
        mysqli_rollback($conn);
        return [
            'status' => 'error',
            'message' => 'Error processing order: ' . $e->getMessage()
        ];
    }
}

function validateInput() {
    return isset($_POST['customer_name']) && 
           isset($_POST['customer_phone']) && 
           isset($_POST['invoice_number']);
}

function getAndSanitizeInput($conn) {
    return [
        'customer_name' => mysqli_real_escape_string($conn, trim($_POST['customer_name'])),
        'customer_phone' => mysqli_real_escape_string($conn, trim($_POST['customer_phone'])),
        'invoice_number' => $_POST['invoice_number'],
        'session_id' => session_id(),
        'user_id' => $_SESSION['user_id'] ?? null
    ];
}

function getCartItems($conn, $session_id) {
    $cart_query = "SELECT ci.*, p.name as product_name 
                   FROM cart_items ci 
                   JOIN products p ON ci.product_id = p.id 
                   WHERE ci.session_id = ?";
    
    $stmt = mysqli_prepare($conn, $cart_query);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (!$result) {
        return ['status' => 'error', 'message' => 'Error fetching cart items'];
    }

    $subtotal = 0;
    $items = [];
    while ($item = mysqli_fetch_assoc($result)) {
        $item_total = $item['quantity'] * $item['price'];
        $subtotal += $item_total;
        $items[] = $item;
    }

    return [
        'status' => 'success',
        'items' => $items,
        'subtotal' => $subtotal
    ];
}

function createOrder($conn, $data, $total_amount) {
    $order_query = "INSERT INTO orders (user_id, customer_name, customer_phone, total_amount, receipt_number, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $order_query);
    mysqli_stmt_bind_param(
        $stmt,
        "issdss",
        $data['user_id'],
        $data['customer_name'],
        $data['customer_phone'],
        $total_amount,
        $data['invoice_number'],
        $_SESSION['fullname']
    );

    if (!mysqli_stmt_execute($stmt)) {
        return ['status' => 'error', 'message' => 'Error creating order'];
    }

    return [
        'status' => 'success',
        'order_id' => mysqli_insert_id($conn)
    ];
}

function addCustomer($conn, $data) {
    try {
        // Get cart items to combine
        $session_id = session_id();
        $cart_query = "SELECT ci.*, p.name as product_name 
                      FROM cart_items ci 
                      JOIN products p ON ci.product_id = p.id 
                      WHERE ci.session_id = ?";
        
        $stmt = mysqli_prepare($conn, $cart_query);
        mysqli_stmt_bind_param($stmt, "s", $session_id);
        mysqli_stmt_execute($stmt);
        $cart_items = mysqli_stmt_get_result($stmt);

        // Combine items and calculate total
        $items_array = [];
        $total = 0;
        
        while ($item = mysqli_fetch_assoc($cart_items)) {
            // Add item to combined string
            $items_array[] = $item['quantity'] . "x " . $item['product_name'];
            
            // Calculate total
            $total += ($item['quantity'] * $item['price']);
        }

        // Convert items array to string
        $combined_items = implode(", ", $items_array);

        // Insert into customers table
        $customer_query = "INSERT INTO customers (
            name, 
            contact, 
            items, 
            total,
            created_by
        ) VALUES (?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $customer_query);
        mysqli_stmt_bind_param(
            $stmt, 
            "sssss", 
            $data['customer_name'],
            $data['customer_phone'],
            $combined_items,
            $total,
            $_SESSION['fullname']
        );
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception('Error adding customer: ' . mysqli_error($conn));
        }
        
        return [
            'status' => 'success',
            'message' => 'Customer added successfully',
            'customer_id' => mysqli_insert_id($conn)
        ];

    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}

function addOrderItems($conn, $order_id, $items) {
    $item_query = "INSERT INTO order_items (order_id, product_id, quantity, unit_price, total_price, created_by) 
                   VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $item_query);

    foreach ($items as $item) {
        $item_total = $item['quantity'] * $item['price'];
        mysqli_stmt_bind_param(
            $stmt,
            "iiddds",
            $order_id,
            $item['product_id'],
            $item['quantity'],
            $item['price'],
            $item_total,
            $_SESSION['fullname']
        );
        
        if (!mysqli_stmt_execute($stmt)) {
            return ['status' => 'error', 'message' => 'Error adding order items'];
        }
    }
    
    return ['status' => 'success'];
}

function addOrderHistory($conn, $order_id, $user_id) {
    $history_query = "INSERT INTO order_history (order_id, user_id, action, new_status, created_by) 
                      VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $history_query);
    $action = 'order_created';
    $status = 'completed';

    mysqli_stmt_bind_param($stmt, "iisss", $order_id, $user_id, $action, $status, $_SESSION['fullname']);
    
    if (!mysqli_stmt_execute($stmt)) {
        return ['status' => 'error', 'message' => 'Error adding order history'];
    }
    
    return ['status' => 'success'];
}

function addNotification($conn, $order_id) {
    $notification_query = "INSERT INTO notifications (order_id) VALUES (?)";
    $stmt = mysqli_prepare($conn, $notification_query);
    mysqli_stmt_bind_param($stmt, "i", $order_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        return ['status' => 'error', 'message' => 'Error adding notification'];
    }
    
    return ['status' => 'success'];
}

// Execute the process
echo json_encode(processOrder($conn));
?>