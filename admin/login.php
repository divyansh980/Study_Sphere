<?php
// session_start();

// if (isset($_SESSION['admin_id'])) {
//     header('location: index.php');
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="..\assets\images\mylogo\favicon.png">

    <title>Log in </title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="src/css/vendors_css.css">

    <!-- Style-->
    <link rel="stylesheet" href="src/css/style.css">
    <link rel="stylesheet" href="src/css/skin_color.css">

</head>

<body class="hold-transition theme-primary bg-img" style="background-image: url(images/auth-bg/bg-1.jpg)">

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <h2 class="text-primary">Let's Get Started</h2>
                                <p class="mb-0">Sign in to continue to CRMi.</p>
                            </div>
                            <div class="p-40">
                                <form id="loginForm" method="post">
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-user"></i></span>
                                            <input type="text" class="form-control ps-15 bg-transparent" name="username" placeholder="Username" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent"><i class="ti-lock"></i></span>
                                            <input type="password" class="form-control ps-15 bg-transparent" name="password" placeholder="Password" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-danger mt-10">SIGN IN</button>
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
    <script src="src/js/vendors.min.js"></script>
    <script src="src/js/pages/chat-popup.js"></script>
    <script src="assets/icons/feather-icons/feather.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <script>
    $(document).ready(function () {
        $('#loginForm').on('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission
            var formData = $(this).serialize(); // Serialize the form data

            $.ajax({
                type: 'POST',
                url: 'ajax/login-process.php', // URL to the login process PHP script
                data: formData,
                success: function (response) {
                    // Display the response in an alert
                    alert(response);
                    // Optionally, you can redirect or take other actions based on the response
                    if (response.includes("successful")) {
                        window.location.href = "index.php"; // Redirect on success
                    }
                },
                error: function () {
                    alert('An error occurred while processing your request.');
                }
            });
        });
    });
</script>
</body>
</html>