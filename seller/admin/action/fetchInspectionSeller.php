<?php 	
require_once 'core.php';

$sql = "SELECT a.store, r.title, a.email, r.phone, a.storeStatus
		FROM admin a
		JOIN restaurant r ON a.store = r.rs_id
		WHERE u_role != 'SUPA' AND storeStatus = 0
		ORDER BY storeStatus";

$result = $db->query($sql);

$output = array('data' => array());
if($result->num_rows > 0) { 

 while($row = $result->fetch_array()) {
 	$output['data'][] = array(
		$row[0],
		$row[1],
		$row[2],
		$row[3],
		$row[4]
 	); 	
 } // /while 

}// if num_rows

echo json_encode($output);