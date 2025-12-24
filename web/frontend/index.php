<?php
require_once("../Database.php"); // Kết nối cơ sở dữ liệu
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Truyện Hay TT</title>
    <link href="../style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    
    <header>
        <div class="logo" width = 10px >
            <img src="../../public/anhlogo.png" alt="Logo" />
        </div>
        <ul class="menu">
            <li><a href="./Trangchu.html">Trang chủ</a></li>
            <li><a href="./danhsach.php">Danh sách</a></li>
            <li><a href="#">Thể loại</a></li>
            <li><a href="#">Theo dõi</a></li>
            <li><a href="#">Lịch sử</a></li>
            <li><a href="#">Thông báo</a></li>
            <li><a href="#">Tài khoản</a></li>
        </ul>
      <div class="timkiem">
            <input type="text" placeholder="Tìm kiếm">
            <img class="icon" src="../public/timkiem.png" alt="Tìm kiếm" />
        </div>
    </header>

    <div class="content">
        <h1>Chào mừng bạn đến với Truyện Hay TT</h1>
        <div class="story-list">
            <?php
            // Lấy danh sách truyện từ database
            $query = "SELECT * FROM truyen LIMIT 10";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='story-item'>";
                    echo "<h3><a href='story.php?id=" . $row['id'] . "'>" . htmlspecialchars($row['Têntruyện']) . "</a></h3>";
                    echo "<p>Thể loại: " . htmlspecialchars($row['genre']) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Không có truyện nào!</p>";
            }
            ?>
        </div>
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
