<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Combined Graphs</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        h1{
            text-decoration: underline;
            font-size: 30px;
        }
        table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 50px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50; /* Green */
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }
        .chart-container {
            width: 80%;
            height: 80%;
            margin: 5% auto 30% auto;
        }

        .export-button {
            background-color: #4CAF50; /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
            }

        /* The Modal (background) */
        .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
        position: relative;
        background-color: #fefefe;
        margin: auto;
        padding: 0;
        border: 1px solid #888;
        width: 80%;
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
        -webkit-animation-name: animatetop;
        -webkit-animation-duration: 0.4s;
        animation-name: animatetop;
        animation-duration: 0.4s
        }

        /* Add Animation */
        @-webkit-keyframes animatetop {
        from {top:-300px; opacity:0} 
        to {top:0; opacity:1}
        }

        @keyframes animatetop {
        from {top:-300px; opacity:0}
        to {top:0; opacity:1}
        }

        /* The Close Button */
        .close {
        color: white;
        float: right;
        font-size: 28px;
        font-weight: bold;
        }

        .close:hover,
        .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
        }

        .modal-header {
        padding: 2px 16px;
        background-color: #5cb85c;
        color: white;
        }

        .modal-body {padding: 30px;}

        .modal-footer {
        padding: 2px 16px;
        background-color: #5cb85c;
        color: white;
        }
    </style>
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
        <h1 style = "text-align:center;">Product Report</h1>
        <div class="chart-container">
    <h1>Sales Analysis</h1>
    <canvas id="ordersChart" width="400" height="200"></canvas>
    <div>

    <?php
        // Include necessary PHP code to establish database connection
        include 'connect.php';

        // Perform the SQL query to get the quantity of orders over time
        $sql_orders = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS order_month, SUM(quantity) AS total_quantity 
                    FROM order_item oi
                    JOIN orders o ON oi.order_id = o.order_id
                    GROUP BY DATE_FORMAT(order_date, '%Y-%m') 
                    ORDER BY order_date";

        $result_orders = mysqli_query($db, $sql_orders);

        // Fetch order data into an array
        $data_orders = array();
        while ($row_orders = mysqli_fetch_assoc($result_orders)) {
            $data_orders[] = $row_orders;
        }

        // Perform the SQL query to get the total sales over time
        $sql_total_sales = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS order_month, SUM(p.proPrice * oi.quantity) AS total_sales
                            FROM order_item oi
                            INNER JOIN tblprice p ON oi.priceID = p.priceNo
                            INNER JOIN product pr ON p.productID = pr.product_id
                            INNER JOIN orders o ON oi.order_id = o.order_id
                            GROUP BY DATE_FORMAT(order_date, '%Y-%m') 
                            ORDER BY order_date";

        $result_total_sales = mysqli_query($db, $sql_total_sales);

        // Fetch total sales data into an array
        $data_total_sales = array();
        while ($row_sales = mysqli_fetch_assoc($result_total_sales)) {
            $data_total_sales[] = $row_sales;
        }

        // Close the database connection
        mysqli_close($db);
        // Explanation message to be displayed
        $explanation_message = "This chart represents the sales analysis over time. It shows the quantity of orders made each month, as well as the total sales generated.";

        // Print the explanation message within a <p> tag
        echo "<p>$explanation_message</p>";
    ?>

    <script>
        var dataOrders = <?php echo json_encode($data_orders); ?>;
        var dataTotalSales = <?php echo json_encode($data_total_sales); ?>;

        // Extract labels (order months), quantity data, and total sales data
        var labels = dataOrders.map(item => item.order_month);
        var quantityData = dataOrders.map(item => item.total_quantity);
        var totalSalesData = dataTotalSales.map(item => item.total_sales);

        var ctx = document.getElementById('ordersChart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Quantity of Orders',
                    data: quantityData,
                    yAxisID: 'y', // Associate with the 'y' axis (left)
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }, {
                    label: 'Total Sales',
                    data: totalSalesData,
                    yAxisID: 'y1', // Associate with the 'y1' axis (right)
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Month',
                            color: 'black',
                            font: {
                                family: 'Comic Sans MS',
                                size: 20,
                                weight: 'bold',
                                lineHeight: 1.2,
                            },
                            padding: {top: 20, left: 0, right: 0, bottom: 0}
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Quantity',
                            color: 'black',
                            font: {
                                family: 'Comic Sans MS',
                                size: 20,
                                weight: 'bold',
                                lineHeight: 1.2,
                            },
                            padding: {top: 30, left: 0, right: 0, bottom: 50}
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Total Sales',
                            color: 'black',
                            font: {
                                family: 'Comic Sans MS',
                                size: 20,
                                weight: 'bold',
                                lineHeight: 1.2,
                            },
                            padding: {top: 30, left: 0, right: 0, bottom: 50}
                        },
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });

    </script>


        </div>


        <div class="chart-container" >
            <h1>Market Share Pie Chart</h1>
            <canvas id="marketShareChart" width="800" height="400"></canvas>

           <?php
                // Include necessary PHP code to establish database connection
                include 'connect.php';

                // Perform the SQL query to calculate total sales amount for each product
                $sql = "SELECT p.product_name, SUM(oi.quantity * tp.proPrice) AS total_sales
                        FROM order_item oi
                        JOIN tblprice tp ON oi.priceID = tp.priceNo
                        JOIN product p ON tp.productID = p.product_id
                        GROUP BY p.product_id";

                $result = mysqli_query($db, $sql);

                // Fetch data into an array
                $data = array();
                while ($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }

                // Calculate total sales across all products
                $totalSales = array_sum(array_column($data, 'total_sales'));

                // Calculate market share for each product and generate explanation
                $graphExplanation = "<p><strong>Market Share Pie Chart Explanation:</strong></p>";
                $graphExplanation .= "<p>The Market Share Pie Chart illustrates the distribution of sales among different products based on the data obtained.</p>";

                foreach ($data as &$product) {
                    // Calculate market share percentage for each product
                    $product['market_share'] = ($product['total_sales'] / $totalSales) * 100;
                    $productName = $product['product_name'];
                    $totalSalesAmount = $product['total_sales'];
                    $marketSharePercentage = round($product['market_share'], 2);

                }

              
                // Close the database connection
                mysqli_close($db);

                // Output the graph explanation message
                echo $graphExplanation;
            ?>


            <script>
                var data = <?php echo json_encode($data); ?>;
                
                // Extract product names and market share percentages
                var productNames = data.map(item => item.product_name);
                var marketShareData = data.map(item => item.market_share);

                var ctx = document.getElementById('marketShareChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'pie', // Use pie chart for market share
                    data: {
                        labels: productNames,
                        datasets: [{
                            label: 'Market Share (%)',
                            data: marketShareData,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 206, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(255, 159, 64, 0.2)'
                                // Add more colors if needed
                            ],
                            borderColor: [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)'
                                // Add more colors if needed
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true
                    }
                });
            </script>
        </div>

        <div class="chart-container" >
            <h1>Product Performance by Region Graph</h1>
            <canvas id="productPerformanceChart" width="800" height="400"></canvas>

            <?php
            // Include necessary PHP code to establish database connection
            include 'connect.php';

            // Perform the SQL query to calculate total sales amount for each region and product
            $sql = "SELECT p.product_name, c.categories_name, SUM(oi.quantity * tp.proPrice) AS total_sales
                    FROM order_item oi
                    JOIN tblprice tp ON oi.priceID = tp.priceNo
                    JOIN product p ON tp.productID = p.product_id
                    JOIN categories c ON p.categories_id = c.categories_id
                    GROUP BY p.product_id, c.categories_id";

            $result = mysqli_query($db, $sql);

            // Fetch data into an array
            $data = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }

            // Close the database connection
            mysqli_close($db);

          // Calculate total sales for each category
            $categorySales = array();
            foreach ($data as $item) {
                $category = $item['categories_name'];
                $sales = $item['total_sales'];
                if (!isset($categorySales[$category])) {
                    $categorySales[$category] = 0;
                }
                $categorySales[$category] += $sales;
            }

            // Find the most popular category
            $mostPopularCategory = '';
            $maxSales = 0;
            foreach ($categorySales as $category => $sales) {
                if ($sales > $maxSales) {
                    $mostPopularCategory = $category;
                    $maxSales = $sales;
                }
            }
            echo "<p>The graph illustrates product sales across various categories and regions. Each bar represents the total sales volume of a product category, providing insights into relative performance and identifying popular categories.</p>";

            // Output the message
            echo "<p>The most popular product category, <strong>$mostPopularCategory</strong>, leads with total sales reaching <strong>RM" . number_format($maxSales, 2) . "</strong>. This insight assists in directing marketing strategies and optimizing inventory based on sales performance.</p>";

            ?>

            <script>
                    var data = <?php echo json_encode($data); ?>;
                    
                    // Extract product names, region names, and total sales data
                    var productNames = [];
                    var regionNames = [];
                    var salesData = [];

                    data.forEach(item => {
                        if (!productNames.includes(item.product_name)) {
                            productNames.push(item.product_name);
                        }
                        if (!regionNames.includes(item.categories_name)) {
                            regionNames.push(item.categories_name);
                        }
                    });

                    // Initialize sales data array
                    for (var i = 0; i < productNames.length; i++) {
                        salesData[i] = new Array(regionNames.length).fill(0);
                    }

                    // Populate sales data array
                    data.forEach(item => {
                        var productIndex = productNames.indexOf(item.product_name);
                        var regionIndex = regionNames.indexOf(item.categories_name);
                        salesData[productIndex][regionIndex] = item.total_sales;
                    });

                    var ctx = document.getElementById('productPerformanceChart').getContext('2d');
                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: productNames,
                            datasets: regionNames.map((region, index) => {
                                return {
                                    label: region,
                                    data: salesData.map(product => product[index]),
                                    backgroundColor: 'rgba(' + (Math.random() * 255) + ', ' + (Math.random() * 255) + ', ' + (Math.random() * 255) + ', 0.5)',
                                    borderColor: 'rgba(' + (Math.random() * 255) + ', ' + (Math.random() * 255) + ', ' + (Math.random() * 255) + ', 1)',
                                    borderWidth: 1
                                };
                            })
                        },
                        options: {
                        responsive: true,
                        scales: {
                            x: {
                                display: true,
                                title: {
                                    display: true,
                                    text: 'Product Names',
                                    color: 'black',
                                    font: {
                                        family: 'Comic Sans MS',
                                        size: 20,
                                        weight: 'bold',
                                        lineHeight: 1.2,
                                    },
                                    padding: {top: 20, left: 0, right: 0, bottom: 0}
                                }
                            },
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'Total Sales',
                                    color: 'black',
                                    font: {
                                        family: 'Comic Sans MS',
                                        size: 20,
                                        weight: 'bold',
                                        lineHeight: 1.2,
                                    },
                                    padding: {top: 30, left: 0, right: 0, bottom: 50}
                                }
                            }
                        }
                    }

                    });
                </script>
        </div>

        <div class="chart-container" style="margin-top: 10%;">
    <h1>Sales Report</h1>
    <?php
    // Start the session
    session_start();

    // Include necessary PHP code to establish database connection
    include 'connect.php';

    // Default values for date range
    $start_date = "";
    $end_date = "";

    // Check if session variables for date range are set
    if(isset($_SESSION["start_date"]) || isset($_SESSION["end_date"])) {
        // Use session values if available
        $start_date = $_SESSION["start_date"];
        $end_date = $_SESSION["end_date"];
    }

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get the start date and end date from the form
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];

        // Store the search parameters in session variables
        $_SESSION["start_date"] = $start_date;
        $_SESSION["end_date"] = $end_date;

        // Perform the SQL query based on date range
        if (!empty($start_date) && !empty($end_date)) {
            // Both start date and end date are selected
            $sql = "SELECT oi.order_item_id AS item_no, u.username, o.order_date, p.product_name, p.descr AS item_description, tp.proPrice AS price, oi.quantity,
                    (tp.proPrice * oi.quantity) AS amount, p.product_image, r.title AS owner, uc.comment
                FROM order_item oi
                INNER JOIN tblprice tp ON oi.priceID = tp.priceNo
                INNER JOIN product p ON tp.productID = p.product_id
                INNER JOIN orders o ON oi.order_id = o.order_id
                INNER JOIN users u ON o.user_id = u.u_id
                LEFT JOIN restaurant r ON p.owner = r.rs_id
                LEFT JOIN user_comments uc ON u.u_id = uc.user_id
                WHERE o.order_date BETWEEN '$start_date' AND '$end_date'
                ORDER BY item_no ASC";
        } elseif (!empty($start_date)) {
            // Only start date is selected
            $sql = "SELECT oi.order_item_id AS item_no, u.username, o.order_date, p.product_name, p.descr AS item_description, tp.proPrice AS price, oi.quantity,
                    (tp.proPrice * oi.quantity) AS amount, p.product_image, r.title AS owner, uc.comment
                FROM order_item oi
                INNER JOIN tblprice tp ON oi.priceID = tp.priceNo
                INNER JOIN product p ON tp.productID = p.product_id
                INNER JOIN orders o ON oi.order_id = o.order_id
                INNER JOIN users u ON o.user_id = u.u_id
                LEFT JOIN restaurant r ON p.owner = r.rs_id
                LEFT JOIN user_comments uc ON u.u_id = uc.user_id
                WHERE o.order_date >= '$start_date'
                ORDER BY item_no ASC";
        } elseif (!empty($end_date)) {
            // Only end date is selected
            $sql = "SELECT oi.order_item_id AS item_no, u.username, o.order_date, p.product_name, p.descr AS item_description, tp.proPrice AS price, oi.quantity,
                    (tp.proPrice * oi.quantity) AS amount, p.product_image, r.title AS owner, uc.comment
                FROM order_item oi
                INNER JOIN tblprice tp ON oi.priceID = tp.priceNo
                INNER JOIN product p ON tp.productID = p.product_id
                INNER JOIN orders o ON oi.order_id = o.order_id
                INNER JOIN users u ON o.user_id = u.u_id
                LEFT JOIN restaurant r ON p.owner = r.rs_id
                LEFT JOIN user_comments uc ON u.u_id = uc.user_id
                WHERE o.order_date <= '$end_date'
                ORDER BY item_no ASC";
        } else {
            // Neither start date nor end date is selected
            $sql = "SELECT oi.order_item_id AS item_no, u.username, o.order_date, p.product_name, p.descr AS item_description, tp.proPrice AS price, oi.quantity,
                    (tp.proPrice * oi.quantity) AS amount, p.product_image, r.title AS owner, uc.comment
                FROM order_item oi
                INNER JOIN tblprice tp ON oi.priceID = tp.priceNo
                INNER JOIN product p ON tp.productID = p.product_id
                INNER JOIN orders o ON oi.order_id = o.order_id
                INNER JOIN users u ON o.user_id = u.u_id
                LEFT JOIN restaurant r ON p.owner = r.rs_id
                LEFT JOIN user_comments uc ON u.u_id = uc.user_id
                ORDER BY item_no ASC";
        }

        // Execute the query
        $result = mysqli_query($db, $sql);
    }

    // Initialize total sales amount
    $total_sales = 0;

    // Check if there are any results
    if (isset($result) && mysqli_num_rows($result) > 0) {
        echo "<div class='chart-container' style='margin-top: 10%;'>";
        echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
        echo "<label for='start_date'>Start Date:</label>";
        echo "<input type='date' name='start_date' id='start_date' value='$start_date'>";
        echo "<label for='end_date'>End Date:</label>";
        echo "<input type='date' name='end_date' id='end_date' value='$end_date'>";
        echo "<input type='submit' value='Search' name='search_sales'>";
        echo "<button type='submit' formaction='export_sales_to_excel.php' class='export-button'>Excel</button>"; // Add Export to Excel button with class
        echo "</form>";
        echo "<table border='1'>";
        echo "<tr><th>ITEM NO</th><th>ITEM NAME</th><th>PRICE (RM)</th><th>QUANTITY</th><th>TOTAL (RM)</th><th>DATE</th><th>DETAILS</th></tr>";

        // Initialize item number counter
        $item_no = 1;

        // Fetch and display each row of the result
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $item_no . "</td>";
            echo "<td>" . $row['product_name'] . "</td>";
            echo "<td>" . number_format($row['price'], 2) . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . number_format($row['amount'], 2) . "</td>";
            echo "<td>" . $row['order_date'] . "</td>";
            echo "<td><button onclick='showDetails(\"" . $row['username'] . "\", \"" . $row['product_name'] . "\", \"" . $row['item_description'] . "\", \"" . $row['product_image'] . "\", \"" . $row['order_date'] . "\", \"" . $row['owner'] . "\", \"" . $row['comment'] . "\", " . $row['price'] . ")'>Details</button></td>";

            // Add amount to total sales
            $total_sales += $row['amount'];

            // Increment item number
            $item_no++;

            echo "</tr>";
        }

        // Add total sales row
        echo "<tr><td colspan='6' align='right'><strong>Total Sales:</strong></td><td colspan='2'>" . number_format($total_sales, 2) . "</td></tr>";

        echo "</table>";
        echo "</div>";
    } else {
        echo "<div class='chart-container' style='margin-top: 10%;'>";
        echo "<h2>Sales Report</h2>";
        echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";
        echo "<label for='start_date'>Start Date:</label>";
        echo "<input type='date' name='start_date' id='start_date' value='$start_date'>";
        echo "<label for='end_date'>End Date:</label>";
        echo "<input type='date' name='end_date' id='end_date' value='$end_date'>";
        echo "<input type='submit' value='Search' name='search_sales'>";
        echo "</form>";
        echo "No sales data available.";
        echo "</div>";
    }

    // Close the database connection
    mysqli_close($db);
    ?>

    <script>
        function showDetails(username, productName, description, image, date, owner, comment, price) {
            // Get the modal
            var modal = document.getElementById("myModal");

            // Get the modal content
            var modalContent = document.getElementById("modal-content");

            // Set the details in the modal
            modalContent.innerHTML = `
                <div class="modal-header">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <h2>Details</h2>
                </div>
                <div class="modal-body">
                    <p><strong>User Name:</strong> ${username}</p>
                    <p><strong>Product Name:</strong> ${productName}</p>
                    <p><strong>Description:</strong> ${description}</p>
                    <p><strong>Date:</strong> ${date}</p>
                    <p><strong>Owner:</strong> ${owner}</p>
                    <p><strong>Comment:</strong> ${comment}</p>
                    <p><strong>Price:</strong> RM ${price.toFixed(2)}</p>
                    <img src="${image}" alt="Product Image" style="max-width: 200px; max-height: 200px;">
                </div>
            `;

            // Display the modal
            modal.style.display = "block";
        }

        function closeModal() {
            // Get the modal
            var modal = document.getElementById("myModal");

            // Hide the modal
            modal.style.display = "none";
        }
    </script>

    <!-- The Modal -->
    <div id="myModal" class="modal" >
        <!-- Modal content -->
        <div class="modal-content" id="modal-content" ></div>
    </div>
</div>



<div class="chart-container" style="margin-top: 10%;">
    <!-- Data Analytics Report Summary -->
    <h1>Product Review Table</h1>
    <!-- Display form to input restaurant name -->
    <form id="searchForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="restaurant_name">Restaurant Name:</label>
        <input type="text" id="restaurant_name" name="restaurant_name" value="<?php echo $restaurant_name; ?>">
        <input type="submit" value="Submit" name="search_products">
        <button type="button" id="excelBtn" class="export-button">Excel</button>
    </form>

    <?php
    session_start(); // Start the session

    // Include the connection file
    include 'connect.php';

    // Check if the form is submitted for searching products
    if (isset($_POST['search_products'])) {
        // Get the restaurant name from the form
        $restaurant_name = $_POST['restaurant_name'];

        // Store the search parameter in a session variable
        $_SESSION['restaurant_name'] = $restaurant_name;
    } elseif (!isset($_SESSION['restaurant_name'])) {
        // Set a default restaurant name if session variable is not set
        $_SESSION['restaurant_name'] = "";
    }

    // Retrieve the restaurant name from the session
    $restaurant_name = $_SESSION['restaurant_name'];

    // SQL query to retrieve product report data for the provided restaurant name
    $sql = "SELECT p.product_id AS 'No.', p.owner AS 'Restaurant ID', r.title AS 'Restaurant Title', CONCAT(p.product_name, ' (', tp.proWeight, ')') AS 'Product Name and Weight', p.descr AS 'Description', c.categories_name AS 'Category', p.quantity AS 'Quantity', p.product_date AS 'Product Date', p.lowStock AS 'Low Stock', FORMAT(tp.proPrice, 2) AS 'Price (RM)', oi.quantity AS 'Ordered Quantity'
            FROM product p
            JOIN categories c ON p.categories_id = c.categories_id
            JOIN tblprice tp ON p.product_id = tp.productID
            LEFT JOIN order_item oi ON tp.priceNo = oi.priceID
            LEFT JOIN restaurant r ON p.owner = r.rs_id
            WHERE r.title LIKE '%$restaurant_name%'";

    // Execute the query
    $result = $db->query($sql);

    // Initialize counter
    $counter = 1;

    // Check if there are results
    if ($result->num_rows > 0) {
        // Output table headers
        echo "<table id='dataTable'>";
        echo "<tr><th>NO.</th><th>RESTAURANT TITLE</th><th>PRODUCT NAME AND WEIGHT</th><th>DESCRIPTION</th><th>CATEGORY</th><th>PRODUCT DATE</th><th>PRICE (RM)</th><th>ORDERED QUANTITY</th></tr>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $counter . "</td>";
            echo "<td>" . $row["Restaurant Title"] . "</td>";
            echo "<td>" . $row["Product Name and Weight"] . "</td>";
            echo "<td>" . $row["Description"] . "</td>";
            echo "<td>" . $row["Category"] . "</td>";
            echo "<td>" . $row["Product Date"] . "</td>";
            echo "<td>" . $row["Price (RM)"] . "</td>";
            echo "<td>" . $row["Ordered Quantity"] . "</td>";
            echo "</tr>";

            // Increment counter
            $counter++;
        }
        echo "</table>"; // Close the table
    } else {
        // No results found
        echo "0 results";
    }

    // Close connection
    $db->close();
    ?>
</div>

<script>
    // Function to convert table to Excel file and download
    function exportToExcel() {
        var table = document.getElementById("dataTable");
        var html = table.outerHTML;
        var url = 'data:application/vnd.ms-excel,' + escape(html);
        var link = document.createElement("a");
        link.href = url;
        link.download = "product_review.xls";
        link.click();
    }

    // Add event listener to Excel button
    document.getElementById("excelBtn").addEventListener("click", exportToExcel);
</script>

    </section>
</body>
</html>