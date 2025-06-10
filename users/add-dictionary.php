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
    

    <title>Add Dictionary</title>
    
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
				  
				
				<div class="box-body">
				    <form id="insert-book" class="row">
				    <input type="hidden" value="<?php echo $_SESSION['user_id']?>" name="userid">
		            <?php include'ajax/userinfo.php';?>

                    <!-- Topic Input -->
                    <div class="form-group col-md-6">
                        <label class="col-form-label">Word</label>
                        <input class="form-control" name="word" required>
                             
                    </div>
				    
					
					
						<div class="book-cont">
					<div class="form-group row">
						<label class="col-form-label col-md-12">Description</label>
						<div class="col-md-12">
							<textarea class="form-control book-textarea" id="description" type="text" name="description"></textarea>
						</div>
					</div>
					
					</div>
                    <!--<button type="button" id="addmore" class="mb-2 btn btn-success">Add Book in Another Language</button>-->
					
					
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
	  <script>
$(document).ready(function() {
   

    // Function to initialize TinyMCE on textareas
    function initializeTinyMCE(selector = 'textarea') {
        tinymce.init({
            selector: selector,
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

    // Initialize TinyMCE for existing textareas on page load
    initializeTinyMCE();
    initializeTinyMCE('.book-textarea');
});


</script>

<script>
    $(document).ready(function() {
        $('#insert-book').on('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Serialize the form data
            var formData = $(this).serialize();

            // Send the AJAX request to add-dictionary.php
            $.ajax({
                url: 'ajax/add-dictionary.php', // The URL to which the request is sent
                type: 'POST', // The HTTP method to use
                data: formData, // The data to send
                success: function(response) {
                    // Handle success
                    alert('Word added successfully!');
                    console.log(response); // Log the response for debugging

                    // Clear the "Word" input field
                    $('input[name="word"]').val('');

                    // Reset the TinyMCE editor content
                    tinymce.get('description').setContent(''); // Use the ID of the textarea
                },
                error: function(xhr, status, error) {
                    // Handle error
                    alert('An error occurred while adding the Word: ' + error);
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
