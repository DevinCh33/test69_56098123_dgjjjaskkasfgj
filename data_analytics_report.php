<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Data Analytics Report</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<?php
// Replace with your actual database connection code
include("connection/connect.php"); // connection to database

// Fetch data from the orders table
$query = "SELECT * FROM orders";
$result = $db->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$result->free_result();
$db->close();
?>

<!-- Chart Section -->
<div style="width: 500px; height: 500px; margin: 20px auto;">
    <label for="chartType">Select Chart Type:</label>
    <select id="chartType" onchange="updateChartType()">
        <option value="bar">Bar Chart</option>
        <option value="line">Line Graph</option>
        <option value="pie">Pie Chart</option>
    </select>
    <canvas id="myChart" width="500" height="500"></canvas>
</div>

<!-- Summary Section -->
<div>
    <h1>Data Analytics Report Summary</h1>
    <?php
// Include necessary PHP code to fetch and process data for the summary
include("connection/connect.php"); // connection to database

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

<!-- JavaScript for Chart -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const orderDates = <?php echo json_encode(array_column($data, 'order_date')); ?>;
        const totalAmounts = <?php echo json_encode(array_column($data, 'total_amount')); ?>;
        let chartType = 'bar'; // Default chart type
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
                            text: 'Data Analytics Report'
                        }
                    },
                    scales: {
                        x: {
                            type: 'category',
                            labels: orderDates,
                            scaleLabel: {
                                display: true,
                                labelString: 'X Axis Label'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Y Axis Label'
                            }
                        }
                    }
                }
            });
        }

        // Call the updateChartType function initially and when the dropdown changes
        updateChartType();

        // Optionally, you can add an event listener to dynamically update the chart
        // when the dropdown changes.
        document.getElementById('chartType').addEventListener('change', updateChartType);
    });
</script>





</body>

</html>
