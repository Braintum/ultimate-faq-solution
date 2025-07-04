import React, { useState, useEffect } from 'react';
import { FaqGroups } from './FaqGroups';
import { FaqList } from './FaqList';
import { FaqAnswer } from './FaqAnswer';
import { Header } from './Header';
import Preloader from './Preloader';
import Bottom from './bottom/Bottom';
import fetchData from '../models/fetchData';
import getTitle from '../functions/getTitle';
import handleBackClick from '../functions/handleBackClick';
import handleQuestionClick from '../functions/handleQuestionClick';
import handleGroupClick from '../functions/handleGroupClick';
import handleResize from '../functions/handleResize';
import adjustHeight from '../functions/adjustHeight';

// Global variable to cache FAQ data
let cachedFaqData = null;

/**
 * ShadowApp is the main component for the chatbot UI rendered inside a Shadow DOM.
 * It manages the view state (group, list, answer), fetches FAQ data, and handles UI transitions and animations.
 * The component also applies dynamic styles and text based on global configuration provided via `window.chatbotData`.
 *
 * @component
 * @param {Object} props - The component props.
 * @param {Function} props.onClose - Callback function to close the chatbot window.
 * @param {ShadowRoot} props.shadowRoot - The ShadowRoot instance where the component is rendered.
 *
 * @returns {JSX.Element} The rendered chatbot UI.
 */
const ShadowApp = ({ onClose, shadowRoot }) => {
	const [view, setView] = useState('group'); // 'group', 'list', or 'answer'
	const [selectedGroup, setSelectedGroup] = useState(null);
	const [selectedFaq, setSelectedFaq] = useState(null);
	const [faqData, setFaqData] = useState([]); // Store fetched FAQ data
	const [ajaxUrl, setAjaxUrl] = useState('');
	const [assistantWindowHeadline, setAssistantWindowHeadline] = useState('');
	const [assistantWindowIntroText, setAssistantWindowIntroText] = useState('');
	const [headerBackgroundColor, setHeaderBackgroundColor] = useState('');
	const [headerTextColor, setHeaderTextColor] = useState('');
	const [bodyText, setBodyText] = useState('');
	const [nonce, setNonce] = useState('');
	const [loading, setLoading] = useState(false); // Track loading state
	const [animateOpen, setAnimateOpen] = useState(false);

	const chatbotContainerRef = React.useRef(null);

	useEffect(() => {
		// Access the global chatbotData object
		if (window.chatbotData) {
			setAjaxUrl(window.chatbotData.ajaxUrl);
			setNonce(window.chatbotData.nonce);
			setAssistantWindowHeadline(window.chatbotData.assistant_window_headline);
			setAssistantWindowIntroText(window.chatbotData.assistant_window_intro_text);
			setHeaderBackgroundColor(window.chatbotData.header_background_color)
			setHeaderTextColor(window.chatbotData.header_text_color)
			setBodyText(window.chatbotData.body_text)
		}

		// fetch data when the component mounts
		if (ajaxUrl && nonce) {
			if (cachedFaqData) {
				// Use cached data if available
				setFaqData(cachedFaqData);
			} else {
				// Fetch data from the server if not cached
				fetchData({ setLoading, ajaxUrl, setFaqData, nonce });
			}
		}

		// Trigger open animation on mount
		setAnimateOpen(true);

		// Scroll to top when an FAQ is selected
		if (view === 'answer' && chatbotContainerRef.current) {
			chatbotContainerRef.current.scrollTop = 0;
		}
		if (view === 'list' && chatbotContainerRef.current) {
			chatbotContainerRef.current.scrollTop = 0;
		}

	}, [ajaxUrl, nonce, view]);

	useEffect(() => {
		const adjustHeightHandler = () => adjustHeight(shadowRoot);

		adjustHeightHandler(); // Adjust height on initial load
		window.addEventListener('resize', adjustHeightHandler); // Adjust height on window resize

		return () => {
			window.removeEventListener('resize', adjustHeightHandler); // Cleanup event listener
		};
	}, [shadowRoot]);

	useEffect(() => {
		const resizeHandler = () => handleResize(shadowRoot);

		resizeHandler(); // Initial call
		window.addEventListener('resize', resizeHandler);

		return () => {
			window.removeEventListener('resize', resizeHandler);
		};
	}, [shadowRoot]);

	return (
		<div className={`chatbot-container${animateOpen ? ' chatbot-open-animate' : ''}`}>
			<Header
				onClose={onClose}
				onBack={() => handleBackClick({ view, setView, setSelectedGroup, setSelectedFaq })}
				showBackButton={view !== 'group'}
				title={getTitle({ view, selectedGroup, selectedFaq, assistantWindowHeadline })}
				intro={view == 'group' ? assistantWindowIntroText : ""}
				headerColor={headerBackgroundColor}
				textColor={headerTextColor}
				back_button_label={window.chatbotData.back_button_label || 'Back'}
				close_button_label={window.chatbotData.close_button_label || 'Close'}
			/>

			<div className="chatbot-body" ref={chatbotContainerRef}>
				{loading ? (
					<Preloader 
						dotColor={ window.chatbotData.loading_animation_color || '#222' }
						preloader_text={window.chatbotData.preloader_text}
					/>
				) : (
					<>
						{view === 'group' && (
							<FaqGroups
								faqData={faqData}
								onGroupClick={(group) => handleGroupClick({
									group, 
									setSelectedGroup, 
									setView 
								})}
								description={bodyText}
								faqs_count_text={window.chatbotData.faqs_count_text || '[count] Frequently Asked Questions'}
							/>
						)}

						{view === 'list' && selectedGroup && (
							<FaqList
								group={selectedGroup}
								onListClick={(faq) => handleQuestionClick({ 
									faq, 
									setSelectedFaq, 
									setView 
								})}
							/>
						)}

						{view === 'answer' && selectedFaq && (
							<FaqAnswer faq={selectedFaq} />
						)}

						{ window.chatbotData.bottom_text && <Bottom text={window.chatbotData.bottom_text} /> }
						
					</>
				)}
			</div>
		</div>
	);
};

export { ShadowApp, cachedFaqData };