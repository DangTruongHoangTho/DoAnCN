 <!-- Footer Begin-->
 <div class="footer" id="contact">
        <div class="container mt-2">
          <div class="row justify-content-center text-center">
            <div class="col-md-3 contact-info">
              <h2>Thông tin chi tiết</h2>
              <p><strong>Email:</strong> T&TStore@gmail.com</p>
              <p>
                <strong>Địa chỉ:</strong> 180 Đ. Cao Lỗ, Phường 4, Quận 8, Thành
                phố Hồ Chí Minh
              </p>
            </div>
            <div class="col-lg-5 col-md-8 col-sm-10">
              <div class="footer__widget">
                <h2>Yêu cầu hỗ trợ</h2>
                <ul>
                  <li><a href="about.php">Về chúng tôi</a></li>
                  <li><a href="#">Về cửa hàng chúng tôi</a></li>
                  <li><a href="#">Mua sắm an toàn</a></li>
                  <li><a href="#">Thông tin giao dịch</a></li>
                  <li><a href="#">Bảo mật</a></li>
                  <li><a href="#">Hồ sơ trang web</a></li>
                </ul>
                <ul>
                  <li><a href="#">Chúng tôi là ai?</a></li>
                  <li><a href="#">Dịch vụ của chúng tôi</a></li>
                  <li><a href="#">Dự án</a></li>
                  <li><a href="#">Liên hệ</a></li>
                  <li><a href="#">Sự thay đổi</a></li>
                  <li><a href="#">Lời cảm ơn</a></li>
                </ul>
              </div>
            </div>
            <div class="col-lg-4 col-md-12">
              <div class="footer__widget">
                <h3>
                  Nơi Mùi Hương Là <br />
                  Bạn Đồng Hành
                </h3>

                <p>
                  GỌI ĐẶT MUA:
                  <a class="tel-a-classing call" href="tel:17001722">
                    <span
                      ><img
                        src="//theme.hstatic.net/1000340570/1000964732/14/icon-phone.svg?v=5317"
                        alt="phone"
                    /></span>
                    1700 1722 (8:30 - 22:00)
                  </a>
                </p>
              </div>
              <h4>Theo dõi chúng tôi</h4>
              <div class="social-icons mt-3 m-3">
                <div class="footer__widget__social">
                  <a href="#"><i class="fa fa-facebook"></i></a>
                  <a href="#"><i class="fa fa-instagram"></i></a>
                  <a href="#"><i class="fa fa-twitter"></i></a>
                </div>
              </div>
            </div>
          </div>
          <div class="separator"></div>
          <div class="row">
            <div class="col-lg-12">
              <div class="footer__copyright">
                <p>
                  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | With <i class="fa fa-heart" aria-hidden="true"></i> by T&TStore</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Footer End -->
    </div>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script>
      function displayPass(){
          var passwordField = document.getElementById("password");
          var passwordField1 = document.getElementById("confirm_password");
          var checkBox = document.querySelector('input[type="checkbox"]');
          if (checkBox.checked){
              passwordField.type = 'text';
              passwordField1.type = 'text';
          } else {
              passwordField.type = 'password';
              passwordField1.type = 'password';
          }
      }
    </script>
  </body>
</html>