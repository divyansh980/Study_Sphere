<?php
include "db.php"; // Ensure this file contains the connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course = htmlspecialchars(trim($_POST['course']));
    $courseImage = $_FILES['courseimage'];

    // Check for errors in the uploaded file
    if ($courseImage['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Error uploading image.']);
        exit();
    }

    // Specify the directory where the image will be saved
    $uploadDir = 'images/'; // Make sure this directory exists and is writable
    $uploadFileName = basename($courseImage['name']); // Get only the file name

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($courseImage['tmp_name'], $uploadDir . $uploadFileName)) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO addcourses (courses, courseimage) VALUES (?, ?)");

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            die("MySQL prepare statement error: " . $conn->error);
        }

        // Bind the parameters (store only the image name)
        $stmt->bind_param("ss", $course, $uploadFileName); // Bind course name and image name only

        $response = [];
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Course inserted successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error inserting data: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
        exit();
    }

    // Close the connection
    $conn->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>