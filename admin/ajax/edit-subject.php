<?php
include "db.php"; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the submitted data
    $id = htmlspecialchars(trim($_POST['id'])); // User ID (primary key)
    
    $subject = htmlspecialchars(trim($_POST['subject']));
    
    
    // Handle file uploads
    $uploadDir = 'images/'; // Make sure this directory exists and is writable

    // Function to handle file uploads
    function handleFileUpload($fileInputName, $oldImageName, $uploadDir) {
        if ($_FILES[$fileInputName]['error'] !== UPLOAD_ERR_NO_FILE) {
            // If a new file is uploaded
            if ($_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
                return ['success' => false, 'message' => 'Error uploading file.'];
            }

            // Move the uploaded file to the specified directory
            $newFileName = basename($_FILES[$fileInputName]['name']);
            if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $uploadDir . $newFileName)) {
                // Return the new file name
                return ['success' => true, 'fileName' => $newFileName];
            } else {
                return ['success' => false, 'message' => 'Failed to move uploaded file.'];
            }
        }
        // If no new file is uploaded, return the old image name
        return ['success' => true, 'fileName' => $oldImageName];
    }

    // Handle each image upload
    $subjectImageResult = handleFileUpload('newsubjectimage', $_POST['oldsubjectimage'], $uploadDir);
    

    // Check if any file upload failed
    if (!$subjectImageResult['success']) {
        echo json_encode($subjectImageResult);
        exit();
    }
    
    
    // Update query
    $sql = "UPDATE subject SET subject = ?, subjectimage = ? WHERE id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssi",$subject, $subjectImageResult['fileName'] , $id); // Correct binding

        // Execute the statement
        if ($stmt->execute()) {
            // Success
            $response = [
                'success' => true,
                'message' => 'Details updated successfully.'
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