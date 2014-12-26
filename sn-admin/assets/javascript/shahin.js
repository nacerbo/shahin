// header animation
var header,y_pos,top_menu;

		function y_scroll(){
			header   = document.getElementById('header');
			top_menu = document.getElementById('header-menu');
			y_pos   = window.pageYOffset;
			
			if (y_pos > 100) {
				header.style.height        = "90px";
				header.style.paddingBottom = "11px";
				header.style.paddingTop    = "11px";
				top_menu.style.display     = "none"; 
				header.setAttribute("class","header small");

			} else {
				header.style.height 	   = "142px";
				header.style.paddingBottom = "20px";
				header.style.paddingTop    = "20px";
				top_menu.style.display     = "block"; 
				header.setAttribute("class","header");
			}
		}
window.addEventListener("scroll", y_scroll);

/* Textarea */
$(document).ready(function(){
	$('textarea').autogrow({
		animate: false,
	});

	$("#last-activ").click(function(){
		$("#last-ac-content").slideToggle();
	});

	$("#drafts-box").click(function(){
		$("#drafts-box-content").slideToggle();
	});


});