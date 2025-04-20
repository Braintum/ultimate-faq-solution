import { createRoot } from 'react-dom/client';
import ReactDOM from 'react-dom/client';
import { createPortal } from 'react-dom';
import React, { useEffect, useState } from 'react';

const shadowHost = document.getElementById('chatbot-root');
const shadowRoot = shadowHost.attachShadow({ mode: 'open' });

const ShadowApp = ({ onClose }) => {
  const [messages, setMessages] = useState([]);

  const handleSend = (text) => {
    setMessages([...messages, { sender: 'user', text }]);
    setTimeout(() => {
      setMessages((prev) => [...prev, { sender: 'bot', text: "Echo: " + text }]);
    }, 500);
  };

  return (
    <div style={{
      position: 'fixed', // Make the chatbot float
      bottom: '90px', // Position it above the floating button
      right: '20px',
      fontFamily: 'Arial',
      width: '300px',
      border: '1px solid #ccc',
      borderRadius: '10px',
      padding: '10px',
      backgroundColor: '#fff',
      boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)',
      zIndex: 1000, // Ensure it appears above other elements
    }}>
      <button
        onClick={onClose}
        style={{
          position: 'absolute',
          top: '10px',
          right: '10px',
          backgroundColor: 'transparent',
          border: 'none',
          fontSize: '16px',
          cursor: 'pointer',
        }}
      >
        ✖
      </button>
      <div style={{ height: '200px', overflowY: 'auto', marginBottom: '10px' }}>
        {messages.map((msg, i) => (
          <div key={i}><strong>{msg.sender}:</strong> {msg.text}</div>
        ))}
      </div>
      <ChatInput onSend={handleSend} />
    </div>
  );
};

const ChatInput = ({ onSend }) => {
  const [input, setInput] = useState('');
  const handleClick = () => {
    if (input.trim()) {
      onSend(input.trim());
      setInput('');
    }
  };
  return (
    <div style={{ display: 'flex' }}>
      <input
        value={input}
        onChange={(e) => setInput(e.target.value)}
        style={{ flex: 1, padding: '5px' }}
        placeholder="Type something"
      />
      <button onClick={handleClick} style={{ padding: '5px 10px' }}>Send</button>
    </div>
  );
};

// Mount using React Portal
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

// Mount the floating button and chatbot
const container = document.createElement('div');
document.body.appendChild(container);
const root = ReactDOM.createRoot(container);
root.render(<App />);