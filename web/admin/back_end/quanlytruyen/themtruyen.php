<?php
require_once("../../../database.php");
$conn = connectDatabase(); // Kết nối cơ sở dữ liệu
// Kiểm tra người dùng đã đăng nhập chưa
session_start();
if (!isset($_SESSION['admin_id'])) {
    die("Bạn cần đăng nhập để thêm truyện.");
}

// Lấy user_id từ session
$user_id = $_SESSION['admin_id'];
// Lấy danh sách thể loại
$sqlTheLoai = "SELECT * FROM theloai";
$theloaiList = $conn->query($sqlTheLoai);

// Xử lý form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tentruyen = $_POST["tentruyen"];
    $tacgia = $_POST["tacgia"];
    $motatruyen = $_POST["motatruyen"];
    $idtheloai = $_POST["idtheloai"];
    $noidungtruyen = $_POST["noidungtruyen"];
    $isHot = isset($_POST["is_hot"]) ? 1 : 0; // Checkbox HOT
    $imagePath = '';

    // Xử lý upload ảnh
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] == 0) {
        $targetDir = "../public/images/"; // Đường dẫn tới thư mục lưu ảnh
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $originalFileName = basename($_FILES["image"]["name"]);
        $fileName = time() . "_" . $originalFileName;
        $targetFilePath = $targetDir . $fileName;
    
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFilePath)) {
            $imagePath = $fileName; // Lưu tên file đầy đủ vào database
        } else {
            echo "Lỗi khi tải file ảnh.";
            exit;
        }
    }
    



    // Thêm dữ liệu vào bảng truyen
    $sql = "INSERT INTO truyen (tentruyen, tacgia, motatruyen, idtheloai, noidungtruyen, image_path, is_hot) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $tentruyen, $tacgia, $motatruyen, $idtheloai, $noidungtruyen, $imagePath, $isHot);

    if ($stmt->execute()) {
        $idTruyenMoi = $stmt->insert_id;
        echo "Thêm truyện thành công! <a href='themchuong.php?idtruyen=$idTruyenMoi'>Thêm chương</a>";
        // Thêm thông báo vào bảng thongbao
        // Thêm truyện mới
$tentruyen = $_POST['tentruyen']; // Tên truyện mới
$noidungThongBao = "Truyện mới '$tentruyen' đã được thêm vào hệ thống!";
$queryUsers = "SELECT id FROM users"; // Lấy danh sách ID người dùng
$resultUsers = $conn->query($queryUsers);

while ($user = $resultUsers->fetch_assoc()) {
    $user_id = $user['id'];

    // Kiểm tra nếu thông báo này chưa tồn tại cho người dùng
    $checkExistsQuery = "SELECT id FROM thongbao WHERE user_id = ? AND noidung = ?";
    $checkStmt = $conn->prepare($checkExistsQuery);
    $checkStmt->bind_param("is", $user_id, $noidungThongBao);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows === 0) { // Nếu chưa có thông báo này thì thêm vào
        $queryThongBao = "INSERT INTO thongbao (user_id, noidung, trangthai, ngaytao) VALUES (?, ?, 'unread', NOW())";
        $stmtThongBao = $conn->prepare($queryThongBao);
        $stmtThongBao->bind_param("is", $user_id, $noidungThongBao);
        $stmtThongBao->execute();
    }

    $checkStmt->close();
}

echo "Truyện mới đã được thêm vào và tất cả người dùng sẽ nhận thông báo!";


    } else {
        echo "Lỗi: " . $conn->error;
    }
    $stmt->close();
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm truyện mới</title>
    <style>
        /* General body and layout */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #333;
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
select,
input[type="file"] {
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

a button {
    background-color: #2196F3;
    margin-top: 10px;
}

a button:hover {
    background-color: #1976D2;
}

/* File upload */
#imagePreview {
    display: block;
    margin-top: 10px;
    max-width: 200px;
}

#fileName {
    font-size: 14px;
    color: #666;
}

/* Section style */
section {
    margin-top: 20px;
}

/* Checkbox label */
input[type="checkbox"] {
    margin-top: 10px;
}

/* Styling for the back button */
a {
    text-decoration: none;
}

/* Add spacing between form elements */
form > div {
    margin-bottom: 20px;
}

/* Add some margin and padding to form and buttons */
button, a button {
    width: 100%;
    max-width: 200px;
    margin-top: 10px;
    font-size: 18px;
}

/* Table and links */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

/* Add some extra margin at the top of the page */
h2 {
    margin-top: 0;
}

    </style>
</head>
<body>
    <h2>Thêm truyện mới</h2>
    <form method="POST" enctype="multipart/form-data">
        <!-- Tên truyện -->
        <label for="tentruyen">Tên truyện:</label><br>
        <input type="text" id="tentruyen" name="tentruyen" required><br><br>

        <!-- Tác giả -->
        <label for="tacgia">Tác giả:</label><br>
        <input type="text" id="tacgia" name="tacgia" required><br><br>

        <!-- Mô tả truyện -->
        <label for="motatruyen">Mô tả truyện:</label><br>
        <textarea id="motatruyen" name="motatruyen" rows="4" cols="50" required></textarea><br><br>

        <!-- Thể loại -->
        <label for="idtheloai">Thể loại:</label><br>
        <select id="idtheloai" name="idtheloai" required>
            <?php while ($row = $theloaiList->fetch_assoc()) { ?>
                <option value="<?php echo htmlspecialchars($row['idtheloai']); ?>">
                    <?php echo htmlspecialchars($row['name']); ?>
                </option>
            <?php } ?>
        </select><br><br>

        <!-- Nội dung truyện -->
        <label for="noidungtruyen">Nội dung truyện:</label><br>
        <textarea id="noidungtruyen" name="noidungtruyen" rows="8" cols="50" required></textarea><br><br>

        <!-- Gán HOT -->
        <label for="is_hot">Gán mục HOT:</label><br>
        <input type="checkbox" id="is_hot" name="is_hot" value="1"><br><br>

        <!-- Hình ảnh -->
        <label for="image">Ảnh truyện:</label><br>
        <input type="file" id="image" name="image" accept="image/*" required onchange="previewImage(event)"><br>
        <span id="fileName"></span><br>
        <img id="imagePreview" src="#" alt="Xem trước ảnh" style="display:none; max-width:200px; margin-top:10px;"><br><br>

        <!-- Nút thêm truyện -->
        <button type="submit">Thêm truyện</button><br><br>
        
        <!-- Nút quay lại trang quản lý truyện -->
        <a href="quanlytruyen.php">
            <button type="button">Quay lại trang quản lý truyện</button>
        </a>
    </form>
</body>
</html>
