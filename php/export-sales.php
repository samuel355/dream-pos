<?php
session_start();
include_once('../includes/db_connection.php');
include_once('../includes/auth.php');

requireAdmin();

try {
    // Get POST data
    $dateRange = $_POST['dateRange'] ?? 'today';
    $startDate = $_POST['startDate'] ?? '';
    $endDate = $_POST['endDate'] ?? '';

    // Build date condition
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
            if ($startDate && $endDate) {
                $startDate = mysqli_real_escape_string($conn, $startDate);
                $endDate = mysqli_real_escape_string($conn, $endDate);
                $dateCondition = "DATE(o.created_at) BETWEEN '$startDate' AND '$endDate'";
            } else {
                $dateCondition = "1=1";
            }
            break;
        default:
            $dateCondition = "1=1";
    }

    // Get sales data
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
              GROUP BY o.id
              ORDER BY o.created_at DESC";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        throw new Exception("Query error: " . mysqli_error($conn));
    }

    // Prepare filename
    $filename = 'sales_report_' . date('Y-m-d_H-i-s') . '.csv';

    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Create output stream
    $output = fopen('php://output', 'w');

    // Add UTF-8 BOM for proper Excel encoding
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Add report title and date range
    fputcsv($output, ['Sales Report']);
    fputcsv($output, ['Date Range:', $dateRange]);
    if ($dateRange === 'custom') {
        fputcsv($output, ['From:', $startDate, 'To:', $endDate]);
    }
    fputcsv($output, []); // Empty line

    // Add headers
    fputcsv($output, [
        'Order ID',
        'Date & Time',
        'Customer Name',
        'Contact',
        'Items',
        'Total Amount (GHS)'
    ]);

    // Add data rows
    $totalAmount = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            '#' . $row['id'],
            date('Y-m-d H:i', strtotime($row['created_at'])),
            $row['customer_name'],
            $row['customer_phone'],
            $row['items'],
            number_format($row['total_amount'], 2)
        ]);
        $totalAmount += $row['total_amount'];
    }

    // Add summary
    fputcsv($output, []); // Empty line
    fputcsv($output, ['Total:', '', '', '', '', number_format($totalAmount, 2)]);

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