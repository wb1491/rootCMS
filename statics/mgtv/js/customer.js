function dropdownOpen() {
    var dropdownLi = $('li.dropdown');
    dropdownLi.mouseover(function() {
        $(this).addClass('open');
    }).mouseout(function() {
        $(this).removeClass('open');
    });
}
$(function(){
   $('.content img').addClass('img-responsive');
    dropdownOpen();
    $(document).off('click.bs.dropdown.data-api');
	$(window).scroll(function() {
		
	if($(this).scrollTop() != 0) {
		$(".scrollToTop").fadeIn();	
	} else {
		$(".scrollToTop").fadeOut();
	}
});

$(".scrollToTop").click(function() {
	$("body,html").animate({scrollTop:0},800);
});
});