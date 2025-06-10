<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
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
    

    <title>Add Descriptive Questions</title>
    
    <?php include"include/links.php";?>
     
  </head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
  <?php include"include/header.php";?>


<div class="content-wrapper">
	  <div class="container-full">
		

		<!-- Main content -->
		<section class="content">

		  <div class="row">

			<div class="col-12">
			  <div class="box">
				  
				<div class="box-header">
					<h4 class="box-title">Enter Descriptive Questions</h4>  
				</div>
				<div class="box-body">
				    <form id="insert-questions" class="row">
				    <input type="hidden" value="<?php echo $_SESSION['user_id']?>" name="userid">
				    <?php include'ajax/userinfo.php'?>
					<div class="form-group col-md-3">
						<label class="col-form-label">Marks</label>
						<div>
						    <input placeholder="1" min="1" class="form-control" type="number" value="1" name="marks">
						</div>
					</div>
					<div class="form-group col-md-3">
						<label class="col-form-label">Difficulty Level</label>
						<div>
							<select class="form-control" name="difficulty" required>
							    
							    <option selected value="1">1</option>
							    <option value="2">2</option>
							    <option value="3">3</option>
							    <option value="4">4</option>
							    <option value="5">5</option>
							</select>
						</div>
					</div>
					<!--<div class="form-group col-md-2">-->
					<!--	<label class="col-form-label">Negative Value</label>-->
					<!--	<div>-->
					<!--		<input placeholder="0"  value="0" step="0.01" min="0" class="form-control" type="number" name="negative">-->
					<!--	</div>-->
					<!--</div>-->
					<div id="questionsContainer">
                                            <!-- Initial Question Block -->
                                            <div class="question-block row">
                                               <div class="form-group col-md-12">
                                                    <label class="col-form-label">Enter Your Question</label>
                                                    <div>
                                                        <textarea class="form-control tinymce-question" type="text" name="questions[]"></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label class="col-form-label">Enter Your Answer</label>
                                                    <div class="">
                                                        <textarea class="form-control tinymce-answer" type="text" name="answer[]"></textarea>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="col-form-label">Solution</label>
                                                    <div class="col-md-12">
                                                        <textarea class="form-control tinymce-solution" type="text" name="solution[]"></textarea>
                                                    </div>
                                                </div>
                                                
                                                
                                            </div>
                                        </div>

                                        <button type="button" id="addmore" class="mb-2 btn btn-success">Add Details in Another Language</button>
				   <div class="form-group col-md-6">
						
						<div>
							<input class="form-control btn btn-primary" type="submit" value="Submit">
						
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.0/tinymce.min.js" integrity="sha512-KmEMNDKX2KDYPrBMr2MJj/JLgYK271k+P2341E5wvBMgepz1HS3wpc7r65hDXcp4Ul89omtSKIHxdk8VYHd9ug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
            $(document).ready(function() {
    let questionCount = 0; // Initialize question count

    // Initialize TinyMCE for existing textareas
    initializeTinyMCE();

    $('#addmore').click(function() {
        if (questionCount < 2) { // Limit to 2 questions
            questionCount++;
            const questionBlock = `
            <div class="question-block row">
                <div class="form-group col-md-12">
                    <label class="col-form-label">Enter Your Question</label>
                    <div>
                        <textarea class="form-control tinymce-question" name="questions[]"></textarea>
                    </div>
                </div>
               
                <div class="form-group col-md-12">
                    <label class="col-form-label">Enter Your Answer</label>
                    <div class="">
                        <textarea class="form-control tinymce-answer" name="answer[]"></textarea>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="col-form-label">Solution</label>
                    <div class="col-md-12">
                        <textarea class="form-control tinymce-solution" name="solution[]"></textarea>
                    </div>
                </div>
                <button type="button" class="remove-btn btn btn-danger mb-2">Remove</button>
            </div>`;
            $('#questionsContainer').append(questionBlock);
            // Reinitialize TinyMCE for the newly added question and solution textareas
            initializeTinyMCE();
        } else {
            alert("You can only add up to 2 question sets.");
        }
    });

    // Event delegation for dynamically added remove buttons
    $('#questionsContainer').on('click', '.remove-btn', function() {
        $(this).closest('.question-block').remove();
        questionCount--; // Decrease the count when removed
    });

    // Function to initialize TinyMCE
    function initializeTinyMCE() {
        tinymce.init({
            selector: '.tinymce-question, .tinymce-solution,.tinymce-answer', // Select specific textareas
            plugins: [
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
                'importword', 'exportword', 'exportpdf'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat'
        });
    }

});
        </script>

<script>
    $(document).ready(function() {
  
    $('#insert-questions').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission
        // Get all question and answer fields
        const questions = $('textarea[name="questions[]"]');
        const answers = $('textarea[name="answer[]"]');
        let isValid = true;

        // Check if any question or answer is empty
        questions.each(function(index) {
            if ($(this).val().trim() === '' || answers.eq(index).val().trim() === '') {
                isValid = false;
                return false; // Break out of the loop
            }
        });

        if (!isValid) {
            alert('Please fill in all questions and answers before submitting.');
            return; // Stop the submission
        }
        // Serialize the form data
        var formData = $(this).serialize();

        // Send the AJAX request to add-question.php
        $.ajax({
            url: 'ajax/add-descriptive.php', // The URL to which the request is sent
            type: 'POST', // The HTTP method to use
            data: formData, // The data to send
            success: function(response) {
                // Handle success
                alert('Question added successfully!');
                console.log(response); // Log the response for debugging
                // Optionally, you can reset the form or perform other actions
                // $('#insert-questions')[0].reset(); // Reset the form
            },
            error: function(xhr, status, error) {
                // Handle error
                alert('An error occurred while adding the question: ' + error);
                console.error(xhr.responseText); // Log the error for debugging
            }
        });
    });
});
</script>

<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.0/tinymce.min.js" integrity="sha512-KmEMNDKX2KDYPrBMr2MJj/JLgYK271k+P2341E5wvBMgepz1HS3wpc7r65hDXcp4Ul89omtSKIHxdk8VYHd9ug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- Place the following <script> and <textarea> tags your HTML's <body> -->


</body>

</html>