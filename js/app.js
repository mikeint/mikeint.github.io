$( document ).ready(function() {

	$('body').bind('touchstart', function() {});

	$(function () {
	    $(window).resize(function () {
	        $('.hero').height($(window).height());
	    });
	});

	$(".ch-img-2 a").click(function() {

		if ($(window).width() < 500) {
			$('html, body').animate({ scrollTop: 1300 });
		} 
		else {
			$('html, body').animate({ scrollTop: 400 });
		}


		$(".inner").animate({
		  top: 0
		},500),
        $(".skills-page").show();
        $(".feature-2").slideDown(); 
				$(".feature-3").slideUp(); 
				$(".feature-4").slideUp(); 
        $(".close-container").fadeIn();
    	return false;
    });

    $(".ch-img-4 a").click(function() {
			if ($(window).width() < 500) {
				$('html, body').animate({ scrollTop: 1300 });
			} 
			else {
				$('html, body').animate({ scrollTop: 350 });
			} 

			$(".inner").animate({
				top: 0
			},500), 
					$(".feature-3").slideDown(); 
					$(".feature-2").slideUp(); 
					$(".feature-4").slideUp(); 
					$(".close-container").fadeIn();
				return false;
		});

		$(".ch-img-5 a").click(function() {
			if ($(window).width() < 500) {
				$('html, body').animate({ scrollTop: 1300 });
			} 
			else {
				$('html, body').animate({ scrollTop: 350 });
			} 

			$(".inner").animate({
				top: 0
			},500), 
					$(".feature-4").slideDown(); 
					$(".feature-3").slideUp(); 
					$(".feature-2").slideUp(); 
					$(".close-container").fadeIn();
				return false;
		});
		


    $(".close-container img").click(function() {
    	 $(".feature-2").slideUp(); 
			 $(".feature-3").slideUp(); 
			 $(".feature-4").slideUp(); 
    	 $(".close-container").fadeOut();
    	 $(".inner").animate({
		  top: '100%'
		},500) 
    });



	$(".hide-skills").click(function() {

		$(".inner").animate({
		  top: '100%'
		},500), 
		$(".feature-2").slideUp();   
		$(".skills-page").hide(); 
		$(".close-container").hide();
	}); 
	$(".hide-about").click(function() {  
		$(".inner").animate({
		  top: '100%'
		},500),   
		$(".feature-3").slideUp();  
		$(".feature-4").slideUp();  
		$(".close-container").hide();
	}); 
   

}); 