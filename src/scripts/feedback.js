// Per-FAQ feedback widget (Was this helpful?)
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
