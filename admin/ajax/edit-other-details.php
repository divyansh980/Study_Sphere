<?php
include "db.php"; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the submitted data
    $id = htmlspecialchars(trim($_POST['id'])); // User ID (primary key)
    $class = htmlspecialchars(trim($_POST['class']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $book = htmlspecialchars(trim($_POST['book']));
    $lesson = htmlspecialchars(trim($_POST['lesson']));
    $topic = htmlspecialchars(trim($_POST['topic']));

    // Update query (removed image fields)
    $sql = "UPDATE adddetails SET class = ?, subject = ?, book = ?, lesson = ?, topic = ? WHERE id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters (adjusted to remove image fields)
        $stmt->bind_param("sssssi", $class, $subject, $book, $lesson, $topic, $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->affected_rows > 0) {
                // Success
                $response = [
                    'success' => true,
                    'message' => 'Details updated successfully.'
                ];
            } else {
                // No rows affected (maybe the ID does not exist)
                $response = [
                    'success' => false,
                    'message' => 'No changes made. Please check the ID.'
                ];
            }
        } else {
            // Error during execution
            $response = [
                'success' => false,
                'message' => 'Error updating details: ' . $stmt->error
            ];
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error preparing the statement
        $response = [
            'success' => false,
            'message' => 'Error preparing statement: ' . $conn->error
        ];
    }

    // Close the database connection
    $conn->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If not a POST request, return an error
    $response = [
        'success' => false,
        'message' => 'Invalid request method.'
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>