<?php
include('../connect.php');

if ($_GET['act'] == "app") {
	
    $appSQL = "UPDATE product SET status = 1 WHERE product_id = '".$_GET['proID']."'";
	if($db->query($appSQL))
		$message = 'Product Approved';
} 
elseif ($_GET['act'] == "rej") {
	$delSQL = "UPDATE product SET status = 2 WHERE product_id = '".$_GET['proID']."'";
	if($db->query($delSQL))
		$message = 'Product Deactive';
	
}
else if($_GET['act'] == 'del'){
	$delSQL = "UPDATE product SET status = 3 WHERE product_id = '".$_GET['proID']."'";
	if($db->query($delSQL))
		$message = "Product Deleted";
}

// Return a JSON response
echo json_encode($message);
?>
