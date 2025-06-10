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
    

    <title>Content</title>
    
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
								<li class="breadcrumb-item active" aria-current="page">Content</li>
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
					    <form id="myForm" class="row">
					  <div class="form-group col-md-3">
						<label class="col-form-label">Select the Class/Course</label>
					
							<select class="form-control" name="class" id="classDropdown" required>
							    <option disabled selected>Please Select Class/Course</option>
							    <?php 
								    while ($row = $result->fetch_assoc()){
								    echo '
								       <option value="'.$row['courses'].'">'.$row['courses'].'</option>
								     ';
								    }?>
							</select>
						
					</div>
					
					<div class="form-group col-md-3">
						<label class="col-form-label">Enter the Subject</label>
						<div>
							<select class="form-control" name="subject" required>
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
					 <div class="form-group col-md-3 mt-2">
						<label></label>
						<div>
							<input class="form-control btn btn-primary" name="submit" type="submit" value="View Books">
						</div>
					</div>
					
					
					</form>
					   
						
						</div>
					
					</div>
				<div class="box">
					
					<div class="box-body">
					    <div id="response" style="height:300px;overflow:auto;scrollbar-color:#ffc0cc00 #ffc0cc00;"></div>
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
    $('#myForm').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        // Get selected values
        var selectedClass = $('#classDropdown').val();
        var selectedSubject = $('select[name="subject"]').val();

        // Fetch books based on selected class and subject
        $.ajax({
            url: 'ajax/fetch_books.php', // Replace with your server endpoint to fetch books
            type: 'POST',
            data: { class: selectedClass, subject: selectedSubject },
            dataType: 'json',
            success: function(data) {
                // Check if the response contains books
                if (data.books && data.books.length > 0) {
                    let booksHtml = '<div class="book-list">'; // Start a div for the book list
                    data.books.forEach(function(book, index) {
                        // Create a link for each book with a serial number and include class and subject in the URL
                        booksHtml += '<a href="book_detail.php?book=' + encodeURIComponent(book) + '&class=' + encodeURIComponent(selectedClass) + '&subject=' + encodeURIComponent(selectedSubject) + '" class="book-link">' + (index + 1) + '. ' + book + '</a><br>'; // Serial number starts from 1
                    });
                    booksHtml += '</div>'; // Close the book list div
                    $('#response').html(booksHtml); // Display books in the response div
                } else {
                    $('#response').text('No books available for the selected class and subject.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error:', textStatus, errorThrown);
                $('#response').text('An error occurred: ' + errorThrown);
            }
        });
    });
});
</script>

 
</body>

</html>