<?php
session_start();
require_once("../../../database.php");

$conn = connectDatabase();


$id = $_GET['id'];
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $update_sql = "UPDATE theloai SET name = '" . mysqli_real_escape_string($conn, $name) . "' WHERE idtheloai = $id";
    if (mysqli_query($conn, $update_sql)) {
        header("Location: quanlytheloai.php");
        exit();
    } else {
        echo "Lỗi khi cập nhật: " . mysqli_error($conn);
    }
}

$result = mysqli_query($conn, "SELECT * FROM theloai WHERE idtheloai = $id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Thể Loại</title>
        <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
        width: 100%;
    }

    button:hover {
        background-color: #45a049;
    }
</style>
</head>
<body>
    <h1>Sửa Thể Loại</h1>
    <form method="POST" action="">
        <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
        <button type="submit" name="update">Cập Nhật</button>
    </form>
</body>
</html>
<?php mysqli_close($conn); ?>
