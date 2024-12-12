<?php include "layout/header.php";
include "layout/banner.php";
$sql = "SELECT products.id AS product_id, 
            products.name AS product_name, 
            brands.name AS brand_name,
            categories.name AS category_name, 
            products.price, products.images FROM products 
            INNER JOIN brands ON products.brand_id = brands.id 
            INNER JOIN categories ON brands.category_id = categories.id LIMIT 4";
$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

?>
<!-- Content Begin -->
<main class="main">
  <div class="justify-content-center text-center">
    <section class="block-products" id="burberry">
      <div class="container">
        <h1
          class="category-heading pt-4"
          style="font-family: 'Times New Roman', Times, serif">
          Hàng Mới
        </h1>
      </div>
      <div class="container p-row">
        <button class="carousel-btn left" onclick="moveLeft()">&lt;</button>
        <div class="carousel-track">
          <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
              <div class="p-item">
                <?php
                $productSlug = removeAccents($product['product_name']);
                echo "<a class='pro-a-href' href='chitietSP.php?id={$product['product_id']}&slug={$productSlug}'>";
                $imageArray = explode(', ', $product['images']);
                if (!empty($imageArray[0])) {
                  $categoryName = removeAccents($product['category_name']);
                  $brandName = removeAccents($product['brand_name']);

                  $categoryNameFormated = str_replace('-', '', strtoupper($categoryName));
                  $brandNameFormatted = str_replace('-', '_', strtoupper($brandName));
                  $imagePath = "images/categories/" . $categoryNameFormated . "/" . $brandNameFormatted . "/" . htmlspecialchars(trim($imageArray[0]));
                ?>
                  <img
                    src="<?php echo $imagePath; ?>"
                    alt=""
                    class="w-50" />
                <?php echo "</a>";
                } ?>
                <div class="pro-vendor"><strong><?php echo htmlspecialchars($product['brand_name']); ?></strong></div>
                <h6 class="pro-name">
                  <?php
                  echo "<a class='pro-a-href' href='chitietSP.php?id={$product['product_id']}&slug={$productSlug}'>{$product['product_name']}</a>";
                  ?>
                </h6>
                <div class="box-pro-prices">
                  <p class="pro-price"><strong><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</strong></p>
                </div>
              </div>
              <div class="box-pro-detail">
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Không có sản phẩm nào</p>
          <?php endif; ?>
        </div>
        <button class="carousel-btn right" onclick="moveRight()">&gt;</button>
      </div>
    </section>
  </div>
</main>
<!-- Content End -->
<?php include "layout/footer.php"; ?>