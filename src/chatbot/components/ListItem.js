import React from "react";

export const ListItem = ({ item, index, onClick, content, faqs_count_text }) => {
	const faqsCountText = faqs_count_text && item.items
		? faqs_count_text.replace("[count]", item.items.length)
		: undefined;

	return (
		<div key={index} className="list-item" onClick={() => onClick(item)}>
			<div className="title">{content}</div>
			{item.items && (
				<div className="description">
					{faqsCountText}
				</div>
			)}
		</div>
	);
}