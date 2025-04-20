import React, { useState } from 'react';

export const ChatInput = ({ onSend }) => {
	const [input, setInput] = useState('');
	const handleClick = () => {
		if (input.trim()) {
		onSend(input.trim());
		setInput('');
		}
	};
	return (
		<div style={{ display: 'flex' }}>
		<input
			value={input}
			onChange={(e) => setInput(e.target.value)}
			style={{ flex: 1, padding: '5px' }}
			placeholder="Type something"
		/>
		<button onClick={handleClick} style={{ padding: '5px 10px' }}>Send</button>
		</div>
	);
};