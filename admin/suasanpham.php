<?php
session_start();
include '../database/connect.php';

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$user_name = $_SESSION['user']['name'];
$user_type = $_SESSION['user']['type'];

$error = '';
$success = '';
$product = null;
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    try {

        $stmt_brands = $conn->prepare("SELECT id, name FROM brands");
        $stmt_brands->execute();
        $brands = $stmt_brands->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare(
            "SELECT 
                products.*, 
                brands.name AS brand_name, 
                categories.name AS category_name
            FROM products
            JOIN brands ON products.brand_id = brands.id
            JOIN categories ON brands.category_id = categories.id
            WHERE products.id = :id"
        );
        $stmt->execute([':id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            $error = "Không tìm thấy sản phẩm.";
        }
    } catch (PDOException $e) {
        $error = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
    }
} else {
    header("Location: danhsachsanpham.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand_id = intval($_POST['brand_id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $discounted_price = floatval($_POST['discounted_price']);
    $quantity = intval($_POST['quantity']);
    $size = trim($_POST['size']);
    $origin = trim($_POST['origin']);
    $incense_group = trim($_POST['incense_group']);
    $style = trim($_POST['style']);

    if (empty($name) || $price <= 0 || $quantity < 0 || !$brand_id) {
        $error = "Vui lòng điền đầy đủ thông tin hợp lệ.";
    } else {
        try {
            $stmt = $conn->prepare(
                "UPDATE products SET 
                    brand_id = :brand_id,
                    name = :name, 
                    price = :price, 
                    discounted_price = :discounted_price, 
                    quantity = :quantity, 
                    size = :size, 
                    origin = :origin, 
                    incense_group = :incense_group, 
                    style = :style
                WHERE id = :id"
            );
            $stmt->execute([
                ':brand_id' => $brand_id,
                ':name' => $name,
                ':price' => $price,
                ':discounted_price' => $discounted_price,
                ':quantity' => $quantity,
                ':size' => $size,
                ':origin' => $origin,
                ':incense_group' => $incense_group,
                ':style' => $style,
                ':id' => $product_id
            ]);

            header("Location: danhsachsanpham.php");
            exit;
        } catch (PDOException $e) {
            $error = "Lỗi khi cập nhật sản phẩm: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa thương hiệu</title>
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
                <h4 class="mb-0">Sửa sản phẩm</h4>
                <span>Chào, <strong><?= htmlspecialchars($user_name) ?> (<?= strtoupper($user_type) ?>)</strong></span>
            </div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="form-group">
                        <label for="name">Tên sản phẩm</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="brand_id">Thương hiệu</label>
                        <select name="brand_id" id="brand_id" class="form-control" required>
                            <option value="">Chọn thương hiệu</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= htmlspecialchars($brand['id']) ?>" 
                                    <?= $product['brand_id'] == $brand['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($brand['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Giá</label>
                        <input type="number" name="price" id="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="discounted_price">Giá khuyến mãi</label>
                        <input type="number" name="discounted_price" id="discounted_price" class="form-control" value="<?= htmlspecialchars($product['discounted_price']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Số lượng</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="<?= htmlspecialchars($product['quantity']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="size">Dung tích (ml)</label>
                        <input type="text" name="size" id="size" class="form-control" value="<?= htmlspecialchars($product['size']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="origin">Xuất xứ</label>
                        <input type="text" name="origin" id="origin" class="form-control" value="<?= htmlspecialchars($product['origin']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="incense_group">Nhóm hương</label>
                        <input type="text" name="incense_group" id="incense_group" class="form-control" value="<?= htmlspecialchars($product['incense_group']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="style">Phong cách</label>
                        <input type="text" name="style" id="style" class="form-control" value="<?= htmlspecialchars($product['style']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

