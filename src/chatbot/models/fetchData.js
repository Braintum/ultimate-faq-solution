import React from 'react';
import { cachedFaqData } from '../components/ShadowApp';

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