<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>
<?php
include "ajax/db.php";

// Prepare the SQL statement to fetch all data
$sql = "SELECT * FROM addcourses"; // Fetching id along with questions
$result = $conn->query($sql);
$sql1 = "SELECT * FROM subject"; // Fetching id along with questions
$result1 = $conn->query($sql1);

// Check if the query was successful
if ($result === false) {
    die("Error fetching data: " . $conn->error);
}



// Fetch the data

// Close the connection
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
    

    <title>Add Other Details</title>
    
    <?php include"include/links.php";?>
     
  </head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
  <?php include"include/header.php";?>


<div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="d-flex align-items-center">
				<div class="me-auto">
					<!--<h4 class="page-title">Advanced Form Elements</h4>-->
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php">Home</a></li>
								<!--<li class="breadcrumb-item" aria-current="page">Forms</li>-->
								<li class="breadcrumb-item active" aria-current="page">Add Other Details</li>
							</ol>
						</nav>
					</div>
				</div>
				
			</div>
		</div>

		<!-- Main content -->
		<section class="content">

		  <div class="row">

			<div class="col-12">
			  <div class="box">
				  
				<div class="box-header">
					<h4 class="box-title">Enter the Questions Details</h4>  
				</div>
				<div class="box-body">
				    <form id="add-other-details" class="row">
					
					
					<div class="form-group col-md-6">
						<label class="col-form-label">Select the Class/Course</label>
					
							<select class="form-control" name="class" id="classDropdown"  required>
							    <option disabled selected>Please Select Class/Course</option>
							    <?php 
								    while ($row = $result->fetch_assoc()){
								    echo '
								       <option value="'.$row['courses'].'">'.$row['courses'].'</option>
								     ';
								    }?>
							</select>
						
					</div>
					
					<div class="form-group col-md-6">
						<label class="col-form-label">Enter the Subject</label>
						<div>
						<select class="form-control" name="subject" id="subject"  required>
							    <option disabled selected>Please Select Subject</option>
							    <?php 
								    while ($row1 = $result1->fetch_assoc()){
								    echo '
								       <option value="'.$row1['subject'].'">'.$row1['subject'].'</option>
								     ';
								    }?>
							</select>
						</div>
					</div>
					
					<div class="form-group col-md-6">
						<label class="col-form-label">Enter the Book</label>
						<div>
							<select class="form-control" name="book" required>
							    <option disabled selected>Please Select Book</option>
							</select>
						</div>
					</div>
				
					<div class="form-group col-md-6">
						<label class="col-form-label">Enter the Lesson</label>
						<div>
							<input class="form-control" type="text" name="lesson" required>
						</div>
					</div>
					<div class="form-group col-md-6">
						<label class="col-form-label">Enter the Topic</label>
						<div>
							<input class="form-control" type="text" name="topic" required>
						</div>
					</div>
                    
				   <div class="form-group col-md-12">
						
						<div>
							<input class="form-control btn btn-primary" type="submit" value="Submit">
						
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
	
  <?php include"include/footer.php";?>
	
	<!-- Page Content overlay -->
	
	
	<!-- Vendor JS -->
	<?php include"include/script.php";?>
	 
  <script>
$(document).ready(function() {
    $('#add-other-details').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Create a FormData object to hold the form data, including files
        var formData = new FormData(this);

        $.ajax({
            url: 'ajax/add-other-details.php', // The PHP script to handle the insertion
            type: 'POST',
            data: formData, // Use the FormData object
            contentType: false, // Prevent jQuery from overriding content type
            processData: false, // Prevent jQuery from processing data
            success: function(response) {
                if (response.success) {
                    alert(response.message); // Show success message in alert
                    // Reset only the 'topic' input field
                    $('input[name="topic"]').val(''); // Clear the 'topic' input field
                } else {
                    // Handle validation errors
                    var errorMessage = 'Please correct the following errors:\n';
                    for (var key in response.errors) {
                        if (response.errors.hasOwnProperty(key)) {
                            errorMessage += response.errors[key] + '\n'; // Append each error message
                        }
                    }
                    alert(errorMessage); // Show error messages in alert
                }
            },
            error: function() {
                alert('An error occurred while processing your request.');
            }
        });
    });
});
</script>
<script>
   $(document).ready(function() {
    

    $('select[name="subject"]').change(function() {
    var selectedSubject = $(this).val();
    var selectedClass = $('#classDropdown').val(); // Get the selected class value

    // Fetch books based on selected subject and class
    $.ajax({
        url: 'ajax/fetch_books.php',
        type: 'POST',
        data: { subject: selectedSubject, class: selectedClass }, // Send both subject and class
        success: function(data) {
            var response = JSON.parse(data);
            var bookSelect = $('select[name="book"]');
            bookSelect.empty().append('<option disabled selected>Please Select Book</option>');
            response.books.forEach(function(book) {
                bookSelect.append('<option value="' + book + '">' + book + '</option>');
            });
        }
    });
});

   
});
</script>
</body>

</html>
