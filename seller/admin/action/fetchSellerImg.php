<?php
	include('../connect.php');

	$search = trim($_GET['search']);

	$query = "SELECT * FROM tblvalidation WHERE storeID = '".$search."' AND imgStatus = 1";

	$result = $db->query($query);

	$output = array();
	$spent = 0;
	if($result->num_rows > 0) { 

		 while($row = $result->fetch_array()) {


			$output[] = array(
				'frontImg' => $row['frontImg'],
				'backImg' => $row['backImg'],
				'faceImg' => $row['faceImg'],
			); 	
		 } // /while 

	}// if num_rows
	

	echo json_encode($output);
    
?>

