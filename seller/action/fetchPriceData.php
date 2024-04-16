<?php 	
require_once 'core.php';

$sql = "SELECT tblprice.priceNo, product.product_name, tblprice.proWeight FROM product JOIN tblprice ON tblprice.productID = product.product_id WHERE product.status = 1 AND product.owner = '".$_SESSION['store']."'";
$result = $db->query($sql);

$data = $result->fetch_all();

$db->close();

echo json_encode($data);
