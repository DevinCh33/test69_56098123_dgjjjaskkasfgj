<?php 

require_once 'core.php';


	$act = $_GET['act'];
	$shopID = $_GET['shopID'];
	$message = "";

	if($act == 'blk'){
		$sql = "UPDATE admin SET storeStatus = 10 WHERE store = '".$shopID."'";
		if($db->query($sql) === TRUE) {
			$message = "SHOP BANNED!";
		} else {
			$message = "SOMETHINGS PROBLEM!";
		}
	}
	else{
		$sql = "UPDATE admin SET storeStatus = 1 WHERE store = '".$shopID."'";
		if($db->query($sql) === TRUE) {
			$message = "SHOP RECOVERY!";
		} else {
			$message = "SOMETHINGS PROBLEM!";
		}
	}

	echo json_encode($message);
?>