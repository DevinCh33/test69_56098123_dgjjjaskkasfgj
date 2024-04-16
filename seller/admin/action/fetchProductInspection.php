<?php 	

include('../../connect.php');

//$search = $_POST['search'];

$sql = "SELECT 
			product.product_id,
			product.productCode, 
			product.product_name, 
			product.product_image, 
			product.descr,
			c.categories_name,
			product.status
		FROM 
			product
		JOIN
			categories c ON product.categories_id = c.categories_id
		WHERE
			product.status = 0 OR product.status = 6";

if($_GET['name'] != "")
	$sql .= " AND product.product_id = '".$_GET['name']."'";

if($_GET['search'] != "" && $_GET['name'] == "")
	$sql .= " AND product.product_name LIKE '%".$_GET['search']."%' OR product.productCode LIKE '%".$_GET['search']."%'";
		
$sql .= " ORDER BY product.status ASC, product.product_name ASC";

$result = $db->query($sql);

$tempProducts = array();
$finalProducts = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $productID = $row[0]; // Assuming the first column is the unique product ID.
		$img = "<img src='".$row[3]."' height='100' width='100'></img>";
        if (!isset($tempProducts[$productID])) {
            // If the product is not in the temp array, add it.
			if($row[6] == 1 || $row[6] == 6)
				$status = 'ACTIVE';
			else if($row[6] == 2 || $row[6] == 7)
				$status = 'INACTIVE';
			else if($row[6] == 0)
				$status = 'NEW PRODUCT';
            $tempProducts[$productID] = array(
				'productID' => $row[0],
                'productCode' => $row[1],
                'productName' => $row[2],
                'productImage' => $img,
                'descr' => $row[4],
				'cat' => $row[5],
				'status' =>$status
            );
        }
    }

    // Transfer data from the temporary associative array to the final array.
    foreach ($tempProducts as $product) {
        $finalProducts[] = $product;
    }
}

// Use $finalProducts for your loop.
echo json_encode($finalProducts);