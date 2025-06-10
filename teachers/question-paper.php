<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

// Get the current year and month
$currentYear = date("Y");
$currentMonth = date("n"); // "n" returns the month as a number without leading zeros (1 to 12)

// Determine the academic year based on the current month
if ($currentMonth >= 4) {
    // From April (4) to December (12), the academic year is current year - upcoming year
    $academicYear = $currentYear . '-' . ($currentYear + 1);
} else {
    // From January (1) to March (3), the academic year is previous year - current year
    $academicYear = $currentYear - 1 . '-' . $currentYear;
}

// Set the session variable for the question paper year
$_SESSION['question_paper_year'] = $academicYear;

// Display the session year for the question paper
// echo "The session year for the question paper is: " . $_SESSION['question_paper_year'];

include "ajax/db.php";

// Fetch POST data safely

$class12 = $_GET['class'] ?? ''; // Use $_POST instead of $POST
$subject12 = $_GET['subject'] ?? '';
$book12 = $_GET['book'] ?? '';
$papertitle12 = $_GET['papertitle'] ?? '';

// Prepare the SQL statement to fetch data based on the new fields
$query12 = "SELECT * FROM questionpaper WHERE class='$class12' AND subject='$subject12' AND book='$book12' AND papertitle='$papertitle12'";
$result12 = $conn->query($query12);
$query14 = "SELECT * FROM paperdetails 
WHERE papertitle = '$papertitle12' 
ORDER BY id DESC 
LIMIT 1;";
$result14 = $conn->query($query14);
$row14 = $result14->fetch_assoc();
$institutionname = $row14['institutionname'];
$institutionaddress = $row14['institutionaddress'];
$logo = $row14['logo'];
$time = $row14['time'];
$maxmarks = $row14['maxmarks'];
$instructionsJson = $row14['instructions'];
$instructionsanotherJson = $row14['instructionsanother'];

// Decode instructions JSON
$instructionsArray = json_decode($instructionsJson, true);
$instructionanotherArray = json_decode($instructionsanotherJson, true);
// Check if the query was successful
if ($result12 === false) {
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
                                <div class="box-header" id="box-header">
                                    <div class="row mt-5" style="padding-top:20px;">
                                    <div class="col-md-12 col-sm-12 col-12 text-center" style="margin-bottom:15px;">
                                        <?php if (!empty($logo)): ?>
                                            <img src="ajax/logo/<?php echo $logo; ?>" width="200px">
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if (empty($logo)): ?>
                                        <div class="col-md-12 col-sm-12 col-12"><h2 class="text-center"><?php echo $institutionname; ?></h2></div>
                                        <div class="col-md-12 col-sm-12 col-12"><h4 class="text-center"><?php echo $institutionaddress; ?></h4></div>
                                    <?php endif; ?>
                                    <div class="col-md-4 col-sm-4 col-4"></div>
                                    <div class="col-md-4 col-sm-4 col-4"><h4 class="text-center">Class:<?php echo $class12; ?>-<?php echo $subject12; ?></h4></div>
                                    <div class="col-md-4 col-sm-4 col-4"><h4 class="text-center">Roll No. <u>&nbsp; &nbsp; &nbsp; &nbsp;</u></h4></div>
                                    <div class="col-md-4 col-sm-4 col-4"><h4 class="text-center">Time:<?php echo $time; ?>hrs </h4></div>
                                    <div class="col-md-4 col-sm-4 col-4"><h4 class="text-center"><?php echo $papertitle12; ?>(<?php echo $academicYear; ?>)</h4></div>
                                    <div class="col-md-4 col-sm-4 col-4"><h4 class="text-center">Max. Marks.:<?php echo $maxmarks; ?></h4></div>
                                    <div class="col-md-12 col-sm-12 col-12 mb-4">
                                          <div class="p-2">
                                            <?php
                                                // Initialize counters for serial numbers
                                                $serialNoMain = 1; // Counter for main instructions
                                                $serialNoAdditional = 1; // Counter for additional instructions
                                        
                                                // Check if the first array is an array and has elements
                                                if (is_array($instructionsArray) && count($instructionsArray) > 0) {
                                                    echo '<h4 class="m-3">Instructions</h4>';
                                                    foreach ($instructionsArray as $instructionText) {
                                                        $instructionText = htmlspecialchars($instructionText); // Sanitize the instruction text
                                                        echo $serialNoMain . '. ' . $instructionText . '<br>'; // Output with serial number
                                                        $serialNoMain++; // Increment the serial number for main instructions
                                                    }
                                                }
                                        
                                                // Check if the second array is an array and has elements
                                                if (is_array($instructionanotherArray) && count($instructionanotherArray) > 0) {
                                                    echo '<h4 class="m-3">निर्देश</h4>';
                                                    foreach ($instructionanotherArray as $instructionAnotherText) {
                                                        $instructionAnotherText = htmlspecialchars($instructionAnotherText); // Sanitize the instruction text
                                                        echo $serialNoAdditional . '. ' . $instructionAnotherText . '<br>'; // Output with serial number
                                                        $serialNoAdditional++; // Increment the serial number for additional instructions
                                                    }
                                                }
                                            ?>
                                            </div>
                                        </div>
                                    
                                    </div>

                                    <?php
                                    echo "<div>";
                                    $questionCounter = 0; // Initialize question counter

                                    while ($row12 = $result12->fetch_assoc()) {
                                        // Assuming the 'question' column contains a JSON encoded array
                                        $questions = json_decode($row12['question'], true); // Decode the JSON string into an associative array
                                        $marks = htmlspecialchars($row12['marks']); // Fetch marks
                                        $questiontype = htmlspecialchars($row12['question_type']);

                                        if (is_array($questions) && count($questions) > 0) {
                                            $questionCounter++; // Increment the question counter for each question array

                                            // Display the question number
                                            echo "<div class='row'  id='question-paper' style='margin-bottom: 10px;padding-left:20px;'>"; // Create a row for each question

                                            // First column for the question number
                                            echo "<div class='col-md-1 col-sm-1 col-1 text-center'>";
                                            echo "Q" . $questionCounter . ".";
                                            echo "</div>";

                                            // Second column for the question text
                                            echo "<div class='col-md-10 col-sm-10 col-10'>";

                                            if ($questiontype === 'MCQ') {
    // Iterate through the questions
    for ($q = 0; $q < count($questions); $q++) {
        // Display the current question
        echo "<span>" . $questions[$q] . "</span>";

        // Display options if there are more elements in the array
        if ($q + 1 < count($questions)) {
            echo "<div style='display: flex; flex-wrap: wrap;margin-bottom:10px;'>";
            // Display the next four options or until the end of the array
            for ($i = $q + 1; $i < min($q + 5, count($questions)); $i++) {
                // Calculate option number (1-based index)
                $optionNumber = $i - $q; // This will give 1, 2, 3, 4 for the options of the current question
                // Display options in a two-column format with option number
                echo "<div style='width: 50%;'>" . $optionNumber . ". " . $questions[$i] . "</div>";
            }
            echo "</div>";
        }

        // Move the index to the next question
        $q += 5; // Skip the options we just displayed
    }
}elseif ($questiontype === 'AR Based') {
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

                                            echo "</div>"; // Close the row
                                        } else {
                                            echo "<p>No questions found.</p>";
                                        }
                                    }
                                    echo '</div>';
                                    ?>

                                </div>




                                <div class="box-body">

                                    <button id="print-question-paper" class="btn btn-primary">Print Question
                                        Paper</button> <!-- Print button -->
                                    <a type="button" class="btn btn-primary" href="add-questionpaper.php?papertitle=<?php echo $papertitle12; ?>">Add More</a>
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