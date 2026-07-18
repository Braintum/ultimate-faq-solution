const submitFeedback = async ({ ajaxUrl, nonce, question, vote }) => {
	try {
		const response = await fetch(ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: new URLSearchParams({
				action: 'ufaqsw_faq_feedback',
				nonce,
				question,
				vote,
			}),
		});
		return await response.json();
	} catch {
		return { success: false };
	}
};

export default submitFeedback;
