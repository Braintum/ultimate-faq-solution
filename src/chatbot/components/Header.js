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
          >
            {`<`}
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
        >
          ✖
        </button>
      </div>
    </div>
  );
};
