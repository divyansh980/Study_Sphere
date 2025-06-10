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
$sql = "SELECT * FROM dictionary WHERE id='$updateid'"; // Fetching id along with books
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$class=$row['class'];
$subject=$row['subject'];
$book=$row['book'];
$query1 = "SELECT DISTINCT lesson FROM `adddetails` WHERE class='$class' AND subject='$subject' AND book='$book'";
$result1 = $conn->query($query1);



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
    
    <title>Edit Dictionary</title>
    
    <?php include "include/links.php"; ?>
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
                            
                            <div class="box-body">
                                <form id="edit-book" class="row">
				    <div class="form-group col-md-12 d-none">
						<label class="col-form-label ">Book ID</label>
						<div>
							<input class="form-control" name="id" readonly value="<?php echo $row['id']; ?>" >
                            <input class="form-control" id="class" name="class" readonly value="<?php echo $row['class']; ?>" >
                            <input class="form-control" id="subject" name="subject" readonly value="<?php echo $row['subject']; ?>" >
                            <input class="form-control" id="book" name="book" readonly value="<?php echo $row['book']; ?>" >
						</div>
					</div> 
					<div class="form-group col-md-6">
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
					<div class="form-group col-md-6">
						<label class="col-form-label">Word</label>
						<div>
							<input class="form-control" name="word" value="<?php echo $row['word'];?>" required>
							    
						</div>
					</div>
				    
					
					
					<div class="books-cont">
    <label class="col-form-label col-md-12">Description</label>
         <div class="books">
                <div class="form-group row">
                    <div class="col-md-12">
                        <textarea class="form-control" type="text" name="description"><?php echo htmlspecialchars (json_decode($row['description'])); ?></textarea>
                    </div>
                    
                </div>
            </div>
       
</div>

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

 <script>
$(document).ready(function() {
   
    function initializeTinyMCE() {
        tinymce.init({
            selector: 'textarea', // Select all textarea elements
            plugins: [
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown',
                'importword', 'exportword', 'exportpdf'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
            exportpdf_converter_options: { 'format': 'Letter', 'margin_top': '1in', 'margin_right': '1in', 'margin_bottom': '1in', 'margin_left': '1in' },
            exportword_converter_options: { 'document': { 'size': 'Letter' } },
            importword_converter_options: { 'formatting': { 'styles': 'inline', 'resets': 'inline', 'defaults': 'inline' } },
        });
    }

    // Initialize TinyMCE for existing textareas
    initializeTinyMCE();

    



    // Remove question and decrement the count



});

</script>

 <script>
    $(document).ready(function() {
    $('#edit-book').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        $.ajax({
            url: 'ajax/edit-dictionary.php', // The PHP script to handle the insertion
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
                // $('#edit-books')[0].reset(); // Reset the form
                 window.location.href = 'view-dictionary.php';
            },
            error: function(xhr, status, error) {
                alert('An error occurred while processing your request: ' + error);
            }
        });
    });
});
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.5.0/tinymce.min.js" integrity="sha512-KmEMNDKX2KDYPrBMr2MJj/JLgYK271k+P2341E5wvBMgepz1HS3wpc7r65hDXcp4Ul89omtSKIHxdk8VYHd9ug==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>