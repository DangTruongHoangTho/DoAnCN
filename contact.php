<?php include "layout/header.php"; ?>

      <!-- Content Begin-->
      <main class="">
        <div class="page-template noneBackground">
          <div class="container">
            <div class="row">
              <div class="col-md-12">
                <div class="page-template__knowledge">
                  <div class="justify-content-center text-center">
                    <p><h2>Liên hệ với chúng tôi</h2></p>
                  </div>
                  <div class="content-page">
                    <p></p>
                    <div class="gE iv gt">
                      <div class="row">
                        <div class="col-md-6 col-lg-6 col-12">
                          <form id="contactForm">
                            <div class="form-group row">
                              <label
                                for="name"
                                class="col-sm-3 col-form-label mb-1"
                                >Tên</label
                              >
                              <div class="col-sm-9">
                                <input
                                  type="text"
                                  class="form-control"
                                  id="name"
                                  placeholder="Nhập tên"
                                  pattern="[A-Za-z]{8,30}" title="[a-z][A-Z] && 8->30 character"
                                />
                                <div
                                  class="error-message"
                                  id="nameMessage"
                                ></div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label
                                for="email"
                                class="col-sm-3 col-form-label mb-1"
                                >Email</label
                              >
                              <div class="col-sm-9">
                                <input
                                  type="email"
                                  class="form-control"
                                  id="email1"
                                  placeholder="Nhập email"
                                />
                                <div
                                  class="error-message"
                                  id="EmailErrorMessage1"
                                ></div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label
                                for="message"
                                class="col-sm-3 col-form-label mb-1"
                                >Lời nhắn</label
                              >
                              <div class="col-sm-9">
                                <textarea
                                  class="form-control"
                                  id="message"
                                  rows="4"
                                  placeholder="Lời nhắn của bạn..."
                                ></textarea>
                              </div>
                              <div
                                class="error-message"
                                id="messegeErrorMessage"
                              ></div>
                            </div>
                            <div class="form-group row">
                              <div
                                class="col-sm-12 col-lg-12 col-xl-12 w-100 justify-content-center text-center"
                              >
                                <button type="submit" class="btn btn-contact">
                                  Gửi
                                </button>
                              </div>
                            </div>
                          </form>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                          <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6592.4985499755685!2d106.6789931371774!3d10.
                        74041089759156!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f62a90e5dbd%3A0x674d5126513db295!
                        2zVHLGsOG7nW5nIMSQ4bqhaSBo4buNYyBDw7RuZyBuZ2jhu4cgU8OgaSBHw7Ju!5e0!3m2!1svi!
                        2s!4v1714125058390!5m2!1svi!2s"
                            width="100%"
                            height="250"
                            style="border: 0"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                          >
                          </iframe>
                        </div>
                      </div>
                    </div>
                    <div class=""><div id=":mo" class="ii gt"></div></div>
                    <p></p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
      <!-- Content End -->
<?php include "layout/footer.php" ?>