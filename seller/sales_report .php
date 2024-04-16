<?php
// Include necessary PHP code to fetch and process data for the chart
include 'connect.php';

// Fetch data from the database
$query = "SELECT * FROM orders";
$result = $db->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$result->free_result();
$db->close();

// Process data for the chart
$orderDates = [];
$totalAmounts = [];

foreach ($data as $row) {
    $orderDates[] = $row['order_date'];
    $totalAmounts[] = $row['total_amount'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Sales Report</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="sidebar close">
        <?php include "sidebar.php"; ?>
    </div>
    <section class="home-section">
        <div class="home-content">
        <i class='bx bx-menu' ></i>
        <span class="text">Reports</span>
        </div>
        <!-- Chart Section -->
        <div style="width: 800px; height: 400px; margin: 20px auto;">
            <label for="chartType">Select Chart Type:</label>
            <select id="chartType" onchange="updateChartType()">
                <option value="bar">Bar Chart</option>
                <option value="line">Line Graph</option>
                <option value="pie">Pie Chart</option>
            </select>
            <canvas id="myChart" width="800" height="400"></canvas>
        </div>

        <!-- Summary Section -->
        <div>
            <h1>Data Analytics Report Summary</h1>
            <?php

            $totalOrders = count($data);
            $totalAmount = array_sum(array_column($data, 'total_amount'));
            $averageAmount = $totalOrders > 0 ? $totalAmount / $totalOrders : 0;

            // Additional logic to analyze data and generate dynamic summary
            $summaryMessage = "No specific summary available.";

            if ($totalOrders > 0) {
                if ($averageAmount > 0) {
                    $summaryMessage = "The average order amount is positive.";
                } else {
                    $summaryMessage = "The average order amount is not available or zero.";
                }
            } else {
                $summaryMessage = "No orders data available.";
            }
            ?>

        <!-- Summary Section -->
        <div>
            <p>Total Orders: <?php echo $totalOrders; ?></p>
            <p>Total Amount: <?php echo $totalAmount; ?></p>
            <p>Average Amount: <?php echo $averageAmount; ?></p>
            <p>Summary: <?php echo $summaryMessage; ?></p>
        </div>
        </div>


        <!-- Trending Section -->
        <div>
            <h2>Trending</h2>
            <?php
            echo "<p>Orders are trending positively this month.</p>";
            ?>
        </div>

        <!-- Explanation Section -->
        <div>
            <h2>Explanation</h2>
            <?php
            echo "<p>The increase in total amount is due to high-value orders from new clients.</p>";
            ?>
        </div>
    </section>

    <!-- JavaScript for Chart -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const orderDates = <?php echo json_encode($orderDates); ?>;
            const totalAmounts = <?php echo json_encode($totalAmounts); ?>;
            let chartType = 'bar';
            let chart = null;

            function updateChartType() {
                chartType = document.getElementById('chartType').value;
                updateChart();
            }

            function updateChart() {
                const ctx = document.getElementById('myChart').getContext('2d');
                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: chartType,
                    data: {
                        labels: orderDates,
                        datasets: [{
                            label: 'Total Amount',
                            data: totalAmounts,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            title: {
                                display: true,
                                text: 'Sales Report'
                            }
                        },
                        scales: {
                            x: {
                                type: 'category',
                                labels: orderDates,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Order Date'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Total Amount'
                                }
                            }
                        }
                    }
                });
            }

            updateChart();
        });
    </script>

</body>

</html>
