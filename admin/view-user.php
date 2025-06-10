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
$sql = "SELECT * FROM user"; // Fetching id along with questions
$result = $conn->query($sql);
$sql1 = "SELECT * FROM user2 WHERE user_type ='Teacher'"; // Fetching id along with questions
$result1 = $conn->query($sql1);
$sql2 = "SELECT * FROM user2 WHERE user_type ='Student'"; // Fetching id along with questions
$result2 = $conn->query($sql2);
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
    

    <title>View Users</title>
    
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
								<li class="breadcrumb-item active" aria-current="page">View Users</li>
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
					    <h4 class="text-center">Editors</h4>
					    <a href="create-user.php" type="button" class="btn btn-primary">Add New User</a>
						<div class="table-responsive">
						   	<table id="example" class="display table table-striped table-bordered display" style="width:100%">
								<thead>
									
									<tr>
										<th>User Id</th>
										<th>User Name</th>
										<th>Edit User</th>
										<th>Delete User</th>
									</tr>
								</thead>
								<tbody>
								    
								    <?php 
								    while ($row = $result->fetch_assoc()){
								    echo '
								    <tr data-id="'.$row['id'].'">
                                            <td>'.$row['userid'].'</td>
                                            <td>'.$row['full_name'].'</td>
                                            <td class="text-center"><a type="button" href="edit-user.php?updateid='.$row['id'].'" class="btn btn-primary">Edit User</a></td>
                                            <td class="text-center"><button class="btn btn-warning delete-btn" href="delete-question.php">Delete User</a></td>
                                        </tr>
								    
								    ';
								    }?>
                        
                
								</tbody>
								<tfoot>
									<tr>
										<th>User Id</th>
										<th>User Name</th>
										<th>Edit User</th>
										<th>Delete User</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					
				</div>
			</div>
            <div class="col-12">
				<div class="box">
					<!--<div class="box-header">						-->
					<!--	<h4 class="box-title">Complex headers (rowspan and colspan)</h4>-->
					<!--</div>-->
					<div class="box-body">
					    <h4 class="text-center">Teachers</h4>
						<div class="table-responsive">
						   	<table id="example1" class="display table table-striped table-bordered display" style="width:100%">
								<thead>
									
									<tr>
										<th>Gmail</th>
										<th>Class</th>
										<th>Subject</th>
										<th>Mobile Number</th>
										<th>Delete</th>
									</tr>
								</thead>
								<tbody>
								    
								    <?php 
                                    while ($row1 = $result1->fetch_assoc()) {
                                        // Decode the JSON-encoded arrays
                                        $classes = json_decode($row1['class'], true);
                                        $subjects = json_decode($row1['subject'], true);
                                        
                                        // Convert arrays to strings for display
                                        $classList = implode(", ", $classes);
                                        $subjectList = implode(", ", $subjects);
                                        
                                        echo '
                                        <tr data-id="'.$row1['id'].'">
                                            <td>'.$row1['email'].'</td>
                                            <td>'.$classList.'</td>
                                            <td>'.$subjectList.'</td>
                                            <td>'.$row1['mobile'].'</td>
                                            <td class="text-center"><button class="btn btn-warning delete-btn" data-href="delete-question.php?id='.$row1['id'].'">Delete</button></td>
                                        </tr>
                                        ';
                                    }
                                    ?>
                        
                
								</tbody>
								<tfoot>
									<tr>
										<th>Gmail</th>
										<th>Class</th>
										<th>Subject</th>
										<th>Mobile Number</th>
										<th>Delete</th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
					
				</div>
			</div>
			<div class="col-12">
				<div class="box">
					<!--<div class="box-header">						-->
					<!--	<h4 class="box-title">Complex headers (rowspan and colspan)</h4>-->
					<!--</div>-->
					<div class="box-body">
					    <h4 class="text-center">Student</h4>
						<div class="table-responsive">
						   	<table id="example2" class="display table table-striped table-bordered display" style="width:100%">
								<thead>
									
									<tr>
										<th>Gmail</th>
										<th>Class</th>
										<th>Subject</th>
										<th>Mobile Number</th>
										<th>Delete</th>
									</tr>
								</thead>
								<tbody>
								    
								    <?php 
                                    while ($row2 = $result2->fetch_assoc()) {
                                        // Decode the JSON-encoded arrays
                                        $classes2 = json_decode($row2['class'], true);
                                        $subjects2 = json_decode($row2['subject'], true);
                                        
                                        // Convert arrays to strings for display
                                        $classList2 = implode(", ", $classes2);
                                        $subjectList2 = implode(", ", $subjects2);
                                        
                                        echo '
                                        <tr data-id="'.$row2['id'].'">
                                            <td>'.$row2['email'].'</td>
                                            <td>'.$classList2.'</td>
                                            <td>'.$subjectList2.'</td>
                                            <td>'.$row2['mobile'].'</td>
                                            <td class="text-center"><button class="btn btn-warning delete-btn" data-href="delete-question.php?id='.$row1['id'].'">Delete</button></td>
                                        </tr>
                                        ';
                                    }
                                    ?>
                        
                
								</tbody>
								<tfoot>
									<tr>
										<th>Gmail</th>
										<th>Class</th>
										<th>Subject</th>
										<th>Mobile Number</th>
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
    new DataTable('#example1',{
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
    new DataTable('#example2',{
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

        if (confirm('Are you sure you want to delete this User?')) {
            $.ajax({
                url: 'ajax/delete-user.php',
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
$(document).ready(function() {
    $('#example1 tbody').on('click', '.delete-btn', function() {
        var row = $(this).closest('tr');
        var id = row.data('id');

        if (confirm('Are you sure you want to delete this User?')) {
            $.ajax({
                url: 'ajax/delete-user2.php',
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
$(document).ready(function() {
    $('#example2 tbody').on('click', '.delete-btn', function() {
        var row = $(this).closest('tr');
        var id = row.data('id');

        if (confirm('Are you sure you want to delete this User?')) {
            $.ajax({
                url: 'ajax/delete-user2.php',
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
