<?php 	
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) 
{	
	$productName 	= $_POST['productName'];
	//$productImage 	= $_POST['productImage'];
	$quantity 		= $_POST['quantity'];
	$price 			= $_POST['price'];
	$categoryName 	= $_POST['categoryName'];
	$productStatus 	= $_POST['productStatus'];
	$userIdentify 	= $_POST['userIdentify'];
	$productDescr	= $_POST['productDescr'];
	$productWeight	= $_POST['productWeight'];
	
	$type = explode('.', $_FILES['productImage']['name']);
	$type = $type[count($type)-1];
	$tmp = 	uniqid(rand()).'.'.$type;
	$url = '../assets/images/stock/'.$tmp;
	if(in_array($type, array('gif', 'jpg', 'jpeg', 'png', 'JPG', 'GIF', 'JPEG', 'PNG'))) {
		if(is_uploaded_file($_FILES['productImage']['tmp_name'])) {			
			if(move_uploaded_file($_FILES['productImage']['tmp_name'], $url)) {
				$url = 'http://localhost/lfsc/inventory/assets/images/stock/'.$tmp;
				$sql = "INSERT INTO product(product_name, product_image, descr, weight, categories_id, quantity, price, owner, active, status) 
				VALUES ('$productName', '$url','$productDescr', '$productWeight',  '$categoryName', '$quantity', '$price','$userIdentify', '$productStatus', 1)";
				
				if(($db->query($sql) === TRUE)) 
				{
					$valid['success'] = true;
					$valid['messages'] = "Successfully Added";	
				} 
				
				else 
				{
					$valid['success'] = false;
					$valid['messages'] = "Error while adding the members";
				}

			}	
			
			else 
			{
				return false;
			}	// /else	
		} // if
	} // if in_array 		

	$db->close();

	echo json_encode($valid);
	
	header("Location: http://localhost/lfsc/inventory/product.php");
} // /if $_POST