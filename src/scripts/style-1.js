jQuery(function($) {
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
	
});