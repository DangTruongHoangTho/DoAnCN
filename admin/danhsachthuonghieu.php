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

$sql = "SELECT * FROM brands";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Thương Hiệu</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Danh Sách Thương Hiệu</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên thương hiệu</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['status'] == 1 ? 'Kích hoạt' : 'Ẩn'; ?></td>
                    <td>
                        <a href="edit_brand.php?id=<?php echo $row['id']; ?>">Sửa</a> | 
                        <a href="delete_brand.php?id=<?php echo $row['id']; ?>">Xóa</a>
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