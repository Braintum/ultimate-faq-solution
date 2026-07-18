import React from 'react';

const SearchBar = ({ value, onChange, placeholder }) => {
	return (
		<div className="faq-search-bar">
			<svg className="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true">
				<circle cx="11" cy="11" r="8" />
				<line x1="21" y1="21" x2="16.65" y2="16.65" />
			</svg>
			<input
				type="text"
				value={value}
				onChange={(e) => onChange(e.target.value)}
				placeholder={placeholder || 'Search FAQs…'}
				aria-label={placeholder || 'Search FAQs'}
				autoComplete="off"
			/>
			{value && (
				<button
					className="search-clear"
					onClick={() => onChange('')}
					aria-label="Clear search"
					type="button"
				>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round" aria-hidden="true">
						<line x1="18" y1="6" x2="6" y2="18" />
						<line x1="6" y1="6" x2="18" y2="18" />
					</svg>
				</button>
			)}
		</div>
	);
};

export default SearchBar;
