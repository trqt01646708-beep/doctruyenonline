<?php
// functions.php
include 'db_theloai.php';

function gettheloai() {
    $conn = connectDatabase();
    $sql = "SELECT * FROM theloai";
    $stmt = $conn->prepare($sql);
	if (!$stmt) {
        die("SQL prepare failed: " . $conn->error);
    }    
	$stmt->execute();
    $result = $stmt->get_result();
    $stories = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $stories;
}
?>
