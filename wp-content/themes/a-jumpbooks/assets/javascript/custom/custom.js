var counter = 0;
$('.menu-icon').on('click', function(){
	if (counter === 0) {
		$('.vertical.menu').show();
		$('.menu-icon').css({'-ms-transform': 'rotate(180deg)', '-webkit-transform': 'rotate(180deg)', 'transform': 'rotate(180deg)'});
		var pageheight = $(document).height();
		$('.top-bar-right').height(pageheight);
		counter = 1;
	} else if (counter === 1) {
		$('.vertical.menu').hide();
		$('.menu-icon').css({'-ms-transform': 'rotate(0deg)', '-webkit-transform': 'rotate(0deg)', 'transform': 'rotate(0deg)'});
		$('.top-bar-right').height('auto');
		counter = 0;
	}
});

$('body').on('click', '#view-all', function(){
	if ($('.view-all-list div').hasClass('hide')) {
		$('.view-all-list div').removeClass('hide');
	} else {
		$('.view-all-list div').addClass('hide');
	}
});

$(document).click(function(e) {
  	if( e.target.id != 'view-all') {
		$('#view-all').removeClass('view-all-open');
		$('.view-all-list div').addClass('hide');
  	}
});

if ($('.top-bar-right').css('position') == 'absolute' && $('.detail-feat-img .book-links').length) {
	$('.detail-feat-text').append($('.detail-feat-img .book-links'));
}

$(window).resize(function(){
	if ($('.top-bar-right').css('position') == 'relative') {
		$('.menu-icon').css({'-ms-transform': 'rotate(0deg)', '-webkit-transform': 'rotate(0deg)', 'transform': 'rotate(0deg)', 'top': '35px'});		
		$('.top-bar-right').height('auto');
		counter = 0;
	} else if ($('.top-bar-right').css('position') == 'absolute') {
		var pageheight = $(document).height();
		$('.top-bar-right').height(pageheight);		
	}
	if ($('.top-bar-right').css('position') == 'absolute' && $('.detail-feat-img .book-links').length) {
		$('.detail-feat-text').append($('.detail-feat-img .book-links'));
	} else if ($('.top-bar-right').css('position') == 'relative' && $('.detail-feat-text .book-links').length) {
		$('.detail-feat-img').append($('.detail-feat-text .book-links'));		
	}
});

$(document).on("scroll", function(){
	if ($(document).scrollTop() > 150){
		$(".fixed-nav").addClass("fixed-nav-show");
	} else if ($(document).scrollTop() < 70){
		$(".fixed-nav").removeClass("fixed-nav-show");
	}
});

$('.add-to-cart').on('click', function(e){
	e.preventDefault();
    var link = $(this).attr('href');
    $.ajax({
    	url: link,
    	success: function(){
    		$('.product-added').hide().fadeIn(500).css("display","inline-block");
    		var currentcart = $('.menu-item-cart span').html();
    		if (currentcart) {
	    		currentcart = currentcart*1;
	    		currentcart = currentcart+1;
	    		$('.menu-item-cart span').html(currentcart);    			
    		} else {
				if ($(document).scrollTop()>50){
		    		currentcart = 1;
		    		$('.menu-item-cart').html('<a href="/cart" class="nav-item-small">Cart (<span style="color: #a32f38;">' + currentcart + '</span>)</a>');    			    			
		    	} else {
		    		currentcart = 1;
		    		$('.menu-item-cart').html('<a href="/cart" class="nav-item-large">Cart (<span style="color: #a32f38;">' + currentcart + '</span>)</a>');    			    					    		
		    	}
    		}
			setTimeout(
				function() {
		    		$('.product-added').fadeOut(500, function() {
		    		});
				}, 5000);
        }
    });
});

var equalheight;

equalheight = function(container){

	var currentTallest = 0;
	var currentRowStart = 0;
	var rowDivs = [];
	var $el;
	var topPosition = 0;
	var currentDiv;

	$(container).each(function() {
		$el = $(this);
		$($el).height('auto');
		topPosition = $el.position().top;

		if (currentRowStart != topPosition) {
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
			rowDivs.length = 0; // empty the array
			currentRowStart = topPosition;
			currentTallest = $el.height();
			rowDivs.push($el);

		} else {
			rowDivs.push($el);
			currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
		}

		for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			rowDivs[currentDiv].height(currentTallest);
		}
	});
};

$(window).load(function() {
	equalheight('.book-item');
});

$(window).resize(function(){
	equalheight('.book-item');
});

$(document).ready(function(){
	$('.book-detail-slider').not('.slick-initialized').slick({
		slidesToShow: 1,
		autoplay: true,
		autoplaySpeed: 4000,
        arrows: false,
        speed: 500,
        draggable: false,
        pauseOnHover: true,
        fade: true,
	});
	$('.home-slider').not('.slick-initialized').slick({
		slidesToShow: 1,
		autoplay: true,
		autoplaySpeed: 4000,
        arrows: false,
        speed: 500,
        draggable: false,
        pauseOnHover: true,
        fade: true,
	});
});

$('body').on('click', '#outof', function(e){
	e.preventDefault();
	$('#view-all').removeClass('view-all-open');
	$('.view-all-list div').addClass('hide');
	$.ajax({
		type: "POST",
		url: window.location,
		data: {sort: "outofstock"},
		success: function(data){
			console.log(data);
			$(".grid-a .book-item").fadeOut().promise().done(function(){
				$(this).remove();
				$(data).find('.grid-a .book-item').appendTo('.grid-a').hide().fadeIn();
				equalheight('.book-item');
			});
	   	}
	});
});

$('body').on('click', '#avail', function(e){
	e.preventDefault();
	$('#view-all').removeClass('view-all-open');
	$('.view-all-list div').addClass('hide');
	$.ajax({
		type: "POST",
		url: window.location,
		data: {sort: "instock"},
		success: function(data){
			$(".grid-a .book-item").fadeOut().promise().done(function(){
				$(this).remove();
				$(data).find('.grid-a .book-item').appendTo('.grid-a').hide().fadeIn();
				equalheight('.book-item');
			});
	   	}
	});
});

$('body').on('click', '#viewall', function(e){
	e.preventDefault();
	$('#view-all').removeClass('view-all-open');
	$('.view-all-list div').addClass('hide');
	$.ajax({
		type: "POST",
		url: window.location,
		data: {sort: "viewall"},
		success: function(data){
			$(".grid-a .book-item").fadeOut().promise().done(function(){
				$(this).remove();
				$(data).find('.grid-a .book-item').appendTo('.grid-a').hide().fadeIn();
				equalheight('.book-item');
			});
	   	}
	});
});

if ($(".book .error-cont").length) {
	$(".book .error-cont").delay(10000).fadeOut();
}

$(document).ready(function(){
    $('form.woocommerce-checkout').find("#billing_postcode, #shipping_postcode, #billing_state, #shipping_state").val("");
});

function formtop() {
	$('html, body').animate({
	    scrollTop: ($('.checkout-steps-cont').offset().top)
	}, 500);	
}

$("body").on("click", ".billing-continue", function() {
	formtop();
	var clear = 0;
	if ($(".woocommerce-billing-fields p.validate-required:not(.woocommerce-validated)").length) {
		$(".woocommerce-billing-fields p.validate-required:not(.woocommerce-validated)").addClass('woocommerce-invalid');
	} else {
		$(".billing").addClass("hide");
		$(".shipping").removeClass("hide");
		$(".checkout-step-billing").removeClass("checkout-step-active");
		$(".checkout-step-shipping").addClass("checkout-step-active");
		$(".ch-hr-1").addClass("hide");
		$(".ch-hr-2").removeClass("hide");
		var b_name = $(".billing #billing_first_name").val();
		var b_last = $(".billing #billing_last_name").val();
		var b_email = $(".billing #billing_email").val();
		var b_address = $(".billing #billing_address_1").val();
		var b_city = $(".billing #billing_city").val();
		var b_state = $(".billing #billing_state").val();
		var b_zip = $(".billing #billing_postcode").val();
		var b_country = $(".billing #billing_country option:selected").text();
		$(".bill-info p").remove();
		$(".bill-info").removeClass("hide").append('<p>' + b_name + ' ' + b_last + '</p>');
		$(".bill-info").append('<p>' + b_address + '</p>');
		$(".bill-info").append('<p>' + b_city + ', ' + b_state + ' ' + b_zip + '</p>');
		$(".bill-info").append('<p>' + b_country + '</p>');
	}
});

$("#billing_country").on("change", function() {
	setTimeout(function (){ 
		$('.billing p.form-row:visible:odd').removeClass('form-row-first').addClass("form-row-last");
		$('.billing p.form-row:visible:even').removeClass('form-row-last').addClass("form-row-first");
		$('.billing #billing_postcode').val("");
		$('.billing #billing_postcode_field').removeClass('woocommerce-validated');
		$('.billing #billing_city').val("");
		$('.billing #billing_city_field').val("").removeClass('woocommerce-validated');
		$('.billing #billing_state').val("");
		$('.billing #billing_state_field').val("").removeClass('woocommerce-validated');
	}, 500);
});

$("#shipping_country").on("change", function() {
	setTimeout(function (){ 
		$('.shipping p.form-row:visible:odd').removeClass('form-row-first').addClass("form-row-last");
		$('.shipping p.form-row:visible:even').removeClass('form-row-last').addClass("form-row-first");
	}, 500);
});

$("body").on("click", ".shipping-back", function() {
	formtop();
	if ($("#ship-to-different-address-checkbox").val() == 1) {
		if ($(".woocommerce-shipping-fields p.validate-required:not(.woocommerce-validated)").length) {
			$(".woocommerce-shipping-fields p.validate-required:not(.woocommerce-validated)").addClass('woocommerce-invalid');
		} else {
			$(".shipping").addClass("hide");
			$(".billing").removeClass("hide");
			$(".checkout-step-shipping").removeClass("checkout-step-active");
			$(".checkout-step-billing").addClass("checkout-step-active");
			$(".ch-hr-2").addClass("hide");
			$(".ch-hr-1").removeClass("hide");	
			var s_name = $(".shipping #shipping_first_name").val();
			var s_last = $(".shipping #shipping_last_name").val();
			var s_email = $(".shipping #shipping_email").val();
			var s_address = $(".shipping #shipping_address_1").val();
			var s_address2 = $(".shipping #shipping_address_2").val();
			var s_city = $(".shipping #shipping_city").val();
			var s_state = $(".shipping #shipping_state").val();
			var s_zip = $(".shipping #shipping_postcode").val();
			var s_country = $(".shipping #shipping_country option:selected").text();
			$(".ship-info p").remove();
			$(".ship-info").removeClass("hide").append('<p>' + s_name + ' ' + s_last + '</p>');
			$(".ship-info").append('<p>' + s_address + '</p>');
			if (s_address2) {
			$(".ship-info").append('<p>' + s_address2 + '</p>');				
			}
			$(".ship-info").append('<p>' + s_city + ', ' + s_state + ' ' + s_zip + '</p>');
			$(".ship-info").append('<p>' + s_country + '</p>');
		}
	} else {
		$(".shipping").addClass("hide");
		$(".billing").removeClass("hide");
		$(".checkout-step-shipping").removeClass("checkout-step-active");
		$(".checkout-step-billing").addClass("checkout-step-active");
		$(".ch-hr-2").addClass("hide");
		$(".ch-hr-1").removeClass("hide");	
		var b_name = $(".billing #billing_first_name").val();
		var b_last = $(".billing #billing_last_name").val();
		var b_email = $(".billing #billing_email").val();
		var b_address = $(".billing #billing_address_1").val();
		var b_city = $(".billing #billing_city").val();
		var b_state = $(".billing #billing_state").val();
		var b_zip = $(".billing #billing_postcode").val();
		var b_country = $(".billing #billing_country option:selected").text();
		$(".ship-info p").remove();
		$(".ship-info").removeClass("hide").append('<p>' + b_name + ' ' + b_last + '</p>');
		$(".ship-info").append('<p>' + b_address + '</p>');
		$(".ship-info").append('<p>' + b_city + ', ' + b_state + ' ' + b_zip + '</p>');
		$(".ship-info").append('<p>' + b_country + '</p>');
	}
});

$("body").on("click", ".shipping-continue", function() {
	formtop();
	if ($("#ship-to-different-address-checkbox").val() == 1) {
		if ($(".woocommerce-shipping-fields p.validate-required:not(.woocommerce-validated)").length) {
			$(".woocommerce-shipping-fields p.validate-required:not(.woocommerce-validated)").addClass('woocommerce-invalid');
		} else {
			$(".shipping").addClass("hide");
			$(".payment").removeClass("hide");
			$(".checkout-step-shipping").removeClass("checkout-step-active");
			$(".checkout-step-payment").addClass("checkout-step-active");
			$(".ch-hr-2").addClass("hide");
			$(".ch-hr-3").removeClass("hide");	
			var s_name = $(".shipping #shipping_first_name").val();
			var s_last = $(".shipping #shipping_last_name").val();
			var s_email = $(".shipping #shipping_email").val();
			var s_address = $(".shipping #shipping_address_1").val();
			var s_address2 = $(".shipping #shipping_address_2").val();
			var s_city = $(".shipping #shipping_city").val();
			var s_state = $(".shipping #shipping_state").val();
			var s_zip = $(".shipping #shipping_postcode").val();
			var s_country = $(".shipping #shipping_country option:selected").text();
			$(".ship-info p").remove();
			$(".ship-info").removeClass("hide").append('<p>' + s_name + ' ' + s_last + '</p>');
			$(".ship-info").append('<p>' + s_address + '</p>');
			if (s_address2) {
			$(".ship-info").append('<p>' + s_address2 + '</p>');				
			}
			$(".ship-info").append('<p>' + s_city + ', ' + s_state + ' ' + s_zip + '</p>');
			$(".ship-info").append('<p>' + s_country + '</p>');
		}
	} else {
		$(".shipping").addClass("hide");
		$(".payment").removeClass("hide");
		$(".checkout-step-shipping").removeClass("checkout-step-active");
		$(".checkout-step-payment").addClass("checkout-step-active");
		$(".ch-hr-2").addClass("hide");
		$(".ch-hr-3").removeClass("hide");	
		var b_name = $(".billing #billing_first_name").val();
		var b_last = $(".billing #billing_last_name").val();
		var b_email = $(".billing #billing_email").val();
		var b_address = $(".billing #billing_address_1").val();
		var b_city = $(".billing #billing_city").val();
		var b_state = $(".billing #billing_state").val();
		var b_zip = $(".billing #billing_postcode").val();
		var b_country = $(".billing #billing_country option:selected").text();
		$(".ship-info p").remove();
		$(".ship-info").removeClass("hide").append('<p>' + b_name + ' ' + b_last + '</p>');
		$(".ship-info").append('<p>' + b_address + '</p>');
		$(".ship-info").append('<p>' + b_city + ', ' + b_state + ' ' + b_zip + '</p>');
		$(".ship-info").append('<p>' + b_country + '</p>');
	}
});

$("body").on("click", ".payment-back", function() {
	formtop();
	$(".payment").addClass("hide");
	$(".shipping").removeClass("hide");
	$(".checkout-step-payment").removeClass("checkout-step-active");
	$(".checkout-step-shipping").addClass("checkout-step-active");
	$(".ch-hr-3").addClass("hide");
	$(".ch-hr-2").removeClass("hide");	
});

$("#billing_country").on("change", function(){
	if ($("#billing_country").length) {
		if ($("#billing_country").val() == "US" || $("#billing_country").val() == "CA") {
			$(".payment_method_paypal").addClass("hide");
			$(".payment_method_square").removeClass("hide");
			$("#payment_method_square").prop("checked", true);
			$("#payment_method_paypal").prop("checked", false);
			$("#place_order").attr("value", "Place order");
		} else {
			$(".payment_method_paypal").removeClass("hide");
			$(".payment_method_square").addClass("hide");
			$("#payment_method_paypal").prop("checked", true);
			$("#payment_method_square").prop("checked", false);
			$("#place_order").attr("value", "Place order").attr("data-value", "Place order");
			$("#place_order").attr("value", "Proceed to Paypal");
		}
	}
});

$(document.body).on('init_checkout', function (event) {
	$('#billing_address_1_field').after($('#billing_country_field'));
	$('#shipping_address_2_field').after($('#shipping_country_field'));
    $('#shipping_country_field').removeClass('form-row-last').addClass('form-row-first');
    $('#shipping_city_field').removeClass('form-row-first').addClass('form-row-last');
    var regionTimer = setInterval(function() {
    	if ($(".billing p.woocommerce-invalid").length || $(".shipping p.woocommerce-invalid").length) {
			$(".billing p, .shipping p").each(function(){
				$(this).removeClass("woocommerce-invalid");
				$(this).removeClass("woocommerce-invalid-required-field");
	            $(this).removeClass("woocommerce-validated");
				$(this).attr("data-o_class", $(this).attr("class"));
				$("#billing_state").val("");
				$("#billing_country").val("");
				$("#shipping_state").val("");
				$("#shipping_country").val("");
				if ($("#billing_country").length) {
					if ($("#billing_country").val() == "US" || $("#billing_country").val() == "CA") {
						$(".payment_method_paypal").addClass("hide");
						$(".payment_method_square").removeClass("hide");
						$("#payment_method_square").prop("checked", true);
						$("#payment_method_paypal").prop("checked", false);
						$("#place_order").attr("value", "Place order");
					} else {
						$(".payment_method_paypal").removeClass("hide");
						$(".payment_method_square").addClass("hide");
						$("#payment_method_paypal").prop("checked", true);
						$("#payment_method_square").prop("checked", false);
						$("#place_order").attr("value", "Place order").attr("data-value", "Place order");
						$("#place_order").attr("value", "Proceed to Paypal");
					}
				}
				$(".shipping_address").show();
			});    		
    	}
        clearInterval(regionTimer);
    }, 1);
    setTimeout(function() {
        clearInterval(regionTimer);
    }, 5000);
});