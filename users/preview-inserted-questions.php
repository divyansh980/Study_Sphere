<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include "ajax/db.php"; // Ensure this file connects to the database

// Fetch GET data safely
$userId = $_SESSION['user_id'];
$qType = $_GET['questionType'];
$className = $_GET['class'];
$subjectName = $_GET['subject'];
$bookName = $_GET['book'];
$lessonName = $_GET['lesson'];

switch ($qType) {
    case 'MCQ':
        $tableName = 'mcq';
        break;
    case 'AR Based':
        $tableName = 'arbased';
        break;
    case 'Fillups':
        $tableName = 'fillups';
        break;
    case 'True/False':
        $tableName = 'truefalse';
        break;
    case 'Descriptive':
        $tableName = 'descriptive';
        break;
    case 'Dictionary':
        $tableName = 'dictionary';
        break;
    default:
        echo json_encode(['error' => 'Invalid question type']);
        exit;
}

// Prepare the SQL statement to fetch data based on the new fields
$query = "SELECT * FROM $tableName WHERE class = ? AND subject = ? AND book = ? AND lesson = ? AND user_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['error' => 'SQL prepare failed: ' . $conn->error]);
    exit;
}

// Bind parameters
$stmt->bind_param('sssss', $className, $subjectName, $bookName, $lessonName, $userId);

// Execute the statement
if (!$stmt->execute()) {
    echo json_encode(['error' => 'SQL execute failed: ' . $stmt->error]);
    exit;
}

$resultSet = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Question List</title>

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
                                <div class="box-header" id="box-header">
                                    <h4>Questions List</h4>
                                    <?php
                                    echo "<div>";
                                    $questionCounter = 0; // Initialize question counter

                                    while ($row12 = $resultSet->fetch_assoc()) {
                                        // Assuming the 'question', 'answer', and 'solution' columns contain JSON encoded arrays
                                        $questions = json_decode($row12['question'], true); // Decode the JSON string into an associative array
                                        $answers = json_decode($row12['answer'], true); // Decode the answer JSON string
                                        $solutions = json_decode($row12['solution'], true); // Decode the solution JSON string
                                        $marks = htmlspecialchars($row12['marks']); // Fetch marks
                                        $questiontype = htmlspecialchars($row12['question_type']); // Fetch question type

                                        if (is_array($questions) && count($questions) > 0) {
                                            $questionCounter++; // Increment the question counter for each question array

                                            // Display the question number
                                            echo "<div class='row' id='question-paper' style='margin-bottom: 10px;padding-left:20px;'>"; // Create a row for each question

                                            // First column for the question number
                                            echo "<div class='col-md-1 col-sm-1 col-1 text-center'>";
                                            echo "<strong>Q" . $questionCounter . ".</strong>";
                                            echo "</div>";

                                            // Second column for the question text
                                            echo "<div class='col-md-10 col-sm-10 col-10'>";
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
                                            } elseif ($questiontype === 'AR Based') {
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
                                            } else {
                                                // For other question types, display all questions in the array
                                                echo implode("<br>", $questions); // Display all questions in the array
                                            }

                                            echo "</div>";

                                            // Third column for the marks
                                            echo "<div class='col-md-1 col-sm-1 col-1'>";
                                            echo "<span>" . $marks . "</span>";
                                            echo "</div>";

                                            // Display the answers
                                            echo "<div class='col-md-10 col-sm-10 col-10'>";
                                            echo "<strong>Answers:</strong><br>";
                                            if (is_array($answers) && count($answers) > 0) {
                                                foreach ($answers as $ans) {
                                                    echo $ans . "<br>"; // Display each answer
                                                }
                                            } else {
                                                echo "No answers available.<br>";
                                            }

                                            // Display the solutions
                                            echo "<strong>Solutions:</strong><br>";
                                            if (is_array($solutions) && count($solutions) > 0) {
                                                foreach ($solutions as $sol) {
                                                    echo $sol . "<br>"; // Display each solution
                                                }
                                            } else {
                                                echo "No solutions available.<br>";
                                            }
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
                                    <button id="print-question-paper" class="btn btn-primary">Print</button> <!-- Print button -->
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
            document.getElementById('print-question-paper').addEventListener('click', function() {
                // Create a new window for printing
                var printWindow = window.open('', '', 'height=600,width=800');
                printWindow.document.write('<html><head><title>Print Preview</title>');
                
                // Create an array of CSS links to load
                var cssLinks = [
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
                    'https://easy-padhai.com/teachers/src/css/style.css',
                    'https://easy-padhai.com/teachers/src/css/skin_color.css'
                ];

                // Function to load CSS and print when all are loaded
                var loadCssAndPrint = function() {
                    var loadedCount = 0;

                    // Function to check if all CSS files are loaded
                    var checkAllLoaded = function() {
                        loadedCount++;
                        if (loadedCount === cssLinks.length) {
                            // All CSS files are loaded, now write the content and print
                            var printContent = document.getElementById('box-header').innerHTML;
                            printWindow.document.write('</head><body>');
                            printWindow.document.write(printContent); // Write the content to the new window
                            printWindow.document.write('</body></html>');
                            printWindow.document.close(); // Close the document

                            // Wait for a short time to ensure the content is rendered before printing
                            setTimeout(function() {
                                printWindow.print(); // Trigger the print dialog
                            }, 500); // Adjust the timeout as necessary
                        }
                    };

                    // Load each CSS file
                    cssLinks.forEach(function(link) {
                        var cssLink = printWindow.document.createElement('link');
                        cssLink.rel = 'stylesheet';
                        cssLink.href = link;
                        cssLink.onload = checkAllLoaded; // Call checkAllLoaded when the CSS is loaded
                        printWindow.document.head.appendChild(cssLink); // Append the link to the head
                    });
                };

                loadCssAndPrint(); // Start loading CSS
            });
        </script>

    </body>
</html>