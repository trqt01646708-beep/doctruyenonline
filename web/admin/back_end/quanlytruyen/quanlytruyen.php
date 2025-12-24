<?php
session_start();
require_once("../../../database.php");

if (!isset($_SESSION['admin_id'])) {
    header('location:dangnhap.php');
    exit();
}

$conn = connectDatabase(); // Kết nối cơ sở dữ liệu

// Xử lý cập nhật trạng thái HOT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_hot'])) {
    $idTruyen = $_POST['idtruyen'];
    $isHot = isset($_POST['is_hot']) ? 1 : 0;

    $updateSql = "UPDATE truyen SET is_hot = ? WHERE idtruyen = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("ii", $isHot, $idTruyen);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật trạng thái HOT thành công!');</script>";
        header("Refresh:0");
    } else {
        echo "Lỗi khi cập nhật trạng thái HOT: " . $conn->error;
    }
    $stmt->close();
}

// Lấy danh sách truyện
$sql = "SELECT * FROM truyen";
$truyenList = $conn->query($sql);

if (!$truyenList) {
    die("Lỗi truy vấn: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý truyện</title>
    <style>
        /* General body and layout */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #333;
}

/* Links */
a {
    color: #4CAF50;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

table th, table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

table th {
    background-color: #4CAF50;
    color: white;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table td {
    font-size: 14px;
}

/* Form styling inside table */
form {
    display: inline-block;
    margin: 0;
}

input[type="checkbox"] {
    margin-right: 10px;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #45a049;
}

/* Action links */
td a {
    margin-right: 10px;
    font-size: 14px;
    color: #2196F3;
}

td a:hover {
    text-decoration: underline;
}

/* Back to admin page button */
button {
    background-color: #2196F3;
    color: white;
    border-radius: 4px;
    padding: 10px 15px;
    border: none;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #1976D2;
}

/* Style for alert or messages (optional) */
.alert {
    background-color: #f44336;
    color: white;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 4px;
    text-align: center;
}

    </style>
</head>
<body>
    <h1>Quản lý truyện</h1>
    <a href="themtruyen.php">Thêm truyện mới</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên truyện</th>
                <th>Tác giả</th>
                <th>Mô tả truyện</th>
                <th>Thể loại</th>
                <th>Nội dung truyện</th>
                <th>HOT</th>
                <th>Quản lý chương</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $truyenList->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['idtruyen']); ?></td>
                    <td><?php echo htmlspecialchars($row['tentruyen']); ?></td>
                    <td><?php echo htmlspecialchars($row['tacgia']); ?></td>
                    <td><?php echo htmlspecialchars($row['motatruyen']); ?></td>
                    <td><?php echo htmlspecialchars($row['idtheloai']); ?></td>
                    <td><?php echo htmlspecialchars($row['noidungtruyen']); ?></td>
                    
                    <!-- Hiển thị và chỉnh HOT -->
                    <td>
                        <form method="POST">
                            <input type="hidden" name="idtruyen" value="<?php echo $row['idtruyen']; ?>">
                            <input type="checkbox" name="is_hot" <?php echo $row['is_hot'] ? 'checked' : ''; ?>>
                            <button type="submit" name="update_hot">Cập nhật</button>
                        </form>
                        <?php if ($row['is_hot']) { echo "<strong>HOT</strong>"; } ?>
                    </td>
                    
                    <!-- Quản lý chương -->
                    <td>
                        <a href="quanlychuong.php?idtruyen=<?php echo $row['idtruyen']; ?>">Quản lý chương</a>
                    </td>
                    
                    <!-- Hành động sửa và xóa -->
                    <td>
                        <a href="suatruyen.php?id=<?php echo $row['idtruyen']; ?>">Sửa</a>
                        <a href="xoatruyen.php?id=<?php echo $row['idtruyen']; ?>" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Nút quay lại trang quản trị -->
    <br><br>
    <a href="../../../quantri.php">
        <button>Quay lại trang quản trị</button>
    </a>
</body>
</html>
<?php $conn->close(); ?>
