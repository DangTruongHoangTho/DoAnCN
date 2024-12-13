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

$sql = "SELECT p.id, p.name, p.price, p.discounted_price, p.status, c.name AS category_name, b.name AS brand_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN brands b ON p.brand_id = b.id";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Sản Phẩm</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Danh Sách Sản Phẩm</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Giá giảm</th>
                <th>Danh mục</th>
                <th>Thương hiệu</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo number_format($row['discounted_price'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo $row['category_name']; ?></td>
                    <td><?php echo $row['brand_name']; ?></td>
                    <td><?php echo $row['status'] == 1 ? 'Còn hàng' : 'Hết hàng'; ?></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>">Sửa</a> | 
                        <a href="delete_product.php?id=<?php echo $row['id']; ?>">Xóa</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <br>
    <a href="giaodienadmin.php" class="btn-back">Quay về trang chủ</a>
</body>
</html>

<?php
$conn->close();
?>