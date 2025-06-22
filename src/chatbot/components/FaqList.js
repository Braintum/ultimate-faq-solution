import React from "react";
import { ListItem } from "./ListItem";

/**
 * Renders a list of FAQ items for a given group.
 *
 * @component
 * @param {Object} props - The component props.
 * @param {Object} props.group - The FAQ group containing a description and items.
 * @param {string} props.group.description - The HTML description of the FAQ group.
 * @param {Array<Object>} props.group.items - The list of FAQ items in the group.
 * @param {Function} props.onListClick - Callback function to handle click events on FAQ items.
 * @returns {JSX.Element} The rendered FAQ list component.
 */
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