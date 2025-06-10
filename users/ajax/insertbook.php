<?php
session_start();
include "db.php"; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userid'];
    $class = $_POST['class'];
    $subject = $_POST['subject'];
    $book = $_POST['book'];
    $lesson = $_POST['lesson'];
    $topic = $_POST['topic'];
    $books = $_POST['books']; // Array of books
    $serial = $_POST['serial'];
    // JSON encode the books array
    $booksJson = json_encode($books);

    // Prepare the SQL statement for inserting the book data
    $stmt = $conn->prepare("INSERT INTO addbook (user_id, class, subject, book, lesson, topic, books,serial) VALUES (?, ?, ?, ?, ?, ?, ?,?)");

    // Bind parameters
    $stmt->bind_param("ssssssss", $userId, $class, $subject, $book, $lesson, $topic, $booksJson,$serial);

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
