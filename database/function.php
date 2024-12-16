<?php
function searchExactName($searchTerm)
{
    global $conn;
    $sql = "SELECT products.id AS product_id, 
            products.name AS product_name, 
            brands.name AS brand_name,
            categories.name AS category_name, 
            products.price, products.images FROM products 
            INNER JOIN brands ON products.brand_id = brands.id 
            INNER JOIN categories ON brands.category_id = categories.id 
            WHERE products.name LIKE :searchTerm";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':searchTerm', $searchTerm);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hàm tìm kiếm tên tương đối
function searchRelativeName($searchTerm)
{
    global $conn;
    $sql = "SELECT products.id AS product_id, 
            products.name AS product_name, 
            brands.name AS brand_name,
            categories.name AS category_name, 
            products.price, products.images FROM products 
            INNER JOIN brands ON products.brand_id = brands.id 
            INNER JOIN categories ON brands.category_id = categories.id 
            WHERE products.name LIKE :searchTerm";
    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $searchTerm . "%";  // Bao quanh từ khóa với dấu phần trăm
    $stmt->bindParam(':searchTerm', $searchTerm);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Hàm tìm kiếm tổng hợp (tìm tên tuyệt đối trước, nếu không có thì tìm tên tương đối)
function searchProducts($searchTerm)
{
    // Tìm kiếm tuyệt đối (khi người dùng nhập đúng tên)
    $exactResults = searchExactName($searchTerm);
    if (count($exactResults) > 0) {
        return $exactResults; // Trả về kết quả nếu tìm thấy tên tuyệt đối
    }

    // Nếu không tìm thấy, tìm kiếm tên tương đối
    return searchRelativeName($searchTerm);
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
function getCartItems()
{
    return $_SESSION['cart'] ?? [];
}

function getCartTotalItems()
{
    $totalItems = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalItems += $item['quantity'];
        }
    }
    return $totalItems;
}

function getCartTotalPrice()
{
    $totalPrice = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
    }
    return $totalPrice;
}
function isEmailExists($conn, $email)
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
}
