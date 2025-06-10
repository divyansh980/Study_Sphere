<?php
include "db.php"; // Ensure this file contains the connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to hold error messages
    $errors = [];

    // Validate and sanitize input fields
    $class = htmlspecialchars(trim($_POST['class']));
    if (empty($class)) {
        $errors['class'] = "Class is required.";
    }

    $subject = htmlspecialchars(trim($_POST['subject']));
    if (empty($subject)) {
        $errors['subject'] = "Subject is required.";
    }

    $book = htmlspecialchars(trim($_POST['book']));
    if (empty($book)) {
        $errors['book'] = "Book is required.";
    }

    $lesson = htmlspecialchars(trim($_POST['lesson']));
    if (empty($lesson)) {
        $errors['lesson'] = "Lesson is required.";
    }

    $topic = htmlspecialchars(trim($_POST['topic']));
    if (empty($topic)) {
        $errors['topic'] = "Topic is required.";
    }

    // If there are errors, return them as a JSON response
    if (!empty($errors)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO adddetails (class, subject, book, lesson, topic) VALUES (?, ?, ?, ?, ?)");
    
    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("MySQL prepare statement error: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("sssss", $class, $subject, $book, $lesson, $topic);
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