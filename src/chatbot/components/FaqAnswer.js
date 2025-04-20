import React from "react";

export const FaqAnswer = ({ faq, onBackClick }) => {
	return (
		<div className="faq-answer">
			<h2>{faq.question}</h2>
			<p>{faq.answer}</p>
		</div>
	);
}