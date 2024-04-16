<?php 	

include('../connect.php');


$sql = "SELECT empID, empname, empgender, empcontact, empemail, empjob, empstatus, icNo FROM tblemployee WHERE empstore = '".$_SESSION['store']."'";

if($_GET['search'] == ""){
	$sql .= " AND empstatus > 0";
}

if($_GET['search'] != ""){
	$sql .= " AND empID LIKE '%".$_GET['search']."%' OR icNo LIKE '%".$_GET['search']."%' OR empName LIKE '%".$_GET['search']."%' ";
}

$sql .= " ORDER BY empstatus";
$result = $db->query($sql);

$output = array('data' => array());

if($result->num_rows > 0) 
{ 
	// $row = $result->fetch_array();
	$active = ""; 

	while($row = $result->fetch_array()) {
		$empId = $row[0];

		$output['data'][] = array(
			$row[0], 
			$row[1], 
			$row[2], 
			$row[3], 
			$row[4], 
			$row[5], 
			$row[6],
			$row[7]
		); 	
	} // /while 
}// if num_rows


echo json_encode($output);
