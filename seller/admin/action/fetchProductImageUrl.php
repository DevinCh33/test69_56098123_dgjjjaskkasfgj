<?php 	
require_once 'core.php';

$productId = $_GET['i'];

$sql = "SELECT product_image FROM product WHERE product_id = {$productId}";
$data = $db->query($sql);
$result = $data->fetch_row();

$db->close();

echo $result[0];
