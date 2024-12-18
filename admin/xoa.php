<?php
    session_start();
    include '../database/connect.php';
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
        header("Location: index.php");
        exit;
    }
    if (isset($_GET['id']) && isset($_GET['type'])) {
        $id = $_GET['id'];
        $type = $_GET['type'];

        try {
            switch ($type) {
                case 'category':
                    $stmt = $conn->prepare("DELETE FROM categories WHERE id = :id");
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    header("Location: danhsachdanhmuc.php");
                    break;
                
                case 'brand':
                    $stmt = $conn->prepare("DELETE FROM brands WHERE id = :id");
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                    header("Location: danhsachthuonghieu.php");
                    break;
                
                case 'product': 
                    $stmt = $conn->prepare("DELETE FROM products_imgs WHERE product_id = :id");
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    $stmt = $conn->prepare("DELETE FROM products WHERE id = :id");
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();

                    header("Location: danhsachsanpham.php");
                    break;
                
                default:
                die("Loại đối tượng không hợp lệ.");
        }
    } catch (PDOException $e) {
        die("Lỗi cơ sở dữ liệu: " . htmlspecialchars($e->getMessage()));
    }
} else {
    die("Thông tin xóa không hợp lệ.");
}
?>