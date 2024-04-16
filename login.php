<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Login</title>
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
  	<link rel="stylesheet prefetch" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900">
	<link rel="stylesheet prefetch" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/login.css">
	<link rel="icon" type="image/png" sizes="16x16" href="landing/logo.png">

	<style type="text/css">
		#buttn {
			color: #fff;
			background-color: #ff3300;
		}
	</style>
</head>

<body>
<?php
session_start(); // temp session
error_reporting(0); // hide undefined index errors
include("connection/connect.php"); // connection to database

function obtainPriceDictionary($db) 
{
	$priceDictionary = [];
	$query = "SELECT product_id, price from product";
	$products = mysqli_query($db, $query); // executing
	
	if (!empty($products)) 
	{
		foreach($products as $product)
		{   
			$priceDictionary[$product['product_id']] = $product['price'];
		}
	}

	$_SESSION['prices'] = $priceDictionary;
}

if (isset($_SESSION["user_id"])) // if already logged in
{
	header("refresh:0;url=market.php"); // redirect to market.php page
}

if(isset($_POST['submit'])) // if submit button was preseed
{
	$username = $_POST['username']; // fetch records from login form
	$password = $_POST['password'];
	
	if(!empty($_POST['submit'])) // if records were not empty
    {
		$loginquery = "SELECT u_id, password FROM users WHERE username='$username'"; // selecting matching records
		$result = mysqli_query($db, $loginquery); // executing
		$row = mysqli_fetch_array($result);
	
		if(password_verify($password, $row['password'])) // if matching records in the array & if everything is right
		{
			$_SESSION['user_id'] = $row['u_id']; // put user id into temp session
			$_SESSION['loginStatus'] = true;

			//obtainPriceDictionary($db);

			header("refresh:1;url=market.php"); // redirect to market.php page
		} 
		else
		{
			$message = "Invalid Username or Password!"; // throw error
		}
	 }
}
?>
  
<!-- Form Mixin-->
<!-- Input Mixin-->
<!-- Button Mixin-->
<!-- Pen Title-->
<div class="pen-title">
  	<h1>Login Form</h1>
</div>

<!-- Form Module-->
<div class="module form-module">
	<div class="toggle">
	</div>
	<div class="form">
		<h2>Login to your account</h2>
		<span style="color:red;"><?php echo $message; ?></span>
		<span style="color:green;"><?php echo $success; ?></span>
		<form action="" method="post">
			<input type="text" placeholder="Username" name="username" />
			<input type="password" placeholder="Password" name="password"/>
            <input type="submit" name="submit" value="Login" />
		</form>
	</div>
  
	<div class="cta">
		Not registered?<a href="registration.php" style="color:#f30;"> Create an account</a>
		or <a href="enter_email.php" style="color:#f30;">Forgot password?</a>
	</div>
</div>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
</body>
</html>
