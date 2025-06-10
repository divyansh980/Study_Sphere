<?php
include "db.php"; // Ensure this file contains the connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Access the array of textarea values
   // Retrieve and sanitize form inputs
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $user_id = trim($_POST['user_id']);
    $class = $_POST['class'];
    $subject = $_POST['subject'];
    $book = $_POST['book'];
    $userType = $_POST['user-type'];
    // Basic validation
    if (empty($full_name) || empty($book) || empty($email) || empty($password) || empty($confirm_password) || empty($user_id)|| empty($class) || empty($subject) || empty($userType)) {
        die("All fields are required.");
    }

    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO user ( email, password, userid, full_name, class, subject, user_type,book) VALUES (?, ?, ?, ?, ?, ?, ?,?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die("MySQL prepare statement error: " . $conn->error);
    }

    // Bind the parameter and execute the statement
    $stmt->bind_param("ssssssss", $email, $password, $user_id, $full_name, $class, $subject, $userType, $book); // Store password as plain text

    $response = [];
if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = "User Registered Succesfully.";
} else {
    $response['success'] = false;
    $response['message'] = "Error in Registering user: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
}
?>