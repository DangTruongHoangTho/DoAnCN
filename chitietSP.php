<?php
include "layout/header_pro.php";
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 1;


// Kiểm tra ID hợp lệ
if ($productId <= 0) {
  die("ID sản phẩm không hợp lệ.");
}
$stmt = $conn->prepare("SELECT p.*, b.name AS brand_name, b.category_id, categories.name AS category_name
                            FROM products p
                            JOIN brands b ON p.brand_id = b.id
                            JOIN categories ON b.category_id = categories.id
                            WHERE p.id = ?");
$stmt->execute([$productId]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
  die("Sản phẩm không tồn tại.");
}

$productCategoryId = $product['category_id'];
$categoryNameInf = $product['category_name'] ?? '';
$brandNameInf = $product['brand_name'] ?? '';

// Lấy danh sách sản phẩm liên quan dựa trên category_id hoặc brand_id
$sqlRelated = "SELECT products.id AS product_id, products.name AS product_name, categories.name AS category_name,
              brands.name AS brand_name, products.price, products.images FROM products 
              INNER JOIN brands ON products.brand_id = brands.id
              JOIN categories ON brands.category_id = categories.id 
              WHERE brands.category_id = :category_id 
              AND products.id != :product_id LIMIT 4";
$stmtRelated = $conn->prepare($sqlRelated);
$stmtRelated->execute([
  ':category_id' => $productCategoryId, // Lấy ID danh mục từ sản phẩm hiện tại
  ':product_id' => $productId,         // Bỏ qua sản phẩm hiện tại
]);
$relatedProducts = $stmtRelated->fetchAll(PDO::FETCH_ASSOC);


?>

<!-- Content Begin-->
<main class="main">
  <div class="page-template noneBackground">
    <section class="block-products" id="burberry">
      <div class="container p-row">
        <div class="row">
          <div class="col-sm-4 col-md-4 col-xs-6 col-lg-5">
            <?php $imageArray = explode(', ', $product['images']); // Tách chuỗi thành mảng
            if (!empty($product['images'])) {
              foreach ($imageArray as $index => $image) {
                $categoryName = removeAccents($product['category_name']);
                $brandName = removeAccents($product['brand_name']);

                $categoryNameFormated = str_replace('-', '', strtoupper($categoryName));
                $brandNameFormatted = str_replace('-', '_', strtoupper($brandName));
                $imagePath = "images/categories/" . $categoryNameFormated . "/" . $brandNameFormatted . "/" . htmlspecialchars(trim($image));
            ?>
                <div class="p-item">
                  <div class="mySlides">
                    <div class="numbertext"><?php echo ($index + 1) . " / " . count($imageArray); ?></div>
                    <img src="<?php echo $imagePath; ?>" style="width: 100%; height: 400px;" />
                  </div>
                </div>
                <div class="row">
                  <div class="column">
                    <img class="demo cursor" src="<?php echo $imagePath; ?>" style="background-color: white; width: 200%" onclick="currentSlide(<?php echo $index + 1; ?>)" />
                  </div>
                </div>
            <?php }
            } ?>
          </div>
          <div class="col-sm-4 col-md-4 col-xs-6 col-lg-6">
            <div class="product__details__text">
              <h3><?php echo htmlspecialchars($product['name']); ?></h3>
              <ul>
                <li>Thương hiệu: <span><strong><?php echo htmlspecialchars($product['brand_name']); ?></strong></span></li>
                <li></li>
              </ul>
              <div class="product__details__price"><?php echo number_format($product['price'], 0, ',', '.') . " ₫"; ?></div>
              <div class="product__details__quantity">
                <div class="quantity">
                  <div class="pro-qty">
                    <input type="text" id="quantity" min="1" value="1" />
                  </div>
                </div>
              </div>
              <a href="./Order.php?id=<?php echo $productId; ?>" onclick="handleOrder(event, 2, 'Burberry Eau de Parfum 100ml', 6500000)" class="primary-btn">Đặt hàng</a>
              <a href="#" class="primary-btn">Thêm vào giỏ hàng</a>
            </div>
          </div>
        </div>
      </div>
    </section>
    <div class="col-lg-12">
      <div class="product-summary-tab">
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="nav-item">
            <a
              class="nav-link summary-tab active"
              href="#tab-detail"
              aria-controls="home"
              role="tab"
              data-toggle="tab"
              aria-selected="true">Chi tiết sản phẩm</a>
          </li>
          <li role="presentation" class="nav-item">
            <a
              class="nav-link summary-tab"
              href="#tab-usage"
              aria-controls="profile"
              role="tab"
              data-toggle="tab"
              aria-selected="false">Sử dụng và bảo quản</a>
          </li>
          <li role="presentation" class="nav-item">
            <a
              class="nav-link summary-tab"
              href="#tab-shipping"
              aria-controls="profile"
              role="tab"
              data-toggle="tab"
              aria-selected="false">Vận chuyển và đổi trả</a>
          </li>
        </ul>

        <!-- Tab panes -->
        <div class="row">
          <div class="tab-content col-md-8 col-xs-12">
            <div role="tabpanel" class="tab-pane active" id="tab-detail">
              <div class="product-attribute-list">
                <dl
                  class="product-attribute-list__item dl-horizontal attr-brand">
                  <dt><b>Mã hàng</b></dt>
                  <dd id="prod_variant_detail_sku"><?php echo htmlspecialchars($product['id']); ?></dd>
                </dl>
                <dl
                  class="product-attribute-list__item dl-horizontal attr-brand">
                  <dt><b>Thương hiệu</b></dt>
                  <dd>
                    <a href="chitietBrand.php?category=<?php echo urlencode($categoryNameInf); ?>&brand=<?php echo urlencode($brandNameInf); ?>"><?php echo htmlspecialchars($product['brand_name']); ?></a>
                  </dd>
                </dl>

                <dl class="product-attribute-list__item dl-horizontal">
                  <dt><b>Xuất xứ</b></dt>
                  <dd><?php echo htmlspecialchars($product['origin']); ?></dd>
                </dl>

                <dl class="product-attribute-list__item dl-horizontal">
                  <dt><b>Năm phát hành</b></dt>
                  <dd><?php echo htmlspecialchars($product['year_of_release']); ?></dd>
                </dl>

                <dl class="product-attribute-list__item dl-horizontal">
                  <dt><b>Nhóm hương</b></dt>
                  <dd><?php echo htmlspecialchars($product['incense_group']); ?></dd>
                </dl>

                <dl class="product-attribute-list__item dl-horizontal">
                  <dt><b>Phong cách</b></dt>
                  <dd><?php echo htmlspecialchars($product['style']); ?></dd>
                </dl>
              </div>
              <div class="product-description">
                <div class="description-content">
                  <div class="description-productdetail collapsed">
                    <?php
                    $paragraphs = explode("\n", $product['description']); // Tách chuỗi thành mảng các đoạn
                    foreach ($paragraphs as $paragraph) {
                      if (trim($paragraph)) { // Bỏ qua đoạn trống
                        echo '<p>' . htmlspecialchars($paragraph) . '</p>';
                      }
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-usage">
              <p>
                <b>Cách sử dụng được T&T Store đề xuất dành cho bạn:</b>
              </p>
              <ul style="list-style: disc; padding-left: 18px">
                <li>
                  Nước hoa mang lại mùi hương cho cơ thể bạn thông qua
                  việc tiếp xúc lên da và phản ứng với hơi ấm trên cơ thể
                  của bạn. Việc được tiếp xúc với các bộ phận như cổ tay,
                  khuỷu tay, sau tai, gáy, cổ trước là những vị trí được
                  ưu tiên hàng đầu trong việc sử dụng nước hoa bởi sự kín
                  đáo và thuận lợi trong việc tỏa mùi hương.
                </li>
                <li>
                  Sau khi sử dụng, xịt nước hoa lên cơ thể, tránh dùng tay
                  chà xát hoặc làm khô da bằng những vật dụng khác, điều
                  này phá vỡ các tầng hương trong nước hoa, khiến nó có
                  thể thay đổi mùi hương hoặc bay mùi nhanh hơn.
                </li>
                <li>
                  Để chai nước hoa cách vị trí cần dùng nước hoa trong
                  khoảng 15-20cm và xịt mạnh, dứt khoát để mật đổ phủ của
                  nước hoa được rộng nhất, tăng độ bám tỏa trên da của
                  bạn.
                </li>
                <li>
                  Phần cổ tay được xịt nước hoa thường có nhiều tác động
                  như lúc rửa tay, đeo vòng, đồng hồ, do đó để đảm bảo mùi
                  hương được duy trì, bạn nên sử dụng nước hoa ở cổ tay ở
                  tần suất nhiều hơn lúc cần thiết.
                </li>
                <li>
                  Nước hoa có thể bám tốt hay không tốt tùy thuộc vào thời
                  gian, không gian, cơ địa, chế độ sinh hoạt, ăn uống của
                  bạn, việc sử dụng một loại nước hoa trong thời gian dài
                  có thể khiến bạn bị quen mùi, dẫn đến hiện tượng không
                  nhận biết được mùi hương. Mang theo nước hoa bên mình
                  hoặc trang bị những mẫu mini tiện dụng để giúp bản thân
                  luôn tự tin mọi lúc mọi nơi.
                </li>
              </ul>

              <p><b>Bảo quản nước hoa:</b></p>
              <ul style="list-style: disc; padding-left: 18px">
                <li>
                  Nước hoa phổ thông (Designer) thường không có hạn sử
                  dụng, ở một số Quốc gia, việc ghi chú hạn sử dụng là
                  điều bắt buộc để hàng hóa được bán ra trên thị trường.
                  Hạn sử dụng ở một số dòng nước hoa được chú thích từ 24
                  đến 36 tháng, và tính từ ngày bạn mở sản phẩm và sử dụng
                  lần đầu tiên.
                </li>
                <li>
                  Nước hoa là tổng hợp của nhiều thành phần hương liệu tự
                  nhiên và tổng hợp, nên bảo quản nước hoa ở những nơi khô
                  thoáng, mát mẻ, tránh nắng, nóng hoặc quá lạnh, lưu ý
                  không để nước hoa trong cốp xe, những nơi có nhiệt độ
                  nóng lạnh thất thường...
                </li>
              </ul>
            </div>
            <div role="tabpanel" class="tab-pane" id="tab-shipping">
              <p style="font-size: 15px"><b>Vận chuyển:</b></p>
              <p><b>TP Hồ Chí Minh</b></p>
              Các đơn hàng tại Thành phố Hồ Chí Minh có thể chọn phương
              thức thanh toán COD hoặc chuyển khoản, T&T Store cam kết
              các quận trung tâm quý khách sẽ nhận được hàng chậm nhất
              trong 3 giờ kể từ khi chốt đơn đối với những đơn hàng trong
              ngày từ khung giờ 8.30 am đến 22.00 pm. Nếu bạn muốn ship
              hẹn giờ, liên hệ với tổng đài CSKH 1700 1722 hoặc fangape
              Facebook, Instagram của chúng tôi để được hỗ trợ.<br /><br />

              <p><b>Các Tỉnh/Thành khác</b></p>
              T&T Store thực hiện lên đơn hàng và thanh toán với hình
              thức COD (Nhận hàng thanh toán) hoặc chuyển khoản trước khi
              Ship.<br /><br />

              <p><b>Cách Thức Đóng Hàng</b></p>
              Với các đơn hàng ship Tỉnh, các sản phẩm của bạn sẽ được
              đóng gói cẩn thận và kỹ lưỡng, bao gồm nhiều lớp chống sốc,
              đóng hộp carton và dán keo dính cẩn thận kèm hóa đơn, đảm
              bảo các sản phẩm được đến tay khách hàng của chúng tôi một
              cách tốt nhất.<br /><br />

              <p><b>Hỗ trợ từ T&T Store</b></p>
              Trong trường hợp các bạn mua tại Thành phố Hồ Chí Minh cần
              hỗ trợ đóng hàng, hãy liên hệ hotline 1700 1722 hoặc fangape
              Facebook, Instagram của chúng tôi để được hỗ trợ.
              <hr />
              <p style="font-size: 15px"><b>Đổi trả hàng hóa:</b></p>
              Hàng hóa T&T Store bán ra đảm bảo là hàng chính hãng 100%,
              chúng tôi cam kết không bán hàng giả, hàng nhái, hàng không
              đảm bảo chất lượng.<br /><br />

              <p><b>Chính sách đổi hàng hóa:</b></p>
              <p>
                Các trường hợp được đổi lại hàng hóa:<br />
                - Với những sản phẩm lỗi kết cấu sản phẩm do quá trình sản
                xuất của hãng, hay lỗi do vận chuyển dẫn đến việc sản phẩm
                bị méo mó, thay đổi hình dạng, hư hỏng bộ phận vòi xịt,
                ống xịt, bị nứt, vỡ.<br />
                - Đối với những sản phẩm đổi vì lý do cá nhân (tặng, được
                tặng), sản phẩm đổi chỉ được áp dụng trong thời gian 10
                ngày kể từ khi sản phẩm được bán ra. Sản phẩm đổi phải đảm
                bảo chưa được sửa dụng, đối với hàng Full seal thì phải
                còn nguyên seal, đối với các sản phẩm Giftset, Tester phải
                đảm bảo còn nguyên hộp, sản phẩm chưa bị can thiệp và sử
                dụng. sản phẩm sẽ được chúng tôi kiểm tra lại để đảm bảo
                sản phẩm là hàng hóa của bên chúng tôi phân phối.<br /><br />
              </p>
              <p>
                <b>Các trường hợp không được áp dụng đổi lại hàng hóa:</b>
              </p>
              <p>
                - Sản phẩm không phải do T&T Store cung cấp, không chứng
                minh được nguồn gốc của sản phẩm (hóa đơn, thời gian mua
                hàng)<br />
                - Sản phẩm được mua quá 10 ngày kể từ khi sản phẩm được
                bán ra.<br />
                - Sản phẩm đã được sử dụng hoặc bị tác động từ người mua
                dẫn đến hư hại.<br /><br />
              </p>
              <p><b>Quy trình đổi hàng hóa:</b></p>
              <p>
                - Sau khi bạn đáp ứng được các điều kiện về đổi lại hàng
                hóa của chúng tôi, hãy liên hệ với chúng tôi để được hỗ
                trợ.<br />
                - Sau khi tiếp nhận thông tin và check kiểm các điều kiện,
                nếu bạn đáp ứng đủ điều kiện, chúng tôi sẽ hỗ trợ ngay và
                nhanh nhất cho bạn.<br /><br />
              </p>
              <p><b>Chính sách trả hàng hóa:</b></p>
              <p>
                Các trường hợp được trả lại hàng hóa:<br />
                - Với những sản phẩm lỗi kết cấu sản phẩm do quá trình sản
                xuất của hãng, hay lỗi do vận chuyển dẫn đến việc sản phẩm
                bị méo mó, thay đổi hình dạng, hư hỏng bộ phận vòi xịt,
                ống xịt, bị nứt, vỡ.<br /><br />
              </p>
              <p>
                <b>Các trường hợp không được chấp nhận trả lại hàng
                  hóa:</b>
              </p>
              <p>
                - Sản phẩm bị tác động từ phía người sử dụng dẫn đến hư
                hỏng, móp méo, thay đổi hình dạng.<br />
                - Sản phẩm đã được sử dụng<br />
                - Chúng tôi không chấp nhận trả lại hàng, hoàn lại tiền
                với các trường hợp muốn trả lại hàng vì lý do cá nhân như
                không thích nữa, thay đổi ý định, hay các lý do cá nhân
                khác.<br /><br />
              </p>
              <p><b>Quy trình trả hàng, hoàn tiền:</b></p>
              <p>
                - Sau khi bạn đáp ứng được các điều kiện về trả lại hàng
                hóa của chúng tôi, hãy liên hệ với chúng tôi để được hỗ
                trợ<br />
                - Sau khi tiếp nhận thông tin và check kiểm các điều kiện,
                nếu bạn đáp ứng đủ điều kiện, chúng tôi sẽ hỗ trợ ngay và
                nhanh nhất cho bạn.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Related Product Section Begin -->
    <section class="related-product">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="section-title related__product__title">
              <h2>Gợi ý sản phẩm</h2>
            </div>
          </div>
        </div>
        <div class="row">
          <?php foreach ($relatedProducts as $related) { ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
              <div class="product__item">
                <?php
                $relatedSlug = removeAccents($related['product_name']);
                echo "<a class='pro-a-href' href='chitietSP.php?id={$related['product_id']}&slug={$relatedSlug}'>";
                $imageArray = explode(', ', $related['images']);
                if (!empty($imageArray[0])) {
                  $categoryName = removeAccents($related['category_name']);
                  $brandName = removeAccents($related['brand_name']);
                  $categoryNameFormated = str_replace('-', '', strtoupper($categoryName));
                  $brandNameFormatted = str_replace('-', '_', strtoupper($brandName));
                  $imagePath = "images/categories/" . $categoryNameFormated . "/" . $brandNameFormatted . "/" . htmlspecialchars(trim($imageArray[0]));
                } ?>
                <div class="product__item__pic set-bg" style="background-image: url('<?php echo $imagePath; ?>');"></div>
                </a>
                <div class="product__item__text">
                <div class="pro-vendor"><strong><?php echo htmlspecialchars($related['brand_name']); ?></strong></div>
                  <h6>
                    <?php
                    echo "<a class='pro-a-href' href='chitietSP.php?id={$related['product_id']}&slug={$relatedSlug}'>{$related['product_name']}</a>";
                    ?>
                  </h6>
                  <h5><?php echo number_format($related['price'], 0, ',', '.') . " ₫"; ?></h5>
                </div>
              </div>

            </div>
          <?php } ?>
        </div>
      </div>
    </section>
    <!-- Related Product Section End -->
  </div>
</main>

<!-- Content End -->
<?php include "layout/footer.php"; ?>