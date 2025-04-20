import React from 'react';
import { ListItem } from './ListItem';

export const FaqGroups = ({ faqData, onGroupClick }) => {
  return (
    <div className="faq-groups list-items">
      {faqData.map((group, index) => (
		<ListItem item={group} index={index} onClick={onGroupClick} content={group.group} />
      ))}
    </div>
  );
};
