<!DOCTYPE html>
<html lang="en">

<?php
session_start(); // temp session
error_reporting(0); // hide undefined index errors
include("connection/connect.php"); // connection to database

if (empty($_SESSION['user_id']))  //if user is not logged in, redirect baack to login page
{
    header('location:login.php');
}

?>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="#" />
    <title>Your Orders</title>
    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <link href="css/animsition.min.css" rel="stylesheet" />
    <link href="css/animate.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" />
    <link rel="icon" type="image/png" sizes="16x16" href="landing/logo.png" />
    <style type="text/css" rel="stylesheet">
        .indent-small {
            margin-left: 5px;
        }

        .form-group.internal {
            margin-bottom: 0;
        }

        .dialog-panel {
            margin: 10px;
        }

        .datepicker-dropdown {
            z-index: 200 !important;
        }

        .panel-body {
            background: #e5e5e5;
            /* Old browsers */
            background: -moz-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
            /* FF3.6+ */
            background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%, #e5e5e5), color-stop(100%, #ffffff));
            /* Chrome,Safari4+ */
            background: -webkit-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
            /* Chrome10+,Safari5.1+ */
            background: -o-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
            /* Opera 12+ */
            background: -ms-radial-gradient(center, ellipse cover, #e5e5e5 0%, #ffffff 100%);
            /* IE10+ */
            background: radial-gradient(ellipse at center, #e5e5e5 0%, #ffffff 100%);
            /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e5e5e5', endColorstr='#ffffff', GradientType=1);
            /* IE6-9 fallback on horizontal gradient */
            font: 600 15px "Open Sans", Arial, sans-serif;
        }

        label.control-label {
            font-weight: 600;
            color: #777;
        }

        table {
            width: 750px;
            border-collapse: collapse;
            margin: auto;
            margin-right: auto;
        }
        /* Zebra striping */
        tr:nth-of-type(odd) {
            background: #eee;
        }

        th {
            background: #ff3300;
            color: white;
            font-weight: bold;
        }

        td, th {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
            font-size: 14px;
        }
        /*
        Max width before this PARTICULAR table gets nasty
        This query will take effect for any screen smaller than 760px
        and also iPads specifically.
        */
        @media only screen and (max-width: 760px), (min-device-width: 768px) and (max-device-width: 1024px) {
            table {
                width: 100%;
            }

            /* Force table to not be like tables anymore */
            table, thead, tbody, th, td, tr {
                display: block;
            }

                /* Hide table headers (but not display: none;, for accessibility) */
                thead tr {
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }

            tr {
                border: 1px solid #ccc;
            }

            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }

                td:before {
                    /* Now like a table header */
                    position: absolute;
                    /* Top/left values mimic padding */
                    top: 6px;
                    left: 6px;
                    width: 45%;
                    padding-right: 10px;
                    white-space: nowrap;
                    /* Label the data */
                    content: attr(data-column);
                    color: #000;
                    font-weight: bold;
                }
        }

        .popup-container {
            display: none;
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            width: 70%;
        }

            .popup-container button {
                background-color: transparent;
                border: none;
                padding: 8px 12px;
                color: #333;
                font-size: 14px;
                cursor: pointer;
            }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>

<body>
    <!--header starts-->
    <?php
    $currentPage = 'orders';
    include("includes/header.php");
    ?>

    <div class="page-wrapper">
        <!-- start: Inner page hero -->
        <div class="inner-page-hero bg-image" data-image-src="images/img/res.jpeg">
            <div class="container"></div>
            <!-- end:Container -->
        </div>


        <div class="result-show">
            <div class="container">
                <div class="row"></div>
            </div>
        </div>
        <!-- //results show -->

        <section class="restaurants-page">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-7 col-md-12 ">
                        <div class="bg-gray restaurant-entry">
                            <div class="row">
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <th>Order ID#</th>
                                            <th>Seller</th>
                                            <th style="width: 10%">Number of Products</th>
                                            <th>Total Price</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        // displaying current session user login orders
                                        $query_res = mysqli_query($db, "SELECT orders.*, order_item.*, COUNT(order_item_id) as total_product, restaurant.*, tblprice.*, SUM(proPrice) as total
    FROM orders
    JOIN order_item ON orders.order_id = order_item.order_id
	JOIN tblprice ON order_item.priceID = tblprice.priceNo
    JOIN product ON tblprice.productID = product.product_id
    JOIN restaurant ON product.owner = restaurant.rs_id
    WHERE orders.user_id = '" . $_SESSION['user_id'] . "' AND orders.order_status <= 4
	GROUP BY orders.order_id
    ORDER BY orders.order_id DESC");

                                        if (!mysqli_num_rows($query_res) > 0) {
                                            echo '<td colspan="6"><center>You have no orders placed yet. </center></td>';
                                        } else {
                                            while ($row = mysqli_fetch_array($query_res)) {
                                                ?>
                                                <tr>
                                                    <td data-column="ProductID" style="text-decoration: underline;font-weight: bold;"><a onclick="openPopup(<?php echo $row['order_id']; ?>)"><?php echo $row['order_id']; ?></a></td>
                                                    <td data-column="Item"><?php echo $row['title']; ?></td>
                                                    <td data-column="Quantity"><?php echo $row['total_product']; ?></td>
                                                    <td data-column="Total Price">RM <?php echo $row['total']; ?></td>
                                                    <td data-column="status">
                                                        <?php
                                                        $status = $row['order_status'];
                                                        if ($status == "" or $status == "1") {
                                                            ?>
                                                            <button type="button" class="btn btn-info" style="font-weight:bold;">Processing</button>
                                                            <?php
                                                        }
                                                        if ($status == "2") { ?>
                                                            <button type="button" class="btn btn-warning"><span class="fa fa-cog fa-spin" aria-hidden="true"></span>On the Way</button>
                                                            <?php
                                                        }
                                                        if ($status == "3") {
                                                            ?>
                                                            <button type="button" class="btn btn-success"><span class="fa fa-check-circle" aria-hidden="true">Delivered</button>
                                                            <?php
                                                        }
                                                        ?>
                                                        <?php
                                                        if ($status == "4") {
                                                            ?>
                                                            <button type="button" class="btn btn-danger"><i class="fa fa-close"></i>cancelled</button>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                    <td data-column="Date"><?php echo $row['order_date']; ?></td>
                                                    <td data-column="Action">
                                                        <?php
                                                        if ($status == 1) {
                                                            ?>
                                                            <a href="delete_orders.php?order_del=<?php echo $row['o_id']; ?>" onclick="return confirm('Are you sure you want to cancel your order?');" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10"><i class="fa fa-trash-o" style="font-size:16px"></i></a>
                                                            <?php
                                                        } else if ($status == 2) {

                                                        } else if ($status == 3) {
                                                            ?>
                                                                    <a alt="Receipt"><i class="fa fa-file-text-o btn btn-primary" aria-hidden="true" onclick="generateReceipt(<?php echo $row['order_id']; ?>)"></i></a>
                                                            <?php
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } ?>
                                    </tbody>
                                </table>

                                <div id="popup" style="display: none;" class="popup-container">
                                    <div style="display: flex; justify-content: space-between; align-items: center; text-align: right; font-size: 30px;">
                                        <div><a style="color: black; text-align: center">Order Details</a></div>
                                        <button class="btn theme-btn" onclick="closePopup()" style="font-size: 20px;">X</button>
                                    </div>

                                    <table style="width: 100%; " border="1">
                                        <thead style="text-align: center;">
                                            <tr>
                                                <th>Image</th>
                                                <th>Product Name</th>
                                                <th style="text-align: center;">Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody id="orderBody"></tbody>
                                    </table>
                                </div>

                                <div id="overlay" class="overlay" onclick="closePopup()"></div>
                            </div>
                            <!--end:row -->
                        </div>
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

</body>
</html>

<script>
    function generateReceipt(rs_id) {
        console.log(rs_id);
        $.ajax({
            type: 'POST',
            url: 'order_receipt.php',
            data: { rs_id: rs_id },
            success: function (response) {
                // Open a new window and inject the receipt content
                console.log(response);
                var receiptWindow = window.open('', '_blank');
                receiptWindow.document.write(response);
            },
            error: function (xhr, status, error) {
                console.error('AJAX error: ' + status, error);
            }
        });
    }

    function openPopup(orderId) {
        document.getElementById('popup').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';

        $.ajax({
            url: './seller/action/getOrderProduct.php', // Path to your PHP script
            type: 'GET',
            data: { orderId: orderId },
            dataType: 'json',
            success: function (data) {
                var tbody = document.getElementById('orderBody');

                // Clear existing rows
                tbody.innerHTML = '';

                // Iterate through the data and append rows to the table
                for (var i = 0; i < data.length; i++) {

                    var row = '<tr  width="200" height="200">' +
                        '<td><center><img src="' + data[i].product_image + '" alt="Product Image" width="200" height="200"></center></td>' +
                        '<td width="50%"style="text-align: center; color: black;">' + data[i].product_name + '</td>' +
                        '<td width="20%" style="text-align: center;">' + data[i].quantity + '</td>' +
                        '</tr>';
                    tbody.innerHTML += row;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
            }
        });
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
        document.getElementById('overlay').style.display = 'none';
    }
</script>