<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

include "ajax/db.php";

$subject = $_GET['subject'] ?? '';
$class = $_GET['class'] ?? '';
$book = $_GET['book'] ?? '';
$lesson = $_GET['lesson'] ?? '';
// $topic = $_GET['topic'] ?? '';

// Prepare the SQL statement to fetch all data
$sql = "SELECT topic, serial, books FROM addbook WHERE class = ? AND subject = ? AND book = ? AND lesson = ? ORDER BY topic ASC, CAST(serial AS UNSIGNED) ASC";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Prepare failed: " . htmlspecialchars($conn->error));
}

// Bind parameters
$stmt->bind_param("ssss", $class, $subject, $book, $lesson);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the query was successful and if there are results
if ($result === false) {
    die("Error fetching data: " . htmlspecialchars($stmt->error));
}

// Fetch all results
$booksArray = [];
while ($row = $result->fetch_assoc()) {
    $books = json_decode($row['books'], true); // Assuming books is stored as JSON
    if ($books !== null) {
        $booksArray[] = $books; // Only add if JSON decoding was successful
    } else {
        die("Error decoding JSON: " . htmlspecialchars(json_last_error_msg()));
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
    
    <title>Preview Lesson</title>
    
    <?php include "include/links.php"; ?>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
    <?php 
    include "include/header.php";
    ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
	    <div class="container-full">
		    <!-- Main content -->
		    <section class="content">
			    <div class="row">
				    <div class="col-xl-12 col-12">
					    <div class="box">
						    <div class="box-header">
							    <h4 class="box-title"><?php echo $lesson?></h4>	
							    <?php if (!empty($booksArray)): ?>
                                    <?php foreach ($booksArray as $books): ?>
                                        <?php if (is_array($books)): // Check if books is an array ?>
                                            <?php foreach ($books as $bookItem): ?>
                                                <p><?php echo $bookItem; ?></p>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p><?php echo $books; ?></p>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No books found for the selected criteria.</p>
                                <?php endif; ?>
						    </div>
					    </div>
				    </div>
			    </div>							
		    </section>
		    <!-- /.content -->
	    </div>
    </div>
    <!-- /.content-wrapper -->
	
    <?php 
    include "include/footer.php";
    ?>
	
    <!-- Vendor JS -->
    <?php include "include/script.php"; ?>
	<script>
        // Using jQuery to target all <img> tags
        $(document).ready(function() {
    $('img').each(function() {
        // Get the natural dimensions of the image
        var width = this.naturalWidth;
        var height = this.naturalHeight;

        // Check if the dimensions are greater than 100
        if (width > 200 && height > 200) {
            $(this).addClass('img-fluid'); // Add the class 'img-fluid' if both dimensions are greater than 100
        }
    });
});
        $(document).ready(function() {
            var $tables = $('table'); // Select all <table> elements
            console.log($tables); // Log the jQuery object of tables to the console

            // Wrap each table in a div with the class 'table-responsive'
            $tables.wrap('<div class="table-responsive"></div>');

            // Example: Adding a class to all tables
            $tables.addClass('selected-table'); // Add a class to each table
        });
    </script>
</body>
</html>