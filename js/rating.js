document.addEventListener("DOMContentLoaded", function() {
    let ratingBlocks = document.querySelectorAll(".rating-block");

    ratingBlocks.forEach(function(ratingBlock) {
        let stars = ratingBlock.querySelectorAll("i");
        let restaurantId = ratingBlock.dataset.resid;

        stars.forEach(function(star, index) {
            // Add mouseover event listener to each star
            star.addEventListener("mouseover", function() {
                // Light up the stars up to the current index
                for (let i = 0; i <= index; i++) {
                    stars[i].classList.add("star-hover");
                }
            });

            // Add mouseleave event listener to each star
            star.addEventListener("mouseleave", function() {
                // Remove hover effect from all stars
                stars.forEach(function(star) {
                    star.classList.remove("star-hover");
                });
            });

            // Add click event listener to each star
            star.addEventListener("click", function() {
                // Update the rating value in the database
                let rating = index + 1; // Rating is 1-based

                // AJAX call to update_rating.php with the selected rating and restaurant ID
                $.ajax({
                    url: "update_rating.php",
                    method: "POST",
                    data: { rating: rating, res_id: restaurantId },
                    success: function() {
                        console.log("Rating updated successfully");
                    },
                    error: function(xhr, status, error) {
                        console.error("Failed to update rating:", error);
                    }
                });

                // Update the data-rating attribute to reflect the selected rating
                ratingBlock.dataset.rating = rating;

                // Toggle the class to fill or empty stars based on the clicked index
                for (let i = 0; i < stars.length; i++) {
                    if (i <= index) {
                        stars[i].classList.add("star-active");
                        stars[i].classList.remove("star-inactive");
                    } else {
                        stars[i].classList.remove("star-active");
                        stars[i].classList.add("star-inactive");
                    }
                }
            });
        });
    });



});
