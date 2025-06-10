<?php
include "db.php"; // Include your database connection

if (isset($_POST['lesson']) && isset($_POST['book']) && isset($_POST['subject']) && isset($_POST['class'])) {
    $lesson = $_POST['lesson'];
    $book = $_POST['book'];
    $subject = $_POST['subject'];
    $class = $_POST['class'];

    // Corrected the SQL query syntax
    $query = "SELECT DISTINCT `topic` FROM `adddetails` WHERE `class` = ? AND `subject` = ? AND `book` = ? AND `lesson` = ?";
    
    // Prepare the statement
    $stmt = $conn->prepare($query);
    if ($stmt) { // Check if the statement was prepared successfully
        $stmt->bind_param("ssss", $class, $subject, $book, $lesson);
        $stmt->execute();
        
        $result = $stmt->get_result();

        $topics = [];
        while ($row = $result->fetch_assoc()) {
            $topics[] = $row['topic'];
        }

        // Return the response as JSON
        echo json_encode(['topics' => $topics]);
    } else {
        // Handle error if the statement could not be prepared
        echo json_encode(['error' => 'Failed to prepare the SQL statement.']);
    }
} else {
    // Handle the case where required POST parameters are not set
    echo json_encode(['error' => 'Required parameters are missing.']);
}

$conn->close();
?>