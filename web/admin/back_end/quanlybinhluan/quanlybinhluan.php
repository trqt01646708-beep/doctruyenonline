<?php
session_start();
require_once("../../../database.php");
$conn = connectDatabase();
if (!isset($_SESSION['admin_id'])) {
    header('location:dangnhap.php');
    exit();
}

// Xử lý xóa bình luận nếu có yêu cầu
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $query_delete = "DELETE FROM binhluan WHERE id = ?";
    $stmt_delete = $conn->prepare($query_delete);
    $stmt_delete->bind_param("i", $delete_id);
    if ($stmt_delete->execute()) {
        echo "<script>alert('Đã xóa bình luận.');</script>";
    } else {
        echo "<script>alert('Xóa bình luận thất bại.');</script>";
    }
}

// Lấy danh sách bình luận
$query = "SELECT binhluan.*, truyen.tentruyen, users.hoten 
          FROM binhluan 
          JOIN truyen ON binhluan.idtruyen = truyen.idtruyen 
          JOIN users ON binhluan.user_id = users.id 
          ORDER BY binhluan.ngaydang DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Bình luận</title>
   <style>
    

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f9;
    color: #333;
    line-height: 1.6;
}


header {
    background-color: #4CAF50;
    color: #fff;
    padding: 10px 20px;
    text-align: center;
}

header h1 {
    margin: 0;
    font-size: 24px;
}


main {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}


table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    background-color: #fff;
    border: 1px solid #ddd;
}

table th, table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #4CAF50;
    color: #fff;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #f1f1f1;
}


a {
    color: #007BFF;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}


footer {
    text-align: center;
    padding: 10px 20px;
    background-color: #007BFF;
    color: #fff;
    margin-top: 20px;
}


button {
    background-color: #007BFF;
    color: #fff;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
}

button:hover {
    background-color: #0056b3;
}


.alert {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid transparent;
    border-radius: 5px;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-error {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}


@media (max-width: 768px) {
    table {
        font-size: 14px;
    }

    table th, table td {
        padding: 8px;
    }

    header, footer {
        padding: 10px;
        text-align: center;
    }
}


   </style>
</head>
<body>
<header>
    <h1>Quản lý Bình luận</h1>
</header>
<main>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Truyện</th>
                <th>Người dùng</th>
                <th>Nội dung</th>
                <th>Ngày đăng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['tentruyen']); ?></td>
                        <td><?php echo htmlspecialchars($row['hoten']); ?></td>
                        <td><?php echo htmlspecialchars($row['noidung']); ?></td>
                        <td><?php echo htmlspecialchars($row['ngaydang']); ?></td>
                        <td>
                            <a href="quanlybinhluan.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc chắn muốn xóa bình luận này?');">Xóa</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Chưa có bình luận nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
    <a href="../../../quantri.php">
        <button>Quay lại trang quản trị</button>
    </a>
</body>
</html>
