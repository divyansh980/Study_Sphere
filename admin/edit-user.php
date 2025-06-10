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

$updateid = $_GET['updateid'];

// Prepare the SQL statement to fetch all data
$sql2 = "SELECT * FROM user WHERE id='$updateid'"; // Fetching id along with questions
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();

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
    

    <title>Edit User</title>
    
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
								<li class="breadcrumb-item active" aria-current="page">Edit User</li>
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
					<h4 class="box-title">Edit User</h4>  
				</div>
				<div class="box-body">
				    <form id="edit-user" class="row">
				    <div class="form-group d-none">
        <div class="input-group mb-3">
            <span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
            <input type="text" name="id" class="form-control ps-15 bg-transparent" placeholder="Id" value="<?php echo $row2['id'];?>"readonly>
        </div>
    </div>
				    <div class="form-group col-md-4">
						<label class="col-form-label">Select the Class/Course</label>
					
							<select class="form-control" name="class" id="classDropdown" required>
							    <option value="<?php echo $row2['class'];?>" selected><?php echo $row2['class'];?>(Selected)</option>
							    <?php 
								    while ($row = $result->fetch_assoc()){
								    echo '
								       <option value="'.$row['courses'].'">'.$row['courses'].'</option>
								     ';
								    }?>
							</select>
						
					</div>
					
					<div class="form-group col-md-4">
						<label class="col-form-label">Enter the Subject</label>
						<div>
							<select class="form-control" name="subject" required>
							    <option value="<?php echo $row2['subject'];?>" selected><?php echo $row2['subject'];?>(Selected)</option>
							    <?php 
								    while ($row1 = $result1->fetch_assoc()){
								    echo '
								       <option value="'.$row1['subject'].'">'.$row1['subject'].'</option>
								     ';
								    }?>
							</select>
						</div>
					</div>   
					<div class="form-group col-md-4">
						<label class="col-form-label">Enter the Book</label>
						<div>
							<select class="form-control" name="book" required>
							    <option selected value="<?php echo $row2['book'];?>"><?php echo $row2['book'];?>(Selected)</option>
							    <option disabled>Please Select Book</option>
							</select>
						</div>
					</div>
    <div class="form-group col-md-4 d-none">
        <label class="col-form-label">User Type</label>
        <input type="text" name="user-type" class="form-control"  value="<?php echo $row2['user_type'];?>" required>
    </div>
    <div class="form-group col-md-6">
        <div class="input-group mb-3">
            <span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
            <input type="text" name="full_name" class="form-control" placeholder="Full Name" value="<?php echo $row2['full_name'];?>" required>
        </div>
    </div>
    <div class="form-group col-md-6">
        <div class="input-group mb-3">
            <span class="input-group-text bg-transparent"><i class="ti-email"></i></span>
            <input type="email" name="email" class="form-control ps-15 bg-transparent" placeholder="Email" value="<?php echo $row2['email'];?>" required>
        </div>
    </div>
    <div class="form-group col-md-6">
        <div class="input-group mb-3">
            <span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
            <input type="password" name="password" class="form-control ps-15 bg-transparent" placeholder="Password" value="<?php echo $row2['password'];?>" required>
        </div>
    </div>
    <div class="form-group col-md-6">
        <div class="input-group mb-3">
            <span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
            <input type="password" name="confirm_password" class="form-control ps-15 bg-transparent" placeholder="Retype Password" value="<?php echo $row2['password'];?>" required>
        </div>
    </div>
    <div class="form-group col-md-6">
        <div class="input-group mb-3">
            <span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
            <input type="text" name="user_id" class="form-control ps-15 bg-transparent" placeholder="Please Enter User ID" value="<?php echo $row2['userid'];?>" required>
        </div>
    </div>
    
    
        
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-info margin-top-10">Edit</button>
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
 <script>
    $(document).ready(function() {
        $('#edit-user').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            $.ajax({
                url: 'ajax/edit-user.php', // The PHP script to handle the insertion
                type: 'POST',
                data: $(this).serialize(), // Serialize the form data
                success: function(response) {
                    if (response.success) {
                        alert(response.message); // Show success message in alert
                    } else {
                        alert('Error: ' + response.message); // Show error message in alert
                    }
                    $('#edit-user')[0].reset(); // Reset the form
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
