<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}
$userid = $_SESSION['email'];

include "ajax/db.php";

// Prepare the SQL statement to fetch distinct data based on the new fields
$sql12 = "SELECT DISTINCT class,subject,book, papertitle FROM questionpaper WHERE user_id = '$userid'";
$result12 = $conn->query($sql12);

if ($result12 === false) {
    die("Error fetching data: " . $conn->error);
}



// Close the connection
// $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Question Papers</title>
    
    <?php 
        include "include/links.php";
         
    ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
    <?php 
        include "include/header.php"; 
        
    ?>

    <div class="content-wrapper">
		<div class="container-full">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="d-flex align-items-center">
					<div class="me-auto">
						<div class="d-inline-block align-items-center">
							<nav>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="index.php">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">Question Papers</li>
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
							    <a href="question-papers-details.php" class="btn btn-primary" type="button">Add Questions Paper</a>
								<div class="table-responsive">
								    <table id="example" class="display table table-striped table-bordered display" style="width:100%">
										<thead>
											<tr>
												<th>Class/Course</th>
											    <th>Book</th>
												<th>Paper Title</th>
												<th>View</th>
												<th>Delete</th>
											</tr>
										</thead>
										<tbody>
										    <?php
										    while ($row12 = $result12->fetch_assoc()) {
                                               
                                                echo '
                                            <tr data-id="'.$row12['papertitle'].'">
                                                <td>'.$row12['class'].'</td>
                                                <td>'.$row12['book'].'</td>
                                                <td>'.$row12['papertitle'].'</td>
                                                <td class="text-center"><a class="btn btn-primary" href="question-paper.php?book='.$row12['book'].'& class='.$row12['class'].'& subject='.$row12['subject'].'&papertitle='.$row12['papertitle'].'">View</a></td>
                                                <td class="text-center"><button class="btn btn-warning delete-btn">Delete</button></td>
                                              </tr>';
                                            }
										    ?>
										</tbody>
										<tfoot>
											<tr>
												<th>Class/Course</th>
												<th>Book</th>
												<th>Paper Title</th>
											    <th>View</th>
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

<?php include "include/footer.php"; ?>
<?php include "include/script.php"; ?>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script>
    // Initialize DataTable
    new DataTable('#example', {
        order: [[0, 'desc']]
    });

    $(document).ready(function() {
        $('#example tbody').on('click', '.delete-btn', function() {
            var row = $(this).closest('tr');
            var id = row.data('id');

            if (confirm('Are you sure you want to delete this Question?')) {
                $.ajax({
                    url: 'ajax/delete-questionpaper.php',
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
</body>
</html>