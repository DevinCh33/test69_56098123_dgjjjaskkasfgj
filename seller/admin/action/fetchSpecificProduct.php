<?php
// Include your database connection code here
include('../connect.php');

// Check if priceId is set in POST request
if(isset($_POST['priceId'])) {
   $priceId = $_POST['priceId'];

// Initialize an array to store the results
$resultsArray = array();

// Assuming $priceId is an array of IDs
foreach ($priceId as $id) {
    $sql = "SELECT p.product_name, tp.proPrice, tp.proWeight, tp.priceNo, tp.proPrice
            FROM product AS p
            JOIN tblprice AS tp ON p.product_id = tp.productID
            WHERE tp.priceNo = '".$id."'";

    $res = $db->query($sql);

    if ($res->num_rows > 0) {
        while ($row = $res->fetch_array()) {
            $productResult = array(
                 'productName' => $row[0] . " (" . $row[2] . ")",
                'productPrice' => $row[1],
				'priceID' => $row[3],
				'price' => $row[4]
            );
            $resultsArray[] = $productResult;
        }
    }
}

// Encode the array as JSON and send it as the response
echo json_encode($resultsArray);

} else {
    // Handle the case where priceId is not set
    echo json_encode(array('error' => 'priceId not set'));
}

?>

