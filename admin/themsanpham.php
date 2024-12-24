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
    $brandsStmt = $conn->prepare("SELECT * FROM brands");
    $brandsStmt->execute();
    $brands = $brandsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Lỗi cơ sở dữ liệu: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $brand_id = $_POST['brand_id'];
    $price = $_POST['price'];
    $discounted_price = $_POST['discounted_price'];
    $quantity = $_POST['quantity'];
    $size = $_POST['size'];
    $origin = $_POST['origin'];
    $year_of_release = $_POST['year_of_release'];
    $description = $_POST['description'];
    $incense_group = $_POST['incense_group'];
    $style = $_POST['style'];

    $image_names = [];
    for ($i = 1; $i <= 3; $i++) {
        $image_field = "product_image_$i";

        if (isset($_FILES[$image_field]) && $_FILES[$image_field]['error'] === 0) {
            $image_name = $_FILES[$image_field]['name'];
            $image_tmp_name = $_FILES[$image_field]['tmp_name'];
            $image_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
            $image_new_name = uniqid('', true) . '.' . $image_ext;
            $image_path = "../images/products/" . $image_new_name;

            if (move_uploaded_file($image_tmp_name, $image_path)) {
                echo "Lưu ảnh thành công: " . $image_new_name . "<br>";
                $image_names[] = $image_new_name;
            } else {
                echo "Không thể lưu ảnh: " . $image_name . "<br>";
            }
        } elseif ($_FILES[$image_field]['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "Lỗi tải ảnh {$image_field}: " . $_FILES[$image_field]['error'] . "<br>";
        }
    }

    try {
        $stmt = $conn->prepare("INSERT INTO products (name, brand_id, price, discounted_price, quantity, size, origin, year_of_release, incense_group, style, description) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $brand_id, $price, $discounted_price, $quantity, $size, $origin, $year_of_release, $incense_group, $style, $description]);

        $product_id = $conn->lastInsertId();

        if (!$product_id) {
            echo "Không lấy được product_id.<br>";
            exit;
        }

        foreach ($image_names as $image) {
            try {
                $stmt = $conn->prepare("INSERT INTO products_imgs (product_id, images) VALUES (?, ?)");
                $stmt->execute([$product_id, $image]);
                echo "Thêm ảnh thành công: " . $image . "<br>";
            } catch (PDOException $e) {
                echo "Lỗi khi thêm ảnh {$image}: " . $e->getMessage() . "<br>";
            }
        }

        header("Location: danhsachsanpham.php");
        exit;
    } catch (PDOException $e) {
        echo "Lỗi cơ sở dữ liệu: " . $e->getMessage() . "<br>";
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
            margin-left: 270px;
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
        <h2 class="text-center mb-4">Admin</h2>
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
                <h4 class="mb-0">Thêm sản phẩm mới</h4>
                <span>Chào, <strong><?= htmlspecialchars($user_name) ?> (<?= strtoupper($user_type) ?>)</strong></span>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form action="themsanpham.php" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="brand_id">Thương hiệu</label>
                            <select class="form-control" id="brand_id" name="brand_id" required>
                                <option value="">Chọn thương hiệu</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="price">Giá</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="discounted_price">Giá khuyến mãi</label>
                            <input type="number" class="form-control" id="discounted_price" name="discounted_price">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="quantity">Số lượng</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="size">Size (ml)</label>
                            <input type="number" class="form-control" id="size" name="size" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="origin">Xuất xứ</label>
                            <input type="text" class="form-control" id="origin" name="origin" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                        <label for="year_of_release">Năm phát hành</label>
                        <input type="number" class="form-control" id="year_of_release" name="year_of_release" required>
                        </div>
                        <div class="form-group col-md-6">
                        <label for="description">Mô tả sản phẩm</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="incense_group">Nhóm hương</label>
                        <input type="text" class="form-control" id="incense_group" name="incense_group">
                    </div>
                    <div class="form-group">
                        <label for="style">Phong cách</label>
                        <input type="text" class="form-control" id="style" name="style">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="product_image_1">Ảnh sản phẩm 1</label>
                            <input type="file" class="form-control" id="product_image_1" name="product_image_1" accept="image/*">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="product_image_2">Ảnh sản phẩm 2</label>
                            <input type="file" class="form-control" id="product_image_2" name="product_image_2" accept="image/*">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="product_image_3">Ảnh sản phẩm 3</label>
                            <input type="file" class="form-control" id="product_image_3" name="product_image_3" accept="image/*">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-custom btn-block">Thêm sản phẩm</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>