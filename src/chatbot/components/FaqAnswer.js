import React from "react";

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