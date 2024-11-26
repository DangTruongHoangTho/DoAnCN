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