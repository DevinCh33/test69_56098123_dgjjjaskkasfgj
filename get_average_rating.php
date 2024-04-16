<?php
// Include your database connection file
include("connection/connect.php");

// Query to calculate average rating for a specific product
$query = "SELECT AVG(rating) AS avg_rating FROM product_ratings WHERE product_id = ?";
$stmt = mysqli_prepare($db, $query);
mysqli_stmt_bind_param($stmt, "i", $_GET['product_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $avgRating = round($row['avg_rating'], 1); // Round to one decimal place
    echo $avgRating;
} else {
    echo "0"; // Return 0 if no ratings found
}
