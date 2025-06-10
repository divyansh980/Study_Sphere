<?php
include "db.php"; // Ensure this file contains the connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted data
    $class = htmlspecialchars(trim($_POST['class']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $book = htmlspecialchars(trim($_POST['book']));

    
    $bookImage = $_FILES['bookimage'];

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
   
    $bookImageResult = handleFileUpload($bookImage, $uploadDir);

    // Check if any file upload failed
    
    if (!$bookImageResult['success']) {
        echo json_encode($bookImageResult);
        exit();
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO books (class, subject , book, bookimage) VALUES (?, ?, ?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("MySQL prepare statement error: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("ssss", $class, $subject, $book, $bookImageResult['fileName']);

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