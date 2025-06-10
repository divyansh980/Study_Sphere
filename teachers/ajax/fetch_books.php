<?php
include 'db.php'; // Include your database connection

if (isset($_GET['class']) && isset($_GET['subject'])) {
    $class = $_GET['class'];
    $subject = $_GET['subject'];

    $bookquery = "SELECT DISTINCT `book` FROM adddetails WHERE subject = ? AND class = ?";
    $stmt = $conn->prepare($bookquery);
    $stmt->bind_param("ss", $subject, $class);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<option disabled selected>Please Select Book</option>';
        while ($row = $result->fetch_assoc()) {
            
            echo "<option value='" . htmlspecialchars($row['book']) . "'>" . htmlspecialchars($row['book']) . "</option>";
        }
    } else {
        echo "<option disabled>No Books available</option>";
    }

    $stmt->close();
}
$conn->close();
?>