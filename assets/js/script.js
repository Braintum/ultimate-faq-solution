jQuery(window).load(function() {
	
	"use strict";
	
	const ufaqsw_element_object = {
		faq_item: '.ufaqsw_element_src',
		faq_item_group: '.ufaqsw_element_group_src',
	};

	if ( jQuery('.ufaqsw_default_all_faq_content').length ) {
	  
		jQuery('input.ufaqsw_default_all_search_box').quicksearch('.ufaqsw_element_src', {
			'delay': 300,
			'selector': ['.ufaqsw_faq_question_src', '.ufaqsw_faq_answer_src'],
			'loader': 'span.ufaqsw_search_loading',
			'noResults': '.ufaqsw_search_no_result',
			'bind': 'keyup keydown',
			'onBefore': function () {
			},
			'onAfter': function () {
				
				jQuery(ufaqsw_element_object.faq_item_group).each(function () {
					const group = jQuery(this);
					const visibleItems = group.find(ufaqsw_element_object.faq_item).filter(function() { 
						return jQuery(this).css('display') !== 'none'; 
					});

					if (visibleItems.length) {
						group.show();
					} else {
						group.hide();
					}
				});
				
			},
			'show': function () {
				jQuery(this).closest(ufaqsw_element_object.faq_item).show();
			},
			'hide': function () {
				jQuery(this).closest(ufaqsw_element_object.faq_item).hide();
			},
			'prepareQuery': function (val) {
				return new RegExp(val, "i");
			},
			'testQuery': function (query, txt, _row) {
				return query.test(txt);
			}
		});
	}

});;jQuery(document).ready(function($){
	'use strict';

	jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").each(function() {
		if ( jQuery(this).hasClass('ufaqsw_active') ) {	
			jQuery(this).closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').show();
		}else{
			jQuery(this).closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').hide();
		}
	});

	//Handle click event
	jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").on('click', function(){

		if(ufaqsw_object_default.behaviour=='accordion'){			
			closeall($(this));
		}

		if( jQuery(this).hasClass('ufaqsw_active') ){
			jQuery(this).removeClass("ufaqsw_active").closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').slideUp(200);
		}
		else{	
			jQuery(this).addClass("ufaqsw_active").closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').slideDown(200);
		}
		$(this).find('i').toggle();
		
	});
	
	const closeall = function(exceptElement){
		jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").each(function(){
			if( jQuery(this).hasClass('ufaqsw_active') && this !== exceptElement[0]){
				jQuery(this).removeClass("ufaqsw_active").closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').slideUp(200);
				$(this).find('i').toggle();
			}
		})
	}
	
	//show all answers on start
	if (typeof ufaqsw_object_default !== 'undefined' && ufaqsw_object_default.showall == '1' && ufaqsw_object_default.behaviour != 'accordion') {
		jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").each(function() {
			jQuery(this).trigger("click");
		});
	}

});jQuery(document).ready(function($){
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
	
});;jQuery(document).ready(function($){
	'use strict';
	
	$(".ufaqsw_box_style2").on('click', function(e){
		if(ufaqsw_object_style_2.behaviour == 'accordion'){
			closeall($(this));
		}
		$(this).next().slideToggle("fast");
		$(this).find('i').toggle();
	});
	
	var closeall = function(exceptElement){
		$('.ufaqsw_draw_style2').each(function(){
			var obj = $(this);
			if(obj.is(":visible") && obj.prev()[0] !== exceptElement[0]){
				obj.slideToggle("fast");
				obj.prev().find('i').toggle();
			}
		});
	}
	
	if( typeof( ufaqsw_object_style_2 ) !== 'undefined' && ufaqsw_object_style_2.showall=='1' && ufaqsw_object_style_2.behaviour!='accordion'){
		$(".ufaqsw_box_style2").each(function(){
			$(this).trigger('click');
		})
	}
})