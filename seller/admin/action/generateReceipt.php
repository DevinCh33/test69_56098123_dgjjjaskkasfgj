<?php
// Include database connection or any necessary files
include("./../../connection/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date']) && isset($_POST['rs_id'])) {
    $selectedDate = $_POST['date'];
    $order_belong = $_POST['rs_id'];

    // Your SQL query to fetch data and generate receipt content based on order_belong
    $sql = "SELECT
					oi.product_id,
					SUM(oi.quantity) AS total_quantity,
					SUM(oi.total) AS total_price,
					p.product_name
				FROM
					orders o
				JOIN order_item oi ON o.order_id = oi.order_id
				JOIN tblprice tp ON oi.priceID = tp.priceNo
				JOIN product p ON tp.productID = p.product_id
				JOIN restaurant r ON p.owner = r.rs_id
				WHERE
					o.order_date LIKE '".$selectedDate."%'
					AND o.order_belong = '".$order_belong."'
				GROUP BY
					oi.product_id";
    $query = $db->query($sql);
	
//	$companyInfo = "SELECT * FROM restaurant WHERE rs_id = '".$_POST['rs_id']."'";
//	$companyInfo1 = $db->query($companyInfo);
//	$companyInfo2 = $companyInfo1->fetch_assoc();

    if ($query->num_rows > 0) {
        $receiptNo = rand(10000, 99999); // Generating a random receipt number
        $receiptContent = "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); text-align: center;'>";
        $receiptContent .= "<p style='font-size: 14px; color: #555; font-weight: bold;'>LITTLE FARMER SDN.BHD.</p>";
        $receiptContent .= "<p style='font-size: 14px; color: #555;'>AB102 Ground Floor Parcel 2586-1-9 Lorong Persiaran Bandar Baru Batu Kawa 3D Batu Kawah New Township Jalan Batu Kawa 93250 Kuching Sarawak</p>";
        $receiptContent .= "<p style='font-size: 14px; color: #555;'>TEL: 010-217 0960</p>";
        $receiptContent .= "<h3 style='color: #333; border-bottom: 2px solid #333; padding-bottom: 10px; margin-top: 10px;'>RECEIPT</h3>";
        $receiptContent .= "<p style='font-size: 16px; color: #f00; margin-bottom: 10px;'><strong>RECEIPT NO. : LF$receiptNo</strong></p>";
        $receiptContent .= "<div style='display: flex; justify-content: space-between; align-items: center; margin: 10px 0;'>";
        $receiptContent .= "<p style='font-size: 14px; color: #555;'><strong>Date:</strong> " . date("Y-m-d H:i:s") . "</p>";
        $receiptContent .= "</div>";
        $receiptContent .= "<table style='width:100%; border-collapse: collapse; margin-top: 20px;'>";
        $receiptContent .= "<tr style='background-color: #f2f2f2; text-align: left;'><th style='width: 10%;'>No</th><th style='width: 30%;'>Product Name</th><th style='width: 20%;'>Quantity</th><th style='width: 20%; text-align: right'>Price</th></tr>";

        $totalAmount = 0;
        $totalItems = 0;
        $no = 1;

        while ($result = $query->fetch_assoc()) {
            $totalAmount += $result["total_price"];
            $totalItems += $result["total_quantity"];

            $receiptContent .= "<tr style='text-align: left;'><td style='width: 10%;'>$no</td><td style='width: 30%;'>".$result["product_name"]."</td><td style='width: 20%;'>".$result["total_quantity"]."</td><td style='width: 20%; text-align: right'>".number_format($result["total_price"], 2)."</td></tr>";

            ++$no;
        }

        $receiptContent .= "<tr style='background-color: #f2f2f2;'><td colspan='2' style='text-align: left; font-weight: bold; padding: 10px;'>Total Items: $totalItems</td><td colspan='2' style='text-align: right; font-weight: bold; padding: 10px;'>Total Price: RM " . number_format($totalAmount, 2) . "</td></tr>";

        $receiptContent .= "</table>";
        $receiptContent .= "<p style='font-size: 12px; margin-top: 20px;'>Thank you for shopping with us! We appreciate your business.</p>";
        $receiptContent .= "<button onclick='window.print()' style='display: block; margin: 20px auto; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;'>Print Receipt</button>";
        $receiptContent .= "</div>";

        echo $receiptContent;
    } else {
         // Display a styled message when no orders are found
         echo "<div style='font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); text-align: center;'>";
         echo "<p style='font-size: 16px; color: #f00; font-weight: bold;'>No orders found for the selected date and seller.</p>";
         echo "</div>";
    }
} else {
	
    echo "Error: Invalid request method or missing parameters.";
}
?>
