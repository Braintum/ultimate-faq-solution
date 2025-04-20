import React from 'react';

export const Header = ({ onClose, onBack, showBackButton, title }) => {
  return (
    <div className="header">
      <div className="header-content">
        {showBackButton && (
          <button
            onClick={onBack}
            className="faq-back-button"
          >
            {'<'}
          </button>
        )}
        <span className="chatbot-title">{title}</span>
        <button
          onClick={onClose}
          className="chatbot-close-button"
        >
          ✖
        </button>
      </div>
    </div>
  );
};
