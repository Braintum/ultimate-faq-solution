import React from 'react';
import { createRoot } from 'react-dom/client';

// Your chatbot component
const Chatbot = () => {
  return (
    <div>
      <h2>Chatbot Hello world</h2>
      <p>Hello! How can I help you?</p>
    </div>
  );
};

// Create the React app inside a shadow DOM
class ChatbotWebComponent extends HTMLElement {
  connectedCallback() {
    const shadowRoot = this.attachShadow({ mode: 'open' });
    const root = createRoot(shadowRoot);
    root.render(<Chatbot />);
  }
}

customElements.define('chatbot-component', ChatbotWebComponent);
