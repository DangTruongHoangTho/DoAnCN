<?php
    error_reporting(0);
    session_start();
    include '../database/connect.php';
    
    if (!isset($_SESSION['user'])) {
        header("Location: index.php");
        exit;
    }

    $user_name = $_SESSION['user']['name'];
    $user_type = $_SESSION['user']['type'];

    try {
        $stmt = $conn->prepare("SELECT id, name FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $brand_name = $_POST['name'];
        $category_id = $_POST['category_id'];

        try {
            $stmt = $conn->prepare("INSERT INTO brands (name, category_id) VALUES (:name, :category_id)");
            $stmt->execute([
                ':name' => $brand_name,
                ':category_id' => $category_id
            ]);
            $success = "Thêm thương hiệu thành công!";
        } catch (PDOException $e) {
            $error = "Lỗi khi thêm thương hiệu: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm thương hiệu</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            height: 100vh;
            position: fixed;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 12px;
            color: #ddd;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background-color: #495057;
            color: white;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            padding: 15px;
            color: white;
            font-size: 20px;
            text-align: center;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: white;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .form-control:focus {
            border-color: #007bff;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2 class="text-center mb-4">Admin Panel</h2>
        <a href="giaodienadmin.php">Trang chủ</a>
        <a href="danhsachdanhmuc.php">Danh sách danh mục</a>
        <a href="themdanhmuc.php">Thêm danh mục</a>
        <a href="danhsachthuonghieu.php">Danh sách thương hiệu</a>
        <a href="themthuonghieu.php">Thêm thương hiệu</a>
        <a href="danhsachsanpham.php">Danh sách sản phẩm</a>
        <a href="themsanpham.php">Thêm sản phẩm</a>
        <a href="danhsachdonhang.php">Danh sách đơn hàng</a>
        <a href="dangxuat.php" class="text-danger">Đăng xuất</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Thêm thương hiệu</h4>
                <span>Chào, <strong><?= htmlspecialchars($user_name) ?> (<?= strtoupper($user_type) ?>)</strong></span>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Tên thương hiệu:</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="category_id">Danh mục:</label>
                    <select name="category_id" id="category_id" class="form-control" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-custom">Thêm thương hiệu</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
