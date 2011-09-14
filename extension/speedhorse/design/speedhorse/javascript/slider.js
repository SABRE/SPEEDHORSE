$(function(){
	    $('.carousel-next-page').click(function(){
	        var carousel = $(this).parents('.carousel').find('ul');
			
			//alert(carousel.attr('id'));
	        var scroll = carousel.scrollLeft();
	        var w = carousel.width();
	        var x = 0;
	        carousel.find('li').each(function(i){
	            if ($(this).position().left<w){ 
				x = $(this).position().left;
				//alert(w);
				//alert($(this).position().left);
				//alert(x);
				}
	        });
	        carousel.animate({ scrollLeft: scroll+x }, 'fast');
	        return false;
	    });
	    $('.carousel-prev-page').click(function(){
	        var carousel = $(this).parents('.carousel').find('ul');
	        var scroll = carousel.scrollLeft();
	        var w = carousel.width();
	        var x = 0;
	        carousel.find('li').each(function(i){
	            if ($(this).position().left<1-w) x = $(this).position().left;
	        });
	        if (x==0) {
	            carousel.animate({ scrollLeft: 0 }, 'fast');
	        } else {
	            carousel.animate({ scrollLeft: scroll+x }, 'fast');
	        }
	        return false;
	    });
	    $('.carousel-next-image').click(function(){
	        var carousel = $(this).parents('.carousel').find('ul');
	        var scroll = carousel.scrollLeft();
	        var x = 0;
	        carousel.find('li').each(function(){
	            if (x==0&&$(this).position().left>1) x = $(this).position().left;
	        });
	        carousel.animate({ scrollLeft: scroll+x }, 'fast');
	        return false;
	    });
	    $('.carousel-prev-image').click(function(){
	        var carousel = $(this).parents('.carousel').find('ul');
	        var scroll = carousel.scrollLeft();
	        var x = 0;
	        carousel.find('li').each(function(){
	            if ($(this).position().left<0) x = $(this).position().left;
	        });
	        carousel.animate({ scrollLeft: scroll+x }, 'fast');
	        return false;
	    });
	});