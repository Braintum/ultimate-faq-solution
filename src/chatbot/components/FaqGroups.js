import React from 'react';
import { ListItem } from './ListItem';
import SearchBar from './SearchBar';
import SearchResults from './SearchResults';

export const FaqGroups = ({
	faqData,
	onGroupClick,
	description,
	faqs_count_text,
	searchQuery,
	onSearchChange,
	onResultClick,
	onAskClick,
	searchConfig,
	askConfig,
}) => {
	const searchEnabled = searchConfig && searchConfig.enabled;

	// Build flat search index from all groups
	const searchResults = React.useMemo(() => {
		if (!searchQuery || !searchQuery.trim() || !searchEnabled) return null;

		const q = searchQuery.toLowerCase().trim();
		const results = [];

		faqData.forEach((group) => {
			if (!group.items) return;
			group.items.forEach((item) => {
				const inQuestion = item.question.toLowerCase().includes(q);
				const inAnswer = searchConfig.search_in_answers
					? (item.answer || '').replace(/<[^>]*>/g, '').toLowerCase().includes(q)
					: false;

				if (inQuestion || inAnswer) {
					results.push({
						question: item.question,
						answer: item.answer,
						groupName: group.group,
						groupData: group,
					});
				}
			});
		});

		return results;
	}, [searchQuery, faqData, searchEnabled, searchConfig]);

	const isSearching = searchEnabled && searchQuery && searchQuery.trim().length > 0;

	return (
		<div className="faq-groups">
			{searchEnabled && (
				<SearchBar
					value={searchQuery}
					onChange={onSearchChange}
					placeholder={searchConfig.placeholder}
				/>
			)}

			{isSearching ? (
				<SearchResults
					results={searchResults}
					query={searchQuery}
					onResultClick={onResultClick}
					noResultsText={searchConfig.no_results_text}
					onAskClick={onAskClick}
					askEnabled={askConfig && askConfig.enabled}
					askButtonLabel={askConfig && askConfig.button_label}
				/>
			) : (
				<>
					{description && (
						<div
							className="faq-groups-description"
							dangerouslySetInnerHTML={{ __html: description }}
						/>
					)}
					<div className="list-items">
						{faqData.map((group, index) => (
							<ListItem
								key={index}
								item={group}
								index={index}
								onClick={onGroupClick}
								content={group.group}
								faqs_count_text={faqs_count_text}
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
				</>
			)}
		</div>
	);
};
