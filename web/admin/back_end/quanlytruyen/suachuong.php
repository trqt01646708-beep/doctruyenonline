<?php
session_start();
require_once("../../../database.php");

$conn = connectDatabase();

// Lấy ID chương từ URL
$idchuong = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idchuong === 0) {
    die("ID chương không hợp lệ hoặc không tồn tại.");
}

// Lấy thông tin chương hiện tại
$sqlChuong = "SELECT * FROM chuong WHERE id = ?";
$stmtChuong = $conn->prepare($sqlChuong);
$stmtChuong->bind_param("i", $idchuong);
$stmtChuong->execute();
$resultChuong = $stmtChuong->get_result();

if ($resultChuong->num_rows === 0) {
    die("Chương không tồn tại.");
}

$chuong = $resultChuong->fetch_assoc();

// Cập nhật chương nếu người dùng gửi dữ liệu
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tenchuong = $_POST["tenchuong"];
    $noidungchuong = $_POST["noidungchuong"];

    $sqlUpdate = "UPDATE chuong SET tenchuong = ?, noidungchuong = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssi", $tenchuong, $noidungchuong, $idchuong);

    if ($stmtUpdate->execute()) {
        echo "Cập nhật chương thành công! <a href='quanlychuong.php?idtruyen=" . htmlspecialchars($chuong['idtruyen']) . "'>Quay lại quản lý chương</a>";
    } else {
        echo "Lỗi khi cập nhật chương: " . $conn->error;
    }

    $stmtUpdate->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa chương</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        a {
            display: block;
            text-align: center;
            color: #28a745;
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
    <h2>Sửa chương: <?php echo htmlspecialchars($chuong['tenchuong']); ?></h2>
    <form method="POST">
        <!-- Tên chương -->
        <label for="tenchuong">Tên chương:</label><br>
        <input type="text" id="tenchuong" name="tenchuong" value="<?php echo htmlspecialchars($chuong['tenchuong']); ?>" required><br><br>

        <!-- Nội dung chương -->
        <label for="noidungchuong">Nội dung chương:</label><br>
        <textarea id="noidungchuong" name="noidungchuong" rows="10" cols="50" required><?php echo htmlspecialchars($chuong['noidungchuong']); ?></textarea><br><br>

        <!-- Nút sửa chương -->
        <button type="submit">Cập nhật chương</button>
    </form>

    <!-- Nút quay lại trang quản lý truyện -->
    <br>
    <a href="quanlytruyen.php">Quay lại trang quản lý truyện</a> <!-- Liên kết quay lại trang quản lý truyện -->
</body>
</html>
