<?php
session_start();

include "db.php";

// Process the login form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $passwordInput = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT password FROM user WHERE userid = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check if the user exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($storedPassword);
        $stmt->fetch();

        // Verify the password
        if ($passwordInput === $storedPassword) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $username;
            echo "Login successful! Welcome, " . htmlspecialchars($username);
            // Redirect to admin dashboard or another page
            // header("Location: ../index.php");
            // exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }
    $stmt->close();
}

$conn->close();
?>