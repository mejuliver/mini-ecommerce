/*price range*/

 $('#sl2').slider();

	var RGBChange = function() {
	  $('#RGB').css('background', 'rgb('+r.getValue()+','+g.getValue()+','+b.getValue()+')')
	};	
		
/*scroll to top*/
$(document).ready(function(){
	$("#product-container table")
		.addClass("table table-responsive table-striped")
		.wrap('<div class="table-responsive"></div>');
	$(function () {
		$.scrollUp({
	        scrollName: 'scrollUp', // Element ID
	        scrollDistance: 300, // Distance from top/bottom before showing element (px)
	        scrollFrom: 'top', // 'top' or 'bottom'
	        scrollSpeed: 300, // Speed back to top (ms)
	        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
	        animation: 'fade', // Fade, slide, none
	        animationSpeed: 200, // Animation in speed (ms)
	        scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
					//scrollTarget: false, // Set a custom target element for scrolling to the top
	        scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
	        scrollTitle: false, // Set a custom <a> title if required.
	        scrollImg: false, // Set true to use image
	        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
	        zIndex: 2147483647 // Z-Index for the overlay
		});
	});

	var divs = $("#hot-items-carousel > .col-sm-4,#similar-product .carousel-inner a");
	for(var i = 0; i < divs.length; i+=3) {
	  divs.slice(i, i+3).wrapAll("<div class='item'></div>");
	}
	$("#hot-items-carousel .item:first-child,#similar-product .carousel-inner .item:first-child").addClass('active');
	$("#hot-items-carousel").show();

	//on close modal
    $(document).on('hidden.bs.modal', '.modal', function () {
        $('.modal:visible').length && $(document.body).addClass('modal-open');
        
        if(refresh === true){
            location.reload();
        }
        dialog_open = false;
        
        $("#notification_dialog").removeClass("bs-example-modal-lg").find(".modal-dialog").removeClass("modal-lg");
    });

    //add to wishlist
    $(document).on('click','.add-to-wishlist',function(e){
		e.preventDefault();
		var dis = $(this);
		$.ajax({
			url : site_link+'/product/add-to-wishlist',
			data : { item_id : dis.attr("data-id") },
			type : 'post',
			dataType : 'json',
			beforeSend: function(){
				j_loading('on');
			},
			complete: function(){
				j_loading('off');
			},
			success: function(e){
				dis.closest('li').html('<a href="#" class="c-megamitch"><i class="fa fa-check"></i> Already in wishlist</a>');
			}
		})
	});

	//add to cart
	$(document).on('click','.add-to-cart',function(e){
		e.preventDefault();
		var dis = $(this);
		$.ajax({
			url : site_link+'/product/add-to-cart',
			data : { item_id : dis.attr("data-id") },
			type : 'post',
			dataType : 'json',
			beforeSend: function(){
				j_loading('on');
			},
			complete: function(){
				j_loading('off');
			},
			success: function(e){
				console.log(e);
			}
		})
	});

});

$(window).load(function(){
	$('body').fadeIn(200);
	init_components();
});

function format_datetime(e,f){
    return moment(e).format(f);
}

//hide the fieldset and the buttons holder
function hide_pokemon(){
 if($("#extra-modal").is(":visible")){
     $("#extra-modal fieldset").next().hide(function(){
        $("#extra-modal fieldset").fadeOut(200);
     });
 }else{
     $("#notification-dialog fieldset").next().hide(function(){
        $("#notification-dialog fieldset").fadeOut(200);
     });
 }
}
function init_components(){
	$('[data-toggle="tooltip"]').tooltip();
}
