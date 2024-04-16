<?php 

require_once 'core.php';


	$store = $_GET['store'];
	$stockNum = $_GET['num'];

	$sql = "UPDATE product SET lowStock = '$stockNum' WHERE owner = '".$store."'";
	if($db->query($sql) === TRUE) {
		$valid = true;
	} else {
		$valid = false;
	}

	$db->close();

	echo json_encode($valid);


?>