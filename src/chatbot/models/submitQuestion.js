const submitQuestion = async ({ ajaxUrl, nonce, name, email, question, honeypot }) => {
	try {
		const response = await fetch(ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: new URLSearchParams({
				action: 'ufaqsw_submit_question',
				nonce,
				name,
				email,
				question,
				page_url: window.location.href,
				website: honeypot || '',
			}),
		});
		return await response.json();
	} catch {
		return { success: false, data: { message: 'Network error. Please try again.' } };
	}
};

export default submitQuestion;
