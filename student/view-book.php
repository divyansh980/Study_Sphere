<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php"); // Redirect to login if not logged in
    exit();
}

include "ajax/db.php";

$subject = $_GET['subject'];
$class = $_GET['class'];
$book = $_GET['book'];

// Prepare the SQL statement to fetch all data
$sql = "SELECT DISTINCT lesson FROM addbook WHERE class = ? AND subject = ? AND book = ?";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

// Bind parameters
$stmt->bind_param("sss", $class, $subject, $book);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the query was successful and if there are results
if ($result === false) {
    die("Error fetching data: " . htmlspecialchars($stmt->error));
}

// Fetch all results
$lessonsArray = [];
while ($row = $result->fetch_assoc()) {
    $lessons = $row['lesson']; // Assuming lessons are stored as a string
    if ($lessons !== null) {
        $lessonsArray[] = $lessons; // Add the lesson to the array
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Lessons</title>
    
    <?php include "include/links.php"; ?>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
    <?php include "include/header.php"; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	    <div class="container-full">
		    <!-- Main content -->
		    <section class="content">
			    <div class="row">
				    <div class="col-xl-12 col-12">
					    <div class="box">
						    <div class="box-header">
							    <h4 class="box-title mb-5"><?php echo $_GET['book'];?></h4>	
							   <ul style="line-height: 30px;">
							    <?php if (!empty($lessonsArray)): ?>
                                    <?php foreach ($lessonsArray as $lesson): ?>
                                        <li>
                                            <a href="preview-book.php?class=<?php echo urlencode($class); ?>&book=<?php echo urlencode($_GET['book']); ?>&subject=<?php echo urlencode($_GET['subject']); ?>&lesson=<?php echo urlencode($lesson); ?>">
                                                <?php echo htmlspecialchars($lesson); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li>No Lessons found for the selected criteria.</li>
                                <?php endif; ?>
                                </ul>
						    </div>
					    </div>
				    </div>
			    </div>							
		    </section>
		    <!-- /.content -->
	    </div>
    </div>
    <!-- /.content-wrapper -->
	
    <?php include "include/footer.php"; ?>
	
    <!-- Vendor JS -->
    <?php include "include/script.php"; ?>
	
</body>
</html>