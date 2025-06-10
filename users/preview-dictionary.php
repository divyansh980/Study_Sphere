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

// Prepare the SQL statement to fetch data based on the new fields
$query = "SELECT * FROM dictionary WHERE class = ? AND subject = ? AND book = ? AND lesson = ? AND user_id = ?";
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

    <title>Dictionary Preview</title>

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
                                    <h4>Dictionary</h4>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-6">
                                        <h3>Word</h3>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-6">
                                        <h3 class="text-end">Meaning</h3>
                                    </div>
                                    </div>
                                    <?php
                                    echo "<div>";
                                    $dictionaryCounter = 0; // Initialize dictionary counter
                                    
                                    while ($row12 = $resultSet->fetch_assoc()) {
                                        // Assuming the 'word' and 'description' columns contain the word and its meaning
                                        $word = htmlspecialchars($row12['word']); // Fetch the word
                                        $meaning = json_decode($row12['description']); // Fetch the meaning
                                    
                                        if ($word && $meaning) {
                                            $dictionaryCounter++; // Increment the dictionary counter for each entry
                                    
                                            // Display the word and meaning in a two-column layout
                                            echo "<div class='row' id='dictionary-entry' style='margin-bottom: 10px;padding-left:20px;'>";
                                            
                                            // First column for the word
                                            echo "<div class='col-md-6 col-sm-6 col-6'>";
                                            echo "<strong>" . $dictionaryCounter . ".</strong> " . $word;
                                            echo "</div>";
                                    
                                            // Second column for the meaning
                                            echo "<div class='col-md-6 col-sm-6 col-6' style='text-align: right;'>";
                                            echo "<strong></strong> " . $meaning;
                                            echo "</div>";
                                    
                                            echo "</div>"; // Close the row
                                        }
                                    }
                                    
                                    // Display the count of dictionary entries
                                    echo "<div class='col-md-12'>";
                                    echo "<strong>Total Dictionary Entries: </strong>" . $dictionaryCounter;
                                    echo "</div>";
                                    
                                    echo '</div>'; // Close the main div
                                    ?>
                                </div>

                                <div class="box-body">
                                    <button id="print-question-paper" class="btn btn-primary">Print Dictionary</button> <!-- Print button -->
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