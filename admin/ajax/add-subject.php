<?php
include "db.php"; // Ensure this file contains the connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted data
    
    $subject = htmlspecialchars(trim($_POST['subject']));
    

    // Handle file uploads
    $subjectImage = $_FILES['subjectimage'];
   

    $uploadDir = 'images/'; // Make sure this directory exists and is writable

    // Function to handle file upload
    function handleFileUpload($file, $uploadDir) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Error uploading file.'];
        }

        $uploadFileName = basename($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $uploadDir . $uploadFileName)) {
            return ['success' => false, 'message' => 'Failed to move uploaded file.'];
        }

        return ['success' => true, 'fileName' => $uploadFileName];
    }

    // Upload images
    $subjectImageResult = handleFileUpload($subjectImage, $uploadDir);
    

    // Check if any file upload failed
    if (!$subjectImageResult['success']) {
        echo json_encode($subjectImageResult);
        exit();
    }
    

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO subject (subject, subjectimage) VALUES (?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("MySQL prepare statement error: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("ss", $subject, $subjectImageResult['fileName']);

    $response = [];
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Details inserted successfully.";
    } else {
        $response['success'] = false;
        $response['message'] = "Error inserting data: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>