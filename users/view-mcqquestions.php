<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
$userid= $_SESSION['user_id'];
include "ajax/db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>MCQ</title>
    
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
								<li class="breadcrumb-item active" aria-current="page">MCQ</li>
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
					        <input type="hidden" value="<?php echo $userid;?>" name="userid">
					        <input type="hidden" value="MCQ" name="questiontype" id="questiontype">
					<?php include 'ajax/userinfo.php';?>
					<div class="form-group col-md-3 mt-2">
						<label></label>
						<div>
							<input class="form-control btn btn-primary" name="submit" type="submit" value="View Question">
						</div>
					</div>
					<div class="form-group col-md-3 mt-2">
						<label></label>
						<div>
							 <a href="add-mcq.php" class="btn btn-primary" type="button">Add MCQ</a>
						</div>
					</div>
					<div class="form-group col-md-2 mt-2">
						<label></label>
					<div>
                       
                         <a id="viewButton" class="btn btn-primary" href="#">Preview</a>
                        
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
    $('#classDropdown').change(function() {
        var selectedClass = $(this).val();
        
        // Fetch subjects based on selected class
        $.ajax({
            url: 'ajax/fetch_subjects.php',
            type: 'POST',
            data: { class: selectedClass },
            success: function(data) {
                var response = JSON.parse(data);
                var subjectSelect = $('select[name="subject"]');
                subjectSelect.empty().append('<option disabled selected>Please Select Subject</option>');
                response.subjects.forEach(function(subject) {
                    subjectSelect.append('<option value="' + subject + '">' + subject + '</option>');
                });
            }
        });
    });

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

    $('select[name="book"]').change(function() {
        var selectedBook = $(this).val();
        var selectedSubject = $('#subject').val();
        var selectedClass = $('#classDropdown').val(); 
        // Fetch lessons based on selected book
        $.ajax({
            url: 'ajax/fetch_lessons_topics.php',
            type: 'POST',
            data: { book: selectedBook, subject: selectedSubject, class: selectedClass },
            success: function(data) {
                var response = JSON.parse(data);
                
                // Update the lesson dropdown
                var lessonSelect = $('select[name="lesson"]');
                lessonSelect.empty().append('<option disabled selected>Please Select Lesson</option>');
                response.lessons.forEach(function(lesson) {
                    lessonSelect.append('<option value="' + lesson + '">' + lesson + '</option>');
                });

                
            }
        });
    });
     $('#myForm').on('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    $.ajax({
        url: 'ajax/view-questions.php', // Replace with your server endpoint
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(data) {
            console.log(data);
            // Check if there are questions and display them
            if (data.questions && data.questions.length > 0) {
                let questionsHtml = '';
                data.questions.forEach(function(questionObj) {
                    // Assuming questionObj.question is a JSON string representing an array
                    const questionArray = JSON.parse(questionObj.question); // Parse the question string
                    if (questionArray.length > 0) {
                        // Create buttons for Edit and Delete
                        questionsHtml += '<div>' +
                            '<p>' + questionArray[0] + '</p>' + // Display only the first element
                            '<div style="text-align:right !important">' +
                            '<a href="edit-mcq.php?updateid='+ questionObj.id +'" class=" btn btn-primary me-2" type="button" data-id="' + questionObj.id + '">Edit</a>' +
                            '<button class="delete-btn btn btn-warning" data-id="' + questionObj.id + '">Delete</button>' +
                            '</div>'+
                            '</div>';
                    }
                });
                $('#response').html(questionsHtml); // Display all questions
            } else {
                $('#response').text('No questions available.');
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

 <script>
$(document).ready(function() {
    $('#response').on('click', '.delete-btn', function() {
        // Get the ID from the clicked button's data attribute
        var id = $(this).data('id'); // Use $(this) to refer to the clicked button

        if (confirm('Are you sure you want to delete this question?')) {
            $.ajax({
                url: 'ajax/delete-mcq.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    if (response.success) {
                        // Remove the parent div of the button (which contains the question and buttons)
                        $(this).parent().remove(); // Use $(this) to refer to the clicked button's parent
                        alert('Question deleted successfully.');
                    } else {
                        alert('Error deleting question: ' + response.message);
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
        document.getElementById('viewButton').href = 'preview-inserted-questions.php?book=' + book + '&class=' + className + '&subject=' + subject + '&lesson=' + encodeURIComponent(selectedLesson) + '&questionType=' + encodeURIComponent(selectedQuestionType);
    }
</script>
</body>

</html>