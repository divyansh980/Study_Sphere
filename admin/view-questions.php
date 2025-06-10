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
$sql = "SELECT id, userid, questions FROM addquestion"; // Include userid in the SELECT statement
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error fetching data: " . $conn->error);
}

// Initialize an array to hold the fetched data
$questionsArray = [];

// Fetch the data
while ($row = $result->fetch_assoc()) {
    // Decode the JSON string into an array
    $questions = json_decode($row['questions'], true);
    if ($questions !== null) { // Check if decoding was successful
        // Add the ID, userid, and questions to the main array
        $questionsArray[] = [
            'id' => $row['id'],
            'userid' => $row['userid'], // Get userid from the row
            'questions' => $questions,
        ];
    }
}

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
    

    <title>View Questions</title>
    
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
								<li class="breadcrumb-item active" aria-current="page">View Questions</li>
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
					<!--<div class="box-header">						-->
					<!--	<h4 class="box-title">Complex headers (rowspan and colspan)</h4>-->
					<!--</div>-->
					<div class="box-body">
						<div class="table-responsive">
						   	<table id="example" class="display table table-striped table-bordered display" style="width:100%">
								<thead>
									
									<tr>
										<th>Question Id</th>
										<th>User Id</th>
										<th>Question</th>
										<th>Edit Question</th>
										<th>Delete Question</th>
									</tr>
								</thead>
								<tbody>
								    
								    <?php foreach ($questionsArray as $entry): ?>
                        <tr data-id="<?php echo htmlspecialchars($entry['id']); ?>">
                        <td><?php echo htmlspecialchars($entry['id']); ?></td>
                        <td><?php echo htmlspecialchars($entry['userid']); ?></td>
                        <td>
                            <ul class="question-list">
                                <?php foreach ($entry['questions'] as $question): ?>
                                    <li><?php echo htmlspecialchars($question); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td class="text-center"><a type="button" href="edit-question.php?updateid=<?php echo htmlspecialchars($entry['id']); ?>" class="btn btn-primary">Edit Question</a></td>
                        <td class="text-center"><button class="btn btn-warning delete-btn" href="delete-question.php">Delete Question</a></td>
                    </tr>
                <?php endforeach; ?>
								</tbody>
								<tfoot>
									<tr>
										<th>Question Id</th>
										<th>User Id</th>
										<th>Question</th>
										<th>Edit Question</th>
										<th>Delete Question</th>
										
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
	    new DataTable('#example', {
    layout: {
        bottomEnd: {
            paging: {
                firstLast: false
            }
        }
    }
});
	</script>
	<script>
$(document).ready(function() {
    $('.delete-btn').click(function() {
        var row = $(this).closest('tr');
        var id = row.data('id');

        if (confirm('Are you sure you want to delete this Question?')) {
            $.ajax({
                url: 'ajax/delete-question.php',
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
