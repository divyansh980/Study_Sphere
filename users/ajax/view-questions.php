<?php
include 'db.php'; // Database connection

// Check if POST variables are set
if (!isset($_POST['class'], $_POST['subject'], $_POST['book'], $_POST['lesson'], $_POST['userid'], $_POST['questiontype'])) {
    echo json_encode(['error' => 'Required parameters are missing']);
    exit;
}

$userid = $_POST['userid'];
$questiontype = $_POST['questiontype'];
$class = $_POST['class'];
$subject = $_POST['subject'];
$book = $_POST['book'];
$lessons = $_POST['lesson']; // Expecting an array of lessons

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

$query = "SELECT id, question FROM $tableName WHERE class = ? AND subject = ? AND book = ? AND lesson = ? AND user_id = ?";

$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['error' => 'SQL prepare failed: ' . $conn->error]);
    exit;
}

// Create an array for binding parameters



// Bind parameters
$stmt->bind_param('sssss',$class, $subject, $book, $lessons,$userid);

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
        'id' => $row['id']
    ]; // Append the question and marks to the array
}

// Return the questions and marks as JSON
echo json_encode(['questions' => $questions]);

// Close the statement and connection
$stmt->close();
$conn->close();
?>