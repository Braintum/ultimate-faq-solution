import React, { useState } from 'react';
import ShadowWrapper from './components/ShadowWrapper'; // Updated import
import { FloatingButton } from './components/FloatingButton'; // Updated import

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