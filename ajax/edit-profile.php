<?php
include "db.php"; // Ensure this file contains the connection setup

 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $class = $_POST['class'];
    $subject = $_POST['subject'];
    $mobile = trim($_POST['mobile']) ?? null; // Trim the mobile input

    // Check if class and subject are arrays
    if (!is_array($class)) {
        $class = [$class]; // Convert to array if it's not
    }
    if (!is_array($subject)) {
        $subject = [$subject]; // Convert to array if it's not
    }

    // Basic validation
    if (empty($email) || empty($class) || empty($subject)) {
        die(json_encode(["success" => false, "message" => "Email, class, and subject are required."]));
    }
    
    // Prepare the SQL statement for retrieving the current user type
    $stmt = $conn->prepare("SELECT user_type FROM user2 WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($currentUser );
    $stmt->fetch();
    $stmt->close();

    // Prepare the SQL statement for updating the user
    // Include mobile in the update statement
    $stmt = $conn->prepare("UPDATE user2 SET class = ?, subject = ?, mobile = ? WHERE email = ?");

    // Check if the statement was prepared successfully
    if ($stmt === false) {
        die(json_encode(["success" => false, "message" => "MySQL prepare statement error: " . $conn->error]));
    }

    // Convert class and subject arrays to JSON
    $classJson = json_encode($class);
    $subjectJson = json_encode($subject);
    $stmt->bind_param("ssss", $classJson, $subjectJson, $mobile, $email);
    
    $response = [];
    if ($stmt->execute()) {
        // Determine new user type based on class and subject counts
        if (count($subject) >= 1 && count($class) == 1) {
            $newUser  = "Student";
        } elseif (count($subject) == 1 && count($class) > 1) {
            $newUser  = "Teacher";
        } else {
            $newUser  = "Unknown"; // Default case
        }

        // Check if the user type has changed
        if ($currentUser  !== $newUser ) {
            // Do not update the user type and return an error
            $response['success'] = false;
            $response['message'] = "User  type cannot be changed from '$currentUser ' to '$newUser '.";
        } else {
            $response['success'] = true;
            $response['message'] = "User  updated successfully.";
            $response['usertype'] = $newUser ; // Include new usertype in the response
        }
    } else {
        $response['success'] = false;
        $response['message'] = "Error in updating user: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>