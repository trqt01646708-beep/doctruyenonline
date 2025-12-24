<?php
session_start(); // Khởi tạo session
require_once("database.php"); // Kết nối đến cơ sở dữ liệu
$conn = connectDatabase();

// Kiểm tra nếu có từ khóa tìm kiếm
if (isset($_GET['query'])) {
    $query = $_GET['query'];

    try {
        // Kết nối PDO
        $pdo = new PDO("mysql:host=localhost;dbname=truyenhaytt", "root", ""); // Thay thông tin nếu cần
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Truy vấn tìm kiếm tên truyện
        $sql = "SELECT * FROM truyen WHERE tentruyen LIKE :query";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
        $stmt->execute();

        // Lấy kết quả
        $truyenList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Lỗi kết nối: " . $e->getMessage();
        exit;
    }
} else {
    $truyenList = [];
}

// Hàm để xử lý tên file ảnh
function getDisplayImageName($image_path) {
    return preg_replace('/^\d+_/', '', $image_path);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Kết quả tìm kiếm</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .truyen-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            padding: 20px;
        }
        .truyen-card {
            width: 200px;
            margin: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }
        .truyen-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 5px;
        }
        .truyen-card h3 {
            margin: 10px 0;
            font-size: 16px;
        }
        .truyen-card p {
            font-size: 14px;
            color: #666;
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
    <h1>Kết quả tìm kiếm cho "<?php echo htmlspecialchars($query); ?>"</h1>

    <div class="truyen-list">
        <?php if (!empty($truyenList)): ?>
            <?php foreach ($truyenList as $truyen): ?>
                <div class="truyen-card">
                    <a href="truyen.php?id=<?php echo $truyen['idtruyen']; ?>">
                        <img src="../public/<?php echo htmlspecialchars(getDisplayImageName($truyen['image_path'])); ?>" alt="<?php echo htmlspecialchars($truyen['tentruyen']); ?>">
                        <h3><?php echo htmlspecialchars($truyen['tentruyen']); ?></h3>
                    </a>
                    <p><?php echo htmlspecialchars($truyen['tacgia']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không tìm thấy truyện nào phù hợp.</p>
        <?php endif; ?>
    </div>
</main>

<footer>
    <?php include "footer.php"; ?>
</footer>
</body>
</html>
