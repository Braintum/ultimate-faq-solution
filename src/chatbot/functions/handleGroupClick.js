import React from 'react';

/**
 * Handles the event when a group is clicked.
 *
 * @param {Object} params - The parameters for handling the group click.
 * @param {any} params.group - The group object that was clicked.
 * @param {Function} params.setSelectedGroup - Function to set the selected group.
 * @param {Function} params.setView - Function to update the current view.
 */
const handleGroupClick = (params) => {
	const { group, setSelectedGroup, setView } = params;
	setSelectedGroup(group);
	setView('list');
};

export default handleGroupClick;