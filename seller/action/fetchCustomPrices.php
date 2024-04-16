<?php 	
require_once 'core.php';

$user = $_GET['user'];
$id = $_GET['id'];

$sql = "SELECT * FROM custom_prices WHERE price_id = ".$id." AND user_id = ".$user;
$result = $db->query($sql);

$data = $result->fetch_all();

$db->close();

echo json_encode($data);
