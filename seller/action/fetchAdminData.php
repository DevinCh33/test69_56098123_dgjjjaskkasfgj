<?php 	
require_once 'core.php';

$adm_id = $_GET['userId'];

$sql = "SELECT r.title, r.email, r.phone, r.description, a.username, a.u_role
		FROM admin a
		JOIN restaurant r ON a.store = r.rs_id
		WHERE a.adm_id = '".$adm_id."'";

$result = $db->query($sql);


if ($result->num_rows > 0) { 
    while ($row = $result->fetch_assoc()) {
        // Store each row in the output array
        $output[] = array(
            'title' => $row['title'],
            'u_role' => $row['u_role'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'description' => $row['description'],
            'username' => $row['username']
        ); 	
    }
} else{
    $output[] = array(
        'title' => "SYSTEM ADMIN",
        'u_role' => "ADMIN",
        'email' => "", // Provide default value for email
        'phone' => "", // Provide default value for phone
        'description' => "", // Provide default value for description
        'username' => "" // Provide default value for username
    );
    
}

echo json_encode($output);