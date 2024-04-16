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
      <span class="text">Customer</span>
    </div>
	  
	  <div class="empMainCon">
		  <div style="text-align: right; margin-bottom: 10px">
		  	<button id="popupButton" onclick="openPopup(1)" class="save-button">+Add Customer</button>
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
			<th onclick="sortTable(0)">NO# <span class="sort-indicator" id="indicator0"></span></th>
			<th onclick="sortTable(1)">Username <span class="sort-indicator" id="indicator1"></span></th>
			<th onclick="sortTable(2)">Name <span class="sort-indicator" id="indicator2"></span></th>
			<th onclick="sortTable(3)">Email <span class="sort-indicator" id="indicator3"></span></th>
			<th onclick="sortTable(4)">Phone Number <span class="sort-indicator" id="indicator4"></span></th>
			<th onclick="sortTable(5)">Address <span class="sort-indicator" id="indicator5"></span></th>
			<th onclick="sortTable(6)">Action <span class="sort-indicator" id="indicator6"></span></th>

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
		<div id="addCustomer">
			
			<form action="action/createUser.php" method="POST" class="myform" name="myForm" id="myForm">
			
			<div class="myform-row">
				<div id="divalert" class="divalert" name="divalert"></div>
			</div>
				
			<div class="myform-row">
				<div class="label">
					<label for="username" class="myform-label">Username</label>
				</div>
				<div class="input">
					<input type="text" id="username" name="username" class="myform-input" required>
				</div>
			</div>

			<div class="myform-row">
				<div class="label">
					<label for="password" class="myform-label">Password</label>
				</div>
				<div class="input">
					<input type="text" id="custPass" name="custPass" class="myform-input" required>
				</div>
			</div>

			<div class="myform-row">
				<div class="label">
					<label for="emoNum">Email:</label>
				</div>
				<div class="input">
					<input type="tel" id="custEmail" name="custEmail" class="myform-input" required>
				</div>
			</div>
				
			<input type="button" id="addEmployee" class="button" value="Add Customer" onClick="customerInfo('add', this.form)">
    </form>
		
		</div>
		<div id="showingPurchaseHistory">
			<div class="customer-info">
				<h2>Customer Information</h2>
				<form action="action/infoCustomer.php" method="POST" class="myform" name="myForm" id="myForm">
					<div style="text-align: center;">
						<p>Customer ID# <span id="customerID"></span></p>
						<p>Customer Name# <span id="customerName"></span></p>
						<p>Total Spent# RM <span id="customerSpent"></span></p>
					</div>
				</form>
			</div>

			<div class="purchase-history">
				<h2>Purchase History</h2>
				<table class="purchase-table">
					<thead>
						<tr>
							<th>Order Date</th>
							<th>Product Name</th>
							<th>Quantity</th>
							<th>Price</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody id="purchaseHistoryBody">
						<!-- Data will be populated here -->
					</tbody>
				</table>
			</div>
		</div>

    <div id="editCustomPrices">
      <h2>Custom Prices</h2>
      
      <div>
        <select id="productsList">
          <!-- Data will be populated here -->
        </select>
      </div>
      <br/>
      <div>Set price: </div>

      <input id="customPriceInput" type="text" required/>
      <button onclick="changePrice()">Change</button>
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
	$('#divalert').hide();	
	fetchData();
});
	
function customerInfo(action, form) {
    var username = $('#username').val();
    var custPass = $('#custPass').val();
    var custEmail = $('#custEmail').val();

    if (username === "" || custPass === "" || custEmail === "") {
        $('#divalert').css('background-color', 'red');
        $('#divalert').text('All text fields must not be empty');
        $('#divalert').show();
        setTimeout(function () {
            $('#divalert').hide();
        }, 3000);
    } else {
        // AJAX request
        $.ajax({
            url: $(form).attr('action'), // The script to call to add data
            type: $(form).attr('method'),
            data: {
                act: action,
                username: username,
                custPass: custPass,
                custEmail: custEmail,
            },
            success: function (response) {
                var resText = "";
                if (action == "add")
                    resText = "Customer Added Successfully!";
                if (action == "edit")
                    resText = "Information Updated Successfully!";
                if (action == "del")
                    resText = "Customer Deactivated Successfully!";
                $('#divalert').css('background-color', 'green');
                $('#divalert').text(resText);
                $('#divalert').show();
                setTimeout(function () {
                    $('#divalert').hide();
                }, 2000);
                if (action == "add") {
                    closePopup();
                    openPopup(1);
                    document.getElementById('myForm').reset();
                }
            },
            error: function (xhr, status, error) {
                $('#divalert').css('background-color', 'red');
                $('#divalert').text('Add Customer Failed');
                $('#divalert').show();
                setTimeout(function () {
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
			
        newRow.innerHTML = '<td>' + (i+1) + '</td>' +
            '<td>' + rowData[0] + '</td>' +
            '<td>' + rowData[1] + '</td>' +
            '<td>' + rowData[2] + '</td>' +
            '<td>' + rowData[3] + '</td>' +
            '<td>' + rowData[4] + '</td>' +
            //`<td style="color: ${(rowData[6] === '1') ? 'green' : 'red'};">${(rowData[6] === '1') ? 'Active' : 'Inactive'}</td>`+
			      '<td><i class="icon fa fa-eye" id="btnView'+i+'" name="'+rowData[5]+'" onclick="viewRec('+i+')"></i><i class="fa-solid fa-money-bill-1" id="customPrice'+i+'" name="'+rowData[5]+'" onclick="givePrice('+i+')"></i></td>';

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
        url: 'action/fetchUser.php',
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
	
function findRec(windowType, name) {
	$.ajax({
        url: 'action/fetchUserData.php',
        type: 'GET',
        dataType: 'json',
		data: {search:  name},
        success: function(response) {
			
			var purchaseHistoryBody = document.getElementById("purchaseHistoryBody");
			purchaseHistoryBody.innerHTML = ""; // Clear existing rows

			response.data.forEach(function(row) {
				var tr = document.createElement("tr");

				// Assuming row array is [userID, productID, quantity, price, total, fullName]
				tr.innerHTML = "<td>" + row[0] + "</td>" +
							   "<td>" + row[1] + "</td>" +
							   "<td>" + row[2] + "</td>" +
							   "<td>" + row[3] + "</td>" +
							   "<td>" + row[4] + "</td>";

				purchaseHistoryBody.appendChild(tr);
				$("#customerID").text(row[6]);
				$("#customerName").text(row[5]);
				$("#customerSpent").text(row[7]);
			});
      
			openPopup(windowType);
		},
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
	
function viewRec(num) {
	var button = document.getElementById("btnView"+num);
	var name = button.getAttribute("name");
  
	findRec(3, name);
}

function setPrice(windowType, name) {
  $.ajax({
        url: 'action/fetchPriceData.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
			var products = document.getElementById("productsList");
			products.innerHTML = "<option>Select product</option>";
          
			response.forEach(function(row) {
				var option = document.createElement("option");
        
				// Assuming row array is [price_id, product_name]
        option.setAttribute('user', name);
        option.id = row[0];
				option.innerHTML = row[1] + " (" + row[2] + "g)";
        
				products.appendChild(option);
			});
      
			openPopup(windowType);
		},
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function givePrice(num) {
	var button = document.getElementById("customPrice"+num);
	var name = button.getAttribute("name");
  
	setPrice(2, name);
}

function changePrice() {
  var select = document.getElementById('productsList');
  var selectedOption = select.options[select.selectedIndex];
  
  var user = selectedOption.getAttribute('user');
  var priceID = selectedOption.id;
  var price = document.getElementById('customPriceInput').value;

  // Regex from https://stackoverflow.com/questions/354044/what-is-the-best-u-s-currency-regex
  var priceRegex = /[0-9]{1,3}(?:,?[0-9]{3})*\.[0-9]{2}/;

  if (!priceRegex.test(price)) {
    alert("Please provide a valid price with two decimal places!");

    return;
  }

  $.ajax({
        url: 'action/editCustomPrice.php',
        type: 'POST',
        data: {user: user, priceID: priceID, price: price},
        dataType: 'json',
        success: function(response) {
          alert("Custom price set!");
		},
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}
	
function openPopup(type) {
    document.getElementById("popupWindow").style.display = "block";
	if(type == 1) {
    $("#editCustomPrices").hide();
		$("#showingPurchaseHistory").hide();
		$("#addCustomer").show();
	}
  else if(type == 2) {
    $("#editCustomPrices").show();
		$("#showingPurchaseHistory").hide();
		$("#addCustomer").hide();
  }
	else if(type == 3) {
    $("#editCustomPrices").hide();
		$("#showingPurchaseHistory").show();
		$("#addCustomer").hide();
	}
}

document.getElementById('productsList').addEventListener('change', function() {
  var select = document.getElementById('productsList');
  var selectedOption = select.options[select.selectedIndex];
  
  // Retrieve the values of user and id attributes
  var user = selectedOption.getAttribute('user');
  var id = selectedOption.getAttribute('id');

  $.ajax({
        url: 'action/fetchCustomPrices.php',
        type: 'GET',
        data: {user: user, id: id},
        dataType: 'json',
          success: function(response) {
            if (response.length == 1) {
              // First index is to obtain the only price_id, user_id, and custom price array
              // Second index is to obtain the custom price
              var price = response[0][2];
            }

            else {
              var price = "No custom price";
            }

            // Set the newly generated price on the selected option
            $('#customPriceInput').val(price);
		},
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});
</script>
