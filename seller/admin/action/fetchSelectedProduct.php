<?php 	

$data = $_POST['detail'];


$sql = "SELECT product_id, product_name, product_image,descr, weight, categories_id, quantity, price, active, status FROM product WHERE productID LIKE '".$search."' OR ";
$result = $db->query($sql);

if($result->num_rows > 0) 
{ 
    $row = $result->fetch_array();
} // if num_rows

$db->close();

echo json_encode($row);
