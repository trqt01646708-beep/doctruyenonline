<?php
session_start();
require_once("database.php");
$conn = connectDatabase();

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("Bạn cần đăng nhập để bình luận.");
}

// Lấy ID truyện từ URL
if (!isset($_GET['idtruyen']) || !is_numeric($_GET['idtruyen'])) {
    die("ID truyện không hợp lệ.");
}
$idtruyen = intval($_GET['idtruyen']);

// Lấy thông tin truyện
$query_truyen = "SELECT tentruyen FROM truyen WHERE idtruyen = ?";
$stmt_truyen = $conn->prepare($query_truyen);
$stmt_truyen->bind_param("i", $idtruyen);
$stmt_truyen->execute();
$truyen = $stmt_truyen->get_result()->fetch_assoc();
if (!$truyen) {
    die("Truyện không tồn tại.");
}

// Xử lý khi người dùng gửi bình luận
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $noidung = trim($_POST['noidung']);
    if (empty($noidung)) {
        echo "<script>alert('Nội dung bình luận không được để trống.');</script>";
    } else {
        $user_id = $_SESSION['user_id'];
        $query_insert = "INSERT INTO binhluan (idtruyen, user_id, noidung, ngaydang) VALUES (?, ?, ?, NOW())";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bind_param("iis", $idtruyen, $user_id, $noidung);
        if ($stmt_insert->execute()) {
            echo "<script>alert('Bình luận của bạn đã được gửi.');</script>";
        } else {
            echo "<script>alert('Gửi bình luận thất bại.');</script>";
        }
    }
}

// Lấy danh sách bình luận của truyện
$query_binhluan = "SELECT binhluan.*, users.username FROM binhluan JOIN users ON binhluan.user_id = users.id WHERE binhluan.idtruyen = ? ORDER BY binhluan.ngaydang DESC";
$stmt_binhluan = $conn->prepare($query_binhluan);
$stmt_binhluan->bind_param("i", $idtruyen);
$stmt_binhluan->execute();
$binhluanList = $stmt_binhluan->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bình luận về truyện: <?php echo htmlspecialchars($truyen['tentruyen']); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Bình luận về truyện: <?php echo htmlspecialchars($truyen['tentruyen']); ?></h1>
</header>
<main>
    <form method="POST">
        <textarea name="noidung" placeholder="Nhập nội dung bình luận..." required></textarea>
        <button type="submit">Gửi bình luận</button>
    </form>

    <h2>Các bình luận</h2>
    <div class="binhluan-list">
        <?php if (!empty($binhluanList)): ?>
            <?php foreach ($binhluanList as $binhluan): ?>
                <div class="binhluan-item">
                    <p><strong><?php echo htmlspecialchars($binhluan['username']); ?></strong>: <?php echo htmlspecialchars($binhluan['noidung']); ?></p>
                    <p><small>Ngày đăng: <?php echo htmlspecialchars($binhluan['ngaydang']); ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có bình luận nào.</p>
        <?php endif; ?>
    </div>
</main>
<footer>
    <p>Bình luận về truyện &copy; 2024</p>
</footer>
</body>
</html>
