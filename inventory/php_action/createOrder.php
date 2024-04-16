<?php 	

//ALTER TABLE `orders` ADD `payment_place` INT NOT NULL AFTER `payment_status`;
//TER TABLE `orders` ADD `gstn` VARCHAR(255) NOT NULL AFTER `payment_place`;
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'order_id' => '');

// print_r($valid);
if($_POST) {	

  $orderDate 					= $_POST['orderDate'];	
  $clientName 					= $_POST['clientName'];
  $clientContact 				= $_POST['clientContact'];
  $subTotalValue 				= $_POST['subTotalValue'];
  $vatValue 					= $_POST['vatValue'];
  $totalAmountValue     		= $_POST['totalAmountValue'];
  $discount 					= $_POST['discount'];
  $grandTotalValue 				= $_POST['grandTotalValue'];
  $paid 						= $_POST['paid'];
  $dueValue 					= $_POST['dueValue'];
  $paymentType 					= $_POST['paymentType'];
  $paymentStatus 				= $_POST['paymentStatus'];
  $paymentPlace 				= $_POST['paymentPlace'];
  $gstn 						= $_POST['gstn'];
  $userid 						= $_POST['custID'];
  $orderCreator 				= $_SESSION['store'];
				
	$sql = "INSERT INTO orders (order_date, client_name, client_contact, sub_total, vat, total_amount, discount, grand_total, paid, due, payment_type, payment_status,payment_place, gstn,order_status,user_id, order_belong) VALUES ('$orderDate', '$clientName', '$clientContact', '$subTotalValue', '0', '$totalAmountValue', '$discount', '0', '$paid', '$dueValue', $paymentType, $paymentStatus,$paymentPlace,0, 1,$userid, $orderCreator)";
//	
	$order_id;
	$orderStatus = false;
	if($db->query($sql) === true) {
		$order_id = $db->insert_id;
		$valid['order_id'] = $order_id;	

		$orderStatus = true;
	}
//
//		
//	// echo $_POST['productName'];
	$orderItemStatus = false;

	for($x = 0; $x < count($_POST['productName']); $x++) {			
		$updateProductQuantitySql = "SELECT product.quantity FROM product WHERE product.product_id = ".$_POST['productName'][$x]."";
		$updateProductQuantityData = $db->query($updateProductQuantitySql);
//		
//		
		while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
			$updateQuantity[$x] = $updateProductQuantityResult[0] - $_POST['quantity'][$x];							
				// update product table
				$updateProductTable = "UPDATE product SET quantity = '".$updateQuantity[$x]."' WHERE product_id = ".$_POST['productName'][$x]."";
				$db->query($updateProductTable);

				// add into order_item
				$orderItemSql = "INSERT INTO order_item (order_id, product_id, quantity, price, total, order_item_status) 
				VALUES ('$order_id', '".$_POST['productName'][$x]."', '".$_POST['quantity'][$x]."', '".$_POST['priceValue'][$x]."', '".$_POST['totalValue'][$x]."', 1)";

				$db->query($orderItemSql);		

				if($x == count($_POST['productName'])) {
					$orderItemStatus = true;
				}		
		} // while	
	} // /for quantity
//
	$valid['success'] = true;
	$valid['messages'] = "Successfully Added ";		
	
	$db->close();

	echo json_encode($valid);
 
} // /if $_POST
// echo json_encode($valid);
?>