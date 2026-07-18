import React from 'react';
import { createPortal } from 'react-dom';
import { ShadowApp } from './ShadowApp';
import chatbotStyles from '../styles/chatbot.css?shadow';

const chatbotComponent = document.querySelector('chatbot-component');
if (!chatbotComponent) {
	throw new Error('<chatbot-component> not found in the DOM');
}

const shadowHost = document.createElement('div');
chatbotComponent.appendChild(shadowHost);
const shadowRoot = shadowHost.attachShadow({ mode: 'open' });

const ShadowWrapper = ({ onClose }) => {
	let mountPoint = shadowRoot.querySelector('.chatbot-wrapper');
	if (!mountPoint) {
		mountPoint = document.createElement('div');
		mountPoint.classList.add('chatbot-wrapper');

		const style = document.createElement('style');
		style.textContent = chatbotStyles;
		shadowRoot.appendChild(style);

		// Inject dynamic CSS custom property for primary color
		const primaryColor = (window.chatbotData && window.chatbotData.header_background_color) || '#1a185e';
		const varStyle = document.createElement('style');
		varStyle.textContent = `*, *::before, *::after { --chatbot-primary: ${primaryColor}; }`;
		shadowRoot.appendChild(varStyle);

		shadowRoot.appendChild(mountPoint);
	}

	return createPortal(<ShadowApp onClose={onClose} shadowRoot={shadowRoot} />, mountPoint);
};

export default ShadowWrapper;
