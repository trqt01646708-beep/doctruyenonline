<?php
require_once("../Database.php");

if (isset($_GET['id'])) {
    $IDtruyện = intval($_GET['IDtruyện']);
    $query = "SELECT * FROM truyen WHERE IDtruyện = $IDtruyện";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $story = $result->fetch_assoc();
    } else {
        die("Không tìm thấy truyện!");
    }
} else {
    die("ID truyện không hợp lệ!");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($story['Têntruyện']); ?></title>
    <link href="../style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <header>
        <div class="logo">
            <img src="../../public/anhlogo.png" alt="Logo" />
        </div>
        <ul class="menu">
            <li><a href="#">Trang chủ</a></li>
            <li><a href="#">Danh sách</a></li>
            <li><a href="#">Thể loại</a></li>
            <li><a href="#">Theo dõi</a></li>
            <li><a href="#">Lịch sử</a></li>
            <li><a href="#">Thông báo</a></li>
            <li><a href="#">Tài khoản</a></li>
        </ul>
      <div class="timkiem">
            <form action="search.php" method="GET">
                <input type="text" name="search" placeholder="Tìm kiếm" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>" />
                <img class="icon" src="../public/timkiem.png" alt="Tìm kiếm" />
            </form>
        </div>
    </header>

    <div class="content">
        <h1><?php echo htmlspecialchars($story['title']); ?></h1>
        <p><strong>Thể loại:</strong> <?php echo htmlspecialchars($story['genre']); ?></p>
        <p><?php echo nl2br(htmlspecialchars($story['content'])); ?></p>
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
                <a href="#">ngôn tình</a>
                <a href="#">Truyện ngôn tình</a>
                <a href="#">Truyện xuyên nhanh</a>
                <a href="#">Truyện kiếm hiệp hay</a>
                <a href="#">Truyện tiên hiệp hay</a>
            </div>
        </div>
    </div>
</footer>
</body>
</html>
