import React from "react";
import "../styles/floatingButton.css"; // Import your CSS file for styling

export const FloatingButton = ({ onClick }) => {
	return (
		<button
		onClick={onClick}
		className="ufaqsw_floating-chat-button"
		style={{
			backgroundImage: `url('http://ultimate-faq-solution.devlocal/wp-content/uploads/2025/04/faq-bot-image.svg')`,
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