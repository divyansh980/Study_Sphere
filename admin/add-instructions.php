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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Add Instructions</title>
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
                                    <h4 class="box-title">Enter Instructions</h4>
                                </div>
                                <div class="box-body">
                                    <form id="insert-questions" class="row">
                                        <div class="form-group col-md-12">
                                            <label class="col-form-label">Subject</label>
                                            <div>
                                                <select class="form-control" id="subject" name="subject" required>
                                                    <option disabled selected>Please Select Subject</option>
                                                    <?php if ($result1->num_rows > 0) {
                                                        while ($row1 = $result1->fetch_assoc()) {
                                                            echo "<option value='" . htmlspecialchars($row1['subject']) . "'>" . htmlspecialchars($row1['subject']) . "</option>";
                                                        }
                                                    } else {
                                                        echo "<option disabled>No Subject available</option>";
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="questionsContainer">
                                            <div class="question-block row">
                                                <div class="form-group col-md-6">
                                                    <label class="col-form-label">Enter Instruction In English</label>
                                                    <div>
                                                        <input class="form-control instruction" type="text" name="instruction[]" required>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label class="col-form-label">Enter Instruction In Another Language</label>
                                                    <div>
                                                        <input class="form-control instruction" type="text" name="instructionanother[]" required>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <button type="button" id="addmore" class="mb-2 btn btn-success">Add Another Instructions</button>
                                        
                                        <div class="form-group col-md-6">
                                            <div>
                                                <input class="form-control btn btn-primary" type="submit" value="Submit">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div>
                                                <button id="cancelButton" type="button" class="form-control btn btn-primary">Cancel</button>
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
    let questionCount = 0;

    // Functionality for adding more questions
    $('#addmore').click(function() {
        questionCount++; // Increment question count
        const questionBlock = `
            <div class="question-block row">
                <div class="form-group col-md-6">
                    <label class="col-form-label">Enter Instruction In English</label>
                    <div>
                        <input class="form-control instruction" type="text" name="instruction[]" required>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label class="col-form-label">Enter Instruction In Another Language</label>
                    <div>
                        <input class="form-control instruction" type="text" name="instructionanother[]" required>
                    </div>
                </div>
                <button type="button" class="remove-btn btn btn-danger mb-2">Remove</button>
            </div>`;
        $('#questionsContainer').append(questionBlock);
    });

    // Event delegation for dynamically added remove buttons
    $('#questionsContainer').on('click', '.remove-btn', function() {
        $(this).closest('.question-block').remove(); // Remove the entire question block
        questionCount--; // Decrease the count when removed
    });
    $('#insert-questions').on('submit', function(event) {
                    event.preventDefault(); // Prevent the default form submission
                    
                    // Serialize the form data
                    var formData = $(this).serialize();

                    // Send the AJAX request to add-instructions.php
                    $.ajax({
                        url: 'ajax/add-instructions.php', // The URL to which the request is sent
                        type: 'POST', // The HTTP method to use
                        data: formData, // The data to send
                        success: function(response) {
                            // Handle success
                            alert('Instructions added successfully!');
                            console.log(response); // Log the response for debugging
                            $('#insert-questions')[0].reset(); // Reset the form
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                            alert('An error occurred while adding the instructions: ' + error);
                            console.error(xhr.responseText); // Log the error for debugging
                        }
                    });
                });
});
       
    
</script>
</body>

</html>