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
    $delete_sql = "DELETE FROM categories WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param('i', $category_id);

    if ($delete_stmt->execute()) {
        header('Location: danhsachdanhmuc.php'); // Quay lại danh sách danh mục sau khi xóa
        exit();
    } else {
        echo "Lỗi khi xóa danh mục.";
    }
} else {
    echo "ID danh mục không hợp lệ.";
}

$conn->close();
?>