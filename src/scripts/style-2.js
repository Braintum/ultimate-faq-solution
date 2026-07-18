jQuery(function($) {
	'use strict';

	$('.ufaqsw_box_style2').on('click', function() {
		toggleItem($(this));
	});

	$('.ufaqsw_box_style2').on('keydown', function(e) {
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			toggleItem($(this));
		}
	});

	const toggleItem = (element) => {
		if (ufaqsw_object_style_2.behaviour == 'accordion') {
			closeall(element.closest('.ufaqsw_container_style2'), element);
		}

		const isExpanded = element.attr('aria-expanded') === 'true';
		element.attr('aria-expanded', String(!isExpanded));
		element.next().attr('aria-hidden', String(isExpanded));

		element.next().slideToggle('fast');
		element.find('.ufaqsw-icon-normal, .ufaqsw-icon-active').toggle();
	};

	// Closes all visible answers within the container, except the one belonging to exceptElement.
	const closeall = (container, exceptElement) => {
		container.find('.ufaqsw_draw_style2').each(function() {
			const answer = $(this);
			if (answer.is(':visible') && answer.prev()[0] !== exceptElement[0]) {
				answer.slideToggle('fast');
				answer.prev().find('.ufaqsw-icon-normal').show();
				answer.prev().find('.ufaqsw-icon-active').hide();
				answer.prev().attr('aria-expanded', 'false');
				answer.attr('aria-hidden', 'true');
			}
		});
	};

	// Expand / Collapse All button
	$(document).on('click', '.ufaqsw_container_style2 .ufaqsw-toggle-all-btn', function() {
		const btn = $(this);
		const container = btn.closest('.ufaqsw_container_style2');
		const isAllExpanded = btn.data('all-expanded') === 'true' || btn.data('all-expanded') === true;

		if (isAllExpanded) {
			container.find('.ufaqsw_draw_style2:visible').each(function() {
				const answer = $(this);
				answer.slideToggle('fast');
				answer.prev().find('.ufaqsw-icon-normal').show();
				answer.prev().find('.ufaqsw-icon-active').hide();
				answer.prev().attr('aria-expanded', 'false');
				answer.attr('aria-hidden', 'true');
			});
			btn.data('all-expanded', false);
			btn.find('.ufaqsw-toggle-all-label-expand').show();
			btn.find('.ufaqsw-toggle-all-label-collapse').hide();
		} else {
			container.find('.ufaqsw_draw_style2:hidden').each(function() {
				const answer = $(this);
				answer.slideToggle('fast');
				answer.prev().find('.ufaqsw-icon-normal').hide();
				answer.prev().find('.ufaqsw-icon-active').show();
				answer.prev().attr('aria-expanded', 'true');
				answer.attr('aria-hidden', 'false');
			});
			btn.data('all-expanded', true);
			btn.find('.ufaqsw-toggle-all-label-expand').hide();
			btn.find('.ufaqsw-toggle-all-label-collapse').show();
		}
	});

	// "Show all answers" initial state is handled by PHP inline styles in the template.
	// No JS trigger needed — triggering click on already-visible answers would close them.
});
