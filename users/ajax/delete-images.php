<?php
include "db.php"; // Ensure this file contains the connection setup

$id = $_POST['id'];
// Prepare and bind to retrieve the image name
$stmt = $conn->prepare("SELECT `image` FROM contentimage WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($imageName);
$stmt->fetch();
$stmt->close();

// Construct the full image path
$imagePath = "../../ajax/images/" . trim($imageName); // Trim whitespace from the image name

// Debugging: Log the constructed image path
error_log("Constructed image path: " . $imagePath);

// Check if the image exists and delete it from the server
$response = [];
if ($imageName && file_exists($imagePath)) {
    if (unlink($imagePath)) {
        // Prepare and bind to delete the record from the database
        $stmt = $conn->prepare("DELETE FROM contentimage WHERE id = ?");
        $stmt->bind_param("i", $id);

        // Execute the statement
        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to delete the image from the server.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Image not found. Check the path: " . $imagePath; // Include the path in the error message
}

// Close the connection
$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>