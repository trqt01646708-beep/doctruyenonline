<?php
session_start();
require_once("../../../database.php");

if (!isset($_SESSION['admin_id'])) {
    header('location:dangnhap.php');
    exit();
}

$conn = connectDatabase();

// Lấy ID truyện từ URL
$idtruyen = isset($_GET['idtruyen']) ? intval($_GET['idtruyen']) : 0;

// Debug: check ID truyện
if ($idtruyen === 0) {
    die("ID truyện không hợp lệ hoặc không tồn tại.");
}

// Lấy thông tin truyện
$sqlTruyen = "SELECT tentruyen FROM truyen WHERE idtruyen = ?";
$stmtTruyen = $conn->prepare($sqlTruyen);
$stmtTruyen->bind_param("i", $idtruyen);
$stmtTruyen->execute();
$resultTruyen = $stmtTruyen->get_result();

if ($resultTruyen->num_rows === 0) {
    die("Truyện không tồn tại.");
}
$truyen = $resultTruyen->fetch_assoc();

// Lấy danh sách các chương của truyện
$sqlChuong = "SELECT * FROM chuong WHERE idtruyen = ? ORDER BY chapter_number ASC";
$stmtChuong = $conn->prepare($sqlChuong);
$stmtChuong->bind_param("i", $idtruyen);
$stmtChuong->execute();
$chuongList = $stmtChuong->get_result();

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý chương - <?php echo htmlspecialchars($truyen['tentruyen']); ?></title>
    <style>
        /* General body and layout */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

a {
    color: #4CAF50;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

th, td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ccc;
}

th {
    background-color: #4CAF50;
    color: white;
}

td {
    background-color: #f9f9f9;
}

td a {
    margin-right: 10px;
    color: #2196F3;
    text-decoration: none;
}

td a:hover {
    text-decoration: underline;
}

/* Button styles */
button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049;
}

button:focus {
    outline: none;
}

/* Responsive Design */
@media (max-width: 768px) {
    table {
        font-size: 14px;
    }

    button {
        width: 100%;
        padding: 12px;
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <h1>Quản lý chương cho truyện: <?php echo htmlspecialchars($truyen['tentruyen']); ?></h1>
    <a href="themchuong.php?idtruyen=<?php echo $idtruyen; ?>">Thêm chương mới</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID Chương</th>
                <th>Số chương</th>
                <th>Tên chương</th>
                <th>Nội dung</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $chuongList->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['chapter_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['tenchuong']); ?></td>
                    <td><?php echo htmlspecialchars(substr($row['noidungchuong'], 0, 50)); ?>...</td>
                    <td>
                        <a href="suachuong.php?id=<?php echo $row['id']; ?>">Sửa</a>
                        <a href="xoachuong.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa chương này?')">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- Nút quay lại trang quản lý truyện -->
    <br><br>
    <a href="quanlytruyen.php">
        <button>Quay lại trang quản lý truyện</button>
    </a>
</body>
</html>
