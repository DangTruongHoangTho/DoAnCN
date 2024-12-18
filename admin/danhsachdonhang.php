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
                orders.id, 
                users.first_name, 
                users.last_name, 
                orders.status, 
                orders.created_at, 
                orders.updated_at, 
                orders.consignee_name, 
                orders.consignee_address, 
                orders.consignee_phone_number, 
                orders.delivery_date, 
                orders.payment_method,
                (SELECT SUM(quantity) FROM order_details WHERE order_id = orders.id) AS total_quantity,
                (SELECT SUM(quantity * price) FROM order_details WHERE order_id = orders.id) AS total_price
            FROM orders
            JOIN users ON orders.user_id = users.id
            ORDER BY orders.created_at DESC"
        );
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        $error = "Lỗi truy vấn cơ sở dữ liệu: " . $e->getMessage();
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
                <h4 class="mb-0">Danh sách đơn hàng</h4>
                <span>Chào, <strong><?= htmlspecialchars($user_name) ?> (<?= strtoupper($user_type) ?>)</strong></span>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tên User</th>
                            <th>Trạng thái</th>
                            <th>Ngày Tạo</th>
                            <th>Ngày Cập Nhật</th>
                            <th>Tên Người Nhận</th>
                            <th>Địa Chỉ</th>
                            <th>SĐT</th>
                            <th>Ngày Giao Hàng</th>
                            <th>Phương Thức Thanh Toán</th>
                            <th>Tổng Số Lượng</th>
                            <th>Tổng Tiền</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order['id']) ?></td>
                                <td><?= htmlspecialchars($order['first_name'] . ' ' . $order['last_name']) ?></td>
                                <td><?= htmlspecialchars($order['status']) ?></td>
                                <td><?= htmlspecialchars($order['created_at']) ?></td>
                                <td><?= htmlspecialchars($order['updated_at']) ?></td>
                                <td><?= htmlspecialchars($order['consignee_name']) ?></td>
                                <td><?= htmlspecialchars($order['consignee_address']) ?></td>
                                <td><?= htmlspecialchars($order['consignee_phone_number']) ?></td>
                                <td><?= htmlspecialchars($order['delivery_date']) ?></td>
                                <td><?= htmlspecialchars($order['payment_method']) ?></td>
                                <td><?= htmlspecialchars($order['total_quantity']) ?></td>
                                <td><?= htmlspecialchars(number_format($order['total_price'], 0, ',', '.')) ?> VND</td>
                                <td>
                                    <a href="danhsachchitietdonhang.php?order_id=<?= $order['id'] ?>" class="btn btn-info btn-sm">Chi Tiết</a>
                                    <a href="capnhattrangthai.php?order_id=<?= $order['id'] ?>" class="btn btn-warning btn-sm">Cập Nhật Trạng Thái</a>
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