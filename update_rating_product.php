<?php
// Include your database connection file
include("connection/connect.php");

// Check if the rating, restaurant ID, and product ID are sent via POST
if (isset($_POST['rating']) && isset($_POST['res_id']) && isset($_POST['product_id'])) {
    // Sanitize and validate input
    $rating = intval($_POST['rating']);
    $res_id = intval($_POST['res_id']);
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user_id']; // Assuming you have user authentication and user ID in session

    // Check if the user has already rated this product
    $checkQuery = "SELECT * FROM product_ratings WHERE user_id = ? AND res_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($db, $checkQuery);
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $res_id, $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // User has already rated this product, update the existing rating
        $updateQuery = "UPDATE product_ratings SET rating = ? WHERE user_id = ? AND res_id = ? AND product_id = ?";
        $stmt = mysqli_prepare($db, $updateQuery);
        mysqli_stmt_bind_param($stmt, "iiii", $rating, $user_id, $res_id, $product_id);
    } else {
        // User has not rated this product, insert a new rating
        $insertQuery = "INSERT INTO product_ratings (user_id, res_id, product_id, rating) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($db, $insertQuery);
        mysqli_stmt_bind_param($stmt, "iiii", $user_id, $res_id, $product_id, $rating);
    }

    $result = mysqli_stmt_execute($stmt);

    // Check if the insert/update was successful
    if ($result) {
        // Return success response with HTTP status code 200
        http_response_code(200);
        echo json_encode(array("success" => true));
    } else {
        // Return error response with HTTP status code 500
        http_response_code(500);
        echo json_encode(array("error" => "Failed to update rating"));
    }
} else {
    // Return error response with HTTP status code 400 if data is not received
    http_response_code(400);
    echo json_encode(array("error" => "Invalid data received"));
}
