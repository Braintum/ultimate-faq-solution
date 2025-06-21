import React from 'react';

/**
 * Renders the chatbot footer with the provided HTML content.
 *
 * @component
 * @param {Object} props - Component props.
 * @param {string} props.text - The HTML string to render inside the footer. This will be injected using dangerouslySetInnerHTML.
 * @returns {JSX.Element} The rendered chatbot footer component.
 */
const Bottom = ({ text }) => {
  return (
	<div className="chatbot-footer" dangerouslySetInnerHTML={{ __html: text }}></div>
  );
};

export default Bottom;
