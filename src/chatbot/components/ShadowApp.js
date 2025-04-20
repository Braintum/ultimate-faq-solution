import React, { useState } from 'react';
import { ChatInput } from './ChatInput';

const faqData = [
  {
    group: "Account",
    items: [
      {
        question: "Login issues",
        answer: "Steps to resolve login issues.",
      },
      {
        question: "Billing problems",
        answer: "How to handle billing-related queries.",
      },
    ],
  },
  {
    group: "Privacy",
    items: [
      {
        question: "Data usage",
        answer: "Details on how we use your data.",
      },
      {
        question: "Policy updates",
        answer: "Information on recent policy changes.",
      },
    ],
  },
  {
    group: "API",
    items: [
      {
        question: "API limits",
        answer: "Details on API usage limits.",
      },
      {
        question: "Error handling",
        answer: "How to handle common API errors.",
      },
    ],
  },
];

export const ShadowApp = ({ onClose }) => {
  const [view, setView] = useState('group'); // 'group', 'list', or 'answer'
  const [selectedGroup, setSelectedGroup] = useState(null);
  const [selectedFaq, setSelectedFaq] = useState(null);
  const [messages, setMessages] = useState([]);

  const handleSend = (text) => {
    setMessages([...messages, { sender: 'user', text }]);
    setTimeout(() => {
      setMessages((prev) => [...prev, { sender: 'bot', text: "Echo: " + text }]);
    }, 500);
  };

  const handleGroupClick = (group) => {
    setSelectedGroup(group);
    setView('list');
  };

  const handleQuestionClick = (faq) => {
    setSelectedFaq(faq);
    setView('answer');
  };

  const handleBackClick = () => {
    if (view === 'answer') {
      setView('list');
      setSelectedFaq(null);
    } else if (view === 'list') {
      setView('group');
      setSelectedGroup(null);
    }
  };

  return (
    <div className="chatbot-container">
      <button
        onClick={onClose}
        className="chatbot-close-button"
      >
        ✖
      </button>

      {view === 'group' && (
        <div className="faq-groups">
          {faqData.map((group, index) => (
            <div
              key={index}
              className="faq-group"
              onClick={() => handleGroupClick(group)}
            >
              {group.group}
            </div>
          ))}
        </div>
      )}

      {view === 'list' && selectedGroup && (
        <div className="faq-list">
          <button
            onClick={handleBackClick}
            className="faq-back-button"
          >
            Back
          </button>
          {selectedGroup.items.map((faq, index) => (
            <div
              key={index}
              className="faq-question"
              onClick={() => handleQuestionClick(faq)}
            >
              {faq.question}
            </div>
          ))}
        </div>
      )}

      {view === 'answer' && selectedFaq && (
        <div className="faq-answer">
          <button
            onClick={handleBackClick}
            className="faq-back-button"
          >
            Back
          </button>
          <h2>{selectedFaq.question}</h2>
          <p>{selectedFaq.answer}</p>
        </div>
      )}

      <div className="chatbot-messages">
        {messages.map((msg, i) => (
          <div key={i}><strong>{msg.sender}:</strong> {msg.text}</div>
        ))}
      </div>
      <ChatInput onSend={handleSend} />
    </div>
  );
};
