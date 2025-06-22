import React from "react";

/**
 * Renders the answer section for a given FAQ item.
 *
 * @component
 * @param {Object} props
 * @param {Object} props.faq - The FAQ item to display.
 * @param {string} props.faq.question - The question text.
 * @param {string} props.faq.answer - The answer HTML content (rendered as HTML).
 * @returns {JSX.Element} The rendered FAQ answer component.
 */
export const FaqAnswer = ({ faq }) => {
	return (
		<div className="faq-answer">
			<h2>{faq.question}</h2>
			<div
				className="faq-answer-content"
				dangerouslySetInnerHTML={{ __html: faq.answer }}
			></div>
		</div>
	);
}