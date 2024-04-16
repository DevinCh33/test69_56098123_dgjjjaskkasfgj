<?php
$root = realpath($_SERVER["DOCUMENT_ROOT"]); // get root directory
include("$root/lfsc/connection/connect.php"); // connection to database

session_start();
error_reporting(0);

if(!$_SESSION['adm_id']) {
	header('location:'.$inv_url);
}

if ($_SESSION['store'] == null)
{
	header('location:'.$inv_url.'add_restraunt.php');
}
?>

