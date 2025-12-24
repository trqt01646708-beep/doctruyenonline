<?php
function connectDatabase() {
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "truyenhaytt"; 
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    return $conn;
}
?>