<?php
session_start();
require_once("database.php");
$conn = connectDatabase();

function getDisplayImageName($image_path) {
    return preg_replace('/^\d+_/', '', $image_path);
}

$truyens_lichsu = array();
if (isset($_SESSION['lichsu']) && !empty($_SESSION['lichsu'])) {
    $ids = implode(',', array_map('intval', $_SESSION['lichsu']));
    $query = "SELECT t.idtruyen, t.tentruyen, t.image_path, t.motatruyen, 
              MAX(c.chapter_number) as last_read_chapter, MAX(c.id) as last_chapter_id
              FROM truyen t
              LEFT JOIN chuong c ON t.idtruyen = c.idtruyen
              WHERE t.idtruyen IN ($ids)
              GROUP BY t.idtruyen
              ORDER BY FIELD(t.idtruyen, $ids)";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $truyens_lichsu[] = $row;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Đọc Truyện</title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <style>
        .lichsu-truyens {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .truyen-item {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        .truyen-image {
            width: 100px;
            height: 150px;
            object-fit: cover;
            margin-right: 20px;
        }
        .truyen-info {
            flex: 1;
        }
        .read-continue {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
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

    <div class="lichsu-truyens">
        <h2>Lịch Sử Đọc Truyện</h2>
        <?php if (empty($truyens_lichsu)): ?>
            <p>Chưa có truyện nào trong lịch sử đọc của bạn.</p>
        <?php else: ?>
            <?php foreach ($truyens_lichsu as $truyen): ?>
                <div class="truyen-item">
                    <img class="truyen-image" src="../public/<?php echo htmlspecialchars(getDisplayImageName($truyen['image_path'])); ?>" alt="<?php echo htmlspecialchars($truyen['tentruyen']); ?>" />
                    <div class="truyen-info">
                        <h3><a href="truyen.php?id=<?php echo $truyen['idtruyen']; ?>"><?php echo htmlspecialchars($truyen['tentruyen']); ?></a></h3>
                        <p><?php echo nl2br(htmlspecialchars(substr($truyen['tentruyen'], 0, 150) . '')); ?></p>
                        <?php if ($truyen['last_read_chapter']): ?>
                            <p>Đã đọc đến: Chương <?php echo htmlspecialchars($truyen['last_read_chapter']); ?></p>
                            <a href="Chuong.php?id=<?php echo $truyen['last_chapter_id']; ?>" class="read-continue">Đọc tiếp</a>
                        <?php else: ?>
                            <p>Chưa đọc chương nào</p>
                            <a href="truyen.php?id=<?php echo $truyen['idtruyen']; ?>" class="read-continue">Bắt đầu đọc</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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

    <?php $conn->close(); ?>
</body>
</html>
