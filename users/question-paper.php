<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
include "ajax/db.php";

// Fetch POST data safely

$class = $_GET['class'] ?? ''; // Use $_POST instead of $POST
$subject = $_GET['subject'] ?? '';
$book = $_GET['book'] ?? '';
$papertitle = $_GET['papertitle'] ?? '';

// Prepare the SQL statement to fetch data based on the new fields
$query = "SELECT * FROM questionpaper WHERE class='$class' AND subject='$subject' AND book='$book' AND papertitle='$papertitle'";
$result = $conn->query($query);

// Check if the query was successful
if ($result === false) {
    die("Error fetching data: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Create Question Paper</title>
    
    <?php include "include/links.php"; ?>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
    <?php include "include/header.php"; ?>

    <div class="content-wrapper">
		<div class="container-full">
			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-12">
						<div class="box">
							<div class="box-header">
    <h4 class="box-title mb-5">Question Paper</h4>  
    <!-- Display fetched data -->
    
				    <?php
	echo '<div id="question-paper">';
$questionCounter = 0; // Initialize question counter

while ($row = $result->fetch_assoc()) {
    // Assuming the 'question' column contains a JSON encoded array
    $questions = json_decode($row['question'], true); // Decode the JSON string into an associative array
    $marks = htmlspecialchars($row['marks']); // Fetch marks
    $questiontype = htmlspecialchars($row['question_type']); 
    
    if (is_array($questions) && count($questions) > 0) {
        $questionCounter++; // Increment the question counter for each question array

        // Display the question number
        echo "<div class='row' style='margin-bottom: 10px;'>"; // Create a row for each question

        // First column for the question number
        echo "<div class='col-md-1 text-center'>";
        echo "Q" . $questionCounter . ".";
        echo "</div>";

        // Second column for the question text
        echo "<div class='col-md-10'>";

if ($questiontype === 'MCQ') {
    // Iterate through the questions
    for ($q = 0; $q < count($questions); $q++) {
        // Display the current question
        echo "" . $questions[$q] . "";
        
        // Display options if there are more elements in the array
        if ($q + 1 < count($questions)) {
            echo "<ol type='number'>";
            // Display the next five options or until the end of the array
            for ($i = $q + 1; $i < min($q + 6, count($questions)); $i++) {
                echo "<li>" . $questions[$i] . "</li>";
            }
            echo "</ol>";
        }

        // Move the index to the next question
        $q += 5; // Skip the options we just displayed
    }
}


        elseif ($questiontype === 'AR Based') {
    // Iterate through the questions
    for ($q = 0; $q < count($questions); $q++) {
        // Display the current question
        echo "" . $questions[$q] . "";
        
        // Display options if there are more elements in the array
        if ($q + 1 < count($questions)) {
            echo "<ol type='number'>";
            // Display the next five options or until the end of the array
            for ($i = $q + 1; $i < min($q + 5, count($questions)); $i++) {
                echo "<li>" . $questions[$i] . "</li>";
            }
            echo "</ol>";
        }

        // Move the index to the next question
        $q += 4; // Skip the options we just displayed
    }
}

        else {
            // For other question types, display all questions in the array
            echo implode("<br>", $questions); // Display all questions in the array
        }

        echo "</div>";

        // Third column for the marks
        echo "<div class='col-md-1'>";
        echo "<span>" . $marks . "</span>";
        echo "</div>";

        echo "</div>"; // Close the row
    } else {
        echo "<p>No questions found.</p>";
    }
}
echo '</div>';
?>
				
</div>
				
				
				
				
							<div class="box-body">
							
								<button id="print-question-paper" class="btn btn-primary">Print Question Paper</button> <!-- Print button -->
							</div>
						</div>
						<!-- /.box -->
					</div>
					<!-- ./col -->
				</div>
				<!-- /.row -->
			</section>
			<!-- /.content -->
		</div>
	</div>
	<!-- /.content-wrapper -->

    <?php include "include/footer.php"; ?>
	
	<!-- Vendor JS -->
	<?php include "include/script.php"; ?>

	<!-- Button to Download PDF -->
	

<script>
// Print functionality for the question paper
document.getElementById('print-question-paper').addEventListener('click', function() {
    var printContents = document.getElementById('question-paper').innerHTML; // Get the content of the question paper
    var printWindow = window.open('', '_blank', 'width=800,height=600'); // Open a new window

    // Write the HTML structure including Bootstrap CSS and container
    printWindow.document.write('<html><head><title>Print Question Paper</title>');
    printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">'); // Include Bootstrap CSS
    
    // Adding custom styles for printing
    printWindow.document.write('<style>');
    printWindow.document.write('@media print {');
    printWindow.document.write('@page { size: A4; margin: 20mm; }'); // Set page size and margins
    printWindow.document.write('body { margin: 0; padding: 0; font-family: Arial, sans-serif; }'); // Remove default body margin
    printWindow.document.write('.container { width: 100%; margin: 0; padding: 0; }'); // Full width for container
    printWindow.document.write('.row { margin: 0; }'); // Remove row margins
    printWindow.document.write('.col { padding: 10px; }'); // Adjust column padding
    printWindow.document.write('h1, h2, h3, p { margin: 0; padding: 5px 0; }'); // Set margins for headings and paragraphs
    printWindow.document.write('}');
    printWindow.document.write('</style>');
    
    printWindow.document.write('</head><body>');
    
    // Wrap the print contents in a Bootstrap container
    printWindow.document.write('<div class="container">');
    printWindow.document.write(printContents); // Write the question paper content
    printWindow.document.write('</div>'); // Close the container

    printWindow.document.write('</body></html>');
    printWindow.document.close(); // Close the document to render it

    // Wait for the new window to load before printing
    printWindow.onload = function() {
        printWindow.print(); // Trigger the print dialog
        printWindow.close(); // Close the print window after printing
    };
});
</script>
</body>
</html>