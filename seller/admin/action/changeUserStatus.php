<?php 

require_once 'core.php';


	$act = $_GET['act'];
	$custID = $_GET['custID'];
	$message = "";

	if($act == 'blk'){
		$sql = "UPDATE users SET status = 10 WHERE u_id = '".$custID."'";
		if($db->query($sql) == TRUE) {
			$message = "USER BANNED!";
		} else {
			$message = "SOMETHINGS PROBLEM!";
		}
	}
	else{
		$sql = "UPDATE users SET status = 1 WHERE u_id = '".$custID."'";
		if($db->query($sql) == TRUE) {
			$message = "USER RECOVERY!";
		} else {
			$message = "SOMETHINGS PROBLEM!";
		}
	}

	echo json_encode($message);
?>