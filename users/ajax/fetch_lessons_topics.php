<?php
include "db.php"; // Include your database connection

// Corrected the condition to check if 'book', 'subject', and 'class' are set
if (isset($_POST['book']) && isset($_POST['subject']) && isset($_POST['class'])) {
    $book = $_POST['book'];
    $subject = $_POST['subject'];
    $class = $_POST['class'];

    // Corrected the SQL query syntax
    $queryLessons = "SELECT DISTINCT `lesson` FROM `adddetails` WHERE `class` = ? AND `subject` = ? AND `book` = ?";

    // Prepare and execute the statements for lessons
    $stmtLessons = $conn->prepare($queryLessons);
    if ($stmtLessons) { // Check if the statement was prepared successfully
        $stmtLessons->bind_param("sss", $class, $subject, $book);
        
        // Execute the statement
        $stmtLessons->execute();
        $resultLessons = $stmtLessons->get_result();

        // Fetch the results into arrays
        $lessons = [];
        while ($row = $resultLessons->fetch_assoc()) {
            $lessons[] = $row['lesson'];
        }

        // Return the response as JSON
        echo json_encode(['lessons' => $lessons]);
    } else {
        // Handle error if the statement could not be prepared
        echo json_encode(['error' => 'Failed to prepare the SQL statement.']);
    }
} else {
    // Handle the case where the required POST parameters are not set
    echo json_encode(['error' => 'Required parameters are missing.']);
}

$conn->close();
?>