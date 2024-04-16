<?php 	

require_once 'core.php';


$userid = $_POST['userid'];

$sql = "SELECT * FROM users WHERE u_id = '".$userid."' ";
$result = $db->query($sql);

if($result->num_rows > 0) { 
 $row = $result->fetch_array();
} // if num_rows

$db->close();

echo json_encode($row);