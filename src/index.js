import React from 'react';
import { createRoot } from 'react-dom/client';
import { App } from './chatbot/App'; // Updated import

// Locate the custom element
const chatbotComponent = document.querySelector('chatbot-component');
if (chatbotComponent) {
    // Create a div inside the custom element
    const container = document.createElement('div');
    chatbotComponent.appendChild(container);

    // Mount the React app to the created div
    const root = createRoot(container); // Use createRoot from react-dom/client
    root.render(<App />);
}