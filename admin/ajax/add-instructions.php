<?php
session_start();
include "db.php"; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Retrieve POST data
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

    // Prepare the SQL statement for inserting questions
    $stmt = $conn->prepare("INSERT INTO instruction (subject, instruction, instructionanother) VALUES (?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("sss", $subject, $instructionJson, $instructionAnotherJson);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Instructions added successfully.']);
    } else {
        // Handle execution error
        echo json_encode(['success' => false, 'message' => 'Error inserting instructions: ' . $stmt->error]);
    }

    // Close the statement and the connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>