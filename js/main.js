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
    var $input = $button.parent().find("input");
    var oldValue = parseFloat($input.val());
    var newVal;
    if ($button.hasClass("inc")) {
      newVal = oldValue + 1;
    } else {
      newVal = Math.max(1, oldValue - 1);
    }
    $input.val(newVal);
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

    cartIcon.addEventListener("mouseenter", function () {
      cartMenu.style.display = "block";
    });

    cartIcon.addEventListener("mouseleave", function () {
      cartMenu.style.display = "none";
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
  document.getElementById("btnEdit").classList.add("d-none");
  document.getElementById("btnSave").classList.remove("d-none");
  document.getElementById("btnCancel").classList.remove("d-none");
}

function disableEditMode() {
  document
    .querySelectorAll("#editForm input")
    .forEach((input) => input.setAttribute("disabled", true));
  document.getElementById("btnEdit").classList.remove("d-none");
  document.getElementById("btnSave").classList.add("d-none");
  document.getElementById("btnCancel").classList.add("d-none");
}

function clearActiveClass() {
  document
    .querySelectorAll(".sidebar a")
    .forEach((link) => link.classList.remove("active"));
}

// Hiển thị danh sách đơn hàng
function showOrders() {
  document.getElementById("accountInfo").classList.add("d-none");
  document.getElementById("orderList").classList.remove("d-none");

  clearActiveClass();
  document.getElementById("ordersLink").classList.add("active");
}

// Hiển thị thông tin tài khoản
function showAccountInfo() {
  document.getElementById("orderList").classList.add("d-none");
  document.getElementById("accountInfo").classList.remove("d-none");

  clearActiveClass();
  document.getElementById("accountLink").classList.add("active");
}
