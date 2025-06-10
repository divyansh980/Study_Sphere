<?php
include 'db.php'; // Database connection

// Check if POST variables are set
if (!isset($_POST['questiontype'], $_POST['class'], $_POST['subject'], $_POST['book'], $_POST['lessons'])) {
    echo json_encode(['error' => 'Required parameters are missing']);
    exit;
}

$questiontype = $_POST['questiontype'];
$class = $_POST['class'];
$subject = $_POST['subject'];
$book = $_POST['book'];
$lessons = $_POST['lessons']; // Expecting an array of lessons

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
        exit;
}

// Prepare the SQL query using the determined table name
$lessonPlaceholders = implode(',', array_fill(0, count($lessons), '?'));
$query = "SELECT id, question, marks FROM $tableName WHERE class = ? AND subject = ? AND book = ? AND lesson IN ($lessonPlaceholders)";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['error' => 'SQL prepare failed: ' . $conn->error]);
    exit;
}

// Create an array for binding parameters
$params = array_merge([$class, $subject, $book], $lessons);

// Create a string of types for bind_param
$types = str_repeat('s', count($params)); // 's' for string type

// Bind parameters
$stmt->bind_param($types, ...$params);

// Execute the statement
if (!$stmt->execute()) {
    echo json_encode(['error' => 'SQL execute failed: ' . $stmt->error]);
    exit;
}

$result = $stmt->get_result();

// Initialize an array to hold the questions and marks
$questions = [];

// Fetch all questions and store them in the array
while ($row = $result->fetch_assoc()) {
    $questions[] = [
        'question' => $row['question'],
        'marks' => $row['marks'],
        'id' => $row['id']
    ]; // Append the question and marks to the array
}

// Return the questions and marks as JSON
echo json_encode(['questions' => $questions]);

// Close the statement and connection
$stmt->close();
$conn->close();
?>