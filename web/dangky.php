<?php
// Kết nối tới cơ sở dữ liệu
require_once("database.php");
$conn = connectDatabase();

if (isset($_POST['submit'])) {

    // Lấy dữ liệu từ form
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $ngaysinh = $_POST['ngaysinh'];
    $matkhau = $_POST['matkhau'];
    $cpass = $_POST['cpassword'];
    $idvaitro = 2; // Gán giá trị mặc định cho vai trò

    // Kiểm tra ngày sinh có hợp lệ hay không
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $ngaysinh)) {
        echo "Ngày sinh không đúng định dạng (YYYY-MM-DD)!";
    } elseif ($matkhau != $cpass) {
        echo "Mật khẩu không khớp!";
    } else {
        // Kiểm tra xem email đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Tài khoản đã tồn tại!";
        } else {
            // Thêm người dùng mới vào cơ sở dữ liệu
            $stmt = $conn->prepare("INSERT INTO users (hoten, email, ngaysinh, matkhau, idvaitro) 
                                    VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $hoten, $email, $ngaysinh, $matkhau, $idvaitro);

            if ($stmt->execute()) {
                echo "Đăng ký thành công!";
                header("Location: dangnhap.php");
                exit();
            } else {
                echo "Lỗi: Không thể thêm dữ liệu.";
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Tài Khoản</title>
   <style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Registration form container */
.registration-form {
    background-color: #fff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 400px;
    text-align: center;
}

/* Title */
h2 {
    color: #333;
    margin-bottom: 20px;
}

/* Form group */
.form-group {
    margin-bottom: 20px;
    text-align: left;
}

/* Labels */
label {
    font-size: 14px;
    color: #555;
    display: block;
    margin-bottom: 6px;
}

/* Input fields */
input[type="text"],
input[type="email"],
input[type="date"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

/* Submit button */
.submit-btn {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #45a049;
}

/* Link */
p {
    margin-top: 20px;
    font-size: 14px;
}

p a {
    color: #4CAF50;
    text-decoration: none;
}

p a:hover {
    text-decoration: underline;
}

/* Thông báo lỗi */
.error-message {
    color: red;
    font-size: 14px;
    margin-top: 10px;
}
</style>
</head>
<body>
    <div class="registration-form">
        <h2>Đăng Ký Tài Khoản</h2>

        <form action="dangky.php" method="POST">
            <div class="form-group">
                <label for="hoten">Họ và Tên</label>
                <input type="text" id="hoten" name="hoten" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="ngaysinh">Ngày Sinh</label>
                <input type="date" id="ngaysinh" name="ngaysinh" required>
            </div>

            <div class="form-group">
                <label for="matkhau">Mật Khẩu</label>
                <input type="password" id="matkhau" name="matkhau" required>
            </div>

            <div class="form-group">
                <label for="cpassword">Xác Nhận Mật Khẩu</label>
                <input type="password" id="cpassword" name="cpassword" required>
            </div>

            <button type="submit" name="submit" class="submit-btn">Đăng Ký</button>
        </form>

        <p>Đã có tài khoản? <a href="dangnhap.php">Đăng Nhập</a></p>
    </div>
</body>
</html>