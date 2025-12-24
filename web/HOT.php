<?php
require_once("database.php");
$conn = connectDatabase(); // Kết nối cơ sở dữ liệu

// Truy vấn lấy các truyện HOT
$sql = "SELECT t.*, COUNT(c.id) as chapter_count 
        FROM truyen t 
        LEFT JOIN chuong c ON t.idtruyen = c.idtruyen 
        WHERE t.is_hot = 1 
        GROUP BY t.idtruyen";
$result = $conn->query($sql);

function getDisplayImageName($image_path) {
    return preg_replace('/^\d+_/', '', $image_path);
}


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Truyện HOT</title>
    <link rel="stylesheet" href="style.css" />
    <style>
        .truyen-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .truyen-card {
            width: 200px;
            margin: 15px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .truyen-card img {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }
        .truyen-info {
            padding: 10px;
        }
        .truyen-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
    </style>
</head>
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
<body>
    <h2>Danh sách Truyện HOT</h2>
    <div class="truyen-list">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="truyen-card <?php echo ($row['is_hot'] == 1) ? 'hot' : ''; ?>" onclick="openModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">
    <img src="../public/<?php echo htmlspecialchars(getDisplayImageName($row['image_path'])); ?>" alt="<?php echo htmlspecialchars($row['tentruyen']); ?>">
    <div class="truyen-info">
        <h3><?php echo htmlspecialchars($row['tentruyen']); ?></h3>
        <p>Số chương: <?php echo $row['chapter_count']; ?></p>
    </div>
</div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Không có truyện HOT nào.</p>
        <?php endif; ?>
    </div>

    <div id="truyenModal" class="truyen-modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle"></h2>
            <img id="modalImage" src="" alt="" style="max-width: 100%; height: auto;">
            <p id="modalChapters"></p>
            <a id="modalLink" href="">Xem chi tiết</a>
        </div>
    </div>

    <script>
    function openModal(truyen) {
        var modal = document.getElementById("truyenModal");
        var title = document.getElementById("modalTitle");
        var image = document.getElementById("modalImage");
        var chapters = document.getElementById("modalChapters");
        var link = document.getElementById("modalLink");

        title.textContent = truyen.tentruyen;
        image.src = "../public/" + truyen.image_path.replace(/^\d+_/, '');
        image.alt = truyen.tentruyen;
        chapters.textContent = "Số chương: " + truyen.chapter_count;
        link.href = "truyen.php?id=" + truyen.idtruyen;

        modal.style.display = "block";
    }

    function closeModal() {
        var modal = document.getElementById("truyenModal");
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        var modal = document.getElementById("truyenModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>
    
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
<?php $conn->close(); ?>
