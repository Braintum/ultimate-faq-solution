import React from 'react';

/**
 * Handles the event when a FAQ question is clicked.
 *
 * @param {Object} params - The parameters for handling the question click.
 * @param {Object} params.faq - The FAQ object that was clicked.
 * @param {Function} params.setSelectedFaq - Function to set the selected FAQ.
 * @param {Function} params.setView - Function to change the current view.
 */
const handleQuestionClick = (params) => {
	const { faq, setSelectedFaq, setView } = params;
	setSelectedFaq(faq);
	setView('answer');
};

export default handleQuestionClick;