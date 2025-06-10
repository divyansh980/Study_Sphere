<?php
include "db.php"; // Include your database connection

if (isset($_POST['class'])) {
    $class = $_POST['class'];

    // Query to fetch subjects based on the selected class
    $query = "SELECT DISTINCT `subject` FROM `adddetails` WHERE `class` = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $class);
    $stmt->execute();
    $result = $stmt->get_result();

    $subjects = [];
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row['subject'];
    }

    // Return the response as JSON
    echo json_encode(['subjects' => $subjects]);
}
$conn->close();
?>