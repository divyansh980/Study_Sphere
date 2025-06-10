<?php
include "db.php"; // Include your database connection

if (isset($_POST['subject']) && isset($_POST['class'])) {
    $subject = $_POST['subject'];
    $class = $_POST['class'];

    // Query to fetch books based on the selected subject and class
    $query = "SELECT DISTINCT `book` FROM `adddetails` WHERE `subject` = ? AND `class` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $subject, $class); // Bind both parameters
    $stmt->execute();
    $result = $stmt->get_result();

    $books = [];
    while ($row = $result->fetch_assoc()) {
        $books[] = $row['book'];
    }

    // Return the response as JSON
    echo json_encode(['books' => $books]);
}

$conn->close();
?>