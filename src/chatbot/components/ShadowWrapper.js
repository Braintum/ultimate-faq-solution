import React from 'react';
import { createPortal } from 'react-dom';
import { ShadowApp } from './ShadowApp';
import chatbotStyles from '../styles/chatbot.css?shadow';

// Locate the custom element
const chatbotComponent = document.querySelector('chatbot-component');
if (!chatbotComponent) {
  throw new Error('<chatbot-component> not found in the DOM');
}

// Create a new element inside the custom element and attach Shadow DOM
const shadowHost = document.createElement('div');
chatbotComponent.appendChild(shadowHost); // Append the new element to the custom element
const shadowRoot = shadowHost.attachShadow({ mode: 'open' });

/**
 * Renders the chatbot UI inside a Shadow DOM, ensuring styles are encapsulated.
 *
 * @component
 * @param {Object} props - Component props.
 * @param {Function} props.onClose - Callback function to handle closing the chatbot.
 * @returns {React.ReactPortal} A React portal rendering the ShadowApp inside the Shadow DOM.
 */
const ShadowWrapper = ({ onClose }) => {

  let mountPoint = shadowRoot.querySelector('div');
  if (!mountPoint) {
    mountPoint = document.createElement('div');
    mountPoint.classList.add('chatbot-wrapper'); // Add the class to ensure it can be selected
    const style = document.createElement('style');

    style.textContent = chatbotStyles; // Inject chatbot CSS into Shadow DOM
    shadowRoot.appendChild(style);
    shadowRoot.appendChild(mountPoint);
  }

  return createPortal(<ShadowApp onClose={onClose} shadowRoot={shadowRoot} />, mountPoint);
};

export default ShadowWrapper;
