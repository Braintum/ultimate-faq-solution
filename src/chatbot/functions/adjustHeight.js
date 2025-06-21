import React from 'react';

/**
 * Adjusts the height of the chatbot container to 80% of the window's height on desktop screens.
 *
 * @param {ShadowRoot} shadowRoot - The shadow root containing the chatbot container element.
 */
const adjustHeight = (shadowRoot) => {
	if (shadowRoot && window.innerWidth > 768) { // Only apply on desktop
		const chatbotContainer = shadowRoot.querySelector('.chatbot-container');
		if (chatbotContainer) {
			chatbotContainer.style.height = `${window.innerHeight * 0.8}px`; // Set height to 80% of window height
		}
	}
};

export default adjustHeight;