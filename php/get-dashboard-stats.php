<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');

try {
    // Get today's orders count
    $query = "SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = CURRENT_DATE()";
    $result = mysqli_query($conn, $query);
    $todays_orders = mysqli_fetch_assoc($result)['total'];

    // Get today's total sales
    $query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE DATE(created_at) = CURRENT_DATE()";
    $result = mysqli_query($conn, $query);
    $todays_sales = mysqli_fetch_assoc($result)['total'];

    // Get weekly sales
    $query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 7 DAY)";
    $result = mysqli_query($conn, $query);
    $weekly_sales = mysqli_fetch_assoc($result)['total'];

    // Get monthly sales
    $query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE MONTH(created_at) = MONTH(CURRENT_DATE())";
    $result = mysqli_query($conn, $query);
    $monthly_sales = mysqli_fetch_assoc($result)['total'];

    // Get today's unique customers
    $query = "SELECT COUNT(DISTINCT customer_name) as total FROM orders WHERE DATE(created_at) = CURRENT_DATE()";
    $result = mysqli_query($conn, $query);
    $todays_customers = mysqli_fetch_assoc($result)['total'];

    // Get total admins
    $query = "SELECT COUNT(*) as total FROM users WHERE role = 'admin'";
    $result = mysqli_query($conn, $query);
    $total_admins = mysqli_fetch_assoc($result)['total'];

    // Get total cashiers
    $query = "SELECT COUNT(*) as total FROM users WHERE role = 'cashier'";
    $result = mysqli_query($conn, $query);
    $total_cashiers = mysqli_fetch_assoc($result)['total'];

    // Get today's orders list with items
    $query = "SELECT 
                o.*,
                GROUP_CONCAT(
                    CONCAT(oi.quantity, 'x ', p.name)
                    SEPARATOR ', '
                ) as items
              FROM orders o
              LEFT JOIN order_items oi ON o.id = oi.order_id
              LEFT JOIN products p ON oi.product_id = p.id
              WHERE DATE(o.created_at) = CURRENT_DATE()
              GROUP BY o.id
              ORDER BY o.created_at DESC";
    
    $result = mysqli_query($conn, $query);
    $todays_orders_list = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $todays_orders_list[] = [
            'id' => $row['id'],
            'customer_name' => $row['customer_name'],
            'customer_phone' => $row['customer_phone'],
            'items' => $row['items'],
            'total_amount' => $row['total_amount'],
            'created_at' => $row['created_at'],
            'created_by' => $row['created_by']
        ];
    }

    // Send the response
    echo json_encode([
        'status' => 'success',
        'data' => [
            'todays_orders' => $todays_orders,
            'todays_sales' => $todays_sales,
            'weekly_sales' => $weekly_sales,
            'monthly_sales' => $monthly_sales,
            'todays_customers' => $todays_customers,
            'total_admins' => $total_admins,
            'total_cashiers' => $total_cashiers,
            'todays_orders_list' => $todays_orders_list
        ]
    ]);

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

mysqli_close($conn);
?>