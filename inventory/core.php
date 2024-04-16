<?php 

session_start();

require_once '../connection/connect.php';

// echo $_SESSION['userId'];

if(!$_SESSION['adm_id']) {
	header('location:'.$store_url);	
} 



?>