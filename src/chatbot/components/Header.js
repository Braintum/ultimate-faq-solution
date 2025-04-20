import React from 'react';

export const Header = ({ onClose, onBack, showBackButton }) => {
  return (
    <div className="header">
		<div className='header-content'>
			{showBackButton && (
			<button
				onClick={onBack}
				className="faq-back-button"
				>
				Back
				</button>
			)}
			<span className="chatbot-title">Frequently Asked Questions</span>
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
