<?php
    $conn = mysqli_connect("localhost", "root", "", "recipe");
    if ($conn->connect_errno) {
        echo "Failed to connect to MySQL: " . $conn->connect_error;
        exit();
    }
    $db = $conn->query("SELECT * FROM users");
?>
