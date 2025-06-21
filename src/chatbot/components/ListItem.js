import React from "react";

/**
 * Renders a list item component for the chatbot FAQ list.
 *
 * @param {Object} props - The component props.
 * @param {Object} props.item - The item object containing FAQ data.
 * @param {number} props.index - The index of the item in the list.
 * @param {Function} props.onClick - Callback function to handle click events on the item.
 * @param {string} props.content - The title or content to display for the item.
 * @param {string} [props.faqs_count_text] - Template string for displaying the FAQ count, with "[count]" as a placeholder.
 * @returns {JSX.Element} The rendered list item component.
 */
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