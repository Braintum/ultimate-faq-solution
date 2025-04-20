import React from "react";

export const FaqList = ({ faqs, onListClick, onBackClick }) => {
	return (
		<div className="faq-list">
			{faqs.map((faq, index) => (
				<div
					key={index}
					className="faq-item"
					onClick={() => onListClick(faq)}
				>
					{faq.question}
				</div>
			))}
		</div>
	);
}