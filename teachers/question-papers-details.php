<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}
include "ajax/db.php";

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
                                <h4 class="box-title">Question Paper Details</h4>  
                            </div>
                            <div class="box-body">
                                <form id="insert-details" class="row" enctype="multipart/form-data">
                                    <input type="hidden" value="<?php echo $_SESSION['email']?>" name="userid">
                                    
                                    <div class="form-group col-md-4">
                                        <label class="col-form-label">Question Paper Title</label>
                                        <div>
                                            <input class="form-control" name="papertitle" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-form-label">Name of Institution</label>
                                        <div>
                                            <input class="form-control" name="institutionname">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-form-label">Address of Institution</label>
                                        <div>
                                            <input class="form-control" name="institutionaddress">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-form-label">Maximum Marks</label>
                                        <div>
                                            <input class="form-control" name="maxmarks" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-form-label">Time</label>
                                        <div>
                                            <input class="form-control" name="time" required>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label class="col-form-label">Logo If Any</label>
                                        <div>
                                            <input class="form-control" type="file" name="logo" accept="image/png, image/jpeg,image/png">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <?php
                                        $instrcutionquery1 = "SELECT * FROM instruction WHERE subject='$subject'";
                                        $instrcutionresult1 = $conn->query($instrcutionquery1);
                                        
                                        if ($instrcutionresult1->num_rows > 0) {
                                            // Output data of each row as checkboxes
                                            echo '<h4>Select Instructions</h4>';
                                            while ($row = $instrcutionresult1->fetch_assoc()) {
                                                $instructionId = $row['id']; // Assuming there's an 'id' column
                                                $instructionJson = $row['instruction']; 
                                                $instructionanotherJson = $row['instructionanother']; // Assuming there's an 'instructionanother' column containing JSON
                                                
                                                // Decode the JSON into associative arrays
                                                $instructionsArray = json_decode($instructionJson, true); 
                                                $instructionanotherArray = json_decode($instructionanotherJson, true);
                                                
                                                // Check if decoding was successful and the result is an array for the first set of instructions
                                                // Inside the loop for instructions
if (is_array($instructionsArray)) {
    foreach ($instructionsArray as $index => $instructionText) {
        $instructionText = htmlspecialchars($instructionText);
        $checkboxId = 'instruction_' . $instructionId . '_' . $index;
        echo '<div>';
        echo '<input type="checkbox" name="instructions[]" value="' . $instructionText . '" id="' . $checkboxId . '" class="instruction-checkbox" data-another-id="instruction_another_' . $instructionId . '_' . $index . '">';
        echo '<label for="' . $checkboxId . '">' . $instructionText . '</label>';
        echo '</div>';
    }
}

// Inside the loop for another instructions
if (is_array($instructionanotherArray)) {
    foreach ($instructionanotherArray as $index => $instructionAnotherText) {
        $instructionAnotherText = htmlspecialchars($instructionAnotherText);
        $checkboxIdAnother = 'instruction_another_' . $instructionId . '_' . $index;
        echo '<div>';
        echo '<input type="checkbox" name="instructionsanother[]" value="' . $instructionAnotherText . '" id="' . $checkboxIdAnother . '" class="another-instruction-checkbox" data-instruction-id="instruction_' . $instructionId . '_' . $index . '">';
        echo '<label for="' . $checkboxIdAnother . '">' . $instructionAnotherText . '</label>';
        echo '</div>';
    }
}
                                            }
                                        } else {
                                            echo "No instructions found for the subject.";
                                        }
                                        
                                        // Close the database connection if needed
                                        $conn->close();
                                        ?>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <div>
                                            <input class="form-control btn btn-primary" type="submit" value="Add Details">
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
    <?php include "include/script.php"; ?>
    
    <script>
        document.getElementById('cancelButton').onclick = function() {
            window.history.back(); // Redirects to the previous page
        };

       $(document).ready(function() {
    // Handle instruction checkbox click
    $('.instruction-checkbox').on('change', function() {
        var anotherCheckboxId = $(this).data('another-id');
        $('#' + anotherCheckboxId).prop('checked', this.checked);
    });

    // Handle another instruction checkbox click
    $('.another-instruction-checkbox').on('change', function() {
        var instructionCheckboxId = $(this).data('instruction-id');
        $('#' + instructionCheckboxId).prop('checked', this.checked);
    });

    $('#insert-details').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Create a FormData object to hold the form data, including files
        var formData = new FormData(this);

        $.ajax({
            url: 'ajax/add-paper-detail.php', // The PHP script to handle the insertion
            type: 'POST',
            data: formData, // Use the FormData object
            contentType: false, // Prevent jQuery from overriding content type
            processData: false, // Prevent jQuery from processing data
            success: function(response) {
                // Check if the response is a valid JSON object
                try {
                    if (typeof response === 'string') {
                        response = JSON.parse(response); // Parse the JSON string if necessary
                    }

                    if (response.success) {
                        var papertitle = $('input[name="papertitle"]').val();
                        alert(response.message); // Show success message in alert
                        $('#insert-details')[0].reset(); // Reset the form

                        // Redirect to the next page with papertitle as a query parameter
                        window.location.href = 'add-questionpaper.php?papertitle=' + encodeURIComponent(papertitle);
                    } else {
                        alert('Error: ' + response.message); // Show error message in alert
                    }
                } catch (e) {
                    alert('Error parsing response: ' + e.message); // Handle JSON parsing errors
                }
            },
            error: function() {
                alert('An error occurred while processing your request.');
            }
        });
    });
});
    </script>

</body>

</html>