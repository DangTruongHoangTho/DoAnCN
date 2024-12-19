<?php
include "database/connect.php";
include "database/function.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: user_account/dangnhap.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id']) || is_array($_GET['id'])) {
    $productIds = isset($_GET['id']) ? (array)$_GET['id'] : [];
    $quantities = isset($_GET['quantity']) ? $_GET['quantity'] : [];
    if (!is_array($_GET['id'])) {
        $_GET['id'] = [$_GET['id']];
    }

    $cartItems = [];
    foreach ($productIds as $key => $productId) {
        if (is_numeric($productId)) {
            $quantity = isset($quantities[$key]) && is_numeric($quantities[$key]) ? intval($quantities[$key]) : 1;
            $stmt = $conn->prepare("SELECT p.*, b.name AS brand_name, b.category_id, categories.name AS category_name,
                                    (SELECT images FROM products_imgs WHERE product_id = p.id LIMIT 1) AS images 
                                    FROM products p JOIN brands b ON p.brand_id = b.id
                                    JOIN categories ON b.category_id = categories.id WHERE p.id = :id");
            $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $existingKey = array_search($productId, array_column($cartItems, 'product_id'));
                if ($existingKey !== false) {
                    $cartItems[$existingKey]['quantity'] += $quantity;
                    $cartItems[$existingKey]['subtotal'] = $cartItems[$existingKey]['quantity'] * $cartItems[$existingKey]['price'];
                } else {
                    $cartItems[] = [
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'price' => $product['discounted_price'],
                        'subtotal' => $product['discounted_price'] * $quantity,
                        'name' => $product['name'],
                        'images' => $product['images'],
                        'brand_name' => $product['brand_name'],
                        'category_name' => $product['category_name'],
                    ];
                }

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    foreach ($cartItems as $item) {
                        $user_id = $_SESSION['user_id'];
                        $status = 'processing';
                        $created_at = date('Y-m-d H:i:s');
                        $updated_at = $created_at;
                        $consignee_name = isset($_POST['fullname']) ? $_POST['fullname'] : '';

                        $address = isset($_POST['address']) ? $_POST['address'] : '';
                        $city = isset($_POST['city']) ? $_POST['city'] : '';
                        $district = isset($_POST['district']) ? $_POST['district'] : '';
                        $ward = isset($_POST['ward']) ? $_POST['ward'] : '';

                        $consignee_address = $address . ', ' . $ward . ', ' . $district . ', ' . $city;
                        $consignee_phone_number = isset($_POST['phone']) ? $_POST['phone'] : '';
                        $delivery_date = date('Y-m-d', strtotime("+2 days"));
                        $payment_method = isset($_POST['payment']) ? $_POST['payment'] : 'cod';
                        $total_quantity = array_sum(array_column($cartItems, 'quantity'));
                        $total_price = array_sum(array_column($cartItems, 'subtotal'));

                        $stmtOrder = $conn->prepare("INSERT INTO orders (user_id, status, created_at, updated_at, consignee_name, consignee_address, consignee_phone_number, delivery_date, payment_method, total_quantity, total_price)
                                                     VALUES (:user_id, :status, :created_at, :updated_at, :consignee_name, :consignee_address, :consignee_phone_number, :delivery_date, :payment_method, :total_quantity, :total_price)");
                        $stmtOrder->execute([
                            ':user_id' => $user_id,
                            ':status' => $status,
                            ':created_at' => $created_at,
                            ':updated_at' => $updated_at,
                            ':consignee_name' => $consignee_name,
                            ':consignee_address' => $consignee_address,
                            ':consignee_phone_number' => $consignee_phone_number,
                            ':delivery_date' => $delivery_date,
                            ':payment_method' => $payment_method,
                            ':total_quantity' => $total_quantity,
                            ':total_price' => $total_price
                        ]);

                        $orderId = $conn->lastInsertId();

                        $stmtDetail = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price)
                                                     VALUES (:order_id, :product_id, :quantity, :price)");

                        $stmtDetail->execute([
                            ':order_id' => $orderId,
                            ':product_id' => $item['product_id'],
                            ':quantity' => $item['quantity'],
                            ':price' => $item['price'],
                        ]);

                        $delete_product_id = $item['product_id'];
                        if (isset($_SESSION['cart'][$delete_product_id])) {
                            unset($_SESSION['cart'][$delete_product_id]);
                        }
                        $sqlDelete = "DELETE FROM carts WHERE user_id = :user_id AND product_id = :product_id";
                        $stmtDelete = $conn->prepare($sqlDelete);
                        $stmtDelete->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $stmtDelete->bindParam(':product_id', $delete_product_id, PDO::PARAM_INT);
                        $stmtDelete->execute();
                    }
                    header("Location: order-confirmation.php");
                    exit();
                }
            } else {
                $error = "Sản phẩm không tồn tại!";
            }
        }
    }
} else {
    $error = "ID sản phẩm không hợp lệ!";
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Hàng</title>
    <link
        rel="website icon"
        type="png"
        href="../images/banner/LogoT&T_2.png"
        id="logo" />
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f8f8f8;
        }

        .container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            border: 1px solid #ddd;
        }

        /* Left Section: Form */
        .left-section {
            width: 60%;
            padding: 20px;
            border-right: 1px solid #ddd;
        }

        .left-section h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input,
        select {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .select-group {
            display: flex;
            gap: 10px;
        }

        .select-group select {
            flex: 1;
        }

        /* Right Section: Order Summary */
        .right-section {
            width: 40%;
            padding: 20px;
        }

        .right-section h3 {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .product {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            align-items: center;
        }

        .info_pro {
            flex: 1;
            text-align: left;
        }

        .product img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .total {
            border-top: 1px solid #ddd;
            margin-top: 20px;
            padding-top: 10px;
            font-weight: bold;
        }

        button {
            background-color: #333;
            color: #fff;
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        button:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Left Section: Customer Form -->
        <div class="left-section">

            <h2>Thông tin giao hàng</h2>
            <form method="POST" action="">
                <input type="text" name="fullname" placeholder="Họ và tên" required>
                <input type="tel" name="phone" placeholder="Số điện thoại" required>
                <input type="text" name="address" placeholder="Địa chỉ" required>

                <div class="select-group">
                    <select name="city" required>
                        <option value="">Chọn Tỉnh / Thành</option>
                        <option value="HCM">Hồ Chí Minh</option>
                        <option value="HN">Hà Nội</option>
                    </select>
                    <select name="district" required>
                        <option value="">Chọn Quận / Huyện</option>
                        <option value="Q8">Quận 8</option>
                    </select>
                    <select name="ward" required>
                        <option value="">Chọn Phường / Xã</option>
                        <option value="P01">Phường 01</option>
                        <option value="P02">Phường 02</option>
                        <option value="P03">Phường 03</option>
                        <option value="P04">Phường 04</option>
                        <option value="P05">Phường 05</option>
                        <option value="P06">Phường 06</option>
                        <option value="P07">Phường 07</option>
                        <option value="P08">Phường 08</option>
                        <option value="P09">Phường 09</option>
                        <option value="P10">Phường 10</option>
                        <option value="P11">Phường 11</option>
                        <option value="P12">Phường 12</option>
                        <option value="P13">Phường 13</option>
                        <option value="P14">Phường 14</option>
                        <option value="P15">Phường 15</option>
                        <option value="P16">Phường 16</option>
                    </select>
                </div>

                <h3>Phương thức vận chuyển</h3>
                <p><input type="radio" name="shipping" value="home_delivery" checked> Giao hàng tận nơi - 0đ</p>

                <h3>Phương thức thanh toán</h3>
                <p><input type="radio" name="payment" value="cod" checked> Thanh toán khi nhận hàng</p>
                <p><input type="radio" name="payment" value="onl"> Chuyển khoản qua ngân hàng</p>
                <p><input type="radio" name="payment" value="atm"> Thẻ ATM/Visa/Master/JCB/QR Pay qua cổng VNPAY</p>

                <button type="submit">Đặt hàng</button>
            </form>
        </div>

        <!-- Right Section: Order Summary -->
        <div class="right-section">
            <h3>Giỏ hàng</h3>
            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php else: ?>
                <?php if (!empty($cartItems)) {
                    $subtotal = number_format(array_sum(array_column($cartItems, 'subtotal')), 0, ',', '.');
                    $total = $subtotal;
                    foreach ($cartItems as $item) {
                ?>
                        <div class="product" style="display: flex; align-items: center;">
                            <?php
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
                            <!-- Thông tin sản phẩm -->
                            <div class="info_pro">
                                <span><?php echo htmlspecialchars($item['name']); ?></span><br>
                                <span><?php echo number_format($item['price'], 0, ',', '.'); ?>đ x<?php echo $item['quantity'] ?></span>
                            </div>
                        </div> <?php }
                                ?>
                    <div class="total">
                        <p>Tạm tính: <?php echo $subtotal; ?>đ</p>
                        <p>Phí vận chuyển: Miễn phí</p>
                        <p style="font-size: 18px; color: red;">Tổng cộng: <?php echo $total; ?>đ</p>
                    </div>
            <?php }
            endif; ?>
        </div>
    </div>
</body>

</html>