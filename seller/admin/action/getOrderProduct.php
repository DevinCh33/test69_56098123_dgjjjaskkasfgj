<?php
include("./../../connection/connect.php");

$orderId = $_GET['orderId'];

$sql = "SELECT product.product_name, product.product_image, order_item.quantity 
		FROM orders, order_item, product, tblprice
		WHERE orders.order_id = order_item.order_id AND order_item.priceID = tblprice.priceNo AND tblprice.productID =  product.product_id AND orders.order_id = '".$orderId."'";
$result = mysqli_query($db, $sql);

if (!$result) {
    // Log or handle the MySQL error
    die('MySQL Error: ' . mysqli_error($db));
}

$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Return data as JSON
echo json_encode($data);
?>
