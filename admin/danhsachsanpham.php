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
        $stmt = $conn->prepare(
            "SELECT 
                products.id, 
                products.name, 
                products.price, 
                products.discounted_price, 
                products.year_of_release, 
                products.quantity, 
                products.size, 
                products.origin, 
                products.incense_group, 
                products.style, 
                brands.name AS brand_name, 
                categories.name AS category_name,
                products_imgs.images AS product_image
            FROM products
            JOIN brands ON products.brand_id = brands.id
            JOIN categories ON brands.category_id = categories.id
            LEFT JOIN products_imgs ON products.id = products_imgs.product_id
            GROUP BY products.id, products.name, products.price, products.discounted_price, 
                    products.year_of_release, products.quantity, products.size, products.origin, 
                    products.incense_group, products.style, brands.name, categories.name
            ORDER BY products.id ASC
        ");
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
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            background-color: white;
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
                <h4 class="mb-0">Danh sách sản phẩm</h4>
                <span>Chào, <strong><?= htmlspecialchars($user_name) ?> (<?= strtoupper($user_type) ?>)</strong></span>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Giá</th>
                            <th>Giá khuyến mãi</th>
                            <th>Số lượng</th>
                            <th>Size</th>
                            <th>Xuất xứ</th>
                            <th>Nhóm hương</th>
                            <th>Phong cách</th>
                            <th>Ảnh</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['id']) ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category_name']) ?></td>
                            <td><?= htmlspecialchars($product['brand_name']) ?></td>
                            <td><?= htmlspecialchars(number_format($product['price'], 0, ',', '.')) ?> VND</td>
                            <td><?= htmlspecialchars(number_format($product['discounted_price'], 0, ',', '.')) ?> VND</td>
                            <td><?= htmlspecialchars($product['quantity']) ?></td>
                            <td><?= htmlspecialchars($product['size']) ?>ml</td>
                            <td><?= htmlspecialchars($product['origin']) ?></td>
                            <td><?= htmlspecialchars($product['incense_group']) ?></td>
                            <td><?= htmlspecialchars($product['style']) ?></td>
                            <td>
                                <?php 
                                    // Kiểm tra nếu có ảnh
                                    if (!empty($product['product_image'])): 
                                        // Tạo đường dẫn ảnh
                                        $categoryFolder = strtolower(str_replace(' ', '-', $product['category_name']));
                                        $brandFolder = strtolower(str_replace(' ', '-', $product['brand_name']));
                                        $productFolder = strtolower(str_replace(' ', '-', $product['name']));
                                        $imagePath = "../images/categories/{$categoryFolder}/{$brandFolder}/{$productFolder}/" . htmlspecialchars($product['product_image']);
                                ?>
                                    <img src="<?= $imagePath ?>" alt="Ảnh sản phẩm" width="100">
                                <?php else: ?>
                                    <span>Không có ảnh</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="suasanpham.php?id=<?= $product['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                                <a href="xoa.php?id=<?= $product['id'] ?>&type=product" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>