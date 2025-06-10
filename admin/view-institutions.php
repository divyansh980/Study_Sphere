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
$sql = "SELECT * FROM paperdetails"; // Fetching id along with questions
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
    

    <title>View Institutions</title>
    
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
					<!--<h4 class="page-title">View Questions</h4>-->
					<div class="d-inline-block align-items-center">
						<nav>
							<ol class="breadcrumb">
								<li class="breadcrumb-item"><a href="index.php">Home</a></li>
								<!--<li class="breadcrumb-item" aria-current="page">Tables</li>-->
								<li class="breadcrumb-item active" aria-current="page">View Institution</li>
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
					    <h4 class="text-center">Institutions</h4>
					    
						<div class="table-responsive">
						   	<table id="example" class="display table table-striped table-bordered display" style="width:100%">
								<thead>
									
									<tr>
										<th>User Id</th>
										<th>Institution Address</th>
										<th>Institution Name</th>
										<th>Delete</th>
									</tr>
								</thead>
								<tbody>
								    
								    <?php 
								    while ($row = $result->fetch_assoc()){
								    echo '
								    <tr data-id="'.$row['id'].'">
                                            <td>'.$row['user_id'].'</td>
                                            <td>'.$row['institutionaddress'].'</td>
                                            <td>'.$row['institutionname'].'</td>
                                            <td class="text-center"><button class="btn btn-warning delete-btn" >Delete</a></td>
                                        </tr>
								    
								    ';
								    }?>
                        
                
								</tbody>
								<tfoot>
									<tr>
									    <th>User Id</th>
										<th>Institution Address</th>
										<th>Institution Name</th>
										<th>Delete</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					
				</div>
			</div>
            
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
    new DataTable('#example',{
        layout: {
            bottomEnd: {
                paging: {
                    firstLast: false
                }
            }
        },
        // Set the initial order to the first column in descending order
        order: [[0, 'desc']] // Change the index if you want to sort by a different column
    });
    
</script>
	<script>
$(document).ready(function() {
    $('#example tbody').on('click', '.delete-btn', function() {
        var row = $(this).closest('tr');
        var id = row.data('id');

        if (confirm('Are you sure you want to delete this Institution?')) {
            $.ajax({
                url: 'ajax/delete-institution.php',
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
