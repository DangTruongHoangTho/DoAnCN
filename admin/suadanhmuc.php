<?php
    session_start();
    include '../database/connect.php';
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
        header("Location: index.php");
        exit;
    }

    if (isset($_GET['id'])) {
        $category_id = $_GET['id'];

        try {
            $stmt = $conn->prepare("SELECT * FROM categories WHERE id = :id");
            $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
            $stmt->execute();
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$category) {
                die("Danh mục không tồn tại.");
            }
        } catch (PDOException $e) {
            die("Lỗi cơ sở dữ liệu: " . $e->getMessage());
        }
    } else {
        die("Không có ID danh mục.");
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $category_name = $_POST['category_name'];

        if (!empty($category_name)) {
            try {
                $stmt = $conn->prepare("UPDATE categories SET name = :name WHERE id = :id");
                $stmt->bindParam(':name', $category_name, PDO::PARAM_STR);
                $stmt->bindParam(':id', $category_id, PDO::PARAM_INT);
                $stmt->execute();
                header("Location: danhsachdanhmuc.php");
                exit;
            } catch (PDOException $e) {
                die("Lỗi cơ sở dữ liệu: " . $e->getMessage());
            }
        } else {
            $error = "Tên danh mục không được để trống.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa danh mục</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Sửa danh mục</h2>
        <form method="post">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <div class="form-group">
                <label for="category_name">Tên danh mục:</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="danhsachdanhmuc.php" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</body>
</html>