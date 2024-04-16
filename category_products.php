<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Marketplace</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet"> <!-- Make sure this line is correct for your CSS file -->
    <link rel="icon" type="image/png" sizes="16x16" href="landing/logo.png">
</head>
<body class="home">
    <?php
    session_start(); // temp session
    error_reporting(0); // hide undefined index errors
    include("connection/connect.php"); // connection to database

    if (empty($_SESSION["user_id"])) // if not logged in
    {
        header("refresh:0;url=login.php"); // redirect to login.php page
    }
    ?>

    <!-- Header starts -->
    <?php include("includes/header.php"); ?>

    <!-- Banner part starts -->
    <section class="hero">
        <div class="hero-inner">
            <!-- Add your PHP code here to determine the active category -->
            <?php
                $activeCategoryId = isset($_GET['categories_id']) ? $_GET['categories_id'] : '';
            ?>

            <!-- Category Links -->
            <div>
                <a href="category_products.php?categories_id=5" class="btn btn-outline-info <?= $activeCategoryId == 5 ? 'active-link' : '' ?>" role="button">Leafy Green</a>
                <a href="category_products.php?categories_id=6" class="btn btn-outline-info <?= $activeCategoryId == 6 ? 'active-link' : '' ?>" role="button">Root Vegetables</a>
                <a href="category_products.php?categories_id=7" class="btn btn-outline-info <?= $activeCategoryId == 7 ? 'active-link' : '' ?>" role="button">Pome Fruits</a>
                <a href="category_products.php?categories_id=8" class="btn btn-outline-info <?= $activeCategoryId == 8 ? 'active-link' : '' ?>" role="button">Other</a>
            </div>
            
            <!-- ... Rest of your existing hero-inner content ... -->
        </div>
    </section>
    <?php
    if (isset($_GET['search']))
    {
    echo '<section class="popular">
            <div class="container">
                <div class="title text-xs-center m-b-30">
                    <h2>Search Results</h2>
                </div>
                <div class="row">';
 
                // fetch records from database to display first 12 products searched from the database
                $query_res = mysqli_query($db,"select * from product JOIN tblprice ON product.product_id = tblprice.productID WHERE product_name LIKE '%".$_GET['search']."%' LIMIT 12"); 
                
                while($r=mysqli_fetch_array($query_res))
                {   
                    echo '<div class="col-xs-12 col-sm-6 col-md-4 food-item">
                            <div class="food-item-wrap">
                                <div class="figure-wrap bg-image search-product" data-image-src="'.$r['product_image'].'">
                                </div>
                                <div class="product content" >
                                    <div class="price-btn-block" data-price-id="'.$r['priceNo'].'" data-product-owner="'.$r['owner'].'">
                                        <a href="products.php?res_id='.$r['owner'].'"> <h5>'.$r['product_name'].' ('.$r['proWeight'].'g)</h5></a>
                                        <div>'.$r['descr'].'</div>                       
                                        <div class="product-name" style="color: green;">Stock: '. (int) $r['quantity'].'</div>';

                                        $stmt = $db->prepare("SELECT price FROM custom_prices WHERE price_id = ? AND user_id = ?");
                                        $stmt->bind_param("ii", $r['priceNo'], $_SESSION['user_id']);
                                        $stmt->execute();
                                        $custom = $stmt->get_result();
                                        $customPrice = number_format($custom->fetch_assoc()['price'], 2);

                                        if ($customPrice != 0) {
                                        echo '            <span class="price">RM ' . $customPrice . '</span>';
                                        }
                    
                                        else if ($r['proDisc'] == 0) {
                                        echo '            <span class="price">RM ' . number_format($r['proPrice'], 2) . '</span>';
                                        }
                                        
                                        else {
                                        echo '            <a style="text-decoration: line-through; color: red;">RM ' . number_format($r['proPrice'], 2) . '</a>
                                                          <a style="color: orange;">'. $r['proDisc'] .'% off</a>
                                                          <span class="price">RM ' . number_format($r['proPrice']*(1-$r['proDisc']/100), 2) . '</span>';
                                        }
                                        
                                        echo '
                                        <button class="btn theme-btn-dash pull-right addToCart">Order Now</button>
                                    </div>
                                </div>
                            </div>
                        </div>';              
                }
        echo   '</div>
            </div>
        </section>';
    }
    ?>
    <!-- Search part ends-->

    <!-- Popular block starts -->
    <section class="popular">
        <div class="container">
            <div class="title text-xs-center m-b-30">
                <!--<h2>Little Farmer's Featured Products</h2>
                <p class="lead">Fresh and pesticide free!</p>-->
            </div>

            <div class="row">
            <?php
            // fetching records from table and filter using html data-filter tag
            if (isset($_GET['categories_id'])) 
            {
                $id = $_GET['categories_id'];
            }

            else
            {
                $id = 0;
            }

            // fetch records from database to display products of chosen category
            $query_res = mysqli_query($db,"select * from product JOIN tblprice ON product.product_id = tblprice.productID WHERE categories_id = {$id}"); 
                
            while($r=mysqli_fetch_array($query_res))
            {   
                echo '<div class="col-xs-12 col-sm-6 col-md-4 food-item">
                        <div class="food-item-wrap">
                            <div class="figure-wrap bg-image search-product" data-image-src="'.$r['product_image'].'">
                            </div>
                            <div class="product content" >
                                <div class="price-btn-block" data-price-id="'.$r['priceNo'].'" data-product-owner="'.$r['owner'].'">
                                    <a href="products.php?res_id='.$r['owner'].'"> <h5>'.$r['product_name'].' ('.$r['proWeight'].'g)</h5></a>
                                    <div>'.$r['descr'].'</div>                       
                                    <div class="product-name" style="color: green;">Stock: '. (int) $r['quantity'].'</div>';

                                    $stmt = $db->prepare("SELECT price FROM custom_prices WHERE price_id = ? AND user_id = ?");
                                    $stmt->bind_param("ii", $r['priceNo'], $_SESSION['user_id']);
                                    $stmt->execute();
                                    $custom = $stmt->get_result();
                                    $customPrice = number_format($custom->fetch_assoc()['price'], 2);

                                    if ($customPrice != 0) {
                                    echo '            <span class="price">RM ' . $customPrice . '</span>';
                                    }
                
                                    else if ($r['proDisc'] == 0) {
                                    echo '            <span class="price">RM ' . number_format($r['proPrice'], 2) . '</span>';
                                    }
                                    
                                    else {
                                    echo '            <a style="text-decoration: line-through; color: red;">RM ' . number_format($r['proPrice'], 2) . '</a>
                                                        <a style="color: orange;">'. $r['proDisc'] .'% off</a>
                                                        <span class="price">RM ' . number_format($r['proPrice']*(1-$r['proDisc']/100), 2) . '</span>';
                                    }
                                    
                                    echo '
                                    <button class="btn theme-btn-dash pull-right addToCart">Order Now</button>
                                </div>
                            </div>
                        </div>
                    </div>';              
            }
            ?>  
        </div>
    </section>
    <!-- Popular block ends -->
  
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
</body>
</html>
