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
			onClick={onClick} // Ensure this triggers the chatbot to open
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
				cursor: 'pointer'
			}}
		>
			{/* Empty content since the icon is set as background */}
		</button>
	);
};