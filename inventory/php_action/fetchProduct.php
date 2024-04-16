<?php 	
require_once 'core.php';

$sql = "SELECT product.product_id, product.product_name, product.product_image, product.descr, product.weight, 
 		product.categories_id, product.quantity, product.price, product.active, product.status, 
 		categories.categories_name FROM product 
		INNER JOIN categories ON product.categories_id = categories.categories_id  
		WHERE product.quantity>0 AND product.owner = '".$_SESSION['store']."'
		ORDER BY product.active ASC, product.product_name ASC";

$result = $db->query($sql);

$output = array('data' => array());

if($result->num_rows > 0) { 
	// $row = $result->fetch_array();
	$active = ""; 

	while($row = $result->fetch_array()) {
		$productId = $row[0];
		// active 
		if($row[8] == 1) 
		{
			// activate member
			$active = "<label class='label label-success'>Available</label>";
		} 
		
		else 
		{
			// deactivate member
			$active = "<label class='label label-danger'>Not Available</label>";
		} // /else

		$button = '<!-- Single button -->
		<div class="btn-group">
		<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			Action <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a type="button" data-toggle="modal" id="editProductModalBtn" data-target="#editProductModal" onclick="editProduct('.$productId.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
			<li><a type="button" data-toggle="modal" data-target="#removeProductModal" id="removeProductModalBtn" onclick="removeProduct('.$productId.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
		</ul>
		</div>';

		// $brandId = $row[3];
		// $brandSql = "SELECT * FROM brands WHERE brand_id = $brandId";
		// $brandData = $db->query($sql);
		// $brand = "";
		// while($row = $brandData->fetch_assoc()) {
		// 	$brand = $row['brand_name'];
		// }

		$category = $row[10];

		$productImage = "<img class='img-round' src='".$row[2]."' style='height:30px; width:50px;'  />";

		$output['data'][] = array( 		
			// image
			$productImage,
			// product name
			$row[1], 
			// price
			$row[7],
			// quantity 
			$row[6], 		 	
			// category 		
			$category,
			// active
			$active,
			// button
			$button 		
		); 	
	} // /while 
}// if num_rows

$db->close();

echo json_encode($output);
