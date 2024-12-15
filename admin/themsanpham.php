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
    } catch (PDOException $e) {
        $error = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $brand_id = $_POST['brand_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $discounted_price = $_POST['discounted_price'];
        $images = $_POST['images'];
        $size = $_POST['size'];
        $origin = $_POST['origin'];
        $year_of_release = $_POST['year_of_release'];
        $incense_group = $_POST['incense_group'];
        $style = $_POST['style'];

        try {
            $stmt = $conn->prepare("INSERT INTO products (brand_id, name, description, price, discounted_price, images, size, origin, year_of_release, incense_group, style) 
                                    VALUES (:brand_id, :name, :description, :price, :discounted_price, :images, :size, :origin, :year_of_release, :incense_group, :style)");
            $stmt->execute([
                ':brand_id' => $brand_id,
                ':name' => $name,
                ':description' => $description,
                ':price' => $price,
                ':discounted_price' => $discounted_price,
                ':images' => $images,
                ':size' => $size,
                ':origin' => $origin,
                ':year_of_release' => $year_of_release,
                ':incense_group' => $incense_group,
                ':style' => $style
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

        <h3>Thêm sản phẩm</h3>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="name">Tên sản phẩm:</label>
            <input type="text" name="name" required><br>

            <label for="description">Mô tả:</label>
            <textarea name="description" required></textarea><br>

            <label for="price">Giá:</label>
            <input type="number" name="price" required><br>

            <label for="discounted_price">Giá giảm:</label>
            <input type="number" name="discounted_price"><br>

            <label for="size">Kích thước:</label>
            <input type="text" name="size" required><br>

            <label for="origin">Xuất xứ:</label>
            <input type="text" name="origin" required><br>

            <label for="year_of_release">Năm phát hành:</label>
            <input type="number" name="year_of_release" required><br>

            <label for="incense_group">Nhóm hương:</label>
            <input type="text" name="incense_group" required><br>

            <label for="style">Phong cách:</label>
            <input type="text" name="style" required><br>

            <label for="brand_id">Thương hiệu:</label>
            <select name="brand_id" required>
                <option value="">-- Chọn thương hiệu --</option>
                <?php foreach ($brands as $brand): ?>
                    <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="category_id">Danh mục:</label>
            <select name="category_id" required>
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                <?php endforeach; ?>
            </select><br>

            <label for="image">Chọn ảnh:</label>
            <select name="image" required>
                <option value="">-- Chọn ảnh --</option>
                <?php foreach ($image_files as $image_file): ?>
                    <option value="images/<?= $image_file ?>"><?= htmlspecialchars($image_file) ?></option>
                <?php endforeach; ?>
            </select><br>

            <button type="submit" class="btn">Thêm sản phẩm</button>
        </form>
    </div>
</body>
</html>
