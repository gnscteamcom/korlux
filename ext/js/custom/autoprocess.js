/* 
 * 
 * Team2One
 * v. 10 Apr 2016
 * 
 * 
 */

var token = $('input[name=_token]').val();

//$.post(
//    "api/cancelexpiredorder",
//    {
//        _token: token
//    },
//    function(data){
//        return 0;
//    }
//);

//$.post(
//    "api/countpoint",
//    {
//        _token: token
//    },
//    function(data){
//        return 0;
//    }
//);

//$.post(
//    "api/countexpiredpoint",
//    {
//        _token: token
//    },
//    function(data){
//        return 0;
//    }
//);

$(document).ready(function() {
	
	/* select colors ======================================= */
	$('.product-colors > li > a').click(function() {
		$('.product-colors > li').removeClass('selected');
		$(this).parent().addClass('selected');
		return false;
	});
	$('.product-colors a').tooltip();


	/* off canvas menu ======================================= */
	$('.menu-link, .close-menu').on('click', function(){
		$('#wrap').toggleClass('menu-open');
		// $('.menu-wrapper').toggleClass('menu-show');
		return false;
	});	
	$(window).bind("resize",function(){
		// console.log($(this).width())
		if($(this).width() >768){
			$('div').removeClass('menu-open');
		}
	});

	$('#showmore').on('click', function(){
		$('#more-items').show();
		$(this).hide();
		return false
	});
});