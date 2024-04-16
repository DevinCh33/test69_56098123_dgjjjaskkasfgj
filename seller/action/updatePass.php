<?php 

require_once 'core.php';


	$newPassword = password_hash($_GET['newpass'], PASSWORD_DEFAULT);
	$userId = $_GET['admID'];
	
	$updateSql = "UPDATE admin SET password = '$newPassword' WHERE adm_id = '".$userId."'";
	if($db->query($updateSql) === TRUE) {
		$valid = "true";		
	} else {
		$valid = "false";	
	}


	echo json_encode($valid);



?>