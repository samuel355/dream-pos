<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/sendResponse.php');
include_once('../includes/auth.php');

if (!isAdmin() || !isSysAdmin()) {
    sendResponse('error', 'Unauthorized Action. Only Admins can perform this action');
}

try {
    // Get and decode JSON data
    $jsonData = file_get_contents('php://input');
    if (!$jsonData) {
        throw new Exception('No data received');
    }

    $data = json_decode($jsonData, true);
    if (!$data) {
        throw new Exception('Invalid JSON data');
    }

    $dateRange = $data['dateRange'] ?? 'today';

    // Build date condition
    switch ($dateRange) {
        case 'today':
            $dateCondition = "DATE(o.created_at) = CURRENT_DATE()";
            break;
        case 'yesterday':
            $dateCondition = "DATE(o.created_at) = DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY)";
            break;
        case 'week':
            $dateCondition = "YEARWEEK(o.created_at) = YEARWEEK(CURRENT_DATE())";
            break;
        case 'month':
            $dateCondition = "YEAR(o.created_at) = YEAR(CURRENT_DATE()) AND MONTH(o.created_at) = MONTH(CURRENT_DATE())";
            break;
        case 'custom':
            $startDate = mysqli_real_escape_string($conn, $data['startDate']);
            $endDate = mysqli_real_escape_string($conn, $data['endDate']);
            $dateCondition = "DATE(o.created_at) BETWEEN '$startDate' AND '$endDate'";
            break;
        default:
            $dateCondition = "1=1";
    }

    // Get sales summary
    $summaryQuery = "SELECT 
                COUNT(*) as total_orders,
                COUNT(DISTINCT o.customer_name) as total_customers,
                COALESCE(SUM(o.total_amount), 0) as total_sales,
                COALESCE(AVG(o.total_amount), 0) as average_sale
              FROM orders o 
              WHERE $dateCondition";

    $summaryResult = mysqli_query($conn, $summaryQuery);
    if (!$summaryResult) {
        throw new Exception("Summary query error: " . mysqli_error($conn));
    }
    $summary = mysqli_fetch_assoc($summaryResult);

    // Get detailed sales data
    $detailsQuery = "SELECT 
                o.*, 
                GROUP_CONCAT(
                    CONCAT(oi.quantity, 'x ', p.name)
                    SEPARATOR ', '
                ) as items
              FROM orders o
              LEFT JOIN order_items oi ON o.id = oi.order_id
              LEFT JOIN products p ON oi.product_id = p.id
              WHERE $dateCondition
              GROUP BY o.id
              ORDER BY o.created_at DESC";

    $detailsResult = mysqli_query($conn, $detailsQuery);
    if (!$detailsResult) {
        throw new Exception("Details query error: " . mysqli_error($conn));
    }

    $sales = [];
    while ($row = mysqli_fetch_assoc($detailsResult)) {
        $sales[] = $row;
    }

    echo json_encode([
        'status' => 'success',
        'data' => [
            'total_sales' => $summary['total_sales'],
            'total_orders' => $summary['total_orders'],
            'total_customers' => $summary['total_customers'],
            'average_sale' => $summary['average_sale'],
            'sales' => $sales
        ]
    ]);
} catch (Exception $e) {
    error_log("Sales Error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    mysqli_close($conn);
}
