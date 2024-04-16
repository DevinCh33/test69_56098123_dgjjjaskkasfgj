<?php

session_start(); // temp session
error_reporting(0); // hide undefined index errors
date_default_timezone_set("Asia/Kuching");

					
//main connection file for both admin & front end
$servername = "localhost"; //server
$username = "root"; //username
$password = ""; //password
$dbname = "store";  //database
$inv_url = "http://localhost/lfsc/seller/";

// Create connection
$db = mysqli_connect($servername, $username, $password, $dbname); // connecting 
// Check connection
if (!$db) //checking connection to DB
{ 
    die("Connection failed: " . mysqli_connect_error());
}
?>
