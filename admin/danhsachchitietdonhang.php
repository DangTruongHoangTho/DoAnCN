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

        try {
            // Lấy thông tin đơn hàng
            $stmt = $conn->prepare(
                "SELECT 
                    orders.id, 
                    orders.status, 
                    orders.created_at, 
                    orders.updated_at, 
                    orders.consignee_name, 
                    orders.consignee_address, 
                    orders.consignee_phone_number, 
                    orders.delivery_date, 
                    orders.payment_method,
                    users.first_name, 
                    users.last_name
                FROM orders
                JOIN users ON orders.user_id = users.id
                WHERE orders.id = :order_id"
            );
            $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmt->execute();
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                $error = "Đơn hàng không tồn tại.";
            }

            // Lấy thông tin chi tiết các sản phẩm trong đơn hàng
            $stmtDetails = $conn->prepare(
                "SELECT 
                    order_details.quantity, 
                    order_details.price, 
                    products.name AS product_name, 
                    products.id AS product_id
                FROM order_details
                JOIN products ON order_details.product_id = products.id
                WHERE order_details.order_id = :order_id"
            );
            $stmtDetails->bindParam(':order_id', $order_id, PDO::PARAM_INT);
            $stmtDetails->execute();
            $order_details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            $error = "Lỗi truy vấn cơ sở dữ liệu: " . $e->getMessage();
        }
    } else {
        header("Location: danhsachdonhang.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng</title>
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
                <h4 class="mb-0">Chi Tiết Đơn Hàng #<?= htmlspecialchars($order['id']) ?></h4>
                <span>Chào, <strong><?= htmlspecialchars($user_name) ?> (<?= strtoupper($user_type) ?>)</strong></span>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <div class="mb-4">
                    <h5>Thông Tin Đơn Hàng:</h5>
                    <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
                    <p><strong>Ngày tạo:</strong> <?= htmlspecialchars($order['created_at']) ?></p>
                    <p><strong>Ngày cập nhật:</strong> <?= htmlspecialchars($order['updated_at']) ?></p>
                    <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['consignee_name']) ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['consignee_address']) ?></p>
                    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['consignee_phone_number']) ?></p>
                    <p><strong>Ngày giao hàng:</strong> <?= htmlspecialchars($order['delivery_date']) ?></p>
                    <p><strong>Phương thức thanh toán:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
                </div>

                <h5 class="mb-3">Chi Tiết Sản Phẩm:</h5>
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_details as $detail): ?>
                            <tr>
                                <td><?= htmlspecialchars($detail['product_name']) ?></td>
                                <td><?= htmlspecialchars($detail['quantity']) ?></td>
                                <td><?= number_format($detail['price'], 0, ',', '.') ?> VND</td>
                                <td><?= number_format($detail['quantity'] * $detail['price'], 0, ',', '.') ?> VND</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="danhsachdonhang.php" class="btn btn-secondary btn-sm mt-3">Quay lại</a>
            </div>
        </div>
    </div>
</body>
</html>
