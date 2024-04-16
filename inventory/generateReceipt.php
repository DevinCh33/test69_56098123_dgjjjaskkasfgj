<?php

require_once 'core.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = strtotime($_POST['startDate']);
    $start_date = date("Y/m/d", $startDate);

    $endDate = strtotime($_POST['endDate']);
    $end_date = date("Y/m/d", $endDate);

    $sql = "SELECT * FROM orders WHERE order_date >= '$start_date' AND order_date <= '$end_date' AND order_status = '3'";
    $query = $db->query($sql);

    if ($query->num_rows > 0) {
        $receiptContent = "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);'>";
        $receiptContent .= "<h2 style='text-align: center; color: #333;'>Financial Report</h2>";
        $receiptContent .= "<div style='display: flex; justify-content: space-between; align-items: center; margin: 10px 0;'>";
        $receiptContent .= "<p><strong>Date:</strong> " . date("Y-m-d H:i:s") . "</p>";
        $receiptContent .= "<p><strong>Period:</strong> $start_date to $end_date</p>";
        $receiptContent .= "</div>";
        $receiptContent .= "<table style='width:100%; border-collapse: collapse; margin-top: 20px;'>";
        // Adding Total Price column header
        $receiptContent .= "<tr><th style='text-align: left; border: 1px solid #ddd; padding: 10px; background-color: #f2f2f2;'>No</th><th style='text-align: left; border: 1px solid #ddd; padding: 10px; background-color: #f2f2f2;'>Order Date</th><th style='text-align: left; border: 1px solid #ddd; padding: 10px; background-color: #f2f2f2;'>Product Name</th><th style='text-align: left; border: 1px solid #ddd; padding: 10px; background-color: #f2f2f2;'>Quantity</th><th style='text-align: left; border: 1px solid #ddd; padding: 10px; background-color: #f2f2f2;'>Price</th><th style='text-align: left; border: 1px solid #ddd; padding: 10px; background-color: #f2f2f2;'>Total Price</th></tr>";

        $totalAmount = 0;
        $no = 1;

        while ($result = $query->fetch_assoc()) {
            $order_id = $result['order_id'];
            $productSql = "SELECT oi.quantity, oi.price, p.product_name FROM order_item oi
                           INNER JOIN product p ON oi.product_id = p.product_id
                           WHERE order_id = '$order_id'";
            $productQuery = $db->query($productSql);

            while ($product = $productQuery->fetch_assoc()) {
                $totalPrice = $product["quantity"] * $product["price"];
                $totalAmount += $totalPrice;

                $receiptContent .= "<tr><td style='border: 1px solid #ddd; padding: 10px;'>$no</td><td style='border: 1px solid #ddd; padding: 10px;'>".$result["order_date"]."</td><td style='border: 1px solid #ddd; padding: 10px;'>".$product["product_name"]."</td><td style='border: 1px solid #ddd; padding: 10px;'>".$product["quantity"]."</td><td style='border: 1px solid #ddd; padding: 10px;'>".$product["price"]."</td><td style='border: 1px solid #ddd; padding: 10px;'>$totalPrice</td></tr>";

                ++$no;
            }
        }

        $receiptContent .= "</table>";
        $receiptContent .= "<p style='text-align: right; margin-top: 20px; font-weight: bold;'>Total Income: RM " . number_format($totalAmount, 2) . "</p>";
        $receiptContent .= "<button onclick='window.location.href=\"report.php\"' style='display: block; margin: 20px auto; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;'>Back</button>";
        $receiptContent .= "</div>";

        echo "<script type='text/javascript'>
                var receiptWindow = window.open('', '_blank');
                receiptWindow.document.write('" . addslashes($receiptContent) . "');
              </script>";
    } else {
        echo "No results found";
    }
} else {
    echo "Error: Invalid request method.";
}

?>
