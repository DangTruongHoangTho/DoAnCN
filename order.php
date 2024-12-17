<?php
include "database/connect.php";
session_start();
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];
    $quantity = isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] > 0 ? intval($_POST['quantity']) : 1;


    // Truy vấn lấy thông tin sản phẩm từ CSDL
    $stmt = $conn->prepare("SELECT p.*, b.name AS brand_name, b.category_id, categories.name AS category_name,
                            products_imgs.images FROM products p
                            JOIN brands b ON p.brand_id = b.id
                            INNER JOIN products_imgs ON p.id = products_imgs.product_id
                            JOIN categories ON b.category_id = categories.id WHERE p.id = :id");
    $stmt->bindParam(':id', $productId, PDO::PARAM_INT);
    $stmt->execute();

    // Lấy sản phẩm
    $product = $stmt->fetch(PDO::FETCH_ASSOC);


    $sql = "SELECT categories.name AS category_name, 
    brands.name AS brand_name FROM brands INNER JOIN categories
    ON brands.category_id = categories.id
    ORDER BY categories.name, brands.name";
    $stmtCate = $conn->prepare($sql);
    $stmtCate->execute();
    $categories = $stmtCate->fetchAll(PDO::FETCH_ASSOC);
    $categoryBrands = [];

    foreach ($categories as $category) {
        $categoryBrands[$category['category_name']][] = $category['brand_name'];
    }
    function removeAccents($string)
    {
        $accents = [
            'a' => ['á', 'à', 'ả', 'ã', 'ạ', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'a'],
            'e' => ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'e'],
            'i' => ['í', 'ì', 'ỉ', 'ĩ', 'ị', 'i'],
            'o' => ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'o'],
            'u' => ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'u'],
            'y' => ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'y'],
            'd' => ['đ', 'd'],
        ];

        foreach ($accents as $nonAccent => $accent) {
            $string = str_replace($accent, $nonAccent, $string);
        }

        $string = str_replace(' ', '-', $string);

        return $string;
    }

    // Giá sản phẩm từ database
    $price = $product['price'];

    // Tính tạm tính và tổng cộng
    $subtotal = $price * $quantity;
    $total = $subtotal; // Phí vận chuyển = 0

    if (!$product) {
        $error = "Sản phẩm không tồn tại!";
    } else {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
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

            $stmtOrder = $conn->prepare("INSERT INTO orders (user_id, status, created_at, updated_at, consignee_name, consignee_address, consignee_phone_number, delivery_date, payment_method)
                                     VALUES (:user_id, :status, :created_at, :updated_at, :consignee_name, :consignee_address, :consignee_phone_number, :delivery_date, :payment_method)");
            $stmtOrder->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmtOrder->bindParam(':status', $status, PDO::PARAM_STR);
            $stmtOrder->bindParam(':created_at', $created_at, PDO::PARAM_STR);
            $stmtOrder->bindParam(':updated_at', $updated_at, PDO::PARAM_STR);
            $stmtOrder->bindParam(':consignee_name', $consignee_name, PDO::PARAM_STR);
            $stmtOrder->bindParam(':consignee_address', $consignee_address, PDO::PARAM_STR);
            $stmtOrder->bindParam(':consignee_phone_number', $consignee_phone_number, PDO::PARAM_STR);
            $stmtOrder->bindParam(':delivery_date', $delivery_date, PDO::PARAM_STR);
            $stmtOrder->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
            $stmtOrder->execute();

            $orderId = $conn->lastInsertId();
            $stmtDetail = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price, total_quantity, total)
                                     VALUES (:order_id, :product_id, :quantity, :price, :total_quantity, :total)");
            $stmtDetail->execute([
                ':order_id' => $orderId,
                ':product_id' => $productId,
                ':quantity' => $quantity,
                ':price' => $product['price'],
                ':total_quantity' => $quantity,
                ':total' => $product['price'] * $quantity
            ]);


            // header("Location: order-confirmation.php");
            // exit();
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
                <div class="product" style="display: flex; align-items: center;">

                    <?php
                    $imageArray = explode(', ', $product['images']);
                    if (!empty($imageArray[0])) {
                        $categoryName = removeAccents($product['category_name']);
                        $brandName = removeAccents($product['brand_name']);

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
                    <div>
                        <span><?php echo htmlspecialchars($product['name']); ?></span><br>
                        <span><?php echo number_format($product['price'], 0, ',', '.'); ?>đ x<?php echo $quantity ?></span>
                    </div>
                </div>
                <div class="total">
                    <p>Tạm tính: <?php echo number_format($subtotal, 0, ',', '.'); ?>đ</p>
                    <p>Phí vận chuyển: Miễn phí</p>
                    <p style="font-size: 18px; color: red;">Tổng cộng: <?php echo number_format($total, 0, ',', '.'); ?>đ</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>