<?php
session_start();
header('Content-Type: application/json');
include '../includes/db_connection.php';
include '../includes/sendResponse.php';

try {
    // Check authorization
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        throw new Exception('Unauthorized access');
    }

    // Get POST data
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data');
    }

    // Set default date range if not provided
    $dateRange = isset($data['dateRange']) ? $data['dateRange'] : 'today';

    // Build date condition
    switch ($dateRange) {
        case 'today':
            $dateCondition = "DATE(o.created_at) = CURDATE()";
            break;
        case 'yesterday':
            $dateCondition = "DATE(o.created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
            break;
        case 'week':
            $dateCondition = "DATE(o.created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            break;
        case 'month':
            $dateCondition = "DATE(o.created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            break;
        case 'custom':
            if (empty($data['startDate']) || empty($data['endDate'])) {
                throw new Exception('Start date and end date are required for custom range');
            }
            $startDate = mysqli_real_escape_string($conn, $data['startDate']);
            $endDate = mysqli_real_escape_string($conn, $data['endDate']);
            $dateCondition = "DATE(o.created_at) BETWEEN '$startDate' AND '$endDate'";
            break;
        default:
            $dateCondition = "DATE(o.created_at) = CURDATE()";
    }

    // Get summary data
    $summaryQuery = "SELECT 
        COUNT(*) as total_orders,
        COUNT(DISTINCT o.customer_name) as total_customers,
        COALESCE(SUM(o.total_amount), 0) as total_sales,
        COALESCE(AVG(o.total_amount), 0) as average_sale
    FROM orders o 
    WHERE $dateCondition";

    $summaryResult = mysqli_query($conn, $summaryQuery);
    if (!$summaryResult) {
        throw new Exception("Error getting summary: " . mysqli_error($conn));
    }
    $summary = mysqli_fetch_assoc($summaryResult);

    // Get sales data with items
    $salesQuery = "SELECT 
        o.*,
        GROUP_CONCAT(
            CONCAT(oi.quantity, 'x ', p.name)
            SEPARATOR ', '
        ) as items
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    WHERE $dateCondition
    GROUP BY o.id, o.created_at, o.customer_name, o.customer_phone, 
             o.total_amount, o.created_by
    ORDER BY o.created_at DESC";

    $salesResult = mysqli_query($conn, $salesQuery);
    if (!$salesResult) {
        throw new Exception("Error getting sales: " . mysqli_error($conn));
    }

    $sales = [];
    while ($row = mysqli_fetch_assoc($salesResult)) {
        // Format any null values
        $row['created_by'] = $row['created_by'] ?: 'Cashier 1';
        $row['items'] = $row['items'] ?: 'No items';
        $sales[] = $row;
    }

    // Send response
    sendResponse('success', 'Data retrieved successfully', [
        'total_sales' => (float)$summary['total_sales'],
        'total_orders' => (int)$summary['total_orders'],
        'total_customers' => (int)$summary['total_customers'],
        'average_sale' => (float)$summary['average_sale'],
        'sales' => $sales
    ]);

} catch (Exception $e) {
    error_log('Sales Error: ' . $e->getMessage());
    sendResponse('error', $e->getMessage());
} finally {
    if (isset($conn)) {
        mysqli_close($conn);
    }
}