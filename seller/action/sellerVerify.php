<?php

require_once 'core.php';
// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if all required fields are present
    if (isset($_FILES['frontID']) && isset($_FILES['backID']) && isset($_FILES['IDwithFace'])) {
        // Directory where uploaded images will be stored
        $uploadDirectory = "../images/verify/";

        // Verify uploaded images
        $frontID = verifyImage($_FILES['frontID'], $uploadDirectory);
        $backID = verifyImage($_FILES['backID'], $uploadDirectory);
        $IDwithFace = verifyImage($_FILES['IDwithFace'], $uploadDirectory);
		
		

        // If all images are verified successfully, proceed to store data
        if ($frontID && $backID && $IDwithFace) {
            // Perform additional data validation and processing here
            // For demonstration purposes, let's just echo the filenames
			$fullPath = "http://localhost/lfsc/seller/images/verify/";
			$sql = "INSERT INTO tblvalidation(frontImg, backImg, faceImg, imgStatus, sellerID) VALUES('".$fullPath.$frontID."', '".$fullPath.$backID."', '".$fullPath.$IDwithFace."',1, '".$_SESSION['store']."')";
			if($db->query($sql) == TRUE) {
				echo "Successfully Uploaded, Please Kindly Waiting for Verification";
			} else {
				echo "System Error! Submit Failed";
			}

        } else {
            // Image verification failed
            echo "Image verification failed. Please make sure all uploaded files are valid images.";
        }
    } else {
        // Required fields are missing
        echo "Please upload all required images.";
    }
} else {
    // Not a POST request
    echo "Invalid request method.";
}

// Function to verify and move uploaded image to the specified directory
function verifyImage($file, $uploadDirectory) {
    // Get the file type before using it
    $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = uniqid() . '.' . $fileType;
    $targetPath = $uploadDirectory . $fileName;

    // Check if the file is an actual image
    $check = getimagesize($file['tmp_name']);
    if ($check !== false) {
        // Check file size if needed
        if ($file['size'] > 5000000) { // Adjust size limit as needed
            echo "Sorry, your file is too large.";
            return false;
        }

        // Allow only certain file formats
        $allowedFormats = array("jpg", "jpeg", "png", "gif");
        if (!in_array(strtolower($fileType), $allowedFormats)) {
            echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
            return false;
        }

        // Move the file to the specified directory
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return false;
        }
    } else {
        echo "File is not an image.";
        return false;
    }
}

?>
