import React, { useState, useEffect } from 'react';
import { FaqGroups } from './FaqGroups';
import { FaqList } from './FaqList';
import { FaqAnswer } from './FaqAnswer';
import { Header } from './Header';
import Preloader from './Preloader';
import Bottom from './bottom/Bottom';

// Global variable to cache FAQ data
let cachedFaqData = null;

export const ShadowApp = ({ onClose, shadowRoot }) => {
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
	}, []);

	const fetchData = async () => {
		setLoading(true); // Start loading
		try {
			const response = await fetch(ajaxUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: new URLSearchParams({
					action: 'ufaqsw_get_faqs',
					nonce: nonce,
				}),
			});

			const result = await response.json();
			if (result.success) {
				cachedFaqData = result.data; // Cache the fetched data
				setFaqData(result.data); // Update state with fetched data
			} else {
				console.error('Error fetching FAQs:', result);
			}
		} catch (error) {
			console.error('Error fetching data:', error);
		} finally {
			setLoading(false); // Stop loading
		}
	};

	useEffect(() => {
		if (ajaxUrl && nonce) {
			if (cachedFaqData) {
				// Use cached data if available
				setFaqData(cachedFaqData);
			} else {
				// Fetch data from the server if not cached
				fetchData();
			}
		}
	}, [ajaxUrl, nonce]);

	useEffect(() => {
		const adjustHeight = () => {
			if (shadowRoot && window.innerWidth > 768) { // Only apply on desktop
				const chatbotContainer = shadowRoot.querySelector('.chatbot-container');
				if (chatbotContainer) {
					chatbotContainer.style.height = `${window.innerHeight * 0.8}px`; // Set height to 80% of window height
				}
			}
		};

		adjustHeight(); // Adjust height on initial load
		window.addEventListener('resize', adjustHeight); // Adjust height on window resize

		return () => {
			window.removeEventListener('resize', adjustHeight); // Cleanup event listener
		};
	}, [shadowRoot]);

	useEffect(() => {
		const handleResize = () => {
			if (shadowRoot) {
				const chatbotContainer = shadowRoot.querySelector('.chatbot-container');
				if (chatbotContainer) {
					if (window.innerWidth <= 768) {
						chatbotContainer.classList.add('full-screen');
					} else {
						chatbotContainer.classList.remove('full-screen');
					}
				}
			}
		};

		handleResize(); // Apply full-screen class on initial load
		window.addEventListener('resize', handleResize); // Adjust on window resize

		return () => {
			window.removeEventListener('resize', handleResize); // Cleanup event listener
		};
	}, [shadowRoot]);

	const handleGroupClick = (group) => {
		setSelectedGroup(group);
		setView('list');
	};

	const handleQuestionClick = (faq) => {
		setSelectedFaq(faq);
		setView('answer');
	};

	const handleBackClick = () => {
		if (view === 'answer') {
			setView('list');
			setSelectedFaq(null);
		} else if (view === 'list') {
			setView('group');
			setSelectedGroup(null);
		}
	};

	const getTitle = () => {
		if (view === 'list' && selectedGroup) {
			return selectedGroup.group;
		}
		if (view === 'answer' && selectedFaq) {
			return selectedGroup.group;
		}
		return assistantWindowHeadline || 'Frequently Asked Questions'; // Default title if no specific group or FAQ is selected
	};

	return (
		<div className="chatbot-container">
			<Header
				onClose={onClose}
				onBack={handleBackClick}
				showBackButton={view !== 'group'}
				title={getTitle()}
				intro={view == 'group' ? assistantWindowIntroText : ""}
				headerColor={headerBackgroundColor}
				textColor={headerTextColor}
				back_button_label={window.chatbotData.back_button_label || 'Back'}
				close_button_label={window.chatbotData.close_button_label || 'Close'}
			/>

			<div className="chatbot-body">
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
								onGroupClick={handleGroupClick}
								description={bodyText}
								faqs_count_text={window.chatbotData.faqs_count_text || '[count] Frequently Asked Questions'}
							/>
						)}

						{view === 'list' && selectedGroup && (
							<FaqList
								faqs={selectedGroup.items}
								onListClick={handleQuestionClick}
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
