<?php include 'connect.php'; ?>
<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    <link rel="stylesheet" href="style.css">
    <!-- Boxiocns CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
	
	
<body>
  <div class="sidebar close">
    <?php include "sidebar.php"; ?>
  </div>
	
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Dashboard</span>
    </div>
	<div class="setting-Dash">
		<div class="setting">
  <div class="container">
    <div class="view-account">
        <section class="module">
            <div class="module-inner">
                <div class="content-panel">
                    <form class="form-horizontal">
						<fieldset class="fieldset">
                            <h3 class="fieldset-title">Shop Info 
								<?php
								
								if($_SESSION['status'] == 0){
								?>
									<input type="button" class="btnVerify" onclick="openPopup()" value="* Verify Now">
								<?php
								}
								else{
									?>
									<i class="fa-solid fa-circle-check" onclick="openPopup()" style="margin-left: 10px;"></i>
								<?php
								}
								?>
							</h3>
                            <div class="form-group">
                                <label class="label">Shop Title</label>
                                <div class="textfield">
                                    <input type="text" class="form-control" id="shopTitle">
                               
                                </div>
                            </div>
							<div class="form-group">
                                <label class="label">Email</label>
                                <div class="textfield">
                                    <input type="email" class="form-control" id="shopEmail">
                               
                                </div>
                            </div>
							<div class="form-group">
                                <label class="label">Phone Number</label>
                                <div class="textfield">
                                    <input type="text" class="form-control" id="shopNumber">
                               
                                </div>
                            </div>
							<div class="form-group">
                                <label class="label">Description</label>
                                <div class="textfield">
                                    <textarea class="form-control" rows="5" id="shopDescr"></textarea>
                               
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="fieldset">
                            <h3 class="fieldset-title">Personal Info</h3> 
							
                            <div class="form-group">
                                <label class="label">User Name</label>
                                <div class="textfield">
                                    <input type="text" class="form-control" id="ownerUser">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="label">Name</label>
                                <div class="textfield">
                                    <input type="text" class="form-control" id="ownerName">
                                </div>
                            </div>
							<div class="form-group">
                                <label class="label">Email</label>
                                <div class="textfield">
                                    <input type="email" class="form-control" id="ownerEmail">
                               
                                </div>
                            </div>
							<div class="form-group">
                                <label class="label">Phone Number</label>
                                <div class="textfield">
                                    <input type="text" class="form-control" id="ownerNumber">
                               
                                </div>
                            </div>
                        </fieldset>
						
                        <div class="form-group">
                            <div class="updateButtonBox">
                                <input class="updateButton" type="button" value="Update Profile" onClick="updatePass()">
                            </div>
                        </div>
						<hr>
						
						<fieldset class="fieldset">
                            <h3 class="fieldset-title">Security</h3>
                            <div class="form-group">
                                <label class="label">Old Password</label>
                                <div class="textfield">
                                    <input type="password" class="form-control" id="oldPass">
                               		<div id="passAlert0" class="passAlert"></div>
                                </div>
                            </div>
							<div class="form-group">
                                <label class="label">New Password</label>
                                <div class="textfield">
                                    <input type="password" class="form-control" id="newPass" onKeyUp="checkPass()">
                               		<div id="passAlert1" class="passAlert"></div>
                                </div>
                            </div>
							<div class="form-group">
                                <label class="label">Confirm Password</label>
                                <div class="textfield">
                                    <input type="password" class="form-control" onKeyUp="checkPass()" id="conPass">
                               		<div id="passAlert2" class="passAlert"></div>
                                </div>
                            </div>
                        </fieldset>
                        <div class="form-group">
                            <div class="updateButtonBox">
                                <input class="updateButton" type="button" value="Update Password" onClick="updatePass()">
                            </div>
                        </div>
                    </form>
					
					
					<div id="popupWindow" class="popup">
    					<div class="popup-content">
							<div class="xclose">
								<span class="close" onclick="closePopup()">&times;</span>
							</div>
							<div id="cardVerify">
								<form action="action/sellerVerify.php"  method="POST" class="myform" name="myForm" id="myForm" enctype="multipart/form-data">
									<div class="myform-row">
										<div id="divalert" class="divalert" name="divalert"></div>
									</div>
									<div class="myform-row">
										<div class="label">
											<label for="frontID" class="myform-label">Front ID Card</label>
										</div>
										<div class="input">
											<input type="file" onchange="validateImage()" id="frontID" name="frontID" class="myform-input" required>
											<div id="imageAlert0"></div>
										</div>
									</div>
									<div class="myform-row">
										<div class="label">
											<label for="backID" class="myform-label">Back ID Card</label>
										</div>
										<div class="input">
											<input type="file" id="backID" name="backID" class="myform-input" required>
											<div id="imageAlert1"></div>
										</div>
									</div>
									<div class="myform-row">
										<div class="label">
											<label for="IDwithFace" class="myform-label">ID Card with Face</label>
										</div>
										<div class="input">
											<input type="file" id="IDwithFace" name="IDwithFace" class="myform-input" required>
											<div id="imageAlert2"></div>
										</div>
									</div>
									<div style="text-align: center;">
										<input type="button" id="subDoc" class="button" value="Submit Document" onClick="submitDoc()">
									</div>
									
								</form>
								<div id="validationHistory" style="margin-top: 20px; width: 90%; margin-left: auto; margin-right: auto; text-align: center;">
									<h1>History Of Validating</h1>
									<table id="validationTable" style="width: 100%; border-collapse: collapse; border-top: 2px solid black;border-bottom: 2px solid black;">
										<thead>
											<tr>
												<th>No#</th>
												<th>Status</th>
												<th>Reason</th>
											</tr>
										</thead>
										<tbody style="border-collapse: collapse; border-bottom: 2px solid black;">
											<!-- Validation history rows will be displayed here -->
										</tbody>
									</table>
								</div>



							</div>
    					</div>
					</div>
					
                </div>
            </div>
        </section>
    </div>
</div>
	</div>
	</div>
	  
  </section>
  
</body>
</html>
<script src="scripts.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
	fetchData();
});
	
function retrieveRec() {
    $.ajax({
        url: "action/retrieveValidate.php",
        type: "GET",
        data: { admID: "<?php echo $_SESSION['adm_id']; ?>" },
        success: function(response) {
            // Parse the response as JSON
            var data = JSON.parse(response);
			

            var tbody = $('#validationTable tbody');

			// Clear existing rows
			tbody.empty();

			// Populate tbody with data
			for (var i = 0; i < data.length; i++) {
				var statusColor;
				var statusText;
				if (data[i].imgStatus == 1) {
					statusText = "Under validation";
					statusColor = "blue";
				} else if (data[i].imgStatus == 2) {
					statusText = "Rejected";
					statusColor = "red";
				} else if (data[i].imgStatus == 3) {
					statusText = "Successfully validated";
					statusColor = "green";
				}

				// Append row to tbody with inline styles for background color
				tbody.append('<tr><td>' + (i + 1) + '</td><td style="color: ' + statusColor + ';">' + statusText + '</td><td>' + data[i].imgComment + '</td></tr>');
			}

			// Apply inline CSS to remove border from tbody
			tbody.css('border', 'none');

        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

	
function submitDoc() {
    // Get the input elements for the front ID, back ID, and ID with face images
    var frontIDInput = document.getElementById('frontID');
    var backIDInput = document.getElementById('backID');
    var IDwithFaceInput = document.getElementById('IDwithFace');

    // Create a FormData object to store the files
    var formData = new FormData();

    // Add the files to the FormData object
    formData.append('frontID', frontIDInput.files[0]);
    formData.append('backID', backIDInput.files[0]);
    formData.append('IDwithFace', IDwithFaceInput.files[0]);

    // Create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Set up the request
    xhr.open('POST', 'action/sellerVerify.php', true);

    // Set up a function to handle the response
    xhr.onload = function() {
        if (xhr.status === 200) {
            // Request was successful, handle the response here if needed
            console.log(xhr.responseText);
        } else {
            // Request failed
            console.error('Request failed with status:', xhr.status);
        }
    };

    // Set up a function to handle any errors
    xhr.onerror = function() {
        console.error('Request failed');
    };

    // Send the request with the FormData containing the images
    xhr.send(formData);
}

	
function updatePass() {
    var pass = $("#oldPass").val();

    $.ajax({
        url: "action/checkOldPass.php", // The script to call to add data
        type: "GET",
        data: { admID: "<?php echo $_SESSION['adm_id']; ?>", pass:pass },
        success: function(response) {
            if (response == 1) {
                $("#passAlert0").hide();
				if($("#conPass").val() === "" || $("#newPass").val() === "" ){
					if($("#newPass").val() === "")
						$("#passAlert1").text("This Text Field Must Not Empty");
					else
						$("#passAlert1").hide();
					if($("#conPass").val() === "")
						$("#passAlert2").text("This Text Field Must Not Empty");
					else
						$("#passAlert2").hide();
				}
				else{
					$("#passAlert1").hide();
					$("#passAlert2").hide();
					
					if($("#conPass").val() == $("#newPass").val()){
						$.ajax({
						url: "action/updatePass.php", // The script to call to add data
						type: "GET",
						data: { admID: "<?php echo $_SESSION['adm_id']; ?>", newpass:$("#newPass").val() },
						success: function(response) {
							console.log(response);
							if(response){
								alert("Password Changed Successfully!");
								$("#oldPass").text("");
								$("#newPass").text("");
								$("#conPass").text("");
							}
							else{
								alert("Password Changed Failed!");
							}
						},
						error: function(xhr, status, error) {
            
						}
						})
					}
						
				}
			
            } else {
                $("#passAlert0").text("Old Password Not Match");
				
            }
        },
        error: function(xhr, status, error) {
            
        }
    });
}
	

function checkPass(){
	if($("#conPass").val() !== $("#newPass").val()){
		$("#passAlert2").show();
		$("#passAlert2").text("Password Not Match with New Password");
	}
	else{
		$("#passAlert2").hide();
		
	}
}
	
function validateImage() {
    var fileInput = document.getElementById('frontID');
    var filePath = fileInput.value;
    // Get the file extension
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
	// Get the file size
    var fileSize = fileInput.files[0].size;
	
    if (!allowedExtensions.exec(filePath)) {
        fileInput.value = '';
		$("#imageAlert").text("Please upload file having extensions .jpeg/.jpg/.png/.gif only.");
        return false;
		
    }
	else{
		$("#imageAlert").hide();
		if (fileSize > 10 * 1024 * 1024) {
			fileInput.value = '';
			$("#imageAlert").text("File size should not exceed 5MB.");
			return false;
		}
		else{
			$("#imageAlert").hide();
			return true;
		}
	}
}

function fetchData() {
	var userid = <?php echo $_SESSION['adm_id']; ?>;
    $.ajax({
        url: 'action/fetchAdminData.php',
        type: 'GET',
		data:{userId: userid},
        dataType: 'json',
        success: function(response) {
			$('#shopTitle').val(response[0][0]);
			$('#shopEmail').val(response[0][1]);
			$('#shopNumber').val(response[0][2]);
			$('#shopDescr').text(response[0][3]);
			$('#ownerUser').val(response[0][4]);
			//$('#ownerName').text(response[0][5]);
			//$('#ownerEmail').text(response[0][0]);
			//$('#ownerNumber').text(response[0][5]);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
	
function openPopup() {
    document.getElementById("popupWindow").style.display = "block";
	$('#cardVerify').show();
	retrieveRec();
}
	
	  
 
  </script>