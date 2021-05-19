jQuery(document).ready(function($){
	'use strict';
	if( jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").hasClass('ufaqsw_active') ){
		jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default.ufaqsw_active").closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').show();
	}
	
	//Handle click event
	jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").on('click', function(){
		
		if(ufaqsw_object_default.behaviour=='accordion'){
			closeall();
		}
		if( jQuery(this).hasClass('ufaqsw_active') ){
			jQuery(this).removeClass("ufaqsw_active").closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').slideUp(200);
		}
		else{	jQuery(this).addClass("ufaqsw_active").closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').slideDown(200);
		}
		$(this).find('i').toggle();

		
	});
	
	var closeall = function(){
		jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").each(function(){
			if( jQuery(this).hasClass('ufaqsw_active') ){
				jQuery(this).removeClass("ufaqsw_active").closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').slideUp(200);
				$(this).find('i').toggle();
			}
		})
	}
	
	//show all answers on start
	if(ufaqsw_object_default.showall=='1' && ufaqsw_object_default.behaviour!='accordion'){
		jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").each(function(){
			jQuery(this).trigger( "click" );
		})
	}
	
	
})