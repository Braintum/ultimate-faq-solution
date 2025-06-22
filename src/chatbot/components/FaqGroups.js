import React from 'react';
import { ListItem } from './ListItem';

/**
 * Renders a list of FAQ groups with a description.
 *
 * @component
 * @param {Object} props - Component props.
 * @param {Array<Object>} props.faqData - Array of FAQ group objects to display.
 * @param {Function} props.onGroupClick - Callback function invoked when a group is clicked.
 * @param {string} props.description - HTML string for the description displayed above the groups.
 * @param {string} props.faqs_count_text - Text to display for the FAQ count in each group.
 * @returns {JSX.Element} The rendered FAQ groups component.
 */
export const FaqGroups = ({ faqData, onGroupClick, description, faqs_count_text }) => {
  return (
    <div className="faq-groups">
      <div
				className="faq-groups-description"
				dangerouslySetInnerHTML={{ __html: description }}
			></div>
      <div className="list-items">
        {faqData.map((group, index) => (
          <ListItem item={group} index={index} onClick={onGroupClick} content={group.group} faqs_count_text={faqs_count_text} />
        ))}
      </div>
    </div>
  );
};
