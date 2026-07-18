jQuery(function($) {
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
		if (ufaqsw_object_style_2.behaviour == 'accordion') {
			// Scope closeall to the same group container.
			closeall(element.closest('.ufaqsw_container_style2'), element);
		}

		const question_expanded = element.attr('aria-expanded') === 'true';
    	element.attr('aria-expanded', String(!question_expanded));

		const answer_expanded = element.next().attr('aria-hidden') === 'true';
    	element.next().attr('aria-hidden', String(!answer_expanded));

		element.next().slideToggle("fast");
		element.find('i').toggle();
	}

	// Closes all visible answers within the container, except the one belonging to exceptElement.
	const closeall = function(container, exceptElement) {
		container.find('.ufaqsw_draw_style2').each(function() {
			var obj = $(this);
			if (obj.is(":visible") && obj.prev()[0] !== exceptElement[0]) {
				obj.slideToggle("fast");
				obj.prev().find('i').toggle();
				obj.prev().attr('aria-expanded', 'false');
				obj.attr('aria-hidden', 'true');
			}
		});
	}

	if (typeof ufaqsw_object_style_2 !== 'undefined' && ufaqsw_object_style_2.showall == '1' && ufaqsw_object_style_2.behaviour != 'accordion') {
		$(".ufaqsw_box_style2").each(function() {
			$(this).trigger('click');
		});
	}
})
