import React, { useState } from 'react';
import { createPortal } from 'react-dom';
import { ChatInput } from './ChatInput';

export const ShadowApp = ({ onClose }) => {
	const [messages, setMessages] = useState([]);

	const handleSend = (text) => {
		setMessages([...messages, { sender: 'user', text }]);
		setTimeout(() => {
		setMessages((prev) => [...prev, { sender: 'bot', text: "Echo: " + text }]);
		}, 500);
	};

	return (
		<div style={{
		position: 'fixed', // Make the chatbot float
		bottom: '90px', // Position it above the floating button
		right: '20px',
		fontFamily: 'Arial',
		width: '300px',
		border: '1px solid #ccc',
		borderRadius: '10px',
		padding: '10px',
		backgroundColor: '#fff',
		boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)',
		zIndex: 1000, // Ensure it appears above other elements
		}}>
		<button
			onClick={onClose}
			style={{
			position: 'absolute',
			top: '10px',
			right: '10px',
			backgroundColor: 'transparent',
			border: 'none',
			fontSize: '16px',
			cursor: 'pointer',
			}}
		>
			✖
		</button>
		<div style={{ height: '200px', overflowY: 'auto', marginBottom: '10px' }}>
			{messages.map((msg, i) => (
			<div key={i}><strong>{msg.sender}:</strong> {msg.text}</div>
			))}
		</div>
		<ChatInput onSend={handleSend} />
		</div>
	);
};
