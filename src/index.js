import { createRoot } from 'react-dom/client';
import ReactDOM from 'react-dom/client';
import { createPortal } from 'react-dom';
import React, { useEffect, useState } from 'react';

/*
// Your chatbot component
const Chatbot = () => {
  return (
    <div>
      <h2>Chatbot Hello world</h2>
      <p>Hello! How can I help you? Mahedi</p>
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
*/

const shadowHost = document.getElementById('chatbot-root');
const shadowRoot = shadowHost.attachShadow({ mode: 'open' });

const ShadowApp = () => {
  const [messages, setMessages] = useState([]);

  const handleSend = (text) => {
    setMessages([...messages, { sender: 'user', text }]);
    setTimeout(() => {
      setMessages((prev) => [...prev, { sender: 'bot', text: "Echo: " + text }]);
    }, 500);
  };

  return (
    <div style={{
      fontFamily: 'Arial', width: '300px', border: '1px solid #ccc',
      borderRadius: '10px', padding: '10px', backgroundColor: '#fff'
    }}>
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
const ShadowWrapper = () => {
  const mountPoint = document.createElement('div');
  const style = document.createElement('style');
  style.textContent = `:host { all: initial; }`; // reset styles if needed
  shadowRoot.appendChild(style);
  shadowRoot.appendChild(mountPoint);

  return createPortal(<ShadowApp />, mountPoint);
};

const container = document.createElement('div');
document.body.appendChild(container);
const root = ReactDOM.createRoot(container);
root.render(<ShadowWrapper />);