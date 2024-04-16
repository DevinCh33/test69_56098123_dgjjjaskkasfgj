<?php
require_once 'core.php';

$sql = "SELECT imgStatus, comment FROM tblvalidation WHERE storeID = '" . $_SESSION['store'] . "'";
$result = $db->query($sql);

$output = array(); // Initialize the output array

if ($result->num_rows > 0) {
    while ($row = $result->fetch_array()) {
        $output[] = array(
            'imgStatus' => $row['imgStatus'],
            'imgComment' => $row['comment']
        );
    } // /while
} // if num_rows

echo json_encode($output);
?>
