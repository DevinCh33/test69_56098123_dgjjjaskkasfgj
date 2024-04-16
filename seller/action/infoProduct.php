<?php
include('../connect.php');

$valid = false;
$check = false;

$formDataArray = array();
    foreach ($_POST as $key => $value) {
        $formDataArray[$key] = $value;
    }


$act = $formDataArray['act'];
$data = $formDataArray['data'];

$date = date("Y-m-d");
$proID = $formDataArray['proID'];

$productID = $formDataArray['proID'];
$productCode = $formDataArray['productCode'];
$productName = $formDataArray['proName'];
$productDescription = $formDataArray['proDescr'];
$productQuantity = $formDataArray['proQuan'];
$productCategory = $formDataArray['proCat'];
$productStatus = $formDataArray['proStatus'];
$productStockAlert = $formDataArray['txtStock'];
$store = $formDataArray['storeID'];

// Extract weight and price values from the arrays
$weightValues = $formDataArray['weight'];
$priceValues = $formDataArray['price'];
$priceNo = $formDataArray['priceNo'];
$discountValues = $formDataArray['discount'];

$uploadDirectory = "../images/product/";
$fileDirectory = "http://localhost/lfsc/seller/images/product/";
$fileName = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$imageTmpName = $_FILES['image']['tmp_name'];
$uploadFile = $uploadDirectory.$fileName;
move_uploaded_file($imageTmpName, $uploadFile);
$urlImage = $fileDirectory . $fileName;


if ($act == "add") {
    // Perform the INSERT operation for 'product' table
    $sql = "INSERT INTO product (productCode, product_name, product_image, descr, quantity,  owner, product_date, lowStock, status)
            VALUES ('$productCode', '$productName','$urlImage', '$productDescription', '$productQuantity',  '$store', '$date', '$productStockAlert', '$productStatus')";

    if ($db->query($sql) === true) {
        // Get the last inserted product_id
        $lastProductID = $db->insert_id;

        // Insert data into 'tblprice' table
        foreach ($weightValues as $key => $weight) {
            $price = $priceValues[$key];
			$discount = $discountValues[$key];

            // Perform the INSERT operation for 'tblprice' table, linking it to the last inserted 'product_id'
            $priceInsertSQL = "INSERT INTO tblprice (productID, proWeight, proPrice, proDisc)
                                VALUES ('$lastProductID', '$weight', '$price', '$discount')";

            if ($db->query($priceInsertSQL) !== true) {
                // Handle error if the 'tblprice' insert fails
                $valid = false;
                break;
            }
        }

        $valid = true;
    }
} 
elseif ($act == "edit") {
	$existingPricesQuery = "SELECT priceNo FROM tblprice WHERE productID = ".$productID;
	$existingPricesResult = $db->query($existingPricesQuery);
	$existingPriceNos = [];
	while ($row = $existingPricesResult->fetch_assoc()) {
		$existingPriceNos[] = $row['priceNo'];
	}
	
	$updateSQL = "UPDATE product
					SET productCode = '$productCode', product_name = '$productName', descr = '$productDescription', categories_id = '$productCategory', quantity = '$productQuantity', status = '$productStatus'
					WHERE product_id = '$productID'";
	$db->query($updateSQL);
	
	// First, delete price records that exist but are not in $priceNo
	$deleteSQL = "DELETE FROM tblprice WHERE productID = '$productID' AND priceNo NOT IN (" . implode(",", $priceNo) . ")";
	$db->query($deleteSQL);

	// Then, update the existing prices in $priceNo
	foreach ($priceNo as $index => $price) {
		$proPrice = $priceValues[$index];
		$proWeight = $weightValues[$index];
		$proDisc = $discountValues[$index];

		// Use the retrieved values to update the database
		$updatePrice = "UPDATE tblprice SET proWeight = '$proWeight', proPrice = '$proPrice', proDisc = '$proDisc' WHERE priceNo = '$price'";
		$db->query($updatePrice);
	}

	$valid = true;
	
}
else if($act == 'del'){
	$delSQL = "UPDATE product SET status = 3 WHERE product_id = '$proID'";
	if($db->query($delSQL))
		$valid = true;
}

// Return a JSON response
echo json_encode($imageTmpName);
?>
