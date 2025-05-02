jQuery(window).on('load', function() {
	
	"use strict";
	
	const ufaqsw_element_object = {
		faq_item: '.ufaqsw_element_src',
		faq_item_group: '.ufaqsw_element_group_src',
		faq_filter_item: '.filter-list__item',
	};

	jQuery(ufaqsw_element_object.faq_filter_item).on( 'click', function(e) {
		const filter_id = jQuery(this).data('index');
		const targetSection = jQuery(`#${filter_id}`);
		if (targetSection.length) {
			jQuery('html, body').animate({
				scrollTop: targetSection.offset().top - 140
			}, 500);
		}
	});

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

});;jQuery(function($) {
	'use strict';

	const toggleItem = (element) => {
		if (ufaqsw_object_default.behaviour == 'accordion') {			
			closeall(element);
		}

		if (element.hasClass('ufaqsw_active')) {
			hideItem(element);
		} else {	
			showItem(element);
		}
		element.find('i').toggle();

		const question_expanded = element.attr('aria-expanded') === 'true';
    	element.attr('aria-expanded', String(!question_expanded));

		const answer_expanded = element.closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').attr('aria-hidden') === 'true';
    	element.closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').attr('aria-hidden', String(!answer_expanded));
	};

	const showItem = (element) => {
		element.addClass("ufaqsw_active").closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').slideDown(200);
	};

	const hideItem = (element) => {
		if (element.hasClass('ufaqsw_active')) {
			element.removeClass("ufaqsw_active");
		}
		element.closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').slideUp(200);
	};

	$(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").each(function() {
		const obj = $(this);
		if (obj.hasClass('ufaqsw_active')) {	
			showItem(obj);
		} else {
			hideItem(obj);
		}
	});

	// Handle click event
	$(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").on('click', function() {
		toggleItem($(this));
	});

	// Toggle on keyboard
	$(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").on('keydown', function(e){
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			toggleItem($(this));
		}
	});

	const closeall = function(exceptElement) {
		jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").each(function() {
			if (jQuery(this).hasClass('ufaqsw_active') && this !== exceptElement[0]) {
				hideItem(jQuery(this));
			}
		});
	};

	// Show all answers on start
	if (typeof ufaqsw_object_default !== 'undefined' && ufaqsw_object_default.showall == '1' && ufaqsw_object_default.behaviour != 'accordion') {
		jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").each(function() {
			jQuery(this).trigger("click");
		});
	}
});;jQuery(function($) {
	'use strict';
	
	$('.ufaqsw_toggle_default > .ufaqsw_title_area_style1').on('click', function(e){
		toggleItem($(this));
	});

	// Toggle on keyboard
	$('.ufaqsw_toggle_default > .ufaqsw_title_area_style1').on('keydown', function(e){
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			toggleItem($(this));
		}
	});

	const toggleItem = ( element ) => {

		var obj = element.parent().find('[type=checkbox]');

		if(ufaqsw_object_style_1.behaviour == 'accordion'){
			closeall(element.parent());
		}

		if (obj.attr('checked')) {
			obj.attr('checked', false);
			hideItem(obj);
		} else {
			obj.attr('checked', true);
			showItem(obj);
		}

		const question_expanded = element.attr('aria-expanded') === 'true';
    	element.attr('aria-expanded', String(!question_expanded));

		const answer_expanded = element.next().attr('aria-hidden') === 'true';
    	element.next().attr('aria-hidden', String(!answer_expanded));
	}

	const showItem = ( element ) => {
		element.next().next().css({"height": "auto", "opacity": "1", "padding": "15px"});			
		element.next().find('.ufaqsw-style1-active-icon').css({"display": "inline-block"});			
		element.next().find('.ufaqsw-style1-normal-icon').css({"display": "none"});	
	}

	const hideItem = ( element ) => {
		element.next().next().css({"height": "", "opacity": "", "padding": ""});	
		element.next().find('.ufaqsw-style1-active-icon').css({"display": "none"});			
		element.next().find('.ufaqsw-style1-normal-icon').css({"display": "inline-block"});
	}
	
	var closeall = function(exceptElement){
		$('.ufaqsw_questions_style1').each(function(){
			var obj = $(this);
			if (obj.closest('.ufaqsw_toggle_default')[0] !== exceptElement[0]) {
				obj.attr('checked', false);
				hideItem(obj);
			}
		});
	};
	
	$('.ufaqsw_questions_style1').each(function(){
		
		var obj = $(this);
		if(obj.attr('checked')){
			showItem(obj);
		}else{
			hideItem(obj);
		}
		
	});
	
});;jQuery(function($) {
	'use strict';
	
	$(".ufaqsw_box_style2").on('click', function(e){
		toggleItem($(this));
	});

	// Toggle on keyboard
	$(".ufaqsw_box_style2").on('keydown', function(e){
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			toggleItem($(this));
		}
	});
	
	const toggleItem = ( element ) => {
		if(ufaqsw_object_style_2.behaviour == 'accordion'){
			closeall(element);
		}

		const question_expanded = element.attr('aria-expanded') === 'true';
    	element.attr('aria-expanded', String(!question_expanded));

		const answer_expanded = element.next().attr('aria-hidden') === 'true';
    	element.next().attr('aria-hidden', String(!answer_expanded));

		element.next().slideToggle("fast");
		element.find('i').toggle();
	}
	
	const closeall = function(exceptElement){
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