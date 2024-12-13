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

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];
    
    // Lấy thông tin danh mục từ database
    $sql = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
    } else {
        echo "Danh mục không tồn tại.";
        exit();
    }
} else {
    echo "ID danh mục không hợp lệ.";
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $status = $_POST['status'];

    // Cập nhật thông tin vào cơ sở dữ liệu
    $update_sql = "UPDATE categories SET name = ?, status = ?, updated_at = NOW() WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param('sii', $name, $status, $category_id);

    if ($update_stmt->execute()) {
        header('Location: danhsachdanhmuc.php'); // Quay lại danh sách danh mục sau khi sửa
        exit();
    } else {
        echo "Lỗi cập nhật danh mục.";
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Danh Mục</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Sửa Danh Mục</h1>
    <form action="suadanhmuc.php?id=<?php echo $category['id']; ?>" method="POST">
        <label for="name">Tên danh mục:</label>
        <input type="text" name="name" value="<?php echo $category['name']; ?>" required><br><br>

        <label for="status">Trạng thái:</label>
        <select name="status" required>
            <option value="1" <?php echo $category['status'] == 1 ? 'selected' : ''; ?>>Kích hoạt</option>
            <option value="0" <?php echo $category['status'] == 0 ? 'selected' : ''; ?>>Ẩn</option>
        </select><br><br>

        <button type="submit">Cập nhật danh mục</button>
    </form>
    <br>
    <a href="giaodienadmin.php" class="btn-back">Quay về trang chủ</a>
</body>
</html>

<?php
$conn->close();
?>