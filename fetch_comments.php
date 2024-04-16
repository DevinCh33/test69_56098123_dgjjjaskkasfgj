<?php
include("connection/connect.php");

$res_id = $_GET['res_id']; // Get the restaurant ID from the GET parameter

$query = "SELECT * FROM user_comments WHERE res_id = $res_id ORDER BY created_at DESC";
$result = mysqli_query($db, $query);

$response = array();
if ($result && mysqli_num_rows($result) > 0) {
    $response['success'] = true;
    $comments = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $comments[] = array(
            'id' => $row['id'],
            'comment' => $row['comment'],
            'created_at' => $row['created_at']
        );
    }
    $response['comments'] = $comments;
} else {
    $response['success'] = false;
}

header('Content-Type: application/json');
echo json_encode($response);
