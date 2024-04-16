<?php include 'connect.php'; ?>
<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
<head>
	<link rel="stylesheet" href="style.css">
	<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
	
	
	<div class="logo-details">
		<img src="img/logo.png" width="80" height="80" id="logo" name="logo"></img>
		<span id="logo_name" name="logo_name"  class="logo_name">Little Farmer</span>
    </div>
	<div style="logo_name">
		
	</div>
		
    <ul class="nav-links">
		
		
      <li>
        <a href="dashboard.php">
         <i class="fa-solid fa-gauge"></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="dashboard.php">Dashboard</a></li>
        </ul>
      </li>
		
	
		<?php if($_SESSION['adm_co'] != "VSUPP"){ ?>
      <li>
        <div class="iocn-link">
          <a href="employee.php">
            <i class="fa-solid fa-user-tie"></i>
            <span class="link_name">Employee</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="#">Employee</a></li>
          <li><a href="#">HTML & CSS</a></li>
          <li><a href="#">JavaScript</a></li>
          <li><a href="#">PHP & MySQL</a></li>
        </ul>
      </li>
		<?php } ?>
		
	<?php if($_SESSION['adm_co'] != "VSUPP"){ ?>
      <li>
        <div class="iocn-link">
          <a href="product.php">
            <i class="fa-solid fa-carrot"></i>
            <span class="link_name">Products</span>
          </a>
          <i class='bx bxs-chevron-down arrow' ></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="product.php">Products</a></li>
          <li><a href="#">Web Design</a></li>
          <li><a href="#">Login Form</a></li>
          <li><a href="#">Card Design</a></li>
        </ul>
      </li>
	<?php } ?> 
		
	
      <li>
        <a href="customer.php">
          <i class="fa-solid fa-user"></i>
          <span class="link_name">Customer</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="customer.php">Customer</a></li>
        </ul>
      </li>
		
		
		
      <li>
        <a href="order.php">
          <i class="fa-solid fa-clipboard-check"></i>
          <span class="link_name">Order</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="order.php">Order</a></li>
        </ul>
      </li>
      
      <?php if($_SESSION['adm_co'] != "VSUPP"){ ?>
      <li>
        <a href="product_report.php">
          <i class='bx bxs-report'></i>
          <span class="link_name">Report</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Report</a></li>
        </ul>
      </li>
		<?php } ?>
		
		
      <li>
        <a href="setting.php">
          <i class="fa-solid fa-gear"></i>
          <span class="link_name">Setting</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Setting</a></li>
        </ul>
      </li>
      <li>
    <div class="profile-details">
      <div class="profile-content">
        <!--<img src="image/profile.jpg" alt="profileImg">-->
      </div>
      <div class="name-job">
        <div class="profile_name"><span id="userName" /></div>
        <div class="job"><span id="userRole" /></div>
      </div>
		<a href="logout.php"><i class='bx bx-log-out' ></i></a>
    </div>
  </li>
</ul>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
	fetchData();
});

function fetchData() {
	var userid = <?php echo $_SESSION['adm_id']; ?>;
	var userrole = '<?php echo $_SESSION['u_role']; ?>';
    $.ajax({
        url: 'action/fetchAdminData.php',
        type: 'GET',
		data:{userId: userid},
        dataType: 'json',
        success: function(response) {
			$('#userName').text(response[0]['title']);
			$('#userRole').text(response[0]['u_role']);
		},
		error: function(xhr, status, error) {
			console.error('Error:', error); // Log any errors to the console
		}

    });
}
</script>
