<?php
include "db.php"; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the submitted data
    $id = htmlspecialchars(trim($_POST['id'])); // User ID (primary key)
    $course = htmlspecialchars(trim($_POST['course']));
    $newImage = $_FILES['newimage'];
    $oldImage = htmlspecialchars(trim($_POST['oldimage']));
    
    if ($newImage['error'] !== UPLOAD_ERR_NO_FILE) {
        // Handle the new image upload
        if ($newImage['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['success' => false, 'message' => 'Error uploading new image.']);
            exit();
        }

        // Specify the directory where the image will be saved
        $uploadDir = 'images/'; // Make sure this directory exists and is writable
        $uploadFileName = basename($newImage['name']);
        
        // Move the uploaded file to the specified directory
        if (!move_uploaded_file($newImage['tmp_name'], $uploadDir . $uploadFileName)) {
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
            exit();
        }
    } else {
        // If no new image is uploaded, keep the old image name
        $uploadFileName = $oldImage;
    }
    
    // Update query
    $sql = "UPDATE addcourses SET courses = ?, courseimage = ? WHERE id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssi", $course,$uploadFileName,$id); // Use the correct variables

        // Execute the statement
        if ($stmt->execute()) {
            // Success
            $response = [
                'success' => true,
                'message' => 'Course updated successfully.'
            ];
        } else {
            // Error during execution
            $response = [
                'success' => false,
                'message' => 'Error updating details: ' . $stmt->error
            ];
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement
        $response = [
            'success' => false,
            'message' => 'Error preparing statement: ' . $conn->error
        ];
    }

    // Close the database connection
    $conn->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If not a POST request, return an error
    $response = [
        'success' => false,
        'message' => 'Invalid request method.'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>