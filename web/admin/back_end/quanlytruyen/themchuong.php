<?php
require_once("../../../database.php");

$conn = connectDatabase();

// Lấy ID truyện từ URL
$idtruyen = isset($_GET['idtruyen']) ? intval($_GET['idtruyen']) : 0;

// Kiểm tra nếu ID truyện hợp lệ
if ($idtruyen <= 0) {
    echo "ID truyện không hợp lệ!";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tenchuong = $_POST["tenchuong"];
    $noidungchuong = $_POST["noidungchuong"];

    // Lấy số chương lớn nhất hiện tại của truyện để tạo số chương tiếp theo
    $sqlMaxChapter = "SELECT MAX(chapter_number) AS max_chapter FROM chuong WHERE idtruyen = ?";
    $stmtMaxChapter = $conn->prepare($sqlMaxChapter);
    $stmtMaxChapter->bind_param("i", $idtruyen);
    $stmtMaxChapter->execute();
    $resultMaxChapter = $stmtMaxChapter->get_result();
    $row = $resultMaxChapter->fetch_assoc();
    $next_chapter_number = $row['max_chapter'] + 1; // Tạo số chương tiếp theo

    // Thêm chương mới vào cơ sở dữ liệu
    $sqlChuong = "INSERT INTO chuong (idtruyen, chapter_number, tenchuong, noidungchuong) VALUES (?, ?, ?, ?)";
    $stmtChuong = $conn->prepare($sqlChuong);
    $stmtChuong->bind_param("iiss", $idtruyen, $next_chapter_number, $tenchuong, $noidungchuong);

    if ($stmtChuong->execute()) {
        echo "Thêm chương thành công! <a href='quanlychuong.php?idtruyen=" . htmlspecialchars($idtruyen) . "'>Quay lại quản lý chương</a>";
    } else {
        echo "Lỗi khi thêm chương: " . $conn->error;
    }
    $stmtChuong->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm chương mới</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #218838;
        }

        a {
            display: block;
            text-align: center;
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
            margin-top: 15px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>Thêm chương mới cho truyện</h2>
    <form method="POST">
        <!-- Tên chương -->
        <label for="tenchuong">Tên chương:</label><br>
        <input type="text" id="tenchuong" name="tenchuong" required><br><br>

        <!-- Nội dung chương -->
        <label for="noidungchuong">Nội dung chương:</label><br>
        <textarea id="noidungchuong" name="noidungchuong" rows="10" cols="50" required></textarea><br><br>

        <!-- Nút thêm chương -->
        <button type="submit">Thêm chương</button>
    </form>

    <!-- Nút quay lại trang quản lý truyện -->
    <br><br>
    <a href="quanlytruyen.php">Quay lại trang quản lý truyện</a>
</body>
</html>
