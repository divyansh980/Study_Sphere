<?php
include "db.php"; // Ensure this file contains the connection setup
$id = $_POST['id'];

// Prepare and bind to fetch the user ID
$stmt = $conn->prepare("SELECT userid FROM user WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the user ID
$user = $result->fetch_assoc();
$userId = $user['userid'];

// Array of tables to delete from
$tables = ["arbased", "descriptive", "fillups", "truefalse", "mcq", "questionpaper", "addbook"];
$response = ['success' => true, 'messages' => []];

// Loop through each table and attempt to delete the records
foreach ($tables as $table) {
    $stmt = $conn->prepare("DELETE FROM $table WHERE user_id = ?");
    $stmt->bind_param("s", $userId);
    
    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Optionally, you can log successful deletions
        $response['messages'][] = "Deleted from $table successfully.";
    } else {
        // Log the error message without breaking the loop
        $response['success'] = false;
        $response['messages'][] = "Error deleting from $table: " . $stmt->error;
    }
    
    // Close the statement for the current table
    $stmt->close();
}

// Now delete the user from the user table
$stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
$stmt->bind_param("i", $id);

// Execute the statement for deleting the user
if ($stmt->execute()) {
    $response['messages'][] = "User  deleted successfully.";
} else {
    $response['success'] = false;
    $response['messages'][] = "Error deleting user: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>