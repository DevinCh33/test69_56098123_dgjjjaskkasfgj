<?php 	

include("../connect.php");

$totalPrice = 0;
$total = 0;
$check = false;
$act = $_POST['act'];
$data = $_POST['data'];

parse_str($data, $formData);
////
$date = date("Y-m-d");
$check = false;
$ordUID = $formData['ordUID'];
$ordNum = $formData['ordNum'];
$ordName = $formData['ordName'];
$ordType = $formData['ordType'];
$ordDrvFee = $formData['ordDlvFee'];
$ordDep = $formData['ordDep'];

$prdQuantity = $formData['quan'];
$prdPriceID = $formData['proID'];
$prdPrice = $formData['proPrice'];

// Iterate through the arrays
for ($i = 0; $i < count($prdPrice); $i++) {
    // Calculate the subtotal for the current product
    $subtotal = $prdPrice[$i] * $prdQuantity[$i];
    
    // Add the subtotal to the total price
    $totalPrice += $subtotal;
}

$total = $ordDrvFee + $totalPrice;
$due = $total - $ordDep;

if($act == "add"){
	
	$sql = "INSERT INTO orders (order_date, client_name, client_contact, sub_total, total_amount, paid, due, payment_type, order_status, user_id, order_belong) VALUES ('$date', '$ordName', '$ordNum', '$totalPrice', '$total', '$ordDep', '$due', '$ordType', 1,  $ordUID, '".$_SESSION['store']."')";

	$order_id;
	$orderStatus = false;
	if($db->query($sql) === true) {
		$order_id = $db->insert_id;
    	$orderStatus = true;
	}
	
	for($i = 0; $i < count($prdPriceID); ++$i){
		$insertDetail = "INSERT INTO order_item(order_id, priceID, quantity)VALUES('$order_id', '".$prdPriceID[$i]."', '".$prdQuantity[$i]."')";
		$db->query($insertDetail);
		
		if(($i+1) == count($prdPriceID))
			$check = true;
	}
	
	
}


echo json_encode($check);