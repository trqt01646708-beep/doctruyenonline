<?php
include '../../../database.php';

$conn = connectDatabase();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $ngaysinh = $_POST['ngaysinh'];
    $idvaitro = $_POST['idvaitro'];

    // Kiểm tra xem mật khẩu có được cung cấp không
    if (!empty($_POST['matkhau'])) {
        $matkhau = $_POST['matkhau']; // Mật khẩu không được mã hóa
        $sql = "UPDATE users SET hoten=?, email=?, ngaysinh=?, matkhau=?, idvaitro=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $hoten, $email, $ngaysinh, $matkhau, $idvaitro, $id);
    } else {
        // Nếu không có mật khẩu mới, không cập nhật mật khẩu
        $sql = "UPDATE users SET hoten=?, email=?, ngaysinh=?, idvaitro=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $hoten, $email, $ngaysinh, $idvaitro, $id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thông tin người dùng thành công!'); window.location.href='quanlyuser.php';</script>";
    } else {
        echo "<script>alert('Lỗi: " . $stmt->error . "');</script>";
    }
    $stmt->close();
} else if (isset($_GET['id'])) {
    // Lấy thông tin người dùng cần sửa
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Không có ID người dùng được cung cấp.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Thông Tin Người Dùng</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 20px;
    background-color: #f4f4f4;
}

h2 {
    text-align: center;
    color: #333;
}

form {
    max-width: 500px;
    margin: 0 auto;
    background: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

input[type="text"],
input[type="email"],
input[type="date"],
input[type="password"],
select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 14px;
}

input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

a {
    display: block;
    text-align: center;
    margin-top: 20px;
    text-decoration: none;
    color: #f44336;
    font-weight: bold;
}

a:hover {
    color: #d32f2f;
}

form select {
    padding: 10px;
    font-size: 14px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 15px;
}

form .error {
    color: red;
    font-size: 12px;
    margin-top: -10px;
    margin-bottom: 10px;
}

    </style>
</head>
<body>
    <h2>Sửa Thông Tin Người Dùng</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
        
        <label for="hoten">Họ tên:</label>
        <input type="text" name="hoten" value="<?php echo htmlspecialchars($user['hoten']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label for="ngaysinh">Ngày sinh:</label>
        <input type="date" name="ngaysinh" value="<?php echo $user['ngaysinh']; ?>" required><br>

        <label for="matkhau">Mật khẩu mới (để trống nếu không thay đổi):</label>
        <input type="password" name="matkhau"><br>

        <label for="idvaitro">Vai trò:</label>
        <select name="idvaitro" required>
        <option value="1">admin</option>
        <option value="2">user</option>
    </select>


        <input type="submit" value="Cập nhật">
    </form>
    <a href="quanlyuser.php">Quay lại danh sách người dùng</a>
</body>
</html>
