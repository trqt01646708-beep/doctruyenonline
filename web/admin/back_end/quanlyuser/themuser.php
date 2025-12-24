<?php
include '../../../database.php';
$conn = connectDatabase();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $ngaysinh = $_POST['ngaysinh'];
    $matkhau = $_POST['matkhau'];
    $idvaitro = $_POST['idvaitro'];
    // Kiểm tra xem email đã tồn tại chưa
    $checkEmailSQL = "SELECT * FROM users WHERE email = ?";
    $checkStmt = $conn->prepare($checkEmailSQL);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('Email đã tồn tại, vui lòng chọn email khác.');</script>";
    } else {
        // Thêm người dùng mới
        $sql = "INSERT INTO users (hoten, email, ngaysinh, matkhau, idvaitro) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $hoten, $email, $ngaysinh, $matkhau, $idvaitro);

        if ($stmt->execute()) {
            echo "<script>alert('Thêm người dùng thành công!'); window.location.href='quanlyuser.php';</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
    $checkStmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm User Mới</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 0 auto;
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="password"],
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="submit"], .back-button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .back-button {
            background-color: #f44336;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-right: 10px;
        }
        .back-button:hover {
            background-color: #d32f2f;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
    <h2>Thêm User Mới</h2>
    <form method="POST" action="">
    <label for="hoten">Họ tên:</label>
    <input type="text" name="hoten" required>

    <label for="email">Email:</label>
    <input type="email" name="email" required>

    <label for="ngaysinh">Ngày sinh:</label>
    <input type="date" name="ngaysinh" required>

    <label for="matkhau">Mật khẩu:</label>
    <input type="password" name="matkhau" required>

    <label for="idvaitro">Vai trò:</label>
    <select name="idvaitro" required>
        <option value="1">admin</option>
        <option value="2">user</option>
    </select>

    <div class="button-group">
        <a href="quanlyuser.php" class="back-button">Quay lại</a>
        <input type="submit" value="Thêm người dùng">
    </div>
</form>
    </div>
</body>
</html>
