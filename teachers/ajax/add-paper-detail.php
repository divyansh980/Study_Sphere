<?php
session_start(); // Start the session

// Include database connection
include "db.php"; // Make sure this path is correct

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'User  not logged in.']);
    exit();
}

// Check if the form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $userid = $_POST['userid'];
    $papertitle = $_POST['papertitle'];
    $institutionname = !empty($_POST['institutionname']) ? $_POST['institutionname'] : '';
    $institutionaddress = !empty($_POST['institutionaddress']) ? $_POST['institutionaddress'] : '';
    $maxmarks = $_POST['maxmarks'];
    $time = $_POST['time'];
    $instructions = isset($_POST['instructions']) ? $_POST['instructions'] : []; // Ensure it's an array
    $instructionsanother = isset($_POST['instructionsanother']) ? $_POST['instructionsanother'] : []; // Ensure it's an array

    // JSON encode the instructions arrays
    $instructionsJson = json_encode($instructions);
    $instructionsAnotherJson = json_encode($instructionsanother);

    // Handle file upload   
    $logoName = ''; // Initialize logoName as an empty string
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo = $_FILES['logo'];
        $uploadDir = 'logo/'; // Make sure this directory exists and is writable
        $logoName = basename($logo['name']); // Get only the file name

        // Move the uploaded file to the desired directory
        if (!move_uploaded_file($logo['tmp_name'], $uploadDir . $logoName)) {
            echo json_encode(['success' => false, 'message' => 'File upload failed.']);
            exit();
        }
    } else {
        // If logo is not uploaded, it will remain an empty string
        // Optionally, you can handle the case where the logo is required
        // echo json_encode(['success' => false, 'message' => 'Logo file is required.']);
        // exit();
    }

    // Prepare and bind the SQL statement
    $stmt = $conn->prepare("INSERT INTO paperdetails (user_id, papertitle, institutionname, institutionaddress, maxmarks, time, logo, instructions, instructionsanother) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $userid, $papertitle, $institutionname, $institutionaddress, $maxmarks, $time, $logoName, $instructionsJson, $instructionsAnotherJson); // Save JSON encoded instructions

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Question paper details added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Close the database connection
$conn->close();
?>