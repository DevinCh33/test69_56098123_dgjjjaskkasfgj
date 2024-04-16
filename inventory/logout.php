<?php 

require_once 'php_action/core.php';

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 
echo "<script>location = 'http://localhost/LFSC/index.php';</script>";
	

?>