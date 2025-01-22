<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/auth.php');

requireAdmin();

try {
    $dateRange = $_GET['dateRange'] ?? 'today';
    
    // Build date condition with specific table alias 'o'
    switch($dateRange) {
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
            $startDate = $_GET['startDate'];
            $endDate = $_GET['endDate'];
            $dateCondition = "DATE(o.created_at) BETWEEN '$startDate' AND '$endDate'";
            break;
        default:
            $dateCondition = "1=1";
    }

    // Get sales data with specific table aliases
    $query = "SELECT 
                o.id,
                o.created_at,
                o.customer_name,
                o.customer_phone,
                o.total_amount,
                GROUP_CONCAT(
                    CONCAT(oi.quantity, 'x ', p.name)
                    SEPARATOR ', '
                ) as items
              FROM orders o
              LEFT JOIN order_items oi ON o.id = oi.order_id
              LEFT JOIN products p ON oi.product_id = p.id
              WHERE $dateCondition
              GROUP BY o.id, o.created_at, o.customer_name, o.customer_phone, o.total_amount
              ORDER BY o.created_at DESC";
    
    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Query error: " . mysqli_error($conn));
    }

    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="sales_report_' . date('Y-m-d') . '.csv"');

    // Create output stream
    $output = fopen('php://output', 'w');

    // Add UTF-8 BOM for proper Excel encoding
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Add headers
    fputcsv($output, [
        'Order ID',
        'Date',
        'Customer Name',
        'Contact',
        'Items',
        'Total Amount (GHS)'
    ]);

    // Add data rows
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['id'],
            date('Y-m-d H:i', strtotime($row['created_at'])),
            $row['customer_name'],
            $row['customer_phone'],
            $row['items'],
            number_format($row['total_amount'], 2)
        ]);
    }

    // Close the output stream
    fclose($output);

} catch (Exception $e) {
    // If there's an error, return JSON response
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} finally {
    mysqli_close($conn);
}
?>