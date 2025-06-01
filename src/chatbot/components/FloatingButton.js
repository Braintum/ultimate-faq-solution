import React, { useState, useEffect } from 'react';
import "../styles/floatingButton.css"; // Import your CSS file for styling

export const FloatingButton = ({ onClick }) => {

	const [floatingButtonTitle, setFloatingButtonTitle] = useState('');
	const [floatingButtonIcon, setFloatingButtonIcon] = useState('');

	useEffect(() => {
		// Access the global chatbotData object
		if (window.chatbotData) {
			setFloatingButtonTitle(window.chatbotData.floating_button_title);
			setFloatingButtonIcon(window.chatbotData.floating_button_icon);
		}
	}, []);

	return (
		<button
			onClick={onClick}
			className="ufaqsw_floating-chat-button"
			title={floatingButtonTitle}
			style={{
				backgroundImage: `url('${floatingButtonIcon}')`,
				backgroundSize: 'cover',
				backgroundRepeat: 'no-repeat',
				backgroundPosition: 'center',
				width: '80px',
				height: '80px',
				border: 'none',
				cursor: 'pointer',
				opacity: 0.7, // Default opacity
				transition: 'opacity 0.2s'
			}}
			onMouseEnter={e => (e.currentTarget.style.opacity = 1)}
			onMouseLeave={e => (e.currentTarget.style.opacity = 0.7)}
		>
			{/* Empty content since the icon is set as background */}
		</button>
	);
};