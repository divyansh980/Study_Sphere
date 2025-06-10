<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User  not logged in']);
    exit();
}

include "db.php"; // Database connection

// Check if the required POST variables are set
if (!isset($_POST['questiontype'], $_POST['papertitle'], $_POST['questionid'])) {
    echo json_encode(['error' => 'Required parameters are missing']);
    exit();
}

// Get the form data
$questiontype = $_POST['questiontype'];
$paperTitle = $_POST['papertitle'];
$questionIds = $_POST['questionid'];

// Determine the table name based on question type
switch ($questiontype) {
    case 'MCQ':
        $tableName = 'mcq';
        break;
    case 'AR Based':
        $tableName = 'arbased';
        break;
    case 'Fillups':
        $tableName = 'fillups';
        break;
    case 'True/False':
        $tableName = 'truefalse';
        break;
    case 'Descriptive':
        $tableName = 'descriptive';
        break;
    default:
        echo json_encode(['error' => 'Invalid question type']);
        exit();
}

// Prepare to fetch questions from the determined table
$placeholders = implode(',', array_fill(0, count($questionIds), '?'));
$stmt = $conn->prepare("SELECT * FROM $tableName WHERE id IN ($placeholders)");

if (!$stmt) {
    echo json_encode(['error' => 'SQL prepare failed: ' . $conn->error]);
    exit();
}

// Bind parameters dynamically
$types = str_repeat('i', count($questionIds)); // Assuming question IDs are integers
$stmt->bind_param($types, ...$questionIds);

// Execute the statement to fetch questions
$stmt->execute();
$result = $stmt->get_result();

// Prepare the SQL statement for inserting into descriptivequestion
$insertStmt = $conn->prepare("INSERT INTO questionpaper (user_id, class, subject, question_type, book, lesson, question, answer, marks, difficulty, negative, solution, papertitle) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

if (!$insertStmt) {
    echo json_encode(['error' => 'SQL prepare failed: ' . $conn->error]);
    exit();
}

// Loop through each fetched question and insert into descriptivequestion
while ($row = $result->fetch_assoc()) {
    $userId = $row['user_id'];
    $class = $row['class'];
    $subject = $row['subject'];
    $questiontype = $row['question_type'];
    $book = $row['book'];
    $lesson = $row['lesson'];
    $questions = $row['question'];
    $answers = $row['answer'];
    $marks = $row['marks'];
    $difficulty = $row['difficulty'];
    $negative = ($questiontype === 'Descriptive') ? NULL : $row['negative'];
    
    // Check if the question type is True/False and set solution accordingly
    $solutions =  $row['solution'];

    // Bind parameters for the insert statement
    $insertStmt->bind_param("ssssssssiiiss", $userId, $class, $subject, $questiontype, $book, $lesson, $questions, $answers, $marks, $difficulty, $negative, $solutions, $paperTitle);
    
    // Execute the insert statement
    if (!$insertStmt->execute()) {
        echo json_encode(['error' => 'SQL execute failed: ' . $insertStmt->error]);
        exit();
    }
}

// Close the statements and connection
$stmt->close();
$insertStmt->close();
$conn->close();

// Return success message
echo json_encode(['success' => 'Question paper created successfully.']);
?>