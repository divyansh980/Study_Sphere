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


$updateid = $_GET['updateid'];

// Prepare the SQL statement to fetch all data
$sql = "SELECT * FROM fillups WHERE id='$updateid'"; // Fetching id along with questions
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$class=$row['class'];
$subject=$row['subject'];
$book=$row['book'];
$query1 = "SELECT DISTINCT lesson FROM `adddetails` WHERE class='$class' AND subject='$subject' AND book='$book'";
$result1 = $conn->query($query1);
$questionsArray = json_decode($row['question'], true);
$answersArray = json_decode($row['answer'], true);
$solutionsArray = json_decode($row['solution'], true);


// Check if the query was successful
if ($result === false) {
    die("Error fetching data: " . $conn->error);
}

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
    
    <title>Edit Fill Up Questions</title>
    
    <?php include "include/links.php"; ?>
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
    <?php include "include/header.php"; ?>

    <div class="content-wrapper">
        <div class="container-full">
            

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Edit Fill Up Questions</h4>  
                            </div>
                            <div class="box-body">
                                <form id="edit-questions" class="row">
				    <div class="form-group col-md-12 d-none">
						<label class="col-form-label"> Question ID</label>
						<div>
                                            <input class="form-control" name="questionid" readonly value="<?php echo $row['id']; ?>" >
                                            <input class="form-control" name="class" readonly value="<?php echo $row['class']; ?>" >
                                            <input class="form-control" name="subject" readonly value="<?php echo $row['subject']; ?>" >
                                            <input class="form-control" name="book" readonly value="<?php echo $row['book']; ?>" >
                                            
                                        </div>
					</div>
				    
				    
				
					<div class="form-group col-md-7">
                                        <label class="col-form-label">Lesson</label>
                                        <div>
                                            <select class="form-control" name="lesson" required>
                                                <option selected value="<?php echo $row['lesson']; ?>"><?php echo $row['lesson']; ?>(Selected)</option>
                                                <?php
                                                if ($result1->num_rows > 0) {
                                                    while ($row1 = $result1->fetch_assoc()) {
                                                        echo "<option value='" . htmlspecialchars($row1['lesson']) . "'>" . htmlspecialchars($row1['lesson']) . "</option>";
                                                    }
                                                } else {
                                                    echo "<option disabled>No Classes available</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
					<div class="form-group col-md-1">
						<label class="col-form-label">Marks</label>
						<div>
						    <input placeholder="1" min="1" value="<?php echo $row['marks'];?>" class="form-control" type="number" name="marks">
							
						</div>
					</div>
					<div class="form-group col-md-2">
						<label class="col-form-label">Difficulty Level</label>
						<div>
							<select class="form-control" name="difficulty" required>
							    <option Selected value="<?php echo $row['difficulty'];?>"><?php echo $row['difficulty'];?>(Selected)</option>
							    <option value="1">1</option>
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
							<input placeholder="0" step="0.01" value="<?php echo $row['negative'];?>"  min="0" class="form-control" type="number" name="negative">
						</div>
					</div>
					<div id="questionsContainer">
                            <?php
                            
                        
                            // Calculate the total number of questions
                            $totalQuestions = count($questionsArray);
                        
                            // Loop through each question
                            for ($index = 0; $index < $totalQuestions; $index++) {
                                
                        
                                // Each question block starts here
                                echo '<div class="question-block row' . ($index === 0 ? ' mandatory' : '') . '">'; // Add 'mandatory' class to the first block
                                
                                // Question Text Area
                                echo '<div class="form-group col-md-12">';
                                echo '<label class="col-form-label">Enter Your Question</label>';
                                echo '<div>';
                                echo '<textarea class="form-control tinymce-question" type="text" name="questions[]">' . htmlspecialchars($questionsArray[$index]) . '</textarea>';
                                echo '</div>';
                                echo '</div>';
                                // Answer Field
                                echo '<div class="form-group col-md-12">';
                                echo '<label class="col-form-label">Enter Your Answer</label>';
                                echo '<div class="">';
                                echo '<textarea class="form-control " type="text" name="answer[]" >' . htmlspecialchars($answersArray[$index]) . '</textarea>';
                                echo '</div>';
                                echo '</div>';
                        
                                // Solution Field
                                echo '<div class="form-group col-md-12">';
                                echo '<label class="col-form-label">Solution</label>';
                                echo '<div class="col-md-12">';
                                echo '<textarea class="form-control tinymce-solution" type="text" name="solution[]">' . htmlspecialchars($solutionsArray[$index] ?? '') . '</textarea>';
                                echo '</div>';
                                echo '</div>';
                                if ($index > 0) {
                                    echo '<button type="button" class="remove-btn btn btn-danger mb-2">Remove</button>';
                                }
                                // End of question block
                                echo '</div>';
                        
                                // Add remove button for blocks after the first one
                                
                            }
                            ?>
                        </div>

                                        <button type="button" id="addmore" class="mb-2 btn btn-success">Add Details in Another Language</button>
				   <div class="form-group col-md-6">
						
						<div>
							<input class="form-control btn btn-primary" type="submit" value="Edit">
						
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
	
    <?php include "include/footer.php"; ?>
	
    <!-- Vendor JS -->
    <?php include "include/script.php"; ?>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.0/tinymce.min.js" integrity="sha512-KmEMNDKX2KDYPrBMr2MJj/JLgYK271k+P2341E5wvBMgepz1HS3wpc7r65hDXcp4Ul89omtSKIHxdk8VYHd9ug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
        document.getElementById('cancelButton').onclick = function() {
            window.history.back(); // Redirects to the previous page
        };
</script>
<script>
            $(document).ready(function() {

    let questionCount = <?php echo $totalQuestions - 1; ?>; // Initialize question count based on existing questions

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
                    <div class="col-md-12">
                        <textarea class="form-control " name="answer[]"></textarea>
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
        if (!$(this).closest('.question-block').hasClass('mandatory')) { // Check if the block is not mandatory
            $(this).closest('.question-block').remove();
            questionCount--; // Decrease the count when removed
            
        } else {
            alert("The first question block cannot be removed.");
        }
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

     $('#edit-questions').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission
                // Get all question and answer fields
        const questions = $('textarea[name="questions[]"]');
        const answers = $('textarea[name="answer[]"]');
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
                $.ajax({
                    url: 'ajax/edit-fillup.php', // The PHP script to handle the insertion
                    type: 'POST',
                    data: $(this).serialize(), // Serialize the form data
                    dataType: 'json', // Expect a JSON response
                    success: function(response) {
                        if (response.success) {
                            alert(response.message); // Show success message in alert
                        } else {
                            alert('Error: ' + response.message); // Show error message in alert
                        }
                        // Optionally reset the form or navigate to another page
                        // $('#edit-questions')[0].reset(); // Reset the form
                        window.location.href = 'view-fillupquestions.php';
                    },
                    error: function(xhr, status, error) {
                        alert('An error occurred while processing your request: ' + error);
                    }
                });
            });
    
});
</script>


</body>

</html>