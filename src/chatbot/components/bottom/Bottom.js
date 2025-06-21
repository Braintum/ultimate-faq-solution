import React from 'react';

const Bottom = ({ text }) => {
  return (
	<div className="chatbot-footer" dangerouslySetInnerHTML={{ __html: text }}></div>
  );
};

export default Bottom;
