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
    

    <title>Add Courses</title>
    
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
								<li class="breadcrumb-item active" aria-current="page">Add Courses</li>
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
					<h4 class="box-title">Add Courses</h4>  
				</div>
				<div class="box-body">
				    <form id="add-course" enctype="multipart/form-data">
					
					<div class="form-group row">
						<label class="col-form-label col-md-3">Enter Course Name</label>
						<div class="col-md-9">
							<input class="form-control" type="text" name="course" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-md-3">Add Course Image</label>
						<div class="col-md-9">
							<input class="form-control" type="file" name="courseimage" accept="image/png, image/jpeg,image/png" required>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-md-3">Existing Courses</label>
						<div class="col-md-9">
							<select class="form-control">
							    <?php 
								    while ($row = $result->fetch_assoc()){
								    echo '
								       <option>'.$row['courses'].'</option>
								     ';
								    }?>
							</select>
						</div>
					</div>
                    
				   <div class="form-group col-md-12">
						
						<div>
							<input class="form-control btn btn-primary" type="submit" value="Submit">
						
						</div>
					</div>
					</form>
					<form>
					 
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
    $('#add-course').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this); // Create a FormData object from the form

        $.ajax({
            url: 'ajax/add-course.php', // The PHP script to handle the insertion
            type: 'POST',
            data: formData, // Use the FormData object as the data to send
            contentType: false, // Prevent jQuery from overriding content type
            processData: false, // Prevent jQuery from processing the data
            success: function(response) {
                if (response.success) {
                    alert(response.message); // Show success message in alert
                } else {
                    alert('Error: ' + response.message); // Show error message in alert
                }
                $('#add-course')[0].reset(); // Reset the form
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
