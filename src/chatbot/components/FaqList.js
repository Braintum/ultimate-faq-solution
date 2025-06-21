import React from "react";
import { ListItem } from "./ListItem";

export const FaqList = ({ group, onListClick }) => {
	return (
		<div className="faq-groups">
			<div
				className="faq-groups-description"
				dangerouslySetInnerHTML={{ __html: group.description }}
			></div>
			<div className="list-items">
				{group.items.map((faq, index) => (
					<ListItem item={faq} index={index} onClick={onListClick} content={faq.question} />
				))}
			</div>
		</div>
	);
}