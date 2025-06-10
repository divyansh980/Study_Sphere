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
    $topic = $_POST['topic'];
    $serial = $_POST['serial'];
    $books = $_POST['books']; // This will be an array of books
    
    // Remove empty elements from books array
    $books = array_filter($books, function($value) {
        return !empty(trim($value)); // Remove empty strings
    });

    // JSON encode the books array
    $booksJson = json_encode($books);

    // Prepare SQL statement to update the question data
    $sql = "UPDATE addbook SET class=?, subject=?, book=?, lesson=?, topic=?, books=?, serial=? WHERE id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", 
        $class, 
        $subject, 
        $book, 
        $lesson, 
        $topic, 
        $booksJson,
        $serial,
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
