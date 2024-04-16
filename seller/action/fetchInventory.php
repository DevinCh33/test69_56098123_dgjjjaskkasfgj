<?php 	

include('../connect.php');

//$search = $_POST['search'];
//
$sql = "SELECT 
			p.product_id,
			p.productCode, 
			p.product_name, 
			p.product_image,
			p.quantity,
			SUM(oi.quantity) AS total_sold_quantity
		FROM 
			product p, order_item oi
		JOIN 
			orders o ON oi.order_id = o.order_id
        JOIN 
        	tblprice tp ON oi.priceID = tp.priceNo
		WHERE 
			p.status = 1
			AND p.owner = '".$_SESSION['store']."'
			AND o.order_status < 3";

if($_GET['name'] != "")
	$sql .= " AND product_id = '".$_GET['name']."'";

if($_GET['search'] != "" && $_GET['name'] == "")
	$sql .= " AND product_name LIKE '%".$_GET['search']."%' OR productCode LIKE '%".$_GET['search']."%'";
		
$sql .= " ORDER BY product.status ASC, total_sold_quantity, product.product_name ASC";

$result = $db->query($sql);

$tempProducts = array();
$finalProducts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $productID = $row[0]; // Assuming the first column is the unique product ID.
        $productPriceWeight = array('proWeight' => $row[7], 'proPrice' => $row[8], 'priceNo' => $row[9]);
		$img = "<img src='".$row[3]."' height='100' width='100'></img>";
        if (!isset($tempProducts[$productID])) {
            // If the product is not in the temp array, add it.
            $tempProducts[$productID] = array(
				'productID' => $row[0],
                'productCode' => $row[1],
                'productName' => $row[2],
                'productImage' => $img,
                'quantity' => $row[4],
                'totalSold' => $row[5]
            );
        }
    }

    // Transfer data from the temporary associative array to the final array.
    foreach ($tempProducts as $product) {
        $finalProducts[] = $product;
    }
}
else{
	
}

// Use $finalProducts for your loop.



echo json_encode($finalProducts);
