import React from "react";

export const ListItem = ({ item, index, onClick, content }) => {
	return (
		<div key={index} className="list-item" onClick={() => onClick(item)}>
			{content}
		</div>
	);
}