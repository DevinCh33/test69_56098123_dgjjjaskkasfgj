<?php 	

require_once 'core.php';

$valid = false;

if($_POST) {	

	$userName 			= $_POST['username'];
  	$upassword 			= md5($_POST['custPass']);
  	$uemail 			= $_POST['custEmail'];

	
	$sql = "INSERT INTO users (username, password,email) 
				VALUES ('$userName', '$upassword' , '$uemail')";
				if($db->query($sql) === TRUE) {
					$valid = true;
				} else {
					$valid = false;
				}

				// /else	
		
	}

	echo json_encode($valid);
 
?>