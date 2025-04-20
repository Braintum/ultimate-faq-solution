import React, { useState } from 'react';
import { ChatInput } from './ChatInput';
import { FaqGroups } from './FaqGroups';
import { FaqList } from './FaqList';
import { FaqAnswer } from './FaqAnswer';

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
        <FaqGroups faqData={faqData} onGroupClick={handleGroupClick} />
      )}

      {view === 'list' && selectedGroup && (
		<FaqList faqs={selectedGroup.items} onListClick={handleQuestionClick} onBackClick={handleBackClick} />
      )}

      {view === 'answer' && selectedFaq && (
		<FaqAnswer faq={selectedFaq} onBackClick={handleBackClick} />
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
