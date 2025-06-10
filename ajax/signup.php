<?php
include "db.php"; // Ensure this file contains the connection setup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $class = $_POST['class'];
    $subject = $_POST['subject'];

    // Check if class and subject are arrays
    if (!is_array($class)) {
        $class = [$class]; // Convert to array if it's not
    }
    if (!is_array($subject)) {
        $subject = [$subject]; // Convert to array if it's not
    }

    // Determine user type based on class and subject counts
    if (count($subject) >= 1 && count($class) == 1) {
        $usertype = "Student";
    } elseif (count($subject) == 1 && count($class) > 1) {
        $usertype = "Teacher";
    } else {
        die(json_encode(["success" => false, "message" => "Please fill the form correctly."]));
    }

    // Basic validation
    if (empty($email) || empty($class) || empty($subject)) {
        die(json_encode(["success" => false, "message" => "All fields are required."]));
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO user2 (email, class, subject, user_type) VALUES (?, ?, ?, ?)");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die(json_encode(["success" => false, "message" => "MySQL prepare statement error: " . $conn->error]));
    }

    // Convert class and subject arrays to JSON
    $classJson = json_encode($class);
    $subjectJson = json_encode($subject);

    // Bind the parameters and execute the statement
    $stmt->bind_param("ssss", $email, $classJson, $subjectJson, $usertype);

    $response = [];
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "User  registered successfully.";
        $response['usertype'] = $usertype; // Include usertype in the response
    } else {
        $response['success'] = false;
        $response['message'] = "Error in registering user: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>