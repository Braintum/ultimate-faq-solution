import React from 'react';

export const FaqGroups = ({ faqData, onGroupClick }) => {
  return (
    <div className="faq-groups">
      {faqData.map((group, index) => (
        <div
          key={index}
          className="faq-group"
          onClick={() => onGroupClick(group)}
        >
          {group.group}
        </div>
      ))}
    </div>
  );
};
