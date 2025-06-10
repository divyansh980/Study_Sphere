<?php
session_start();
include "db.php"; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userid'];
   
    $class = $_POST['class'];
    $subject = $_POST['subject'];
    $book = $_POST['book'];
    $lesson = $_POST['lesson'];
    $questions = $_POST['questions'];
    $answers = $_POST['answer'];
    $marks = $_POST['marks'];
    $difficulty = $_POST['difficulty'];
    $negative = $_POST['negative'];
    $solutions = $_POST['solution'];

    // Remove blank entries from the questions array
    $questions = array_filter($questions, function($value) {
        return !empty(trim($value)); // Remove empty or whitespace-only entries
    });
    
    // Check if there are any valid questions after filtering
    if (empty($questions)) {
        echo json_encode(['success' => false, 'message' => 'No valid questions provided.']);
        exit; // Stop further execution
    }

    // JSON encode the filtered questions, answers, and solutions
    $questionsJson = json_encode(array_values($questions)); // Use array_values to re-index the array
    $answersJson = json_encode($answers); // Assuming you want to keep all answers
    $solutionsJson = json_encode($solutions); // Assuming you want to keep all solutions

    // Prepare the SQL statement for inserting questions and answers
    $stmt = $conn->prepare("INSERT INTO arbased (user_id, class, subject, book, lesson, question, answer, marks, difficulty, negative, solution) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("sssssssiiis", $userId, $class, $subject, $book, $lesson, $questionsJson, $answersJson, $marks, $difficulty, $negative, $solutionsJson);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Questions added successfully.']);
    } else {
        // Handle execution error
        echo json_encode(['success' => false, 'message' => 'Error inserting questions: ' . $stmt->error]);
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>