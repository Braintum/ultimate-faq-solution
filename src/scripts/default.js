jQuery(function($) {
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
		// Scope to the same group container to avoid closing items in other groups on the page.
		const container = exceptElement.closest('.ufaqsw_container_default');
		container.find('.ufaqsw_toggle_default .ufaqsw-toggle-title-area-default').each(function() {
			if (jQuery(this).hasClass('ufaqsw_active') && this !== exceptElement[0]) {
				hideItem(jQuery(this));
				jQuery(this).find('i').each(function() {
					// Reset icons: ensure normal icon is visible, active icon is hidden.
					if (jQuery(this).attr('id') === 'ufaqsw_other_style') {
						jQuery(this).hide();
					} else {
						jQuery(this).show();
					}
				});
				jQuery(this).attr('aria-expanded', 'false');
				jQuery(this).closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').attr('aria-hidden', 'true');
			}
		});
	};

	// Show all answers on start
	if (typeof ufaqsw_object_default !== 'undefined' && ufaqsw_object_default.showall == '1' && ufaqsw_object_default.behaviour != 'accordion') {
		jQuery(".ufaqsw_toggle_default .ufaqsw-toggle-title-area-default").each(function() {
			jQuery(this).trigger("click");
		});
	}
});
