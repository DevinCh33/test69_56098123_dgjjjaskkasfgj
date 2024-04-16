<?php 	

include("../connect.php");

$sql = "SELECT order_id, client_name, client_contact,  order_date,  payment_type ,order_status FROM orders WHERE order_status = 1 AND order_belong = '".$_SESSION['store']."'";

$result = $db->query($sql);
$output = array('data' => array());

if($result->num_rows > 0) { 

	 while($row = $result->fetch_array()) {
		 $output['data'][] = array(
			$row[0], 
			$row[1], 
			$row[2], 
			$row[3], 
			$row[4], 
			$row[5], 
			$row[6]
		); 	
	 } // /while 

}// if num_rows

$db->close();

echo json_encode($output);