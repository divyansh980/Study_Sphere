<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

include "ajax/db.php";
$updateid = $_GET['updateid'];
// Prepare the SQL statement to fetch all data
$sql = "SELECT * FROM arbased WHERE id='$updateid'"; // Fetching id along with questions
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
    
    <title>Edit Questions</title>
    
    <?php include "include/links.php"; ?>
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
</head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
    <?php include "include/header.php"; ?>

    <div class="content-wrapper">
        <div class="container-full">
            <section class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="box">
                            <div class="box-header">
                                <h4 class="box-title">Edit the Questions</h4>  
                            </div>
                            <div class="box-body">
                                <form id="edit-questions" class="row">
                                    <div class="form-group col-md-12 d-none">
                                        <label class="col-form-label">Question ID</label>
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
                                            <input placeholder="1" min="1" value="<?php echo $row['marks']; ?>" class="form-control" type="number" name="marks">
                                        </div>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label class="col-form-label">Difficulty Level</label>
                                        <div>
                                            <select class="form-control" name="difficulty" required>
                                                <option selected value="<?php echo $row['difficulty']; ?>"><?php echo $row['difficulty']; ?>(Selected)</option>
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
                                            <input placeholder="0" step="0.01" value="<?php echo $row['negative']; ?>" min="0" class="form-control" type="number" name="negative">
                                        </div>
                                    </div>
                                     <div id="questionsContainer">
                                            <?php
                                            // Assuming $questionsArray is a flat array where each question is followed by its options
                                            // Number of options per question
                                            $optionsCount = 4;
                                        
                                            // Calculate the total number of questions
                                            $totalQuestions = count($questionsArray) / ($optionsCount + 1); // +1 for the question itself
                                        
                                            // Loop through each question
                                            for ($index = 0; $index < $totalQuestions; $index++) {
                                                // Calculate the starting index for the current question block
                                                $startIndex = $index * ($optionsCount + 1);
                                        
                                                // Each question block starts here
                                                echo '<div class="question-block row' . ($index === 0 ? ' mandatory' : '') . '">'; // Add 'mandatory' class to the first block
                                                
                                                // Question Text Area
                                                echo '<div class="form-group col-md-12">';
                                                echo '<label class="col-form-label">Enter Your Question</label>';
                                                echo '<div>';
                                                echo '<textarea class="form-control tinymce-question" type="text" name="questions[]">' . htmlspecialchars($questionsArray[$startIndex]) . '</textarea>';
                                                echo '</div>';
                                                echo '</div>';
                                        
                                                // Options (next 5 elements)
                                                for ($i = 1; $i <= $optionsCount; $i++) {
                                                    echo '<div class="form-group col-md-5">';
                                                    echo '<label class="col-form-label">Option ' . $i . '</label>';
                                                    echo '<div>';
                                                    echo '<input class="form-control option-input" required type="text" name="questions[]" value="' . htmlspecialchars($questionsArray[$startIndex + $i]) . '" oninput="updateCheckboxValue(this, \'checkbox' . ($index + 1) . $i . '\')" data-question="' . ($index + 1) . '" data-option="' . $i . '">';
                                                    echo '</div>';
                                                    echo '</div>';
                                                    echo '<div class="form-group col-md-1">';
                                                    echo '<label class="col-form-label"></label>';
                                                    echo '<div>';
                                                    echo '<input class="form-check-input" type="checkbox" id="checkbox' . ($index + 1) . $i . '" name="correct_answers[]" data-question="' . ($index + 1) . '" data-option="' . $i . '">';
                                                    echo '</div>';
                                                    echo '</div>';
                                                }
                                        
                                                // Answer Field
                                                echo '<div class="form-group col-md-12">';
                                                echo '<label class="col-form-label">Enter Your Answer</label>';
                                                echo '<div class="">';
                                                echo '<input class="form-control" type="text" name="answer[]" value="' . htmlspecialchars($answersArray[$index] ?? '') . '" placeholder="' . htmlspecialchars($answersArray[$index] ?? '') . '">';
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
    <script>
        document.getElementById('cancelButton').onclick = function() {
            window.history.back(); // Redirects to the previous page
        };
    </script>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.0/tinymce.min.js" integrity="sha512-KmEMNDKX2KDYPrBMr2MJj/JLgYK271k+P2341E5wvBMgepz1HS3wpc7r65hDXcp4Ul89omtSKIHxdk8VYHd9ug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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
        if (!$(this).closest('.question-block').hasClass('mandatory')) { // Check if the block is not mandatory
            $(this).closest('.question-block').remove();
            questionCount--; // Decrease the count when removed
            console.log("hii");
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

    // Function to generate options for each question block
    function generateOptions(questionCount) {
    let optionsHtml = '';
    const isFirstQuestion = questionCount === 1; // Check if it's the first question

    if (isFirstQuestion) {
        // Define values for the first question options
        const optionValues = [
            "अभिकथन (A) और कारण (R) दोनों सत्य हैं और कारण (R) अभिकथन (A) की सही व्याख्या है।",
            "अभिकथन (A) और कारण (R) दोनों सत्य हैं लेकिन कारण (R) अभिकथन (A) का सही स्पष्टीकरण नहीं है।",
            "अभिकथन (A) सत्य है और कारण (R) असत्य है।",
            "अभिकथन (A) गलत है और कारण (R) सत्य है।"
        ];

        for (let i = 0; i < optionValues.length; i++) {
            optionsHtml += `
            <div class="form-group col-md-5">
                <label class="col-form-label">Option ${String.fromCharCode(97 + i)}</label>
                <div>
                    <input class="form-control option-input" type="text" name="questions[]" value="${optionValues[i]}" oninput="updateCheckboxValue(this, 'checkbox${questionCount}${i + 1}')" data-question="${questionCount}" data-option="${i + 1}">
                </div>
            </div>
            <div class="form-group col-md-1">
                <label class="col-form-label"></label>
                <div>
                    <input class="form-check-input" type="checkbox" id="checkbox${questionCount}${i + 1}" name="correct_answers[]" data-question="${questionCount}" data-option="${i + 1}">
                </div>
            </div>`;
        }
    } else {
        // For the second question, set the values as 1, 2, 3, 4
        for (let i = 1; i <= 4; i++) {
            optionsHtml += `
            <div class="form-group col-md-5">
                <label class="col-form-label">Option ${i}</label>
                <div>
                    <input class="form-control option-input" type="text" name="questions[]" value="" oninput="updateCheckboxValue(this, 'checkbox${questionCount}${i}')" data-question="${questionCount}" data-option="${i}">
                </div>
            </div>
            <div class="form-group col-md-1">
                <label class="col-form-label"></label>
                <div>
                    <input class="form-check-input" type="checkbox" id="checkbox${questionCount}${i}" name="correct_answers[]" data-question="${questionCount}" data-option="${i}">
                </div>
            </div>`;
        }
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
     $('#edit-questions').on('submit', function(event) {
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
                $.ajax({
                    url: 'ajax/edit-arbased.php', // The PHP script to handle the insertion
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
                        window.location.href = 'view-arquestions.php';
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