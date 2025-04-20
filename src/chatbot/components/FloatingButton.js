import React from "react";

export const FloatingButton = ({ onClick }) => {
	return (
		<button
		onClick={onClick}
		style={{
			position: 'fixed',
			bottom: '20px',
			right: '20px',
			backgroundColor: '#007bff',
			color: '#fff',
			border: 'none',
			borderRadius: '50%',
			width: '60px',
			height: '60px',
			fontSize: '24px',
			cursor: 'pointer',
			boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)',
		}}
		>
		💬
		</button>
	);
};