<?php 	

include ("../connect.php");

$userNum = $_GET['search'];

$sql = "SELECT fullName, u_id FROM users WHERE phone  = '".$userNum."' AND status = '1' ";
$result = $db->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows


echo json_encode($row);