<?php
include "database/connect.php";
include "database/function.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_account/dangnhap.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy thông tin đơn hàng mới nhất của người dùng
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Không tìm thấy đơn hàng.";
    exit();
}

// Lấy chi tiết sản phẩm trong đơn hàng
$stmtDetails = $conn->prepare("SELECT od.*, p.name, b.name AS brand_name, categories.name AS category_name,
                            (SELECT images FROM products_imgs WHERE product_id = od.product_id LIMIT 1) AS images
                            FROM order_details od 
                            JOIN products p ON od.product_id = p.id
                            JOIN brands b ON p.brand_id = b.id
                            JOIN categories ON b.category_id = categories.id
                            WHERE od.order_id = :order_id");
$stmtDetails->bindParam(':order_id', $order['id'], PDO::PARAM_INT);
$stmtDetails->execute();
$orderDetails = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

$paymentMethod = $order['payment_method'] === 'cod' ? 'Thanh toán khi nhận hàng' : ($order['payment_method'] === 'onl' ? 'Chuyển khoản qua ngân hàng' : 'Thẻ ATM/Visa/Master/JCB/QR Pay qua cổng VNPAY');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác Nhận Đơn Hàng</title>
    <link
        rel="website icon"
        type="png"
        href="../images/banner/LogoT&T_2.png"
        id="logo" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .order-details {
            margin-bottom: 20px;
        }

        .order-details p {
            margin: 5px 0;
        }

        .product {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .product img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .product-info {
            flex: 1;
        }

        .total {
            font-weight: bold;
            margin-top: 20px;
        }

        .back-home {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .back-home:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Xác Nhận Đơn Hàng</h1>

        <div class="order-details">
            <p><strong>Mã đơn hàng:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
            <p><strong>Họ và tên:</strong> <?php echo htmlspecialchars($order['consignee_name']); ?></p>
            <p><strong>Địa chỉ giao hàng:</strong> <?php echo htmlspecialchars($order['consignee_address']); ?></p>
            <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['consignee_phone_number']); ?></p>
            <p><strong>Ngày giao dự kiến:</strong> <?php echo htmlspecialchars($order['delivery_date']); ?></p>
            <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($paymentMethod); ?></p>
        </div>

        <h2>Chi Tiết Sản Phẩm</h2>
        <?php foreach ($orderDetails as $item) { ?>
            <div class="product">
                <?php if (!empty($item['images'])) {
                    $imageArray = explode(', ', $item['images']);
                    if (!empty($imageArray[0])) {
                        $categoryName = removeAccents($item['category_name']);
                        $brandName = removeAccents($item['brand_name']);
                        $categoryNameFormated = str_replace('-', '', strtoupper($categoryName));
                        $brandNameFormatted = str_replace('-', '_', strtoupper($brandName));
                        $imagePath = "./images/categories/" . $categoryNameFormated . "/" . $brandNameFormatted . "/" . htmlspecialchars(trim($imageArray[0]));
                    ?>
                        <img
                            src="<?php echo $imagePath; ?>"
                            alt=""
                            class="w-50" />
                    <?php } ?>
                <?php } ?>
                <div class="product-info">
                    <p><strong><?php echo htmlspecialchars($item['name']); ?></strong></p>
                    <p><?php echo number_format($item['price'], 0, ',', '.'); ?>đ x <?php echo $item['quantity']; ?></p>
                </div>
            </div>
        <?php } ?>

        <div class="total">
            Tổng tiền: <?php echo number_format($order['total_price'], 0, ',', '.'); ?>đ
        </div>

        <a href="index.php" class="back-home">Quay về trang chủ</a>
    </div>
</body>

</html>
