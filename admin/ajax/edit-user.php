<?php
include "db.php"; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the submitted data
   $id = trim($_POST['id']); // User ID (primary key)
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

   $sql = "UPDATE user SET full_name = ?, email = ?, password = ?, userid = ?, class = ?, subject = ?, user_type = ?, book= ? WHERE id = ?";
    
    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssssssssi", $full_name, $email, $password, $user_id, $class, $subject, $userType, $book, $id); // Store password as plain text

        // Execute the statement
        if ($stmt->execute()) {
            // Success
            $response = [
                'success' => true,
                'message' => 'User Details updated successfully.'
            ];
        } else {
            // Error during execution
            $response = [
                'success' => false,
                'message' => 'Error updating User Details: ' . $stmt->error
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