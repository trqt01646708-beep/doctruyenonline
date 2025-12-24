<?php
session_start();
require_once("database.php");
$conn = connectDatabase(); // Use the MySQLi connection function

// Truy vấn lấy danh sách truyện thuộc thể loại Kiếm Hiệp
$query = "SELECT truyen.*, theloai.name 
          FROM truyen 
          JOIN theloai ON truyen.idtheloai = theloai.idtheloai
          WHERE theloai.name = 'Trọng sinh'"; 
// Thực thi truy vấn
$result = $conn->query($query);

// Kiểm tra nếu có dữ liệu trả về
if ($result->num_rows > 0) {
    $truyenList = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $truyenList = [];
}

$conn->close();

// Hàm để xử lý tên file ảnh
function getDisplayImageName($image_path) {
    return preg_replace('/^\d+_/', '', $image_path);
}

// Hàm để kiểm tra sự tồn tại của file ảnh
function imageExists($image_path) {
    $full_path = realpath(__DIR__ . '/../public/' . $image_path);
    return $full_path && file_exists($full_path) && is_file($full_path);
}
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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Truyện Trọng Sinh - Truyện Hay TT</title>
    <link rel="stylesheet" href="style.css" />
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
        <h1>Danh sách Truyện Trọng Sinh</h1>
        <div class="truyen-list">
            <?php if (empty($truyenList)): ?>
                <p>Hiện tại không có truyện ngôn tình nào.</p>
            <?php else: ?>
                <?php foreach ($truyenList as $truyen): ?>
                    <div class="truyen-card">
                        <a href="truyen.php?id=<?php echo $truyen['idtruyen']; ?>">
                            <?php
                            $image_name = getDisplayImageName($truyen['image_path']);
                            $image_path = '../public/' . $image_name;
                            if (imageExists($image_name)):
                            ?>
                                <img class="truyen-image" src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($truyen['tentruyen']); ?>">
                            <?php else: ?>
                                <img class="truyen-image" src="../public/">
                            <?php endif; ?>
                            <div class="truyen-info">
                                <h3><?php echo htmlspecialchars($truyen['tentruyen']); ?></h3>
                                <p><?php echo htmlspecialchars($truyen['tacgia']); ?></p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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


