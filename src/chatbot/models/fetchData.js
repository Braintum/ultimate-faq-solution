import React from 'react';
import { cachedFaqData } from '../components/ShadowApp';

/**
 * Fetches FAQ data from the server via AJAX and updates the application state.
 *
 * @async
 * @function fetchData
 * @param {Object} params - The parameters for fetching data.
 * @param {Function} params.setLoading - Function to set the loading state (true/false).
 * @param {string} params.ajaxUrl - The URL to send the AJAX request to.
 * @param {Function} params.setFaqData - Function to update the FAQ data state.
 * @param {string} params.nonce - Security nonce for the AJAX request.
 * @returns {Promise<void>} Resolves when the data has been fetched and state updated.
 */
const fetchData = async ({ setLoading, ajaxUrl, setFaqData, nonce }) => {
	setLoading(true); // Start loading
	try {
		const response = await fetch(ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: new URLSearchParams({
				action: 'ufaqsw_get_faqs',
				nonce: nonce,
			}),
		});

		const result = await response.json();
		if (result.success) {
			cachedFaqData = result.data; // Update cached state
			setFaqData(result.data); // Update state with fetched data
		} else {
			console.error('Error fetching FAQs:', result);
		}
	} catch (error) {
		console.error('Error fetching data:', error);
	} finally {
		setLoading(false); // Stop loading
	}
};

export default fetchData;