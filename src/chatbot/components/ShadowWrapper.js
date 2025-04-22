import React from 'react';
import { createPortal } from 'react-dom';
import { ShadowApp } from './ShadowApp';
import chatbotStyles from '../styles/chatbot.css?shadow';

// Create a new element and attach Shadow DOM
const shadowHost = document.createElement('div');
document.body.appendChild(shadowHost); // Append the new element to the body
const shadowRoot = shadowHost.attachShadow({ mode: 'open' });

const ShadowWrapper = ({ onClose }) => {
  console.log('Mounting ShadowWrapper');

  let mountPoint = shadowRoot.querySelector('div');
  if (!mountPoint) {
    mountPoint = document.createElement('div');
    const style = document.createElement('style');
    const fontAwesomeStyle = document.createElement('style');

    console.log(chatbotStyles);
    style.textContent = chatbotStyles; // Inject chatbot CSS into Shadow DOM
    shadowRoot.appendChild(fontAwesomeStyle);
    shadowRoot.appendChild(style);
    shadowRoot.appendChild(mountPoint);
  }

  return createPortal(<ShadowApp onClose={onClose} />, mountPoint);
};

export default ShadowWrapper;
