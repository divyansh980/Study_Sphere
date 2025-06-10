<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>

<?php
include "ajax/db.php"; // Ensure this file contains the connection setup
$updateid = $_GET['updateid'];

// Prepare the SQL statement to fetch all data
$sql = "SELECT * FROM books WHERE id='$updateid'"; // Fetching id along with questions
$result = $conn->query($sql);
$sql1 = "SELECT * FROM addcourses"; // Fetching id along with questions
$result1 = $conn->query($sql1);
$sql2 = "SELECT * FROM subject"; // Fetching id along with questions
$result2 = $conn->query($sql2);
$row = $result->fetch_assoc();


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
    
    <title>Edit Fields</title>
    
    <?php include "include/links.php"; ?>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
    <?php include "include/header.php"; ?>

    <div class="content-wrapper">
        <div class="container-full">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Fields</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header">
					<h4 class="box-title">Update Details</h4>  
				</div>
				<div class="box-body">
				    <form id="edit-field-images" enctype="multipart/form-data" class="row">
					<div class="form-group col-md-12 d-none">
						<label class="col-form-label">Detail Id</label>
						<div>
							<input class="form-control" type="text" name="id" required value="<?php echo $row['id'];?>" readonly>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="col-form-label">Enter the Class</label>
						<div>
							<select class="form-control" name="class" id="classDropdown" required>
							    <option selected value="<?php echo $row['class'];?>"><?php echo $row['class'];?>(Selected)</option>
							    <?php 
								    while ($row1 = $result1->fetch_assoc()){
								    echo '
								       <option value="'.$row1['courses'].'">'.$row1['courses'].'</option>
								     ';
								    }?>
							</select>
						</div>
					</div>
					
					<div class="form-group col-md-3">
						<label class="col-form-label">Enter the Subject</label>
						<div>
							<select class="form-control" name="subject" required>
							    <option selected value="<?php echo $row['subject'];?>"><?php echo $row['subject'];?>(Selected)</option>
							    <?php 
								    while ($row2 = $result2->fetch_assoc()){
								    echo '
								       <option value="'.$row2['subject'].'">'.$row2['subject'].'</option>
								     ';
								    }?>
							</select>
						</div>
					</div>
					
					<div class="form-group col-md-3">
						<label class="col-form-label">Enter the Book</label>
						<div>
						  <input class="form-control" value="<?php echo $row['book'];?>" name="book" required>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="col-form-label">Image Of Book</label>
						<div>
							<input class="form-control" type="file" accept="image/jpeg,image/jpg,image/png" name="newbookimage">
							<input type="hidden" name="oldbookimage" value="<?php echo $row['bookimage'];?>">
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
	
    <?php include "include/footer.php"; ?>
	
    <!-- Vendor JS -->
    <?php include "include/script.php"; ?>
      
 <script>
$(document).ready(function() {
    $('#edit-field-images').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Create a FormData object to hold the form data, including files
        var formData = new FormData(this);

        $.ajax({
            url: 'ajax/edit-book.php', // The PHP script to handle the update
            type: 'POST',
            data: formData, // Use the FormData object
            contentType: false, // Prevent jQuery from overriding content type
            processData: false, // Prevent jQuery from processing data
            success: function(response) {
                if (response.success) {
                    alert(response.message); // Show success message in alert
                } else {
                    alert('Error: ' + response.message); // Show error message in alert
                }
                // $('#edit-other-details')[0].reset(); // Reset the form
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