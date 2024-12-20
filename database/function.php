<?php
function searchExactName($searchTerm)
{
    global $conn;
    $sql = "SELECT products.id AS product_id, 
            products.name AS product_name, 
            brands.name AS brand_name,
            categories.name AS category_name, 
            products.discounted_price, products_imgs.images FROM products 
            INNER JOIN brands ON products.brand_id = brands.id 
            INNER JOIN categories ON brands.category_id = categories.id 
            INNER JOIN products_imgs ON products.id = products_imgs.product_id
            WHERE products.name LIKE :searchTerm";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchRelativeName($searchTerm)
{
    global $conn;
    $sql = "SELECT products.id AS product_id, 
            products.name AS product_name, 
            brands.name AS brand_name,
            categories.name AS category_name, 
            products.discounted_price, products_imgs.images FROM products 
            INNER JOIN brands ON products.brand_id = brands.id 
            INNER JOIN categories ON brands.category_id = categories.id
            INNER JOIN products_imgs ON products.id = products_imgs.product_id
            WHERE products.name LIKE :searchTerm";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':searchTerm', "%$searchTerm%", PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchProducts($searchTerm)
{
    $exactResults = searchExactName($searchTerm);
    if (count($exactResults) > 0) {
        return $exactResults;
    }

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
function getImagePath($categoryName, $brandName, $imageName)
{
    $categoryName = removeAccents($categoryName);
    $brandName = removeAccents($brandName);

    $categoryNameFormated = str_replace('-', '', strtoupper($categoryName));
    $brandNameFormatted = str_replace('-', '_', strtoupper($brandName));

    $imagePath = "./images/categories/" . $categoryNameFormated . "/" . $brandNameFormatted . "/" . $imageName;

    // Kiểm tra xem file ảnh có tồn tại không
    if (!file_exists($imagePath)) {
        $imagePath = "./images/default.jpg"; // Sử dụng ảnh mặc định nếu không tồn tại
    }

    return $imagePath;
}
function getImagePath1($categoryName, $brandName, $imageName)
{
    $categoryName = removeAccents($categoryName);
    $brandName = removeAccents($brandName);

    $categoryNameFormated = str_replace('-', '', strtoupper($categoryName));
    $brandNameFormatted = str_replace('-', '_', strtoupper($brandName));

    $imagePath = "../images/categories/" . $categoryNameFormated . "/" . $brandNameFormatted . "/" . $imageName;

    // Kiểm tra xem file ảnh có tồn tại không
    if (!file_exists($imagePath)) {
        $imagePath = "./images/default.jpg"; // Sử dụng ảnh mặc định nếu không tồn tại
    }

    return $imagePath;
}
function getCartTotalItems()
{
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return 0;
    }
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
function loadCartFromDatabase($userId, $conn)
{
    $sql = "SELECT product_id, quantity FROM carts WHERE user_id = :user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['user_id' => $userId]);
    $cart = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $_SESSION['cart'] = [];
    foreach ($cart as $item) {
        $_SESSION['cart'][$item['product_id']] = [
            'quantity' => $item['quantity']
        ];
    }
}
function isEmailExists($conn, $email)
{
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
}
