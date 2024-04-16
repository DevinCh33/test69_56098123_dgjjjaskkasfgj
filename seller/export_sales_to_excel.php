<?php

// Include necessary PHP code to establish database connection
include 'connect.php';

// Set headers for Excel file download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=sales_report.xlsx");

// Default values for date range
$start_date = "";
$end_date = "";

// Check if session variables for date range are set
if (isset($_POST["start_date"]) && isset($_POST["end_date"])) {
    // Get the start date and end date from the form
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];

    // Perform the SQL query based on date range
    if (!empty($start_date) && !empty($end_date)) {
        // Both start date and end date are selected
        $sql = "SELECT oi.order_item_id AS item_no, u.username, o.order_date, p.product_name, p.descr AS item_description, ROUND(tp.proPrice, 2) AS price, oi.quantity,
                ROUND(tp.proPrice * oi.quantity, 2) AS amount, p.product_image, r.title AS owner, uc.comment
            FROM order_item oi
            INNER JOIN tblprice tp ON oi.priceID = tp.priceNo
            INNER JOIN product p ON tp.productID = p.product_id
            INNER JOIN orders o ON oi.order_id = o.order_id
            INNER JOIN users u ON o.user_id = u.u_id
            LEFT JOIN restaurant r ON p.owner = r.rs_id
            LEFT JOIN user_comments uc ON u.u_id = uc.user_id
            WHERE o.order_date BETWEEN '$start_date' AND '$end_date'
            ORDER BY item_no ASC"; // Order by item_no in ascending order
    } else {
        // Neither start date nor end date is selected
        $sql = "SELECT oi.order_item_id AS item_no, u.username, o.order_date, p.product_name, p.descr AS item_description, ROUND(tp.proPrice, 2) AS price, oi.quantity,
                tp.proPrice * oi.quantity AS amount, p.product_image, r.title AS owner, uc.comment
            FROM order_item oi
            INNER JOIN tblprice tp ON oi.priceID = tp.priceNo
            INNER JOIN product p ON tp.productID = p.product_id
            INNER JOIN orders o ON oi.order_id = o.order_id
            INNER JOIN users u ON o.user_id = u.u_id
            LEFT JOIN restaurant r ON p.owner = r.rs_id
            LEFT JOIN user_comments uc ON u.u_id = uc.user_id
            ORDER BY item_no ASC"; // Order by item_no in ascending order
    }

    // Execute the query
    $result = mysqli_query($db, $sql);

    // Initialize total sales amount
    $total_sales = 0;

    // Output Excel file content
    echo "<table border='1'>";
    echo "<tr><th colspan='10'>Sales Report</th></tr>";
    echo "<tr><td colspan='2'>Start Date:</td><td colspan='8'>$start_date</td></tr>";
    echo "<tr><td colspan='2'>End Date:</td><td colspan='8'>$end_date</td></tr>";
    echo "<tr><th>ITEM NO</th><th>USER NAME</th><th>PRODUCT NAME</th><th>DESCRIPTION</th><th>PRICE (RM)</th><th>QUANTITY</th><th>TOTAL (RM)</th><th>OWNER</th><th>COMMENT</th><th>DATE</th></tr>";
    
    // Initialize item number counter
    $item_no = 1;
    
    // Fetch and output each row of the result
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $item_no . "</td>"; // Display item_no starting from 1
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['product_name'] . "</td>";
        echo "<td>" . $row['item_description'] . "</td>";
        echo "<td>" . number_format($row['price'], 2) . "</td>"; // Format price with 2 decimal places
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>" . number_format($row['amount'], 2) . "</td>"; 
        echo "<td>" . $row['owner'] . "</td>";
        echo "<td>" . $row['comment'] . "</td>";
        echo "<td>" . $row['order_date'] . "</td>";

        // Add amount to total sales
        $total_sales += $row['amount'];

        // Increment item number
        $item_no++;

        echo "</tr>";
    }

    // Add total sales row
    echo "<tr><td colspan='8' align='right'><strong>Total Sales:</strong></td><td colspan='2'>" . number_format($total_sales, 2) . "</td></tr>";

    echo "</table>";

    // Close the database connection
    mysqli_close($db);
}
?>
