<?php 	
require_once 'core.php';

$sql = "SELECT product_id, product_name FROM product WHERE status = 1 AND active = 1 AND owner = '".$_SESSION['store']."'";
$result = $db->query($sql);

$data = $result->fetch_all();

$db->close();

echo json_encode($data);
