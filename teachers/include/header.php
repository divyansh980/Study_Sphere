<?php
session_start(); // Ensure session is started
include "ajax/db.php"; // Include your database connection file

$email = $_SESSION['email'];

// Prepare the SQL statement to fetch all data
$sql = "SELECT * FROM user2 WHERE email='$email'"; // Fetching id along with questions
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if ($row) {
    $subject = json_decode($row['subject']);
    $classArray = json_decode($row['class'], true);
    $subject = $subject[0];

    // Prepare the SQL statement to fetch books based on class and subjects
    $classList = implode("','", $classArray); // Prepare the subject list for SQL IN clause
    $sqlBooks = "SELECT class, book FROM addbook WHERE subject='$subject' AND class IN ('$classList') GROUP BY class, book";
    $resultBooks = $conn->query($sqlBooks);

    // Check if the books query returns any results
    if ($resultBooks->num_rows > 0) {
        // Initialize an associative array to store classes and their corresponding books
        $classBooks = [];

        // Fetch the books and organize them by class
        while ($rowBook = $resultBooks->fetch_assoc()) {
            $class = $rowBook['class'];
            $book = $rowBook['book'];

            // If the class doesn't exist in the array, initialize it
            if (!isset($classBooks[$class])) {
                $classBooks[$class] = [];
            }

            // Add the book to the corresponding class
            $classBooks[$class][] = $book; // Corrected this line
        }
    } else {
        echo "No books found for the subject: $subject and classes: $classList.";
    }
} else {
    echo "No user data found.";
}
?>

<header class="main-header">
    <div class="d-flex align-items-center logo-box justify-content-start d-md-none d-block">    
        <a href="index.php" class="logo">
            <div class="logo-mini w-100">
                <span class="light-logo"><img src="..\assets\images\mylogo\image (1).png" alt="logo"></span>
                <span class="dark-logo"><img src="..\assets\images\mylogo\image (1).png" alt="logo"></span>
            </div>
        </a>    
    </div>   
    <nav class="navbar navbar-static-top">
        <div class="app-menu">
            <ul class="header-megamenu nav">
                <li class="btn-group nav-item">
                    <a href="#" class="waves-effect waves-light nav-link push-btn btn-primary-light" data-toggle="push-menu" role="button">
                        <i class="icon-Menu"><span class="path1"></span><span class="path2"></span></i>
                    </a>
                </li>
            </ul> 
        </div>
    </nav>
</header>

<aside class="main-sidebar">
    <section class="sidebar position-relative">
        <div class="d-flex align-items-center logo-box justify-content-start d-md-block d-none">    
            <a href="index.php" class="logo">
                <div class="logo-mini">
                    <span class="light-logo"><img src="..\assets\images\mylogo\image (1).png" alt="logo"></span>
                </div>
            </a>    
        </div> 
        <div class="user-profile my-15 px-20 py-10 b-1 rounded10 mx-15">
            <div class="d-flex align-items-center justify-content-between">            
                <div class="image d-flex align-items-center">
                    <img src="images/avatar/avatar-13.png" class="rounded-0 me-10" alt="User  Image">
                    <div>
                        <p class="mb-0 fw-600" style="font-size:10px;"><?php echo htmlspecialchars($_SESSION['email']); ?></p>
                        <p class="mb-0" style="font-size:10px;">Teacher</p>
                    </div>
                </div>
                <div class="info">
                    <a class="dropdown-toggle p-15 d-grid" data-bs-toggle="dropdown" href="#"></a>
                    <div class=" dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="logout.php"><i class="ti-lock"></i>Logout</a>
                         <a class="dropdown-item" href="../edit-profile.php?email=<?php echo $email;?>"><i class="fa-solid fa-pen-to-square"></i>Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="multinav">
            <div class="multinav-scroll" style="height: 97%;">    
                <ul class="sidebar-menu" data-widget="tree">    
                    <li class="header">Main Menu</li>
                    <li>
                        <a href="index.php">
                            <i class="icon-Layout-4-blocks"><span class="path1"></span><span class="path2"></span></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="question-papers.php">
                            <i class="fa-solid fa-file-circle-question"></i>
                            <span>Question Paper</span>
                        </a>
                    </li>
                    <?php foreach ($classBooks as $class => $books): ?>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa-solid fa-chalkboard-user" ></i>
                                <span><?php echo htmlspecialchars($class); ?></span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" >
                                <?php foreach ($books as $book): ?>
                                    <li>
                                        <a href="view-book.php?book=<?php echo urlencode($book); ?>&subject=<?php echo $subject; ?>&class=<?php echo $class; ?>">
                                            <i class="icon-Commit" style="padding-right: 0px;padding-left: 0px;"><span class="path1"></span><span class="path2"></span></i>
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
                        <p><strong class="d-block">Study Sphere</strong> © <script>document.write(new Date().getFullYear())</script> All Rights Reserved</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</aside>
