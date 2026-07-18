import React from 'react';

const ChevronRight = () => (
	<svg width="13" height="13" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
		<polyline points="7 5 13 10 7 15" />
	</svg>
);

const RelatedQuestions = ({ currentFaq, group, count, title, onQuestionClick }) => {
	if (!group || !group.items) return null;

	const related = group.items
		.filter((item) => item.question !== currentFaq.question)
		.slice(0, count || 3);

	if (!related.length) return null;

	return (
		<div className="related-questions">
			<h4>{title || 'Related Questions'}</h4>
			<ul>
				{related.map((item, i) => (
					<li
						key={i}
						onClick={() => onQuestionClick(item)}
						role="button"
						tabIndex={0}
						onKeyDown={(e) => e.key === 'Enter' && onQuestionClick(item)}
					>
						<span>{item.question}</span>
						<ChevronRight />
					</li>
				))}
			</ul>
		</div>
	);
};

export default RelatedQuestions;
