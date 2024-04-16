<!DOCTYPE html>
<html lang="en">
<head>
	<title>Little Farmer Website</title>
	<meta charset="UTF-8">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="landing/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/style.css" rel="stylesheet">
	<link rel="icon" type="image/png" sizes="16x16" href="landing/logo.png">
</head>

<body>
<?php
session_start(); // temp session
error_reporting(0); // hide undefined index errors
include("connection/connect.php"); // connection to database
?>

	<header>
		<img src="landing/logo.png" alt="logo">

		<ul>
			<li><a href="index.php" class="active">Home</a></li>
			<li><a href="#">About US</a></li>
			<li><a href="market.php">Market</a></li>
			<?php
			if($_SESSION['loginStatus'] == false){
			?>
			<li><a href="portal.php">Login / Sign Up</a></li>
			<!--<li><a href="registration.php">Sign Up</a></li>-->
			<?php
			}
			?>
		</ul>
	</header>

	<!-- Slide -->
	<div class="slide-container">
		<div class="slides fade">
			<img src="landing/slide1.jpg" alt="slide1">
		</div>
		<div class="slides fade">
			<img src="landing/slide2.jpg" alt="slide2">
		</div>
		<div class="slides fade">
			<img src="landing/slide3.jpg" alt="slide3">
		</div>
		<div class="slides fade">
			<img src="landing/slide4.png" alt="slide4">
		</div>

		<div class="dots-container">
    		<span class="dot"></span><span class="dot"></span><span class="dot"></span><span class="dot"></span>
		</div>
	</div>

	<!-- Product -->
	<section class="showProduct">
		<div class="top-section">
			<div class="promo">
				<h1>Sign Up Now</h1>
				<p>Join one of the best online fresh produce marketplaces!</p>
			</div>
			<div class="mainProduct-card">
				<h2>Your one stop shop for you cooking needs!</h2>
				<img src="landing/fruit.jpg" alt="fruit">
			</div>
		</div>

		<div class="products-grid">
		<?php
			// Query a maximum of 4 random active products
			$randomProductsQuery = "SELECT * FROM product WHERE status = 1 ORDER BY RAND() LIMIT 4";
			$result = mysqli_query($db, $randomProductsQuery);

			// Check if there are any products returned by the query
			if ($result && mysqli_num_rows($result) > 0) {
				// Loop through each product and display the card
				while ($product = mysqli_fetch_assoc($result)) {
					// Use htmlspecialchars to escape any special characters
					$productName = htmlspecialchars($product['product_name']);
					$productImage = htmlspecialchars($product['product_image']);

					echo '<a class="row" href="market.php">';
					echo '    <div class="col-3 product-card" style="height: 25rem;">';
					echo '        <h4>' . $productName . '</h4>';
					echo '        <img src="' . $productImage . '" alt="' . $productName . '" style="margin-top: 4rem; max-height: 20rem;">';
					// No need for <p>View range</p>
					echo '    </div>';
					echo '</a>';
				}
			} else {
				// No products found
				echo '<p>No products found.</p>';
			}
			?>
		</div>
	</section>

	<!-- About Little Farmer Section -->
	<section class="about-farmer">
		<div class="about-container">
			<img src="landing/logo.png" alt="About Little Farmer">
			<div class="about-content">
				<h2>About Little Farmer</h2>
				<p>Little Farmer has been cultivating organic produce for over two decades. Our commitment to fresh and sustainable farming has made us the choice of many households. Dive in to know more about our journey and values.</p>
				<div class="about-links">
					<a href="#">Our Story</a>
					<a href="#">Meet the Team</a>
					<a href="#">Something</a>
				</div>
			</div>
		</div>
	</section>

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

	<script>
    var slideIndex = 1; // Start with 1 to match your logic in displaySlide
    var slides = document.getElementsByClassName("slides");
    var dots = document.getElementsByClassName("dot");
    var slideInterval;

    function incrementSlide() {
        slideIndex++;
        if (slideIndex > slides.length) {
            slideIndex = 1;
        }
        displaySlide();
    }

	function displaySlide() {
    for (var i = 0; i < slides.length; i++) {
        slides[i].classList.remove("show"); // Remove the "show" class from all slides
    }
    
    for (var i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active-dot", "");
    }
    
    slides[slideIndex-1].classList.add("show"); // Add "show" class to the current slide
    dots[slideIndex-1].className += " active-dot";
	}

	// Existing incrementSlide and other functions remain unchanged.
    function currentSlide(n) {
        clearInterval(slideInterval);  // Stop the auto slideshow
        slideIndex = n;
        displaySlide();
        slideInterval = setInterval(incrementSlide, 4000); // Restart the auto slideshow
    }

    for (var i = 0; i < dots.length; i++) {
        dots[i].addEventListener("click", function() {
            var index = Array.prototype.indexOf.call(dots, this);
            currentSlide(index + 1);
        });
    }

    displaySlide(); // Display the first slide immediately
    slideInterval = setInterval(incrementSlide, 4000);  // Start the auto slideshow
	</script>
</body>
</html>
