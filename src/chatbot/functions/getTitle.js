import React from 'react';

/**
 * Returns the appropriate title for the assistant window based on the current view and selection.
 *
 * @param {Object} params - The parameters object.
 * @param {'list'|'answer'} params.view - The current view mode of the assistant ('list' or 'answer').
 * @param {Object} [params.selectedGroup] - The currently selected group object, if any.
 * @param {string} params.selectedGroup.group - The name of the selected group.
 * @param {Object} [params.selectedFaq] - The currently selected FAQ object, if any.
 * @param {string} [params.assistantWindowHeadline] - The custom headline for the assistant window, if provided.
 * @returns {string} The title to display in the assistant window.
 */
const getTitle = ({ view, selectedGroup, selectedFaq, assistantWindowHeadline }) => {
  if (view === 'list' && selectedGroup) {
		return selectedGroup.group;
	}
	if (view === 'answer' && selectedFaq) {
		return selectedGroup.group;
	}
	return assistantWindowHeadline || 'Frequently Asked Questions'; // Default title if no specific group or FAQ is selected
};

export default getTitle;