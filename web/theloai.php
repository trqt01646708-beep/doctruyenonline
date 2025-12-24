<?php
session_start();
require_once("database.php");
$conn = connectDatabase();

// Khởi tạo biến $theloai và $truyenList
$current_theloai = null;
$truyenList = [];

// Lấy ID thể loại từ URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idtheloai = intval($_GET['id']);
    
    // Truy vấn thể loại hiện tại
    $query = "SELECT * FROM theloai WHERE idtheloai = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $idtheloai);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $current_theloai = $result->fetch_assoc();

        // Truy vấn danh sách truyện thuộc thể loại
        $query_truyen = "SELECT idtruyen, tentruyen, image_path FROM truyen WHERE idtheloai = ?";
        $stmt_truyen = $conn->prepare($query_truyen);
        $stmt_truyen->bind_param("i", $idtheloai);
        $stmt_truyen->execute();
        $truyenList = $stmt_truyen->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo $current_theloai ? htmlspecialchars($current_theloai['name']) : 'Thể loại không tồn tại'; ?></title>
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
    
    <main>
        <?php if ($current_theloai !== null): ?>
            <h1>Thể loại: <?php echo htmlspecialchars($current_theloai['name']); ?></h1>
            <div class="truyen-list">
    <?php if (!empty($truyenList)): ?>
        <?php foreach ($truyenList as $truyen): ?>
            <div class="truyen-card">
            <a href="truyen.php?id=<?php echo $truyen['idtruyen']; ?>">
                    <?php
                    $image_name = preg_replace('/^\d+_/', '', $truyen['image_path']);
                    ?>
                    <img src="../public/<?php echo htmlspecialchars($image_name); ?>" alt="<?php echo htmlspecialchars($truyen['tentruyen']); ?>" />
                    <h3><?php echo htmlspecialchars($truyen['tentruyen']); ?></h3>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Chưa có truyện nào trong thể loại này.</p>
    <?php endif; ?>
</div>

        <?php else: ?>
            <h1>Thể loại không tồn tại</h1>
            <p>Xin lỗi, không tìm thấy thể loại bạn yêu cầu.</p>
        <?php endif; ?>
    </main>

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
                Trang web này cung cấp nội dung truyện chỉ với mục đích giải trí và không chịu trách nhiệm về bất kỳ nội dung quảng cáo, liên kết của bên thứ ba hiển thị trên trang web của chúng tôi.
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
