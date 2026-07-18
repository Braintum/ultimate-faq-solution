import React, { useState, useEffect, useRef, useMemo } from 'react';
import { FaqGroups } from './FaqGroups';
import { FaqList } from './FaqList';
import { FaqAnswer } from './FaqAnswer';
import { Header } from './Header';
import SkeletonLoader from './SkeletonLoader';
import AskQuestion from './AskQuestion';
import Bottom from './bottom/Bottom';
import fetchData from '../models/fetchData';
import handleResize from '../functions/handleResize';
import adjustHeight from '../functions/adjustHeight';

// Module-level cache shared with fetchData.js
let cachedFaqData = null;

const ShadowApp = ({ onClose, shadowRoot }) => {
	const cfg = window.chatbotData || {};

	// View state: 'group' | 'list' | 'answer' | 'ask'
	const [view, setView] = useState('group');
	const [selectedGroup, setSelectedGroup] = useState(null);
	const [selectedFaq, setSelectedFaq] = useState(null);
	const [faqData, setFaqData] = useState([]);
	const [loading, setLoading] = useState(false);
	const [animateOpen, setAnimateOpen] = useState(false);
	const [searchQuery, setSearchQuery] = useState('');
	const [slideDirection, setSlideDirection] = useState(null);
	const [viewKey, setViewKey] = useState(0);
	const [fromSearch, setFromSearch] = useState(false);
	const [prevView, setPrevView] = useState('group');

	const chatbotContainerRef = useRef(null);

	// Navigate to a new view with optional slide direction and state updates
	const navigate = (newView, direction = 'right', updates = {}) => {
		setSlideDirection(direction);
		setViewKey((k) => k + 1);
		setView(newView);
		if ('group' in updates) setSelectedGroup(updates.group);
		if ('faq' in updates) setSelectedFaq(updates.faq);
	};

	// Fetch FAQ data on mount
	useEffect(() => {
		const ajaxUrl = cfg.ajaxUrl || '';
		const nonce = cfg.nonce || '';
		if (ajaxUrl && nonce) {
			if (cachedFaqData) {
				setFaqData(cachedFaqData);
			} else {
				fetchData({ setLoading, ajaxUrl, setFaqData, nonce });
			}
		}
		setAnimateOpen(true);
	}, []);

	// Scroll to top when view changes
	useEffect(() => {
		if (chatbotContainerRef.current) {
			chatbotContainerRef.current.scrollTop = 0;
		}
	}, [view, selectedFaq]);

	// Responsive height adjustment
	useEffect(() => {
		const handler = () => adjustHeight(shadowRoot);
		handler();
		window.addEventListener('resize', handler);
		return () => window.removeEventListener('resize', handler);
	}, [shadowRoot]);

	useEffect(() => {
		const handler = () => handleResize(shadowRoot);
		handler();
		window.addEventListener('resize', handler);
		return () => window.removeEventListener('resize', handler);
	}, [shadowRoot]);

	// Keyboard: close on Escape
	useEffect(() => {
		const handler = (e) => {
			if (e.key === 'Escape') onClose();
		};
		window.addEventListener('keydown', handler);
		return () => window.removeEventListener('keydown', handler);
	}, [onClose]);

	// --- Navigation handlers ---

	const handleGroupClick = (group) => {
		setSearchQuery('');
		navigate('list', 'right', { group });
	};

	const handleListClick = (faq) => {
		navigate('answer', 'right', { faq });
	};

	const handleSearchResultClick = (result) => {
		setFromSearch(true);
		setSearchQuery('');
		navigate('answer', 'right', { group: result.groupData, faq: result });
	};

	const handleRelatedQuestionClick = (faq) => {
		navigate('answer', 'right', { faq });
	};

	const handleAskClick = () => {
		setPrevView(view); // remember where we came from so Back returns correctly
		navigate('ask', 'right');
	};

	const handleBack = () => {
		if (view === 'answer') {
			if (fromSearch) {
				setFromSearch(false);
				navigate('group', 'left', { group: null, faq: null });
			} else {
				navigate('list', 'left', { faq: null });
			}
		} else if (view === 'list') {
			navigate('group', 'left', { group: null });
		} else if (view === 'ask') {
			navigate(prevView, 'left');
		}
	};

	const handleBreadcrumbClick = (targetView) => {
		if (targetView === 'group') {
			navigate('group', 'left', { group: null, faq: null });
		} else if (targetView === 'list') {
			navigate('list', 'left', { faq: null });
		}
	};

	// --- Breadcrumbs ---
	const breadcrumbs = useMemo(() => {
		const home = { label: cfg.assistant_window_headline || 'Home', targetView: null };

		if (view === 'group' || view === 'ask') {
			if (view === 'ask') {
				return [
					{ ...home, targetView: 'group' },
					{ label: cfg.ask_form_title || 'Ask a Question', targetView: null },
				];
			}
			return [home];
		}

		if (view === 'list' && selectedGroup) {
			return [
				{ ...home, targetView: 'group' },
				{ label: selectedGroup.group, targetView: null },
			];
		}

		if (view === 'answer' && selectedGroup) {
			if (fromSearch) {
				return [
					{ ...home, targetView: 'group' },
					{ label: selectedGroup.group, targetView: null },
				];
			}
			return [
				{ ...home, targetView: 'group' },
				{ label: selectedGroup.group, targetView: 'list' },
			];
		}

		return [home];
	}, [view, selectedGroup, fromSearch, cfg.assistant_window_headline, cfg.ask_form_title]);

	// --- Header title ---
	const getHeaderTitle = () => {
		if (view === 'list' && selectedGroup) return selectedGroup.group;
		if (view === 'answer' && selectedGroup) return selectedGroup.group;
		if (view === 'ask') return cfg.ask_form_title || 'Ask a Question';
		return cfg.assistant_window_headline || 'Frequently Asked Questions';
	};

	const showBottom = cfg.bottom_text && view !== 'ask';

	return (
		<div
			className={`chatbot-container${animateOpen ? ' chatbot-open-animate' : ''}`}
			role="dialog"
			aria-modal="true"
			aria-label={cfg.assistant_window_headline || 'FAQ Assistant'}
		>
			<Header
				onClose={onClose}
				onBack={handleBack}
				showBackButton={view !== 'group'}
				title={getHeaderTitle()}
				intro={view === 'group' ? (cfg.assistant_window_intro_text || '') : ''}
				headerColor={cfg.header_background_color}
				textColor={cfg.header_text_color}
				back_button_label={cfg.back_button_label}
				close_button_label={cfg.close_button_label}
				breadcrumbs={breadcrumbs}
				onBreadcrumbClick={handleBreadcrumbClick}
			/>

			<div className="chatbot-body" ref={chatbotContainerRef}>
				{loading ? (
					<SkeletonLoader />
				) : (
					<div
						key={viewKey}
						className={`view-content${slideDirection ? ` slide-from-${slideDirection}` : ''}`}
						onAnimationEnd={() => setSlideDirection(null)}
					>
						{view === 'group' && (
							<FaqGroups
								faqData={faqData}
								onGroupClick={handleGroupClick}
								description={cfg.body_text}
								faqs_count_text={cfg.faqs_count_text || '[count] Frequently Asked Questions'}
								searchQuery={searchQuery}
								onSearchChange={setSearchQuery}
								onResultClick={handleSearchResultClick}
								onAskClick={handleAskClick}
								searchConfig={{
									enabled: !!cfg.search_enabled,
									placeholder: cfg.search_placeholder,
									no_results_text: cfg.search_no_results_text,
									search_in_answers: !!cfg.search_in_answers,
								}}
								askConfig={{
									enabled: !!cfg.ask_enabled,
									button_label: cfg.ask_button_label,
								}}
							/>
						)}

						{view === 'list' && selectedGroup && (
							<FaqList
								group={selectedGroup}
								onListClick={handleListClick}
								onAskClick={handleAskClick}
								askConfig={{
									enabled: !!cfg.ask_enabled,
									button_label: cfg.ask_button_label,
								}}
							/>
						)}

						{view === 'answer' && selectedFaq && (
							<FaqAnswer
								faq={selectedFaq}
								group={selectedGroup}
								onQuestionClick={handleRelatedQuestionClick}
								feedbackConfig={{
									enabled: !!cfg.feedback_enabled,
									label: cfg.feedback_label,
									helpful_text: cfg.feedback_helpful_text,
									not_helpful_text: cfg.feedback_not_helpful_text,
									thanks_text: cfg.feedback_thanks_text,
								}}
								relatedConfig={{
									enabled: !!cfg.related_enabled,
									title: cfg.related_title,
									count: parseInt(cfg.related_count, 10) || 3,
								}}
								ajaxUrl={cfg.ajaxUrl}
								nonce={cfg.nonce}
								onAskClick={handleAskClick}
								askConfig={{
									enabled: !!cfg.ask_enabled,
									button_label: cfg.ask_button_label,
								}}
							/>
						)}

						{view === 'ask' && (
							<AskQuestion
								config={{
									form_title: cfg.ask_form_title,
									gdpr_enabled: !!cfg.ask_gdpr_enabled,
									gdpr_text: cfg.ask_gdpr_text,
									gdpr_error: cfg.ask_gdpr_error,
									success_title: cfg.ask_success_title,
									success_message: cfg.ask_success_message,
									back_to_faqs_label: cfg.ask_back_to_faqs_label,
									name_label: cfg.ask_name_label,
									email_label: cfg.ask_email_label,
									question_label: cfg.ask_question_label,
									submit_label: cfg.ask_submit_label,
									cancel_label: cfg.ask_cancel_label,
								}}
								ajaxUrl={cfg.ajaxUrl}
								nonce={cfg.nonce}
								onCancel={() => navigate(prevView, 'left')}
								onSuccess={() => navigate('group', 'left')}
							/>
						)}
					</div>
				)}
			</div>

			{showBottom && <Bottom text={cfg.bottom_text} />}
		</div>
	);
};

export { ShadowApp, cachedFaqData };
