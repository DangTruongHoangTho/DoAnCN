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
  document.querySelector(".dropdown-toggle").addEventListener("click", function (e) {
    e.preventDefault(); // Ngăn điều hướng link
    const menu = this.nextElementSibling; // Lấy menu liền sau phần tử được click
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  });

  // Ẩn menu khi click ra ngoài
  document.addEventListener("click", function (e) {
      const dropdown = document.querySelector(".dropdown");
      if (!dropdown.contains(e.target)) {
          dropdown.querySelector(".dropdown-menu").style.display = "none";
      }
  });
});

function ajax_giohang(){
	$('#spm').removeClass('ajaxing');
	$('#mntq').removeClass('ajaxing');
	$('#dgg').removeClass('ajaxing');
	$.ajax({
		url : "ajax_calling.php",
		type : "get",
		dataType:"text",
		data : {
			fname: 'php_giohang'
		},
		success : function (result){
			$('#content').html(result);
		}
	});
}

function handleOrder(event, productId, productName, productPrice) {
  event.preventDefault();

  const quantityElement = document.getElementById('quantity');
  const quantity = quantityElement ? parseInt(quantityElement.value, 10) || 1 : 1;

  addToCart(productId, productName, productPrice, quantity);
  window.location.href = './Order.php';
}

function addToCart(productId, productName, productPrice, quantity) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  cart.push({ id: productId, name: productName, price: productPrice, quantity });
  localStorage.setItem('cart', JSON.stringify(cart));
  alert(`Đã thêm ${quantity} x ${productName} vào giỏ hàng!`);
}


function renderCart() {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  let cartContainer = document.getElementById('cart');
  if (cart.length === 0) {
      cartContainer.innerHTML = '<p>Giỏ hàng trống</p>';
  } else {
      cartContainer.innerHTML = `
          <table>
              <thead>
                  <tr>
                      <th>Sản phẩm</th>
                      <th>Giá</th>
                      <th>Số lượng</th>
                      <th>Tổng</th>
                      <th>Hành động</th>
                  </tr>
              </thead>
              <tbody>
                  ${cart.map((item, index) => `
                      <tr>
                          <td>${item.name}</td>
                          <td>${item.price} VND</td>
                          <td>${item.quantity}</td>
                          <td>${item.price * item.quantity} VND</td>
                          <td>
                              <button onclick="removeFromCart(${index})">Hủy</button>
                          </td>
                      </tr>
                  `).join('')}
              </tbody>
          </table>
      `;
  }
}

function removeFromCart(index) {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  cart.splice(index, 1); // Xóa sản phẩm tại vị trí `index`
  localStorage.setItem('cart', JSON.stringify(cart)); // Cập nhật lại giỏ hàng
  renderCart(); // Hiển thị lại giỏ hàng
}