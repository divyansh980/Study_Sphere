<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include "ajax/db.php"; // Include your database connection file

// Fetch GET data safely
$questionid = $_GET['questionid'] ?? ''; 
$questiontype = $_GET['questiontype'] ?? '';

// Determine the table name based on question type
switch ($questiontype) {
    case 'MCQ':
        $tableName = 'mcq';
        $addmore = 'add-mcq.php';
        break;
    case 'AR Based':
        $tableName = 'arbased';
        $addmore = 'add-arquestions.php';
        break;
    case 'Fillups':
        $tableName = 'fillups';
        $addmore = 'add-fillups.php';
        break;
    case 'True/False':
        $tableName = 'truefalse';
        $addmore = 'add-truefalsequestions.php';
        break;
    case 'Descriptive':
        $tableName = 'descriptive';
        $addmore = 'add-descriptivequestions.php';
        break;
    default:
        echo json_encode(['error' => 'Invalid question type']);
        exit;
}

// Prepare the SQL statement
$query = "SELECT * FROM $tableName WHERE id='$questionid'"; // Corrected variable name
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
    
    <title>Question Preview</title>
    
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
    <h4 class="box-title mb-5">Question Preview</h4>  
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
							
								<a  type="button" class="btn btn-primary" href="<?php echo $addmore;?>">Add Questions</a> 
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
	


</body>
</html>