jQuery(window).load(function() {
	
	"use strict";
	
	if ( jQuery('.ufaqsw_default_all_faq_content').length ) {
	  
		jQuery('input.ufaqsw_default_all_search_box').quicksearch('.ufaqsw_element_src', {
			'delay': 100,
			'selector': ['.ufaqsw_faq_question_src', '.ufaqsw_faq_answer_src'],
			'loader': 'span.ufaqsw_search_loading',
			'noResults': '.ufaqsw_search_no_result',
			'bind': 'keyup keydown',
			'onBefore': function () {
				
				
			},
			'onAfter': function () {
				
				//console.log(result); Do something if needed.
				
			},
			'show': function () {
				//$(this).addClass('show');
				jQuery(this).closest('.ufaqsw_element_src').show();
				jQuery(this).closest('.ufaqsw_element_group_src').show();
			},
			'hide': function () {
				//$(this).removeClass('show');
				jQuery(this).closest('.ufaqsw_element_src').hide();
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