<?php
session_start();
require_once("database.php");
$conn = connectDatabase(); // Kết nối đến cơ sở dữ liệu

// Kiểm tra nếu người dùng chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("Bạn cần đăng nhập để thêm truyện vào yêu thích.");
}
// Xử lý xóa truyện khỏi danh sách yêu thích
if (isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $idTruyenToRemove = intval($_GET['remove']);
    if (($key = array_search($idTruyenToRemove, $_SESSION['yeuthich'])) !== false) {
        unset($_SESSION['yeuthich'][$key]);
        $_SESSION['yeuthich'] = array_values($_SESSION['yeuthich']); // Reindex the array
        echo "<script>alert('Truyện đã được xóa khỏi danh sách yêu thích.');</script>";
    }
}

// Khởi tạo mảng yêu thích trong session nếu chưa có
if (!isset($_SESSION['yeuthich'])) {
    $_SESSION['yeuthich'] = array();
}

// Kiểm tra nếu có truyện cần thêm vào danh sách yêu thích
if (isset($_GET['idtruyen']) && is_numeric($_GET['idtruyen'])) {
    $idtruyen = intval($_GET['idtruyen']);

    // Kiểm tra xem truyện đã có trong danh sách yêu thích chưa
    if (!in_array($idtruyen, $_SESSION['yeuthich'])) {
        $_SESSION['yeuthich'][] = $idtruyen;  // Thêm truyện vào danh sách yêu thích
        echo "<script>alert('Truyện đã được thêm vào danh sách yêu thích.');</script>";
    } else {
        echo "<script>alert('Truyện này đã có trong danh sách yêu thích của bạn.');</script>";
    }
}

// Lấy danh sách truyện yêu thích từ session
$yeuthichList = array();
if (!empty($_SESSION['yeuthich'])) {
    $yeuthichList = $_SESSION['yeuthich'];
}

// Lấy thông tin chi tiết của các truyện yêu thích, bao gồm ảnh và các chương
$truyenList = array();
$chuongList = array();
if (!empty($yeuthichList)) {
    $query = "SELECT t.idtruyen, t.tentruyen, t.image_path, c.id AS chuong_id, c.tenchuong
              FROM truyen t
              LEFT JOIN chuong c ON t.idtruyen = c.idtruyen
              WHERE t.idtruyen IN (" . implode(",", $yeuthichList) . ")";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $truyenList[$row['idtruyen']] = [
                'idtruyen' => $row['idtruyen'],
                'tentruyen' => $row['tentruyen'],
                'image_path' => $row['image_path']
            ];
            if ($row['chuong_id'] !== null) {
                $chuongList[$row['idtruyen']][] = [
                    'id' => $row['chuong_id'],
                    'tenchuong' => $row['tenchuong']
                ];
            }
        }
    }
}



?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách yêu thích</title>
    <link rel="stylesheet" href="style.css">
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
<h2>Truyện yêu thích</h2>
        <?php if (!empty($truyenList)): ?>
            <div class="yeuthich-grid">
                <?php foreach ($truyenList as $truyen): ?>
                    <div class="truyen-card">
                        <div class="truyen-image">
                            <?php
                            $image_name = preg_replace('/^\d+_/', '', $truyen['image_path']);
                            ?>
                            <img src="../public/<?php echo htmlspecialchars($image_name); ?>" alt="<?php echo htmlspecialchars($truyen['tentruyen']); ?>" />
                        </div>
                        <div class="truyen-info">
                            <h3><a href="truyen.php?id=<?php echo $truyen['idtruyen']; ?>"><?php echo htmlspecialchars($truyen['tentruyen']); ?></a></h3>
                            <?php if (isset($chuongList[$truyen['idtruyen']])): ?>
                                <ul class="chuong-list">
                                    <?php 
                                    $displayCount = min(3, count($chuongList[$truyen['idtruyen']]));
                                    for ($i = 0; $i < $displayCount; $i++): 
                                        $chuong = $chuongList[$truyen['idtruyen']][$i];
                                    ?>
                                        <li><a href="chuong.php?id=<?php echo $chuong['id']; ?>">Chương <?php echo htmlspecialchars($chuong['tenchuong']); ?></a></li>
                                    <?php endfor; ?>
                                    <?php if (count($chuongList[$truyen['idtruyen']]) > 3): ?>
                                        <li>...</li>
                                    <?php endif; ?>
                                </ul>
                            <?php else: ?>
                                <p>Chưa có chương</p>
                            <?php endif; ?>
                            <a href="yeuthich.php?remove=<?php echo $truyen['idtruyen']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc muốn xóa truyện này khỏi danh sách yêu thích?');">Xóa khỏi yêu thích</a>
                        </divdiv>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Chưa có truyện yêu thích nào.</p>
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

<?php
// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>
