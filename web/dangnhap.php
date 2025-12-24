<?php
session_start();
require_once 'database.php';
$conn = connectDatabase();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $matkhau = isset($_POST['matkhau']) ? $_POST['matkhau'] : '';

    if (empty($matkhau)) {
        echo "Vui lòng nhập mật khẩu!";
        exit();
    }

    // Chuẩn bị và thực thi truy vấn lấy thông tin user
    $stmt = $conn->prepare("SELECT id, hoten, matkhau, idvaitro FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Kiểm tra mật khẩu (không mã hóa)
        if ($matkhau === $row['matkhau']) {
            $_SESSION['hoten'] = $row['hoten'];
            $_SESSION['idvaitro'] = $row['idvaitro'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['hoten'];

            // ✅ Chuyển hướng người dùng sau khi đăng nhập thành công
            if ($row['idvaitro'] == "admin") {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_name'] = $row['hoten'];
                $_SESSION['admin_email'] = $email;
                header("Location: quantri.php");
            } else {
                header("Location: Trangchu.php");
            }
            exit();
        } else {
            echo "Mật khẩu không đúng!";
        }
    } else {
        echo "Email không tồn tại!";
    }
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.login-container {
    background-color: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 300px;
}

h2 {
    color: #333;
    margin-bottom: 20px;
    text-align: center;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    display: block;
    font-size: 14px;
    color: #555;
    margin-bottom: 6px;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 20px;
    box-sizing: border-box;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

.error-message {
    color: red;
    font-size: 14px;
    text-align: center;
    margin-bottom: 10px;
}

.forgot-password {
    text-align: center;
    margin-top: 15px;
}

.forgot-password a {
    color: #4CAF50;
    text-decoration: none;
    font-size: 14px;
}

.forgot-password a:hover {
    text-decoration: underline;
}
.form-footer {
    margin-top: 20px;
    text-align: center;
}

.form-footer a {
    display: block;
    margin-bottom: 10px;
    text-decoration: none;
    width:300px;
}

.form-footer a:not(.register-button) {
    color: #4CAF50; /* Màu cho link "Quên mật khẩu?" */
}

.register-button {
    display: inline-block;
   
    background-color: #4CAF50;
    color: white; /* Màu chữ trắng */
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s, color 0.3s;
    font-weight: bold; /* Làm đậm chữ để dễ đọc hơn trên nền xanh */
   
}

.register-button:hover {
    background-color: #45a049;
    color: #f0f0f0; /* Màu chữ hơi nhạt đi khi hover để tạo hiệu ứng */
}

/* Đảm bảo nút đăng nhập và đăng ký có style giống nhau */
input[type="submit"], .register-button {
    width: 100%;
    padding:10px 0px 10px 0px;
    font-size: 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover, .register-button:hover {
    background-color: #45a049;
}


    </style>
</head>
<body>
<div class="login-container">
        <h2>Đăng nhập</h2>
        <form action="" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="matkhau">Mật khẩu:</label>
            <input type="password" name="matkhau" required>

            <input type="submit" name="submit" value="Đăng nhập">
        </form>
        <div class="form-footer">
            <a href="quenmk.php">Quên mật khẩu?</a>
            <a href="dangky.php" class="register-button">Đăng ký tài khoản mới</a>
        </div>
    </div>
</body>
</html>
