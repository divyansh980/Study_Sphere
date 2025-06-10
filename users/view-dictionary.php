<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
$userid= $_SESSION['user_id'];
?>
<?php
include "ajax/db.php";
// Prepare the SQL statement to fetch data based on the new fields
$sql3 = "SELECT * FROM dictionary WHERE user_id = '$userid' ORDER BY lesson ASC, word ASC ";
$result12 = $conn->query($sql3);

// Check if the query was successful
if ($result12 === false) {
    die("Error fetching data: " . $conn->error);
}

// Close the connection

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>Dictionary</title>
    
    <?php include"include/links.php";?>
     <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
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
								<li class="breadcrumb-item active" aria-current="page">Dictionary</li>
							</ol>
						</nav>
					</div>
				</div>
				
			</div>
		</div>

		<!-- Main content -->
		<section class="content">
		  <div class="row">
			  <div class="box">
					
					<div class="box-body">
					    <form id="myForm" class="row">
					        <input type="hidden" value="<?php echo $userid;?>" name="userid">
					        <input type="hidden" value="Dictionary" name="questiontype" id="questiontype">
					<?php include 'ajax/userinfo.php';?>
					<div class="form-group col-md-3 mt-2">
						<label></label>
						<div>
							<a id="viewButton" class="btn btn-primary" href="#">Preview Dictionary</a>
						</div>
					</div>
					<div class="form-group col-md-3 mt-2">
						<label></label>
						<div>
							 <a href="add-dictionary.php" class="btn btn-primary" type="button">Add Dictionary</a>
						</div>
					</div>
					
					</form>
					   
						
						</div>
					
					</div>
			<div class="col-12">
			    
				<div class="box">
					
					<div class="box-body">
					    
						<div class="table-responsive">
						   	<table id="example" class="display table table-striped table-bordered display" style="width:100%">
								<thead>
									
									<tr>
										<th>Lesson</th>
										<th>Word</th>
										<th>Description</th>
										<th>Edit</th>
										<th>Delete</th>
									</tr>
								</thead>
								<tbody>
								    <?php
								    while ($row111 = $result12->fetch_assoc()) {
                                 echo '
                                 <tr data-id="'.$row111['id'].'">
                                     <td>'.$row111['lesson'].'</td>
                                     <td>'.$row111['word'].'</td>
                                     <td>'.json_decode($row111['description']).'</td>
                                     <td class="text-center"><a type="button" href="edit-dictionary.php?updateid='.$row111['id'].'" class="btn btn-primary">Edit</a></td>
                                     <td class="text-center"><button class="btn btn-warning delete-btn">Delete</a></td>
                                 </tr>';
    
}
								    ?>
								
								
                        
                
								</tbody>
								<tfoot>
									<tr>
										<th>Lesson</th>
										<th>Word</th>
										<th>Description</th>
										<th>Edit</th>
										<th>Delete</th>
									</tr>
								</tfoot>
							</table>
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
	<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
	  <script>
      // Initialize DataTable
      new DataTable('#example', {
          order: [[0, 'desc']]
      });

      
  </script>
  <script>
$(document).ready(function() {
    $('#example tbody').on('click', '.delete-btn', function() {
        var row = $(this).closest('tr');
        var id = row.data('id');

        if (confirm('Are you sure you want to delete this Dictionary?')) {
            $.ajax({
                url: 'ajax/delete-dictionary.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response.success) {
                        row.remove(); // Remove the row from the table
                        alert('Row deleted successfully.');
                    } else {
                        alert('Error deleting row: ' + response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while processing your request.');
                }
            });
        }
    });
});
</script>
<script>
    document.getElementById('lessonSelect').addEventListener('change', updateHref);
    document.getElementById('questiontype').addEventListener('input', updateHref);

    function updateHref() {
        var selectedLesson = document.getElementById('lessonSelect').value; // Get the selected lesson value
        var selectedQuestionType = document.getElementById('questiontype').value; // Get the question type from the hidden input
        var book = '<?php echo $row['book']; ?>'; // Get PHP variables
        var className = '<?php echo $row['class']; ?>';
        var subject = '<?php echo $row['subject']; ?>';

        // Update the href of the anchor tag
        document.getElementById('viewButton').href = 'preview-dictionary.php?book=' + book + '&class=' + className + '&subject=' + subject + '&lesson=' + encodeURIComponent(selectedLesson) + '&questionType=' + encodeURIComponent(selectedQuestionType);
    }
</script>
</body>

</html>