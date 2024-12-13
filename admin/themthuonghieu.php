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
$sql_category = "SELECT * FROM categories WHERE status = 1";
$result_category = $conn->query($sql_category);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $status = $_POST['status'];

    $sql_insert = "INSERT INTO brands (category_id, name, status, created_at, updated_at) 
                   VALUES ('$category_id', '$name', '$status', NOW(), NOW())";

    if ($conn->query($sql_insert) === TRUE) {
        echo "Thương hiệu đã được thêm thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Thương Hiệu</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Thêm Thương Hiệu Mới</h1>
    <form action="themthuonghieu.php" method="POST">
        <label for="category_id">Danh mục:</label>
        <select name="category_id" required>
            <?php while ($row = $result_category->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
        </select><br><br>

        <label for="name">Tên thương hiệu:</label>
        <input type="text" name="name" required><br><br>

        <label for="status">Trạng thái:</label>
        <select name="status" required>
            <option value="1">Kích hoạt</option>
            <option value="0">Ẩn</option>
        </select><br><br>

        <button type="submit">Thêm thương hiệu</button>
        <a href="giaodienadmin.php" class="btn-back">Quay về trang chủ</a>
    </form>
</body>
</html>

<?php
$conn->close();
?>