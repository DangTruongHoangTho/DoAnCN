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

    if (isset($_GET['order_id'])) {
        $order_id = $_GET['order_id'];

        // Lấy thông tin đơn hàng hiện tại
        try {
            $stmt = $conn->prepare(
                "SELECT id, status FROM orders WHERE id = :order_id"
            );
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                $error = "Đơn hàng không tồn tại.";
            } else {
                $current_status = $order['status'];
            }

        } catch (PDOException $e) {
            $error = "Lỗi truy vấn cơ sở dữ liệu: " . $e->getMessage();
        }
    } else {
        header("Location: danhsachdonhang.php");
        exit;
    }

    // Xử lý cập nhật trạng thái
    if (isset($_POST['update_status'])) {
        $new_status = $_POST['status'];

        try {
            $stmt = $conn->prepare(
                "UPDATE orders SET status = :status WHERE id = :order_id"
            );
            $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: danhsachdonhang.php");
            exit;
        } catch (PDOException $e) {
            $error = "Lỗi cập nhật trạng thái: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Trạng Thái Đơn Hàng</title>
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
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Cập Nhật Trạng Thái Đơn Hàng</h4>
                <span>Chào, <strong><?= htmlspecialchars($user_name) ?> (<?= strtoupper($user_type) ?>)</strong></span>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if (isset($order)): ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="status">Trạng thái hiện tại:</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($current_status) ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="status">Chọn trạng thái mới:</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="processing" <?= $current_status == 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                <option value="confirmed" <?= $current_status == 'confirmed' ? 'selected' : '' ?>>Đã xác nhận</option>
                                <option value="shipping" <?= $current_status == 'shipping' ? 'selected' : '' ?>>Đang giao hàng</option>
                                <option value="dellvered" <?= $current_status == 'dellvered' ? 'selected' : '' ?>>Đã giao</option>
                                <option value="cancelled" <?= $current_status == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                            </select>
                        </div>

                        <button type="submit" name="update_status" class="btn btn-success">Cập Nhật Trạng Thái</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
