jQuery(function($) {
	'use strict';

	// Listen on the checkbox change event instead of a click on the title area.
	// This fires AFTER the browser has already toggled the checkbox via the <label>,
	// so prop('checked') always reflects the correct new state.
	$('.ufaqsw_questions_style1').on('change', function() {
		const checkbox = $(this);
		const faqItem  = checkbox.closest('.ufaqsw_toggle_default');
		const container = faqItem.closest('.ufaqsw_content_style1');
		const titleArea = faqItem.find('.ufaqsw_title_area_style1');
		const answerDiv = faqItem.find('.ufaqsw_answers_style1');

		if (ufaqsw_object_style_1.behaviour == 'accordion' && checkbox.prop('checked')) {
			closeall(container, faqItem[0]);
		}

		if (checkbox.prop('checked')) {
			showItem(checkbox);
			titleArea.attr('aria-expanded', 'true').addClass('ufaqsw_active_s1');
			answerDiv.attr('aria-hidden', 'false');
		} else {
			hideItem(checkbox);
			titleArea.attr('aria-expanded', 'false').removeClass('ufaqsw_active_s1');
			answerDiv.attr('aria-hidden', 'true');
		}
	});

	// Keyboard support: manually toggle the checkbox and fire change.
	// Space/Enter on the label's parent div won't trigger the native label behavior,
	// so we handle it ourselves.
	$('.ufaqsw_toggle_default > .ufaqsw_title_area_style1').on('keydown', function(e) {
		if (e.key === 'Enter' || e.key === ' ') {
			e.preventDefault();
			const checkbox = $(this).prev(); // immediate previous sibling is the checkbox
			checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
		}
	});

	const showItem = (checkbox) => {
		checkbox.next().next().css({ height: 'auto', opacity: '1' });
		checkbox.next().find('.ufaqsw-style1-active-icon').css({ display: 'inline-block' });
		checkbox.next().find('.ufaqsw-style1-normal-icon').css({ display: 'none' });
	};

	const hideItem = (checkbox) => {
		checkbox.next().next().css({ height: '', opacity: '', padding: '' });
		checkbox.next().find('.ufaqsw-style1-active-icon').css({ display: 'none' });
		checkbox.next().find('.ufaqsw-style1-normal-icon').css({ display: 'inline-block' });
	};

	// Close all items in the container except the one currently being opened.
	// Called directly (not via change event) to avoid recursive loops.
	const closeall = (container, exceptItem) => {
		container.find('.ufaqsw_questions_style1').each(function() {
			const checkbox = $(this);
			const faqItem  = checkbox.closest('.ufaqsw_toggle_default');
			if (checkbox.prop('checked') && faqItem[0] !== exceptItem) {
				checkbox.prop('checked', false);
				hideItem(checkbox);
				const titleArea = faqItem.find('.ufaqsw_title_area_style1');
				titleArea.attr('aria-expanded', 'false').removeClass('ufaqsw_active_s1');
				titleArea.next().attr('aria-hidden', 'true');
			}
		});
	};

	// Expand / Collapse All button
	$(document).on('click', '.ufaqsw_content_style1 .ufaqsw-toggle-all-btn', function() {
		const btn = $(this);
		const container = btn.closest('.ufaqsw_content_style1');
		const isAllExpanded = btn.data('all-expanded') === 'true' || btn.data('all-expanded') === true;

		if (isAllExpanded) {
			container.find('.ufaqsw_questions_style1').each(function() {
				const checkbox = $(this);
				if (checkbox.prop('checked')) {
					checkbox.prop('checked', false);
					hideItem(checkbox);
					const titleArea = checkbox.closest('.ufaqsw_toggle_default').find('.ufaqsw_title_area_style1');
					titleArea.attr('aria-expanded', 'false').removeClass('ufaqsw_active_s1');
					titleArea.next().attr('aria-hidden', 'true');
				}
			});
			btn.data('all-expanded', false);
			btn.find('.ufaqsw-toggle-all-label-expand').show();
			btn.find('.ufaqsw-toggle-all-label-collapse').hide();
		} else {
			container.find('.ufaqsw_questions_style1').each(function() {
				const checkbox = $(this);
				if (!checkbox.prop('checked')) {
					checkbox.prop('checked', true);
					showItem(checkbox);
					const titleArea = checkbox.closest('.ufaqsw_toggle_default').find('.ufaqsw_title_area_style1');
					titleArea.attr('aria-expanded', 'true').addClass('ufaqsw_active_s1');
					titleArea.next().attr('aria-hidden', 'false');
				}
			});
			btn.data('all-expanded', true);
			btn.find('.ufaqsw-toggle-all-label-expand').hide();
			btn.find('.ufaqsw-toggle-all-label-collapse').show();
		}
	});
});
