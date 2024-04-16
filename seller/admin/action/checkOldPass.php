<?php 	
require_once 'core.php';

$adm_id = $_GET['admID'];
$pass = $_GET['pass'];

$sql = "SELECT password
		FROM admin
		WHERE adm_id = '".$adm_id."'";

$result = $db->query($sql);

if($result->num_rows > 0) { 
	
	$row = $result->fetch_array();
	if (password_verify($pass, $row[0])) {
    	$output = 1;
  	} else {
    	$output = 0;
    }

}// if num_rows

echo json_encode($output);