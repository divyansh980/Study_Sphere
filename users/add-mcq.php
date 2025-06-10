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
    

    <title>Add MCQ</title>
    
    <?php include"include/links.php";?>
     
  </head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
  <?php include"include/header.php";?>
  <style>
      .form-check-input {
    width: 2em;
    height: 2em;
    margin-top: 1.1em;
          
      }
    [type="checkbox"]:not(:checked), [type="checkbox"]:checked {
     position: inherit !important;
    
     opacity: 1 !important; 
}
  </style>

<div class="content-wrapper">
	  <div class="container-full">
		

		<!-- Main content -->
		<section class="content">

		  <div class="row">

			<div class="col-12">
			  <div class="box">
				  
				<div class="box-header">
					<h4 class="box-title">Enter the Questions</h4>  
				</div>
				<div class="box-body">
				    <form id="insert-questions" class="row">
				    <input type="hidden" value="<?php echo $_SESSION['user_id']?>" name="userid">
				    <?php include'ajax/userinfo.php'?>
					<div class="form-group col-md-2">
						<label class="col-form-label">Marks</label>
						<div>
						    <input placeholder="1" min="1" class="form-control" type="number" value="1" name="marks">
						</div>
					</div>
					<div class="form-group col-md-2">
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
					<div class="form-group col-md-2">
						<label class="col-form-label">Negative Value</label>
						<div>
							<input placeholder="0"  value="0" step="0.01" min="0" class="form-control" type="number" name="negative">
						</div>
					</div>
					                            
                                <div id="questionsContainer">
                                            <!-- Initial Question Block -->
                                            <div class="question-block row">
                                               <div class="form-group col-md-12">
                                                    <label class="col-form-label">Enter Your Question</label>
                                                    <div>
                                                        <textarea class="form-control tinymce-question" type="text" name="questions[]"></textarea>
                                                    </div>
                                                </div>

                                                <!-- Options -->
                                                <div class="form-group col-md-5">
                                                    <label class="col-form-label">Option 1</label>
                                                    <div>
                                                        <input class="form-control option-input" required type="text" name="questions[]" oninput="updateCheckboxValue(this, 'checkbox1')" data-question="1" data-option="1">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-1">
                                                    <label class="col-form-label"></label>
                                                    <div>
                                                        <input class="form-check-input" type="checkbox" id="checkbox1" name="correct_answers[]" data-question="0" data-option="1">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-5">
                                                    <label class="col-form-label">Option 2</label>
                                                    <div>
                                                        <input class="form-control option-input" required type="text" name="questions[]" oninput="updateCheckboxValue(this, 'checkbox2')" data-question="2" data-option="2">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-1">
                                                    <label class="col-form-label"></label>
                                                    <div>
                                                        <input class="form-check-input" type="checkbox" id="checkbox2" name="correct_answers[]" data-question="0" data-option="2">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-5">
                                                    <label class="col-form-label">Option 3</label>
                                                    <div>
                                                        <input class="form-control option-input" required type="text" name="questions[]" oninput="updateCheckboxValue(this, 'checkbox3')" data-question="3" data-option="3">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-1">
                                                    <label class="col-form-label"></label>
                                                    <div>
                                                        <input class="form-check-input" type="checkbox" id="checkbox3" name="correct_answers[]" data-question="0" data-option="3">
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-5">
                                                    <label class="col-form-label">Option 4</label>
                                                    <div>
                                                        <input class="form-control option-input" required type="text" name="questions[]" oninput="updateCheckboxValue(this, 'checkbox4')" data-question="4" data-option="4">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-1">
                                                    <label class="col-form-label"></label>
                                                    <div>
                                                        <input class="form-check-input" type="checkbox" id="checkbox4" name="correct_answers[]" data-question="0" data-option="4" >
                                                    </div>
                                                </div>

                                                <div class="form-group col-md-5">
                                                    <label class="col-form-label">Option 5</label>
                                                    <div>
                                                        <input class="form-control option-input" type="text" value="None of these" name="questions[]" oninput="updateCheckboxValue(this, 'checkbox5')" data-question="5" data-option="5">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-1">
                                                    <label class="col-form-label"></label>
                                                    <div>
                                                        <input class="form-check-input" type="checkbox" id="checkbox5" name="correct_answers[]" data-question="0" data-option="5">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group col-md-12">
                                                    <label class="col-form-label">Enter Your Answer</label>
                                                    <div class="">
                                                        <input class="form-control" type="text" name="answer[]">
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
                ${generateOptions(questionCount)}
                <div class="form-group col-md-12">
                    <label class="col-form-label">Enter Your Answer</label>
                    <div class="">
                        <input class="form-control" type="text" name="answer[]">
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
            selector: '.tinymce-question, .tinymce-solution', // Select specific textareas
            plugins: [
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
                'importword', 'exportword', 'exportpdf'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat'
        });
    }

    // Function to generate options for each question block
    function generateOptions(questionCount) {
        let optionsHtml = '';
        for (let i = 1; i <= 5; i++) {
            optionsHtml += `
            <div class="form-group col-md-5">
                <label class="col-form-label">Option ${i}</label>
                <div>
                    <input class="form-control option-input" type="text" name="questions[]" oninput="updateCheckboxValue(this, 'checkbox${questionCount}${i}')" data-question="${questionCount}" data-option="${i}">
                </div>
            </div>
            <div class="form-group col-md-1">
                <label class="col-form-label"></label>
                <div>
                    <input class="form-check-input" type="checkbox" id="checkbox${questionCount}${i}" name="correct_answers[]" data-question="${questionCount}" data-option="${i}">
                </div>
            </div>`;
        }
        return optionsHtml;
    }

    // Function to update the answer field based on selected checkboxes
    function updateAnswerField(questionBlock) {
        let selectedAnswers = [];
        $(questionBlock).find('input[type="checkbox"]:checked').each(function() {
            const optionIndex = $(this).data('option') - 1; // Get the index of the option
            const optionText = $(this).closest('.question-block').find('.option-input').eq(optionIndex).val(); // Get the corresponding option text
            selectedAnswers.push(optionText); // Add the option text to the selected answers
        });
        $(questionBlock).find('input[name="answer[]"]').val(selectedAnswers.join(', ')); // Set the answer field with selected values
    }

    // Attach change event to checkboxes using event delegation
    $('#questionsContainer').on('change', 'input[type="checkbox"]', function() {
        const checkbox = $(this);
        const optionInput = checkbox.closest('.question-block').find('.option-input').eq(checkbox.data('option') - 1);
        
        if (optionInput.val().trim() === '') {
            checkbox.prop('checked', false); // Uncheck the checkbox
            alert('Please enter a value for Option ' + checkbox.data('option') + ' before selecting it.'); // Alert the user
        } else {
            let questionBlock = checkbox.closest('.question-block');
            updateAnswerField(questionBlock); // Update answer field on checkbox change
        }
    });

    // Initialize answer fields for existing question blocks
    $('#questionsContainer .question-block').each(function() {
        updateAnswerField(this); // Update answer fields for all existing blocks
    });
});
        </script>
 
<script>
    $(document).ready(function() {
  
    $('#insert-questions').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission
        // Get all question and answer fields
        const questions = $('textarea[name="questions[]"]');
        const answers = $('input[name="answer[]"]');
        let isValid = true;

        // Check if any question or answer is empty
        questions.each(function(index) {
            if ($(this).val().trim() === '' || answers.eq(index).val() === '') {
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
            url: 'ajax/add-mcq.php', // The URL to which the request is sent
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
</body>
</html>