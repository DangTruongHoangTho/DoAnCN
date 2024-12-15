<?php
    error_reporting(0);
    session_start();
    include '../database/connect.php';
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
        header("Location: index.php"); 
        exit;
    }
    $user_name = $_SESSION['user']['name'];
    $user_type = $_SESSION['user']['type'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $category_name = $_POST['name'];

        try {
            $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (:name)");
            $stmt->execute([':name' => $category_name]);
            $success = "Thêm danh mục thành công!";
        } catch (PDOException $e) {
            $error = "Lỗi khi thêm danh mục: " . $e->getMessage();
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm danh mục</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 15px;
            color: white;
            text-decoration: none;
            font-size: 16px;
            border-bottom: 1px solid #495057;
        }
        .sidebar a:hover {
            background-color: #495057;
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
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
    <h2 style="color:white; text-align:center;">Admin Dashboard</h2>
    <a href="giaodienadmin.php">Trang chủ</a>
    <a href="danhsachdanhmuc.php">Danh sách danh mục</a>
    <a href="themdanhmuc.php">Thêm danh mục</a>
    <a href="danhsachthuonghieu.php">Danh sách thương hiệu</a>
    <a href="themthuonghieu.php">Thêm thương hiệu</a>
    <a href="danhsachsanpham.php">Danh sách sản phẩm</a>
    <a href="themsanpham.php">Thêm sản phẩm</a>
    <a href="dangxuat.php">Đăng xuất</a>
</div>

    <!-- Main Content -->
    <div class="content">
        <div class="header">
            <h3>Chào, <?= htmlspecialchars($user_name) ?> (<?= strtoupper($user_type) ?>)</h3>
        </div>

        <h3>Thêm danh mục</h3>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Tên danh mục:</label>
            <input type="text" name="name" required><br>
            <button type="submit" class="btn">Thêm danh mục</button>
        </form>
    </div>
</body>
</html>
