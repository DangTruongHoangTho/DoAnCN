<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: dangnhapadmin.php');
    exit();
}
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'dbshop';
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_category = "SELECT * FROM categories WHERE status = 1";
$result_category = $conn->query($sql_category);

$sql_brand = "SELECT * FROM brands WHERE status = 1";
$result_brand = $conn->query($sql_brand);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Xử lý form khi admin submit
    $brand_id = $_POST['brand_id'];
    $category_id = $_POST['category_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $summary = $_POST['summary'];
    $price = $_POST['price'];
    $discounted_price = $_POST['discounted_price'];
    $images = $_FILES['images']['name'];
    $size = $_POST['size'];
    $status = $_POST['status'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($images);
    move_uploaded_file($_FILES['images']['tmp_name'], $target_file);

    $sql_insert = "INSERT INTO products (brand_id, category_id, name, description, summary, price, discounted_price, images, size, status, created_at, updated_at) 
                   VALUES ('$brand_id', '$category_id', '$name', '$description', '$summary', '$price', '$discounted_price', '$images', '$size', '$status', NOW(), NOW())";

    if ($conn->query($sql_insert) === TRUE) {
        echo "Sản phẩm đã được thêm thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Thêm Sản Phẩm Mới</h1>
    <form action="themsanpham.php" method="POST" enctype="multipart/form-data">
        <label for="brand_id">Thương hiệu:</label>
        <select name="brand_id" required>
            <?php while ($row = $result_brand->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
        </select><br><br>

        <label for="category_id">Danh mục:</label>
        <select name="category_id" required>
            <?php while ($row = $result_category->fetch_assoc()) { ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
        </select><br><br>

        <label for="name">Tên sản phẩm:</label>
        <input type="text" name="name" required><br><br>

        <label for="description">Mô tả:</label>
        <textarea name="description" required></textarea><br><br>

        <label for="summary">Tóm tắt:</label>
        <textarea name="summary" required></textarea><br><br>

        <label for="price">Giá:</label>
        <input type="number" name="price" required><br><br>

        <label for="discounted_price">Giá giảm:</label>
        <input type="number" name="discounted_price"><br><br>

        <label for="images">Ảnh sản phẩm:</label>
        <input type="file" name="images" required><br><br>

        <label for="size">Kích thước:</label>
        <input type="text" name="size" required><br><br>

        <label for="status">Trạng thái:</label>
        <select name="status" required>
            <option value="1">Còn hàng</option>
            <option value="0">Hết hàng</option>
        </select><br><br>

        <button type="submit">Thêm sản phẩm</button>
        <a href="giaodienadmin.php" class="btn-back">Quay về trang chủ</a>
        
    </form>
</body>
</html>

<?php
$conn->close();
?>