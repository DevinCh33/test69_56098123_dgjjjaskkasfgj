<!DOCTYPE html>
<html lang="en">

<?php
session_start(); // temp session
error_reporting(0); // hide undefined index errors
include("connection/connect.php"); // connection to database

if(empty($_SESSION['user_id']))  // if user is not logged in, redirect back to login page
{
	header('location:login.php');
}

else
{
if($_POST['submit'])
{
    $times = 0;
	$today = date("Y/m/d");
	// Rearrange the array based on owner ID
	$groupedProducts = [];
	$user = "SELECT * FROM users WHERE u_id = '".$_SESSION['user_id']."'";
	$user1 = $db->query($user);
	$user2 = $user1->fetch_array();
	
	foreach ($_SESSION["cart"] as $product) 
    {
		$ownerId = $product['owner'];
		$groupedProducts[$ownerId][] = $product;
	}
	
	foreach ($groupedProducts as $ownerId => $products) 
    {
		$totalPrice = 0;
		$sql = "INSERT INTO orders (order_date, client_name, client_contact, sub_total, total_amount, paid, due, payment_type, order_status, user_id, order_belong) VALUES ('$today', '".$user2['f_name'].' '.$user2['l_name']."', '".$user2['phone']."', '$item_total', '$item_total', '0', '$item_total', 1, 1, ".$_SESSION['user_id'].", $ownerId)";
			
        $order_id;
        $orderStatus = false;

        if($db->query($sql) === true) 
        {
            $order_id = $db->insert_id;
            $valid['order_id'] = $order_id;	

            $orderStatus = true;
        }
		
		foreach ($products as $item)
        {
            $stmt = $db->prepare("SELECT productID FROM tblprice WHERE priceNo = ?");
            $stmt->bind_param("i", $item['price_id']);
            $stmt->execute();
            $productId = $stmt->get_result();
            $item['product_id'] = $productId->fetch_assoc()['productID'];

			$item_total = 0;
			$item_total += ($item["price"]*$item["quantity"]);
			$totalPrice += $item_total;
			$orderItemStatus = false;
			$updateProductQuantitySql = "SELECT product.quantity FROM product WHERE product.product_id = ".$item['product_id']."";
			$updateProductQuantityData = $db->query($updateProductQuantitySql);
		
			while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) 
            {
				$updateQuantity = $updateProductQuantityResult[0] - $item['quantity']*$item['weight'];							
                // update product table
                $updateProductTable = "UPDATE product SET quantity = '".$updateQuantity."' WHERE product_id = '".$item['product_id']."'";
                $db->query($updateProductTable);
                // add into order_item
                $orderItemSql = "INSERT INTO order_item (order_id, priceID, quantity) 
                VALUES ('$order_id', '".$item['price_id']."', '".$item['quantity']."')";

                $db->query($orderItemSql);		
			} // while
			++$times;
		}

		$updateOrderTotalSql = "UPDATE orders SET sub_total = '$totalPrice', due= '$totalPrice', total_amount = '$totalPrice' WHERE order_id = '$order_id'";
    	$db->query($updateOrderTotalSql);
	}

	if($times == count($_SESSION["cart"]))
    {
		$success = "Thank you! Your order has been placed successfully!";
		// Unset the entire cart_item array
		unset($_SESSION["cart"]);
?>
<script>
    // Redirect to another page after the countdown
    setTimeout(function () {
        window.location.href = 'http://localhost/lfsc/market.php';
    }, 1 * 1000); // Convert seconds to milliseconds
</script>
<?php
	}
}
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Checkout</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="16x16" href="landing/logo.png">
</head>

<body>
    <div class="site-wrapper">
        <!--header starts-->
        <?php
        include("includes/header.php");
        ?>

        <div class="page-wrapper" style="padding-top: 5%;">
            <div class="top-links">
                <div class="container">
                    <ul class="row links"> 
                        <li class="col-xs-12 col-sm-4 link-item"><span>1</span><a href="merchants.php">Choose Merchant</a></li>
                        <li class="col-xs-12 col-sm-4 link-item "><span>2</span><a href="#">Pick Your Products</a></li>
                        <li class="col-xs-12 col-sm-4 link-item active" ><span>3</span><a href="checkout.php">Order and Pay online</a></li>
                    </ul>
                </div>
            </div>
			
            <div class="container">
                <span style="color:green;">
                        <?php echo $success; ?>
                </span>
            </div>
  
            <div class="container m-t-30">
			<form action="" method="post">
                <div class="widget clearfix">
                    <div class="widget-body">
                        <form method="post" action="#">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="cart-totals margin-b-20">
                                        <div class="cart-totals-title">
                                            <h4>Cart Summary</h4> </div>
                                        <div class="cart-totals-fields">
                                            <table class="table">
											<tbody>
                                                <tr>
                                                    <td>Cart Subtotal</td>
                                                    <td class="text-color" id="cartTotal">Total Price: RM 0.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Shipping &amp; Handling</td>
                                                    <td>Free Shipping</td>
                                                </tr>
                                            </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!--cart summary-->
                                    <div class="payment-option">
                                        <ul class=" list-unstyled">
                                            <li>
                                                <label class="custom-control custom-radio  m-b-20">
                                                    <input name="mod" id="radioStacked1" checked value="COD" type="radio" class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Payment on delivery</span>
                                                    <br> <span>Please send your cheque to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</span> </label>
                                            </li>

                                            <li>
                                                <label class="custom-control custom-radio m-b-20">
                                                    <input name="mod" type="radio" value="" class="custom-control-input spay-radio">
                                                    <span class="custom-control-indicator"></span>
                                                    <span class="custom-control-description">S-Pay <img src="" alt="" width="90"></span>
                                                </label>
                                            </li>
                                            <div class="spay-image-container">
                                                <!-- Image display area -->
                                                <div class="spay-image-overlay">
                                                    <img src="images/spay.png" alt="S-Pay" class="spay-image">
                                                    <span class="close-btn" style="color: #000; font-size: 32px; width: 40px; height: 40px; display: flex; justify-content: center; align-items: center; background-color: #fff; border-radius: 50%; border: 2px solid #000;">&#x2716;</span>

                                                </div>
                                            </div>



                                            <li>
                                                <label class="custom-control custom-radio  m-b-10">
                                                    <input name="mod"  type="radio" value="paypal" disabled class="custom-control-input"> <span class="custom-control-indicator"></span> <span class="custom-control-description">Paypal <img src="images/paypal.jpg" alt="" width="90"></span> </label>
                                            </li>
                                        </ul>
                                        <p class="text-xs-center"> <input id="confirmOrder" type="submit" onclick="return confirm('Are you sure?');" name="submit"  class="btn btn-outline-success btn-block" value="Order Now"> </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
			</form>
        </div>
    <!-- start: FOOTER -->
    <?php
    include("includes/footer.php");
    ?>
    <!-- end:Footer -->
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <script src="js/jquery.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/animsition.min.js"></script>
    <script src="js/bootstrap-slider.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/headroom.js"></script>
    <script src="js/foodpicky.min.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/spay.js"></script>
</body>
</html>
