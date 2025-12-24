<?php
session_start();
require_once("../../../database.php");

// Kiểm tra quyền truy cập
if (!isset($_SESSION['admin_id'])) {
    header('location:dangnhap.php');
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = connectDatabase();

// Kiểm tra có ID truyện không
if (isset($_GET['id'])) {
    $idtruyen = $_GET['id'];

    // Xóa tất cả các chương liên quan đến truyện
    $deleteChaptersSql = "DELETE FROM chuong WHERE idtruyen = ?";
    $chapterStmt = $conn->prepare($deleteChaptersSql);
    $chapterStmt->bind_param("i", $idtruyen);
    $chapterStmt->execute();
    $chapterStmt->close();

    // Xóa truyện theo ID
    $deleteSql = "DELETE FROM truyen WHERE idtruyen = ?";
    $stmt = $conn->prepare($deleteSql);
    $stmt->bind_param("i", $idtruyen);
    
    if ($stmt->execute()) {
        echo "Xóa truyện thành công! <br>";
        // Thêm nút quay lại trang quản lý truyện
        echo '<a href="quanlytruyen.php">Quay lại trang quản lý truyện</a>';
    } else {
        echo "Có lỗi xảy ra khi xóa truyện! <br>";
        // Thêm nút quay lại trang quản lý truyện
        echo '<a href="quanlytruyen.php">Quay lại trang quản lý truyện</a>';
    }
    $stmt->close();
} else {
    echo "Không có truyện để xóa! <br>";
    // Thêm nút quay lại trang quản lý truyện
    echo '<a href="quanlytruyen.php">Quay lại trang quản lý truyện</a>';
}

$conn->close();
?>
<head>
    <style>
        /* General body and layout */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

/* Message styling */
.message {
    text-align: center;
    padding: 20px;
    font-size: 18px;
    margin-top: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

/* Links styling */
a {
    display: inline-block;
    margin-top: 20px;
    color: #2196F3;
    text-decoration: none;
    font-size: 16px;
    padding: 10px 20px;
    border-radius: 4px;
    border: 1px solid #2196F3;
    transition: background-color 0.3s ease;
}

a:hover {
    background-color: #2196F3;
    color: white;
}

/* Container styling */
.container {
    max-width: 800px;
    margin: 0 auto;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

button {
    background-color: #FF6347;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #ff4500;
}

button:focus {
    outline: none;
}

    </style>
</head>
