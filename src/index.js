import { createRoot } from 'react-dom/client';
import React, { useEffect, useState } from 'react';
import ShadowWrapper from './components/ShadowWrapper'; // Updated import

const FloatingButton = ({ onClick }) => {
  return (
    <button
      onClick={onClick}
      style={{
        position: 'fixed',
        bottom: '20px',
        right: '20px',
        backgroundColor: '#007bff',
        color: '#fff',
        border: 'none',
        borderRadius: '50%',
        width: '60px',
        height: '60px',
        fontSize: '24px',
        cursor: 'pointer',
        boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)',
      }}
    >
      💬
    </button>
  );
};

const App = () => {
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

console.log('Loading Ultimate FAQ Solution..');
// Mount the floating button and chatbot
const container = document.createElement('div');
document.body.appendChild(container);
const root = createRoot(container); // Use createRoot from react-dom/client
root.render(<App />);