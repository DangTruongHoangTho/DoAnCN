<?php include "layout/header.php";
include "layout/banner.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['search'])) {
  $searchTerm = $_GET['search'];
  $products = searchProducts($searchTerm);
} else {
  $sql = "SELECT products.id AS product_id, products.name AS product_name, 
            brands.name AS brand_name, categories.name AS category_name, 
            products.price, products.discounted_price, MIN(products_imgs.images) AS images FROM products 
            INNER JOIN brands ON products.brand_id = brands.id 
            INNER JOIN products_imgs ON products.id = products_imgs.product_id
            INNER JOIN categories ON brands.category_id = categories.id 
            GROUP BY products.id LIMIT 4";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!-- Content Begin -->
<main class="main">
  <div class="justify-content-center text-center">
    <section class="block-products" id="new">
      <div class="container">
        <h1
          class="category-heading pt-4"
          style="font-family: 'Times New Roman', Times, serif">
          <?php echo isset($searchTerm) ? 'Kết quả tìm kiếm cho: ' . htmlspecialchars($searchTerm) : 'Hàng Mới'; ?>
        </h1>
      </div>
      <div class="container p-row">
        <button class="carousel-btn left" onclick="moveLeft()">&lt;</button>
        <div class="carousel-track">
          <?php if (!empty($products)) {
            foreach ($products as $product) { ?>
              <div class="p-item">
                <?php
                $productSlug = removeAccents($product['product_name']);
                echo "<a class='pro-a-href' href='chitietSP.php?id={$product['product_id']}&slug={$productSlug}'>";
                $imagePath = getImagePath($product['category_name'], $product['brand_name'], $product['images']);
                ?>
                  <img
                    src="<?php echo $imagePath; ?>"
                    alt=""
                    class="w-50" />
                <?php echo "</a>";
                 ?>
                <div class="pro-vendor"><strong><?php echo htmlspecialchars($product['brand_name']); ?></strong></div>
                <h6 class="pro-name">
                  <?php
                  echo "<a class='pro-a-href' href='chitietSP.php?id={$product['product_id']}&slug={$productSlug}'>{$product['product_name']}</a>";
                  ?>
                </h6>
                <div class="box-pro-prices">
                  <p class="pro-price"><strong><?php echo number_format($product['discounted_price'], 0, ',', '.'); ?>đ</strong></p>
                </div>
              </div>
              <div class="box-pro-detail">
              </div>
            <?php }
          } else { ?>
            <p>Không có sản phẩm nào</p>
          <?php } ?>
        </div>
        <button class="carousel-btn right" onclick="moveRight()">&gt;</button>
      </div>
    </section>
  </div>
</main>
<!-- Content End -->
<?php include "layout/footer.php"; ?>