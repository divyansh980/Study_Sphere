<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('Client_ID_.apps.googleusercontent.com');  //replace with correct client id
$client->setClientSecret('AAAAAAAAAA-1Px3FfGEEuS0TGj8SBfM8IDZYZrp');  // replace with correct secret id
$client->setRedirectUri('http://localhost/easy-padhai/signup.php');

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // Get user info
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Store the email in the session
    $email = $userInfo->email;
    $_SESSION['email'] = $email;

    // Database connection
    include "ajax/db.php";

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT user_type FROM user2 WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result2 = $stmt->get_result();

    if ($result2->num_rows > 0) {
        // Email exists, fetch usertype
        $row2 = $result2->fetch_assoc();
        $usertype = $row2['user_type'];

        // Forward to the appropriate panel based on usertype
        if ($usertype == 'Student') {
            header("Location: student/index.php");
        } elseif ($usertype == 'Teacher') {
            header("Location: teachers/index.php");
        } else {
            header("Location:index.php");
        }
        exit(); // Stop further execution
    } else {
        $query = "SELECT DISTINCT courses FROM addcourses";
        $result = $conn->query($query);
        $query1 = "SELECT DISTINCT subject FROM subject";
        $result1 = $conn->query($query1);
    }
} else {
    echo "Error: No code received.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../student/images/logo/eplogo.png">
    <link rel="stylesheet" href="sweetalert2.min.css">
    <title>Sign Up</title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="users/src/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="users/src/css/style.css">
    <link rel="stylesheet" href="users/src/css/skin_color.css">
    <style>
      
        .checkcont{
            display:contents; !important;
        }
        
        @media only screen and (max-width: 600px) {
  .checkcont{
            display:block; !important;
        }
}
#regForm {
  background-color: #ffffff;
 
  font-family: Raleway;
 
  
 
}

h1 {
  text-align: center;  
}

input {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
}

/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

button {
  background-color: #04AA6D;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;  
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #04AA6D;
}
        
    </style>
</head>

<body>

    <div class="container" id="form-cont">
        <div class="row align-items-center justify-content-md-center pb-30 pt-30">

            <div class="col-12">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-8 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            
                            <div class="p-40">
                                <form id="regForm" method="post">
                                   <div class="tab">
                                     <h2 class="text-primary text-center">Please Select Your Subject</h2>
                                     <div class="form-group col-md-12">
                                       
                                        <div>
                                            <?php
                                            if ($result1->num_rows > 0) {
                                                echo "<div class='row'>"; // Start a new row
                                                while ($row1 = $result1->fetch_assoc()) {
                                                    echo "<div class='col-md-3'>"; // Create a column for each card
                                                    echo "<div class='card' style='margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);background: azure;'>"; // Card container
                                                    echo "<div class='card-body' style='height: 100px;'>"; // Card body
                                                    echo "<div class='form-check checkcont'>
                                                            <input type='checkbox' class='form-check-input' name='subject[]' oninput='this.className = ''' value='" . htmlspecialchars($row1['subject']) . "' id='subject_" . htmlspecialchars($row1['subject']) . "'>
                                                            <label class='form-check-label' for='subject_" . htmlspecialchars($row1['subject']) . "'>" . htmlspecialchars($row1['subject']) . "</label>
                                                          </div>";
                                                    echo "</div>"; // Close card body
                                                    echo "</div>"; // Close card
                                                    echo "</div>"; // Close the column
                                                }
                                                echo "</div>"; // Close the row
                                            } else {
                                                echo "<div>No Subjects available</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                   </div>
                                   <div class="tab">
                                      <div class="form-group col-md-12">
                                          <h2 class="text-primary text-center">Please Select Your Class</h2>
                                        
                                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email);?>">
                                        <div>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                echo "<div class='row'>"; // Start a new row
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<div class='col-md-3'>"; // Create a column for each card
                                                    echo "<div class='card' style='margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);background: azure;'>"; // Card container
                                                    echo "<div class='card-body' style='height: 100px;'>"; // Card body
                                                    echo "<div class='form-check checkcont'>
                                                            <input type='checkbox' class='form-check-input' oninput='this.className = ''' name='class[]' value='" . htmlspecialchars($row['courses']) . "' id='class_" . htmlspecialchars($row['courses']) . "'>
                                                            <label class='form-check-label' for='class_" . htmlspecialchars($row['courses']) . "'>" . htmlspecialchars($row['courses']) . "</label>
                                                          </div>";
                                                    echo "</div>"; // Close card body
                                                    echo "</div>"; // Close card
                                                    echo "</div>"; // Close the column
                                                }
                                                echo "</div>"; // Close the row
                                            } else {
                                                echo "<div>No Classes available</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                   </div>
                                   
                                    
                                    
                                    
                                    <div style="overflow:auto;">
                                        <div style="float:right;">
                                          <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                                          <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                                        </div>
                                    </div>
                                    
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor JS -->
    <script src="users/src/js/vendors.min.js"></script>
    <script src="users/src/js/pages/chat-popup.js"></script>
    <script src="users/assets/icons/feather-icons/feather.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }
    fixStepIndicator(n);
}

function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    
    // If moving forward (n == 1), validate the current tab
    if (n == 1) {
        // Validate tab1 (Classes)
        if (currentTab === 0) {
            if (!validateTab2()) return false; // Validate only for the first tab
        }
        // Validate tab2 (Subjects)
        else if (currentTab === 1) {
            if (!validateTab1()) return false; // Validate only for the second tab
        }
    } else if (n == -1) {
        // If moving backward, just show the previous tab
        x[currentTab].style.display = "none";
        currentTab = currentTab + n;
        showTab(currentTab);
        return; // Exit the function after showing the previous tab
    }

    // Hide the current tab:
    x[currentTab].style.display = "none";
    
    // Increase or decrease the current tab by 1:
    currentTab = currentTab + n;

    // If you have reached the end of the form...
   if (currentTab >= x.length) {
    // Validate the last tab (subjects) before submission
    if (!validateTab2()) return false; // Validate before submission
    
    // AJAX submission
   // AJAX submission
// AJAX submission
var formData = $('#regForm').serialize(); // Serialize form data
$.ajax({
    type: 'POST',
    url: 'ajax/signup.php', // Change this to your server-side script
    data: formData,
    success: function(response) {
        console.log("Response received:", response); // Log the entire response object
        if (response.success) {
            // Hide the form before showing the success message
            $('#form-cont').hide(); // Hide the form

            // Show a success message using Swal
            Swal.fire({
                title: 'Success!',
                text: 'Welcome to Easy Padhai',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Handle success response based on usertype
                    if (response.usertype === 'Student') {
                        window.location.href = 'student/index.php'; // Redirect to student panel
                    } else if (response.usertype === 'Teacher') {
                        window.location.href = 'teachers/index.php'; // Redirect to teacher panel
                    }
                }
            });
        } else {
            console.log("Error response detected:", response); // Log the entire error response
            Swal.fire('Error', response.message || 'Please fill the form correctly.'); // Use a fallback message
            currentTab = 0; // Reset to the first tab
            showTab(currentTab); // Show the first tab
        }
    },
    error: function(xhr, status, error) {
        console.error("AJAX error:", error); // Log AJAX error for debugging
        Swal.fire('Error', 'There was an error submitting the form: ' + error); // Show error message
        currentTab = 0; // Reset to the first tab
        showTab(currentTab); // Show the first tab
    }
});
    return false; // Prevent default form submission
}

    // Otherwise, display the correct tab:
    showTab(currentTab);
}

function validateTab1() {
    var selectedClasses = $('input[name="class[]"]:checked').length;

    // Validation for classes
    if (selectedClasses === 0) {
        Swal.fire('Error', 'Please select at least one class.', 'error');
        return false; // Return false if validation fails
    } 
    return true; // Return true if validation passes
}

function validateTab2() {
    var selectedSubjects = $('input[name="subject[]"]:checked').length;

    // Validation for subjects
    if (selectedSubjects === 0) {
        Swal.fire('Error', 'Please select at least one subject.', 'error');
        return false; // Return false if validation fails
    }
    else if (selectedSubjects > 6) {
        Swal.fire('Error', 'You can select a maximum of 6 Subjects.', 'error');
        return false; // Return false if validation fails
    }
    return true; // Return true if validation passes
}
function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    x[n].className += " active";
}
</script>
<script src="sweetalert2.min.js"></script>


</body>
</html>
