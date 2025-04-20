import React from "react";
import { ListItem } from "./ListItem";

export const FaqList = ({ faqs, onListClick }) => {
	return (
		<div className="faq-list list-items">
			{faqs.map((faq, index) => (
				<ListItem item={faq} index={index} onClick={onListClick} content={faq.question} />
			))}
		</div>
	);
}