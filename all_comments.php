<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="#">
    <title>Products</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">
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
    <!--header starts-->
    <?php
    $currentPage = 'products';
    include("includes/header.php");
    ?>

<div class="page-wrapper" > 
        <!-- top Links -->
        <div class="top-links">
            <div class="container">
                <ul class="row links">
                    <li class="col-xs-12 col-sm-4 link-item"><span>1</span><a href="merchants.php">Choose Merchant</a></li>
                    <li class="col-xs-12 col-sm-4 link-item active"><span>2</span><a href="products.php?res_id=<?php echo $_GET['res_id']; ?>">Pick Your Products</a></li>
                    <li class="col-xs-12 col-sm-4 link-item"><span>3</span><a href="#">Order and Pay Online</a></li>
                </ul>
            </div>
        </div>
        <!-- end:Top links -->
        <!-- start: Inner page hero -->
        <?php
            if (!isset($_GET['res_id'])) // hardcoded
            {
                $_GET['res_id'] = 51;
            }

            $ress = mysqli_query($db,"select * from restaurant where rs_id='$_GET[res_id]'");
            $rows = mysqli_fetch_array($ress);                                
        ?>

<section class="inner-page-hero bg-image" data-image-src="images/img/dish.jpeg">
            <div class="profile">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12  col-md-4 col-lg-4 profile-img">
                            <div class="image-wrap">
                                <figure><?php echo '<img src="seller/Res_img/'.$rows['image'].'" alt="Merchant logo">'; ?></figure>
                            </div>  
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 profile-desc">
                            <div class="pull-left right-text white-txt">
                                <h6><a href="#"><?php echo $rows['title']; ?></a></h6>
                                <p><?php echo $rows['description']; ?></p>
                                <p><?php echo $rows['address']; ?></p>
                              <!--  <ul class="nav nav-inline">
                                    <li class="nav-item ratings">
                                        <a class="nav-link" href="#"> <span>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star-o"></i>
                                        <p>245 Review</p>
                                        </span> </a>
                                    </li>
                                </ul> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="breadcrumb">
            <div class="container">
                
            </div>
        </div>

<!-- Section for viewing comments start -->

        <h1>All Comments</h1>

<?php
// Include the database connection
include("connection/connect.php");

// Check if the res_id is set in the URL
if (isset($_GET['res_id'])) {
    $res_id = $_GET['res_id'];

    // Check if the sort order is specified in the URL
    $sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'desc';

    // Get the current page number from the URL or default to page 1
    $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

    // Define the number of comments per page
    $commentsPerPage = isset($_GET['per_page']) ? $_GET['per_page'] : 5;

    // Query to fetch comments for the specified seller sorted by date with pagination
    $query = "SELECT * FROM user_comments WHERE res_id = $res_id ORDER BY created_at $sortOrder";
    $result = mysqli_query($db, $query);

    // Fetch all comments
    $allComments = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Calculate total comments and pages
    $totalComments = count($allComments);
    $totalPages = ceil($totalComments / $commentsPerPage);

    // Calculate the offset to fetch comments for the current page
    $offset = ($currentPage - 1) * $commentsPerPage;

    // Fetch comments for the current page
    $commentsForPage = array_slice($allComments, $offset, $commentsPerPage);
/*
    // Display the dropdown menu for selecting the number of comments per page
    echo '<label for="sort">Comments per page:</label>';
    echo '<select id="commentsPerPage" onchange="changeCommentsPerPage()">';
    echo '<option value="5" ' . ($commentsPerPage == 5 ? 'selected' : '') . '>5 per page</option>';
    echo '<option value="10" ' . ($commentsPerPage == 10 ? 'selected' : '') . '>10 per page</option>';
    echo '<option value="15" ' . ($commentsPerPage == 15 ? 'selected' : '') . '>15 per page</option>';
    echo '<option value="20" ' . ($commentsPerPage == 20 ? 'selected' : '') . '>20 per page</option>';
    echo '</select>';
*/
    // Display the sorting options
    echo '<div class="sorting-options">';
    echo '<form action="" method="GET">';
    echo '<label for="sort">Sort By:</label>';
    echo '<select id="sort" name="sort" onchange="this.form.submit()">';
    echo '<option value="desc" ' . ($sortOrder == 'desc' ? 'selected' : '') . '>Newest First</option>';
    echo '<option value="asc" ' . ($sortOrder == 'asc' ? 'selected' : '') . '>Oldest First</option>';
    echo '</select>';
    echo '</form>';
    echo '</div>';

    // Display comments as a table
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>User ID</th>';
    echo '<th>Restaurant ID</th>';
    echo '<th>Posted on</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($commentsForPage as $row) {
        echo '<tr>';
        echo '<td>' . $row['user_id'] . '</td>';
        echo '<td>' . $row['res_id'] . '</td>';
        echo '<td>' . $row['created_at'] . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td colspan="3">' . $row['comment'] . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
    echo '</div>';

    // Display pagination links
    echo '<div class="pagination">';
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i > 1) {
            echo ', '; // Add comma between page numbers
        }
        echo '<a href="all_comments.php?res_id=' . $res_id . '&per_page=' . $commentsPerPage . '&page=' . $i . '">' . $i . '</a>';
    }
    echo '</div>';

    // Add JavaScript for changing the number of comments per page
    echo '<script src="js/allcomments.js"></script>';
} else {
    echo '<p>Error: Seller ID not provided.</p>';
}

// Close the database connection
mysqli_close($db);
?>







<!-- Section for viewing comments end -->


        <!-- end:Container -->

        <div class="breadcrumb">
            <div class="container">
                
            </div>
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
    <script src="js/rating_product.js"></script>
    <script src="js/rating.js"></script>
    <script src="js/comment.js"></script>
    <script src="js/allcomments.js"></script>
</body>
</html>
