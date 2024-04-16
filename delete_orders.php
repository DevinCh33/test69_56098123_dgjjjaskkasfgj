<?php
include("connection/connect.php"); // connection to db
error_reporting(0);
session_start();

// sending query
mysqli_query($db,"UPDATE orders SET order_status = 4 WHERE order_id = '".$_GET['order_del']."'"); // deleting records on the basis of ID
header("location:your_orders.php");  // once delete success redirect back to current page
?>
