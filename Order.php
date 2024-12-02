<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Hàng Nước Hoa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select, textarea, button {
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        textarea {
            resize: none;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .product-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .product-options label {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>
<button onclick="renderCart()">Xem Giỏ Hàng</button>
        
        <!-- Cart Display -->
        <div id="cart"></div>
    <div class="container">
        <h1>Đặt Hàng Nước Hoa</h1>
        <form action="submit-order.php" method="POST">
            <label for="name">Họ và Tên</label>
            <input type="text" id="name" name="name" placeholder="Nhập họ và tên của bạn" required>
            
            <label for="phone">Số Điện Thoại</label>
            <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
            
            <label for="address">Địa Chỉ Giao Hàng</label>
            <textarea id="address" name="address" rows="3" placeholder="Nhập địa chỉ của bạn" required></textarea>
            
            <label>Số Lượng</label>
            <input type="number" name="quantity" min="1" max="10" value="1" required>
            
            <label for="notes">Ghi Chú (Tùy chọn)</label>
            <textarea id="notes" name="notes" rows="2" placeholder="Ghi chú nếu có..."></textarea>
            
            <button type="submit">Đặt Hàng</button>
        </form>
    </div>
    <script src="./js/main.js"></script>
</body>
</html>
