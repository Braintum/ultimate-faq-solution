import React from 'react';
import { createPortal } from 'react-dom';
import { ShadowApp } from './ShadowApp';

const shadowHost = document.getElementById('chatbot-root');
const shadowRoot = shadowHost.attachShadow({ mode: 'open' });

const ShadowWrapper = ({ onClose }) => {
  console.log('Mounting ShadowWrapper');

  let mountPoint = shadowRoot.querySelector('div');
  if (!mountPoint) {
    mountPoint = document.createElement('div');
    const style = document.createElement('style');
    style.textContent = `:host { all: initial; }`; // reset styles if needed
    shadowRoot.appendChild(style);
    shadowRoot.appendChild(mountPoint);
  }

  return createPortal(<ShadowApp onClose={onClose} />, mountPoint);
};

export default ShadowWrapper;
