jQuery(document).ready(function($){
	'use strict';
	
	$(".ufaqsw_box_style2").on('click', function(e){
		if(ufaqsw_object_style_2.behaviour=='accordion'){
			closeall();
		}
		$(this).next().slideToggle("fast");
		$(this).find('i').toggle();

	});
	
	var closeall = function(){
		$('.ufaqsw_draw_style2').each(function(){
			var obj = $(this);			
			if(obj.is(":visible")){
				obj.slideToggle("fast");
				obj.prev().find('i').toggle();
			}			
		})
	}
	
	if(ufaqsw_object_style_2.showall=='1' && ufaqsw_object_style_2.behaviour!='accordion'){
		$(".ufaqsw_box_style2").each(function(){
			$(this).trigger('click');
		})
	}
})