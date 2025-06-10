<?php
session_start();
include "db.php"; // Include your database connection

$response = array('success' => false, 'message' => '');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $Id = $_POST['id'];
    $class = $_POST['class'];
    $subject = $_POST['subject'];
    $book = $_POST['book'];
    $lesson = $_POST['lesson'];
    $word = $_POST['word'];
    $description = $_POST['description']; // This will be an array of description
    
    // JSON encode the description array
    $descriptionJson = json_encode($description);

    // Prepare SQL statement to update the question data
    $sql = "UPDATE dictionary SET class=?, subject=?, book=?, lesson=?, word=?, description=? WHERE id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", 
        $class, 
        $subject, 
        $book, 
        $lesson, 
        $word, 
        $descriptionJson, 
        $Id
    );

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Record updated successfully.';
    } else {
        $response['message'] = 'Failed to update record: ' . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response); // Send back the response as JSON
?>
