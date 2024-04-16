<?php include 'connect.php'; ?>
<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/CodingLabYT-->
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <!--<title> Drop Down Sidebar Menu | CodingLab </title>-->
    <link rel="stylesheet" href="style.css">
    <!-- Boxiocns CDN Link -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
	  
   </head>
	
<body>
	<input type="hidden" id="storeid" name="storeid" value="<?php echo $_SESSION['store'] ?>">
  <div class="sidebar close">
    <?php include "sidebar.php"; ?>
  </div>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Employee</span>
    </div>
	  
	  <div class="empMainCon">
		  <div style="text-align: right; margin-bottom: 10px">
		  	<button id="popupButton" onclick="openPopup(1)" class="save-button">+Add Employee</button>
		  </div>
		  
		   	<div class="controls-container">
		  		<div class="records-per-page">
					<span>Records per page:</span>
					<select id="recordsPerPage" onchange="changeRecordsPerPage()" class="custom-select">
					  <option value="5">5</option>
					  <option value="10">10</option>
					  <option value="50">50</option>
					</select>
				  </div>
		  	<div class="search-bar">
				<span>Search:</span>
				<input type="text" id="searchInput" onkeyup="fetchData()" placeholder="Search for names..">
		  	</div>
		  </div>

    <div class="table-container">
	  <table id="myTable">
		<thead>
		  <tr>
			<th onclick="sortTable(0)">Emp ID# <span class="sort-indicator" id="indicator0"></span></th>
			<th onclick="sortTable(1)">Name <span class="sort-indicator" id="indicator1"></span></th>
			<th onclick="sortTable(2)">Gender <span class="sort-indicator" id="indicator2"></span></th>
			<th onclick="sortTable(3)">Contact Number <span class="sort-indicator" id="indicator3"></span></th>
			<th onclick="sortTable(4)">Email <span class="sort-indicator" id="indicator4"></span></th>
			<th onclick="sortTable(5)">Job Title <span class="sort-indicator" id="indicator5"></span></th>
			<th onclick="sortTable(6)">Status <span class="sort-indicator" id="indicator6"></span></th>
			<th onclick="sortTable(7)">Action <span class="sort-indicator" id="indicator7"></span></th>

		  </tr>
		</thead>
		<tbody id="tableBody">
		  <!-- Table content goes here -->
		</tbody>
	  </table>
	</div>


    <div class="pagination-summary">
      <span id="tableSummary"></span>
      <div class="pagination">
		  
      </div>
    </div>
	  </div>
	  
	  
    <div id="popupWindow" class="popup">
		
      <div class="popup-content">
		  <div class="xclose">
		  	<span class="close" onclick="closePopup()">&times;</span>
		  </div>
        	
			<form action="action/infoEmployee.php" method="POST" class="myform" name="myForm" id="myForm">
			
			<div class="myform-row">
				<div id="divalert" class="divalert" name="divalert"></div>
			</div>
				
			<div class="myform-row">
				<div class="label">
					<label for="empID" class="myform-label">Emp ID#</label>
				</div>
				<div class="input">
					<div id="empID" name="empID"></div>
					<input type="hidden" id="emp" name="emp">
				</div>
			</div>
			
			<div class="myform-row">
				<div class="label">
					<label for="icNo" class="myform-label">IC No</label>
				</div>
				<div class="input">
					<input type="text" id="icNo" name="icNo" class="myform-input" required>
				</div>
			</div>

			<div class="myform-row">
				<div class="label">
					<label for="empName" class="myform-label">Name:</label>
				</div>
				<div class="input">
					<input type="text" id="empName" name="empName" class="myform-input" required>
				</div>
			</div>
				
			<div class="myform-row">
				<div class="label">
					<label for="gender">Gender:</label>
				</div>
				<div class="input">
					<div class="mydict">
						<div>
							<label>
								<input type="radio" name="empGender" id="empGender1" value="1" checked>
								<span>Women</span>
							</label>
							<label>
								<input type="radio" name="empGender" id="empGender2" value="2">
								<span>Man</span>
							</label>

						</div>
					</div>
				</div>
				
			</div>

			<div class="myform-row">
				<div class="label">
					<label for="emoNum">Contact Number:</label>
				</div>
				<div class="input">
					<input type="tel" id="empNum" name="empNum" class="myform-input" required>
				</div>
			</div>
				
			<div class="myform-row">
				<div class="label">
					<label for="empEmail">Email:</label>
				</div>
				<div class="input">
					<input type="email" id="empEmail" name="empEmail" class="myform-input" required>
				</div>
			</div>
				
			<div class="myform-row">
				<div class="label">
					<label for="empJob">Job Title:</label>
				</div>
				<div class="input">
					<select id="empJob" name="empJob" class="select">
						<option value="1">Accounting</option>
						<option value="2">Delivery</option>
						<option value="3">Packaging</option>
					</select>
				</div>
			</div>
				
			<div class="myform-row">
				<div class="label">
					<label for="empStatus">Status:</label>
				</div>
				<div class="input">
					<select id="empStatus" name="empStatus" class="custom-select" required>
						<option value="1" style="color: green;" value="1">Active</option>
						<option value="2" style="color: red;" value="2">Inactive</option>
					</select>
				</div>
				
			</div>

				<input type="button" id="addEmployee" class="button" value="Add Employee" onClick="employeeInfo('add', this.form)">
				<input type="button" id="editEmployee" class="button" value="Save Change" style="background-color: lightgreen;" onClick="employeeInfo('edit', this.form)">
				<input type="button" id="delEmployee" class="button" value="Delete Employee"style="background-color: lightcoral;" onClick="confirmDeleteEmployee(this.form)">
    </form>
      </div>
    </div>
	  
	  
	  
  </section>
  
</body>
</html>
<script src="scripts.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
$(document).ready(function() {
	$('#divalert').hide();	
	fetchData();
});
	
function employeeInfo(action, form){
	
	var icno = $('#icNo').val();
    var empName = $('#empName').val();
    var empNum = $('#empNum').val();
    var empEmail = $('#empEmail').val();
    var empJob = $('#empJob').val();
    var empStatus = $('#empStatus').val();
	var store = $('#storeid').val();

    if (icno === "" || empName === "" || empNum === "" || empEmail === "") {
        $('#divalert').css('background-color', 'red');
        $('#divalert').text('All text fields must not be empty');
        $('#divalert').show();
        setTimeout(function() {
            $('#divalert').hide();
        }, 3000);
    } else {
        // AJAX request
        $.ajax({
            url: $(form).attr('action'), // The script to call to add data
            type: $(form).attr('method'),
            data: {act: action, data: $(form).serialize()},
            success: function(response) {
				var resText = "";
					if(action == "add")
						resText = "Employee Added Successfully!";
					if(action == "edit")
						resText = "Information Updated Successfully!";
					if(action == "del")
						resText = "Employee Deactive Successfully!";
                $('#divalert').css('background-color', 'green');
				$('#divalert').text(resText);
				$('#divalert').show();
				setTimeout(function() {
					$('#divalert').hide();
				}, 2000);
				if(action == "add"){
					closePopup();
					openPopup(1);
					document.getElementById('myForm').reset();
				}
				

            },
            error: function(xhr, status, error) {
                $('#divalert').css('background-color', 'red');
				$('#divalert').text('Add Employee Failed');
				$('#divalert').show();
				setTimeout(function() {
					$('#divalert').hide();
				}, 3000);
				}
        	});
		}
}
	
var recordsPerPage = parseInt(document.getElementById('recordsPerPage').value);
var currentPage = 1;

function updateTableAndPagination(data) {
    // Calculate the start and end indices based on the current page and records per page
    var startIndex = (currentPage - 1) * recordsPerPage;
    var endIndex = startIndex + recordsPerPage;

    // Clear existing table rows
    var tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = '';
    // Populate the table with the data for the current page
    for (var i = startIndex; i < endIndex && i < data.data.length; i++) {
        var rowData = data.data[i];
        var newRow = document.createElement('tr');
		// Get the job name
		var jobName = "";
		var selectElement = document.getElementById("empJob");
		for (var j = 0; j < selectElement.options.length; j++) {
			if (selectElement.options[j].value === rowData[5]) {
				jobName = selectElement.options[j].text;
				break; // Exit the loop once a match is found
			 }
		}	
        newRow.innerHTML = '<td>' + rowData[0] + '</td>' +
            '<td>' + rowData[1] + '</td>' +
            '<td>' + (rowData[2] === '1' ? 'Women' : 'Man') + '</td>' +
            '<td>' + rowData[3] + '</td>' +
            '<td>' + rowData[4] + '</td>' +
            '<td>' + jobName + '</td>' +
            `<td style="color: ${(rowData[6] === '1') ? 'green' : 'red'};">${(rowData[6] === '1') ? 'Active' : 'Inactive'}</td>`+
			'<td><i class="icon fa fa-eye" id="btnView'+i+'" name="'+rowData[0]+'" onclick="viewRec('+i+')"></i><i class="icon fa fa-edit" id="btnEdit'+i+'" name="'+rowData[0]+'" onclick="editRec('+i+')"></i><i class="icon fa fa-trash"id="btnDel'+i+'" name="'+rowData[0]+'" onclick="delRec('+i+')"></i></td>';
        tableBody.appendChild(newRow);
    }
    // Update the table summary
    var totalRecords = data.data.length;
    var startRecord = Math.min(startIndex + 1, totalRecords);
    var endRecord = Math.min(endIndex, totalRecords);
    document.getElementById('tableSummary').textContent = 'Showing ' + startRecord + '-' + endRecord + ' of ' + totalRecords + ' Records';

    // Calculate the total number of pages based on the selected records per page
    var totalPages = Math.ceil(totalRecords / recordsPerPage);
	
    // Update the pagination controls
    var paginationControls = document.querySelector('.pagination');
    paginationControls.innerHTML = '';

    if (currentPage > 1) {
        paginationControls.innerHTML += '<button onclick="previousPage()" class="btnPrevious">Previous</button>';
    }

    for (var i = 1; i <= totalPages; i++) {
        paginationControls.innerHTML += '<span class="page-link" onclick="goToPage(' + i + ')">' + i + '</span>';
    }

    if (currentPage < totalPages) {
        paginationControls.innerHTML += '<button onclick="nextPage('+totalRecords+')" class="btnNext">Next</button>';
    }
}

// Function to handle the "change" event of the "recordsPerPage" dropdown
function changeRecordsPerPage() {
    recordsPerPage = parseInt(document.getElementById('recordsPerPage').value);
    currentPage = 1; // Reset to the first page when changing records per page
    // Call fetchData to get the updated data and update the table
    fetchData();
}

// Function to navigate to the previous page
function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        fetchData();
    }
}

// Function to navigate to the next page
function nextPage(num) {
    var totalRecords = num; // Replace yourDataArray with your actual data array
    var totalPages = Math.ceil(totalRecords / recordsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        fetchData();
    }
}

// Function to navigate to a specific page
function goToPage(page) {
    currentPage = page;
    fetchData();
}

// Function to fetch and update data
function fetchData() {
    // Perform an AJAX request to fetch your data
	var search = "";
	search = document.getElementById("searchInput").value;
    $.ajax({
        url: 'action/fetchEmployee.php',
        type: 'GET',
        dataType: 'json',
		data: {search:  search},
        success: function(response) {
            updateTableAndPagination(response);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
	
let currentColumn = -1;
let isAscending = true;

function sortTable(columnIndex) {
  const table = document.getElementById("myTable");
  const tbody = document.getElementById("tableBody");
  const rows = Array.from(tbody.rows);

  if (currentColumn === columnIndex) {
    isAscending = !isAscending;
  } else {
    isAscending = true;
    if (currentColumn !== -1) {
      document.getElementById("indicator" + currentColumn).classList.remove("asc", "desc");
    }
    currentColumn = columnIndex;
    document.getElementById("indicator" + currentColumn).classList.add("asc");
  }

  rows.sort((a, b) => {
    const aValue = a.cells[columnIndex].textContent.trim();
    const bValue = b.cells[columnIndex].textContent.trim();

    if (isAscending) {
      return aValue.localeCompare(bValue);
    } else {
      return bValue.localeCompare(aValue);
    }
  });

  tbody.innerHTML = "";
  rows.forEach((row) => tbody.appendChild(row));

  if (!isAscending) {
    document.getElementById("indicator" + currentColumn).classList.remove("asc");
    document.getElementById("indicator" + currentColumn).classList.add("desc");
  }
}
	
function findRec(windowType, name){
	
	$.ajax({
        url: 'action/fetchEmployee.php',
        type: 'GET',
        dataType: 'json',
		data: {search:  name},
        success: function(response) {
			openPopup(windowType);
			document.getElementById("empID").textContent = response.data[0][0];
			document.getElementById("emp").value = response.data[0][0];
			$('#icNo').val(response.data[0][7]).prop('readonly',  windowType === 2);
			$('#empName').val(response.data[0][1]).prop('readonly',  windowType === 2);
			$('#empNum').val(response.data[0][3]).prop('readonly',  windowType === 2);
			$('#empEmail').val(response.data[0][4]).prop('readonly',  windowType === 2);
			// Get the select element by its ID
			var gender = response.data[0][2];
			$('#empGender' + gender).prop('checked', true);
			
			// Get the select element by its ID
			var selectElement = document.getElementById("empJob");

			// Loop through the options and select the one that matches the data
			for (var i = 0; i < selectElement.options.length; i++) {
			  if (selectElement.options[i].value === response.data[0][5]) {
				selectElement.options[i].selected = true;
				break; // Exit the loop once a match is found
			  }
			}	
			// Get the select element by its ID
			var selectElement = document.getElementById("empStatus");

			// Loop through the options and select the one that matches the data
			for (var i = 0; i < selectElement.options.length; i++) {
			  if (selectElement.options[i].value === response.data[0][6]) {
				selectElement.options[i].selected = true;
				break; // Exit the loop once a match is found
			  }
			}	
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
	
function viewRec(num){
	var button = document.getElementById("btnView"+num);
	var name = button.getAttribute("name");
	findRec(2, name);
	
}
	
function editRec(num){
	var button = document.getElementById("btnView"+num);
	var name = button.getAttribute("name");
	findRec(3, name);
}
	
function delRec(num){
	var button = document.getElementById("btnView"+num);
	var name = button.getAttribute("name");
	findRec(4, name);
}	

function confirmDeleteEmployee(form) {
    // Ask for the first confirmation
    if (confirm("Are you sure you want to delete this employee?")) {
    	// If the user confirms the second time, proceed with deletion
        employeeInfo('del', form);
 
    }
}
	
function openPopup(type) {
    document.getElementById("popupWindow").style.display = "block";
	const now = new Date();

	  // Get the current year, month, day, hour, minute, and second
	  const year = now.getFullYear() % 100; // Get the last two digits of the year
	  const month = String(now.getMonth() + 1).padStart(2, '0'); // Add 1 because months are zero-indexed
	  const day = String(now.getDate()).padStart(2, '0');
	  const hour = String(now.getHours()).padStart(2, '0');
	  const minute = String(now.getMinutes()).padStart(2, '0');
	  const second = String(now.getSeconds()).padStart(2, '0');

	  // Combine the components into the YYmmddhhmmss format
	  const formattedDateTime = year+month+day+hour+minute+second;
	
	document.getElementById('empID').textContent = document.getElementById('storeid').value + formattedDateTime;
	document.getElementById('emp').value = document.getElementById('storeid').value + formattedDateTime;
	if(type == 1){
		document.getElementById('addEmployee').style.display = "block";
		document.getElementById('editEmployee').style.display = "none";
		document.getElementById('delEmployee').style.display = "none";
	}
	else if(type == 2){
		document.getElementById('addEmployee').style.display = "none";
		document.getElementById('editEmployee').style.display = "none";
		document.getElementById('delEmployee').style.display = "none";
	}		
	else if(type == 3){
		document.getElementById('addEmployee').style.display = "none";
		document.getElementById('editEmployee').style.display = "block";
		document.getElementById('delEmployee').style.display = "none";
	}
		
	else if(type == 4){
		document.getElementById('addEmployee').style.display = "none";
		document.getElementById('editEmployee').style.display = "none";
		document.getElementById('delEmployee').style.display = "block";
	}
		
}


</script>
