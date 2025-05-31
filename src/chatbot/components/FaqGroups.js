import React from 'react';
import { ListItem } from './ListItem';

export const FaqGroups = ({ faqData, onGroupClick, description }) => {
  return (
    <div className="faq-groups">
      <p>{description}</p>
      <div className="list-items">
        {faqData.map((group, index) => (
          <ListItem item={group} index={index} onClick={onGroupClick} content={group.group} />
        ))}
      </div>
    </div>
  );
};
