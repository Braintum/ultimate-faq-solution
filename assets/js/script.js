jQuery(window).on('load', function() {
	
	"use strict";
	
	const ufaqsw_element_object = {
		faq_item: '.ufaqsw_element_src',
		faq_item_group: '.ufaqsw_element_group_src',
		faq_filter_item: '.filter-list__item',
	};

	jQuery(ufaqsw_element_object.faq_filter_item).on( 'click', function(e) {
		const filter_id = jQuery(this).data('index');
		const targetSection = jQuery(`#${filter_id}`);
		if (targetSection.length) {
			jQuery('html, body').animate({
				scrollTop: targetSection.offset().top - 140
			}, 500);
		}
	});

	if ( jQuery('.ufaqsw_default_all_faq_content').length ) {
	  
		jQuery('input.ufaqsw_default_all_search_box').quicksearch('.ufaqsw_element_src', {
			'delay': 300,
			'selector': ['.ufaqsw_faq_question_src', '.ufaqsw_faq_answer_src'],
			'loader': 'span.ufaqsw_search_loading',
			'noResults': '.ufaqsw_search_no_result',
			'bind': 'keyup keydown',
			'onBefore': function () {
			},
			'onAfter': function () {
				
				jQuery(ufaqsw_element_object.faq_item_group).each(function () {
					const group = jQuery(this);
					const visibleItems = group.find(ufaqsw_element_object.faq_item).filter(function() { 
						return jQuery(this).css('display') !== 'none'; 
					});

					if (visibleItems.length) {
						group.show();
					} else {
						group.hide();
					}
				});
				
			},
			'show': function () {
				jQuery(this).closest(ufaqsw_element_object.faq_item).show();
			},
			'hide': function () {
				jQuery(this).closest(ufaqsw_element_object.faq_item).hide();
			},
			'prepareQuery': function (val) {
				return new RegExp(val, "i");
			},
			'testQuery': function (query, txt, _row) {
				return query.test(txt);
			}
		});
	}

});;jQuery(function($) {
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
;// Per-FAQ feedback widget (Was this helpful?)
( function () {
	if ( typeof window.ufaqswFaqFeedback === 'undefined' ) { return; }
	var cfg = window.ufaqswFaqFeedback;

	document.addEventListener( 'DOMContentLoaded', function () {
		document.querySelectorAll( '.ufaqsw_faq_answer_src' ).forEach( function ( answerEl ) {
			var item = answerEl.closest( '.ufaqsw_element_src, .ufaqsw-faq__item' );
			if ( ! item ) { return; }
			var questionEl = item.querySelector( '.ufaqsw_faq_question_src' );
			if ( ! questionEl ) { return; }
			var question = questionEl.textContent.trim();

			var wrap = document.createElement( 'div' );
			wrap.className = 'ufaqsw-item-feedback';
			wrap.innerHTML =
				'<span class="ufaqsw-item-feedback__label">' + cfg.label + '</span>' +
				'<button class="ufaqsw-item-feedback__btn" data-vote="helpful">' + cfg.helpfulText + '</button>' +
				'<button class="ufaqsw-item-feedback__btn" data-vote="not_helpful">' + cfg.notHelpfulText + '</button>';

			// Universal template wraps answer content in an inner div; others put content directly.
			var target = answerEl.querySelector( '.ufaqsw-faq__answer-inner' ) || answerEl;
			target.appendChild( wrap );

			wrap.addEventListener( 'click', function ( e ) {
				var btn = e.target.closest( '.ufaqsw-item-feedback__btn' );
				if ( ! btn || wrap.dataset.voted ) { return; }
				var vote = btn.dataset.vote;
				wrap.dataset.voted = '1';
				wrap.innerHTML = '<span class="ufaqsw-item-feedback__thanks">' + cfg.thanksText + '</span>';

				fetch( cfg.ajaxUrl, {
					method:  'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body:    new URLSearchParams( {
						action:   'ufaqsw_faq_item_feedback',
						nonce:    cfg.nonce,
						question: question,
						vote:     vote,
					} ).toString(),
				} );
			} );
		} );
	} );
}() );
;jQuery(function($) {
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
;jQuery(function($) {
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
