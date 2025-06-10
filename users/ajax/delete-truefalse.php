<?php
include "db.php"; // Ensure this file contains the connection setup

$id = $_POST['id'];

// Prepare and bind
$stmt = $conn->prepare("DELETE FROM truefalse WHERE id = ?");
$stmt->bind_param("i", $id);

// Execute the statement
$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['message'] = $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>