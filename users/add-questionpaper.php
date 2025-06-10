<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>
<?php
 include "ajax/db.php";

$query1 = "SELECT DISTINCT class FROM adddetails";
$result1 = $conn->query($query1);

            ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    

    <title>Create Question Paper</title>
    
    <?php include"include/links.php";?>
     
  </head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
  <?php include"include/header.php";?>


<div class="content-wrapper">
	  <div class="container-full">
		<!-- Content Header (Page header) -->
		<!--<div class="content-header">-->
		<!--	<div class="d-flex align-items-center">-->
		<!--		<div class="me-auto">-->
					<!--<h4 class="page-title">Advanced Form Elements</h4>-->
		<!--			<div class="d-inline-block align-items-center">-->
		<!--				<nav>-->
		<!--					<ol class="breadcrumb">-->
		<!--						<li class="breadcrumb-item"><a href="index.php">Home</a></li>-->
								<!--<li class="breadcrumb-item" aria-current="page">Forms</li>-->
		<!--						<li class="breadcrumb-item active" aria-current="page">Add MCQ</li>-->
		<!--					</ol>-->
		<!--				</nav>-->
		<!--			</div>-->
		<!--		</div>-->
				
		<!--	</div>-->
		<!--</div>-->

		<!-- Main content -->
		<section class="content">

		  <div class="row">

			<div class="col-12">
			  <div class="box">
				  
				<div class="box-header">
					<h4 class="box-title">Create Question Paper</h4>  
				</div>
				<div class="box-body">
				    <form id="insert-questions" class="row">
				    <input type="hidden" value="<?php echo $_SESSION['user_id']?>" name="userid">
				    
				    
					<div class="form-group col-md-4">
						<label class="col-form-label">Class/Course</label>
						<div>
							<select class="form-control" name="class" id="classDropdown" required>
                            <option disabled selected>Please Select Class/Course</option>
                            <?php
                            if ($result1->num_rows > 0) {
                                while ($row1 = $result1->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($row1['class']) . "'>" . htmlspecialchars($row1['class']) . "</option>";
                                }
                            } else {
                                echo "<option disabled>No Classes available</option>";
                            }
                            ?>
                        </select>
						</div>
					</div>
					
					<div class="form-group col-md-4">
						<label class="col-form-label">Subject</label>
						<div>
						 <select class="form-control" id="subject" name="subject" required>
                            
        
                        </select>
						</div>
					</div>
					<div class="form-group col-md-4">
						<label class="col-form-label">Book</label>
						<div>
							<select class="form-control" name="book" id="book" required>
							   
							 </select>
						</div>
					</div>
					<div class="form-group col-md-4">
						<label class="col-form-label">Question Type</label>
						<div>
							<select class="form-control" name="questiontype" id="questiontype" required>
							   <option selected disabled>Please Select Question Type</option>
							   <option value="MCQ">MCQ</option>
							   <option value="AR Based">AR Based</option>
							   <option value="Fillups">Fillups</option>
							   <option value="True/False">True/False</option>
							   <option value="Descriptive">Descriptive</option>
							   
							 </select>
						</div>
					</div>
					<div class="form-group col-md-4">
						<label class="col-form-label">Question Paper Title</label>
						<div>
							<input class="form-control" name="papertitle" id="papertitle" required>
						</div>
					</div>
					
					<div class="form-group col-md-12">
                            <label class="col-form-label">Select lessons</label>
                            <div id="lessonContainer">
                                <!-- Questions will be dynamically loaded here -->
                            </div>
                        </div>
					<button id="fetchQuestions" class=" btn btn-primary"  type="button">Fetch Questions</button>
					<div class="form-group col-md-12">
                            <label class="col-form-label">Select Questions</label>
                            <div id="questionsContainer">
                                <!-- Questions will be dynamically loaded here -->
                            </div>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <div>
                                <input class="form-control btn btn-primary" type="submit" value="Add Question">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                                            <div>
                                                <button id="cancelButton" type="button" class="form-control btn btn-primary">Cancel</button>
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
	
  <?php include"include/footer.php";?>
	
	<!-- Page Content overlay -->
	
	
	<!-- Vendor JS -->
	<?php include"include/script.php";?>
	<script>
        document.getElementById('cancelButton').onclick = function() {
            window.history.back(); // Redirects to the previous page
        };
    </script>
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
                var lessonSelect = $('#lessonContainer');
                lessonSelect.empty();
                response.lessons.forEach(function(lesson) {
                    console.log(lesson);
                    lessonSelect.append('<div class="form-check">' +
                            '<input type="checkbox" value="'+lesson+'" id="lesson' + lesson +'" name="selected_lesson[]" class="form-check-input">' +
                            '<label class="form-check-label" for="lesson' + lesson +'">' + lesson + '</label>' +
                            '</div>');
                });

                
            }
        });
    });

$('#fetchQuestions').click(function() {
    // Get selected lessons
    var selectedBook = $('#book').val();
    var selectedSubject = $('#subject').val();
    var selectedClass = $('#classDropdown').val();
    var questionType = $('#questiontype').val();
    var selectedLessons = [];

    $('input[name="selected_lesson[]"]:checked').each(function() {
        selectedLessons.push($(this).val());
    });

    // Check if any lessons are selected
    if (selectedLessons.length === 0) {
        alert('Please select at least one lesson.');
        return;
    }

    // Send selected lessons to the server
    $.ajax({
        url: 'ajax/fetch_questions.php',
        type: 'POST',
        data: {
            questiontype:questionType,
            lessons: selectedLessons,
            book: selectedBook,
            subject: selectedSubject,
            class: selectedClass
        },
        success: function(data) {
            console.log("Raw response data:", data); // Log the raw response data
            
            var response;
            try {
                response = JSON.parse(data); // Try to parse the response
            } catch (e) {
                console.error("Error parsing JSON:", e);
                alert("An error occurred while processing the response.");
                return;
            }

            console.log("Decoded response:", response); // Log the decoded response
            
            var questionsContainer = $('#questionsContainer');
            questionsContainer.empty(); // Clear previous questions
            
            // Check if there are questions returned
            if (response.questions && response.questions.length > 0) {
                // Iterate through all the questions array and display them
                response.questions.forEach(function(questionData, index) {
                    // Parse the question JSON string into an array
                    var questionsArray = JSON.parse(questionData.question); // Convert JSON string to array
                    var marks = questionData.marks; // Get the marks
                    var id = questionData.id;
                    
                    // Concatenate questions into a single string
                    var concatenatedQuestions = questionsArray.join('<br>'); // Join questions with line breaks
                    
                    // Append the question and checkbox to the container
                    questionsContainer.append('<div class="form-check">' +
                        '<input type="checkbox" value="' + encodeURIComponent(concatenatedQuestions) + '" id="question_' + index + '" name="selected_questions[]" class="form-check-input">' +
                        '<label class="form-check-label" for="question_' + index + '">' + concatenatedQuestions + ' (Marks: ' + marks + ')</label>' +
                        '<input type="hidden" value="'+id+'" name="questionid[]">'+
                        '</div>');
                });
            } else {
                questionsContainer.append('<div>No questions found for the selected criteria.</div>');
            }
        },
        error: function(xhr, status, error) {
            console.error("An error occurred: " + error);
            alert("An error occurred while fetching questions.");
        }
    });
});


$(document).ready(function() {
    // Existing code...

    $('#insert-questions').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Get the paper title
        var paperTitle = $("input[name='papertitle']").val();
        var questionType = $("#questiontype").val();
        // Get question IDs
        var questionIds = [];
        $('input[name="questionid[]"]').each(function() {
            // Check if the corresponding question checkbox is checked
            if ($(this).closest('.form-check').find('input[name="selected_questions[]"]').is(':checked')) {
                questionIds.push($(this).val()); // Add the question ID to the array
            }
        });

        // Check if any questions are selected
        if (questionIds.length === 0) {
            alert('Please select at least one question.');
            return; // Exit if no questions are selected
        }

        // Prepare the data to send
        var formData = {
            papertitle: paperTitle,
            questiontype:questionType,// The title of the question paper
            questionid: questionIds // Array of selected question IDs
        };

        // Send form data to the server
        $.ajax({
            url: 'ajax/createpaper.php', // The PHP script to handle the request
            type: 'POST',
            data: formData,
            success: function(response) {
                try {
                    var result = JSON.parse(response); // Parse the JSON response
                    if (result.error) {
                        alert(result.error); // Show error if any
                    } else {
                        alert(result.success); // Show success message
                        // Optionally reset the form or redirect
                        // $('#insert-questions')[0].reset(); // Reset the form
                    }
                } catch (e) {
                    console.error("Error parsing JSON response:", e);
                    alert("An error occurred while processing the response.");
                }
            },
            error: function(xhr, status, error) {
                console.error("An error occurred: " + error);
                alert("An error occurred while submitting the form.");
            }
        });
    });
});

 
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.0/tinymce.min.js" integrity="sha512-KmEMNDKX2KDYPrBMr2MJj/JLgYK271k+P2341E5wvBMgepz1HS3wpc7r65hDXcp4Ul89omtSKIHxdk8VYHd9ug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>




</body>

</html>