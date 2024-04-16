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
		<div class="container">
			<div class="subCon">Low Stock Alert: <input type="text" style="height: 20px;" id="stockAlert" onKeyUp="lowStockNumber()"></div>
			<div class="headerButton"><button id="popupButton" onclick="openPopup(1)" class="save-button">+Add Product</button></div>
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
			<th onclick="sortTable(0)">Product Code <span class="sort-indicator" id="indicator0"></span></th>
			<th onclick="sortTable(1)">Image <span class="sort-indicator" id="indicator1"></span></th>
			<th onclick="sortTable(2)">Product Name <span class="sort-indicator" id="indicator2"></span></th>
			<th onclick="sortTable(3)">Description <span class="sort-indicator" id="indicator3"></span></th>
			<th onclick="sortTable(5)">Quantity Remaining (g)<span class="sort-indicator" id="indicator4"></span></th>
			<th onclick="sortTable(6)">Status <span class="sort-indicator" id="indicator5"></span></th>
			<th onclick="sortTable(7)">Action <span class="sort-indicator" id="indicator5"></span></th>
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
	  
    <div id="popupWindow" class="popup">
      <div class="popup-content">
		  <div class="xclose">
		  	<span class="close" onclick="closePopup()">&times;</span>
		  </div>
        	
			<form action="action/infoProduct.php" method="POST" class="myform" name="myForm" id="myForm" enctype="multipart/form-data">
			<input type="hidden" value="<?php echo $_SESSION['store']; ?>" id="storeID" name="storeID">
			<div class="myform-row">
				<div id="divalert" class="divalert" name="divalert"></div>
			</div>
				
			<div class="myform-row">
				<div class="label">
					<label for="productCode" class="myform-label">Product Code#</label>
				</div>
				<div class="input">
					<input type="text" id="productCode" name="productCode" class="myform-input" required>
				</div>
			</div>
			
			<div class="myform-row">
				<div class="label">
					<label for="productImage" class="myform-label">Image</label>
				</div>
				<div class="input" id="addImageFile">
        			<input type="file" id="proImage" name="proImage" class="myform-input" onchange="validateImage()">
				</div>
			</div>
				
			<div class="myform-row">
				<div class="label">
					<label for="productName" class="myform-label">Name:</label>
				</div>
				<div class="input">
					<input type="text" id="proName" name="proName" class="myform-input" required>
				</div>
			</div>

			<div class="myform-row">
				<div class="label">
					<label for="productDescr">Description:</label>
				</div>
				<div class="input">
					<textarea id="proDescr" name="proDescr" class="myform-input" style="height: 80px;" required></textarea>
				</div>
			</div>
			<div class="myform-row">
				<div class="label">
					<label for="productDescr">Quantity (g):</label>
				</div>
				<div class="input">
					<input type="text" id="proQuan" name="proQuan" class="myform-input" value="0" required>
				</div>
			</div>
			<div class="myform-row">
				<div class="label">
					<label for="productDescr">Low Stock Alert:</label>
				</div>
				<div class="input">
					<input type="text" id="txtStock" name="txtStock" class="myform-input" required>
				</div>
			</div>
			<div class="myform-row">
				<div class="label">
					<label for="proStatus">Categories:</label>
				</div>
				<div class="input">
					<select id="proCat" name="proCat" class="custom-select" required>
						<?php
							$sql = "SELECT * FROM categories WHERE categories_status = 1";
							$result = $db->query($sql);
							if ($result) {
								while ($row = $result->fetch_assoc()) {
									$categoryId = $row['categories_id'];
									$categoryName = $row['categories_name'];

									// Generate an <option> element for each category
									echo "<option value='$categoryId'>$categoryName</option>";
								}
							}
						?>
					</select>
				</div>
			</div>
				
			<!-- Price table -->
			<div class="myform-row">
				<div class="label">
					<label for="productPrices">Prices: </label>
				</div>
				<div class="input">
					<table id="priceTable" style="border: none">
						<tr style="text-align: center">
							<th>Weight</th>
							<th>Price</th>
							<th>Discount</th>
							<th>Action</th>
						</tr>
						<tr style="border: none">
							<td><input type="text" name="weight[]" class="myform-input" placeholder="Weight" required></td>
							<td><input type="text" name="price[]" class="myform-input" placeholder="Price" required></td>
							<td><input type="text" name="discount[]" class="myform-input" placeholder="Discount"></td>
							<td><button type="button" onclick="addPriceRow()">Add More</button></td>
						</tr>
					</table>
				</div>
			</div>

			<div class="myform-row">
				<div class="label">
					<label for="proStatus">Status:</label>
				</div>
				<div class="input">
					<select id="proStatus" name="proStatus" class="custom-select" required>
						<option value="1" style="color: green;" value="1">Active</option>
						<option value="2" style="color: red;" value="2">Inactive</option>
					</select>
				</div>
				
			</div>
				<input type="hidden" name="proID" id="proID">
				<input type="button" id="addProduct" class="button" value="Add Product" onClick="productInfo('add', this.form)">
				<input type="button" id="editProduct" class="button" value="Save Change" style="background-color: lightgreen;" onClick="productInfo('edit', this.form)">
				<input type="button" id="delProduct" class="button" value="Delete product"style="background-color: lightcoral;" onClick="confirmDelete(this.form)">
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
	
function lowStockNumber(){
	var num = $("#stockAlert").val();
	console.log(num);
	
	$.ajax({
      		url: "action/updateLowStock.php",
            type: "GET",
            data: {store: <?php echo $_SESSION['store'] ?>, num : num},
            success: function (response) {
				
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
            }
        });
}

function addPriceRow() {
    var priceTable = document.getElementById("priceTable");
    var newRow = priceTable.insertRow(priceTable.rows.length - 1); // Insert before the last row (before the "Add More" row)

    // Create cells for weight, price, and action
    var weightCell = newRow.insertCell(0);
    var priceCell = newRow.insertCell(1);
	var discountCell = newRow.insertCell(2);
    var actionCell = newRow.insertCell(3);

    // Add input fields for weight and price
    weightCell.innerHTML = '<input type="text" name="weight[]" class="myform-input" placeholder="Weight" required>';
    priceCell.innerHTML = '<input type="text" name="price[]" class="myform-input" placeholder="Price" required>';
	discountCell.innerHTML = '<input type="text" name="discount[]" class="myform-input" placeholder="Discount" required>';

    // Create a button element and set a unique id for it
    var removeButton = document.createElement("button");
    removeButton.type = "button";
    removeButton.innerHTML = "Remove";
    removeButton.setAttribute("id", "btnRemove" + removeButtonCounter); // Set the unique id here

    // Add an event handler for the remove button (optional)
    removeButton.addEventListener("click", function() {
        removePriceRow(this);
    });

    // Increment the counter for the next button
    removeButtonCounter++;

    // Append the button to the actionCell
    actionCell.appendChild(removeButton);
}

function removePriceRow(button) {
    var row = button.parentElement.parentElement;
    row.remove();
}

function productInfo(action, form) {
    var productCode = $('#productCode').val();
    var productName = $('#proName').val();
    var productDescription = $('#proDescr').val();
	var proQuan = $('#proQuan').val();
    var productStatus = $('#proStatus').val();
	var productID = $("#proID").val();
    var store = $('#storeID').val();

    // Extract weight, price and discount values from the table
    var weightValues = [];
    var priceValues = [];
	var discountValues = [];
    $('#priceTable tbody tr').each(function () {
		var weightInput = $(this).find('input[name="weight[]"]');
		var priceInput = $(this).find('input[name="price[]"]');
		var discountInput = $(this).find('input[name="discount[]"]');
		var weight = weightInput.val();
		var price = priceInput.val();
		var discount = discountInput.val();
		var priceId = $(this).data('price-id') || 'new'; // 'new' for new entries
		if (weight && price) {
			weightValues.push({weight: weight, priceId: priceId});
			priceValues.push({price: price, priceId: priceId});
			discountValues.push({discount: discount, priceId: priceId});
		}
	});
	
    // Check if required fields are filled
    if (productCode === "" || productName === "" || weightValues.length === 0 || priceValues.length === 0 || proQuan === "" && action != "del") {
        $('#divalert').css('background-color', 'red');
        $('#divalert').text('Product Code, Name, Weight, and Price must not be empty');
        $('#divalert').show();
        setTimeout(function () {
            $('#divalert').hide();
        }, 3000);
    } else {
		var text = "";
		var form = document.getElementById('myForm');
		var formData = new FormData(form);
		var imageInput = document.getElementById('proImage').files[0];
		formData.append('image', imageInput);
		formData.append('act', action);
		formData.append('proID', productID);
        $.ajax({
            url: $(form).attr('action'),
            type: $(form).attr('method'),
			data: formData,
			processData: false,
			contentType: false,
            //data: {act: action, data: $("#myForm").serialize(), proID: productID, image: imageInput},
            success: function (response) {
				if(action == "add"){
					text = "Product Added Successfully!";
					document.getElementById("myForm").reset();
				}
				else if(action == "edit")
					text = "Information Updated Successfully!";
				else if(action == 'del')
					text = "Product Deleted Successfully!";
                $('#divalert').css('background-color', 'green');
				$('#divalert').text(text);
				$('#divalert').show();
				setTimeout(function() {
					$('#divalert').hide();
				}, 3000);
				if(action == 'del'){
					closePopup();
					fetchData();
				}
				
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
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
	
    for (var i = startIndex; i < endIndex && i < data.length; i++) {
        var rowData = data[i];
        var newRow = document.createElement('tr');
        newRow.innerHTML = '<td>' + rowData.productCode + '</td>' +
            '<td style="padding: 0px;">' + rowData.productImage + '</td>' +
            '<td>' + rowData.productName + '</td>' +
            '<td>' + rowData.descr + '</td>' +
            '<td>' + rowData.quantity + '</td>' +
            `<td style="color: ${(rowData.status === '1') ? 'green' : 'red'};">${(rowData.status === '1') ? 'Active' : 'Inactive'}</td>`+
			'<td><i class="icon fa fa-eye" id="btnView'+i+'" name="'+rowData.productID+'" onclick="viewRec('+i+')"></i><i class="icon fa fa-edit" id="btnEdit'+i+'" name="'+rowData.productID+'" onclick="editRec('+i+')"></i><i class="icon fa fa-trash"id="btnDel'+i+'" name="'+rowData.productID+'" onclick="delRec('+i+')"></i></td>';
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

// Function to fetch and update data
function fetchData() {
    // Perform an AJAX request to fetch your data
	var search = "";
	search = document.getElementById("searchInput").value;
    $.ajax({
        url: 'action/fetchProduct.php',
        type: 'GET',
        dataType: 'json',
		data: {search:  search},
        success: function(response) {
			$("#stockAlert").val(response[0].lowStock);
			$("#txtStock").val(response[0].lowStock);
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
        url: 'action/fetchProduct.php',
        type: 'GET',
        dataType: 'json',
        data: {name: name},
        success: function(response) {
            openPopup(windowType);
            // Set product details
            $('#productCode').val(response[0].productCode).prop('readonly', windowType === 2);
            $('#proName').val(response[0].productName).prop('readonly', windowType === 2);
            $('#proDescr').val(response[0].descr).prop('readonly', windowType === 2);
			$('#proID').val(response[0].productID);

            // Clear existing price rows except the first two (header and initial row)
            var priceTable = document.getElementById("priceTable");
            var rowCount = priceTable.rows.length;
            for (var i = rowCount - 2; i > 0; i--) {
                priceTable.deleteRow(i);
            }

            // Populate price table
            response[0].prices.forEach(function(item) {
                addPriceRowWithData(item.proWeight, item.proPrice, item.proDisc, item.priceNo);
            });
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function addPriceRowWithData(weight, price, discount, priceNo) {
    var priceTable = document.getElementById("priceTable");
    var newRow = priceTable.insertRow(priceTable.rows.length - 1); // Insert before the last row

    var weightCell = newRow.insertCell(0);
    var priceCell = newRow.insertCell(1);
	var discountCell = newRow.insertCell(2);
    var actionCell = newRow.insertCell(3);

    weightCell.innerHTML = '<input type="text" name="weight[]" class="myform-input" value="' + weight + '" placeholder="Weight" required>';
    priceCell.innerHTML = '<input type="text" name="price[]" class="myform-input" value="' + price + '" placeholder="Price" required>';
	discountCell.innerHTML = '<input type="text" name="discount[]" class="myform-input" value="' + discount + '" placeholder="Discount" required>';
	actionCell.innerHTML = '<input type="hidden" value="'+priceNo+'" name="priceNo[]" id="priNo"><button type="button" onclick="removePriceRow(this)">Remove</button>';
}

	
function viewRec(num){
	var button = document.getElementById("btnView"+num);
	var name = button.getAttribute("name");
	
	findRec(2, name);
	
}
	
function editRec(num){
	var button = document.getElementById("btnEdit"+num);
	var name = button.getAttribute("name");
	findRec(3, name);
	
}
function delRec(num){
	var button = document.getElementById("btnDel"+num);
	var name = button.getAttribute("name");
	findRec(4, name);
}	
	
function confirmDelete(form) {
    // Ask for the first confirmation
    if (confirm("Are you sure you want to delete this product?")) {
    	// If the user confirms the second time, proceed with deletion
        productInfo('del', form);
    }
}
	
function addPriceRow() {
    addPriceRowWithData("", "", "", true); // Passing true to indicate that action buttons should be added
}

function openPopup(type) {
    document.getElementById("popupWindow").style.display = "block";
	
	if(type == 1){
		document.getElementById('addProduct').style.display = "block";
		document.getElementById('editProduct').style.display = "none";
		document.getElementById('delProduct').style.display = "none";
	}
	else if(type == 2){
		document.getElementById('addImageFile').style.display = "none";
		document.getElementById('addProduct').style.display = "none";
		document.getElementById('editProduct').style.display = "none";
		document.getElementById('delProduct').style.display = "none";
	}		
	else if(type == 3){
		document.getElementById('addProduct').style.display = "none";
		document.getElementById('editProduct').style.display = "block";
		document.getElementById('delProduct').style.display = "none";
	}
		
	else if(type == 4){
		document.getElementById('addProduct').style.display = "none";
		document.getElementById('editProduct').style.display = "none";
		document.getElementById('delProduct').style.display = "block";
	}
		
}
	
function validateImage() {
    var fileInput = document.getElementById("proImage");
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
</script>
