<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'dbshop';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: dangnhapadmin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $status = $_POST['status'];

    $sql_insert = "INSERT INTO categories (name, status, created_at, updated_at) 
                   VALUES ('$name', '$status', NOW(), NOW())";

    if ($conn->query($sql_insert) === TRUE) {
        echo "Danh mục đã được thêm thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Danh Mục</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Thêm Danh Mục Mới</h1>
    <form action="themdanhmuc.php" method="POST">
        <label for="name">Tên danh mục:</label>
        <input type="text" name="name" required><br><br>

        <label for="status">Trạng thái:</label>
        <select name="status" required>
            <option value="1">Kích hoạt</option>
            <option value="0">Ẩn</option>
        </select><br><br>

        <button type="submit">Thêm danh mục</button>
        <a href="giaodienadmin.php" class="btn-back">Quay về trang chủ</a>

    </form>
</body>
</html>

<?php
$conn->close();
?>