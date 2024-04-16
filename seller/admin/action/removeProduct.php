<?php 	
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

$productId = $_POST['productId'];

if($productId) 
{ 
	$sql = "UPDATE product SET active = 2, status = 2 WHERE product_id = {$productId} AND owner = '".$_SESSION['adm_id']."'";

	if($db->query($sql) === TRUE) 
	{
		$valid['success'] = true;
		$valid['messages'] = "Successfully removed!";		
	} 
	
	else 
	{
		$valid['success'] = false;
		$valid['messages'] = "Error while remove the product!";
	}

	$db->close();

	echo json_encode($valid);
} // /if $_POST
