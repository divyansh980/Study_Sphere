<?php
session_start();
include "db.php"; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userid'];
    $class = $_POST['class'];
    $subject = $_POST['subject'];
    $book = $_POST['book'];
    $lesson = $_POST['lesson'];
    $word = $_POST['word'];
    $description = $_POST['description']; // Array of books

    // JSON encode the books array
    $descriptionJson = json_encode($description);

    // Prepare the SQL statement for inserting the book data
    $stmt = $conn->prepare("INSERT INTO dictionary (user_id, class, subject, book, lesson, word, description) VALUES (?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("sssssss", $userId, $class, $subject, $book, $lesson, $word, $descriptionJson);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Data added successfully.']);
    } else {
        // Handle execution error
        echo json_encode(['success' => false, 'message' => 'Error inserting data: ' . $stmt->error]);
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
