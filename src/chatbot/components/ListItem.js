import React from 'react';

const ChevronRight = () => (
	<svg className="list-item-chevron" width="16" height="16" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
		<polyline points="7 5 13 10 7 15" />
	</svg>
);

export const ListItem = ({ item, index, onClick, content, faqs_count_text }) => {
	const faqsCountText = faqs_count_text && item.items
		? faqs_count_text.replace('[count]', item.items.length)
		: undefined;

	return (
		<div
			key={index}
			className="list-item"
			onClick={() => onClick(item)}
			role="button"
			tabIndex={0}
			onKeyDown={(e) => e.key === 'Enter' && onClick(item)}
		>
			<div className="list-item-body">
				<div className="title">{content}</div>
				{faqsCountText && (
					<div className="description">{faqsCountText}</div>
				)}
			</div>
			<ChevronRight />
		</div>
	);
};
