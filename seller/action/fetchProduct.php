<?php 	

include('../connect.php');

//$search = $_POST['search'];

$sql = "SELECT 
			product.product_id,
			product.productCode, 
			product.product_name, 
			product.product_image, 
			product.descr, 
			product.quantity,
			product.lowStock,
			product.status, 
			tblprice.proWeight,
			tblprice.proPrice,
            tblprice.proDisc,
			tblprice.priceNo
		FROM 
			product
		JOIN 
			tblprice ON product.product_id = tblprice.productID
		WHERE 
			product.owner = '".$_SESSION['store']."' AND status < 3";

if($_GET['name'] != "")
	$sql .= " AND product_id = '".$_GET['name']."'";

if($_GET['search'] != "" && $_GET['name'] == "")
	$sql .= " AND product_name LIKE '%".$_GET['search']."%' OR productCode LIKE '%".$_GET['search']."%'";
		
$sql .= " ORDER BY product.status ASC, product.product_name ASC";

$result = $db->query($sql);

$tempProducts = array();
$finalProducts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $productID = $row[0]; // Assuming the first column is the unique product ID.
        $productPriceWeight = array('proWeight' => $row[8], 'proPrice' => $row[9], 'proDisc' => $row[10], 'priceNo' => $row[11]);
		$img = "<img src='".$row[3]."' height='100' width='100'></img>";
        if (!isset($tempProducts[$productID])) {
            // If the product is not in the temp array, add it.
            $tempProducts[$productID] = array(
				'productID' => $row[0],
                'productCode' => $row[1],
                'productName' => $row[2],
                'productImage' => $img,
                'descr' => $row[4],
                'quantity' => $row[5],
				'lowStock' => $row[6],
                'status' => $row[7],
                'prices' => array($productPriceWeight) // Start a new array for prices, weights and discounts
            );
        } else {
            // If the product is already in the temp array, add the new price and weight to it.
            $tempProducts[$productID]['prices'][] = $productPriceWeight;
        }
    }

    // Transfer data from the temporary associative array to the final array.
    foreach ($tempProducts as $product) {
        $finalProducts[] = $product;
    }
}

// Use $finalProducts for your loop.
echo json_encode($finalProducts);
