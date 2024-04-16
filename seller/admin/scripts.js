let arrow = document.querySelectorAll(".arrow");

  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
   arrowParent.classList.toggle("showMenu");
    });
  }
  let sidebar = document.querySelector(".sidebar");
  let sidebarBtn = document.querySelector(".bx-menu");
	  let logoImage = document.getElementById("logo");
	  let logoName = document.getElementById("logo_name");
  		logoName.hidden = true;
	  let initialSize = { width: 80, height: 80 };
	  logoImage.width = initialSize.width;
	  logoImage.height = initialSize.height;
	  // Hide the logo name initially
  logoName.style.opacity = 0;

  // Function to show the logo name with a delaye
  function showLogoName() {
    logoName.style.opacity = 1;
  }
	  
  sidebarBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("close");
	  if (sidebar.classList.contains("close")) {
      // Sidebar is closed
      logoImage.width = initialSize.width;
      logoImage.height = initialSize.height;
      logoName.hidden = true;
    } else {
      // Sidebar is open
      // Adjust the logo size as needed
      logoImage.width = 120; // Increase the width to 120 when the sidebar is open
      logoImage.height = 120; // Increase the height to 120 when the sidebar is open
      logoName.hidden = false;
		setTimeout(showLogoName, 200);
    }
  });
	  

// Sorting function
function sortTable(columnIndex) {
    const table = document.getElementById("myTable");
    let rows, switching, i, x, y, shouldSwitch;
    switching = true;
    currentDirection *= -1;
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[columnIndex];
            y = rows[i + 1].getElementsByTagName("TD")[columnIndex];
            if (currentDirection == -1) {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                shouldSwitch = true;
                break;
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
        }
    }
    updateSortIndicator(columnIndex);
    displayRows();
}

// Update sort indicator
function updateSortIndicator(columnIndex) {
    const indicators = document.getElementsByClassName("sort-indicator");
    for (let i = 0; i < indicators.length; i++) {
        indicators[i].textContent = i === columnIndex ? (currentDirection === 1 ? "↓" : "↑") : "";
    }
}

function closePopup() {
   	document.getElementById("popupWindow").style.display = "none";
	document.getElementById('myForm').reset();
}

// Rest of the JavaScript..