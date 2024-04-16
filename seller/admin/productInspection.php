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
  <div class="sidebar close">
    <?php include "sidebar.php"; ?>
  </div>
  <section class="home-section">
    <div class="home-content">
      <i class='bx bx-menu' ></i>
      <span class="text">Products</span>
    </div>
	  
	<div class="empMainCon">
		  
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
			<th onclick="sortTable(0)">Product Code <span class="sort-indicator" id="indicator0"></span></th>
			<th onclick="sortTable(1)">Image <span class="sort-indicator" id="indicator1"></span></th>
			<th onclick="sortTable(2)">Product Name <span class="sort-indicator" id="indicator2"></span></th>
			<th onclick="sortTable(3)">Description <span class="sort-indicator" id="indicator3"></span></th>
			<th onclick="sortTable(4)">Category <span class="sort-indicator" id="indicator4"></span></th>
			<th onclick="sortTable(5)">Status <span class="sort-indicator" id="indicator5"></span></th>
			<th onclick="sortTable(6)">Action <span class="sort-indicator" id="indicator6"></span></th>
		  </tr>
		</thead>
		<tbody id="tableBody">
		  <!-- Table content goes here -->
		</tbody>
	  </table>
	</div>

    <div class="pagination-summary">
      <span id="tableSummary">Showing 1-10 of 100 Records</span>
      <div class="pagination">
		  
      </div>
    </div>
	  </div>
	  
  </section>
	
	<div id="popupWindow" class="popup">
    	<div class="popup-content">
        <div class="xclose">
            <span class="close" onclick="closePopup()">&times;</span>
        </div>
			<form class="myform" name="myForm" id="myForm">
				<div id="RejectReason">

					<div class="myform-row">
						<div id="divalert" class="divalert" name="divalert"></div>
					</div>

					<div class="myform-row">
						<div class="label">
							<label for="username" class="myform-label">Reason</label>
						</div>
						<div class="input">
							<input type="text" id="txtReason" name="txtReason" style="width: 80%; height: 30px;">
						</div>
					</div>

					<div style="text-align: center;">
						<input name="btnReason" type="button" class="button" id="btnReason" value="Reject" style="margin-top: 10px;">
					</div>
				</div>
			</form>
    </div>
</div>
  
</body>
</html>
<script src="scripts.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
	$('#divalert').hide();	
	fetchData();
});
	
function productInfo(action, btnID){
	
	if(action == 'rej'){
		alert('Product Rejected');
		openPopup();
	}
	else{
		$.ajax({
            url: "action/updateInspectionProduct.php",
            type: "GET",
			data: {proID : btnID, act : action},
            success: function (response) {
				alert(response);
				fetchData();
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
}
	}
	 


// Function to fetch and update data
function fetchData() {
    // Perform an AJAX request to fetch your data
	var search = "";
	search = document.getElementById("searchInput").value;
    $.ajax({
        url: 'action/fetchProductInspection.php',
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
	
	
/*Basic Function*/
	
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
	
function confirmDelete(form) {
	console.log(form);
    // Ask for the first confirmation
    if (confirm("Are you sure you want to delete this product?")) {
    	// If the user confirms the second time, proceed with deletion
        productInfo('del', form);
    }
}
	
	
var recordsPerPage = parseInt(document.getElementById('recordsPerPage').value);
var currentPage = 1;

function updateTableAndPagination(data) {
	
	if (data.length === 0) {
        document.getElementById('tableBody').innerHTML = '<tr><td colspan="7" style="text-align: center;">NO PRODUCT UNDER INSPECTION</td></tr>';
        document.getElementById('tableSummary').textContent = 'Showing 0-0 of 0 Records';
        document.querySelector('.pagination').innerHTML = ''; // Clear pagination controls
        return; // Exit function since there are no records to display
    }
    // Calculate the start and end indices based on the current page and records per page
    var startIndex = (currentPage - 1) * recordsPerPage;
    var endIndex = startIndex + recordsPerPage;

    // Clear existing table rows
    var tableBody = document.getElementById('tableBody');
    tableBody.innerHTML = '';
    // Populate the table with the data for the current page
	
    for (var i = startIndex; i < endIndex && i < data.length; i++) {
        var rowData = data[i];
        var newRow = document.createElement('tr');
        newRow.innerHTML = '<td>' + rowData.productCode + '</td>' +
            '<td style="padding: 0px;">' + rowData.productImage + '</td>' +
            '<td>' + rowData.productName + '</td>' +
            '<td>' + rowData.descr + '</td>' +
			'<td>' + rowData.cat + '</td>' +
			'<td>' + rowData.status + '</td>' +
			'<td><i class="fa-solid fa-check" id="btnApp'+i+'" name="'+rowData.productID+'" onclick="productInfo(\'app\', '+rowData.productID+')" style="margin-left: 10px;"></i><i class="fa-solid fa-x" id="btnRej'+i+'" name="'+rowData.productID+'" onclick="productInfo(\'rej\', '+rowData.productID+')" style="margin-left: 10px;"></i><i class="icon fa fa-trash"id="btnDel'+i+'" name="'+rowData.productID+'" onclick="productInfo(\'del\', '+rowData.productID+')" style="margin-left: 5px;"></i></td>';
        tableBody.appendChild(newRow);
    }
    // Update the table summary
    var totalRecords = data.length;
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
	
function openPopup(type) {
	document.getElementById("popupWindow").style.display = "block";    
}

</script>