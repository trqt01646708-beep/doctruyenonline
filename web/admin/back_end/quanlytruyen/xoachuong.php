<?php
session_start();
require_once("../../../database.php");

$conn = connectDatabase();

// Lấy ID chương từ URL
$idchuong = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idchuong === 0) {
    die("ID chương không hợp lệ hoặc không tồn tại.");
}

// Lấy thông tin chương để hiển thị tên trước khi xóa
$sqlChuong = "SELECT * FROM chuong WHERE id = ?";
$stmtChuong = $conn->prepare($sqlChuong);
$stmtChuong->bind_param("i", $idchuong);
$stmtChuong->execute();
$resultChuong = $stmtChuong->get_result();

if ($resultChuong->num_rows === 0) {
    die("Chương không tồn tại.");
}

$chuong = $resultChuong->fetch_assoc();

// Xóa chương nếu người dùng xác nhận
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $sqlDelete = "DELETE FROM chuong WHERE id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idchuong);

    if ($stmtDelete->execute()) {
        echo "Xóa chương thành công! <a href='quanlychuong.php?idtruyen=" . htmlspecialchars($chuong['idtruyen']) . "'>Quay lại quản lý chương</a>";
    } else {
        echo "Lỗi khi xóa chương: " . $conn->error;
    }

    $stmtDelete->close();
    $conn->close();
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa chương</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h2 {
            color: #dc3545;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            justify-content: center;
            gap: 10px; /* Khoảng cách giữa nút */
            align-items: center;
            margin-top: 20px;
        }

        button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #c82333;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            color: #007bff;
            font-size: 16px;
            border: 1px solid #007bff;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        a:hover {
            background-color: #007bff;
            color: #fff;
        }

        .link-back {
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Xóa chương: <?php echo htmlspecialchars($chuong['tenchuong']); ?></h2>
    <p>Bạn có chắc chắn muốn xóa chương này không?</p>
    <form method="POST">
        <button type="submit">Xóa chương</button>
        <a href="quanlychuong.php?idtruyen=<?php echo $chuong['idtruyen']; ?>">Hủy bỏ</a> <!-- Quay lại quản lý chương -->
    </form>

    <!-- Nút quay lại trang quản lý truyện -->
    <br>
    <a href="quanlytruyen.php">Quay lại trang quản lý truyện</a> <!-- Quay lại trang quản lý truyện -->
</body>
</html>
