"use strict";

function validateForm() {
  var name = document.getElementById("name").value.trim();
  var email = document.getElementById("email1").value.trim();
  var message = document.getElementById("message").value.trim();

  var errorname = document.getElementById("nameMessage");
  var erroremail = document.getElementById("EmailErrorMessage1");
  var errormessage = document.getElementById("messegeErrorMessage");

  var isValid = true;

  if (name === "") {
    errorname.innerHTML = "Vui lòng nhập tên của bạn.";
    isValid = false;
  } else {
    errorname.innerHTML = "";
  }
  if (email === "") {
    erroremail.innerHTML = "Vui lòng nhập email của bạn.";
    isValid = false;
  } else {
    erroremail.innerHTML = "";
  }
  if (message === "") {
    errormessage.innerHTML = "Vui lòng nhập lời nhắn của bạn.";
    isValid = false;
  } else {
    errormessage.innerHTML = "";
  }
  if (!isValid) {
  }

  return isValid;
}

function validateEmail(email) {
  var emailRegex = /\S+@\S+\.\S+/;
  return emailRegex.test(email);
}

$(document).ready(function () {
  jQuery("#contactForm").submit(function (event) {
    event.preventDefault();

    var isValid = validateForm();

    if (isValid) {
      alert(
        "Chúng tôi đã nhận lời nhắn của bạn. Cảm ơn bạn vì đã liên hệ với chung tôi. Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất."
      );
    }
  });

  var proQty = $(".pro-qty");
  if (proQty.find(".qtybtn").length === 0) {
    proQty.prepend('<span class="dec qtybtn">-</span>');
    proQty.append('<span class="inc qtybtn">+</span>');
  }
  proQty.on("click", ".qtybtn", function () {
    var $button = $(this);
    var oldValue = $button.parent().find("input").val();
    var newVal;
    if ($button.hasClass("inc")) {
      newVal = parseFloat(oldValue) + 1;
    } else {
      newVal = Math.max(0, parseFloat(oldValue) - 1);
    }
    $button.parent().find("input").val(newVal);
  });

  let slideIndex = 1;
  showSlides(slideIndex);

  window.plusSlides = function (n) {
    showSlides((slideIndex += n));
  };

  window.currentSlide = function (n) {
    showSlides((slideIndex = n));
  };

  function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("demo");
    if (n > slides.length) {
      slideIndex = 1;
    }
    if (n < 1) {
      slideIndex = slides.length;
    }
    for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
    }
    for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
  }

  document.addEventListener("DOMContentLoaded", function () {
    const cartIcon = document.querySelector(".dropdown");
    const cartMenu = document.querySelector(".dropdown-menu");

    // Sự kiện hover để hiển thị menu giỏ hàng
    cartIcon.addEventListener("mouseenter", function () {
      cartMenu.style.display = "block"; // Hiển thị dropdown menu
    });

    // Ẩn menu khi chuột rời khỏi phần giỏ hàng
    cartIcon.addEventListener("mouseleave", function () {
      cartMenu.style.display = "none"; // Ẩn dropdown menu
    });
  });
});

function ajax_giohang() {
  $("#spm").removeClass("ajaxing");
  $("#mntq").removeClass("ajaxing");
  $("#dgg").removeClass("ajaxing");
  $.ajax({
    url: "ajax_calling.php",
    type: "get",
    dataType: "text",
    data: {
      fname: "php_giohang",
    },
    success: function (result) {
      $("#content").html(result);
    },
  });
}

function handleOrder(event, productId, productName, productPrice) {
  event.preventDefault();

  const quantityElement = document.getElementById("quantity");
  const quantity = quantityElement
    ? parseInt(quantityElement.value, 10) || 1
    : 1;

  addToCart(productId, productName, productPrice, quantity);
  window.location.href = "./order.php";
}

function addToCart(productId, productName, productPrice, quantity) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  cart.push({
    id: productId,
    name: productName,
    price: productPrice,
    quantity,
  });
  localStorage.setItem("cart", JSON.stringify(cart));
  alert(`Đã thêm ${quantity} x ${productName} vào giỏ hàng!`);
}
function renderCart() {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  let cartContainer = document.getElementById("cart");
  if (cart.length === 0) {
    cartContainer.innerHTML = "<p>Giỏ hàng trống</p>";
  } else {
    cartContainer.innerHTML = `
          <table>
              <thead>
                  <tr>
                      <th>Sản phẩm</th>
                      <th>Giá</th>
                      <th>Số lượng</th>
                      <th>Tổng</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>
                  ${cart
                    .map(
                      (item, index) => `
                      <tr>
                          <td>${item.name}</td>
                          <td>${item.price} VND</td>
                          <td>${item.quantity}</td>
                          <td>${item.price * item.quantity} VND</td>
                          <td>
                              <button onclick="removeFromCart(${index})">Hủy</button>
                          </td>
                      </tr>
                  `
                    )
                    .join("")}
              </tbody>
          </table>
      `;
  }
}

function removeFromCart(index) {
  let cart = JSON.parse(localStorage.getItem("cart")) || [];
  cart.splice(index, 1);
  localStorage.setItem("cart", JSON.stringify(cart));
  renderCart();
}
let currentIndex = 0;
const track = document.querySelector(".carousel-track");
const items = document.querySelectorAll(".p-item");
const itemWidth = items[0].offsetWidth + 20;

function moveLeft() {
  currentIndex--;
  if (currentIndex < 0) {
    currentIndex = items.length - 1;
  }
  track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
}

function moveRight() {
  currentIndex++;
  if (currentIndex >= items.length) {
    currentIndex = 0;
  }
  track.style.transform = `translateX(-${currentIndex * itemWidth}px)`;
}

function enableEditMode() {
  document
    .querySelectorAll("#editForm input")
    .forEach((input) => input.removeAttribute("disabled"));
  document.getElementById("btnEdit").classList.add("d-none"); // Ẩn nút "Chỉnh sửa"
  document.getElementById("btnSave").classList.remove("d-none"); // Hiện nút "Lưu"
  document.getElementById("btnCancel").classList.remove("d-none"); // Hiện nút "Hủy"
}

function disableEditMode() {
  document
    .querySelectorAll("#editForm input")
    .forEach((input) => input.setAttribute("disabled", true));
  document.getElementById("btnEdit").classList.remove("d-none"); // Hiện nút "Chỉnh sửa"
  document.getElementById("btnSave").classList.add("d-none"); // Ẩn nút "Lưu"
  document.getElementById("btnCancel").classList.add("d-none"); // Ẩn nút "Hủy"
}

function displayPass() {
  var passwordField = document.getElementById("password");
  var checkBox = document.querySelector('input[type="checkbox"]');
  if (checkBox.checked) {
    passwordField.type = "text";
  } else {
    passwordField.type = "password";
  }
}

function displayPassConfirm() {
  var passwordField = document.getElementById("confirm_password");
  var checkBox = document.querySelector('input[type="checkbox"]');
  if (checkBox.checked) {
    passwordField.type = "text";
  } else {
    passwordField.type = "password";
  }
}
