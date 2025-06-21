import React from 'react';

/**
 * Handles the back button click event in the chatbot UI, updating the view state accordingly.
 *
 * @param {Object} params - The parameters object.
 * @param {'group'|'list'|'answer'} params.view - The current view state.
 * @param {Function} params.setView - Function to update the view state.
 * @param {Function} params.setSelectedFaq - Function to update the selected FAQ (used when returning from 'answer' view).
 * @param {Function} params.setSelectedGroup - Function to update the selected group (used when returning from 'list' view).
 *
 * @returns {void}
 */
const handleBackClick = ({ view, setView, setSelectedFaq, setSelectedGroup }) => {
	if (view === 'answer') {
		setView('list');
		setSelectedFaq(null);
	} else if (view === 'list') {
		setView('group');
		setSelectedGroup(null);
	}
};

export default handleBackClick;