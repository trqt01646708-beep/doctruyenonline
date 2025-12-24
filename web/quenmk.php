<?php
require_once("database.php");

// Kết nối cơ sở dữ liệu với mysqli
$conn = connectDatabase(); // Use the mysqli connection function

if (isset($_POST['submit'])) {
    // Lấy dữ liệu và bảo vệ bằng mysqli
    $email = $_POST['email'];  // Email nhập vào
    $matkhaumoi = $_POST['new_password'];  // Mật khẩu mới nhập từ form

    // Kiểm tra email có tồn tại trong cơ sở dữ liệu
    $checkEmail = "SELECT * FROM users WHERE email = ?";  // Placeholder sử dụng "?" để tránh SQL injection
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);  // Bảo vệ tham số email
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $email_origin = $row['email'];

        // Kiểm tra nếu email khớp
        if ($email === $email_origin) {

            // Cập nhật mật khẩu mới vào cơ sở dữ liệu
            $capnhatmatkhau = "UPDATE users SET matkhau = ? WHERE id = ?";
            $stmt_update = $conn->prepare($capnhatmatkhau);
            $stmt_update->bind_param("si", $matkhaumoi, $id); // Binding parameters (hashed password and user ID)
            
            if ($stmt_update->execute()) {
                $users[] = 'Cập nhật mật khẩu thành công';
            } else {
                $users[] = 'Cập nhật mật khẩu không thành công';
            }
        } else {
            $users[] = 'Email không tồn tại trên hệ thống, vui lòng nhập lại';
        }
    } else {
        $users[] = 'Không tìm thấy người dùng';
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quên Mật Khẩu</title>
<style>
    /* Reset margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f7f7f7;
    color: #333;
    line-height: 1.6;
}

/* Container chính */
.change-password {
    width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

/* Tiêu đề */
.cp-title {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    color: #333;
}

/* Nút quay lại */
.back {
    display: inline-block;
    margin-bottom: 10px;
    text-decoration: none;
    color: #007bff;
    font-size: 14px;
}

.back i {
    margin-right: 5px;
}

/* Nhóm input */
.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
}

input[type="email"]:focus,
input[type="password"]:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

/* Nút submit */
.submit-btn {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    border: none;
    color: #fff;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.submit-btn:hover {
    background-color: #0056b3;
}

/* Thông báo lỗi */
.alert {
    margin-top: 15px;
    padding: 10px;
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    font-size: 14px;
}

.alert p {
    margin: 0;
}

/* Responsive */
@media (max-width: 500px) {
    .change-password {
        width: 95%;
        padding: 15px;
    }

    .cp-title {
        font-size: 20px;
    }
}

</style>
</head>

<body>
<div class="change-password">
    <a href="dangnhap.php" class="back"><i class="fa fa-arrow-left" aria-hidden="true"></i> Quay lại</a>
    <h1 class="cp-title">Quên mật khẩu</h1>
    <form method="POST">
    <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" placeholder="Nhập email..." required>
    </div>
    <div class="form-group">
        <label>Mật khẩu mới</label>
        <input type="password" name="new_password" class="form-control" placeholder="Nhập mật khẩu mới" required>
    </div>
    <input type="submit" name="submit" class="btn btn-primary submit-btn" value="Gửi">
    </form>
    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (!empty($users)): ?>
        <div class="alert">
            <?php foreach ($users as $message): ?>
                <p><?php echo $message; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
