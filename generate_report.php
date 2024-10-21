<?php
require_once 'config.php';
require_once 'functions.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['user_id']) || get_user_role($_SESSION['user_id']) != ROLE_ADMIN) {
    http_response_code(403);
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report_type = $_POST['report_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $report_data = generate_report($report_type, $start_date, $end_date);

    $chart_data = prepare_chart_data($report_type, $report_data);

    $response = [
        'html' => generate_report_html($report_type, $report_data),
        'chartType' => $chart_data['type'],
        'chartData' => $chart_data['data'],
        'chartOptions' => $chart_data['options']
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}

function generate_report($report_type, $start_date, $end_date) {
    $conn = db_connect();
    
    switch ($report_type) {
        case 'daily_sales':
            $query = "SELECT DATE(created_at) as date, SUM(total_amount) as total_sales, COUNT(*) as order_count 
                      FROM orders 
                      WHERE created_at BETWEEN ? AND ? 
                      GROUP BY DATE(created_at) 
                      ORDER BY date";
            break;
        case 'product_performance':
            $query = "SELECT p.name, SUM(oi.quantity) as total_quantity, SUM(oi.quantity * oi.price) as total_sales 
                      FROM order_items oi 
                      JOIN products p ON oi.product_id = p.id 
                      JOIN orders o ON oi.order_id = o.id 
                      WHERE o.created_at BETWEEN ? AND ? 
                      GROUP BY p.id 
                      ORDER BY total_sales DESC 
                      LIMIT 10";
            break;
        case 'category_sales':
            $query = "SELECT c.name, SUM(oi.quantity * oi.price) as total_sales 
                      FROM order_items oi 
                      JOIN products p ON oi.product_id = p.id 
                      JOIN categories c ON p.category_id = c.id 
                      JOIN orders o ON oi.order_id = o.id 
                      WHERE o.created_at BETWEEN ? AND ? 
                      GROUP BY c.id 
                      ORDER BY total_sales DESC";
            break;
        default:
            return [];
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function prepare_chart_data($report_type, $report_data) {
    switch ($report_type) {
        case 'daily_sales':
            return [
                'type' => 'line',
                'data' => [
                    'labels' => array_column($report_data, 'date'),
                    'datasets' => [
                        [
                            'label' => 'Daily Sales',
                            'data' => array_column($report_data, 'total_sales'),
                            'borderColor' => 'rgb(75, 192, 192)',
                            'tension' => 0.1
                        ]
                    ]
                ],
                'options' => [
                    'responsive' => true,
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true
                        ]
                    ]
                ]
            ];
        case 'product_performance':
        case 'category_sales':
            return [
                'type' => 'bar',
                'data' => [
                    'labels' => array_column($report_data, 'name'),
                    'datasets' => [
                        [
                            'label' => 'Total Sales',
                            'data' => array_column($report_data, 'total_sales'),
                            'backgroundColor' => 'rgba(75, 192, 192, 0.6)'
                        ]
                    ]
                ],
                'options' => [
                    'responsive' => true,
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true
                        ]
                    ]
                ]
            ];
        default:
            return [];
    }
}

function generate_report_html($report_type, $report_data) {
    $html = "<h3>" . ucfirst(str_replace('_', ' ', $report_type)) . " Report</h3>";
    $html .= "<canvas id='reportChart'></canvas>";
    $html .= "<table class='table table-striped mt-4'><thead><tr>";
    
    foreach (array_keys($report_data[0]) as $header) {
        $html .= "<th>" . ucfirst(str_replace('_', ' ', $header)) . "</th>";
    }
    
    $html .= "</tr></thead><tbody>";
    
    foreach ($report_data as $row) {
        $html .= "<tr>";
        foreach ($row as $value) {
            $html .= "<td>$value</td>";
        }
        $html .= "</tr>";
    }
    
    $html .= "</tbody></table>";
    
    return $html;
}