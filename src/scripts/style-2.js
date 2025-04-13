jQuery(document).ready(function($){
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