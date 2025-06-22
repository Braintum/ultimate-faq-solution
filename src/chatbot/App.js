import React, { useState } from 'react';
import ShadowWrapper from './components/ShadowWrapper'; // Updated import
import { FloatingButton } from './components/FloatingButton'; // Updated import

/**
 * App component manages the visibility of a chatbot interface.
 * 
 * Renders a floating button when the chatbot is hidden, and a shadow wrapper
 * containing the chatbot when it is visible. Handles toggling and closing
 * of the chatbot interface.
 *
 * @component
 * @returns {JSX.Element} The rendered App component.
 */
export const App = () => {
	const [isChatbotVisible, setChatbotVisible] = useState(false);

	const toggleChatbot = () => {
		setChatbotVisible((prev) => !prev);
	};

	const closeChatbot = () => {
		setChatbotVisible(false);
	};

	return (
		<>
		{!isChatbotVisible && <FloatingButton onClick={toggleChatbot} />}
		{isChatbotVisible && <ShadowWrapper onClose={closeChatbot} />}
		</>
	);
};