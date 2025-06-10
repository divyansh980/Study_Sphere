<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
$userid= $_SESSION['user_id'];
include "ajax/db.php";

$sql = "SELECT * FROM contentimage WHERE user_id='$userid'"; // Fetching id along with questions
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Upload Images</title>
    
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
					<!--<h4 class="page-title">View Books</h4>-->
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php">Home</a></li>
								<!--<li class="breadcrumb-item" aria-current="page">Tables</li>-->
								<li class="breadcrumb-item active" aria-current="page">Images</li>
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
					
					<div class="box-body">
					    <form id="addfieldimages" class="row" enctype=multipart/form-data>
					        <input type="hidden" value="<?php echo $userid;?>" name="userid">
					        
					 <div class="form-group col-md-6">
						<label>Upload Images</label>
						<div>
							<input class="form-control" type="file" name="images" accept="image/png, image/jpeg,image/png" required="">
						</div>
					</div>
					<div class="form-group col-md-3 mt-3">
						
						<div>
							<input class="form-control btn btn-primary" name="submit" type="submit" value="Add Images">
						</div>
					</div>
					
					</form>
					   
						
						</div>
					
					</div>
				<div class="box">
					
					<div class="box-body">
					    <div id="response" style="height:300px;overflow:auto;scrollbar-color:#ffc0cc00 #ffc0cc00;">
					        <div class="row">
					           <?php 
								    while ($row = $result->fetch_assoc()){
								    echo '
								    <div class="col-md-2">
    					                <div>
    					                    <img src="../../ajax/images/'.$row['image'].'" width="200px">
    					                </div>
    					                <button class="btn btn-warning delete-btn mt-2" data-id='.$row['id'].'>Delete</button>
    					            </div>
								      
								     ';
								    }?>
					            
					        </div>
					        
					    </div>
					</div>
					
					</div>
				</div>
			</div>

			
			<!-- /.col -->
		  </div>
		  <!-- /.row -->
		</section>
		<!-- /.content -->
	  
	  </div>
  </div>
 <!-- /.content-wrapper -->
	
  <?php include"include/footer.php";?>
	<?php include"include/script.php";?>

<script>
$(document).ready(function() {
    $('#addfieldimages').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Create a FormData object to hold the form data, including files
        var formData = new FormData(this);

        $.ajax({
            url: 'ajax/add-images.php', // The PHP script to handle the insertion
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
                // $('#add-other-details')[0].reset(); // Reset the form
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
    $('#response').on('click', '.delete-btn', function() {
        // Get the ID from the clicked button's data attribute
        var id = $(this).data('id'); // Use $(this) to refer to the clicked button

        if (confirm('Are you sure you want to delete this image?')) {
            $.ajax({
                url: 'ajax/delete-images.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response.success) {
                        // Remove the parent div of the button (which contains the question and buttons)
                        $(this).parent().remove(); // Use $(this) to refer to the clicked button's parent
                        alert('Image deleted successfully.');
                        $('#response')[0].reset();
                    } else {
                        alert('Error deleting image: ' + response.message);
                    }
                }.bind(this), // Bind 'this' to maintain context in the success function
                error: function() {
                    alert('An error occurred while processing your request.');
                }
            });
        }
    });
});
</script>
</body>

</html>