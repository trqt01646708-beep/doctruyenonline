<?php
session_start();
require_once("../../../database.php");

// Kiểm tra quyền truy cập
if (!isset($_SESSION['admin_id'])) {
    header('location:dangnhap.php');
    exit();
}

// Kết nối cơ sở dữ liệu
$conn = connectDatabase();

// Kiểm tra xem có ID truyện được truyền vào không
if (isset($_GET['id'])) {
    $idtruyen = $_GET['id'];

    // Truy vấn lấy thông tin truyện
    $sql = "SELECT * FROM truyen WHERE idtruyen = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idtruyen);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra xem truyện có tồn tại không
    if ($result->num_rows > 0) {
        $truyen = $result->fetch_assoc();
    } else {
        echo "Truyện không tồn tại!";
        exit();
    }
    $stmt->close();
}

// Cập nhật thông tin truyện khi form được submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $tenTruyen = $_POST['tentruyen'];
    $tacGia = $_POST['tacgia'];
    $moTaTruyen = $_POST['motatruyen'];
    $noiDungTruyen = $_POST['noidungtruyen'];
    $theLoai = $_POST['theloai'];

    // Cập nhật dữ liệu vào cơ sở dữ liệu
    $updateSql = "UPDATE truyen SET tentruyen = ?, tacgia = ?, motatruyen = ?, noidungtruyen = ?, idtheloai = ? WHERE idtruyen = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssii", $tenTruyen, $tacGia, $moTaTruyen, $noiDungTruyen, $theLoai, $idtruyen);

    if ($updateStmt->execute()) {
        echo "Cập nhật truyện thành công!";
    } else {
        echo "Có lỗi xảy ra khi cập nhật dữ liệu!";
    }
    $updateStmt->close();
}

// Lấy danh sách thể loại
$sqlTheLoai = "SELECT * FROM theloai";
$resultTheLoai = $conn->query($sqlTheLoai);
if (!$resultTheLoai) {
    die("Lỗi truy vấn thể loại: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Truyện</title>
    <style>
        /* General body and layout */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #333;
    margin-bottom: 20px;
}

/* Form container */
form {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

label {
    font-weight: bold;
    color: #333;
}

input[type="text"],
textarea,
select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
}

button {
    background-color: #4CAF50;
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

/* Links styling */
a {
    color: #2196F3;
    text-decoration: none;
    font-size: 16px;
    margin-top: 10px;
}

a:hover {
    text-decoration: underline;
}

/* Add some margin and padding to form and buttons */
button, a {
    width: 100%;
    max-width: 200px;
    margin-top: 20px;
    font-size: 18px;
}

/* Section style */
section {
    margin-top: 20px;
}

/* Add spacing between form elements */
form > div {
    margin-bottom: 20px;
}

/* Adjust textarea sizes */
textarea {
    height: 120px;
}

/* Form field error styling */
input:invalid, textarea:invalid, select:invalid {
    border: 1px solid red;
}

    </style>
</head>
<body>
    <h1>Sửa Truyện</h1>
    <form action="suatruyen.php?id=<?php echo $idtruyen; ?>" method="POST">
        <label for="tentruyen">Tên truyện:</label>
        <input type="text" id="tentruyen" name="tentruyen" value="<?php echo htmlspecialchars($truyen['tentruyen']); ?>" required><br>
        
        <label for="tacgia">Tác giả:</label>
        <input type="text" id="tacgia" name="tacgia" value="<?php echo htmlspecialchars($truyen['tacgia']); ?>" required><br>
        
        <label for="motatruyen">Mô tả truyện:</label>
        <textarea id="motatruyen" name="motatruyen" required><?php echo htmlspecialchars($truyen['motatruyen']); ?></textarea><br>
        
        <label for="noidungtruyen">Nội dung truyện:</label>
        <textarea id="noidungtruyen" name="noidungtruyen" required><?php echo htmlspecialchars($truyen['noidungtruyen']); ?></textarea><br>
        
        <label for="theloai">Thể loại:</label>
        <select id="theloai" name="theloai" required>
            <?php while ($row = $resultTheLoai->fetch_assoc()) { ?>
                <option value="<?php echo $row['idtheloai']; ?>" <?php echo ($row['idtheloai'] == $truyen['idtheloai']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($row['name']); ?>
                </option>
            <?php } ?>
        </select><br>

        <button type="submit">Cập nhật</button>
    </form>

    <!-- Nút quay lại trang quản lý truyện -->
    <br><br>
    <a href="quanlytruyen.php">Quay lại trang quản lý truyện</a>
</body>
</html>
