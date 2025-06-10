<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include "ajax/db.php";

$query1 = "SELECT * FROM subject";
$result1 = $conn->query($query1);

$updateid = $_GET['updateid'];

// Prepare the SQL statement to fetch all data
$sql = "SELECT * FROM instruction WHERE id='$updateid'"; // Fetching id along with questions
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$instructionArray = json_decode($row['instruction'], true);
$instructionanotherArray = json_decode($row['instructionanother'], true);

// Check if the query was successful
if ($result === false) {
    die("Error fetching data: " . $conn->error);
}

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
    
    <title>Edit Instructions</title>
    
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
                                <h4 class="box-title">Edit Instructions</h4>  
                            </div>
                            <div class="box-body">
                                <form id="edit-questions" class="row">
                                    <div class="form-group col-md-12 d-none">
                                        <label class="col-form-label"> Question ID</label>
                                        <div>
                                            <input class="form-control" name="instructionid" readonly value="<?php echo $row['id'];?>" >
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <label class="col-form-label">Subject</label>
                                        <div>
                                            <select class="form-control" id="subject" name="subject" required>
                                                <option selected value="<?php echo $row['subject'];?>"><?php echo $row['subject'];?>(Selected)</option>  
                                                <?php
                                                if ($result1->num_rows > 0) {
                                                    while ($row1 = $result1->fetch_assoc()) {
                                                        echo "<option value='" . htmlspecialchars($row1['subject']) . "'>" . htmlspecialchars($row1['subject']) . "</option>";
                                                    }
                                                } else {
                                                    echo "<option disabled>No subject available</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div id="questionsContainer">
                                    <?php
                                    // Populate existing instructions
                                    if (!empty($instructionArray) || !empty($instructionanotherArray)) {
                                        $maxCount = max(count($instructionArray), count($instructionanotherArray));
                                        for ($i = 0; $i < $maxCount; $i++) {
                                            echo '<div class="question-block row">
                                                    <div class="form-group col-md-6">
                                                        <label class="col-form-label">Enter Your Instruction</label>
                                                        <div>
                                                            <input class="form-control" name="instruction[]" value="' . htmlspecialchars($instructionArray[$i] ?? '') . '">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="col-form-label">Enter Instruction In Another Language</label>
                                                        <div>
                                                            <input class="form-control" name="instructionanother[]" value="' . htmlspecialchars($instructionanotherArray[$i] ?? '') . '">
                                                        </div>
                                                    </div>';
                                            // Only show the remove button if it's not the first instruction
                                            if ($i > 0) {
                                                echo '<button type="button" class="remove-btn btn btn-danger mb-2">Remove</button>';
                                            }
                                            echo '</div>'; // Close the question-block div
                                        }
                                    }
                                    ?>
                                </div>

                                <button type="button" id="addmore" class="mb-2 btn btn-success">Add Instructions</button>

                                    
                                    <div class="form-group col-md-6">
                                        <div>
                                            <input class="form-control btn btn-primary" type="submit" value="Edit">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div>
                                            <button id="cancelButton" class="form-control btn btn-primary">Cancel</button>
                                        </div>
                                    </div>
                                </form>
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
    

   <script>
    document.getElementById('cancelButton').onclick = function() {
        window.history.back(); // Redirects to the previous page
    };
    
    $(document).ready(function() {
        // Initialize question counts
        let questionCount = $('#questionsContainer .question-block').length;

        // Functionality for adding new instruction blocks
        $('#addmore').click(function() {
            questionCount++; // Increment question count
            const questionBlock = `
                <div class="question-block row">
                    <div class="form-group col-md-6">
                        <label class="col-form-label">Enter Your Instruction</label>
                        <div>
                            <input class="form-control" name="instruction[]">
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="col-form-label">Enter Instruction In Another Language</label>
                        <div>
                            <input class="form-control" name="instructionanother[]">
                        </div>
                    </div>
                    <button type="button" class="remove-btn btn btn-danger mb-2">Remove</button>
                </div>`;
            $('#questionsContainer').append(questionBlock);
        });

        // Event delegation for dynamically added remove buttons
        $('#questionsContainer').on('click', '.remove-btn', function() {
            $(this).closest('.question-block').remove();
            questionCount--; // Decrease the count when removed
        });
    });

    $('#edit-questions').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission
        $.ajax({
            url: 'ajax/edit-instructions.php', // The PHP script to handle the insertion
            type: 'POST',
            data: $(this).serialize(), // Serialize the form data
            dataType: 'json', // Expect a JSON response
            success: function(response) {
                if (response.success) {
                    alert(response.message); // Show success message in alert
                } else {
                    alert('Error: ' + response.message); // Show error message in alert
                }
                // Optionally reset the form or navigate to another page
                window.location.href = 'view-instructions.php';
            },
            error: function(xhr, status, error) {
                alert('An error occurred while processing your request: ' + error);
            }
        });
    });
</script>
</body>
</html>