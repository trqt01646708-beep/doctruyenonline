<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location:dangnhap.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Quản Trị</title>
    <style>
      
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

/* Navigation bar styles */
nav {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

nav a {
    color: white;
    text-decoration: none;
    margin-right: 20px;
    font-size: 16px;
}

nav a:hover {
    text-decoration: underline;
}

nav .account-box {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

nav .account-box p {
    margin: 5px 0;
    font-size: 14px;
}

nav .account-box a {
    background-color: #4CAF50;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    margin-top: 10px;
}

nav .account-box a:hover {
    background-color: #45a049;
}

/* Add some padding for the main content */
main {
    padding: 20px;
}

/* Optional: Styling for additional elements, if needed */
h1 {
    text-align: center;
    color: #333;
}


    </style>
</head>
<body>
    <nav>
        <a href="admin/back_end/quanlytruyen/quanlytruyen.php">Quản lý truyện</a>
        <a href="admin/back_end/quanlyuser/quanlyuser.php">Quản lý người dùng</a>
        <a href="admin/back_end/quanlytheloai/quanlytheloai.php">Quản lý thể loại</a>
        <a href="admin/back_end/quanlybinhluan/quanlybinhluan.php">Quản lý bình luận</a>
        <a href="admin/thongke.php">Quản lý thống kê</a>
        <div class="account-box">
            <p>Tên người dùng : <span><?php echo htmlspecialchars($_SESSION['admin_name']); ?></span></p>
            <p>Email : <span><?php echo htmlspecialchars($_SESSION['admin_email']); ?></span></p>
            <a href="dangxuat.php" class="delete-btn">Đăng xuất</a>
        </div>
    </nav>
</body>
</html>
