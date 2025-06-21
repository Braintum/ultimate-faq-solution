import React from 'react';

// Accept a single 'headerProps' object to reduce the number of function parameters.
export const Header = (headerProps) => {
  const {
    onClose,
    onBack,
    showBackButton,
    title,
    intro,
    headerColor,
    textColor,
    back_button_label,
    close_button_label
  } = headerProps;

  return (
    <div
      className="header"
      style={{
        ...(headerColor ? { backgroundColor: headerColor } : {}),
        ...(textColor ? { color: textColor } : {})
      }}
    >
      <div className="header-content">
        {showBackButton && (
          <button
            onClick={onBack}
            className="faq-back-button"
            style={textColor ? { color: textColor } : undefined}
            aria-label="Back"
            title={back_button_label}
          >
            <svg
              width="20"
              height="20"
              viewBox="0 0 20 20"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              style={textColor ? { color: textColor } : undefined}
            >
              <polyline points="13 5 7 10 13 15" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
            </svg>
          </button>
        )}
        <div className="chatbot-title-group">
          <span className="chatbot-title">{title}</span>
          {intro && (
            <span className="chatbot-intro">{intro}</span>
          )}
        </div>
        <button
          onClick={onClose}
          className="chatbot-close-button"
          style={textColor ? { color: textColor } : undefined}
          aria-label="Close"
          title={close_button_label}
        >
          {(
            <svg
              width="20"
              height="20"
              viewBox="0 0 20 20"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              style={textColor ? { color: textColor } : undefined}
            >
              <line x1="5" y1="5" x2="15" y2="15" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
              <line x1="15" y1="5" x2="5" y2="15" stroke="currentColor" strokeWidth="2" strokeLinecap="round"/>
            </svg>
          )}
        </button>
      </div>
    </div>
  );
};
