import React from 'react';
import Feedback from './Feedback';
import RelatedQuestions from './RelatedQuestions';

export const FaqAnswer = ({
	faq,
	group,
	onQuestionClick,
	feedbackConfig,
	relatedConfig,
	ajaxUrl,
	nonce,
	onAskClick,
	askConfig,
}) => {
	return (
		<div className="faq-answer">
			<h2>{faq.question}</h2>
			<div
				className="faq-answer-content"
				dangerouslySetInnerHTML={{ __html: faq.answer }}
			/>

			{askConfig && askConfig.enabled && (
				<button className="ask-question-footer-btn" type="button" onClick={onAskClick}>
					<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
						<circle cx="12" cy="12" r="10" />
						<line x1="12" y1="8" x2="12" y2="12" />
						<line x1="12" y1="16" x2="12.01" y2="16" />
					</svg>
					{askConfig.button_label || 'Ask a Question'}
				</button>
			)}

			{feedbackConfig && feedbackConfig.enabled && (
				<Feedback
					faq={faq}
					config={feedbackConfig}
					ajaxUrl={ajaxUrl}
					nonce={nonce}
				/>
			)}

			{relatedConfig && relatedConfig.enabled && group && (
				<RelatedQuestions
					currentFaq={faq}
					group={group}
					count={relatedConfig.count}
					title={relatedConfig.title}
					onQuestionClick={onQuestionClick}
				/>
			)}
		</div>
	);
};
