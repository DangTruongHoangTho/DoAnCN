<?php
    error_reporting(0);
    session_start();
    include '../database/connect.php';
    
    if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'staff') {
        header("Location: index.php"); 
        exit;
    }
    $user_name = $_SESSION['user']['name'];
    $user_type = $_SESSION['user']['type'];

    try {
        $stmt = $conn->prepare("SELECT id, name, type, status FROM admins");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Giao Diện</title>
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

        
    </div>
</body>
</html>
