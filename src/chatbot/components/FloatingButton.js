import React from "react";
import "../styles/floatingButton.css"; // Import your CSS file for styling

export const FloatingButton = ({ onClick }) => {
	return (
		<button
		onClick={onClick}
		className="ufaqsw_floating-chat-button"
		>
		💬
		</button>
	);
};