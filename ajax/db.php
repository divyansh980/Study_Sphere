<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "study_sphere_db";
    
    $conn = new mysqli($servername, $username, $password, $database);
    
    if($conn->connect_error){
        die("connection failed: " . $conn->connect_error);
    }
    
?>
