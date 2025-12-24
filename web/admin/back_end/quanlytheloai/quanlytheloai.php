<?php
session_start();
require_once("../../../database.php");
// Kết nối đến cơ sở dữ liệu
$conn = connectDatabase();

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
// Xử lý thêm thể loại
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $add_sql = "INSERT INTO theloai (name) VALUES ('$name')";
    if (!mysqli_query($conn, $add_sql)) {
        die("Lỗi thêm thể loại: " . mysqli_error($conn));
    } else {
        $_SESSION['success_message'] = "Thêm thể loại thành công!";
        header("Location: quanlytheloai.php");
        exit();
    }
}

if (!isset($_SESSION['admin_id'])) {
    header('location:dangnhap.php');
    exit();
}

// Xử lý xóa thể loại
if (isset($_GET['delete_id'])) {
    $idtheloai = $_GET['delete_id'];
    $delete_sql = "DELETE FROM theloai WHERE idtheloai = $idtheloai";
    mysqli_query($conn, $delete_sql);
    header("Location: quanlytheloai.php");
}

// Xử lý thêm thể loại
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $add_sql = "INSERT INTO theloai (name) VALUES ('$name')";
    if (mysqli_query($conn, $add_sql)) {
        $_SESSION['success_message'] = "Thêm thể loại thành công!";
        header("Location: quanlytheloai.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Lỗi: " . mysqli_error($conn);
        header("Location: quanlytheloai.php");
        exit();
    }
}

// Hiển thị dữ liệu
$result = mysqli_query($conn, "SELECT * FROM theloai");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý thể loại</title>
        <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        padding: 20px;
    }
    h1 {
        text-align: center;
        color: #333;
    }
    form {
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    form input[type="text"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    form button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        width: 100%;
    }
    table {
        width: 100%;
        margin: 20px 0;
        border-collapse: collapse;
    }
    table th, table td {
        padding: 10px;
        text-align: left;
        border: 1px solid #ddd;
    }
    table th {
        background-color: #4CAF50;
        color: white;
    }
    table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    a button {
        display: block;
        margin: 10px auto;
        padding: 10px 20px;
        background-color: #2196F3;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
    }
    a button:hover {
        background-color: #1976D2;
    }

    </style>
</head>
<body>
    <h1>Quản lý Thể Loại</h1>

    
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Tên thể loại" required>
        <button type="submit" name="add">Thêm Thể Loại</button>
    </form>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Tên Thể Loại</th>
            <th>Hành Động</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['idtheloai']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td>
                <a href="suatheloai.php?id=<?php echo $row['idtheloai']; ?>">Sửa</a> |
                <a href="?delete_id=<?php echo $row['idtheloai']; ?>" onclick="return confirm('Xác nhận xóa?');">Xóa</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
     <!-- Nút quay lại trang quản trị -->
     <br><br>
    <a href="../../../quantri.php">
        <button>Quay lại trang quản trị</button>
    </a>
</body>
</html>
<?php mysqli_close($conn); ?>
