import React from 'react';
import { ListItem } from './ListItem';

export const FaqList = ({ group, onListClick, onAskClick, askConfig }) => {
	return (
		<div className="faq-groups">
			{group.description && (
				<div
					className="faq-groups-description"
					dangerouslySetInnerHTML={{ __html: group.description }}
				/>
			)}
			<div className="list-items">
				{group.items.map((faq, index) => (
					<ListItem
						key={index}
						item={faq}
						index={index}
						onClick={onListClick}
						content={faq.question}
					/>
				))}
			</div>

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
		</div>
	);
};
