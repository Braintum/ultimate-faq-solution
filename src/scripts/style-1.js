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

		if (ufaqsw_object_style_1.behaviour == 'accordion') {
			// Scope closeall to the same group container.
			closeall(element.closest('.ufaqsw_content_style1'), element.parent());
		}

		if (obj.prop('checked')) {
			obj.prop('checked', false);
			hideItem(obj);
		} else {
			obj.prop('checked', true);
			showItem(obj);
		}

		const question_expanded = element.attr('aria-expanded') === 'true';
    	element.attr('aria-expanded', String(!question_expanded));

		const answer_expanded = element.next().attr('aria-hidden') === 'true';
    	element.next().attr('aria-hidden', String(!answer_expanded));
	}

	const showItem = ( element ) => {
		element.next().next().css({"height": "auto", "opacity": "1"});
		element.next().find('.ufaqsw-style1-active-icon').css({"display": "inline-block"});
		element.next().find('.ufaqsw-style1-normal-icon').css({"display": "none"});
	}

	const hideItem = ( element ) => {
		element.next().next().css({"height": "", "opacity": "", "padding": ""});
		element.next().find('.ufaqsw-style1-active-icon').css({"display": "none"});
		element.next().find('.ufaqsw-style1-normal-icon').css({"display": "inline-block"});
	}

	// Closes all open items within the given container, except the specified item.
	var closeall = function(container, exceptItem) {
		container.find('.ufaqsw_questions_style1').each(function() {
			var obj = $(this);
			if (obj.closest('.ufaqsw_toggle_default')[0] !== exceptItem[0]) {
				obj.prop('checked', false);
				hideItem(obj);
				obj.closest('.ufaqsw_toggle_default').find('.ufaqsw_title_area_style1')
					.attr('aria-expanded', 'false')
					.next().attr('aria-hidden', 'true');
			}
		});
	};

});
