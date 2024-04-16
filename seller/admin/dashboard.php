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

			// Determine whether the change is positive or negative
			$percentChange = "+" . $totalCurrentMonth;
			$word = "positive";
			?>
			<p><?php echo $totalSeller; ?><span class="stat-delta <?php echo $word; ?>"><?php echo $percentChange; ?></span></p>
			
      	</div>
		
		<div class="card">
			<h3>Seller Under Inspection</h3>
			<?php
				$querySeller = "SELECT COUNT(adm_id) AS total_seller FROM admin WHERE storeStatus = 0";
				$resultSeller = mysqli_query($db, $querySeller);
				$rowSeller = mysqli_fetch_assoc($resultSeller);
				$totalSeller = $rowSeller['total_seller'];
			?>
			<p><?php echo $totalSeller; ?></p>
		</div>
		
		<div class="card">
			<h3>Total Products</h3>
			<?php
			$queryProduct = "SELECT COUNT(product_id) AS total_product FROM product";
			$resultProduct = mysqli_query($db, $queryProduct);
			$rowProduct = mysqli_fetch_assoc($resultProduct);
			$totalProduct = $rowProduct['total_product'];
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
			$percent = $totalCurrentMonth-$totalLastMonth;
			if($totalCurrentMonth < $totalLastMonth){
				$percent = "-".$percent;
				$word = "negative";
			}	
			else{
				$percent = "+".$percent;
				$word = "positive";
			}
				
			
		?>
			<p><?php echo $totalProduct; ?><span class="stat-delta <?php echo $word; ?>"><?php echo $percent; ?></span></p>
		  </div>
		
		<div class="card">
			<h3>Product Under Inspection</h3>
			<?php
			// Query to get the total amount of orders for the current month
			$queryCurrentMonth = "SELECT COUNT(order_id) AS total_current_month FROM orders WHERE order_date like '".date("Y-m-")."%'";
			$resultCurrentMonth = mysqli_query($db, $queryCurrentMonth);
			$rowCurrentMonth = mysqli_fetch_assoc($resultCurrentMonth);
			$totalCurrentMonth = $rowCurrentMonth['total_current_month'];
		?>
			<p><?php echo $totalCurrentMonth; ?></p>
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
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                datasets: [{
                    label: 'Number Seller Join',
                    data: [
						<?php
						$countSeller = "SELECT COUNT(admin.adm_id) AS seller_count 
										FROM (
												SELECT 1 AS month
												UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION 
												SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12
										) AS month_list
										LEFT JOIN 
											admin ON MONTH(admin.date) = month_list.month AND YEAR(admin.date) = YEAR(CURRENT_DATE())
										GROUP BY month_list.month";
						$resultS = mysqli_query($db, $countSeller);
						$dataValues = array(); // Initialize the array to store data values
						if ($resultS) {
							while ($rowS = mysqli_fetch_assoc($resultS)) {
								// Store each seller count in the $dataValues array
								$dataValues[] = $rowS['seller_count'];
							}
						} else {
							echo "Error: " . mysqli_error($db); // Output an error message if query fails
						}
						
						$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

						for ($i = 0; $i < date('m'); $i++) {
							echo $dataValues[$i]; // Output dataValues array instead of $data array
							if ($i < count($months) - 1) {
								echo ",";
							}
							echo "\n";
						}
						?>
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