<?php
include('../connect.php');

if ($_GET['act'] == "app") {
	
    $appSQL = "UPDATE admin SET storeStatus = 1 WHERE store = '".$_GET['sid']."'";
	if($db->query($appSQL))
		$message = 'Store Validated';
	$imgUpdate = "UPDATE tblValidation SET imgStatus = 3 WHERE storeID = '".$_GET['sid']."'";
	$db->query($imgUpdate);
} 
elseif ($_GET['act'] == "rej") {
	$delSQL = "UPDATE tblValidation SET imgStatus = 2 WHERE storeID = '".$_GET['sid']."'";
	if($db->query($delSQL))
		$message = 'Validate Fail';
	
}

// Return a JSON response
echo json_encode($message);
?>
