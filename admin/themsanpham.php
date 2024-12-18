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
        $stmt = $conn->prepare("SELECT id, name FROM brands");
        $stmt->execute();
        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT id, name FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $brand_id = $_POST['brand_id'];
        $category_id = $_POST['category_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $discounted_price = $_POST['discounted_price'];
        $quantity = $_POST['quantity'];
        $size = $_POST['size'];
        $origin = $_POST['origin'];
        $year_of_release = $_POST['year_of_release'];
        $incense_group = $_POST['incense_group'];
        $style = $_POST['style'];
        $image = $_POST['image'];

        try {
            $stmt = $conn->prepare("INSERT INTO products (brand_id, category_id, name, description, price, discounted_price, quantity, size, origin, year_of_release, incense_group, style) 
                                    VALUES (:brand_id, :category_id, :name, :description, :price, :discounted_price, :quantity, :size, :origin, :year_of_release, :incense_group, :style)");
            $stmt->execute([
                ':brand_id' => $brand_id,
                ':category_id' => $category_id,
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':discounted_price' => $discounted_price,
                ':quantity' => $quantity,
                ':size' => $size,
                ':origin' => $origin,
                ':year_of_release' => $year_of_release,
                ':incense_group' => $incense_group,
                ':style' => $style
            ]);
            $product_id = $conn->lastInsertId();
            $stmt = $conn->prepare("INSERT INTO product_imgs (product_id, images) VALUES (:product_id, :image)");
            $stmt->execute([
                ':product_id' => $product_id,
                ':image' => $image  // Assuming this is the image filename (e.g., "image1.jpg")
            ]);

            $success = "Thêm sản phẩm thành công!";
        } catch (PDOException $e) {
            $error = "Lỗi khi thêm sản phẩm: " . $e->getMessage();
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm</title>
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
        <a href="dangxuat.php" class="text-danger">Đăng xuất</a>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Thêm sản phẩm</h4>
                <span>Chào, <strong><?= htmlspecialchars($user_name) ?> (<?= strtoupper($user_type) ?>)</strong></span>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Tên sản phẩm:</label>
                        <input type="text" name="name" class="form-control" required><br>

                        <label for="description">Mô tả:</label>
                        <textarea name="description" class="form-control" required></textarea><br>

                        <label for="price">Giá:</label>
                        <input type="number" name="price" class="form-control" required><br>

                        <label for="discounted_price">Giá giảm:</label>
                        <input type="number" name="discounted_price" class="form-control"><br>

                        <label for="quantity">Số lượng:</label>
                        <input type="number" name="quantity" class="form-control" required><br>

                        <label for="size">Kích thước:</label>
                        <input type="text" name="size" class="form-control" required><br>

                        <label for="origin">Xuất xứ:</label>
                        <input type="text" name="origin" class="form-control" required><br>

                        <label for="year_of_release">Năm phát hành:</label>
                        <input type="number" name="year_of_release" class="form-control" required><br>

                        <label for="incense_group">Nhóm hương:</label>
                        <input type="text" name="incense_group" class="form-control" required><br>

                        <label for="style">Phong cách:</label>
                        <input type="text" name="style" class="form-control" required><br>

                        <label for="brand_id">Thương hiệu:</label>
                        <select name="brand_id" class="form-control" required>
                            <option value="">-- Chọn thương hiệu --</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                            <?php endforeach; ?>
                        </select><br>

                        <label for="category_id">Danh mục:</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select><br>

                        <label for="image">Chọn ảnh:</label>
                        <input type="text" name="image" class="form-control" placeholder="Tên ảnh" required><br>

                        <div class="text-center">
                            <button type="submit" class="btn-custom">Thêm sản phẩm</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>