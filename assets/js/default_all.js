jQuery(window).load(function() {
	
	"use strict";
	
	var grid = jQuery('.ufaqsw_default_all_faq_content').packery({
		  // options
		  itemSelector: '.ufaqsw_default_all_single_faq',
		  resize: true,
		  transitionDuration: '0.0s',
		  initLayout: true,
		  gutter: 50
		});	
	
	setTimeout(function(){
		grid.packery();
		jQuery('.ufaqsw_default_all_single_faq').css("visibility", "visible");
	}, 100)
	
	jQuery('.ufaqsw_toggle_default, .ufaqsw_FaQ_Each_style2').on('click', function(e){
		var obj = jQuery(this);

		setTimeout(function(){
			grid.packery();
		}, 200)
	})
	
	var clearnempthdiv = function(){
		return new Promise(function(resolve, reject){
			jQuery('.ufaqsw_element_group_src').each(function(){
				if(jQuery(this).find('.ufaqsw_element_src:visible').length==0){
					jQuery(this).hide();
				}
			})
			resolve('cleaned the empty div');
		})
	}
	var restartGrid = function(message){
		return new Promise(function(resolve, reject){
			grid.packery();
			resolve(message + ' Restarted Grid');
		})
	}
	
	jQuery('input.ufaqsw_default_all_search_box').quicksearch('.ufaqsw_element_src', {
		'delay': 100,
		'selector': ['.ufaqsw_faq_question_src', '.ufaqsw_faq_answer_src'],
		'loader': 'span.ufaqsw_search_loading',
		'noResults': '.ufaqsw_search_no_result',
		'bind': 'keyup keydown',
		'onBefore': function () {
			
			
		},
		'onAfter': function () {
			
			clearnempthdiv().then(function(result){
				return restartGrid(result);
			}).then(function(result){
				//console.log(result);
			})
			
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
	
});