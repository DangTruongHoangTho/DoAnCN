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

    try {
        $stmt = $conn->prepare("SELECT id, name, type, status FROM admins");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmtCategories = $conn->query("SELECT COUNT(*) AS total_categories FROM categories");
        $totalCategories = $stmtCategories->fetch()['total_categories'];
        $stmtProducts = $conn->query("SELECT COUNT(*) AS total_products FROM products");
        $totalProducts = $stmtProducts->fetch()['total_products'];
        $stmtBrands = $conn->query("SELECT COUNT(*) AS total_brands FROM brands");
        $totalBrands = $stmtBrands->fetch()['total_brands'];
        $stmtOrders = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
        $totalOrders = $stmtOrders->fetch()['total_orders'];
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
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            border-radius: 8px;
            overflow: hidden;
        }
        table th {
            background-color: #343a40;
            color: white;
        }
        table td, table th {
            text-align: center;
            vertical-align: middle;
        }
        .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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

    <!-- Content -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <h3>Xin chào, <?= htmlspecialchars($user_name) ?> (<?= strtoupper($user_type) ?>)</h3>
        </div>
        <!-- Dashboard Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <h5>Total Products</h5>
                        <h3><?php echo $totalProducts; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <h5>Total Brands</h5>
                        <h3><?php echo $totalBrands; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">
                        <h5>Total Categories</h5>
                        <h3><?php echo $totalCategories; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <h5>Total Orders</h5>
                        <h3><?php echo $totalOrders; ?></h3>
                    </div>
                </div>
            </div>
        </div>
<!-- Users Table -->
<h3 class="mb-4">Danh sách người dùng</h3>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Loại</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars(ucwords($user['type'])) ?></td>
                            <td><?= htmlspecialchars(ucwords($user['status'])) ?></td>
                            <td>
                                <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                                <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?')">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
