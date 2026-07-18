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

		element.find('.ufaqsw-icon-normal, .ufaqsw-icon-active').toggle();

		const isExpanded = element.attr('aria-expanded') === 'true';
		element.attr('aria-expanded', String(!isExpanded));

		const answer = element.closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default');
		answer.attr('aria-hidden', String(isExpanded));
	};

	const showItem = (element) => {
		element.addClass('ufaqsw_active')
			.closest('.ufaqsw_toggle_default')
			.find('.ufaqsw-toggle-inner-default')
			.slideDown(200);
	};

	const hideItem = (element) => {
		element.removeClass('ufaqsw_active')
			.closest('.ufaqsw_toggle_default')
			.find('.ufaqsw-toggle-inner-default')
			.slideUp(200);
	};

	// Closes all open items within the same group container, except the one clicked.
	const closeall = (exceptElement) => {
		const container = exceptElement.closest('.ufaqsw_container_default');
		container.find('.ufaqsw_toggle_default .ufaqsw-toggle-title-area-default').each(function() {
			if (jQuery(this).hasClass('ufaqsw_active') && this !== exceptElement[0]) {
				hideItem(jQuery(this));
				jQuery(this).find('.ufaqsw-icon-normal').show();
				jQuery(this).find('.ufaqsw-icon-active').hide();
				jQuery(this).attr('aria-expanded', 'false');
				jQuery(this).closest('.ufaqsw_toggle_default')
					.find('.ufaqsw-toggle-inner-default')
					.attr('aria-hidden', 'true');
			}
		});
	};

	// Handle click
	$('.ufaqsw_toggle_default .ufaqsw-toggle-title-area-default').on('click', function() {
		toggleItem($(this));
	});

	// Handle keyboard
	$('.ufaqsw_toggle_default .ufaqsw-toggle-title-area-default').on('keydown', function(e) {
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			toggleItem($(this));
		}
	});

	// Expand / Collapse All button
	$(document).on('click', '.ufaqsw_container_default .ufaqsw-toggle-all-btn', function() {
		const btn = $(this);
		const container = btn.closest('.ufaqsw_container_default');
		const isAllExpanded = btn.data('all-expanded') === 'true' || btn.data('all-expanded') === true;

		if (isAllExpanded) {
			container.find('.ufaqsw_toggle_default .ufaqsw-toggle-title-area-default.ufaqsw_active').each(function() {
				const el = $(this);
				hideItem(el);
				el.find('.ufaqsw-icon-normal').show();
				el.find('.ufaqsw-icon-active').hide();
				el.attr('aria-expanded', 'false');
				el.closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').attr('aria-hidden', 'true');
			});
			btn.data('all-expanded', false);
			btn.find('.ufaqsw-toggle-all-label-expand').show();
			btn.find('.ufaqsw-toggle-all-label-collapse').hide();
		} else {
			container.find('.ufaqsw_toggle_default .ufaqsw-toggle-title-area-default:not(.ufaqsw_active)').each(function() {
				const el = $(this);
				showItem(el);
				el.find('.ufaqsw-icon-normal').hide();
				el.find('.ufaqsw-icon-active').show();
				el.attr('aria-expanded', 'true');
				el.closest('.ufaqsw_toggle_default').find('.ufaqsw-toggle-inner-default').attr('aria-hidden', 'false');
			});
			btn.data('all-expanded', true);
			btn.find('.ufaqsw-toggle-all-label-expand').hide();
			btn.find('.ufaqsw-toggle-all-label-collapse').show();
		}
	});

	// Show all answers on start
	if (typeof ufaqsw_object_default !== 'undefined' && ufaqsw_object_default.showall == '1' && ufaqsw_object_default.behaviour != 'accordion') {
		$('.ufaqsw_toggle_default .ufaqsw-toggle-title-area-default').each(function() {
			$(this).trigger('click');
		});
	}
});
