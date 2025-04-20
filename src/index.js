import React from 'react';
import { createRoot } from 'react-dom/client';
import { App } from './chatbot/App'; // Updated import

// Mount the floating button and chatbot
const container = document.createElement('div');
document.body.appendChild(container);
const root = createRoot(container); // Use createRoot from react-dom/client
root.render(<App />);