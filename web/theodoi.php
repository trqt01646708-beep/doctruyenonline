<?php 
session_start();
require_once("database.php");
$conn = connectDatabase(); // Kết nối đến cơ sở dữ liệu

// Kiểm tra nếu người dùng đã theo dõi truyện
if (!empty($_SESSION['theodoi'])) {
    $placeholders = str_repeat('?,', count($_SESSION['theodoi']) - 1) . '?';
    $query = "SELECT * FROM truyen WHERE idtruyen IN ($placeholders)";
    
    if ($stmt = $conn->prepare($query)) {
        $types = str_repeat('i', count($_SESSION['theodoi']));  // Giả sử idtruyen là số nguyên (int)
        $stmt->bind_param($types, ...$_SESSION['theodoi']);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $truyens_theodoi = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $truyens_theodoi = [];
    }
} else {
    $truyens_theodoi = [];
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theo Dõi Truyện</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<header>
<div class="logo">
        <img src="../public/anhlogo.png" alt="Logo" />
    </div>
    <ul class="menu">
        <li><a href="Trangchu.php">Trang chủ</a></li>
        <li><a href="HOT.php">HOT</a></li>
        <li><a href="lichsu.php">Lịch sử</a></li>
        <li><a href="yeuthich.php">Yêu thích</a></li>
        <li><a href="thongbao.php">Thông báo</a></li> 
        <li>
    <a href="#" class="has-submenu">Thể loại</a>
    <ul class="submenu">
        <?php
        $query_theloai = "SELECT * FROM theloai";
        $result_theloai = $conn->query($query_theloai);
        while ($theloai = $result_theloai->fetch_assoc()):
        ?>
            <li>
                <a href="theloai.php?id=<?php echo $theloai['idtheloai']; ?>">
                    <?php echo htmlspecialchars($theloai['name']); ?>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</li>
        <li><a href="#" class="has-submenu">Tài khoản</a>
    <div class="account-box">
        <?php if(isset($_SESSION['user_id'])): ?>
            <p>Xin chào, <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span></p>
            <a href="dangxuat.php" class="delete-btn">Đăng xuất</a>
        <?php else: ?>
            <ul class="submenu">
            <a href="dangnhap.php">Đăng nhập</a>
            <a href="dangky.php">Đăng ký</a>
            </ul>
        <?php endif; ?>
    </div>
</li>
        <div class="timkiem">
    <form method="GET" action="timkiem1.php">
        <input type="text" name="query" placeholder="Tìm kiếm tên truyện" required>
        <button type="submit">
            <img class="icon" src="../public/timkiem.png" alt="Tìm kiếm" />
        </button>
    </form>
</div>
    </ul>
</header>

    <div class="theodoi-truyens">
        <h2>Truyện Bạn Đang Theo Dõi</h2>
        <ul>
            <?php if (!empty($truyens_theodoi)): ?>
                <?php foreach ($truyens_theodoi as $truyen): ?>
                    <li>
                        <a href="truyen.php?id=<?php echo $truyen['idtruyen']; ?>">
                            <?php echo htmlspecialchars($truyen['tentruyen']); ?> <!-- Tên truyện -->
                        </a>
                        <p><?php echo htmlspecialchars($truyen['mota']); ?></p> <!-- Mô tả truyện -->
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Hiện tại bạn chưa theo dõi truyện nào.</p>
            <?php endif; ?>
        </ul>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-left">
                <h3>Truyện Hay TT</h3>
                <p>Giới thiệu | Liên hệ | Điều Khoản | Chính Sách Bảo Mật</p>
                <p>Email: truyenhaytt@gmail.com</p>
                <p>Copyright © 2024 TruyenhayTT</p>
            </div>
            <div class="footer-right">
                <h4>Miễn trừ trách nhiệm</h4>
                <p>
                    Trang web này cung cấp nội dung truyện tranh chỉ với mục đích giải trí và không chịu trách nhiệm về bất kỳ nội dung quảng cáo, liên kết của bên thứ ba hiển thị trên trang web của chúng tôi.
                </p>
            </div>
            <div class="footer-tags">
                <div class="tags">
                <a href="#">Truyện chữ</a>
                <a href="trongsinh.php">Trọng sinh</a>
                <a href="ngotinh.php">Truyện ngôn tình</a>
                <a href="dothi.php">Truyện đô thị</a>
                <a href="kiemhiep.php">Truyện kiếm hiệp hay</a>
                <a href="tienhiep.php">Truyện tiên hiệp hay</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>

<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
