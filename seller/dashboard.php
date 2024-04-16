<?php include 'connect.php'; ?>
<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
	<head>
    	<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    	<link rel="stylesheet" href="style.css">
    	<!-- Boxiocns CDN Link -->
    	<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
	</head>
	
<body>
  	<div class="sidebar close">
    	<?php include "sidebar.php"; ?>
  	</div>
  	<section class="home-section">
    <div class="home-content">
      	<i class='bx bx-menu' ></i>
      	<span class="text">Dashboard</span>
    </div>
	  
	  
	<div class="dashboard">
		<div class="card">
		<?php if($_SESSION["adm_co"] == "SUPP"){ ?>
			<h3>Monthly Income</h3>
		<?php
			// Query to get the total amount of orders for the current month
			$queryCurrentMonth = "SELECT SUM(total_amount) AS total_current_month FROM orders WHERE order_status = 3 AND order_date like '".date("Y-m-")."%'";
			$resultCurrentMonth = mysqli_query($db, $queryCurrentMonth);
			$rowCurrentMonth = mysqli_fetch_assoc($resultCurrentMonth);
			$totalCurrentMonth = $rowCurrentMonth['total_current_month'];

			// Query to get the total amount of orders for the last month
			$oneMonthAgo = date("Y-m-", strtotime("-1 month"));
			$queryLastMonth = "SELECT SUM(total_amount) AS total_last_month FROM orders WHERE order_date like '".$oneMonthAgo."%'";
			$resultLastMonth = mysqli_query($db, $queryLastMonth);
			$rowLastMonth = mysqli_fetch_assoc($resultLastMonth);
			$totalLastMonth = $rowLastMonth['total_last_month'];
			
			
			$percent = 0;
			$percent = ($totalCurrentMonth-$totalLastMonth) * 100;
			if($totalCurrentMonth < $totalLastMonth){
				$percent = "-".$percent."%";
				$word = "negative";
			}	
			else{
				$percent = "+".$percent."%";
				$word = "positive";
			}
				
			
		?>
        	<p>RM <?php echo $totalCurrentMonth; ?><span class="stat-delta <?php echo $word; ?>"><?php echo $percent; ?></span></p>
		<?php }else if($_SESSION["adm_co"] == "SUPA"){ ?>
			<h3>Total Seller</h3>
		<?php
			$querySeller = "SELECT COUNT(adm_id) AS total_seller FROM admin";
			$resultSeller = mysqli_query($db, $querySeller);
			$rowSeller = mysqli_fetch_assoc($resultSeller);
			$totalSeller = $rowSeller['total_seller'];
	
			// Query to get the total number of sellers
			$queryCurrentMonth = "SELECT COUNT(adm_id) AS total_seller FROM admin WHERE MONTH(date) = MONTH(CURRENT_DATE()) 
                      				AND YEAR(date) = YEAR(CURRENT_DATE())";
			$resultCurrentMonth = mysqli_query($db, $queryCurrentMonth);
			$rowCurrentMonth = mysqli_fetch_assoc($resultCurrentMonth);
			$totalCurrentMonth = $rowCurrentMonth['total_seller'];

			// Query to get the total number of sellers for the last month
			$oneMonthAgo = date("Y-m-d", strtotime("-1 month"));
			$queryLastMonth = "SELECT COUNT(adm_id) AS total_last_month FROM admin WHERE date >= LAST_DAY(DATE_SUB('$oneMonthAgo', INTERVAL 1 MONTH)) + INTERVAL 1 DAY AND date < LAST_DAY('$oneMonthAgo') + INTERVAL 1 DAY";
			$resultLastMonth = mysqli_query($db, $queryLastMonth);
			$rowLastMonth = mysqli_fetch_assoc($resultLastMonth);
			$totalLastMonth = $rowLastMonth['total_last_month'];

			// Calculate the percentage change
			$percentChange = 0;
			if ($totalLastMonth != 0) {
				$percentChange = (($totalCurrentMonth - $totalLastMonth) / $totalLastMonth) * 100;
			}

			// Determine whether the change is positive or negative
			if ($totalCurrentMonth < $totalLastMonth) {
				$percentChange = "-" . abs($percentChange) . "%";
				$word = "negative";
			} else {
				$percentChange = "+" . abs($percentChange) . "%";
				$word = "positive";
			}
			?>
			<p><?php echo $totalSeller; ?><span class="stat-delta <?php echo $word; ?>"><?php echo $percentChange; ?></span></p>
		<?php } ?>
			
      	</div>
		
		<div class="card">
		<?php if($_SESSION["adm_co"] == "SUPP"){ ?>
			<h3>Total Order</h3>
			<?php
			// Query to get the total amount of orders for the current month
			$queryCurrentMonth = "SELECT COUNT(order_id) AS total_current_month FROM orders WHERE order_date like '".date("Y-m-")."%'";
			$resultCurrentMonth = mysqli_query($db, $queryCurrentMonth);
			$rowCurrentMonth = mysqli_fetch_assoc($resultCurrentMonth);
			$totalCurrentMonth = $rowCurrentMonth['total_current_month'];

			// Query to get the total amount of orders for the last month
			$oneMonthAgo = date("Y-m-", strtotime("-1 month"));
			$queryLastMonth = "SELECT COUNT(order_id) AS total_last_month FROM orders WHERE order_date like '".$oneMonthAgo."%'";
			$resultLastMonth = mysqli_query($db, $queryLastMonth);
			$rowLastMonth = mysqli_fetch_assoc($resultLastMonth);
			$totalLastMonth = $rowLastMonth['total_last_month'];
			
			
			$percent = 0;
			$percent = ($totalCurrentMonth-$totalLastMonth) * 100;
			if($totalCurrentMonth < $totalLastMonth){
				$percent = "-".$percent."%";
				$word = "negative";
			}	
			else{
				$percent = "+".$percent."%";
				$word = "positive";
			}
				
			
		?>
			<p><?php echo $totalCurrentMonth; ?><span class="stat-delta <?php echo $word; ?>"><?php echo $percent; ?></span></p>
		<?php }else if($_SESSION["adm_co"] == "SUPA"){ ?>
			<h3>Product Inspection</h3>
			<?php
			// Query to get the total amount of orders for the current month
			$queryCurrentMonth = "SELECT COUNT(order_id) AS total_current_month FROM orders WHERE order_date like '".date("Y-m-")."%'";
			$resultCurrentMonth = mysqli_query($db, $queryCurrentMonth);
			$rowCurrentMonth = mysqli_fetch_assoc($resultCurrentMonth);
			$totalCurrentMonth = $rowCurrentMonth['total_current_month'];

			// Query to get the total amount of orders for the last month
			$oneMonthAgo = date("Y-m-", strtotime("-1 month"));
			$queryLastMonth = "SELECT COUNT(order_id) AS total_last_month FROM orders WHERE order_date like '".$oneMonthAgo."%'";
			$resultLastMonth = mysqli_query($db, $queryLastMonth);
			$rowLastMonth = mysqli_fetch_assoc($resultLastMonth);
			$totalLastMonth = $rowLastMonth['total_last_month'];
			
			
			$percent = 0;
			$percent = ($totalCurrentMonth-$totalLastMonth) * 100;
			if($totalCurrentMonth < $totalLastMonth){
				$percent = "-".$percent."%";
				$word = "negative";
			}	
			else{
				$percent = "+".$percent."%";
				$word = "positive";
			}
				
			
		?>
			<p><?php echo $totalCurrentMonth; ?><span class="stat-delta <?php echo $word; ?>"><?php echo $percent; ?></span></p>
		<?php } ?>
		</div>

		<div class="card">
			<h3>Total Products</h3>
			<?php
			// Query to get the total amount of orders for the current month
			$queryCurrentMonth = "SELECT COUNT(product_id) AS total_current_month FROM product WHERE product_date like '".date("Y-m-")."%'";
			$resultCurrentMonth = mysqli_query($db, $queryCurrentMonth);
			$rowCurrentMonth = mysqli_fetch_assoc($resultCurrentMonth);
			$totalCurrentMonth = $rowCurrentMonth['total_current_month'];

			// Query to get the total amount of orders for the last month
			$oneMonthAgo = date("Y-m-", strtotime("-1 month"));
			$queryLastMonth = "SELECT COUNT(product_id) AS total_last_month FROM product WHERE product_date like '".$oneMonthAgo."%'";
			$resultLastMonth = mysqli_query($db, $queryLastMonth);
			$rowLastMonth = mysqli_fetch_assoc($resultLastMonth);
			$totalLastMonth = $rowLastMonth['total_last_month'];
			
			
			$percent = 0;
			$percent = ($totalCurrentMonth-$totalLastMonth) * 100;
			if($totalCurrentMonth < $totalLastMonth){
				$percent = "-".$percent."%";
				$word = "negative";
			}	
			else{
				$percent = "+".$percent."%";
				$word = "positive";
			}
				
			
		?>
			<p><?php echo $totalCurrentMonth; ?><span class="stat-delta <?php echo $word; ?>"><?php echo $percent; ?></span></p>
		  </div>

		  <div class="card">
			<h3>Total Employee</h3>
			<p>5,186<span class="stat-delta positive">+150%</span></p>
		  </div>
	
	
		  <div class="sales-graph">
			<canvas id="salesChart"></canvas>
		  </div>
		
		<div class="multiFunction">
			<div class="calendar">
				<h3>Calendar</h3>
				<h2 id="day"></h2>
				<p id="month"></p>
			</div>
			
			<div class="alert">
				<div class="box">
					<p>Stock Alert    :</p> 
				</div>
				<div class="box">
					<p>Order Reminder :</p> 
				</div>
				<div class="box">
					<p>Product Pending:</p> 
				</div>
				
			</div>
			
      	</div>
</div>
	  
	  
  </section>
  
  <script>
  let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
   arrowParent.classList.toggle("showMenu");
    });
  }
  let sidebar = document.querySelector(".sidebar");
  let sidebarBtn = document.querySelector(".bx-menu");
	  let logoImage = document.getElementById("logo");
	  let logoName = document.getElementById("logo_name");
  	logoName.hidden = true;
	  let initialSize = { width: 80, height: 80 };
	  logoImage.width = initialSize.width;
	  logoImage.height = initialSize.height;
	  // Hide the logo name initially
  logoName.style.opacity = 0;

  // Function to show the logo name with a delay
  function showLogoName() {
    logoName.style.opacity = 1;
  }
	  
  sidebarBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("close");
	  if (sidebar.classList.contains("close")) {
      // Sidebar is closed
      logoImage.width = initialSize.width;
      logoImage.height = initialSize.height;
      logoName.hidden = true;
    } else {
      // Sidebar is open
      // Adjust the logo size as needed
      logoImage.width = 120; // Increase the width to 120 when the sidebar is open
      logoImage.height = 120; // Increase the height to 120 when the sidebar is open
      logoName.hidden = false;
		setTimeout(showLogoName, 200);
    }
  });
	  
let currentDate = new Date();

    // Array of month names for display
    const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
	  document.getElementById("day").textContent = currentDate.getDate();
    // Update the content of the element with the ID "date" with the formatted date
    document.getElementById("month").textContent = monthNames[currentDate.getMonth()];
	  
	  
	 
document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Sales Amount (RM)',
                    data: [
                        <?php echo 1200; ?>,
                        <?php echo 1800; ?>,
                        <?php echo $data['March']; ?>,
                        <?php echo $data['April']; ?>,
                        <?php echo $data['May']; ?>,
                        <?php echo $data['June']; ?>,
                        <?php echo $data['July']; ?>,
                        <?php echo $data['August']; ?>,
                        <?php echo $data['September']; ?>,
                        <?php echo $data['October']; ?>,
                        <?php echo $data['November']; ?>,
                        <?php echo $data['December']; ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        color: '#fff', // Color of data labels
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value, context) {
                            return value + ' RM';
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
  </script>
</body>
</html>