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
        $stmt = $conn->prepare("SELECT products.id, products.name, products.price, products.discounted_price, products.year_of_release, brands.name AS brand_name
                                FROM products
                                JOIN brands ON products.brand_id = brands.id");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
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

        <h3>Danh sách sản phẩm</h3>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Thương hiệu</th>
                    <th>Giá</th>
                    <th>Giá khuyến mãi</th>
                    <th>Năm phát hành</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['brand_name']) ?></td>
                    <td><?= htmlspecialchars(number_format($product['price'], 0, ',', '.')) ?> VND</td>
                    <td><?= htmlspecialchars(number_format($product['discounted_price'], 0, ',', '.')) ?> VND</td>
                    <td><?= htmlspecialchars($product['year_of_release']) ?></td>
                    <td>
                        <a href="products_edit.php?id=<?= $product['id'] ?>" class="btn">Sửa</a>
                        <a href="products_delete.php?id=<?= $product['id'] ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
