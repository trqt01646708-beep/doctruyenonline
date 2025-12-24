<?php
session_start();
require_once("../../../database.php");

if (!isset($_SESSION['admin_id'])) {
    header('location:dangnhap.php');
    exit();
}

$conn = connectDatabase(); // Sử dụng hàm connectDatabase từ database.php

// Lấy danh sách người dùng
$sql = "SELECT * FROM users";
$userList = $conn->query($sql);

if (!$userList) {
    die("Lỗi truy vấn: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý người dùng</title>
    <style>
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
    color: #007BFF;
    text-decoration: none;
    font-size: 16px;
    margin-bottom: 20px;
    display: inline-block;
}

a:hover {
    text-decoration: underline;
}


table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #4CAF50;
    color: #333;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}


a {
    display: inline-block;
    margin-right: 10px;
    padding: 6px 12px;
    border-radius: 4px;
    border: 1px solid #4CAF50;
    color: #2196F3;
    font-size: 14px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

a:hover {
    background-color: #2196F3;
    color: white;
}


button {
    background-color: #007BFF;
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


@media (max-width: 768px) {
    table {
        font-size: 14px;
    }

    th, td {
        padding: 10px;
    }

    button {
        font-size: 14px;
    }
}

    </style>
</head>
<body>
    <h1>Quản lý người dùng</h1>
    <a href="themuser.php">Thêm người dùng mới</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Ngày sinh</th>
                <th>Vai trò</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $userList->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['hoten']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['ngaysinh']); ?></td>
                    <td><?php echo htmlspecialchars($row['idvaitro']); ?></td>
                    <td>
                        <a href="suauser.php?id=<?php echo $row['id']; ?>">Sửa</a>
                        <a href="xoauser.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <!-- Nút quay lại trang quản trị -->
    <br><br>
    <a href="../../../quantri.php">
        <button>Quay lại trang quản trị</button>
    </a>
</body>
</html>
