<?php
include "ajax/db.php";
$email = $_SESSION['email'];

// Prepare the SQL statement to fetch all data
$sql = "SELECT * FROM user2 WHERE email='$email'"; // Fetching id along with questions
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$class = json_decode($row['class']);
$subjectArray = json_decode($row['subject'], true);
$class = $class[0];

// Prepare the SQL statement to fetch books based on class and subjects
$subjectList = implode("','", $subjectArray); // Prepare the subject list for SQL IN clause
$sqlBooks = "SELECT book, subject FROM addbook WHERE class='$class' AND subject IN ('$subjectList') GROUP BY book, subject";
$resultBooks = $conn->query($sqlBooks);

// Initialize an associative array to store subjects and their corresponding books
$subjectBooks = [];

// Fetch the books and organize them by subject
while ($rowBook = $resultBooks->fetch_assoc()) {
    $subject = $rowBook['subject'];
    $book = $rowBook['book'];

    // If the subject doesn't exist in the array, initialize it
    if (!isset($subjectBooks[$subject])) {
        $subjectBooks[$subject] = [];
    }

    // Add the book to the corresponding subject
    $subjectBooks[$subject][] = $book;
}
?>

<header class="main-header">
    <div class="d-flex align-items-center logo-box justify-content-start d-md-none d-block">
        <!-- Logo -->
        <a href="index.php" class="logo">
            <!-- logo-->
            <div class="logo-mini w-100">
                <span class="light-logo"><img src="..\assets\images\mylogo\image (1).png" alt="logo"></span>
                
            </div>
            
        </a>
    </div>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <div class="app-menu">
            <ul class="header-megamenu nav">
                <li class="btn-group nav-item">
                    <a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light"
                        data-toggle="push-menu" role="button">
                        <i class="icon-Menu"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                </li>

            </ul>
        </div>


    </nav>
</header>

<aside class="main-sidebar">
    <!-- sidebar-->
    <section class="sidebar position-relative">
        <div class="d-flex align-items-center logo-box justify-content-start d-md-block d-none">
            <!-- Logo -->
            <a href="index.php" class="logo">
                <!-- logo-->
                <div class="logo-mini">
                    <span class="light-logo"><img src="..\assets\images\mylogo\image (1).png" alt="logo"></span>
                </div>

            </a>
        </div>
        <div class="user-profile my-15 px-20 py-10 b-1 rounded10 mx-15">
            <div class="d-flex align-items-center justify-content-between">
                <div class="image d-flex align-items-center">
                    <img src="images/avatar/avatar-13.png" class="rounded-0 me-10" alt="User Image">
                    <div>
                        <p class="mb-0 fw-600" style="font-size: 12px;">
                            <?php echo $email;?>
                        </p>
                        <p class="mb-0" style="font-size: 12px;">Student</p>
                    </div>
                </div>
                <div class="info">
                    <a class="dropdown-toggle p-15 d-grid" data-bs-toggle="dropdown" href="#"></a>
                    <div class="dropdown-menu dropdown-menu-end">

                        <a class="dropdown-item" href="logout.php"><i class="ti-lock"></i> Logout</a>
                        <a class="dropdown-item" href="../edit-profile.php?email=<?php echo $email;?>"><i class="fa-solid fa-pen-to-square"></i>Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="multinav">
            <div class="multinav-scroll" style="height: 97%;">
                <!-- sidebar menu-->
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="header">Main Menu</li>
                    <li>
                        <a href="index.php">
                            <i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <?php foreach ($subjectBooks as $subject => $books): ?>
                            <li class="treeview">
                                <a href="#">
                                    <i class="fa-solid fa-book"></i>
                                    <span><?php echo htmlspecialchars($subject); ?></span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span></i>
                                    
                                </a>
                                <ul class="treeview-menu">
                                    <?php foreach ($books as $book): ?>
                                    <li>
                                       
                                        <a href="view-book.php?book=<?php echo urlencode($book); ?>&subject=<?php echo $subject; ?>&class=<?php echo $class; ?>">
                                             <i class="icon-Commit"><span class="path1"></span><span class="path2"></span></i>
                                            <?php echo htmlspecialchars($book); ?>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <?php endforeach; ?>
                        
                </ul>

                <div class="sidebar-widgets">
                    
                    <div class="copyright text-center m-25">
                        <p><strong class="d-block">Study Sphere</strong> ©
                            <script>document.write(new Date().getFullYear())</script> All Rights Reserved
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</aside>