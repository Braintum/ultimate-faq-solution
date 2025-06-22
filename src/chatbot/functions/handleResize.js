import React from 'react';

/**
 * Adjusts the chatbot container's display based on the window width.
 * Adds the 'full-screen' class to the container if the window width is 768px or less,
 * otherwise removes the 'full-screen' class.
 *
 * @param {ShadowRoot} shadowRoot - The shadow root containing the chatbot container element.
 */
const handleResize = (shadowRoot) => {
	if (shadowRoot) {
		const chatbotContainer = shadowRoot.querySelector('.chatbot-container');
		if (chatbotContainer) {
			if (window.innerWidth <= 768) {
				chatbotContainer.classList.add('full-screen');
			} else {
				chatbotContainer.classList.remove('full-screen');
			}
		}
	}
};

export default handleResize;