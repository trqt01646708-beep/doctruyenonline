<?php 
session_start();
require_once("database.php");
$conn = connectDatabase(); // Kết nối đến cơ sở dữ liệu
// Hàm để xử lý tên file ảnh
function getDisplayImageName($image_path) {
    return preg_replace('/^\d+_/', '', $image_path);
}
// Lấy thông tin truyện chi tiết từ URL
$truyen_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT * FROM truyen WHERE idtruyen = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $truyen_id);
$stmt->execute();
$truyen = $stmt->get_result()->fetch_assoc();

// Lấy các chương của truyện
$chaptersQuery = "SELECT * FROM chuong WHERE idtruyen = ? ORDER BY chapter_number ASC";
$chapterStmt = $conn->prepare($chaptersQuery);
$chapterStmt->bind_param("i", $truyen_id);
$chapterStmt->execute();
$chapterResult = $chapterStmt->get_result();
// Lấy danh sách bình luận của truyện
$query_binhluan = "SELECT binhluan.*, users.hoten FROM binhluan JOIN users ON binhluan.user_id = users.id WHERE binhluan.idtruyen = ? ORDER BY binhluan.ngaydang DESC";
$stmt_binhluan = $conn->prepare($query_binhluan);
$stmt_binhluan->bind_param("i", $truyen_id);
$stmt_binhluan->execute();
$binhluanList = $stmt_binhluan->get_result()->fetch_all(MYSQLI_ASSOC);

// Xử lý khi người dùng gửi bình luận
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['noidung'])) {
    if (!isset($_SESSION['user_id'])) {
        echo "<script>alert('Bạn cần đăng nhập để bình luận.'); window.location.href = 'dangnhap.php';</script>";
        exit; // Dừng xử lý tiếp theo để không đăng bình luận khi người dùng chưa đăng nhập.
    }
    $noidung = trim($_POST['noidung']);
    if (empty($noidung)) {
        echo "<script>alert('Nội dung bình luận không được để trống.');</script>";
    } else {
        $user_id = $_SESSION['user_id'];
        $query_insert = "INSERT INTO binhluan (idtruyen, user_id, noidung, ngaydang) VALUES (?, ?, ?, NOW())";
        $stmt_insert = $conn->prepare($query_insert);
        $stmt_insert->bind_param("iis", $truyen_id, $user_id, $noidung);
        if ($stmt_insert->execute()) {
            echo "<script>alert('Bình luận của bạn đã được gửi.');</script>";
        } else {
            echo "<script>alert('Gửi bình luận thất bại.');</script>";
        }
    }
}
// Kiểm tra nếu có hành động yêu thích
if (isset($_GET['action']) && $_GET['action'] == 'yeuthich' && isset($_GET['idtruyen']) && is_numeric($_GET['idtruyen'])) {
    $idtruyen = intval($_GET['idtruyen']);

    // Kiểm tra nếu mảng yêu thích chưa được khởi tạo trong session
    if (!isset($_SESSION['yeuthich'])) {
        $_SESSION['yeuthich'] = array();
    }

    // Kiểm tra xem truyện đã có trong danh sách yêu thích chưa
    if (!in_array($idtruyen, $_SESSION['yeuthich'])) {
        $_SESSION['yeuthich'][] = $idtruyen;  // Thêm truyện vào danh sách yêu thích
        echo "<script>alert('Truyện đã được thêm vào danh sách yêu thích.');</script>";
    } else {
        echo "<script>alert('Truyện này đã có trong danh sách yêu thích của bạn.');</script>";
    }
}

// Lấy thông tin truyện chi tiết từ URL
$truyen_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$query = "SELECT * FROM truyen WHERE idtruyen = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $truyen_id);
$stmt->execute();
$truyen = $stmt->get_result()->fetch_assoc();
// Cập nhật hoặc thêm lượt đọc vào bảng truyen_thong_ke
$updateQuery = "INSERT INTO truyen_thong_ke (truyen_id, ten_truyen, so_luot_doc, cap_nhat_lan_cuoi) 
                VALUES (?, ?, 1, NOW()) 
                ON DUPLICATE KEY UPDATE so_luot_doc = so_luot_doc + 1, cap_nhat_lan_cuoi = NOW()";

$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("is", $truyen_id, $truyen['tentruyen']);
$stmt->execute();



?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($truyen['tentruyen']); ?></title>
    <link href="style.css" rel="stylesheet" type="text/css" />
    <style>
        .truyen-detail {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .truyen-image {
            max-width: 300px;
            height: auto;
            margin-bottom: 20px;
        }
        .chapters {
            list-style-type: none;
            padding: 0;
        }
        .chapters li {
            margin-bottom: 10px;
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

    <div class="truyen-detail">
        <h1><?php echo htmlspecialchars($truyen['tentruyen']); ?></h1>
        
        <?php
        $image_name = getDisplayImageName($truyen['image_path']);
        $image_path = "../public/" . $image_name;
        ?>
        <img class="truyen-image" src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($truyen['tentruyen']); ?>" />
        <a href="truyen.php?id=<?php echo $truyen['idtruyen']; ?>&action=yeuthich&idtruyen=<?php echo $truyen['idtruyen']; ?>" class="btn-yeuthich">Yêu thích</a>   
        <p><strong>Tác giả:</strong> <?php echo htmlspecialchars($truyen['tacgia']); ?></p>
        
        <h2>Nội dung truyện:</h2>
        <p><?php echo nl2br(htmlspecialchars($truyen['noidungtruyen'])); ?></p>

        <h2>Danh sách các chương:</h2>
        <ul class="chapters">
            <?php if ($chapterResult && $chapterResult->num_rows > 0): ?>
                <?php while ($chapter = $chapterResult->fetch_assoc()): ?>
                    <li>
    <a href="Chuong.php?id=<?php echo $chapter['id']; ?>">
        Chương <?php echo $chapter['chapter_number']; ?>: 
        <?php echo isset($chapter['title']) ? htmlspecialchars($chapter['title']) : 'Untitled'; ?>
    </a>
</li>

                <?php endwhile; ?>
            <?php else: ?>
                <p>Chưa có chương nào cho truyện này.</p>
            <?php endif; ?>
        </ul>
        <h2>Bình luận:</h2>
    <?php if (isset($_SESSION['user_id'])): ?>
        <form method="POST">
            <textarea name="noidung" placeholder="Nhập bình luận của bạn..." required></textarea>
            <button type="submit">Gửi bình luận</button>
        </form>
    <?php else: ?>
        <p><a href="dangnhap.php">Đăng nhập</a> để bình luận.</p>
    <?php endif; ?>

    <div class="binhluan-list">
        <?php if (!empty($binhluanList)): ?>
            <?php foreach ($binhluanList as $binhluan): ?>
                <div class="binhluan-item">
                    <p><strong><?php echo htmlspecialchars($binhluan['hoten']); ?>:</strong> <?php echo nl2br(htmlspecialchars($binhluan['noidung'])); ?></p>
                    <p><small>Ngày đăng: <?php echo htmlspecialchars($binhluan['ngaydang']); ?></small></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Chưa có bình luận nào.</p>
        <?php endif; ?>
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
</footer>
</body>
</html>

<?php
// Đóng kết nối cơ sở dữ liệu
$chapterStmt->close();
$conn->close();
?>
