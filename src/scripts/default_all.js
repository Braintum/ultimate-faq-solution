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

});