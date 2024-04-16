<?php
	include('../connect.php');

	$search = trim($_GET['search']);

	$query = "SELECT
					
					o.order_date, p.product_name, oi.quantity, pr.proPrice, o.total_amount, u.fullName, u.u_id
				FROM
					orders AS o
				JOIN order_item AS oi ON o.order_id = oi.order_id
				JOIN tblprice AS pr ON oi.priceID = pr.priceNo
				JOIN product AS p ON pr.productID = p.product_id
				JOIN users AS u ON o.user_id = u.u_id
				WHERE o.user_id = '".$search."' AND o.order_status = 3
				ORDER BY order_date desc";

	$result = $db->query($query);

	$output = array('data' => array());
	$spent = 0;
	if($result->num_rows > 0) { 

		  $row = $result->fetch_array();

		 while($row = $result->fetch_array()) {
			 $spent += $row[4];


			$output['data'][] = array(
				$row[0],
				$row[1],
				$row[2],
				$row[3],
				$row[4],
				$row[5],
				$row[6],
				$spent
			); 	
		 } // /while 

	}// if num_rows
	

	echo json_encode($output);
    
?>

