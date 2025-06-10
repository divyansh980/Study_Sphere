<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "shas1125_easypadhai";
    
    $conn = new mysqli($servername, $username, $password, $database);
    // echo $conn;
    if($conn->connect_error){
        die("connection failed: " . $conn->connect_error);
    }
    // echo 'Connected Successfully';
?>