<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Seller Login</title>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
	<link rel="stylesheet prefetch" href='https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900'>
	<link rel="stylesheet prefetch" href='https://fonts.googleapis.com/css?family=Montserrat:400,700'>
	<link rel="stylesheet prefetch" href='https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css'>
	<link rel="icon" type="image/png" sizes="16x16" href="./../landing/logo.png">
	<link rel="stylesheet" href="css/login.css">
</head>

<?php
session_start(); // temp session
error_reporting(0); // hide undefined index errors
include("./../connection/connect.php"); // connection to database

if (isset($_SESSION["adm_id"])) // if already logged in
{
	header("refresh:0;url=dashboard.php"); // redirect to market.php page
}

if(isset($_POST['submit']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if(!empty($_POST["submit"])) 
    {
		$loginquery = "SELECT adm_id, code, password, u_role, store, storeStatus FROM admin WHERE username='$username'";
		$result = mysqli_query($db, $loginquery);
		$row = mysqli_fetch_array($result);
		
		if(password_verify($password, $row['password']))
		{
			$_SESSION["adm_id"] = $row['adm_id'];
			$_SESSION["adm_co"] = $row['code'];
			$_SESSION["u_role"] = $row['u_role'];
			$_SESSION['store'] = $row['store'];
			$_SESSION['status'] = $row['storeStatus'];
			if($_SESSION['adm_co'] != "SUPA" && $_SESSION['adm_co'] != "VSUPA")
				header("refresh:1;url=dashboard.php");
			else
				header("refresh:1;url=admin/dashboard.php");
		} 
		else
		{
			$message = "Invalid Username or Password!";
		}
	 }
}
?>

<body>
	<div class="container">
		<div class="info">
			<h1>Merchant </h1><span> Login Account</span>
		</div>
	</div>

	<div class="form">
		<div class="thumbnail"><img src="images/manager.png"/></div>

		<span style="color:red;"><?php echo $message; ?></span>
		<span style="color:green;"><?php echo $success; ?></span>

		<form class="login-form" action="index.php" method="post">
			<input type="text" placeholder="Username" name="username" value="admin" />
			<input type="password" placeholder="Password" name="password" value="123456"/>
			<input type="submit" name="submit" value="Login" />
			Not registered?<a href="registration.php" style="color:#f30;"> Create an account</a>
			or <a href="forgot_password.php" style="color:#f30;">Forgot password?</a>
		</form>
	</div>

	<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
</body>
</html>
