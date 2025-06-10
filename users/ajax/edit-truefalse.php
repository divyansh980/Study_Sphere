<?php
session_start();
include "db.php"; // Include your database connection

$response = array('success' => false, 'message' => '');

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $questionId = $_POST['questionid'];
    $class = $_POST['class'];
    $subject = $_POST['subject'];
    $book = $_POST['book'];
    $lesson = $_POST['lesson'];
    $questions = $_POST['questions']; // This will be an array
    $answers = $_POST['answer']; // This will be an array
    $marks = $_POST['marks'];
    $difficulty = $_POST['difficulty'];
    $negative = $_POST['negative'];
    $solution = $_POST['solution'];
    

    // Remove empty elements from questions and answers arrays
    $questions = array_filter($questions, function($value) {
        return !empty(trim($value)); // Remove empty strings
    });
    // Remove empty elements from questions and answers arrays
    $solution = array_filter($solution, function($value) {
        return !empty(trim($value)); // Remove empty strings
    });

    

    // Here you would typically perform your database update operation
    // Example:
    $sql = "UPDATE truefalse SET class=?, subject=?, book=?, lesson=?, question=?, answer=?, marks=?, difficulty=?, negative=?, solution=? WHERE id=?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiiisi", 
        $class, 
        $subject, 
        $book, 
        $lesson, 
        json_encode($questions), 
        $answers, 
        $marks, 
        $difficulty, 
        $negative, 
        json_encode($solution),
        $questionId
    );
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Question updated successfully.';
    } else {
        $response['message'] = 'Failed to update question: ' . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
echo json_encode($response); // Send back the response as JSON
?>