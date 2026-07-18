import React, { useState } from 'react';
import submitFeedback from '../models/submitFeedback';

const Feedback = ({ faq, config, ajaxUrl, nonce }) => {
	const [voted, setVoted] = useState(null);

	const handleVote = async (vote) => {
		setVoted(vote);
		await submitFeedback({ ajaxUrl, nonce, question: faq.question, vote });
	};

	if (voted) {
		return (
			<div className="feedback-thanks" role="status">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
					<polyline points="20 6 9 17 4 12" />
				</svg>
				<span>{config.thanks_text || 'Thank you for your feedback!'}</span>
			</div>
		);
	}

	return (
		<div className="feedback">
			<span className="feedback-label">{config.label || 'Was this helpful?'}</span>
			<div className="feedback-buttons">
				<button
					className="feedback-btn helpful"
					onClick={() => handleVote('helpful')}
					type="button"
					aria-label="Yes, this was helpful"
				>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
						<path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z" />
						<path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3" />
					</svg>
					{config.helpful_text || 'Yes, helpful'}
				</button>
				<button
					className="feedback-btn not-helpful"
					onClick={() => handleVote('not_helpful')}
					type="button"
					aria-label="No, this was not helpful"
				>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
						<path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3H10z" />
						<path d="M17 2h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17" />
					</svg>
					{config.not_helpful_text || 'Not helpful'}
				</button>
			</div>
		</div>
	);
};

export default Feedback;
