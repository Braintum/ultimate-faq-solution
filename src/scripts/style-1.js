jQuery(document).ready(function($){
	'use strict';
	
	$('.ufaqsw_toggle_default > .ufaqsw_title_area_style1').on('click', function(e){
		var obj = $(this).parent().find('[type=checkbox]');

		if(ufaqsw_object_style_1.behaviour == 'accordion'){
			closeall($(this).parent());
		}

		if (obj.attr('checked')) {
			obj.attr('checked', false);
			obj.next().next().css({"height": "", "opacity": "", "padding": ""});	
			obj.next().find('.ufaqsw-style1-active-icon').css({"display": "none"});			
			obj.next().find('.ufaqsw-style1-normal-icon').css({"display": "inline-block"});
		} else {
			obj.attr('checked', true);
			obj.next().next().css({"height": "auto", "opacity": "1", "padding": "15px"});			
			obj.next().find('.ufaqsw-style1-active-icon').css({"display": "inline-block"});			
			obj.next().find('.ufaqsw-style1-normal-icon').css({"display": "none"});	
		}
	});
	
	var closeall = function(exceptElement){
		$('.ufaqsw_questions_style1').each(function(){
			var obj = $(this);
			if (obj.closest('.ufaqsw_toggle_default')[0] !== exceptElement[0]) {
				obj.attr('checked', false);
				obj.next().next().css({"height": "", "opacity": "", "padding": ""});	
				obj.next().find('.ufaqsw-style1-active-icon').css({"display": "none"});			
				obj.next().find('.ufaqsw-style1-normal-icon').css({"display": "inline-block"});
			}
		});
	};
	
	$('.ufaqsw_questions_style1').each(function(){
		
		var obj = $(this);
		if(obj.attr('checked')){
			obj.next().next().css({"height": "auto", "opacity": "1", "padding": "15px"});			
			obj.next().find('.ufaqsw-style1-active-icon').css({"display": "inline-block"});			
			obj.next().find('.ufaqsw-style1-normal-icon').css({"display": "none"});			
		}else{
			obj.next().next().css({"height": "", "opacity": "", "padding": ""});	
			obj.next().find('.ufaqsw-style1-active-icon').css({"display": "none"});			
			obj.next().find('.ufaqsw-style1-normal-icon').css({"display": "inline-block"});
		}
		
	});
	
});