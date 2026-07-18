import React from 'react';

const ChevronRight = () => (
	<svg width="14" height="14" viewBox="0 0 20 20" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
		<polyline points="7 5 13 10 7 15" />
	</svg>
);

const highlightMatch = (text, query) => {
	if (!query || !query.trim()) return text;
	const escaped = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
	const parts = text.split(new RegExp(`(${escaped})`, 'gi'));
	return parts.map((part, i) =>
		part.toLowerCase() === query.toLowerCase()
			? <mark key={i}>{part}</mark>
			: part
	);
};

const SearchResults = ({ results, query, onResultClick, noResultsText, onAskClick, askEnabled, askButtonLabel }) => {
	if (results.length === 0) {
		const message = noResultsText
			? noResultsText.replace('[query]', query)
			: `No results found for "${query}".`;

		return (
			<div className="search-no-results">
				<svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
					<circle cx="11" cy="11" r="8" />
					<line x1="21" y1="21" x2="16.65" y2="16.65" />
					<line x1="8" y1="11" x2="14" y2="11" />
				</svg>
				<p>{message}</p>
				{askEnabled && (
					<button className="ask-question-trigger" onClick={onAskClick} type="button">
						{askButtonLabel || 'Ask a Question'}
					</button>
				)}
			</div>
		);
	}

	return (
		<div className="search-results">
			{results.map((result, i) => (
				<div
					key={i}
					className="search-result-item"
					onClick={() => onResultClick(result)}
					role="button"
					tabIndex={0}
					onKeyDown={(e) => e.key === 'Enter' && onResultClick(result)}
				>
					<div className="result-body">
						<div className="result-question">
							{highlightMatch(result.question, query)}
						</div>
						<div className="result-group">{result.groupName}</div>
					</div>
					<ChevronRight />
				</div>
			))}
		</div>
	);
};

export default SearchResults;
