<?php
session_start();
require_once("database.php");

// Kiểm tra xem ID chương có được cung cấp không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Không có ID chương được cung cấp hoặc ID không hợp lệ.");
}

$idchuong = intval($_GET['id']);
$conn = connectDatabase();

// Kiểm tra kết nối cơ sở dữ liệu
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Chuẩn bị câu lệnh truy vấn để lấy thông tin của chương
$sql = "SELECT c.*, t.tentruyen FROM chuong c JOIN truyen t ON c.idtruyen = t.idtruyen WHERE c.id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Lỗi trong quá trình chuẩn bị truy vấn: " . $conn->error);
}

$stmt->bind_param("i", $idchuong);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Không tìm thấy chương này.");
}

$row = $result->fetch_assoc();

// Lưu truyện vào lịch sử trong session
if (!isset($_SESSION['lichsu'])) {
    $_SESSION['lichsu'] = array();
}
if (!in_array($row['idtruyen'], $_SESSION['lichsu'])) {
    $_SESSION['lichsu'][] = $row['idtruyen'];
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chương <?php echo $row['chapter_number']; ?>: <?php echo htmlspecialchars($row['tenchuong']); ?></title>
    <link rel="stylesheet" href="style.css">
<style>
.navigation {
     display: flex;
    justify-content: space-between;
     margin-top: 20px;
}
.navigation a {
    padding: 10px 20px;
    text-decoration: none;
    color: #fff;
    background-color: #007bff;
    border-radius: 5px;
    transition: background-color 0.3s;
}
.navigation a:hover {
    background-color: #0056b3;
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
<main>
    <h1>Chương <?php echo $row['chapter_number']; ?>: <?php echo htmlspecialchars($row['tenchuong']); ?></h1>
        <div class="chuong-content">
        <?php echo nl2br(htmlspecialchars($row['noidungchuong'])); ?>
        </div>
        <div class="navigation">
        <?php
        $prevChapter = $row['chapter_number'] - 1;
        $nextChapter = $row['chapter_number'] + 1;
        $sqlPrev = "SELECT id FROM chuong WHERE idtruyen = ? AND chapter_number = ?";
        $stmtPrev = $conn->prepare($sqlPrev);
        $stmtPrev->bind_param("ii", $row['idtruyen'], $prevChapter);
        $stmtPrev->execute();
        $resultPrev = $stmtPrev->get_result();
        if ($resultPrev->num_rows > 0) {
        $prevRow = $resultPrev->fetch_assoc();
        echo "<a href='chuong.php?id=" . $prevRow['id'] . "'>Chương trước</a>";
        }
                
         $sqlNext = "SELECT id FROM chuong WHERE idtruyen = ? AND chapter_number = ?";
        $stmtNext = $conn->prepare($sqlNext);
        $stmtNext->bind_param("ii", $row['idtruyen'], $nextChapter);
        $stmtNext->execute();
        $resultNext = $stmtNext->get_result();
        if ($resultNext->num_rows > 0) {
            $nextRow = $resultNext->fetch_assoc();
            echo "<a href='chuong.php?id=" . $nextRow['id'] . "'>Chương tiếp theo</a>";
    }
        ?>
        </div>
        </main>
        <footer>
    <footer>
    <div class="footer-content">
        <div class="footer-left">
            <h3>Truyện Hay TT</h3>
            <p>Giới thiệu | Liên hệ | Điều Khoản | Chính Sách Bảo Mật</p>
            <p>Email: truyenhaytt@gmail.com</p>
            <p>Copyright &copy; 2024 TruyenhayTT</p>
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