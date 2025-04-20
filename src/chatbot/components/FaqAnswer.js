import React from "react";

export const FaqAnswer = ({ faq, onBackClick }) => {
	return (
		<div className="faq-answer">
			<button
				onClick={() => onBackClick()}
				className="faq-back-button"
			>
				Back
			</button>
			<div className="faq-answer-content">
				<h2>{faq.question}</h2>
				<p>{faq.answer}</p>
			</div>
		</div>
	);
}