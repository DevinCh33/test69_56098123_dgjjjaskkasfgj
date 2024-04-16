document.addEventListener('DOMContentLoaded', function () {
    const spayRadio = document.querySelector('.spay-radio');
    const spayImageContainer = document.querySelector('.spay-image-container');
    const spayImage = document.querySelector('.spay-image');
    const closeButton = document.querySelector('.close-btn');

    spayRadio.addEventListener('change', function () {
        if (spayRadio.checked) {
            // Set the image source in the image container
            spayImage.src = "images/spay.png"; // Update the image source
            spayImageContainer.style.display = 'block';
        } else {
            spayImageContainer.style.display = 'none';
        }
    });

    closeButton.addEventListener('click', function () {
        spayImageContainer.style.display = 'none';
    });
});
