<?php
session_start();
include "db.php"; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Retrieve POST data
    $updateId = $_POST['instructionid']; // Assuming you are passing the ID of the instruction to update
    $subject = $_POST['subject'];
    $instruction = $_POST['instruction']; // This should be an array
    $instructionanother = $_POST['instructionanother']; // This should also be an array

    // Remove blank entries from the instruction arrays
    $instruction = array_filter($instruction, function($value) {
        return !empty(trim($value)); // Remove empty or whitespace-only entries
    });

    $instructionanother = array_filter($instructionanother, function($value) {
        return !empty(trim($value)); // Remove empty or whitespace-only entries
    });

    // Check if there are any valid instructions after filtering
    if (empty($instruction) && empty($instructionanother)) {
        echo json_encode(['success' => false, 'message' => 'No valid instructions provided.']);
        exit; // Stop further execution
    }

    // JSON encode the filtered instructions
    $instructionJson = json_encode(array_values($instruction)); // Use array_values to re-index the array
    $instructionAnotherJson = json_encode(array_values($instructionanother)); // For the second set of instructions

    // Prepare the SQL statement for updating questions
    $stmt = $conn->prepare("UPDATE instruction SET subject = ?, instruction = ?, instructionanother = ? WHERE id = ?");

    // Bind parameters
    $stmt->bind_param("sssi", $subject, $instructionJson, $instructionAnotherJson, $updateId);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Instructions updated successfully.']);
    } else {
        // Handle execution error
        echo json_encode(['success' => false, 'message' => 'Error updating instructions: ' . $stmt->error]);
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>