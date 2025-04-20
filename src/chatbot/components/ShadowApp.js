import React, { useState } from 'react';
import { ChatInput } from './ChatInput';

export const ShadowApp = ({ onClose }) => {
  const [messages, setMessages] = useState([]);

  const handleSend = (text) => {
    setMessages([...messages, { sender: 'user', text }]);
    setTimeout(() => {
      setMessages((prev) => [...prev, { sender: 'bot', text: "Echo: " + text }]);
    }, 500);
  };

  return (
    <div className="chatbot-container">
      <button
        onClick={onClose}
        className="chatbot-close-button"
      >
        ✖
      </button>
      <div className="chatbot-messages">
        {messages.map((msg, i) => (
          <div key={i}><strong>{msg.sender}:</strong> {msg.text}</div>
        ))}
      </div>
      <ChatInput onSend={handleSend} />
    </div>
  );
};
