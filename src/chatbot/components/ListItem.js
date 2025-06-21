import React from "react";

export const ListItem = ({ item, index, onClick, content }) => {
	return (
		<div key={index} className="list-item" onClick={() => onClick(item)}>
			<div className="title">{content}</div>
			{item.items && (
				<div className="description">
					{item.items.length} FAQs
				</div>
			)}
		</div>
	);
}