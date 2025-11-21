$(function () {
	"use strict";
	
	
		// Other code goes here...
	
	/* perfect scrol bar */
	// new PerfectScrollbar('.header-message-list');
	// new PerfectScrollbar('.header-notifications-list');
	// search bar
	$(".mobile-search-icon").on("click", function () {
		$(".search-bar").addClass("full-search-bar");
		$(".page-wrapper").addClass("search-overlay");
	});
	$(".search-close").on("click", function () {
		$(".search-bar").removeClass("full-search-bar");
		$(".page-wrapper").removeClass("search-overlay");
	});
	$(".mobile-toggle-menu").on("click", function () {
		$(".wrapper").addClass("toggled");
	});
	// toggle menu button
	$(".toggle-icon").click(function () {
		if ($(".wrapper").hasClass("toggled")) {
			// unpin sidebar when hovered
			$(".wrapper").removeClass("toggled");
			$(".sidebar-wrapper").unbind("hover");
		} else {
			$(".wrapper").addClass("toggled");
			$(".sidebar-wrapper").hover(function () {
				$(".wrapper").addClass("sidebar-hovered");
			}, function () {
				$(".wrapper").removeClass("sidebar-hovered");
			})
		}
	});
	/* Back To Top */
	$(document).ready(function () {
		// $('#1').removeClass('bg-dark shadow-none ');
		$(window).on("scroll", function () {
			if ($(this).scrollTop() > 300) {
				$('.back-to-top').fadeIn();
			} else {
				$('.back-to-top').fadeOut();
			}
		});
		$('.back-to-top').on("click", function () {
			$("html, body").animate({
				scrollTop: 0
			}, 600);
			return false;
		});
	});
	// === sidebar menu activation js
	$(function () {
		for (var i = window.location, o = $(".metismenu li a").filter(function () {
			return this.href == i;
		}).addClass("").parent().addClass("mm-active");;) {
			if (!o.is("li")) break;
			o = o.parent("").addClass("mm-show").parent("").addClass("mm-active");
		}
	});
	// metismenu
	$(function () {
		$('#menu').metisMenu();
	});
	// chat toggle
	$(".chat-toggle-btn").on("click", function () {
		$(".chat-wrapper").toggleClass("chat-toggled");
	});
	$(".chat-toggle-btn-mobile").on("click", function () {
		$(".chat-wrapper").removeClass("chat-toggled");
	});
	// email toggle
	$(".email-toggle-btn").on("click", function () {
		$(".email-wrapper").toggleClass("email-toggled");
	});
	$(".email-toggle-btn-mobile").on("click", function () {
		$(".email-wrapper").removeClass("email-toggled");
	});
	// compose mail
	$(".compose-mail-btn").on("click", function () {
		$(".compose-mail-popup").show();
	});
	$(".compose-mail-close").on("click", function () {
		$(".compose-mail-popup").hide();
	});
	/*switcher*/
	$(".switcher-btn").on("click", function () {
		$(".switcher-wrapper").toggleClass("switcher-toggled");
	});
	$(".close-switcher").on("click", function () {
		$(".switcher-wrapper").removeClass("switcher-toggled");
	});
	$("#lightmode").on("click", function () {
		//$('html').attr('class', 'light-theme');
		
		$('#themeform').submit();
		
		
		
	})
	$("#darkmode").on("click", function () {
		//$('html').attr('class', 'dark-theme');
		$('#themeform').submit();
		
		
	});
	$("#semidark").on("click", function () {
		//$('html').attr('class', 'semi-dark');
		$('#themeform').submit();
		
		
	});
	$("#minimaltheme").on("click", function () {
		$('html').attr('class', 'minimal-theme');
		
	});
	$("#headercolor1").on("click", function () {
		updateHeaderColor("headercolor1")
		// $("html").addClass("color-header headercolor1");
		// $("html").removeClass("headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
	});
	$("#headercolor2").on("click", function () {
		updateHeaderColor("headercolor2")
		// $("html").addClass("color-header headercolor2");
		// $("html").removeClass("headercolor1 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
	});
	$("#headercolor3").on("click", function () {
		updateHeaderColor("headercolor3")
		// $("html").addClass("color-header headercolor3");
		// $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
	});
	$("#headercolor4").on("click", function () {
		updateHeaderColor("headercolor4")
		// $("html").addClass("color-header headercolor4");
		// $("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor5 headercolor6 headercolor7 headercolor8");
	});
	$("#headercolor5").on("click", function () {
		updateHeaderColor("headercolor5")
		// $("html").addClass("color-header headercolor5");
		//$("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor3 headercolor6 headercolor7 headercolor8");
	});
	$("#headercolor6").on("click", function () {
		updateHeaderColor("headercolor6")
		// $("html").addClass("color-header headercolor6");
		// $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor3 headercolor7 headercolor8");
	});
	$("#headercolor7").on("click", function () {
		updateHeaderColor("headercolor7")
		// $("html").addClass("color-header headercolor7");
		// $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor3 headercolor8");
	});
	$("#headercolor8").on("click", function () {
		updateHeaderColor("headercolor8")
		// $("html").addClass("color-header headercolor8");
		// $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor3");
	});
	
	function updateHeaderColor(colorClass){
		$("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8");
    	$("html").addClass(colorClass);
		$.ajax({
			url: '/update-header', // Adjust the URL according to your route
			method: 'POST',
			data: {
				color: colorClass
			},
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			success: function(response) {
				console.log('Selected header color saved successfully');
			},
			error: function(xhr, status, error) {
				console.error('Error saving selected header color:', error);
			}
		});

	}
	
   // sidebar colors 

   $("#sidebarcolor1").on("click", function () {
	updateSidebarColor("sidebarcolor1")
   });
   $("#sidebarcolor2").on("click", function () {
	updateSidebarColor("sidebarcolor2")
   });
   $("#sidebarcolor3").on("click", function () {
	updateSidebarColor("sidebarcolor3")
   });
   $("#sidebarcolor4").on("click", function () {
	updateSidebarColor("sidebarcolor4")
   });
   $("#sidebarcolor5").on("click", function () {
	updateSidebarColor("sidebarcolor5")
   });
   $("#sidebarcolor6").on("click", function () {
	updateSidebarColor("sidebarcolor6")
   });
   $("#sidebarcolor7").on("click", function () {
	updateSidebarColor("sidebarcolor7")
   });
   $("#sidebarcolor8").on("click", function () {
	updateSidebarColor("sidebarcolor8")
   });

   function updateSidebarColor(colorClass){
	$("html").removeClass("sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7 sidebarcolor8");
	$("html").addClass("color-sidebar "+ colorClass);
	$.ajax({
		url: '/update-sidebar', // Adjust the URL according to your route
		method: 'POST',
		data: {
			color: colorClass
		},
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(response) {
			console.log('Selected sidebar color saved successfully');
		},
		error: function(xhr, status, error) {
			console.error('Error saving selected sidebar color:', error);
		}
	});

}

    // $('#sidebarcolor1').click(theme1);
    // $('#sidebarcolor2').click(theme2);
    // $('#sidebarcolor3').click(theme3);
    // $('#sidebarcolor4').click(theme4);
    // $('#sidebarcolor5').click(theme5);
    // $('#sidebarcolor6').click(theme6);
    // $('#sidebarcolor7').click(theme7);
    // $('#sidebarcolor8').click(theme8);

    // function theme1() {
    //   $('html').addClass('color-sidebar sidebarcolor1');
    //   $('html').removeClass('sidebarcolor8 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7')

    // }

    // function theme2() {
    //   $('html').addClass( 'color-sidebar sidebarcolor2');
    //   $('html').removeClass('sidebarcolor1 sidebarcolor8 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7')

    // }

    // function theme3() {
    //   $('html').addClass( 'color-sidebar sidebarcolor3');
    //   $('html').removeClass( 'sidebarcolor1 sidebarcolor2 sidebarcolor8 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7')

    // }

    // function theme4() {
    //   $('html').addClass( 'color-sidebar sidebarcolor4');
    //   $('html').removeClass( 'sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor8 sidebarcolor5 sidebarcolor6 sidebarcolor7')

    // }
	
	// function theme5() {
    //   $('html').addClass( 'color-sidebar sidebarcolor5');
    //   $$('html').removeClass( 'sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor8 sidebarcolor6 sidebarcolor7')

    // }
	
	// function theme6() {
    //   $('html').addClass( 'color-sidebar sidebarcolor6');
    //   $('html').removeClass( 'sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor8 sidebarcolor7')

    // }

    // function theme7() {
    //   $('html').addClass( 'color-sidebar sidebarcolor7');
    //   $('html').removeClass('sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor8')

    // }

    // function theme8() {
    //   $('html').addClass( 'color-sidebar sidebarcolor8');
    //   $('html').removeClass('sidebarcolor1 sidebarcolor2 sidebarcolor3 sidebarcolor4 sidebarcolor5 sidebarcolor6 sidebarcolor7')

    // }

	
});
function get_host(){
    return window.location.protocol + "//" + window.location.host;
}