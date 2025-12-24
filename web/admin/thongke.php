<?php
session_start();
require_once("../database.php");
$conn = connectDatabase();

// Xử lý dữ liệu bộ lọc
$whereClauses = [];
$params = [];
$types = "";

// Lọc theo tên truyện
if (!empty($_GET['ten_truyen'])) {
    $whereClauses[] = "ten_truyen LIKE ?";
    $params[] = "%" . $_GET['ten_truyen'] . "%";
    $types .= "s";
}

// Lọc theo khoảng thời gian
if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $whereClauses[] = "cap_nhat_lan_cuoi BETWEEN ? AND ?";
    $params[] = $_GET['start_date'];
    $params[] = $_GET['end_date'];
    $types .= "ss";
}

// Tạo câu truy vấn SQL
$query = "SELECT truyen_id, ten_truyen, SUM(so_luot_doc) AS tong_luot_doc, MAX(cap_nhat_lan_cuoi) AS cap_nhat_moi_nhat 
          FROM truyen_thong_ke";

if (!empty($whereClauses)) {
    $query .= " WHERE " . implode(" AND ", $whereClauses);
}

$query .= " GROUP BY truyen_id";

// Thêm điều kiện lọc số lượt đọc vào mệnh đề HAVING
$havingClauses = [];
if (!empty($_GET['min_read'])) {
    $havingClauses[] = "SUM(so_luot_doc) >= ?";
    $params[] = $_GET['min_read'];
    $types .= "i";
}
if (!empty($_GET['max_read'])) {
    $havingClauses[] = "SUM(so_luot_doc) <= ?";
    $params[] = $_GET['max_read'];
    $types .= "i";
}

if (!empty($havingClauses)) {
    $query .= " HAVING " . implode(" AND ", $havingClauses);
}

$query .= " ORDER BY tong_luot_doc DESC";
// Thực hiện truy vấn có tham số
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê lượt đọc</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }
        input, button {
            padding: 8px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .back-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            background: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<h1>Thống kê tổng số lượt đọc</h1>

<!-- Form lọc dữ liệu -->
<form method="GET">
    <input type="text" name="ten_truyen" placeholder="Tên truyện" value="<?= isset($_GET['ten_truyen']) ? htmlspecialchars($_GET['ten_truyen']) : '' ?>">
    <input type="date" name="start_date" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : '' ?>">
    <input type="date" name="end_date" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : '' ?>">
    <input type="number" name="min_read" placeholder="Lượt đọc tối thiểu" value="<?= isset($_GET['min_read']) ? $_GET['min_read'] : '' ?>">
    <input type="number" name="max_read" placeholder="Lượt đọc tối đa" value="<?= isset($_GET['max_read']) ? $_GET['max_read'] : '' ?>">
    <button type="submit">Lọc</button>
    <a href="thongke.php"><button type="button">Reset</button></a>
</form>

<table>
    <tr>
        <th>ID Truyện</th>
        <th>Tên Truyện</th>
        <th>Tổng Lượt Đọc</th>
        <th>Cập Nhật Mới Nhất</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['truyen_id']; ?></td>
        <td><?php echo htmlspecialchars($row['ten_truyen']); ?></td>
        <td><strong><?php echo $row['tong_luot_doc']; ?></strong></td>
        <td><?php echo $row['cap_nhat_moi_nhat']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<a href="../quantri.php" class="back-btn">Quay lại trang quản trị</a>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
