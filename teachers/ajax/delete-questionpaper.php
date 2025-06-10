<?php
include "db.php"; // Ensure this file contains the connection setup

// Check if 'id' is set in POST request
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare and bind for deleting from questionpaper
    $stmt1 = $conn->prepare("DELETE FROM questionpaper WHERE papertitle = ?");
    $stmt1->bind_param("s", $id); // Assuming papertitle is a string

    // Prepare and bind for deleting from paperdetails
    $stmt2 = $conn->prepare("DELETE FROM paperdetails WHERE papertitle = ?");
    $stmt2->bind_param("s", $id); // Assuming papertitle is a string

    // Execute the statements
    $response = [];
    if ($stmt1->execute() && $stmt2->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['message'] = $stmt1->error . ' ' . $stmt2->error; // Combine errors if any
    }

    // Close the statements
    $stmt1->close();
    $stmt2->close();
} else {
    $response['success'] = false;
    $response['message'] = 'ID not provided';
}

// Close the connection
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>