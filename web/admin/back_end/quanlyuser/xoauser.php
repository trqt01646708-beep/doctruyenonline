<?php
include '../../../database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];  // Lấy id người dùng từ URL

    // Kết nối cơ sở dữ liệu
    $conn = connectDatabase();

    // Xóa người dùng
    $sql = "DELETE FROM users WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Người dùng đã bị xóa!";
    } else {
        echo "Lỗi: " . $conn->error;
    }

    // Đóng kết nối
    $conn->close();
} else {
    echo "Không tìm thấy ID người dùng!";
}
?>

<!-- Nút quay lại trang quản lý người dùng -->
<br><br>
<a href="quanlyuser.php">
    <button>Quay lại trang quản lý người dùng</button>
</a>
