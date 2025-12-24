<?php 
session_start();
require_once("database.php");
$conn = connectDatabase(); // Kết nối đến cơ sở dữ liệu

// Lấy danh sách truyện từ cơ sở dữ liệu
$query = "SELECT * FROM truyen"; // Truy vấn lấy tất cả truyện
$stmt = $conn->prepare($query);
$stmt->execute();
$truyenList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Lấy kết quả dưới dạng mảng
// Truy vấn lấy danh sách thể loại
$query_theloai = "SELECT * FROM theloai";
$result_theloai = $conn->query($query_theloai);
// Lấy trang hiện tại từ GET (mặc định là trang 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

// Truy vấn lấy danh sách truyện (có giới hạn)
$query = "SELECT * FROM truyen LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $items_per_page, $offset);
$stmt->execute();
$truyenList = $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Lấy kết quả dưới dạng mảng
// Truy vấn tổng số truyện
$totalQuery = "SELECT COUNT(*) AS total FROM truyen";
$result = $conn->query($totalQuery);
$totalRows = $result->fetch_assoc()['total'];

// Tính tổng số trang
$totalPages = ceil($totalRows / $items_per_page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Truyện Hay TT</title>
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
        <ul class="submenu">
            <li><a href="dangnhap.php">Đăng nhập</a></li>
            <li><a href="dangky.php">Đăng ký</a></li>
        </ul>
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
    <h1>Danh sách Truyện Hay</h1>
    <div class="truyen-list">
        <?php foreach ($truyenList as $truyen): ?>
            <div class="truyen-card">
                <a href="truyen.php?id=<?php echo $truyen['idtruyen']; ?>">
                    <?php
                    $image_name = preg_replace('/^\d+_/', '', $truyen['image_path']);
                    ?>
                    <img src="../public/<?php echo htmlspecialchars($image_name); ?>" alt="<?php echo htmlspecialchars($truyen['tentruyen']); ?>" />
                    <h3><?php echo htmlspecialchars($truyen['tentruyen']); ?></h3>
                </a>





                <!-- Lấy danh sách chương từ cơ sở dữ liệu -->
                <?php
                $chaptersQuery = "SELECT * FROM chuong WHERE idtruyen = ? ORDER BY chapter_number ASC";
                $chapterStmt = $conn->prepare($chaptersQuery);
                $chapterStmt->bind_param("i", $truyen['idtruyen']);
                $chapterStmt->execute();
                $chapterResult = $chapterStmt->get_result();
                
                if ($chapterResult && $chapterResult->num_rows > 0): ?>
                    <ul class="chapters">
                        <?php while ($chapter = $chapterResult->fetch_assoc()): ?>
                            <li><a href="Chuong.php?id=<?php echo $chapter['id']; ?>">Chương <?php echo $chapter['chapter_number']; ?> </a></li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <p>Không có chương nào cho truyện này.</p>
                <?php endif; ?>
                
                <?php $chapterStmt->close(); ?>
            </div>
        <?php endforeach; ?>
    </div> 
    <!-- Hiển thị phân trang -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
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