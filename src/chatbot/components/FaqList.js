import React from "react";

export const FaqList = ({ faqs, onListClick, onBackClick }) => {
	return (
		<div className="faq-list">

			<button
				onClick={()=> onBackClick()}
				className="faq-back-button"
			>
				Back
			</button>

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